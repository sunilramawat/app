<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PhotoGalleries Controller
 *
 * @property \App\Model\Table\PhotoGalleriesTable $PhotoGalleries
 */
class PhotoGalleriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Pgs', 'MyCuisines', 'Users']
        ];
        $photoGalleries = $this->paginate($this->PhotoGalleries);

        $this->set(compact('photoGalleries'));
        $this->set('_serialize', ['photoGalleries']);
    }

    /**
     * View method
     *
     * @param string|null $id Photo Gallery id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $photoGallery = $this->PhotoGalleries->get($id, [
            'contain' => ['Pgs', 'MyCuisines', 'Users']
        ]);

        $this->set('photoGallery', $photoGallery);
        $this->set('_serialize', ['photoGallery']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $photoGallery = $this->PhotoGalleries->newEntity();
        if ($this->request->is('post')) {
            $photoGallery = $this->PhotoGalleries->patchEntity($photoGallery, $this->request->data);
            if ($this->PhotoGalleries->save($photoGallery)) {
                $this->Flash->success(__('The photo gallery has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The photo gallery could not be saved. Please, try again.'));
            }
        }
        $pgs = $this->PhotoGalleries->Pgs->find('list', ['limit' => 200]);
        $myCuisines = $this->PhotoGalleries->MyCuisines->find('list', ['limit' => 200]);
        $users = $this->PhotoGalleries->Users->find('list', ['limit' => 200]);
        $this->set(compact('photoGallery', 'pgs', 'myCuisines', 'users'));
        $this->set('_serialize', ['photoGallery']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Photo Gallery id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $photoGallery = $this->PhotoGalleries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $photoGallery = $this->PhotoGalleries->patchEntity($photoGallery, $this->request->data);
            if ($this->PhotoGalleries->save($photoGallery)) {
                $this->Flash->success(__('The photo gallery has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The photo gallery could not be saved. Please, try again.'));
            }
        }
        $pgs = $this->PhotoGalleries->Pgs->find('list', ['limit' => 200]);
        $myCuisines = $this->PhotoGalleries->MyCuisines->find('list', ['limit' => 200]);
        $users = $this->PhotoGalleries->Users->find('list', ['limit' => 200]);
        $this->set(compact('photoGallery', 'pgs', 'myCuisines', 'users'));
        $this->set('_serialize', ['photoGallery']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Photo Gallery id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $photoGallery = $this->PhotoGalleries->get($id);
        if ($this->PhotoGalleries->delete($photoGallery)) {
            $this->Flash->success(__('The photo gallery has been deleted.'));
        } else {
            $this->Flash->error(__('The photo gallery could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
