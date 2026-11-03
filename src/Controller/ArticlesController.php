<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\BadRequestException;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Articles->find()
            ->contain(['Users']);
        $articles = $this->paginate($query);

        $this->set(compact('articles'));
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $article = $this->Articles->get($id, contain: ['Users', 'Tags']);
        $this->set(compact('article'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        $role = $this->request->is('post') ? $this->request->getData('user_role', 'user') : 'user';
        if (!is_string($role) || !in_array($role, ['user', 'editor'], true)) {
            throw new BadRequestException('Invalid user role');
        }
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            if (!$article->user_id || !$this->Articles->Users->exists(['id' => $article->user_id, 'role' => $role])) {
                $article->setError('user_id', __('Select a user with the chosen role.'));
            }
            if (!$article->hasErrors() && $this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }
        $users = $this->Articles->Users->find('list', limit: 200)->where(['Users.role' => $role])->all();
        $tags = $this->Articles->Tags->find('list', limit: 200)->all();
        $this->set(compact('article', 'users', 'tags', 'role'));
    }

    /**
     * Return the user selector for the selected role.
     *
     * @return void
     */
    public function usersByRole(): void
    {
        $this->request->allowMethod(['get']);
        $role = $this->request->getQuery('user_role', 'user');
        if (!is_string($role) || !in_array($role, ['user', 'editor'], true)) {
            throw new BadRequestException('Invalid user role');
        }
        $users = $this->Articles->Users->find('list', limit: 200)->where(['Users.role' => $role])->all();
        $article = $this->Articles->newEmptyEntity();
        $this->set(compact('users', 'article'));
        $this->viewBuilder()->disableAutoLayout();
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $article = $this->Articles->get($id, contain: ['Tags']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('The article has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }
        $users = $this->Articles->Users->find('list', limit: 200)->all();
        $tags = $this->Articles->Tags->find('list', limit: 200)->all();
        $this->set(compact('article', 'users', 'tags'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article has been deleted.'));
        } else {
            $this->Flash->error(__('The article could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
