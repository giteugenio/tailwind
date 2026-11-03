<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ArticlesTagsController Test Case
 *
 * @link \App\Controller\ArticlesTagsController
 */
class ArticlesTagsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.ArticlesTags',
        'app.Articles',
        'app.Tags',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \App\Controller\ArticlesTagsController::index()
     */
    public function testIndex(): void
    {
        $this->get('/articles-tags');
        $this->assertResponseOk();
        $this->assertResponseContains('/articles-tags/view/1/1');
        $this->assertResponseContains('/articles-tags/edit/1/1');
        $this->assertResponseContains('/articles-tags/delete/1/1');
        $this->assertResponseContains('/css/app.css');
    }

    /**
     * Test view method
     *
     * @return void
     * @link \App\Controller\ArticlesTagsController::view()
     */
    public function testView(): void
    {
        $this->get('/articles-tags/view/1/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }

    /**
     * Test add method
     *
     * @return void
     * @link \App\Controller\ArticlesTagsController::add()
     */
    public function testAdd(): void
    {
        $this->enableCsrfToken();
        $this->get('/articles-tags/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<select name="article_id"');
        $this->assertResponseContains('<select name="tag_id"');
        $this->post('/articles-tags/add', ['article_id' => 1, 'tag_id' => $this->createTag()]);
        $this->assertRedirect('/articles-tags');
        $this->assertSame(2, $this->getTableLocator()->get('ArticlesTags')->find()->count());
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \App\Controller\ArticlesTagsController::edit()
     */
    public function testEdit(): void
    {
        $tagId = $this->createTag();
        $this->enableCsrfToken();
        $this->get('/articles-tags/edit/1/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<select name="article_id"');
        $this->assertResponseContains('<select name="tag_id"');
        $this->post('/articles-tags/edit/1/1', ['article_id' => 1, 'tag_id' => $tagId]);
        $this->assertRedirect('/articles-tags');
        $table = $this->getTableLocator()->get('ArticlesTags');
        $this->assertFalse($table->exists(['article_id' => 1, 'tag_id' => 1]));
        $this->assertTrue($table->exists(['article_id' => 1, 'tag_id' => $tagId]));
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \App\Controller\ArticlesTagsController::delete()
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->post('/articles-tags/delete/1/1');
        $this->assertRedirect('/articles-tags');
        $this->assertFalse($this->getTableLocator()->get('ArticlesTags')->exists([
            'article_id' => 1,
            'tag_id' => 1,
        ]));
    }

    public function testInvalidEditPreservesOriginalAssociation(): void
    {
        $this->enableCsrfToken();
        $this->post('/articles-tags/edit/1/1', ['article_id' => 1, 'tag_id' => 999999]);
        $this->assertResponseOk();
        $this->assertResponseContains('alert-error');
        $this->assertResponseContains('This value does not exist');
        $this->assertResponseContains('action="/articles-tags/edit/1/1"');
        $this->assertTrue($this->getTableLocator()->get('ArticlesTags')->exists([
            'article_id' => 1,
            'tag_id' => 1,
        ]));
    }

    public function testDuplicateAddDisplaysError(): void
    {
        $this->enableCsrfToken();
        $this->post('/articles-tags/add', ['article_id' => 1, 'tag_id' => 1]);
        $this->assertResponseOk();
        $this->assertResponseContains('alert-error');
        $this->assertSame(1, $this->getTableLocator()->get('ArticlesTags')->find()->count());
    }

    public function testDuplicateEditPreservesBothAssociations(): void
    {
        $tagId = $this->createTag();
        $table = $this->getTableLocator()->get('ArticlesTags');
        $table->saveOrFail($table->newEntity(['article_id' => 1, 'tag_id' => $tagId]));
        $this->enableCsrfToken();
        $this->post('/articles-tags/edit/1/1', ['article_id' => 1, 'tag_id' => $tagId]);
        $this->assertResponseOk();
        $this->assertResponseContains('alert-error');
        $this->assertTrue($table->exists(['article_id' => 1, 'tag_id' => 1]));
        $this->assertTrue($table->exists(['article_id' => 1, 'tag_id' => $tagId]));
        $this->assertSame(2, $table->find()->count());
    }

    public function testDeleteRequiresPost(): void
    {
        $this->get('/articles-tags/delete/1/1');
        $this->assertResponseCode(405);
        $this->assertSame(1, $this->getTableLocator()->get('ArticlesTags')->find()->count());
    }

    public function testIncompleteKeyReturnsNotFound(): void
    {
        $this->get('/articles-tags/view/1');
        $this->assertResponseCode(404);
    }

    protected function createTag(): int
    {
        $table = $this->getTableLocator()->get('Tags');
        $tag = $table->saveOrFail($table->newEntity(['title' => 'Additional tag']));

        return $tag->id;
    }
}
