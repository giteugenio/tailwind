<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\User;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * Integration coverage for the public, read-only User area.
 */
class UserAreaTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Articles',
        'app.Tags',
        'app.ArticlesTags',
    ];

    /**
     * Exercise responses without debug SQL panels or exception trace data.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Configure::write('debug', false);
        Configure::write('App.fullBaseUrl', 'http://localhost');
        $this->configRequest(['environment' => ['HTTP_HOST' => 'localhost']]);
    }

    /**
     * Test prefix resolution, its own template/layout, and navigation.
     *
     * @return void
     */
    public function testDashboard(): void
    {
        $this->get('/user');
        $this->assertResponseOk();
        $this->assertTemplate('User/Dashboard/index.php');
        $this->assertLayout('user');
        $this->assertResponseContains('Área de usuario');
        $this->assertResponseContains('href="/user/articles"');
        $this->assertResponseContains('href="/"');

        $routes = [
            '/user' => ['Dashboard', 'index'],
            '/user/articles' => ['Articles', 'index'],
            '/user/articles/view/1' => ['Articles', 'view'],
        ];
        foreach ($routes as $url => [$controller, $action]) {
            $params = Router::parseRequest(new ServerRequest(['url' => $url]));
            $this->assertSame('User', $params['prefix']);
            $this->assertSame($controller, $params['controller']);
            $this->assertSame($action, $params['action']);
            if ($action === 'view') {
                $this->assertSame(['1'], $params['pass']);
            }
        }
    }

    /**
     * Test published-only pagination, newest first, including other authors.
     *
     * @return void
     */
    public function testPublishedArticlesPagination(): void
    {
        $users = $this->getTableLocator()->get('Users');
        $user = $users->newEntity(['email' => 'author@example.test', 'password' => 'test']);
        $users->saveOrFail($user);
        $articles = $this->getTableLocator()->get('Articles');
        for ($i = 1; $i <= 21; $i++) {
            $article = $articles->newEntity([
                'user_id' => $user->id,
                'title' => sprintf('Published %02d', $i),
                'slug' => 'published-' . $i,
                'body' => 'Article content',
                'published' => true,
                'created' => sprintf('2026-12-%02d 12:00:00', $i),
            ]);
            $articles->saveOrFail($article);
        }
        $this->insertDraft();
        $this->get('/user/articles');
        $this->assertResponseOk();
        $this->assertTemplate('User/Articles/index.php');
        $this->assertLayout('user');
        $this->assertResponseContains('Published 21');
        $this->assertResponseContains('author@example.test');
        $this->assertResponseNotContains('Published 01');
        $this->assertResponseNotContains('Private draft');
        $this->assertResponseRegExp('/Published 21.*Published 20/s');
        $this->assertResponseRegExp('~href="/user/articles\?[^"]*page=2~');
        $this->assertReadOnlyNavigation();

        $this->get('/user/articles?page=2');
        $this->assertResponseOk();
        $this->assertResponseContains('Published 01');
        $this->assertResponseNotContains('Published 21');
        $this->assertResponseNotContains('Private draft');
    }

    /**
     * Test the empty state when only drafts exist.
     *
     * @return void
     */
    public function testEmptyList(): void
    {
        $this->getTableLocator()->get('Articles')->updateAll(['published' => false], []);
        $this->get('/user/articles');
        $this->assertResponseOk();
        $this->assertResponseContains('No hay artículos publicados.');
    }

    /**
     * Test detail presentation, associations and escaping of stored text.
     *
     * @return void
     */
    public function testPublishedDetail(): void
    {
        $this->getTableLocator()->get('Articles')->updateAll([
            'title' => '<script>alert("title")</script>',
            'body' => '<script>alert("body")</script>',
        ], ['id' => 1]);
        $this->get('/user/articles/view/1');
        $this->assertResponseOk();
        $this->assertTemplate('User/Articles/view.php');
        $this->assertLayout('user');
        $this->assertResponseContains('&lt;script&gt;');
        $this->assertResponseNotContains('<script>alert(');
        $this->assertResponseContains('Etiquetas');
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
        $this->assertResponseContains('href="/user/articles"');
        $this->assertReadOnlyNavigation();
    }

    /**
     * Test the detail empty tag state.
     *
     * @return void
     */
    public function testDetailWithoutTags(): void
    {
        $this->getTableLocator()->get('ArticlesTags')->deleteAll([]);
        $this->get('/user/articles/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Sin etiquetas.');
    }

    /**
     * Test drafts cannot be accessed directly.
     *
     * @return void
     */
    public function testDraftReturnsNotFound(): void
    {
        $id = $this->insertDraft();
        $this->get('/user/articles/view/' . $id);
        $this->assertResponseCode(404);
        $this->assertResponseNotContains('Private draft');
    }

    /**
     * Test nonexistent records return 404.
     *
     * @return void
     */
    public function testMissingArticleReturnsNotFound(): void
    {
        $this->get('/user/articles/view/999999');
        $this->assertResponseCode(404);
    }

    /**
     * Test invalid ids and unsupported area actions in production.
     *
     * @return void
     */
    public function testUnsupportedRoutesReturnNotFound(): void
    {
        Configure::write('debug', false);
        foreach (['/user/articles/view/abc', '/user/articles/add', '/user/articles/edit/1'] as $url) {
            $this->get($url);
            $this->assertResponseCode(404);
        }
    }

    /**
     * Test existing public routes retain their templates and layout.
     *
     * @return void
     */
    public function testExistingRoutes(): void
    {
        $this->get('/');
        $this->assertResponseOk();
        $this->assertTemplate('Pages/home.php');
        $this->cleanup();
        $this->configRequest(['environment' => ['HTTP_HOST' => 'localhost']]);
        $this->get('/articles');
        $this->assertResponseOk();
        $this->assertTemplate('Articles/index.php');
        $this->assertLayout('default');
    }

    /**
     * Insert a draft without changing the shared fixture definitions.
     *
     * @return int
     */
    protected function insertDraft(): int
    {
        $articles = $this->getTableLocator()->get('Articles');
        $draft = $articles->newEntity([
            'user_id' => 1,
            'title' => 'Private draft',
            'slug' => 'private-draft',
            'body' => 'Private content',
            'published' => false,
        ]);
        $articles->saveOrFail($draft);

        return $draft->id;
    }

    /**
     * Check that presentation does not link to CRUD or unrelated controllers.
     *
     * @return void
     */
    protected function assertReadOnlyNavigation(): void
    {
        $this->assertResponseNotRegExp('~href="[^" ]*/(?:add|edit|delete)(?:/|\?)~');
        $this->assertResponseNotContains('/user/users');
        $this->assertResponseNotContains('/user/tags');
        $this->assertResponseNotContains('<form');
    }
}
