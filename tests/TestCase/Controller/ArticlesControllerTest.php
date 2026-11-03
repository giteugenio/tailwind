<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ArticlesController Test Case
 *
 * @link \App\Controller\ArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Articles',
        'app.Users',
        'app.Tags',
        'app.ArticlesTags',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \App\Controller\ArticlesController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @link \App\Controller\ArticlesController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @link \App\Controller\ArticlesController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \App\Controller\ArticlesController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \App\Controller\ArticlesController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testRoleFilteredUserSelector(): void
    {
        $this->getTableLocator()->get('Users')->insertQuery()->insert(['email', 'password', 'role', 'created', 'modified'])
            ->values(['email' => 'editor@example.test', 'password' => 'test', 'role' => 'editor',
                'created' => '2026-01-01 00:00:00', 'modified' => '2026-01-01 00:00:00'])->execute();
        $this->get('/articles/users-by-role?user_role=editor');
        $this->assertResponseOk();
        $this->assertResponseContains('editor@example.test');
        $this->assertResponseNotContains('Lorem ipsum dolor sit amet');
        $this->assertResponseNotContains('<html');
    }

    public function testInvalidRoleIsRejected(): void
    {
        $this->get('/articles/users-by-role?user_role=admin');
        $this->assertResponseCode(400);
    }

    public function testArticleRejectsUserFromAnotherRole(): void
    {
        $this->enableCsrfToken();
        $this->post('/articles/add', [
            'user_role' => 'editor', 'user_id' => 1, 'title' => 'Test',
            'slug' => 'test', 'body' => 'Test', 'published' => 1,
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('Select a user with the chosen role.');
        $this->assertSame(1, $this->getTableLocator()->get('Articles')->find()->count());
    }

    public function testArticleRequiresUser(): void
    {
        $this->enableCsrfToken();
        $this->post('/articles/add', ['user_role' => 'user', 'user_id' => '', 'title' => 'Test']);
        $this->assertResponseOk();
        $this->assertResponseContains('Select a user with the chosen role.');
    }
}
