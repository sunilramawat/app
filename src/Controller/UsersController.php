<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
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
        $this->Auth->allow(['add', 'logout','activation','register','key','newkey','goyalkey']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index(){
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
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
            //$Context = TableRegistry::get('Contexts');
            // Check  100% same word
            $keyword_query_aject_match = $keyModel
            ->find()
            ->where(['k_query'=>@$data['k_query']])
            ->first();
            if(!empty($keyword_query_aject_match)){
                $same_matches = $keyword_query_aject_match->toArray();
            }// $same_matches =''
             //      print_r($same_matches);
            if(empty($same_matches)){
                $contextResult = '';
                $symptomsResult = ''; 
                $strArr = explode(' ', strtolower($data['k_query']));
                $strArr =array_values($strArr);   
                
                // context Check 
                   
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
                //print_r($conditionStr2);
                $contextword_query = $Contextword
                ->find()
                ->where($conditionStr2);
                //debug($contextword_query);
                $contextword_query =  $contextword_query->toArray();
               
               //echo '<pre>';print_r($contextword_query); 
                foreach ($contextword_query as $key => $value){
                 // echo '<pre>'; print_r($value);
                    $percentage = 0;
                    $usrKeywords = $value['word'];
                    $dbKeywords  = $strArr ;
                    //print_r($usrKeywords).'<br>';
                    
                    // Same  match  context in  same  secquence New  Login 
                    //$dataKeyLower = strtolower($data['k_query']);
                    $dataKeyLower = ' '.addslashes(strtolower($data['k_query'])).' ';
                    $conditionStrNew = " '$dataKeyLower' LIKE concat('%',LOWER(`word`),'%') AND word != ''";

                     $contextwordmatch = $Contextword
                    ->find()
                    //->select(['c_id'])
                    ->where([$conditionStrNew])
                    ->toArray();
                    //debug($contextwordmatch);
                    //$contextwordmatch = implode('', $contextwordmatch);
                   
                    // End New Code

                   // Code update  09-01-2017 user old  logic  which  can search keyword  without secquence
                   /*$user_keywords_unique = array_unique($strArr);
                    //echo '<pre>'; print_r($user_keywords_unique );
                    $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                    //echo '<pre>'; print_r($db_keywords_unique);
                    $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                    //$percentage = 100*count($matches)/count($db_keywords_unique);
                    if($percentage == 100 ){
                     echo  'Word Match ='.$value['word']."<br>";
                     echo  'context id ='.$value['c_id']."<br>";
                     $contextResult = $value['c_id'];
                    }*/
                    // echo '<pre>';print_r($matches);
                    // end  before  code
                   
                }
                if(!empty($contextwordmatch)){
                     $contextIds =array();
                    foreach ($contextwordmatch as $contextwordmatchkey => $contextwordmatchvalue) {
                          $contextIds[] =$contextwordmatchvalue['c_id'];
                    }
                     $contextIds = implode(',', $contextIds);
                   //echo '<pre>' ; print_r($contextIds); echo '---------------<br>';
                    // echo $contextwordmatch['word'];
                    if(!empty($contextIds)){
                       
                        //echo  'Word Match ='.$contextwordmatch['word']."<br>";
                        //echo  'context id ='.$contextwordmatch['c_id']."<br>";
                           $contextResult = $contextIds;
                         //echo '<pre>';print_r($contextResult);

                    }
                }
            
                // end  context
                // $condition = $condition1 = $condition2 = array();
                $conditionStr = $conditionStr1 = '';
                foreach ($strArr as $key => $str) {
                    $str =  addslashes(strtolower($str));
                    if( empty($conditionStr) ){
                        $conditionStr = " LOWER(`word`) LIKE '% $str %'";
                        $conditionStr .= " OR LOWER(`word`) LIKE '$str'";
                        $conditionStr .= " OR LOWER(`word`) LIKE '$str %'";
                        $conditionStr .= " OR LOWER(`word`) LIKE '% $str'";
                    }else{
                        $conditionStr .= " OR LOWER(`word`)  LIKE '% $str %'";
                        $conditionStr .= " OR LOWER(`word`) LIKE '$str'";
                        $conditionStr .= " OR LOWER(`word`) LIKE '$str %'";
                        $conditionStr .= " OR LOWER(`word`) LIKE '% $str'";
                    }
                }
                // Symptoms Check 
                $symptomword_query = $SymptomWord
                ->find()
                ->where($conditionStr);
                $symptomword_query =  $symptomword_query->toArray();
                // echo '<pre>';print_r($symptomword_query); exit;
                foreach ($symptomword_query as $key => $value){
                    $percentage = 0;
                    $usrKeywords = $value['word'];
                    $dbKeywords  = $strArr ;

                    $dataKeyLower = addslashes(strtolower($data['k_query']));
                    $conditionStrSymptomNew = " '$dataKeyLower' LIKE concat('%',LOWER(`word`),'%') AND word != ''";
                    $symptomwordmatch = $SymptomWord
                    ->find()
                    ->where($conditionStrSymptomNew)
                    ->first();
                    //debug($symptomwordmatch);
                    if(!empty($symptomwordmatch)){
                       
                       // echo   'Word Match = '.$symptomwordmatch['word']."<br>";
                       // echo   'symptoms id = '.$symptomwordmatch['s_id']."<br>";
                         $symptomswordResult = $symptomwordmatch['word'];
                         $symptomsResult = $symptomwordmatch['s_id'];
                    }
                    
                    /*$user_keywords_unique = array_unique($strArr);
                    $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                    $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                    $percentage = 100*count($matches)/count($db_keywords_unique);
                    if($percentage == 100 ){
                    echo   'Word Match = '.$value['word']."<br>";
                    echo   'symptoms id = '.$value['s_id']."<br>";
                     
                     //print_r($matches);
                     $symptomswordResult = $value['word'];
                     $symptomsResult = $value['s_id'];
                    }*/
                }
                // end  Symptoms 

                // Keyword Check 
                $strArr =array_values($strArr);   
                //$condition = $condition1 = $condition2 = array();
                $conditionStr = $conditionStr1 = '';
                foreach ($strArr as $key => $str) {
                    if(trim($str)!="")
                    {
                        $str = addslashes(strtolower($str));
                        if( empty($conditionStr1) ){
                            $conditionStr1 = " LOWER(`keyword`) LIKE '% $str %'";
                            $conditionStr1 .= " OR LOWER(`keyword`) LIKE '$str'";
                            $conditionStr1 .= " OR LOWER(`keyword`) LIKE '$str %'";
                            $conditionStr1 .= " OR LOWER(`keyword`) LIKE '% $str'";
                        }else{
                            $conditionStr1 .= " OR LOWER(`keyword`)  LIKE '% $str %'";
                            $conditionStr1 .= " OR LOWER(`keyword`) LIKE '$str'";
                            $conditionStr1 .= " OR LOWER(`keyword`) LIKE '$str %'";
                            $conditionStr1 .= " OR LOWER(`keyword`) LIKE '% $str'";
                        }
                    }
                    
                }
                if($conditionStr1 != ""){
                    $conditionStr .= " ( ".$conditionStr1." ) ";
                }
                if($contextResult != ""){
                    //$conditionStr .= " And FIND_IN_SET($contextResult,Keyins.context)";
                    $conditionStr .= " OR Keyins.context= '".@$contextResult."'";
                }
                if($symptomsResult != ""){
                     //$conditionStr .= "  OR FIND_IN_SET($symptomsResult, Keyins.symptom) ";
                    $conditionStr .= " OR Keyins.symptom = '".@$symptomsResult."'";
                }
               // print_r( $conditionStr);
                
                $keyword_query = $keyModel
                ->find()
                ->where($conditionStr);
                 //debug($keyword_query);
                $keyword_query =  $keyword_query->toArray();
                $tempPercentage = 0;
                foreach ($keyword_query as $key => $value){
                    //echo '<pre>'; print_r($value);
                    $percentage = 0;
                    $keyPer = 0;
                   
                    $usrKeywords = $value['keyword'];
                    $dbKeywords  = $strArr ;
                    
                    $user_keywords_unique = array_unique($strArr);
                    //echo '<pre>';print_r($user_keywords_unique);
                    $db_keywords_unique = array_unique(explode(', ', strtolower($usrKeywords)));
                    //echo '<pre>';print_r($db_keywords_unique);
                    $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                    //echo '<pre>'; print_r($matches);
                    $percentage = 100*count($matches)/count($db_keywords_unique);

                    if(trim($contextResult) != ""){
                        $context_array = explode(',', $contextResult);
                    }else{
                        $context_array = array();
                    }

                    //print_r($context_array);

                    if(trim($value['context']) != ""){
                        $keyintable = explode(",",$value['context']);
                    }else{
                        $keyintable = array();
                    }

                    //print_r($keyintable);
                   // $keyintable = array_filter($keyintable);
                   // $context_array = array_filter($context_array);
                    if(!empty(array_intersect($keyintable,$context_array)) && !empty($keyintable)){
                        //echo $value['context'].'-------------';
                        $percentage = $percentage + 500 ;
                        /*foreach ($context_array as $context_arraykey => $context_arrayval) {
                          $percentage = $percentage + 50;
                        }*/
                    }

                    if(!empty($symptomsResult)){
                        if( $symptomsResult == $value['symptom'] )
                        {
                           // echo $value['symptom'];
                            $percentage = $percentage + 100 ;
                        } 
                    }   
                        if( $percentage >= $tempPercentage){
                            $tempPercentage = $percentage;
                            //if($symptomsResult =''){
                               // if($value['symptom']== $symptomsResult){
                                    //if($symptomswordResult==)
                                    
                                    $keyPer = explode(', ', $value['keyword']);
                                     $keyPer =  count($keyPer); echo '<br>';
                                    
                                    /*if($keyPer >= @$tempKeyper){
                                        $tempKeyper = $keyPer;  */
                                        
                                        echo '<br>';
                                        echo "=======================================================";
                                        echo '<br>';
                                        echo $keyinval['answer'] = $value['k_query']." ".$percentage."% Keyword " ;
                                        echo '<br>';
                                        echo "Answer = ".$keyinval['query'] = $value['k_answer'];
                                        echo '<br>';
                                        echo 'Keyword Match ='.$value['keyword']."<br>";
                                       // echo 'keyword id ='.$value['k_id']."<br>";
                                        echo 'symptoms id ='.$value['symptom']."<br>";
                                         echo 'context id ='.$value['context']."<br>";
                                        
                                        echo "=======================================================";
                                        echo '<br>';
                                   // }
                                //}
                           // }
                        }
                       
                        //print_r($matches);
                        $arr_ind = 0;
                        $firstArr = $user_keywords_unique;
                        // echo '<pre>';print_r($firstArr);
                        $secondArr = $db_keywords_unique;
                         //echo '<pre>';print_r($secondArr);
                      
                       /////////////////// end 
                }
                // end keyword
            }else{
                echo 'Query = ';
                echo $same_matches['k_query'];
                echo '<br>';
                echo 'Answer = '.$same_matches['k_answer'];
            }
        }  
    }


    public function goyalkey(){
        if($this->request->is('post') || $this->request->is('put')){
            $data = $this->request->data;
            $this->viewBuilder()->layout('home');
            $keyModel = TableRegistry::get('Keyins'); //use Cake\ORM\TableRegistry;
            $Contextword = TableRegistry::get('ContextWords');
            $SymptomWord =TableRegistry::get('SymptomWords');
            //$Context = TableRegistry::get('Contexts');
            $contextResult = '';
            $symptomsResult = ''; 
            
            $strArr = explode(' ', strtolower($data['k_query']));
            $strArr =array_values($strArr);

            
            /*---------------------GOYAL START-----------------------*/
            $conditionStr = $conditionStr1 = '';
            foreach ($strArr as $key => $str) {
                $str =  addslashes(strtolower($str));    
                if(empty($conditionStr)) {
                    $conditionStr = " LOWER(`word`) LIKE '% $str %'";
                }else {
                    $conditionStr .= " OR LOWER(`word`) LIKE '% $str %'"; 
                }
            }

            $contextword_query = $Contextword
                                ->find()
                                ->where($conditionStr);
                                $contextword_query =  $contextword_query->toArray();
            
            foreach ($contextword_query as $key => $value){
                echo "<pre>"; print_r($contextword_query); exit; 
                $percentage = 0;
                $usrKeywords = $value['word'];
                $dbKeywords  = $strArr ;
                $user_keywords_unique = array_unique($strArr);
                // echo '<pre>'; print_r($user_keywords_unique );
                $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                // echo '<pre>'; print_r($db_keywords_unique);
                $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                 $percentage = 100*count($matches)/count($db_keywords_unique);
                if($percentage == 100 ){
                 echo  'Word Match ='.$value['word']."<br>";
                 echo  'context id ='.$value['c_id']."<br>";
                }
                // print_r($matches);
                 $contextResult = $value['c_id'];
            }                                

            exit;
            /*---------------------GOYAL END-------------------------*/



            $condition = $condition1 = $condition2 = array();
            $conditionStr = $conditionStr1 = '';
            foreach ($strArr as $key => $str) {
                $str =  addslashes(strtolower($str));
                if( empty($conditionStr) ){
                    $conditionStr = " LOWER(`word`) LIKE '% $str %'";
                    $conditionStr .= " OR LOWER(`word`) LIKE '$str %'";
                    $conditionStr .= " OR LOWER(`word`) LIKE '% $str'";
                }else{
                    $conditionStr .= " OR LOWER(`word`)  LIKE '% $str %'";
                    $conditionStr .= " OR LOWER(`word`) LIKE '$str %'";
                    $conditionStr .= " OR LOWER(`word`) LIKE '% $str'";
                }
            }

            // Symptoms Check 
            $symptomword_query = $SymptomWord
            ->find()
            ->where($conditionStr);
            $symptomword_query =  $symptomword_query->toArray();
           
            //echo '<pre>';print_r($query); exit;
            foreach ($symptomword_query as $key => $value){
                $percentage = 0;
                $usrKeywords = $value['word'];
                $dbKeywords  = $strArr ;
                $user_keywords_unique = array_unique($strArr);
                $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                $percentage = 100*count($matches)/count($db_keywords_unique);
                if($percentage == 100 ){
                echo   'Word Match = '.$value['word']."<br>";
                echo   'symptoms id = '.$value['s_id']."<br>";
                 //print_r($matches);
                 $symptomsResult = $value['s_id'];
                }
            }
            // end  Symptoms 

            // context Check 
            $contextword_query = $Contextword
            ->find()
            ->where($conditionStr);
            $contextword_query =  $contextword_query->toArray();
           
           // echo '<pre>';print_r($contextword_query); exit;
            foreach ($contextword_query as $key => $value){
             //  echo '<pre>'; print_r($value);
                $percentage = 0;
                $usrKeywords = $value['word'];
                $dbKeywords  = $strArr ;
                $user_keywords_unique = array_unique($strArr);
                // echo '<pre>'; print_r($user_keywords_unique );
                $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                // echo '<pre>'; print_r($db_keywords_unique);
                $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                 $percentage = 100*count($matches)/count($db_keywords_unique);
                if($percentage == 100 ){
                 echo  'Word Match ='.$value['word']."<br>";
                 echo  'context id ='.$value['c_id']."<br>";
                }
                // print_r($matches);
                 $contextResult = $value['c_id'];
               
            }
            // end  Symptoms 

            // Keyword Check 
            $strArr =array_values($strArr);   
            $condition = $condition1 = $condition2 = array();
            $conditionStr = $conditionStr1 = '';
            foreach ($strArr as $key => $str) {
                $str = addslashes(strtolower($str));
                if( empty($conditionStr1) ){
                    $conditionStr1 = " LOWER(`keyword`) LIKE '% $str %'";
                    $conditionStr1 .= " OR LOWER(`keyword`) LIKE '$str %'";
                    $conditionStr1 .= " OR LOWER(`keyword`) LIKE '% $str'";
                }else{
                    $conditionStr1 .= " OR LOWER(`keyword`)  LIKE '% $str %'";
                    $conditionStr1 .= " OR LOWER(`keyword`) LIKE '$str %'";
                    $conditionStr1 .= " OR LOWER(`keyword`) LIKE '% $str'";
                }

            }
            if($conditionStr1 != "") {
                $conditionStr .= " ( ".$conditionStr1." ) ";
            }
            if($contextResult != ""){
                //$conditionStr .= " And FIND_IN_SET($contextResult,Keyins.context)";
            }
            if($symptomsResult != ""){
            $conditionStr .= "  And FIND_IN_SET($symptomsResult, Keyins.symptom) ";
            }
            
            $keyword_query = $keyModel
            ->find()
            ->where($conditionStr);

            $keyword_query =  $keyword_query->toArray();
            $tempPercentage = 50;
            foreach ($keyword_query as $key => $value){
                //echo '<pre>'; print_r($value);
                $percentage = 0;
                $usrKeywords = $value['keyword'];
                $dbKeywords  = $strArr ;
                $user_keywords_unique = array_unique($strArr);
                //echo '<pre>';print_r($user_keywords_unique);
                $db_keywords_unique = array_unique(explode(', ', strtolower($usrKeywords)));
                //echo '<pre>';print_r($db_keywords_unique);
                $matches = array_intersect($db_keywords_unique,$user_keywords_unique);
                //echo '<pre>'; print_r($matches);
                 $percentage = 100*count($matches)/count($db_keywords_unique);
                
                    if( $percentage >= $tempPercentage){
                        $tempPercentage = $percentage;
                       echo $keyinval['answer'] = $value['k_query']." ".$percentage."% Keyword " ;
                       
                                           }
                    echo '<br>';
                    //echo 'Keyword Match ='.$value['keyword']."<br>";
                    //  echo 'symptoms id ='.$value['s_id']."<br>";
                    //print_r($matches);
                    $arr_ind = 0;
                    $firstArr = $user_keywords_unique;
                    // echo '<pre>';print_r($firstArr);
                    $secondArr = $db_keywords_unique;
                     //echo '<pre>';print_r($secondArr);
                    
                   /////////////////// end 
                                    
            }
            // end  Symptoms 
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
     public function key()
        {
           
          if($this->request->is('post') || $this->request->is('put'))
         {
           $data = $this->request->data;
          
            $this->viewBuilder()->layout('home');
            $keyModel = TableRegistry::get('Keyins'); //use Cake\ORM\TableRegistry;
            $strArr = explode(' ', $data['k_query']);
            foreach ($strArr as $keystrArr => $valuestrArr) {
                if (strlen($valuestrArr) < 3) {
                        unset($strArr[$keystrArr]);
                }
            }
            $strArr =array_values($strArr);   
           
            $condition = $condition1 = $condition2 = array();
            $conditionStr = $conditionStr1 = '';
            foreach ($strArr as $key => $str) {
                  $str = strtolower($str);
                  
                 
                // $condition['k_query LIKE'] = '% '.$str.' %';
                // $condition1['k_query LIKE'] = $str.' %';
                // $condition2['k_query LIKE'] = '% '.$str;
                if( empty($conditionStr) ){
                    $conditionStr = " LOWER(`k_query`) LIKE '% $str %'";
                    $conditionStr .= " OR LOWER(`k_query`) LIKE '$str %'";
                    $conditionStr .= " OR LOWER(`k_query`) LIKE '% $str'";
                }else{
                    $conditionStr .= " OR LOWER(`k_query`)  LIKE '% $str %'";
                    $conditionStr .= " OR LOWER(`k_query`) LIKE '$str %'";
                    $conditionStr .= " OR LOWER(`k_query`) LIKE '% $str'";
                }

            }
            
            $query = $keyModel
            ->find()
            ->where($conditionStr);
           // echo '<pre>';print_r($query);
            $query =  $query->toArray();
                $tempPercentage = 70;


                foreach ($query as $key => $value) 
                {
                   // echo '<pre>'; print_r($value);
                    $percentage = 0;
                    $usrKeywords = $value['k_query'];
                    $dbKeywords  = $strArr ;

                    $user_keywords_unique = array_unique($strArr);
                    $db_keywords_unique = array_unique(explode(' ', strtolower($usrKeywords)));
                    $matches = array_intersect($user_keywords_unique, $db_keywords_unique);
                    $percentage = 100*count($matches)/count($user_keywords_unique);
                   /////////////////// start
                    $firstArr = $user_keywords_unique;//explode(' ', $data['k_query']);
                    $secondArr = $db_keywords_unique;//explode(' ',$value['k_query']);
                    $arr_ind = 0;
                    foreach ($secondArr as $key => $secStr ) {
                        if (strlen($secStr) < 3) {
                                unset($secondArr[$key]);
                        }
                     }
                     $secondArr =array_values($secondArr); 
                     foreach ($secondArr as $key => $secStr) {
                        if(strtolower($secStr) == strtolower($firstArr[0])){
                            $tempPercentage1 = 0;
                            $tempKey = @$key;
                            foreach ($firstArr as $firstKey => $firstStr) {
                                if( isset($secondArr[$tempKey]) && strtolower($firstStr) == strtolower($secondArr[$tempKey]) ){
                                     $arr_ind =  $arr_ind+100/count($firstArr);

                                    $tempKey++;
                                    if( $arr_ind >= $tempPercentage1)
                                    {
                                        $tempPercentage1 = $arr_ind;
                                        
                                    if( count($firstArr) == $firstKey+1){
                                     
                                        $keyinval['question'] = $value['k_query']; 
                                         $percentage."%  ";  $result= 'it is exist'.'<br>';
                                                                                   
                                    }

                                   } 
                                }else{
                                  
                                    break;
                                }

                            }
                             
                              $arr_ind.' % sequence matched<br>';
                              //$percentage .' % keyword  matched <br>';
                              $grantpercentage = $percentage +   $arr_ind;
                              if($grantpercentage >= 80)
                              {
                                 print_r($firstArr);
                                 echo '<br>';
                                 print_r($secondArr);
                                 echo '<br>';
                                 echo $arr_ind.' % sequence matched<br>';
                                 echo $percentage .' % keyword  matched <br>';
                             echo  $keyinval1['question'] = $value['k_query'].' '.$grantpercentage.'%'; 
                          //  echo '<br>'.$keyinval['k_query'] = $value['k_query'].' '.$percentage.'%<br>';
                          echo "<br>===============================================<br>";
                      }
                        }
                     } 
                   /////////////////// end 
                    //echo " $percentage >= $tempPercentage <br>"; 
                    if($grantpercentage >= $tempPercentage)
                    {
                        $tempPercentage = $grantpercentage;
                       $keyinval['aa'] = $value['k_query'].' '.$grantpercentage;
                     //  echo "===============================================<br>$percentage<br>";  
                       //echo "===============================================<br>";
                   }
                    

                }
               if(!empty($keyinval))
               {
                  echo "<br>===============================================<br>";  
                  echo 'Result = '. $keyinval['aa'];
                  echo "<br>===============================================<br>";  
               } 
               else
               {
                  echo 'n';
               }
         }  
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

     public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            // echo '<pre>'; print_r($this->Auth);exit;
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
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
