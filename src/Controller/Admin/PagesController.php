<?php
namespace App\Controller\Admin;

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
use Cake\Datasource\ConnectionManager;

/**
 * Pages Controller
 *
 * @property \App\Model\Table\PagesTable $Pages
 */
class PagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index(){
        $breadcrumb = array();
        $breadcrumb[] = '<li><a href="javascript:void(0)">CMS Pages</a></li>';
        $this->set('breadcrumb',implode(" ", $breadcrumb));
        $this->set('title_for_layout',__("CMS Pages"));
        if($this->request->is('ajax')){
            $this->autoRender = false;
            $PagesTable = TableRegistry::get('Pages');
            $request = $this->request;
            $page = $request->query('draw');
            $limit = $request->query('length');
            $start = $request->query('start');
            $search = $this->request->query('search');
            $colName = $this->request->query['order'][0]['column'];
           // print_r($colName);
            $condition = array();
            $condition['p_status !='] = 2;
            /*foreach ($this->request->query['columns'] as $column) {
                if (isset($column['searchable']) && $column['searchable'] == 'true') {
                    if (isset($column['name']) && $search['value'] != '') {
                          if ($column['name'] == 'p_title') {
                               $condition[$column['name'] . ' LIKE '] = '%' . trim($search['value']) . '%';
                          } 
                    }
                }
            }*/
            foreach ($this->request->query['columns'] as $column) {
                if (isset($column['searchable']) && $column['searchable'] == 'true') {
                    if (isset($column['name']) && $search['value'] != '') {
                        if (in_array($column['name'], array('p_title','p_description'))) {
                            $condition['OR'][$column['name'] . ' LIKE '] = '%' . trim($search['value']) . '%';
                        } 
                    }
                }
            }
            
           // print_r($condition); exit;
            $orderby[$this->request->query['columns'][$colName]['name']] = $this->request->query['order'][0]['dir'];
            $total_records = $PagesTable->find('all', ['conditions' => $condition])->count();
            $resultSet = $PagesTable->find('all')
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
                foreach($resultSet as $row){ 
                    $color = ( $row["p_status"] == '1' ? 'fa-check-circle enable fa-lg' : 'fa-times-circle disable fa-lg' );
                    /*$action = '<a href = "javascript:void(0)" ><i class="fa '.$color.'" onclick="changeStatus('.$row["p_id"].", ".$row['p_status'].')" title="Change Status"></i></a> &nbsp;<a href = '.Router::url(["action" => "mangebanner", $row["id"]])." 'title' ='Edit Banner' ><i class='fa fa-edit fa-lg' title ='Edit Banner'></i></a> &nbsp;<a href = 'javascript:void(0)' ><i class='fa fa-trash-o fa-lg disable' onclick='deleteRow('.$row["p_id"].')" title="Delete Banner"></i></a>';*/
               
               /*&nbsp;<a href = '.Router::url(["action" => "mangebanner", $row["id"]])." 'title' ='Edit Banner' ><i class='fa fa-edit fa-lg' title ='Edit Banner'></i></a> &nbsp; */   
                    $action = '<a href = '.Router::url(["action" => "edit", $row["p_id"]]).' "title" ="Edit Page" ><i class="fa fa-edit fa-lg" title ="Edit Page"></i></a> &nbsp;<i style="cursor:pointer" class="fa '.$color.'" onclick="changeStatus('.$row["p_id"].','.$row["p_status"].')" title="Change Status"></i>&nbsp;&nbsp;<i style="cursor:pointer" class="fa fa-trash-o fa-lg disable" onclick="deleteRow('.$row["p_id"].')" title="Delete Subscribers"></i>';
                    $responce->data[$sno] = array(/*$row["p_id"],*/$row["p_title"],$row['p_description'],$action);
                    $sno++;                                                 
                }
            }else{
                $responce->data = '';
            }
            
            echo json_encode($responce);
        }
        $this->set('_serialize', ['newsletters']);
    }
   /* public function index()
    {
         $this->set('title_for_layout', __('Manage Pages'));
        $this->paginate = [
            //'contain' => ['Ps']
        ];
        $pages = $this->paginate($this->Pages);

        $this->set(compact('pages'));
        $this->set('_serialize', ['pages']);
    }*/

    /**
     * View method
     *
     * @param string|null $id Page id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

     public function change_status() {
        $this->viewBuilder()->layout(false);
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $page = $this->Pages->get($id);
            $page->p_status = $this->request->data['status'] == 1 ? 0 : 1;
            if ($this->Pages->save($page)) {
                echo '1';
            } else {
                echo '0';
            }
            exit;
        } 
        return $this->redirect(['action' => 'index']);
    }
    public function view($id = null)
    {
        $page = $this->Pages->get($id, [
            'contain' => ['Ps']
        ]);

        $this->set('page', $page);
        $this->set('_serialize', ['page']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
         $this->set('title_for_layout', __('Manage CMS Pages'));
        $page = $this->Pages->newEntity();
        if ($this->request->is('post')) {

            $page = $this->Pages->patchEntity($page, $this->request->data);
            $page->p_status = '1';
             //print_r($page);  exit;
            if ($this->Pages->save($page)) {
                $this->Flash->success(__('The page has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The page could not be saved. Please, try again.'));
            }
        }
       // $ps = $this->Pages->Ps->find('list', ['limit' => 200]);
        $this->set(compact('page'/*, 'ps'*/));
        $this->set('_serialize', ['page']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Page id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('title_for_layout', __('Manage CMS Pages'));
        $page = $this->Pages->get($id, [
            'contain' => []
        ]);
      //  print_r($page);  exit;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $page = $this->Pages->patchEntity($page, $this->request->data);
            if ($this->Pages->save($page)) {
                $this->Flash->success(__('The page has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The page could not be saved. Please, try again.'));
            }
        }
       // $ps = $this->Pages->Ps->find('list', ['limit' => 200]);
        $this->set(compact('page'/*, 'ps'*/));
        $this->set('_serialize', ['page']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Page id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
     public function delete_row(){
        $this->request->allowMethod(['ajax']);
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $page = $this->Pages->get($id);
            $page->p_status = 2;
            if ($this->Pages->save($page)) {
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
        $page = $this->Pages->get($id);
        if ($this->Pages->delete($page)) {
            $this->Flash->success(__('The page has been deleted.'));
        } else {
            $this->Flash->error(__('The page could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
