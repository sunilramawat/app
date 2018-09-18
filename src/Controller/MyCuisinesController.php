<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * MyCuisines Controller
 *
 * @property \App\Model\Table\MyCuisinesTable $MyCuisines
 */
class MyCuisinesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Mcs', 'Users']
        ];
        $myCuisines = $this->paginate($this->MyCuisines);

        $this->set(compact('myCuisines'));
        $this->set('_serialize', ['myCuisines']);
    }

    /**
     * View method
     *
     * @param string|null $id My Cuisine id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $myCuisine = $this->MyCuisines->get($id, [
            'contain' => ['Mcs', 'Users']
        ]);

        $this->set('myCuisine', $myCuisine);
        $this->set('_serialize', ['myCuisine']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $myCuisine = $this->MyCuisines->newEntity();
        if ($this->request->is('post')) {
            $myCuisine = $this->MyCuisines->patchEntity($myCuisine, $this->request->data);
            if ($this->MyCuisines->save($myCuisine)) {
                $this->Flash->success(__('The my cuisine has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The my cuisine could not be saved. Please, try again.'));
            }
        }
        $mcs = $this->MyCuisines->Mcs->find('list', ['limit' => 200]);
        $users = $this->MyCuisines->Users->find('list', ['limit' => 200]);
        $this->set(compact('myCuisine', 'mcs', 'users'));
        $this->set('_serialize', ['myCuisine']);
    }

    /**
     * Edit method
     *
     * @param string|null $id My Cuisine id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $myCuisine = $this->MyCuisines->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $myCuisine = $this->MyCuisines->patchEntity($myCuisine, $this->request->data);
            if ($this->MyCuisines->save($myCuisine)) {
                $this->Flash->success(__('The my cuisine has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The my cuisine could not be saved. Please, try again.'));
            }
        }
        $mcs = $this->MyCuisines->Mcs->find('list', ['limit' => 200]);
        $users = $this->MyCuisines->Users->find('list', ['limit' => 200]);
        $this->set(compact('myCuisine', 'mcs', 'users'));
        $this->set('_serialize', ['myCuisine']);
    }

    /**
     * Delete method
     *
     * @param string|null $id My Cuisine id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $myCuisine = $this->MyCuisines->get($id);
        if ($this->MyCuisines->delete($myCuisine)) {
            $this->Flash->success(__('The my cuisine has been deleted.'));
        } else {
            $this->Flash->error(__('The my cuisine could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
