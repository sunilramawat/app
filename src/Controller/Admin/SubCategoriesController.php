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
 * SubCategories Controller
 *
 * @property \App\Model\Table\SubCategoriesTable $SubCategories
 */
class SubCategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($id = null)
    {
        $breadcrumb = array();
        $breadcrumb[] = '<li><a href="../../categories">Manage Category</a></li>';
        $breadcrumb[] = '<li><a href="javascript:void(0)">Manage Sub Category</a></li>';
        $this->set('breadcrumb',implode(" ", $breadcrumb));
        $this->set('title_for_layout', __('Manage Sub Category'));
        if($this->request->is('ajax')){
            $this->autoRender = false;

            $SubCategories = TableRegistry::get('SubCategories');
            $request = $this->request;
            $page = $request->query('draw');
            $limit = $request->query('length');
            $start = $request->query('start');
            $search = $this->request->query('search');
            $colName = $this->request->query['order'][0]['column'];
            $condition = array();
            $condition['SubCategories.status !='] = 2;
            $condition['SubCategories.sc_c_id '] = $id;
            foreach ($this->request->query['columns'] as $column) {
                if (isset($column['searchable']) && $column['searchable'] == 'true') {
                    if (isset($column['name']) && $search['value'] != '') {
                          if (in_array($column['name'], array('sc_name'))) {
                               $condition['OR'][$column['name'] . ' LIKE '] = '%' . trim($search['value']) . '%';
                          } 
                    }
                }
            }
            $orderby[$this->request->query['columns'][$colName]['name']] = $this->request->query['order'][0]['dir'];
            $total_records = $SubCategories->find('all')->hydrate(false)->where($condition)->count();
            $resultSet = $SubCategories->find('all')->hydrate(false)
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
                    $action = '<a href = '.Router::url(["action" => "edit", $row["sc_id"]]).' "title" ="Edit User" ><i class="fa fa-edit fa-lg" title ="Edit User"></i></a> &nbsp;<i style="cursor:pointer" class="fa '.$color.'" onclick="changeStatus('.$row["sc_id"].','.$row["status"].')" title="Change Status"></i>&nbsp;&nbsp;<i style="cursor:pointer" class="fa fa-trash-o fa-lg disable" onclick="deleteRow('.$row["sc_id"].')" title="Delete User"></i>';
                  
                    $responce->data[$sno] = array($c,$row["sc_name"],$action);
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

        $subCategories = $this->paginate($this->SubCategories);

        $this->set(compact('subCategories'));
        $this->set('_serialize', ['subCategories']);
    }

     public function change_status() {
        $this->viewBuilder()->layout(false);
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $subcategory = $this->SubCategories->get($id);
            $subcategory->status = $this->request->data['status'] == 1 ? 0 : 1;
            if ($this->SubCategories->save($subcategory)) {
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
     * @param string|null $id Sub Category id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $subCategory = $this->SubCategories->get($id, [
            'contain' => ['Scs', 'Categories']
        ]);

        $this->set('subCategory', $subCategory);
        $this->set('_serialize', ['subCategory']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('title_for_layout', __('Add Sub Category'));
        $subCategory = $this->SubCategories->newEntity();
        if ($this->request->is('post')) {
            $subCategory = $this->SubCategories->patchEntity($subCategory, $this->request->data);
            //print_r($subCategory);  exit;
            if ($this->SubCategories->save($subCategory)) {
                $this->Flash->success(__('The sub category has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sub category could not be saved. Please, try again.'));
            }
        }
       // $scs = $this->SubCategories->Scs->find('list', ['limit' => 200]);
        $categories = $this->SubCategories->Categories->find('list', ['keyField' => 'c_id',
    'valueField' => 'c_name'], ['limit' => 200]);
       // echo '<pre>';print_r($categories);
        $this->set(compact('subCategory'/*, 'scs'*/, 'categories'));
        $this->set('_serialize', ['subCategory']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sub Category id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
         $this->set('title_for_layout', __('Edit Sub Category'));
        $subCategory = $this->SubCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $subCategory = $this->SubCategories->patchEntity($subCategory, $this->request->data);
            if ($this->SubCategories->save($subCategory)) {
                $this->Flash->success(__('The sub category has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sub category could not be saved. Please, try again.'));
            }
        }
        //$scs = $this->SubCategories->Scs->find('list', ['limit' => 200]);
        $categories = $this->SubCategories->Categories->find('list', ['keyField' => 'c_id',
    'valueField' => 'c_name'],['limit' => 200]);
        $categories = $categories->toArray();
        $this->set(compact('subCategory', /*'scs',*/ 'categories'));
        $this->set('_serialize', ['subCategory']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sub Category id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete_row(){
        $this->request->allowMethod(['ajax']);
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $subCategory = $this->SubCategories->get($id);
            $subCategory->status = 2;
            if ($this->SubCategories->save($subCategory)) {
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
        $subCategory = $this->SubCategories->get($id);
        if ($this->SubCategories->delete($subCategory)) {
            $this->Flash->success(__('The sub category has been deleted.'));
        } else {
            $this->Flash->error(__('The sub category could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
