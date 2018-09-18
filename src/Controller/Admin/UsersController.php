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
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
  use MailerAwareTrait;
    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['add', 'logout','forgotPassword','activation','register','key','newkey','goyalkey']);
    }

    public function Dashboard() {
        $this->set('title_for_layout', __('Admin Dashboard'));
         $this->loadModel('Keyins');
        if($this->request->is('post') || $this->request->is('put')){
            // CSV Download
            if(@$this->request->params['pass'][0] == 'download'){
                $keyModel = TableRegistry::get('Keyins'); //use Cake\ORM\TableRegistry;
                $Contextword = TableRegistry::get('ContextWords');
                $SymptomWord =TableRegistry::get('SymptomWords');
                $keyword_query_aject_match = $keyModel
                ->find();
                if(!empty($keyword_query_aject_match)){

                    $same_matches = $keyword_query_aject_match->toArray();
                }
                if(!empty($same_matches)){
                    $filename = "keyIns.csv";
                    $titleArray = array('k_id','k_query','keyword','symptom','context','k_answer');
                    //Send headers
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=$filename");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    $output = fopen('php://output', 'w');
                    //fputcsv($output, $titleArray);

                    foreach ($same_matches as $same_matcheskey => $same_matchesvalue){
                        //echo $same_matcheskey;
                        $csv['k_id']  = $same_matchesvalue['k_id'];
                        $csv['k_query'] = $same_matchesvalue['k_query'];
                        $csv['keyword'] = $same_matchesvalue['keyword'];
                        $csv['k_answer'] = $same_matchesvalue['k_answer'];
                        $csv['context']  = '';
                        $csv['symptom']  = '';

                        $contextResult = '';
                        $symptomsResult = ''; 
                        $strArr = explode(' ', strtolower($same_matchesvalue['k_query']));
                        $strArr =array_values($strArr);   

                        $condition = $condition1 = $condition2 = array();
                        $conditionStr = $conditionStr1 = $conditionStr2 = '';
                        foreach ($strArr as $key => $str) {
                            $str =  addslashes(strtolower($str));

                            if( empty($conditionStr2) ){
                                $conditionStr2 = " LOWER(`word`) LIKE '% $str %'";
                                $conditionStr2 .= " OR LOWER(`word`) LIKE '$str'";
                                $conditionStr2 .= " OR LOWER(`word`) LIKE '$str %'";
                                $conditionStr2 .= " OR LOWER(`word`) LIKE '% $str'";
                            }else{
                                $conditionStr2 .= " OR LOWER(`word`)  LIKE '% $str %'";
                                $conditionStr2 .= " OR LOWER(`word`) LIKE '$str'";
                                $conditionStr2 .= " OR LOWER(`word`) LIKE '$str %'";
                                $conditionStr2 .= " OR LOWER(`word`) LIKE '% $str'";
                            }
                        }
                        // print_r($conditionStr2);
                        $contextword_query = $Contextword
                        ->find()
                        ->where($conditionStr2);
                        //debug($contextword_query);
                        $contextword_query =  $contextword_query->toArray();
                        foreach ($contextword_query as $contextwordkey => $contextwordvalue){
                            $percentage = 0;
                            $usrKeywords = $contextwordvalue['word'];
                            $dbKeywords  = $strArr ;
                           
                            $dataKeyLower = ' '.addslashes(strtolower($same_matchesvalue['k_query'])).' ';
                            $conditionStrNew = " '$dataKeyLower' LIKE concat('%',LOWER(`word`),'%') AND word != ''";

                             $contextwordmatch = $Contextword
                            ->find()
                            //->select(['c_id'])
                            ->where([$conditionStrNew])
                            ->toArray();
                            /*$user_keywords_unique = array_unique($strArr);
                            $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                            $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                            $percentage = 100*count($matches)/count($db_keywords_unique);
                                if($percentage == 100 ){
                                    $csv['context'] =$contextwordvalue['c_id'];
                                    $contextResult = $contextwordvalue['c_id'];
                                }*/
                        }
                        if(!empty($contextwordmatch)){
                            $contextIds =array();
                            foreach ($contextwordmatch as $contextwordmatchkey => $contextwordmatchvalue) {
                            $contextIds[] =$contextwordmatchvalue['c_id'];
                            // echo $cot['word'] = $contextwordmatchvalue['word'];
                            }
                            $contextIds = array_unique($contextIds);
                            $contextIds = implode(',', $contextIds);
                            //echo '<pre>' ; print_r($contextIds); echo '---------------<br>';
                            // echo $contextwordmatch['word'];
                            if(!empty($contextIds)){

                                //echo  'Word Match ='.$contextwordmatch['word']."<br>";
                                //echo  'context id ='.$contextwordmatch['c_id']."<br>";
                                $csv['context'] = $contextIds;
                                $contextResult = $contextIds;
                                 //echo '<pre>';print_r($contextResult);

                            }
                        }
                        // Symptoms Check 
                        $symptomword_query = $SymptomWord
                        ->find()
                        ->where($conditionStr2);
                        $symptomword_query =  $symptomword_query->toArray();
                        // echo '<pre>';print_r($symptomword_query); exit;
                        foreach ($symptomword_query as $symptomwordkey => $symptomwordvalue){
                            $percentage = 0;
                            $usrKeywords = $symptomwordvalue['word'];
                            $dbKeywords  = $strArr ;
                            $user_keywords_unique = array_unique($strArr);
                            $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                            $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                            $percentage = 100*count($matches)/count($db_keywords_unique);
                                if($percentage == 100 ){
                                    $csv['symptom'] =$symptomwordvalue['s_id'];
                                    $symptomswordResult = $symptomwordvalue['word'];
                                    $symptomsResult = $symptomwordvalue['s_id'];
                                }
                        }
                        // end  Symptoms 
                        $expVal = array($csv['k_id'],$csv['k_query'],$csv['keyword'],@$csv['symptom'],@$csv['context'],$csv['k_answer']);
                         //echo '<pre>';print_r($expVal); 
                        fputcsv($output, $expVal);
                    } 
                }
                exit;
            }else{ 
                if(!empty($this->request))
                {

                    //echo '<pre>'; print_r($this->request); exit;
                    //Check if image has been uploaded
                    if(!empty($this->request->data['upload']['name']))
                    {
                        $file = $this->request->data['upload']; //put the data into a var for easy use

                        $ext = substr(strtolower(strrchr($this->request->data['upload']['name'], '.')), 1); //get the extension 
                        $arr_ext = array('csv'); //set allowed extensions

                        //only process if the extension is valid
                        if(in_array($ext, $arr_ext))
                        {
                            //do the actual uploading of the file. First arg is the tmp name, second arg is
                            //where we are putting it

                            move_uploaded_file($file['tmp_name'], $this->webroot.'profile/' . $this->request->data['upload']['name']);

                            $uploadPath = $this->webroot.'profile/' . $this->request->data['upload']['name'];
                            
                            $handle = fopen($uploadPath,"r");
                            if($handle)
                            {
                                $this->Keyins->deleteAll(array('1 = 1'));
                                $connection = \Cake\Datasource\ConnectionManager::get('default');
                                $connection->execute('ALTER TABLE `keyins` AUTO_INCREMENT = 1;');
                                //$this->Keyins->query('TRUNCATE keyins');
                                //$this->Keyins->deleteAll();
                                while(($data = fgetcsv($handle)) !== false)
                                {
                                    $keyin = $this->Keyins->newEntity();
                                  //echo '<pre>'; print_r($data);
                                    $keyins['k_id'] = $data[0];
                                    $keyins['k_query'] = $data[1];
                                    $keyins['keyword'] = $data[2];
                                    $keyins['symptom'] = $data[3];
                                    $keyins['context'] = $data[4];
                                    $keyins['k_answer'] = $data[5];

                                   $keyin = $this->Keyins->patchEntity($keyin,$keyins);
                                    $this->Keyins->save($keyin);
                                }    
                            }
                            
                            //prepare the filename for database entry
                           // $this->data['products']['product_image'] = $file['name'];
                        }
                    }

                    //now do the save
                    //$this->products->save($this->data) ;
                }

            }
            
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index(){
        $this->set('title_for_layout', __('Manage User'));
        if($this->request->is('ajax')){
            $this->autoRender = false;
            $Users = TableRegistry::get('Users');
            $request = $this->request;
            $page = $request->query('draw');
            $limit = $request->query('length');
            $start = $request->query('start');
            $search = $this->request->query('search');
            $colName = $this->request->query['order'][0]['column'];
            $condition = array();
            $condition['Users.user_status !='] = 2;
            $condition['Users.user_type '] = 1;
            foreach ($this->request->query['columns'] as $column) {
                if (isset($column['searchable']) && $column['searchable'] == 'true') {
                    if (isset($column['name']) && $search['value'] != '') {
                          if (in_array($column['name'], array('username','email'))) {
                               $condition['OR'][$column['name'] . ' LIKE '] = '%' . trim($search['value']) . '%';
                          } 
                    }
                }
            }
            $orderby[$this->request->query['columns'][$colName]['name']] = $this->request->query['order'][0]['dir'];
            $total_records = $Users->find('all')->hydrate(false)->where($condition)->count();
            $resultSet = $Users->find('all')->hydrate(false)
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
                    $color = ( $row["user_status"] == '1' ? 'fa-check-circle enable fa-lg' : 'fa-times-circle disable fa-lg' );
                    $action = '<a href = '.Router::url(["action" => "edit", $row["id"]]).' "title" ="Edit User" ><i class="fa fa-edit fa-lg" title ="Edit User"></i></a> &nbsp;<i style="cursor:pointer" class="fa '.$color.'" onclick="changeStatus('.$row["id"].','.$row["user_status"].')" title="Change Status"></i>&nbsp;&nbsp;<i style="cursor:pointer" class="fa fa-trash-o fa-lg disable" onclick="deleteRow('.$row["id"].')" title="Delete User"></i>';
                    $responce->data[$sno] = array($row["username"],$row["email"],format_normal_date($row["added_date"]),$action);
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
                'user_type' => 1
            ]
        ];
         $users = $this->paginate('Users');
        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    public function change_status() {
        $this->viewBuilder()->layout(false);
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $user = $this->Users->get($id);
            $user->user_status = $this->request->data['status'] == 1 ? 0 : 1;
            if ($this->Users->save($user)) {
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
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null){
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }


    public function newkey(){
        if($this->request->is('post') || $this->request->is('put')){
            $data = $this->request->data;
            $this->viewBuilder()->layout('home');
            $keyModel = TableRegistry::get('Keyins'); //use Cake\ORM\TableRegistry;
            $Contextword = TableRegistry::get('ContextWords');
            $SymptomWord =TableRegistry::get('SymptomWords');
            $keyword_query_aject_match = $keyModel
            ->find();

            if(!empty($keyword_query_aject_match)){
                $same_matches = $keyword_query_aject_match->toArray();
            }// $same_matches =''

            //echo '<pre>';    print_r($same_matches); exit;
            if(!empty($same_matches)){
                $filename = "keyIns.csv";
                $titleArray = array('k_id','k_query','keyword','symptom','context','k_answer');
                //Send headers
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=$filename");
                header("Pragma: no-cache");
                header("Expires: 0");
                $output = fopen('php://output', 'w');
                fputcsv($output, $titleArray);

                foreach ($same_matches as $same_matcheskey => $same_matchesvalue){
                    //echo $same_matcheskey;
                    $csv['k_id']  = $same_matchesvalue['k_id'];
                    $csv['k_query'] = $same_matchesvalue['k_query'];
                    $csv['keyword'] = $same_matchesvalue['keyword'];
                    $csv['k_answer'] = $same_matchesvalue['k_answer'];

                    $contextResult = '';
                    $symptomsResult = ''; 
                    $strArr = explode(' ', strtolower($same_matchesvalue['k_query']));
                    $strArr =array_values($strArr);   

                    $condition = $condition1 = $condition2 = array();
                    $conditionStr = $conditionStr1 = $conditionStr2 = '';
                    foreach ($strArr as $key => $str) {
                        $str =  addslashes(strtolower($str));

                        if( empty($conditionStr2) ){
                        $conditionStr2 = " LOWER(`word`) LIKE '% $str %'";
                        $conditionStr2 .= " OR LOWER(`word`) LIKE '$str'";
                        $conditionStr2 .= " OR LOWER(`word`) LIKE '$str %'";
                        $conditionStr2 .= " OR LOWER(`word`) LIKE '% $str'";
                        }else{
                        $conditionStr2 .= " OR LOWER(`word`)  LIKE '% $str %'";
                        $conditionStr2 .= " OR LOWER(`word`) LIKE '$str'";
                        $conditionStr2 .= " OR LOWER(`word`) LIKE '$str %'";
                        $conditionStr2 .= " OR LOWER(`word`) LIKE '% $str'";
                        }
                    }
                    // print_r($conditionStr2);
                    $contextword_query = $Contextword
                    ->find()
                    ->where($conditionStr2);
                    //debug($contextword_query);
                    $contextword_query =  $contextword_query->toArray();

                    //echo '<pre>';print_r($contextword_query); 
                    foreach ($contextword_query as $contextwordkey => $contextwordvalue){
                        // echo '<pre>'; print_r($value);
                        $percentage = 0;
                        $usrKeywords = $contextwordvalue['word'];
                        $dbKeywords  = $strArr ;
                        $user_keywords_unique = array_unique($strArr);
                        // echo '<pre>'; print_r($user_keywords_unique );
                        $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                        // echo '<pre>'; print_r($db_keywords_unique);
                        $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                        $percentage = 100*count($matches)/count($db_keywords_unique);
                            if($percentage == 100 ){

                            // echo  'Word Match1 ='.$contextwordvalue['word']."<br>";
                            $csv['context'] =$contextwordvalue['c_id'];
                            $contextResult = $contextwordvalue['c_id'];
                            }
                        // echo '<pre>';print_r($matches);
                    }
                    // Symptoms Check 
                    $symptomword_query = $SymptomWord
                    ->find()
                    ->where($conditionStr2);
                    $symptomword_query =  $symptomword_query->toArray();
                    // echo '<pre>';print_r($symptomword_query); exit;
                    foreach ($symptomword_query as $symptomwordkey => $symptomwordvalue){
                        $percentage = 0;
                        $usrKeywords = $symptomwordvalue['word'];
                        $dbKeywords  = $strArr ;
                        $user_keywords_unique = array_unique($strArr);
                        $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                        $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                        $percentage = 100*count($matches)/count($db_keywords_unique);
                        if($percentage == 100 ){

                        // echo   'Word Match2= '.$symptomwordvalue['word']."<br>";
                        $csv['symptom'] =$symptomwordvalue['s_id'];

                        //print_r($matches);
                        $symptomswordResult = $symptomwordvalue['word'];
                        $symptomsResult = $symptomwordvalue['s_id'];
                        }
                    }
                    // end  Symptoms 
                        $expVal = array($csv['k_id'],$csv['k_query'],$csv['keyword'],$csv['symptom'],@$csv['context'],$csv['k_answer']);
                    // print_r($expVal); exit;
                    fputcsv($output, $expVal);
                } 
            }
        }
    }    

    

    public function register()
    {
        $this->viewBuilder()->layout('home');
        $user = $this->Users->newEntity();
        if($this->request->is('post') || $this->request->is('put'))
        {
            $user = $this->Users->patchEntity($user, $this->request->data);
            $user['activation_code'] = mt_rand (100000, 999999) ;
            if ($this->Users->save($user)) {
              
              $email = $user->email;
              $first_name = $user->username;
              $confirmLink = "<a href='" . Router::url(array('controller' => 'users', 'action' => 'activation', $user->activation_code), true) . "' >Click Here</a>";
              //$this->getMailer('User')->send('Registration', [$email, $first_name, $confirmLink]);
                $this->sendUserEmail($email,$first_name,$confirmLink);
                $response['err'] = 0;
                 $response['msg'] = "";
                 echo json_encode($response);
                $this->Flash->success(__('Registration successful. Please check your email to activate your account.')); exit;
                return $this->redirect(['action' => 'index']);
            } else {
               
                if($user->errors())
                 {
                    $error_msg = [];
                    foreach( $user->errors() as $errors){
                        if(is_array($errors)){
                            foreach($errors as $error){
                                $error_msg[]    =   $error;
                            }
                        }else{
                            $error_msg[]    =   $errors;
                        }
                    }
                }
                //print_r($error_msg);
                $response['err'] =1;
                $response['msg']= __ ($error_msg[0]);
                echo json_encode($response); exit;
             $this->Flash->error(__('The user could not be saved. Please, try again.'));
               
               // $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
      /*  $this->set(compact('user'));
        $this->set('_serialize', ['user']);*/
    }
    
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    public function login() {
        $request = $this->request;
        //prd($request);
        $this->set('title_for_layout', __('Admin Login'));
        $user_id = @$this->request->session()->read('Auth.Admin.id');
        if (isset($user_id) && !empty($user_id)) {
            $this->redirect(['controller' => 'Users', 'action' => 'Dashboard']);
        }
        
        $login = new LoginForm();
        if ($request->is('post')) {
            
            $isValid = $login->validate($request->data);    
            if($isValid) {
                
                $user = $this->Auth->identify();
                if ($user) {
                    if($this->checkUserStatus($user)) {
                        $this->Auth->setUser($user);
                        return $this->redirect($this->Auth->loginRedirect);
                    }
                } else {
                    // Bad Login
                    $this->Flash->error('Invalid Email or Password.');
                }
            } else {
                $this->Flash->error('Some error occurs while form submitting');
            }
        }
        
        $this->set('login', $login);
        
    }
    /**
     * function use for Forget password from admin panel
     * @params user email
     */
    public function forgotPassword() {
        $this->set('title_for_layout', __('Forgot Password'));
        $data = $this->request->data;
        if ($this->request->is('post') || $this->request->is('put')) {
            // In a controller or table method.
            $users = TableRegistry::get('Users'); //use Cake\ORM\TableRegistry;
            $query = $users
                ->find()
                ->select(['id', 'username', 'email','user_status'])
                ->where([
                    'email' => $data['email'],
                    'user_type IN ' => array(0) //Admin 
                ])
                ->first();

            if (isset($query->id) && !empty($query->id)) {
                $userData = $query->toArray();
                
                if($this->checkUserStatus($userData)) {
                    $this->loadModel('EmailContents');
                    /** Send Email start  * */
                    //$email = Configure::read('Site.email');
                    $email = $data['email'];
                    $name = $userData['username'];
                    $user_email_id = $data['email'];
                    $pass =  $randstring = md5 ( time () + rand () );//$this->EmailContents->getRandomValues(6);

                    if ($this->getMailer('User')->send('ForgotPassword', [$email, $name, $pass, $user_email_id])/* $this->EmailContents->AdminForgotPassword($email, $name, $pass,$user_email_id) */) {
                        $edit_pass = $users->newEntity();
                        $edit_pass->password = $pass;
                        $edit_pass->id = $userData['id'];
                        /** Save New Password For Admin in User Table * */
                        if ($users->save($edit_pass)) {
                            $this->Flash->success(__('Please check your email "' . $data['email'] . '" inbox for new password.'));
                        } else {
                            $this->Flash->error(__('Mail can not be sent. Please try again'));
                        }
                    } else {
                        $this->Flash->error(__('Mail can not be sent. Please try again'));
                    }
                    /** Send Email end  * */
                }
            } else {
                $this->Flash->error(__('Please fill correct email'));
            }
        }
    }

    /**
     * function use for Forget password from admin panel
     * @params user email
     */
    public function changePassword() {
        
        $this->set('title_for_layout', 'Manage Profile');
        $id = $this->request->session()->read('Auth.Admin.id');
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->request->data['password'] == '' && $this->request->data['confirm_password'] == '') {
                $this->Users->validator()->remove('password');
                $this->Users->validator()->remove('confirm_password');
                unset($this->request->data['password']);
                unset($this->request->data['confirm_password']);
            }
            $user = $this->Users->patchEntity($user, $this->request->data);

            if ($this->Users->save($user)) {
                unset($user['password']);
                $this->Auth->setUser($user->toArray());
               
                 $response['err'] = 0;
                 $response['msg'] = "";
                 echo json_encode($response);

                $this->Flash->success(__('Password updated successfully.'));
                exit;
                //return $this->redirect($this->Auth->loginRedirect);
            } else {
                  $this->Flash->error(__('Profile could not be updated. Please, try again.'));
            }
        }
        unset($user['password']);
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    
    }

    // Logout
    public function logout() {

        $this->request->session()->delete('Auth.Admin');
        $this->Flash->success('You are logged out');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
     public function delete_row(){
        $this->request->allowMethod(['ajax']);
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $user = $this->Users->get($id);
            $user->user_status = 2;
            if ($this->Users->save($user)) {
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
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

     public function activation($key = Null) {
        $users = TableRegistry::get('Users'); //use Cake\ORM\TableRegistry;
        $userInfo = $users->find()->where(['activation_code' => $key])->first();

        if (isset($userInfo->id) && !empty($userInfo->id)) {

            $res = $users->query()->update()
                ->set(['activation_code' => '', 'user_status' => 1])
                ->where(['id' => $userInfo->id])
                ->execute();

            if ($res) {

                $this->Flash->success(__('Your account has been activated.'));
                $this->redirect(array('controller' => 'Pages', 'action' => 'index'));
            } else {
                $this->Flash->error(__('Some error occured while activation.'));
                $this->redirect(array('controller' => 'Pages', 'action' => 'index'));
            }
        } else {
            $this->Flash->error(__('Sorry, Activation key is invalid.'));
            $this->redirect(array('controller' => 'Pages', 'action' => 'index'));
        }
    }
}
