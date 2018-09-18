<?php
namespace App\Controller\Admin; // THIS IS THE CORRECT NAME SPACE

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Controller\Component\AuthComponent;
use Cake\ORM\TableRegistry;
use Cake\Mailer\MailerAwareTrait;
use Cake\Validation\Validator;
use App\Form\LoginForm;
use Cake\Routing\Router;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 */
class CategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $breadcrumb = array();
        $breadcrumb[] = '<li><a href="javascript:void(0)">Manage Category</a></li>';
        $this->set('breadcrumb',implode(" ", $breadcrumb));
        $this->set('title_for_layout', __('Manage Category'));
        if($this->request->is('ajax')){
            $this->autoRender = false;
            $Categories = TableRegistry::get('Categories');
            $request = $this->request;
            $page = $request->query('draw');
            $limit = $request->query('length');
            $start = $request->query('start');
            $search = $this->request->query('search');
            $colName = $this->request->query['order'][0]['column'];
            $condition = array();
            $condition['Categories.status !='] = 2;
            //$condition['Categories.user_type '] = 1;
            foreach ($this->request->query['columns'] as $column) {
                if (isset($column['searchable']) && $column['searchable'] == 'true') {
                    if (isset($column['name']) && $search['value'] != '') {
                          if (in_array($column['name'], array('c_name'))) {
                               $condition['OR'][$column['name'] . ' LIKE '] = '%' . trim($search['value']) . '%';
                          } 
                    }
                }
            }
            $orderby[$this->request->query['columns'][$colName]['name']] = $this->request->query['order'][0]['dir'];
            $total_records = $Categories->find('all')->hydrate(false)->where($condition)->count();
            $resultSet = $Categories->find('all')->hydrate(false)
                ->where($condition)
                ->limit($limit)
                ->order($orderby)
                ->offset($start)
                ->toArray();
            $responce = new \stdClass();
            $responce->draw = $page;
            $responce->recordsTotal = $total_records;
            $responce->recordsFiltered = $total_records;
            if (isset($resultSet[0])) {
                $sno = 0;
                $c = $start+1;
                foreach($resultSet as $row){ 
                    $color = ( $row["status"] == '1' ? 'fa-check-circle enable fa-lg' : 'fa-times-circle disable fa-lg' );
                    $action = '<a href = '.Router::url(["action" => "edit", $row["c_id"]]).' "title" ="Edit User" ><i class="fa fa-edit fa-lg" title ="Edit User"></i></a> &nbsp;<i style="cursor:pointer" class="fa '.$color.'" onclick="changeStatus('.$row["c_id"].','.$row["status"].')" title="Change Status"></i>&nbsp;&nbsp;<i style="cursor:pointer" class="fa fa-trash-o fa-lg disable" onclick="deleteRow('.$row["c_id"].')" title="Delete User"></i>';
                    $subcategory ='<a href = '.Router::url(["controller" => "sub_categories", "action" => "index", $row["c_id"]]).' "title" ="Edit User" >'.$row["c_name"].'</a>';
                    $responce->data[$sno] = array($c,$subcategory,$action);
                    $sno++;                                                 
                    $c++;
                }
            }else{
                $responce->data = '';
            }
            echo json_encode($responce);
        }
        $this->paginate = [
            'conditions' => [
                //'user_status != ' => 2,
               // 'user_type' => 1
            ]
        ];
        
        /*$this->paginate = [
            //'contain' => ['Cs']
        ];*/
        $categories = $this->paginate($this->Categories);

        $this->set(compact('categories'));
        $this->set('_serialize', ['categories']);
    }
    public function change_status() {
        $this->viewBuilder()->layout(false);
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $category = $this->Categories->get($id);
            $category->status = $this->request->data['status'] == 1 ? 0 : 1;
            if ($this->Categories->save($category)) {
                echo '1';
            } else {
                echo '0';
            }
            exit;
        } 
        return $this->redirect(['action' => 'index']);
    }
    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $category = $this->Categories->get($id, [
            //'contain' => ['Cs']
        ]);

        $this->set('category', $category);
        $this->set('_serialize', ['category']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('title_for_layout', __('Add Category'));
        $category = $this->Categories->newEntity();
        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->data);
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The category could not be saved. Please, try again.'));
            }
        }
        //$cs = $this->Categories->find('list', ['limit' => 200]);
        $this->set(compact('category'/*, 'cs'*/));
        $this->set('_serialize', ['category']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('title_for_layout', __('Edit Category'));
        $category = $this->Categories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->data);
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The category could not be saved. Please, try again.'));
            }
        }
       // $cs = $this->Categories->Cs->find('list', ['limit' => 200]);
        $this->set(compact('category'/*, 'cs'*/));
        $this->set('_serialize', ['category']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
     public function delete_row(){
        $this->request->allowMethod(['ajax']);
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $category = $this->Categories->get($id);
            $category->status = 2;
            if ($this->Categories->save($category)) {
                echo '1';
            } else {
                echo '0';
            }
            exit;
        }
        return $this->redirect(['action' => 'index']);
    }
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
