<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ExtraItems Controller
 *
 * @property \App\Model\Table\ExtraItemsTable $ExtraItems
 */
class ExtraItemsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Eis', 'MyCuisines', 'Users']
        ];
        $extraItems = $this->paginate($this->ExtraItems);

        $this->set(compact('extraItems'));
        $this->set('_serialize', ['extraItems']);
    }

    /**
     * View method
     *
     * @param string|null $id Extra Item id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $extraItem = $this->ExtraItems->get($id, [
            'contain' => ['Eis', 'MyCuisines', 'Users']
        ]);

        $this->set('extraItem', $extraItem);
        $this->set('_serialize', ['extraItem']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $extraItem = $this->ExtraItems->newEntity();
        if ($this->request->is('post')) {
            $extraItem = $this->ExtraItems->patchEntity($extraItem, $this->request->data);
            if ($this->ExtraItems->save($extraItem)) {
                $this->Flash->success(__('The extra item has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The extra item could not be saved. Please, try again.'));
            }
        }
        $eis = $this->ExtraItems->Eis->find('list', ['limit' => 200]);
        $myCuisines = $this->ExtraItems->MyCuisines->find('list', ['limit' => 200]);
        $users = $this->ExtraItems->Users->find('list', ['limit' => 200]);
        $this->set(compact('extraItem', 'eis', 'myCuisines', 'users'));
        $this->set('_serialize', ['extraItem']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Extra Item id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $extraItem = $this->ExtraItems->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $extraItem = $this->ExtraItems->patchEntity($extraItem, $this->request->data);
            if ($this->ExtraItems->save($extraItem)) {
                $this->Flash->success(__('The extra item has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The extra item could not be saved. Please, try again.'));
            }
        }
        $eis = $this->ExtraItems->Eis->find('list', ['limit' => 200]);
        $myCuisines = $this->ExtraItems->MyCuisines->find('list', ['limit' => 200]);
        $users = $this->ExtraItems->Users->find('list', ['limit' => 200]);
        $this->set(compact('extraItem', 'eis', 'myCuisines', 'users'));
        $this->set('_serialize', ['extraItem']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Extra Item id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $extraItem = $this->ExtraItems->get($id);
        if ($this->ExtraItems->delete($extraItem)) {
            $this->Flash->success(__('The extra item has been deleted.'));
        } else {
            $this->Flash->error(__('The extra item could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
