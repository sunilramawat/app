<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Reviews Controller
 *
 * @property \App\Model\Table\ReviewsTable $Reviews
 */
class ReviewsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Rs', 'MyCuisines']
        ];
        $reviews = $this->paginate($this->Reviews);

        $this->set(compact('reviews'));
        $this->set('_serialize', ['reviews']);
    }

    /**
     * View method
     *
     * @param string|null $id Review id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $review = $this->Reviews->get($id, [
            'contain' => ['Rs', 'MyCuisines']
        ]);

        $this->set('review', $review);
        $this->set('_serialize', ['review']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $review = $this->Reviews->newEntity();
        if ($this->request->is('post')) {
            $review = $this->Reviews->patchEntity($review, $this->request->data);
            if ($this->Reviews->save($review)) {
                $this->Flash->success(__('The review has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The review could not be saved. Please, try again.'));
            }
        }
        $rs = $this->Reviews->Rs->find('list', ['limit' => 200]);
        $myCuisines = $this->Reviews->MyCuisines->find('list', ['limit' => 200]);
        $this->set(compact('review', 'rs', 'myCuisines'));
        $this->set('_serialize', ['review']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Review id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $review = $this->Reviews->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $review = $this->Reviews->patchEntity($review, $this->request->data);
            if ($this->Reviews->save($review)) {
                $this->Flash->success(__('The review has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The review could not be saved. Please, try again.'));
            }
        }
        $rs = $this->Reviews->Rs->find('list', ['limit' => 200]);
        $myCuisines = $this->Reviews->MyCuisines->find('list', ['limit' => 200]);
        $this->set(compact('review', 'rs', 'myCuisines'));
        $this->set('_serialize', ['review']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Review id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $review = $this->Reviews->get($id);
        if ($this->Reviews->delete($review)) {
            $this->Flash->success(__('The review has been deleted.'));
        } else {
            $this->Flash->error(__('The review could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
