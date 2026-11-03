<?php
declare(strict_types=1);

namespace App\Controller\User;

/**
 * Read-only published articles.
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{
    /**
     * List published articles, newest first.
     *
     * @return void
     */
    public function index(): void
    {
        $query = $this->Articles->find()
            ->where(['Articles.published' => true])
            ->contain(['Users']);
        $articles = $this->paginate($query, [
            'order' => ['Articles.created' => 'DESC', 'Articles.id' => 'DESC'],
        ]);

        $this->set(compact('articles'));
    }

    /**
     * Show a published article, returning 404 for drafts and missing records.
     *
     * @param string $id Article id.
     * @return void
     */
    public function view(string $id): void
    {
        $article = $this->Articles->find()
            ->where(['Articles.id' => $id, 'Articles.published' => true])
            ->contain(['Users', 'Tags'])
            ->firstOrFail();

        $this->set(compact('article'));
    }
}
