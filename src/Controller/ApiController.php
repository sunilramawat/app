<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use Services_Twilio;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\CreditCard;
use PayPal\Api\Amount;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
//include(ROOT . DS . 'vendor' . DS . 'stripe' . DS . 'init.php');

/**
 * ApiController Controller
 *
 */
class ApiController extends AppController
{
   
    use MailerAwareTrait;
    public function initialize()
    {
        parent::initialize();
       // $this->loadComponent('Twilio.Twilio');
        $this->loadComponent('RequestHandler');
        $this->Auth->allow(['register','login','logout','createGroup','forgotPassword','changePassword','updateProfile','test','updateContact','notification','notificationList','notificationSeen','pagesList','activation','payment','card','valutcreate','configuration','cardPayment']);
       
       /* foreach ($_POST as $key => $value){
            $_POST[$key] = base64_decode($value);
            $this->request->data = $_POST;
        }*/
        
        $dontUse = array('register','login','logout','forgotPassword','changePassword','notificationList','notificationSeen','pagesList','activation','payment','card','valutcreate','configuration','cardPayment');
        if(!in_array($this->request->params['action'],$dontUse)){
            $data = $this->request->data;
            $user_responce = $this->user_access($data);
            if(!empty($user_responce)){
               // echo $user_responce;  exit;
                if($user_responce == 538){
                    $ResponseData ['error'] = 1;
                    $ResponseData ['data'] = '';
                    $ResponseData ['code'] ='414';
                    $ResponseData ['url'] ='';
                    $ResponseData ['message'] = responseMsg(414);
                    echo json_encode ( $ResponseData ); //
                    exit;
                }               
            }
        }
    }
  
    public function getFileExtension($filename=''){
        $ext = explode(".",$filename);
        return array_pop($ext);
    }
    
    public function generate_file_name($filename=''){
        $ext = $this->getFileExtension($filename);
        return time()+rand().'.'.$ext;  
    }
   
    public function configuration() {

        return $apiContext = new \PayPal\Rest\ApiContext(
         new \PayPal\Auth\OAuthTokenCredential(
         'AQjOiw7Zc2QEkkFS2ZKtNcAxmoUvGt92qcnNLBkOH94_0zSFXryd8F-tpPEIwesy67CH7JUqOz4y5xfY',  // you will get information about client id and secret once you have created test account in paypal sandbox  
         'EMTiGa5ZBV4MrPSmHlvP04GhX0SLOr6X2eqdOChizIxMjy9Jro9lJDAIFNs4iYEWcPjmNGWxdOByFp3q'  
        )
        );
    }
    /************************************************************************************************************
    * API                   => Register/Fb Login                                                                *
    * Description           => It is used to register new user and  Fblogin existing user                       *
    * Required Parameters   => phone,device_id,device_type                                                      *
    * Created by            => Sunil                                                                            *
    *************************************************************************************************************/
    public function register(){
        $this->loadModel('Users');
        if ($this->request->is('post')){
            $data = $this->request->data;
            $userModel = TableRegistry::get('Users'); //use Cake\ORM\TableRegistry;
            $User = $userModel
            ->find()
            //->orwhere(['phone' => $data['phone']])
            ->orwhere(['email' => $data['email']])
            ->orwhere(['fb_id' => @$data['fb_id']])
            ->first();
            if(!empty($User)){
                $user ['id'] = $User['id'];
                $userId ['id'] = $User['id'];
                $user = $userModel->get($User['id']);
                if( isset($_POST['fb_id']) && strlen($_POST['fb_id']) > 0 ){
                    if($_POST['fb_id'] == $user['fb_id']){
                        $user ['last_login'] = date ( 'Y-m-d H:i:s' );
                        $user ['token_id'] = mt_rand();  
                        $user ['device_type'] =  $data['device_type'];  
                        $user ['device_id'] =  $data['device_id'];  
                        if ($this->Users->save($user)){
                            $user['access_token']= sha1(md5('youfeed'.$user['id'].'!@#$%'. $user ['token_id']).")(*&^%$");
                            $user['id'] = intval($user['id']);
                            //print_r($user);
                             $this->set([
                                'data' => $user,
                                'code' => '200',
                                'error' => '1',
                                'url'  => '',
                                'message'=> responseMsg(200),
                                '_serialize' => ['error','code','url','data','message']
                             ]); 
                        } 
                    }else{
                        $this->set([
                            'data' => '',
                            'code' => '410',
                            'error' => '1',
                            'url'  => '',
                            'message'=> responseMsg(410),
                            '_serialize' => ['error','code','url','data','message']
                        ]);
                    }   
                }else{  
                   
                    $this->set([
                        'data' => '',
                        'code' => '410',
                        'error' => '1',
                        'url'  => '',
                        'message'=> responseMsg(410),
                        '_serialize' => ['error','code','url','data','message']
                     ]);
                    //exit;
                }
            }else{

                $user = $this->Users->newEntity();
                if (!empty ($_FILES ['photo']['name'] )){
                    $imageFormats = array ('jpg','JPG','gif','GIF','jpeg','JPEG','png','PNG','bmp');
                    $user_photo = @$_FILES ['photo'];
                    $randstring = md5 ( time () + rand () );
                    $valid_image = 1;
                    $size_byte = filesize ( $_FILES ['photo'] ['tmp_name'] ); // returns in bytes
                    $extension = $this->getFileExtension ( $_FILES ['photo'] ['name'] );
                    $img_name = $this->generate_file_name ( $_FILES ['photo'] ['name'] );
                    $img_tmp = $_FILES ['photo'] ['tmp_name'];
                    $img_tmp_path = $this->webroot.'storage/post/tmp' . $img_name;
                    $img_path =  $this->webroot.'storage/post/' . $img_name;
                    $img_thumb_path = $this->webroot.'storage/post/thumb_' . $img_name;
                    if (in_array ( $extension, $imageFormats )){
                        if (move_uploaded_file ( $img_tmp, $img_path )){
                            $valid_image = 1;
                            @$_FILES ['photo'] = $img_name;
                        }
                    } 
                    else{
                        $valid_image = 0;
                    }
                    if (! empty ( $_FILES ['photo'] )){
                       $user['photo'] = @$_FILES ['photo'];
                    }
                }   
                $user ['username'] = $data['username']; 
                $user ['email'] = $data['email']; 
                $user ['address'] = @$data['address']?$data['address']:''; 
                $user ['lat'] = @$data['lat']?$data['lat']:'';
                $user ['lng'] = @$data['lng']?$data['lng']:'';
                $user ['start_time'] = @$data['start_time']?$data['start_time']:'00:00:00'; 
                $user ['end_time'] = @$data['end_time']?$data['end_time']:'00:00:00'; 
                $user ['device_id'] = $data['device_id'];
                $user ['device_type'] = $data['device_type']; 
                $user ['password'] = '123456';
                $user ['added_date'] = date ( 'Y-m-d H:i:s' );
                $user ['user_status'] = '0';  
                if( isset($_POST['fb_id']) && strlen($_POST['fb_id']) > 0 ){
                    $user ['fb_id'] = @$data['fb_id'];
                }else{
                    $user['activation_code'] = mt_rand (100000, 999999) ;
                }
          
            //$lastId ='1';
            //$phone = $data['phone'];
            //$code = $this->generateRandomNumber(4);
            $user ['last_login'] = date ( 'Y-m-d H:i:s' );
            $user ['token_id'] = mt_rand();  

            if ($result = $this->Users->save($user)){
               
                 $user['access_token']= sha1(md5('youfeed'.$user['id'].'!@#$%'. $user ['token_id']).")(*&^%$");
                @$user['photo'] = $_FILES ['photo'] ? Router::url('/webroot/profile/'.$_FILES ['photo'],true):'';
                //$verify = $this->phoneVerification ( $lastId, $code, $phone, $country_code );
                //
                if( isset($_POST['fb_id']) && strlen($_POST['fb_id']) > 0 ){
                   
                    $this->set([
                        'data' => $user,
                        'code' => '200',
                        'error' => '0',
                        'url'  => '',
                        'message'=> responseMsg(200),
                        '_serialize' => ['error','code','url','data','message']
                     ]);
                }else{
                     $email = $user->email;
                $first_name = $user->username;
                $confirmLink = "<a href='" . Router::url(array('controller' => 'users', 'action' => 'activation', $user->activation_code), true) . "' >Click Here</a>";
                //$this->getMailer('User')->send('Registration', [$email, $first_name, $confirmLink]);
                $this->sendUserEmail($email,$first_name,$confirmLink);
                    $this->set([
                        'data' => '',
                        'code' => '203',
                        'error' => '0',
                        'url'  => '',
                        'message'=> responseMsg(203),
                        '_serialize' => ['error','code','url','data','message']
                     ]);
                }
            }else{
                    if($user->errors()){
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
                    if(empty($error_msg)){   
                         $this->set([
                            'data' => '',
                            'code' => '408',
                            'error' => '1',
                            'url'  => '',
                            'message'=> responseMsg(408),
                            '_serialize' => ['error','code','url','data','message']
                         ]);
                    }else{
                        $this->set([
                            'data' => '',
                            'code' => '410',
                            'error' => '1',
                            'url'  => '',
                            'message'=> responseMsg(410),
                            '_serialize' => ['error','code','url','data','message']
                         ]);
                    }
                }
            }
        }
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
                //$this->redirect(array('controller' => 'Pages', 'action' => 'index'));
            } else {
                $this->Flash->error(__('Some error occured while activation.'));
                //$this->redirect(array('controller' => 'Pages', 'action' => 'index'));
            }
        } else {
            $this->Flash->error(__('Sorry, Activation key is invalid.'));
           // $this->redirect(array('controller' => 'Pages', 'action' => 'index'));
        }
    }
    

     /************************************************************************************************************
    * API                   => Login/Fb Login                                                                    *
    * Description           => It is used to login new user and  Fblogin existing user                           *
    * Required Parameters   => email,password,device_id,device_type                                              *
    * Created by            => Sunil                                                                             *
    *************************************************************************************************************/
    public function login(){
        $this->loadModel('Users');
        if ($this->request->is('post')){
            $data = $this->request->data;
            if($data['is_social'] != 1){ // Normal login 
                $user = $this->Auth->identify();
                if(isset($user)){   
                    $userTable = TableRegistry::get('Users');
                    $check_user = $userTable
                    ->find()
                    ->where(['email'=>@$data['email']])
                    ->first();
                    //print_r($check_user);
                   if(!empty($check_user['email'])){
                       if( (new DefaultPasswordHasher)->check($_POST['password'], $check_user->forgot_password) ){
                            $user = $check_user->toArray();
                            print_r($user);
                         
                       }else{
                           
                            @$userDetail->forgot_password = '';
                            
                       }
                    }else{
                        $this->set([
                            'code' => '400',
                            'error'  => '1',
                            'url'  => '',
                            'message'=> responseMsg(400),
                            'data' => '',
                            '_serialize' => ['error','code','url','data','message']
                           ]);
                   }
                }
                if($user){
                   if($user['user_status'] == 0){
                         $this->set([
                        'code' => '402',
                        'error'  => '1',
                        'url'  => '',
                        'message'=> responseMsg(402),
                        'data' => '',
                        '_serialize' => ['error','code','url','data','message']
                       ]);
                   }else{
                        $userTable = TableRegistry::get('Users');
                        $userDetail = $userTable->get($user['id']);
                        $token_id =  mt_rand();
                        $userDetail->token_id    = $token_id;
                        $userDetail->last_login  = date ( 'Y-m-d H:i:s' );
                        $userDetail->device_id   = $data['device_id'];
                        $userDetail->device_type = $data['device_type'];
                        if( (new DefaultPasswordHasher)->check($_POST['password'], $check_user->forgot_password) ){
                            if($user['forgot_password']!= ''){
                                $user['forgot_password'] = 1;
                            }
                        }else{
                           //$userDetail->forgot_password = '';
                            //print_r($userDetail);
                           // exit;
                            $user['forgot_password'] = '';
                            $userDetail->forgot_password = '';
                        }
                       // $userDetail->forgot_password = '';
                        // exit;$check_token = sha1(
                        $userTable->save($userDetail);
                        $this->Auth->setUser($user);
                        $user['address']        = @$user['address']?$user['address']:'';
                        $user['lat']            = @$user['lat']?$user['lat']:'';
                        $user['lng']            = @$user['lng']?$user['lng']:'';
                        $user['start_time']     = @$user['start_time']?$user['start_time']:'';
                        $user['end_time']       = @$user['end_time']?$user['end_time']:'';
                        $user['photo']    = @$user['photo'] ? Router::url('/webroot/profile/'.$user['photo'],true):'';
                        $user['access_token']    = sha1(md5('youfeed'.$user['id'].'!@#$%'.$token_id).")(*&^%$");;
                        $user['device_id']    = $data['device_id'];
                        $user['device_type']    = $data['device_type'];
                        $user['last_login']    = date ( 'Y-m-d H:i:s' );
                        
                        unset($user['token_id']);
                        $this->set([
                        'code' => '200',
                        'error' => '0',
                        'url'  => '',
                        'data' => $user,
                        'message'=> responseMsg(200),
                        '_serialize' => ['error','code','url','data','message']
                         ]);
                     }
                }else{ // new user register
                    if(!empty($check_user)){
                         $this->set([
                        'code' => '411',
                        'error'  => '1',
                        'url'  => '',
                        'message'=> responseMsg(411),
                        'data' => '',
                        '_serialize' => ['error','code','url','data','message']
                       ]);

                    }else{
                        $this->set([
                        'code' => '401',
                        'error'  => '1',
                        'url'  => '',
                        'message'=> responseMsg(401),
                        'data' => '',
                        '_serialize' => ['error','code','url','data','message']
                       ]);
                    }
                }
            }else{ // social login 
                $userTable = TableRegistry::get('Users');
                $check_user = $userTable
                ->find()
                ->where(['fb_id'=>@$data['fb_id']])
                //->where(['email'=>@$data['email']])
                ->first();
                // print_r($check_user); exit;
                // $check_user = $check_user->toArray();
                if(!empty($check_user['id'])){ // user already register
                    // update  user  detail
                    $userDetail = $userTable->get($check_user['id']);
                    $token_id =  mt_rand();
                    $userDetail->token_id    = $token_id;
                    $userDetail->last_login  = date ( 'Y-m-d H:i:s' );
                    $userDetail->device_id   = $data['device_id'];
                    $userDetail->device_type = $data['device_type'];
                    if(!empty($data['fb_id'])){
                       $userDetail->fb_id = $data['fb_id']; 
                    }
                   
                    $userTable->save($userDetail);
                    $data['id']             = $check_user['id'];
                    $data['username']       = $check_user['username'];
                    $data['email']          = $check_user['email'];
                    $data['access_token']   = sha1(md5('youfeed'.$check_user['id'].'!@#$%'.$token_id).")(*&^%$");;
                    $data['email']          = $check_user['email'];
                    $data['address']        = @$check_user['address']?$check_user['address']:'';
                    $data['lat']            = @$check_user['lat']?$check_user['lat']:'';
                    $data['lng']            = @$check_user['lng']?$check_user['lng']:'';
                    $data['start_time']     = @$check_user['start_time']?$check_user['start_time']:'';
                    $data['end_time']       = @$check_user['end_time']?$check_user['end_time']:'';
                    $data['last_login']     = date ( 'Y-m-d H:i:s' );
                    $data['photo']          =  @$user['photo'] ? Router::url('/webroot/profile/'.$check_user['photo'],true):'';
                      
                       $this->set([
                        'code' => '200',
                        'error'  => '0',
                        'url'  => '',
                        'message'=> responseMsg(200),
                        'data' => $data,
                        '_serialize' => ['error','code','url','data','message']
                       ]);

                }else{
                    $this->set([
                            'data' => '',
                            'code' => '401',
                            'error' => '1',
                            'url'  => '',
                            'message'=> responseMsg(401),
                            '_serialize' => ['error','code','url','data','message']
                         ]);
                    
                    /* // new user register
                    $this->loadModel('Users');
                    $user = $this->Users->newEntity();
                    $user = $this->Users->patchEntity($user, $this->request->data);
                    $user ['added_date'] = date ( 'Y-m-d H:i:s' );
                    $user ['last_login'] = date ( 'Y-m-d H:i:s' );
                    $user ['password'] = mt_rand();
                    $user ['token_id'] = mt_rand();   
                    $imageFormats = array (
                                        'jpg',
                                        'JPG',
                                        'gif',
                                        'GIF',
                                        'jpeg',
                                        'JPEG',
                                        'png',
                                        'PNG',
                                        'bmp' 
                            );
                    // Profile Photo
                    if (!empty ($_FILES ['photo']['name'] )){
                        $user_photo = @$_FILES ['photo'];
                        $randstring = md5 ( time () + rand () );
                        $valid_image = 1;
                 
                        $size_byte = filesize ( $_FILES ['photo'] ['tmp_name'] ); // returns in bytes
                            
                        $extension = $this->getFileExtension ( $_FILES ['photo'] ['name'] );
                        $img_name = $this->generate_file_name ( $_FILES ['photo'] ['name'] );
                        $img_tmp = $_FILES ['photo'] ['tmp_name'];
                        $img_tmp_path = $this->webroot.'profile/tmp' . $img_name;
                        $img_path =  $this->webroot.'profile/' . $img_name;
                        $img_thumb_path = $this->webroot.'profile/thumb_' . $img_name;
                        if (in_array ( $extension, $imageFormats )){
                            if (move_uploaded_file ( $img_tmp, $img_path )){
                            //  $this->Commonfunctions->create_thumb ( $img_path, $img_thumb_path, 110, 110 );
                                $valid_image = 1;
                                @$_FILES ['photo'] = $img_name;
                            }
                        }else{
                            $valid_image = 0;
                        }
                        if (! empty ( $_FILES ['photo'] )){
                            $user['photo'] = @$_FILES ['photo'];
                        }
                    
                    } 
                    if ($this->Users->save($user)){
                        @$user['photo'] = $_FILES ['photo'] ? Router::url('/webroot/profile/'.$_FILES ['photo'],true):'';
                        $user['access_token']= sha1(md5('youfeed'.$user['id'].'!@#$%'. $user ['token_id']).")(*&^%$");
                        $this->set([
                            'data' => $user,
                            'code' => '203',
                            'error' => '0',
                            'url'  => '',
                            'message'=> responseMsg(203),
                            '_serialize' => ['error','code','url','data','message']
                         ]);
                    }else{
                        $this->set([
                            'data' => '',
                            'code' => '408',
                            'error' => '1',
                            'url'  => '',
                            'message'=> responseMsg(408),
                            '_serialize' => ['error','code','url','data','message']
                         ]);
                    }
                */}
            }     
        }
    }

    /************************************************************************************************************
    * API                   => Forgot Password                                                                  *
    * Description           => It is used send forgot password mail..                                           *
    * Required Parameters   => email                                                                            *
    * Created by            => Sunil                                                                            *
    *************************************************************************************************************/
    public function forgotPassword(){
        $data = $this->request->data;
        if( isset($data['email'])){
            $userModel = TableRegistry::get('Users'); //use Cake\ORM\TableRegistry;
            $query = $userModel
            ->find()
            ->select(['id', 'username','email', 'password', 'photo'])
            ->where([
            'email' => $data['email'],
            ])
            ->first();

            if(isset($query->id) && $query->id > 0 ){
                $userData = $query->toArray();
                $this->loadModel('EmailContents');
                $pass = mt_rand (100000, 999999) ;
                // $password = $query->password = $pass;
                $query->forgot_password = $pass;

                unset($query->password);
                if ($userModel->save($query)){
                    $email = $userData['email'];
                    $name = $userData['username'];
                    $newpassword =  $pass;
                    //$this->getMailer('User')->send('ForgotPassword', [$email, $name, $newpassword]);
                    $this->sendUserEmailforgot($email,$name,$newpassword);
                    $this->set([
                        'data' => '',
                        'message'=> responseMsg(205),
                        'code'  => '205',
                        'error'  => '0',
                        'url'   =>'',
                        '_serialize' => ['error','code','data','message']
                    ]);
                   
                }else{
                    $this->set([
                        'data' => '',
                        'message'=> responseMsg(470),
                        'code'  => '470',
                        'error'  => '1',
                        'url'   =>'',
                        '_serialize' => ['error','code','data','message']
                    ]);
                }
            }else{
                $this->set([
                    'data' => '',
                    'message'=> responseMsg(411),
                    'code'  => '411',
                    'error'  => '1',
                    'url'   =>'',
                    '_serialize' => ['error','code','data','message']
                 ]);
            }
        }else{
            $this->set([
                'data' => '',
                'message'=> responseMsg(401),
                'code'  => '401',
                'error'  => '1',
                'url'   =>'',
                '_serialize' => ['error','code','data','message']
            ]);
        }
    }


    /************************************************************************************************************
    * API                   => verifyCode                                                                       *
    * Description           => It is used  verifyCode ..                                                        *
    * Required Parameters   => code,new_password,confirm_password                                               *
    * Created by            => Sunil                                                                            *
    *************************************************************************************************************/
    public function verifyCode(){
        $data = $this->request->data;
        if( isset($data['code']) && isset($data['new_password']) && isset($data['confirm_password'])){
            $userModel = TableRegistry::get('Users'); //use Cake\ORM\TableRegistry;
            $query = $userModel
            ->find()
            ->select(['id', 'username','email','password','forgot_password'])
            ->where([
            'forgot_password' => $data['code'],

            ])
            ->first();
            // print_r($query);
            if(!empty($query)){
                if((new DefaultPasswordHasher)->check($data['new_password'], $query->password)){
                    $this->set([
                        'data' => '',
                        'message'=> responseMsg(420),
                        'code'  => '420',
                        'error'  => '1',
                        'url'   =>'',
                        '_serialize' => ['error','code','data','message']
                    ]);
                    }else{
                    if(@$query->forgot_password != ''){    
                        if( $data['code'] ==  $query->forgot_password ){   
                        $data['new_password'] = trim($data['new_password']);
                        $data['confirm_password'] = trim($data['confirm_password']);
                        // //*strlen($_POST['new_password']) >= 6 &&*/ 
                            if( ($data['new_password'] == $data['confirm_password']) ){
                                $user = $userModel->get($query->id);
                                $user->password = $data['new_password'];
                                $user->forgot_password = '';
                                $user->user_status = 1;
                                    if( $userModel->save($user)){
                                    $this->set([
                                        'data' => '',
                                        'message'=> responseMsg(204),
                                        'code'  => '204',
                                        'error'  => '0',
                                        'url'   =>'',
                                        '_serialize' => ['error','code','data','message']
                                    ]);
                                    }else{
                                    $this->set([
                                        'data' => '',
                                        'message'=> responseMsg(416),
                                        'code'  => '416',
                                        'error'  => '1',
                                        'url'   =>'',
                                        '_serialize' => ['error','code','data','message']
                                    ]);
                                    }
                            }else{
                                $this->set([
                                    'data' => '',
                                    'message'=> responseMsg(415),
                                    'code'  => '415',
                                    'error'  => '1',
                                    'url'   =>'',
                                    '_serialize' => ['error','code','data','message']
                                ]);
                            }
                        }else{
                            $this->set([
                                'data' => '',
                                'message'=> responseMsg(422),
                                'code'  => '422',
                                'error'  => '1',
                                'url'   =>'',
                                '_serialize' => ['error','code','data','message']
                            ]);
                        }
                    }else{
                        $this->set([
                            'data' => '',
                            'message'=> responseMsg(422),
                            'code'  => '422',
                            'error'  => '1',
                            'url'   =>'',
                            '_serialize' => ['error','code','data','message']
                        ]);
                    }
                }
          }else{
                $this->set([
                    'data' => '',
                    'message'=> responseMsg(406),
                    'code'  => '406',
                    'error'  => '1',
                    'url'   =>'',
                    '_serialize' => ['error','code','data','message']
                ]);
          }   
                
        }else{
            $this->set([
                'data' => '',
                'message'=> responseMsg(406),
                'code'  => '406',
                'error'  => '1',
                'url'   =>'',
                '_serialize' => ['error','code','data','message']
            ]);
       }
    }


    /************************************************************************************************************
    * API                   => Forgot Password                                                                  *
    * Description           => It is used send forgot password mail..                                           *
    * Required Parameters   => email                                                                            *
    * Created by            => Sunil                                                                            *
    *************************************************************************************************************/
    public function profile(){
        $data = $this->request->data;

        }
        
    /************************************************************************************************************
    * API                   => Create my cuisines                                                                      *
    * Description           => It is used to Create Group                                                        *
    * Parameters            => id,access_token,g_name,g_discription,photo,g_category                           *
    * Created by            => Sunil                                                                             *
    *************************************************************************************************************/
    public function myCuisiness(){
        $this->loadModel('MyCuisines');
        $mycuisines = $this->MyCuisines->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $mycuisines = $this->MyCuisines->patchEntity($mycuisines, $this->request->data);
             // Profile Photo
          
            if (!empty ($_FILES ['photo']['name'] )){
                $imageFormats = array ('jpg','JPG','gif','GIF','jpeg','JPEG','png','PNG','bmp');
                $user_photo = @$_FILES ['photo'];
                $randstring = md5 ( time () + rand () );
                $valid_image = 1;
                $size_byte = filesize ( $_FILES ['photo'] ['tmp_name'] ); // returns in bytes
                $extension = $this->getFileExtension ( $_FILES ['photo'] ['name'] );
                $img_name = $this->generate_file_name ( $_FILES ['photo'] ['name'] );
                $img_tmp = $_FILES ['photo'] ['tmp_name'];
                $img_tmp_path = $this->webroot.'storage/group/tmp' . $img_name;
                $img_path =  $this->webroot.'storage/group/' . $img_name;
                $img_thumb_path = $this->webroot.'storage/group/thumb_' . $img_name;
                if (in_array ( $extension, $imageFormats )){
                    if (move_uploaded_file ( $img_tmp, $img_path )){
                        $valid_image = 1;
                        @$_FILES ['photo'] = $img_name;
                    }
                } 
                else{
                    $valid_image = 0;
                }
                if (! empty ( $_FILES ['photo'] )){
                    $mycuisines['photo'] = @$_FILES ['photo'];
                    
                   /* $this->loadModel('Photos');
                    $PhotosModel = TableRegistry::get('Photos');
                    $photos = $this->Photos->newEntity();
                    //$hashtags = $this->Hashtags->patchEntity($hashvalue, $this->request->data);
                    $photos['p_u_id'] = $data ['id'];
                    $photos['p_type'] = '1';
                    $photos['photo'] =  $group['photo'];
                    // print_r($hashtags); exit;
                    $this->Photos->save($photos);*/
                
                }
            } 
            
            //print_r($group);  exit;
            if ($MyCuisines = $this->MyCuisines->save($mycuisines)) {
                $lastId = $Groups->g_id;
                $this->loadModel('GroupSubscribes');
                $groupsubscribe = $this->GroupSubscribes->newEntity();
                $groupsubscribe['gs_u_id'] = $data['id'];
                $groupsubscribe['gs_g_id'] = $lastId;
                $this->GroupSubscribes->save($groupsubscribe);

                $this->set([
                    'data' => $_FILES,
                    'code' => '207',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(207),
                    '_serialize' => ['error','code','url','data','message']
                ]);
               
            } else {
                $this->set([
                    'data' => $_FILES,
                    'code' => '417',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(417),
                    '_serialize' => ['error','code','url','data','message']
                ]);
               
            }
        }
    }

    /************************************************************************************************************
    * API                   => Group subscribe                                                                  *
    * Description           => It is used to Group subscribe                                                    *
    * Parameters            => id,access_token,g_id,id                                                          *
    * Created by            => Sunil                                                                            *
    *************************************************************************************************************/
    public function groupSubscribe(){
        $this->loadModel('GroupSubscribes');
        $groupsubscribe = $this->GroupSubscribes->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $grlupsubscribeModel = TableRegistry::get('GroupSubscribes'); //use Cake\ORM\TableRegistry;
            if($data['type'] == '1'){
                $group = @$data['gs_g_id'];
                $cond = 'gs_g_id = '.$group;
           
            }else if(@$data['type'] == '2'){
                $hashtag = $data['gs_hashtag'];
                $cond = " LOWER(`gs_hashtag`) LIKE '%$hashtag%'";
               
            }else if(@$data['type'] == '3'){
                $city = $data['gs_city'];
                $cond = " `gs_city` LIKE '%$city%'";
            }
            $check_groupalready_subscribe = $grlupsubscribeModel
            ->find()
            ->where(['gs_u_id' => $data['id']])
            ->where([@$cond])
           
           ->first();
           if(empty($check_groupalready_subscribe)){
                $groupsubscribe = $this->GroupSubscribes->patchEntity($groupsubscribe, $this->request->data);
                // Profile Photo
                $groupsubscribe['gs_u_id'] = intval($data['id']);
                unset( $groupsubscribe['id']);
                unset( $groupsubscribe['access_token']);
                // print_r($groupsubscribe);  exit;
                if ($this->GroupSubscribes->save($groupsubscribe)) {
                    $this->set([
                        'data' => '',
                        'code' => '210',
                        'error' => '0',
                        'url'  => '',
                        'message'=> responseMsg(210),
                        '_serialize' => ['error','code','url','data','message']
                    ]);
                   
                } else {
                    $this->set([
                        'data' => '',
                        'code' => '424',
                        'error' => '1',
                        'url'  => '',
                        'message'=> responseMsg(424),
                        '_serialize' => ['error','code','url','data','message']
                    ]);
                }
            }else{
                    $this->set([
                        'data' => '',
                        'code' => '423',
                        'error' => '1',
                        'url'  => '',
                        'message'=> responseMsg(423),
                        '_serialize' => ['error','code','url','data','message']
                    ]);
            }
        }
    }

    /******************************************************************************
    * API                   => Un Subscribe group                                 *
    * Description           => It is used for Un Subscribe form group             *
    * Required Parameters   =>                                                    *
    * Created by            => Sunil                                              *   *******************************************************************************/
    public function unSubscribe(){
        $data = $this->request->data;
        if ($this->request->is('post')){
            $groupsubscribeModel = TableRegistry::get('GroupSubscribes');
            if($data['type'] == '1'){
                $group = @$data['gs_g_id'];
                $cond = 'gs_g_id = '.$group;
           
            }else if(@$data['type'] == '2'){
                $hashtag = $data['gs_hashtag'];
                $cond = " LOWER(`gs_hashtag`) LIKE '%$hashtag%'";
               
            }else if(@$data['type'] == '3'){
                $city = $data['gs_city'];
                $cond = " `gs_city` LIKE '%$city%'";
            }
            $existgroupsubscribe = $groupsubscribeModel
            ->find()
            ->where(['gs_u_id'=>@$data['id']])
            ->where([@$cond])
            ->first(); 
            
            if(!empty( $existgroupsubscribe)){
                $existgroupsubscribe = $existgroupsubscribe->toArray();
                if(!empty($existgroupsubscribe)){
                     $query = $groupsubscribeModel->query();
                        $query->delete()
                        ->where(['gs_u_id'=>@$data['id']])
                        ->where([@$cond])
                        ->execute();
                        $this->set([
                            'data' => '',
                            'code' => '213',
                            'error' => '0',
                            'url'  => '',
                            'message'=> 'Unsubscribe group',//'responseMsg(213)',
                            '_serialize' => ['error','code','url','data','message']
                        ]);
                }else{
                        $this->set([
                                    'data' => '',
                                    'code' => '406',
                                    'error' => '1',
                                    'url'  => '',
                                    'message'=> responseMsg(406),
                                    '_serialize' => ['error','code','url','data','message']
                                 ]);
                }
            }
        }
    }    
    /*********************************************************************************************************
    * API                   => Category List                                                                  *
    * Description           => It is used for get Category List..                                             *
    * Required Parameters   =>                                                                                *
    * Created by            => Sunil                                                                          *
    **********************************************************************************************************/
    public function categoryList(){
        if ($this->request->is('post')){
            $data = $this->request->data;
            
            $CategoriesModel = TableRegistry::get('Categories'); //use Cake\ORM\TableRegistry;
            $query = $CategoriesModel
            ->find();
            // debug($query); exit;
            if(!empty($query)){
                $query =  $query->toArray();
                
                $this->set([
                    'data' => $query,
                    'code' => '211',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(211),
                    '_serialize' => ['error','code','url','data','message','page_count']
                 ]);
           }else{
                $this->set([
                    'data' => '',
                    'code' => '427',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(427),
                    '_serialize' => ['error','code','url','data','message']
                 ]);
           }
        }
    }

    /**********************************************************************************************************
    * API                   => Group List                                                                     *
    * Description           => It is used for get Group List..                                                *
    * Required Parameters   =>                                                                                *
    * Created by            => Sunil                                                                          *
    **********************************************************************************************************/
    public function groupList(){
        if ($this->request->is('post')) {
            
            $data = $this->request->data;
            $pageNo = @$data['page']?$data['page']:1;
            $noOfRecord = '20';
            if(@$data['my_group'] == '1'){
                $cond = "g_u_id = ".$data['id'];
                $gs_cond = 'GroupSubscribes.gs_g_id = Groups.g_id';
                if(!empty(@$data['category'])){
                    $category = $data['category'];
                    $cond .= " AND LOWER(`g_category`) LIKE '%$category%'";
                }
            }else if(@$data['my_group_subscribe'] == '1'){
                // my and subscribe group list
                //$cond = "g_u_id = ".$data['id'];
                $cond = "1=1";
                $gs_cond ="1=1";
                if(!empty(@$data['category'])){
                    $category = $data['category'];
                    $cond .= " AND LOWER(`g_category`) LIKE '%$category%'";

                }
                 $cond .= " AND GroupSubscribes.gs_g_id = Groups.g_id";
                 $cond .= " AND GroupSubscribes.gs_u_id = ".$data['id'];
                 $gs_condq = 
                 "->join([
                    'GroupSubscribes' => [
                        'table' => 'group_subscribes',
                        'alias' => 'GroupSubscribes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'GroupSubscribes.gs_g_id = Groups.g_id'
                            ) 
                    ]
                ])";
                $gs_cond = 'GroupSubscribes.gs_g_id = Groups.g_id';
            }else if(@$data['other_group_subscribe'] == '1'){
                // my and subscribe group list
                //$cond = "g_u_id = ".$data['id'];
                $cond = "1=1";
                $gs_cond ="1=1";
                if(!empty(@$data['category'])){
                    $category = $data['category'];
                    $cond .= " AND LOWER(`g_category`) LIKE '%$category%'";

                }
                if(!empty(@$data['groupname'])){
                     $groupname = $data['groupname'];
                    $cond .= " AND LOWER(`g_name`) LIKE '%$groupname%'";
                }
                 $cond .= " AND GroupSubscribes.gs_g_id = Groups.g_id";
                 $cond .= " AND GroupSubscribes.gs_g_id not in (select gs_g_id from group_subscribes where gs_u_id = ".$data['id']." AND gs_g_id is not null )";
               //  $cond .= " AND GroupSubscribes.gs_u_id != ".$data['id'];
                 $gs_condq = 
                 "->join([
                    'GroupSubscribes' => [
                        'table' => 'group_subscribes',
                        'alias' => 'GroupSubscribes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'GroupSubscribes.gs_g_id = Groups.g_id'
                            ) 
                    ]
                ])";
                $gs_cond = 'GroupSubscribes.gs_g_id = Groups.g_id';
                $gs_cond .= " AND GroupSubscribes.gs_u_id != ".$data['id'];
            }
            else{
                 $cond = '1=1';
                 $gs_cond = '1=1';
                 if(!empty(@$data['category'])){
                    $category = $data['category'];
                    $cond .= " AND LOWER(`g_category`) LIKE '%$category%'";
                      $cond .= " AND GroupSubscribes.gs_g_id != Groups.g_id";
                 $cond .= " AND GroupSubscribes.gs_u_id != ".$data['id'];
                 $gs_cond = 'GroupSubscribes.gs_g_id != Groups.g_id';
                }
            }
            $GroupsModel = TableRegistry::get('Groups'); //use Cake\ORM\TableRegistry;
            $GroupSubscribesModel = TableRegistry::get('GroupSubscribes');
            $query = $GroupsModel
            ->find()
            ->contain(['GroupSubscribes'])
            ->join([
                    'GroupSubscribes' => [
                        'table' => 'group_subscribes',
                        'alias' => 'GroupSubscribes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                               'GroupSubscribes.gs_g_id = Groups.g_id'
                            ) 
                    ]
                ])
            ->where([$cond])
            ->group(['Groups.g_id'])
            ->count();
            //$StaticPages = $query->toArray();
            $noOfPages             = intval($query) / intval($noOfRecord);
            $limitParam            = ($pageNo - 1) * $noOfRecord;
            $noOfPages             = ceil($noOfPages);
            $totalPage = ceil($query/$noOfRecord);
            $StaticPagesCount = $totalPage;

            $query = $GroupsModel
            ->find()
            ->select(['Groups.g_id','Groups.g_name','Groups.g_discription','Groups.g_u_id','Groups.photo','Groups.g_category','GroupSubscribes.gs_id','GroupSubscribes.gs_g_id','GroupSubscribes.gs_hashtag','GroupSubscribes.gs_city','GroupSubscribes.gs_u_id','total_subscribe'=>$GroupSubscribesModel->find()->func()->count('GroupSubscribes.gs_g_id = Groups.g_id')])
            //->contain(['GroupSubscribes'])
            ->join([
                    'GroupSubscribes' => [
                        'table' => 'group_subscribes',
                        'alias' => 'GroupSubscribes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                $gs_cond
                                //'GroupSubscribes.gs_g_id = Groups.g_id'
                            ) 
                    ]
                ])
            ->order(['g_id'=>'DESC'])
            ->limit(intval($noOfRecord))
            ->offset($limitParam)
            ->group(['Groups.g_id'])
            ->where([ $cond]);
            //debug($query); exit;
            if(!empty($query)){
                $test['group'] =array();
                $query =  $query->toArray();
                foreach ($query as $groupkey => $groupvalue) {
                    $data1 =  $groupvalue;
                   // $data1['total_subscribe'] = sizeof($groupvalue['group_subscribes']);
                    $data1['photo'] = $groupvalue['photo'] ? Router::url('/webroot/storage/group/'.$groupvalue['photo'],true):'';
                    array_push($test['group'],$data1);
                }
                $this->set([
                    'data' => $test,
                    'code' => '212',
                    'page_count'=>$StaticPagesCount,
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(212),
                    '_serialize' => ['error','code','url','data','message','page_count']
                 ]);
           }else{
                $this->set([
                    'data' => '',
                    'code' => '427',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(427),
                    '_serialize' => ['error','code','url','data','message']
                 ]);
           }
        }
    }

    /*************************************************************************************************************
    * API                   => Create Post                                                                       *
    * Description           => It is used to Create Post                                                         *
    * Parameters            => id,access_token,description,photo,category,group,lat,lng,type                     *
    *                          (type=>Everyone=1,nearby=2,group=3)                                               *
    * Created by            => Sunil                                                                             *
    *************************************************************************************************************/
    public function createPost(){
        $this->loadModel('Posts');
        $post = $this->Posts->newEntity();
        if ($this->request->is('post')) {
             $data = $this->request->data;
             if(!empty($data['description'])){
                @$text = str_replace("#"," #",$data['description']);;
                $exploded = $this->multiexplode(array(",",".","|",":"," ","?"),$text);
                $hashtag = array();
                foreach ($exploded as $keyexploded => $valueexploded) {
                    if (substr($valueexploded, 0, 1) === '#') {
                        if(strlen($valueexploded) != '1'){ 
                            $hashtag[] = $valueexploded;
                        }
                    }
                }
                if(!empty($hashtag))
                {
                    $hashtag = implode(',', $hashtag);
                }    
                $post['hashtag'] = @$hashtag?$hashtag:''; 
                $post['added_date']=  date ( 'Y-m-d H:i:s' );
            }
            $data = $this->request->data;
            $post = $this->Posts->patchEntity($post, $this->request->data);
             // Profile Photo
            if (!empty ($_FILES ['photo']['name'] )){
                $imageFormats = array ('jpg','JPG','gif','GIF','jpeg','JPEG','png','PNG','bmp');
                $user_photo = @$_FILES ['photo'];
                $randstring = md5 ( time () + rand () );
                $valid_image = 1;
                $size_byte = filesize ( $_FILES ['photo'] ['tmp_name'] ); // returns in bytes
                $extension = $this->getFileExtension ( $_FILES ['photo'] ['name'] );
                $img_name = $this->generate_file_name ( $_FILES ['photo'] ['name'] );
                $img_tmp = $_FILES ['photo'] ['tmp_name'];
                $img_tmp_path = $this->webroot.'storage/post/tmp' . $img_name;
                $img_path =  $this->webroot.'storage/post/' . $img_name;
                $img_thumb_path = $this->webroot.'storage/post/thumb_' . $img_name;
                if (in_array ( $extension, $imageFormats )){
                    if (move_uploaded_file ( $img_tmp, $img_path )){
                        $valid_image = 1;
                        @$_FILES ['photo'] = $img_name;
                    }
                } 
                else{
                    $valid_image = 0;
                }
                if (! empty ( $_FILES ['photo'] )){
                    $post['photo'] = @$_FILES ['photo'];
                    $this->loadModel('Photos');
                    $PhotosModel = TableRegistry::get('Photos');
                    $photos = $this->Photos->newEntity();
                    //$hashtags = $this->Hashtags->patchEntity($hashvalue, $this->request->data);
                    $photos['p_u_id'] = $data ['id'];
                    $photos['p_type'] = '2';
                     $photos['photo'] =  $post['photo'];
                    // print_r($hashtags); exit;
                    $this->Photos->save($photos);
                

                }
            } 
            unset($post['id']);
            $post['u_id'] = $data ['id'];
            if(!empty($data['description'])){
                @$text = str_replace("#"," #",$data['description']);;
                $exploded = $this->multiexplode(array(",",".","|",":"," "),$text);
                $hashtag = array();
                foreach ($exploded as $keyexploded => $valueexploded) {
                    if (substr($valueexploded, 0, 1) === '#') {
                        $hashtag[] = $valueexploded;
                    }
                }
                if(!empty($hashtag))
                {
                    $hashtag = implode(',', $hashtag);
                }    
                $post['hashtag'] = @$hashtag?$hashtag:''; 
                $post['added_date']=  date ( 'Y-m-d H:i:s' );
            }
            if ($this->Posts->save($post)) {
                $this->set([
                    'data' => '',
                    'code' => '208',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(208),
                    '_serialize' => ['error','code','url','data','message']
                ]);
                $this->loadModel('Subscribes');
                $SubscribesModel = TableRegistry::get('Subscribes');
                
                // Add Hash Tag in DB
                
                $this->loadModel('Hashtags');
                $HashtagsModel = TableRegistry::get('Hashtags');
                

                $query = $HashtagsModel->find()->select(['h_hashtag', 'h_id']);
                $query->hydrate(false); // Results as arrays intead of entities
                $result = $query->toArray();
                $result = \Cake\Utility\Hash::combine($result, '{n}.h_id', '{n}.h_hashtag');
                $hash = explode(',', $post['hashtag']);
                $query = array_diff($hash, $result);  //equals (1,2,3,4)
                if(!empty($query[0])){
                    foreach ($query as $hashkey => $hashvalue) {
                         # code...
                        $hashtags = $this->Hashtags->newEntity();
                        //$hashtags = $this->Hashtags->patchEntity($hashvalue, $this->request->data);
                        $hashtags['h_hashtag'] =$hashvalue;
                       // print_r($hashtags); exit;
                        $this->Hashtags->save($hashtags);

                        $subscribes = $this->Subscribes->newEntity();
                        $subscribes['s_type'] =2;
                        $subscribes['s_name'] =substr($hashvalue, 1);
                        $this->Subscribes->save($subscribes);
                    }
                }
                // Places Save on db 
                $this->loadModel('Places');
                $PlacesModel = TableRegistry::get('Places');
                $query = $PlacesModel->find()->select(['p_name', 'p_id']);
                $query->hydrate(false); // Results as arrays intead of entities
                $result = $query->toArray();

                $result = \Cake\Utility\Hash::combine($result, '{n}.p_id', '{n}.p_name');
                $hash = array($post['city']);
                $query = array_diff($hash, $result);  //equals (1,2,3,4)
                $places = $this->Places->newEntity();
                $places['p_name'] = @$query[0];
                if(!empty($places['p_name'])){
                    $this->Places->save($places);
                    $subscribes = $this->Subscribes->newEntity();
                    $subscribes['s_type'] =3;
                    $subscribes['s_name'] =$query[0];
                    $this->Subscribes->save($subscribes);
                }
                if(!empty($data['groups'])){
                    $this->loadModel('Groups');
                    $GroupsModel = TableRegistry::get('Groups');
                    $querygroup = $GroupsModel->find()
                                    ->select(['g_id', 'g_name'])
                                    ->where(['g_id' => $data['groups']])
                                    ->first();

                    $querygroupcheckdb = $SubscribesModel->find()->select(['s_id', 's_name','s_type'])->where(['s_type'=>'1']);
                    $querygroupcheckdb->hydrate(false); // Results as arrays intead of entities
                    $querygroupcheckdb = $querygroupcheckdb->toArray();
                    $querygroupcheckdb = \Cake\Utility\Hash::combine($querygroupcheckdb, '{n}.s_id', '{n}.s_name');
                    $hash = array($querygroup['g_name']);
                    $querygr = array_diff($hash, $querygroupcheckdb);  //equals (1,2,3,4)
                    $Groups['s_name'] = @$querygr[0];
                    if(!empty($Groups['s_name'])){
                        $subscribes = $this->Subscribes->newEntity();
                        $subscribes['s_type'] =1;
                        $subscribes['s_name'] =$querygr[0];
                        $this->Subscribes->save($subscribes);
                    }
                                    
                }
               
            } else {
                $this->set([
                    'data' => '',
                    'code' => '418',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(418),
                    '_serialize' => ['error','code','url','data','message']
                ]);
               
            }
        }
    }

    public function multiexplode ($delimiters,$string) {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    /*************************************************************************************************************
    * API                   => Comment on Post                                                                   *
    * Description           => It is used to Create Post                                                         *
    * Parameters            => id,access_token,comment,p_id                                                      *
    * Created by            => Sunil                                                                             *
    *************************************************************************************************************/
    public function commentPost(){
        $this->loadModel('Posts');
        $this->loadModel('PostComments');
        $this->loadModel('Notifications');
        
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $PostsModel = TableRegistry::get('Posts'); //use Cake\ORM\TableRegistry;
            $postquery = $PostsModel->find();
            $postquery = $PostsModel
            ->find()
            ->where(['p_id' => $data['p_id']])
            //->where(['u_id' => $data['id']])
            ->first();
            if(!empty($postquery)){
                
                $postquery =  $postquery->toArray();
               // print_r($postquery);  exit;
                $postcomment = $this->PostComments->newEntity();
                $postcomment = $this->PostComments->patchEntity($postcomment, $this->request->data);
               
                $postcomment['pc_u_id'] = $data ['id'];
                $postcomment['pc_p_id'] = $data ['p_id'];
                $postcomment['added_date']=  date ( 'Y-m-d H:i:s' );
                unset($postcomment['p_id']);
                unset($postcomment['id']);
                //print_r($postcomment);  exit;

                    
                if ($this->PostComments->save($postcomment)) {

                    if($postquery['u_id'] != $data['id']){
                        $message = 'Someone commented on you post "'.$postcomment['comment'].'"';
                        $data['noti_id'] = 1 ;
                        $_POST = array ();
                        $_POST ['receiver_id'] = $postquery['u_id'];
                        $_POST ['relData'] = $data;
                        $_POST ['message'] = $message;
                        $this->sendPushNotification();
                        $NotificationModel = TableRegistry::get('Notifications');
                        $notification = $this->Notifications->newEntity();
                        $notification = $this->Notifications->patchEntity($notification, $this->request->data);
                        $notification['n_type'] = '1';
                        $notification['n_message'] = $message;
                        $notification['n_u_id'] =  $postquery['u_id'];
                        $notification['n_p_id'] =  $postquery['p_id'];
                        $notification['n_date'] =  date ( 'Y-m-d H:i:s' );
                        //print_r($notification); exit;
                        $this->Notifications->save($notification); 

                    }   
                    $this->set([
                        'data' => '',
                        'code' => '209',
                        'error' => '0',
                        'url'  => '',
                        'message'=> responseMsg(209),
                        '_serialize' => ['error','code','url','data','message']
                    ]);
                
                } else {
                    $this->set([
                        'data' => '',
                        'code' => '405',
                        'error' => '1',
                        'url'  => '',
                        'message'=> responseMsg(405),
                        '_serialize' => ['error','code','url','data','message']
                    ]);
                   
                }
            }else{
                    $this->set([
                        'data' => '',
                        'code' => '407',
                        'error' => '1',
                        'url'  => '',
                        'message'=> responseMsg(407),
                        '_serialize' => ['error','code','url','data','message']
                    ]);

            }
        }
    }
    /*************************************************************************************************************
    * API                   => upvote/downvote on Post                                                           *
    * Description           => It is used to upvote/downvote on Post                                             *
    * Parameters            => id,access_token,p_id,status                                                       *
    * Created by            => Sunil                                                                             *
    *************************************************************************************************************/
    public function vote(){
        $this->loadModel('Posts');
        $this->loadModel('Votes');
        $this->loadModel('Notifications');
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $PostsModel = TableRegistry::get('Posts'); //use Cake\ORM\TableRegistry;
            $VotesModel = TableRegistry::get('Votes'); //use Cake\ORM\TableRegistry;
            $postquery = $PostsModel->find();
            $postquery = $PostsModel
            ->find()
            ->where(['p_id' => $data['p_id']])
            ->first()
            ->toArray();
            if(!empty($postquery)){
                //$postquery =  $postquery->toArray();
                //print_r($postquery); exit;
                $votequery = $VotesModel->find();
                $votequery = $VotesModel
                ->find()
                ->where(['v_p_id' => $data['p_id']])
                ->where(['v_u_id' => $data['id']])
                ->first();
               // print_r($votequery); exit;
                if(empty($votequery))
                {
                    $vote = $this->Votes->newEntity();
                    $vote = $this->Votes->patchEntity($vote, $this->request->data);
                    $vote['v_u_id'] = $data ['id'];
                    $vote['v_p_id'] = $data ['p_id'];
                    $vote['vote'] = $data ['vote'];
                   
                   
                }
                else{
                    $vote = $this->Votes->get($votequery['v_id']);
                    $vote = $this->Votes->patchEntity($vote, $this->request->data);
                    $vote['vote'] = $data ['vote'];
                    
                }
                unset($vote['id']);
                unset($vote['p_id']);
                unset($vote['access_token']);
                if ($this->Votes->save($vote)) {
                    $this->set([
                        'data' => '',
                        'code' => '215',
                        'error' => '0',
                        'url'  => '',
                        'message'=> responseMsg(215),
                        '_serialize' => ['error','code','url','data','message']
                    ]);
                    $post = $this->Posts->get($postquery['p_id']);
                    if($data ['vote'] == 1){
                         $post['vote'] = $postquery['vote']+1;
                    }else{
                        if(@$data['old_status'] == 1){
                            $post['vote'] = $postquery['vote']-1;
                        }else if(@$data['old_status'] == 2){
                            $post['vote'] = $postquery['vote']+1;
                        }else{
                             $post['vote'] = $postquery['vote']-1;
                        }
                    }

                    $this->Posts->save($post);
                    if($postquery['u_id'] != $data['id']){
                        if($post['vote'] == '11'){
                            $message = 'Your post has been featured in hot';
                            $data['noti_id'] = 3 ;
                        }else{
                            $message = 'Someone vote on you post';
                            $data['noti_id'] = 2 ;
                        }
                        $_POST = array ();
                        $_POST ['receiver_id'] = $postquery['u_id'];
                        $_POST ['relData'] = $data;
                        $_POST ['message'] = $message;
                        //print_r($_POST);  exit;
                        $this->sendPushNotification();
                        $NotificationModel = TableRegistry::get('Notifications');
                        $notification = $this->Notifications->newEntity();
                        $notification = $this->Notifications->patchEntity($notification, $this->request->data);
                        if($post['vote'] == '11'){
                            $notification['n_type'] = '3';
                        }else{
                             $notification['n_type'] = '2';
                        }
                        $notification['n_message'] = $message;
                        $notification['n_u_id'] =  $postquery['u_id'];
                        $notification['n_p_id'] =  $postquery['p_id'];
                        $notification['n_date'] =  date ( 'Y-m-d H:i:s' );
                        //print_r($notification); exit;
                        $this->Notifications->save($notification); 

                    }   
                   
                } else {
                    $this->set([
                        'data' => '',
                        'code' => '405',
                        'error' => '1',
                        'url'  => '',
                        'message'=> responseMsg(405),
                        '_serialize' => ['error','code','url','data','message']
                    ]);
                   
                }
            }else{
                    $this->set([
                        'data' => '',
                        'code' => '407',
                        'error' => '1',
                        'url'  => '',
                        'message'=> responseMsg(407),
                        '_serialize' => ['error','code','url','data','message']
                    ]);

            }
        }
    }
    /**********************************************************************************************************
    * API                   => Post List                                                                     *
    * Description           => It is used for get Post List..                                                *
    * Required Parameters   =>                                                                                *
    * Created by            => Sunil                                                                          *
    **********************************************************************************************************/
    public function postList(){
        if ($this->request->is('post')) {
            $data = $this->request->data;

            $uid = @$data['id'];
            $cond = 1;
            $pageNo = @$data['page']?$data['page']:1;
            $noOfRecord = 20;

            if(@$data['search_type'] == 'nearby'){
                $lat = $data['lat'];
                $lng =$data['lng'];
                $radius = $data['radius'];
                if(@$data['type'] != 2)
                {    
                    $order  = 'Posts.p_id DESC';
                    $cond = "(ROUND(DEGREES(ACOS((SIN(RADIANS(Posts.lat))*SIN(RADIANS(" . $lat . "))+ COS(RADIANS(Posts.lat))*COS(RADIANS(" . $lat . ")))*(COS(RADIANS(Posts.lng - (" . $lng . "))))))*60*1.1515,2)) <".$radius;
                }else{
                    $cond = "(ROUND(DEGREES(ACOS((SIN(RADIANS(Posts.lat))*SIN(RADIANS(" . $lat . "))+ COS(RADIANS(Posts.lat))*COS(RADIANS(" . $lat . ")))*(COS(RADIANS(Posts.lng - (" . $lng . "))))))*60*1.1515,2)) <".$radius;
                    //$cond .= ' AND vote > 10';
                    $order  = 'Posts.vote DESC';
                }
                
            }else if(@$data['search_type'] == 'subscription'){
                $cond= "GroupSubscribes.gs_u_id = $uid";
                $cond .= " AND Posts.p_id is not null"; 
                //$cond .= " AND Posts.groups is not null";   
               //echo  $subbb = "Posts.hashtag LIKE %GroupSubscribes.gs_hashtag%"; exit;
                $order  = 'Posts.p_id DESC';
            }else if(@$data['search_type'] == 'hashtag'){
                $hashtag = $data['hashtag'];
                $cond = " LOWER(`hashtag`) LIKE '%$hashtag%'";
                if(@$data['type'] != 2){
                    $order  = 'Posts.p_id DESC';
                    
                }else{
                   $order  = 'Posts.vote DESC';
                }
                //
                // get hashtag subscribe or  not
                $gscond = " LOWER(`gs_hashtag`) LIKE '%$hashtag%'";
                $gsModel = TableRegistry::get('GroupSubscribes'); //use Cake\ORM\TableRegistry;
               
                 $check_groupalready_subscribe = $gsModel
                ->find()
                ->where(['gs_u_id' => $data['id']])
                ->where([@$gscond])
                ->count();
            }else if(@$data['search_type'] == 'category'){
                $category = $data['category'];
                $cond = "category=$category";
                 $order  = 'Posts.p_id DESC';
            }else if(@$data['search_type'] == 'group'){
                $group = $data['group'];
                $cond = "groups=$group";
                 $order  = 'Posts.p_id DESC';
            }else if(@$data['search_type'] == 'places'){
                $place = $data['place'];
                $cond = "places=$place";
                 $order  = 'Posts.p_id DESC';
            }else if(@$data['search_type'] == 'city'){
                $city = $data['city'];
                $cond = " `city` LIKE '%$city%'";
                //$cond = "Post.city=$city";
                if(@$data['type'] != 2){
                    $order  = 'Posts.p_id DESC';
                    
                }else{
                   $order  = 'Posts.vote DESC';
                }
                //$order  = 'Posts.p_id DESC';
                 // get city subscribe or  not
                $gscond = " LOWER(`gs_city`) LIKE '%$city%'";
                $gsModel = TableRegistry::get('GroupSubscribes'); //use Cake\ORM\TableRegistry;
                $check_groupalready_subscribe = $gsModel
                ->find()
                ->where(['gs_u_id' => $data['id']])
                ->where([@$gscond])
                ->count();

            }else if(@$data['search_type'] == 'popular'){    
                $cond = 'vote > 10';
                $order  = 'Posts.vote DESC';
            }else{
                $cond = '1=1';
                $order  = 'Posts.p_id DESC';
            }
            // 
            $new_cond = array("Votes.v_u_id" => $uid);
            if(@$data['search_type'] != 'subscription'){
                $PostsModel = TableRegistry::get('Posts'); //use Cake\ORM\TableRegistry;
                $query = $PostsModel->find();
                $query = $PostsModel
                ->find()
                ->order([$order])
                ->contain(['PostComments'])
                ->contain(['Votes'=> function(\Cake\ORM\Query $q) use($new_cond) {
                    return $q->where([$new_cond]);
                }])
                ->where([$cond])
                ->count();
                //$StaticPages = $query->toArray();
                $noOfPages             = intval($query) / intval($noOfRecord);
                $limitParam            = ($pageNo - 1) * $noOfRecord;
                $noOfPages             = ceil($noOfPages);
                $postcount = intval($query); 
                $totalPage = ceil($query/$noOfRecord);
                $StaticPagesCount = $totalPage;
                
                    //print_r($cond1); exit;
                $query = $PostsModel
                ->find()
                ->order([$order])
                ->limit(intval($noOfRecord))
                ->offset($limitParam)
                ->contain(['PostComments'])
                ->contain(['Votes'=> function(\Cake\ORM\Query $q) use($new_cond) {
                    return $q->where([$new_cond]);
                }])
                ->where([$cond]);
            }else{
                $GroupSubscribesModel = TableRegistry::get('GroupSubscribes');
                $PostCommentsModel = TableRegistry::get('PostComments');
                $query  = $GroupSubscribesModel->find()
                ->select(['Posts.p_id','Posts.photo','Posts.description','Posts.type','Posts.category','Posts.groups','Posts.lat','Posts.lng','Posts.added_date','Posts.u_id','Posts.hashtag','Posts.places','Posts.city','Posts.vote','Votes.v_id','Votes.v_p_id','Votes.v_u_id','Votes.vote','PostComments.pc_id','PostComments.pc_p_id','count'=>$PostCommentsModel->find()->func()->count('PostComments.pc_p_id')])
                 ->distinct(['Posts.p_id','Posts.photo','Posts.description','Posts.type','Posts.category','Posts.groups','Posts.lat','Posts.lng','Posts.added_date','Posts.u_id','Posts.hashtag','Posts.places','Posts.city','Posts.vote'])
                ->where([$cond])
                ->join([
                    'Posts' => [
                        'table' => 'posts',
                        'alias' => 'Posts',
                        'type' => 'LEFT',
                        'conditions'=>array(
                                'or'=>array(
                                'Posts.groups = GroupSubscribes.gs_g_id and GroupSubscribes.gs_hashtag = "" and GroupSubscribes.gs_city = ""',
                                //'Posts.hashtag LIKE' =>'%#hashtag%',
                                "Posts.hashtag LIKE concat('%',GroupSubscribes.gs_hashtag,'%') and GroupSubscribes.gs_g_id is null and GroupSubscribes.gs_city = ''",
                                ),
                         ),
                        //'conditions' => 'posts.p = topics.id'
                    ],
                    'Votes' => [
                        'table' => 'votes',
                        'alias' => 'Votes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'Votes.v_u_id ='.$uid,
                                'Votes.v_p_id = Posts.p_id'
                            ) 
                    ], 
                    'PostComments' => [
                        'table' => 'post_comments',
                        'alias' => 'PostComments',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'PostComments.pc_p_id = Posts.p_id'
                            ) 
                    ]
                ])->count();
                //$StaticPages = $query->toArray();
                $noOfPages             = intval($query) / intval($noOfRecord);
                $limitParam            = ($pageNo - 1) * $noOfRecord;
                $noOfPages             = ceil($noOfPages);
               
                $totalPage = ceil($query/$noOfRecord);
                $StaticPagesCount = $totalPage;

                $GroupSubscribesModel = TableRegistry::get('GroupSubscribes');
                $PostCommentsModel = TableRegistry::get('PostComments');
                $query  = $GroupSubscribesModel->find()
                ->order([$order])
                ->limit(intval($noOfRecord))
                ->offset($limitParam)
                ->select(['Posts.p_id','Posts.photo','Posts.description','Posts.type','Posts.category','Posts.groups','Posts.lat','Posts.lng','Posts.added_date','Posts.u_id','Posts.hashtag','Posts.places','Posts.city','Posts.vote','Votes.v_id','Votes.v_p_id','Votes.v_u_id','Votes.vote','PostComments.pc_id','PostComments.pc_p_id','count'=>$PostCommentsModel->find()->func()->count('PostComments.pc_p_id')])
                 ->distinct(['Posts.p_id','Posts.photo','Posts.description','Posts.type','Posts.category','Posts.groups','Posts.lat','Posts.lng','Posts.added_date','Posts.u_id','Posts.hashtag','Posts.places','Posts.city','Posts.vote'])
                ->where([$cond])
                ->join([
                    'Posts' => [
                        'table' => 'posts',
                        'alias' => 'Posts',
                        'type' => 'LEFT',
                        'conditions'=>array(
                                'or'=>array(
                                'Posts.groups = GroupSubscribes.gs_g_id and GroupSubscribes.gs_hashtag = "" and GroupSubscribes.gs_city = ""',
                                //'Posts.hashtag LIKE' =>'%#hashtag%',
                                "Posts.hashtag LIKE concat('%',GroupSubscribes.gs_hashtag,'%') and GroupSubscribes.gs_g_id is null and GroupSubscribes.gs_city = ''",
                                ),
                         ),
                        //'conditions' => 'posts.p = topics.id'
                    ],
                    'Votes' => [
                        'table' => 'votes',
                        'alias' => 'Votes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'Votes.v_u_id ='.$uid,
                                'Votes.v_p_id = Posts.p_id'
                            ) 
                    ], 
                    'PostComments' => [
                        'table' => 'post_comments',
                        'alias' => 'PostComments',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'PostComments.pc_p_id = Posts.p_id'
                            ) 
                    ]
                ]);
                // ->toArray();
               /* foreach ($query as $key => $value) {
                    print_r($value);  exit;
                }
                print_r($query);  exit;*/

                // Old Code
                /*$PostsModel = TableRegistry::get('Posts'); //use Cake\ORM\TableRegistry;
                $query = $PostsModel->find();
                $query = $PostsModel
                ->find()
                ->order([$order])
                ->where([$cond])
                ->contain(['Groups.GroupSubscribes','PostComments'])
                ->matching('Groups.GroupSubscribes', function(\Cake\ORM\Query $q) {
                    return $q->where([@$cond]);
                })
                ->contain(['Votes'=> function(\Cake\ORM\Query $q) use($new_cond) {
                    return $q->where([$new_cond]);
                }])
                 ->count();

                //$StaticPages = $query->toArray();
                $noOfPages             = intval($query) / intval($noOfRecord);
                $limitParam            = ($pageNo - 1) * $noOfRecord;
                $noOfPages             = ceil($noOfPages);
                $totalPage = ceil($query/$noOfRecord);
                $StaticPagesCount = $totalPage;
                //print_r($cond1); exit;
                $query = $PostsModel
                ->find()
                ->order([$order])
                ->limit(intval($noOfRecord))
                ->offset($limitParam)
                ->where([$cond])
                ->contain(['Groups.GroupSubscribes','PostComments'])
                ->matching('Groups.GroupSubscribes', function(\Cake\ORM\Query $q) {
                    return $q->where([@$cond]);
                })
                ->contain(['Votes'=> function(\Cake\ORM\Query $q) use($new_cond) {
                    return $q->where([$new_cond]);
                }]);*/
                // End Old code
                
            }
            //debug($query); exit;
            //print_r($query);exit;
            if(!empty($query) ){
                $test['post'] =array();
                $query =  $query->toArray();
               /* if(@$data['search_type'] == 'subscription'){
                print_r($query);
                }*/
                foreach ($query as $postkey => $postvalue) {
                    //print_r($postvalue);
                    if(@$data['search_type'] == 'subscription'){
                        if(isset($postvalue['Votes']['vote'])){
                            $postvalue['Posts']['votes'] = $postvalue['Votes'];
                        }else{
                             $postvalue['Posts']['votes'] = array();
                        }
                     
                        $data1 =  $postvalue['Posts'];
                        $data1['p_id'] =  intval($postvalue['Posts']['p_id']);
                        $data1['type'] =  intval($postvalue['Posts']['type']);
                        $data1['category'] =  intval($postvalue['Posts']['category']);
                        $data1['u_id'] =  intval($postvalue['Posts']['u_id']);
                        $data1['vote'] =  intval($postvalue['Posts']['vote']);
                        $data1['groups'] =  @$postvalue['Posts']['groups']?intval($postvalue['Posts']['groups']):'';
                        $data1['is_vote'] = @$postvalue['Votes']['vote']?intval($postvalue['Votes']['vote']):0;
                        $data1['post_comment_count'] = $postvalue['count'];
                        $data1['photo'] = $postvalue['Posts']['photo'] ? Router::url('/webroot/storage/post/'.$postvalue['Posts']['photo'],true):'';
                        array_push($test['post'],$data1);
                    }else{
                        $data1 =  $postvalue;

                        $data1['is_vote'] = @$postvalue['votes'][0]['vote']?$postvalue['votes'][0]['vote']:0;
                        $data1['groups'] =  @$postvalue['groups']?$postvalue['groups']:'';
                        $data1['post_count'] = @$postcount? $postcount :0; 
                        if(@$data['search_type'] == 'hashtag'){
                            $data1['is_subscribe'] = $check_groupalready_subscribe;
                        }
                        if(@$data['search_type'] == 'city'){
                            $data1['is_subscribe'] = $check_groupalready_subscribe;
                        }
                        $data1['post_comment_count'] = sizeof($postvalue['post_comments']);
                        $data1['photo'] = $postvalue['photo'] ? Router::url('/webroot/storage/post/'.$postvalue['photo'],true):'';
                        array_push($test['post'],$data1);
                    }
                       
                        
                   /* }else{
                        //print_r($postvalue);
                        
                        array_push($test['post'],$data1);
                    }*/
                 }
                $this->set([
                    'data' => $test,
                    'code' => '214',
                    //'post_count'=> $test['post'][0]['post_count']?$test['post'][0]['post_count']:'0',
                    'page_count'=>$StaticPagesCount,
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(214),
                    '_serialize' => ['error','code','url','data','message','page_count']
                 ]);
            }else{


                $this->set([
                    'data' => '',
                    'code' => '427',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(427),
                    '_serialize' => ['error','code','url','data','message']
                 ]);
           }
        }
    }

     /**********************************************************************************************************
    * API                   => Post Detail                                                                     *
    * Description           => It is used for get Post Detail..                                                *
    * Required Parameters   =>                                                                                *
    * Created by            => Sunil                                                                          *
    **********************************************************************************************************/
    public function trending(){
        if ($this->request->is('post')){
            $data = $this->request->data;
            $pageNo = @$data['page']?$data['page']:1;
            $noOfRecord = 10;
            $stype = $data['s_type'];
            $cond = 's_type='.$stype;
            if(!empty($data['search_text'])){
                $stext = $data['search_text'];
                $cond .= " AND LOWER(`s_name`) LIKE '%$stext%'";
                       
            }
           
            $GroupsModel = TableRegistry::get('Groups');
            $PostsModel = TableRegistry::get('Posts');
            $SubscribesModel = TableRegistry::get('Subscribes');
            $GroupSubscribesModel = TableRegistry::get('GroupSubscribes');
            $query = $SubscribesModel
            ->find()
            ->where([$cond])
           // ->limit(intval($noOfRecord))
          //  ->offset($limitParam)
            ->order(['`s_name`' => 'ASC',]);
          //  print_r($query); exit;
             //debug($query); exit;
            if(!empty($query)){
                $query =  $query->toArray();
                $expolrearr =array();
                foreach ($query as $querykey => $queryvalue) {
                     //print_r($queryvalue);
                    if($queryvalue['s_type'] == 1){
                        $g_name = $queryvalue['s_name'];
                        $groupquery = $GroupsModel
                        ->find()
                        ->where(['Groups.g_name'=>$g_name])
                        ->first();
                        $group_name = $groupquery['g_id'];
                        $postcond = "GroupSubscribes.gs_g_id =".$group_name;
                        $querypostcount = $GroupSubscribesModel
                        ->find()
                        ->where([$postcond])
                        ->count();
                        $querypostcount;
                    $explore['g_id'] = $groupquery['g_id'];
                    }else if($queryvalue['s_type'] == 2){
                        $g_name = '#'.$queryvalue['s_name'];
                       $postcond = " LOWER(`hashtag`) LIKE '%$g_name%'";
                    }else if($queryvalue['s_type'] == 3){
                        $g_name = "'".$queryvalue['s_name']."'";
                        $postcond = "city=$g_name";
                    }
                   
                    $explore['s_id'] = $queryvalue['s_id'];
                    $explore['s_type'] = $queryvalue['s_type'];
                    $explore['s_name'] = $queryvalue['s_name'];
                    $explore['count'] =  @$querypostcount?$querypostcount:0;
                    if($queryvalue['s_type'] == 1){
                      $explore['photo'] = @$groupquery['photo']? Router::url('/webroot/storage/group/'.$groupquery['photo'],true):'';
                    }
                    array_push($expolrearr, $explore);
                }
                $length = sizeof($expolrearr);
                for ($outer = 0; $outer < $length; $outer++){
                    for ($inner = 0; $inner < $length; $inner++){
                        if ($expolrearr[$outer]['count'] > $expolrearr[$inner]['count']){
                            $tmp = $expolrearr[$outer];
                            $expolrearr[$outer] = $expolrearr[$inner];
                            $expolrearr[$inner] = $tmp;
                        }
                    }
                }
                $hashtagarr =array_slice($expolrearr, 0, 10, true);
                 
               // exit;
                $this->set([
                    'data' => $hashtagarr,
                    'code' => '280',
                    'error' => '0',
                    //'page_count'=>$StaticPagesCount,
                    'url'  => '',
                    'message'=> responseMsg(280),
                    '_serialize' => ['error','code','url','data','message','page_count']
                 ]);
           }else{
                $this->set([
                    'data' => '',
                    'code' => '427',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(427),
                    '_serialize' => ['error','code','url','data','message']
                 ]);
           }
        }
    }
    /**********************************************************************************************************
    * API                   => Post Detail                                                                     *
    * Description           => It is used for get Post Detail..                                                *
    * Required Parameters   =>                                                                                *
    * Created by            => Sunil                                                                          *
    **********************************************************************************************************/
    public function postDetail(){
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $uid = $data['id'];
            $new_cond = array("Votes.v_u_id" => $uid);
            $PostsModel = TableRegistry::get('Posts'); //use Cake\ORM\TableRegistry;
            $query = $PostsModel
            ->find()
            ->contain(['PostComments'])
            ->contain(['Votes'=> function(\Cake\ORM\Query $q) use($new_cond) {
                return $q->where([$new_cond]);
            }])
            ->where(['Posts.p_id'=>$data['p_id']]);
            //debug($query); exit;
            //print_r($query);exit;
            if(!empty($query) ){
                $test['post'] =array();
                $query =  $query->toArray();

                foreach ($query as $postkey => $postvalue) {
                    //print_r($postvalue);
                    $data1 =  $postvalue;
                    $data1['is_vote'] = @$postvalue['votes'][0]['vote']?$postvalue['votes'][0]['vote']:0;
                    $data1['post_comment_count'] = sizeof($postvalue['post_comments']);
                    $data1['photo'] = $postvalue['photo'] ? Router::url('/webroot/storage/post/'.$postvalue['photo'],true):'';
                    array_push($test['post'],$data1);
                }

                $this->set([
                    'data' => $test,
                    'code' => '214',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(214),
                    '_serialize' => ['error','code','url','data','message','page_count']
                 ]);
            }else{


                $this->set([
                    'data' => '',
                    'code' => '427',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(427),
                    '_serialize' => ['error','code','url','data','message']
                 ]);
           }
        }
    }

    /**********************************************************************************************************
    * API                   => Group Detail                                                                   *
    * Description           => It is used for get Group Detail..                                              *
    * Required Parameters   =>                                                                                *
    * Created by            => Sunil                                                                          *
    **********************************************************************************************************/
    public function groupDetailPost(){
        if ($this->request->is('post')) {
            
            $data = $this->request->data;
            $uid = $data['id']; 
            $pageNo = @$data['page']?$data['page']:1;
            $noOfRecord = 20;

            $this->loadModel('Groups');
            $this->loadModel('Posts');
            //$cond = 'Votes.v_u_id ='.$uid;
            //$cond .= 'Votes.v_p_id = Posts.p_id ';
           
            $GroupsModel = TableRegistry::get('Groups');
            $PostsModel = TableRegistry::get('Posts');
            $PostcommentsModel = TableRegistry::get('PostComments');


            $query = $PostsModel
            ->find()
             ->select(['Posts.p_id','Posts.photo','Posts.description','Posts.type','Posts.category','Posts.groups','Posts.lat','Posts.lng','Posts.added_date','Posts.u_id','Posts.hashtag','Posts.places','Posts.city','Posts.vote','Votes.v_id','Votes.v_p_id','Votes.v_u_id','Votes.vote','PostComments.pc_id','PostComments.pc_p_id','count'=>$PostcommentsModel->find()->func()->count('PostComments.pc_p_id')])
                 ->distinct(['Posts.p_id','Posts.photo','Posts.description','Posts.type','Posts.category','Posts.groups','Posts.lat','Posts.lng','Posts.added_date','Posts.u_id','Posts.hashtag','Posts.places','Posts.city','Posts.vote'])
            ->contain(['PostComments'/*,'Votes'*/])
             ->join([
                    'Votes' => [
                        'table' => 'votes',
                        'alias' => 'Votes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'Votes.v_u_id ='.$uid,
                                'Votes.v_p_id = Posts.p_id'
                            ) 
                    ], 
                    'PostComments' => [
                        'table' => 'post_comments',
                        'alias' => 'PostComments',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'PostComments.pc_p_id = Posts.p_id'
                            ) 
                    ]
                ])
            /*->matching('Votes', function(\Cake\ORM\Query $q) use($uid) {
                    return $q->where([@$cond]);
                })*/
           
            ->where(['Posts.groups'=>$data['g_id']])
            ->count();
            //$StaticPages = $query->toArray();
            $noOfPages             = intval($query) / intval($noOfRecord);
            $limitParam            = ($pageNo - 1) * $noOfRecord;
            $noOfPages             = ceil($noOfPages);
            
            $totalPage = ceil($query/$noOfRecord);
            $StaticPagesCount = $totalPage;

            $query = $PostsModel
            ->find()
             ->select(['Posts.p_id','Posts.photo','Posts.description','Posts.type','Posts.category','Posts.groups','Posts.lat','Posts.lng','Posts.added_date','Posts.u_id','Posts.hashtag','Posts.places','Posts.city','Posts.vote','Votes.v_id','Votes.v_p_id','Votes.v_u_id','Votes.vote','PostComments.pc_id','PostComments.pc_p_id','count'=>$PostcommentsModel->find()->func()->count('PostComments.pc_p_id')])
                 ->distinct(['Posts.p_id','Posts.photo','Posts.description','Posts.type','Posts.category','Posts.groups','Posts.lat','Posts.lng','Posts.added_date','Posts.u_id','Posts.hashtag','Posts.places','Posts.city','Posts.vote'])
            ->contain(['PostComments'/*,'Votes'*/])
            ->join([
                    'Votes' => [
                        'table' => 'votes',
                        'alias' => 'Votes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'Votes.v_u_id ='.$uid,
                                'Votes.v_p_id = Posts.p_id'
                            ) 
                    ], 
                    'PostComments' => [
                        'table' => 'post_comments',
                        'alias' => 'PostComments',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'PostComments.pc_p_id = Posts.p_id'
                            ) 
                    ]
                ])
            ->order(['Posts.p_id'=>'DESC'])
            ->limit(intval($noOfRecord))
            ->offset($limitParam)
            /*->matching('Votes', function(\Cake\ORM\Query $q) use($uid) {
                    return $q->where([@$cond]);
                })*/
           
            ->where(['Posts.groups'=>$data['g_id']]);
           // ->first();
                //debug($query); exit;
            if(!empty($query)){
                $test['post'] =array();
                $query =  $query->toArray();
                foreach ($query as $postkey => $postvalue) {
                    //print_r($postvalue);  exit;
                    if(isset($postvalue['Votes']['vote'])){
                        $postvalue['votes'] = $postvalue['Votes'];
                    }else{
                             $postvalue['votes'] = array();
                    }
                    unset($postvalue['Votes']);
                     unset($postvalue['PostComments']);
                    $data1 =  $postvalue;
                    $data1['is_vote'] = @$postvalue['votes'][0]['vote']?$postvalue['votes'][0]['vote']:0;
                    $data1['groups'] =  @$postvalue['groups']?$postvalue['groups']:'';
                    $data1['post_comment_count'] = sizeof($postvalue['post_comments']);
                    $data1['photo'] = $postvalue['photo'] ? Router::url('/webroot/storage/post/'.$postvalue['photo'],true):'';
                    array_push($test['post'],$data1);
                }
                $this->set([
                    'data' => $test,
                    'code' => '214',
                    'error' => '0',
                    'page_count'=>$StaticPagesCount,
                    'url'  => '',
                    'message'=> responseMsg(214),
                    '_serialize' => ['error','code','url','data','message','page_count']
                 ]);
           }else{
                $this->set([
                    'data' => '',
                    'code' => '427',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(427),
                    '_serialize' => ['error','code','url','data','message']
                 ]);
           }
        }
    }

    public function groupDetail(){
        if ($this->request->is('post')) {
            
            $data = $this->request->data;
            $uid = $data['id']; 
            $this->loadModel('Groups');
            $cond = 'GroupSubscribes.gs_u_id ='.$uid;
            //$cond .= 'Votes.v_p_id = Posts.p_id ';
          
            $GroupsModel = TableRegistry::get('Groups');
            $GroupSubscribesModel = TableRegistry::get('GroupSubscribes');
            $GroupSubscribessModel = TableRegistry::get('GroupSubscribes');
            $query = $GroupsModel
            ->find()
            //
            ->select(['Groups.g_id','Groups.g_name','Groups.g_discription','Groups.g_u_id','Groups.photo','Groups.g_category','GroupSubscribes.gs_id','GroupSubscribes.gs_u_id','GroupSubscribes.gs_g_id','total_subscribe'=>$GroupSubscribesModel->find()->func()->count('GroupSubscribes.gs_g_id'),
                'is_subscribe'=>'(select count(*) from group_subscribes GroupSubscribess where GroupSubscribess.gs_g_id = Groups.g_id AND GroupSubscribess.gs_u_id ='.$uid.' )'])
            ->contain(['GroupSubscribes'])
            ->join([
                    'GroupSubscribes' => [
                        'table' => 'group_subscribes',
                        'alias' => 'GroupSubscribes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'GroupSubscribes.gs_g_id = Groups.g_id'
                            ) 
                    ]
                ])

           /*  'conditions'=>array(
                                'or'=>array(
                                'Posts.groups = GroupSubscribes.gs_g_id and GroupSubscribes.gs_hashtag = "" and GroupSubscribes.gs_city = ""',
                                //'Posts.hashtag LIKE' =>'%#hashtag%',
                                "Posts.hashtag LIKE concat('%',GroupSubscribes.gs_hashtag,'%') and GroupSubscribes.gs_g_id is null and GroupSubscribes.gs_city = ''",
                                ),
                         ),*/
           /* ->matching('GroupSubscribes', function(\Cake\ORM\Query $q) use($uid) {
                    return $q->where([@$cond]);
                })*/
           
            ->where(['Groups.g_id'=>$data['g_id']])
            ->first();
                //debug($query); exit;
            //print_r($query);  exit;
            if(!empty($query)){
                    $data1 =  $query;
                    $data1['total_subscribe'] = sizeof($query['group_subscribes']);
                    $data1['is_subscribe'] = intval($query['is_subscribe']);
                    $data1['photo'] = $query['photo'] ? Router::url('/webroot/storage/group/'.$query['photo'],true):'';
                    unset( $data1['group_subscribes']);
                $this->set([
                    'data' => $data1,
                    'code' => '287',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(287),
                    '_serialize' => ['error','code','url','data','message','page_count']
                 ]);
            }else{
                $this->set([
                    'data' => '',
                    'code' => '427',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(427),
                    '_serialize' => ['error','code','url','data','message']
                 ]);
           }
        }
    }

    /************************************************************************************************************
    * API                   => logout                                                                            *
    * Description           => It is used to logout user..                                                       *
    * Parameters            => id,access_token                                                                   *
    * Created by            => Sunil                                                                             *
    *************************************************************************************************************/
    public function logout(){
        if ($this->request->is('post')){
            $user = $this->request->data;
            $this->loadModel('Users');
            $user = $this->Users->get($user['id']);
            $user = $this->Users->patchEntity($user, $this->request->data);
            $user['device_id'] =''; 
            $user['device_type'] =''; 
            $user['token_id'] =''; 
            if ($this->Users->save($user)){

                $this->set([
                'data' => '',
                'code' => '216',
                'error' => '0',
                'url'  => '',
                'message'=> responseMsg(216),
                '_serialize' => ['error','code','url','data','message']
                 ]);
            }else{
                $this->set([
                'data' => '',
                'code' => '406',
                'error' => '1',
                'url'  => '',
                'message'=> responseMsg(406),
                '_serialize' => ['error','code','url','data','message']
                 ]);
            }
        }  
    }
    
    /*********************************************************************************************************
    * API                   => Explore List                                                                  *
    * Description           => It is used for get Explore List..                                             *
    * Required Parameters   =>                                                                               *
    * Created by            => Sunil                                                                         *
    **********************************************************************************************************/
    public function exploreList(){
        if ($this->request->is('post')){
            $data = $this->request->data;
            $pageNo = @$data['page']?$data['page']:1;
            $noOfRecord = 20;
            if($data['search_type'] == 'group'){
                  $cond = "s_type=1";
                if(!empty($data['search_text'])){
                    $stext = $data['search_text'];
                    $cond .= " AND LOWER(`s_name`) LIKE '%$stext%'";
                           
                }
            }elseif($data['search_type'] == 'tag'){
                  $cond = "s_type=2";
                if(!empty($data['search_text'])){
                    $stext = $data['search_text'];
                    $cond .= " AND LOWER(`s_name`) LIKE '%$stext%'";
                           
                }
            }elseif($data['search_type'] == 'place'){
                  $cond = "s_type=3";
                if(!empty($data['search_text'])){
                    $stext = $data['search_text'];
                    $cond .= " AND LOWER(`s_name`) LIKE '%$stext%'";
                           
                }
            }else{
                $cond = '1=1';
                if(!empty($data['search_text'])){
                    $stext = $data['search_text'];
                    $cond .= " AND LOWER(`s_name`) LIKE '%$stext%'";
                           
                }
            }
            $GroupsModel = TableRegistry::get('Groups');
            $PostsModel = TableRegistry::get('Posts');
            $SubscribesModel = TableRegistry::get('Subscribes');
            $GroupSubscribesModel = TableRegistry::get('GroupSubscribes');
            $query = $SubscribesModel
            ->find()
            ->where([$cond])
            ->order(['`s_name`' => 'ASC'])
            ->count();

            $noOfPages             = intval($query) / intval($noOfRecord);
            $limitParam            = ($pageNo - 1) * $noOfRecord;
            $noOfPages             = ceil($noOfPages);

            $totalPage = ceil($query/$noOfRecord);
            $StaticPagesCount = $totalPage;

            $query = $SubscribesModel
            ->find()
            ->where([$cond])
            ->limit(intval($noOfRecord))
            ->offset($limitParam)
            ->order(['`s_name`' => 'ASC']);
          //  print_r($query); exit;
             //debug($query); exit;
            if(!empty($query)){
                $query =  $query->toArray();
                $expolrearr =array();
                foreach ($query as $querykey => $queryvalue) {
                    if($queryvalue['s_type'] == 1){
                        $g_name = $queryvalue['s_name'];
                        $groupquery = $GroupsModel
                        ->find()
                        ->where(['Groups.g_name'=>$g_name])
                        ->first();
                        $group_name = $groupquery['g_id'];
                        $postcond = "GroupSubscribes.gs_g_id =".$group_name;
                        $explore['g_id'] = $groupquery['g_id'];
                        $querypostcount = $GroupSubscribesModel
                        ->find()
                        ->where([$postcond])
                        ->count();
                        $querypostcount;

                    }else if($queryvalue['s_type'] == 2){
                        $g_name = '#'.$queryvalue['s_name'];
                       $postcond = " LOWER(`hashtag`) LIKE '%$g_name%'";
                        $querypostcount = $PostsModel
                    ->find()
                    ->where([$postcond])
                    ->count();
                    $querypostcount;
                    }else if($queryvalue['s_type'] == 3){
                        $g_name = "'".$queryvalue['s_name']."'";
                        $postcond = "city=$g_name";
                         $querypostcount = $PostsModel
                    ->find()
                    ->where([$postcond])
                    ->count();
                    $querypostcount;
                    }
                   
                    $explore['s_id'] = $queryvalue['s_id'];
                    $explore['s_type'] = $queryvalue['s_type'];
                    $explore['s_name'] = $queryvalue['s_name'];
                     $explore['s_name'] = $queryvalue['s_name'];
                    $explore['count'] =  @$querypostcount?$querypostcount:0;
                    array_push($expolrearr,  $explore);
                }
                $this->set([
                    'data' => $expolrearr,
                    'code' => '280',
                    'error' => '0',
                    'page_count'=>$StaticPagesCount,
                    'url'  => '',
                    'message'=> responseMsg(280),
                    '_serialize' => ['error','code','url','data','message','page_count']
                 ]);
           }else{
                $this->set([
                    'data' => '',
                    'code' => '427',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(427),
                    '_serialize' => ['error','code','url','data','message']
                 ]);
           }
        }
    }

    /************************************************************************************************************
    * API                   => Post Report                                                                      *
    * Description           => It is used to Post Report                                                        *
    * Parameters            => id,access_token,p_id,comment                                                     *
    * Created by            => Sunil                                                                            *
    *************************************************************************************************************/
    public function postReport(){
        $this->loadModel('PostReports');
        $postreport = $this->PostReports->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $postreport = $this->PostReports->patchEntity($postreport, $this->request->data);
            $postreport['u_id'] = $data ['id'];
            $postreport['status'] = '1';
           // print_r($postreport);  exit;
            if ($this->PostReports->save($postreport)) {
                $this->set([
                    'data' => '',
                    'code' => '281',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(281),
                    '_serialize' => ['error','code','url','data','message']
                ]);
               
            } else {
                $this->set([
                    'data' => '',
                    'code' => '417',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(417),
                    '_serialize' => ['error','code','url','data','message']
                ]);
               
            }
        }
    }

    /************************************************************************************************************
    * API                   => Post Comment Report                                                              *
    * Description           => It is used to Post Comment Report                                                *
    * Parameters            => id,access_token,p_id,pc_id,comment                                               *
    * Created by            => Sunil                                                                            *
    *************************************************************************************************************/
    public function postcommentReport(){
        $this->loadModel('PostCommentReports');
        $postcommentreport = $this->PostCommentReports->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $postcommentreport = $this->PostCommentReports->patchEntity($postcommentreport, $this->request->data);
            $postcommentreport['u_id'] = $data ['id'];
            $postcommentreport['status'] = '1';
           // print_r($postreport);  exit;
            if ($this->PostCommentReports->save($postcommentreport)) {
                $this->set([
                    'data' => '',
                    'code' => '282',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(282),
                    '_serialize' => ['error','code','url','data','message']
                ]);
               
            } else {
                $this->set([
                    'data' => '',
                    'code' => '417',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(417),
                    '_serialize' => ['error','code','url','data','message']
                ]);
               
            }
        }
    }

    /**********************************************************************************************************
        * API                   => Notification List                                                              *
        * Description           => It is used for get notification List..                                         *
        * Required Parameters   =>                                                                                *
        * Created by            => Sunil                                                                          *
    **********************************************************************************************************/
    public function notificationList(){
        $data = $this->request->data;
        $NotificationsModel = TableRegistry::get('Notifications'); //use Cake\ORM\TableRegistry;
        $noOfRecord = 20;
        $pageNo = @$data['page']?$data['page']:1;
        $query = $NotificationsModel->find();
        $query = $NotificationsModel
            ->find()

            ->where([
                'n_u_id' => $data['id'],
                'n_status' => 0,
                
            ])
            ->count();
        //$StaticPages = $query->toArray();
        $noOfPages             = intval($query) / intval($noOfRecord);
        $limitParam            = ($pageNo - 1) * $noOfRecord;
        $noOfPages             = ceil($noOfPages);
        $totalPage = ceil($query/$noOfRecord);
        $StaticPagesCount = $totalPage;

        $query = $NotificationsModel
        ->find()
        ->limit(intval($noOfRecord))
        ->offset($limitParam)
        ->order(['n_id'=>'DESC'])
        ->where([
            'n_u_id' => $data['id'],
            'n_status' => 0,
        ]);
        //debug($query); exit;
        $query =  $query->toArray();
        
        $data1['notification'] = $query;
        if(!empty($query)){
        $this->set([
            'data' => $data1,
            'code' => '283',
            'page_count'=>$StaticPagesCount,
            'error' => '0',
            'url'  => '',
            'message'=> responseMsg(283),
            '_serialize' => ['error','code','url','data','message','page_count']
             ]);
       }else{
         $this->set([
            'data' => '',
            'code' => '428',
            'error' => '1',
            'url'  => '',
            'message'=> responseMsg(428),
            '_serialize' => ['error','code','url','data','message']
             ]);
       }
           
    }

    /**********************************************************************************************************
    * API                   => Notification Seen                                                               *
    * Description           => It is used for update notification seen status..                                *
    * Required Parameters   =>                                                                                 *
    * Created by            => Sunil                                                                           *
    ***********************************************************************************************************/

    public function notificationSeen(){
        $data = $this->request->data;
        $notification = $this->request->data;
        $this->loadModel('Notifications');
        $notification = $this->Notifications->get($notification['n_id']);
        $notification = $this->Notifications->patchEntity($notification, $this->request->data);
        if ($this->Notifications->save($notification)){
            $NotificationsModel = TableRegistry::get('Notifications'); //use Cake\ORM\TableRegistry;
            $query = $NotificationsModel->find();
            $query = $NotificationsModel
                ->find()
                ->where([
                    'n_u_id' => $data['id'],
                    'n_status' => 0,
                    
                ])
                ->count();
            $this->set([
                'data' => '',
                'code' => '284',
                'error' => '0',
                'url'  => '',
                'notification_count'=>$query ,
                'message'=> responseMsg(284),
                '_serialize' => ['error','code','url','data','message','notification_count']
                 ]);
        }else{
             $this->set([
                'data' => '',
                'code' => '424',
                'error' => '1',
                'url'  => '',
                'message'=> responseMsg(424),
                '_serialize' => ['error','code','url','data','message']
                 ]);
        }
    }

    /**********************************************************************************************************
        * API                   => My Post                                                                     *
        * Description           => It is used for get My post List..                                           *
        * Required Parameters   =>                                                                             *
        * Created by            => Sunil                                                                       *
    ************************************************************************************************************/
    public function myPost(){
        $data = $this->request->data;
        $PostsModel = TableRegistry::get('Posts'); //use Cake\ORM\TableRegistry;
        $noOfRecord = 20;
        $uid = $data['id'];
        $pageNo = @$data['page']?$data['page']:1;
        $query = $PostsModel->find();
        $query = $PostsModel
            ->find()

            ->where([
                'u_id' => $data['id'],
             ])
            ->count();
        //$StaticPages = $query->toArray();
        $noOfPages             = intval($query) / intval($noOfRecord);
        $limitParam            = ($pageNo - 1) * $noOfRecord;
        $noOfPages             = ceil($noOfPages);
        $totalPage = ceil($query/$noOfRecord);
        $StaticPagesCount = $totalPage;
         $new_cond = array("Votes.v_u_id" => $uid);
        $query = $PostsModel
        ->find()
        ->limit(intval($noOfRecord))
        ->offset($limitParam)
        ->order(['p_id'=>'DESC'])
        ->contain(['PostComments'])
        ->contain(['Votes'=> function(\Cake\ORM\Query $q) use($new_cond) {
                    return $q->where([$new_cond]);
            }])
        ->where([
            'u_id' => $data['id'],
        ]);
        //debug($query); exit;
        if(!empty($query)){
            $test['post'] =array();
            $query =  $query->toArray();
            foreach ($query as $postkey => $postvalue) {
                    //print_r($postvalue);
                        $data1 =  $postvalue;
                        $data1['is_vote'] = @$postvalue['votes'][0]['vote']?$postvalue['votes'][0]['vote']:0;
                        $data1['groups'] =  @$postvalue['groups']?$postvalue['groups']:'';
                        $data1['post_comment_count'] = sizeof($postvalue['post_comments']);
                        $data1['photo'] = $postvalue['photo'] ? Router::url('/webroot/storage/post/'.$postvalue['photo'],true):'';
                        array_push($test['post'],$data1);
                        
                   
                 }
        $this->set([
            'data' => $test,
            'code' => '285',
            'page_count'=>$StaticPagesCount,
            'error' => '0',
            'url'  => '',
            'message'=> responseMsg(285),
            '_serialize' => ['error','code','url','data','message','page_count']
             ]);
       }else{
         $this->set([
            'data' => '',
            'code' => '429',
            'error' => '1',
            'url'  => '',
            'message'=> responseMsg(429),
            '_serialize' => ['error','code','url','data','message']
             ]);
       }
    }
    /***********************************************************************************************************
    * API                   => My upvoted                                                                      *
    * Description           => It is used for get My upvoted List..                                            *
    * Required Parameters   =>                                                                                 *
    * Created by            => Sunil                                                                           *
    ************************************************************************************************************/
    public function myUpvote(){
        $data = $this->request->data;
        $VotesModel = TableRegistry::get('Votes'); //use Cake\ORM\TableRegistry;
        $noOfRecord = 20;
        $pageNo = @$data['page']?$data['page']:1;
        $query = $VotesModel->find();
        $query = $VotesModel
            ->find()
            ->where([
             'v_u_id' => $data['id'],
             'Votes.vote' => '1'
             ])
            ->count();
        //$StaticPages = $query->toArray();
        $noOfPages             = intval($query) / intval($noOfRecord);
        $limitParam            = ($pageNo - 1) * $noOfRecord;
        $noOfPages             = ceil($noOfPages);
        $totalPage = ceil($query/$noOfRecord);
        $StaticPagesCount = $totalPage;

        $query = $VotesModel
        ->find()
        ->limit(intval($noOfRecord))
        ->offset($limitParam)
        ->order(['v_id'=>'DESC'])
        ->contain(['Posts'])
        ->where([
            'v_u_id' => $data['id'],
            'Votes.vote' => '1'
        ]);
        //debug($query); exit;
        $query =  $query->toArray();

        $data1 = $query;
        if(!empty($query)){
        $this->set([
            'data' => $data1,
            'code' => '285',
            'page_count'=>$StaticPagesCount,
            'error' => '0',
            'url'  => '',
            'message'=> responseMsg(285),
            '_serialize' => ['error','code','url','data','message','page_count']
             ]);
       }else{
         $this->set([
            'data' => '',
            'code' => '429',
            'error' => '1',
            'url'  => '',
            'message'=> responseMsg(429),
            '_serialize' => ['error','code','url','data','message']
             ]);
       }
    }

    /***********************************************************************************************************
    * API                   =>myComment                                                                        *
    * Description           => It is used for get myComment List..                                            *
    * Required Parameters   =>                                                                                 *
    * Created by            => Sunil                                                                           *
    ************************************************************************************************************/
    public function myComment(){
        $data = $this->request->data;
        $PostcommentsModel = TableRegistry::get('PostComments'); //use Cake\ORM\TableRegistry;
        $noOfRecord = 20;
        $uid = $data['id'];
        $pageNo = @$data['page']?$data['page']:1;
        $query = $PostcommentsModel->find();
        $query = $PostcommentsModel

            ->find()
            ->where([
             'pc_u_id' => $data['id'],
             ])
            ->count();
        //$StaticPages = $query->toArray();
        $noOfPages             = intval($query) / intval($noOfRecord);
        $limitParam            = ($pageNo - 1) * $noOfRecord;
        $noOfPages             = ceil($noOfPages);
        $totalPage = ceil($query/$noOfRecord);
        $StaticPagesCount = $totalPage;

        $query = $PostcommentsModel
        ->find()
        ->select(['Posts.p_id','Posts.photo','Posts.description','Posts.type','Posts.category','Posts.groups','Posts.lat','Posts.lng','Posts.added_date','Posts.u_id','Posts.hashtag','Posts.places','Posts.city','Posts.vote','Votes.v_id','Votes.v_p_id','Votes.v_u_id','Votes.vote','PostComments.pc_id','PostComments.pc_p_id','count'=>$PostcommentsModel->find()->func()->count('PostComments.pc_p_id')])
        ->limit(intval($noOfRecord))
        ->offset($limitParam)
        ->order(['PostComments.pc_id'=>'DESC'])
        ->contain(['Posts'])
        ->join([
                    'Votes' => [
                        'table' => 'votes',
                        'alias' => 'Votes',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'Votes.v_u_id ='.$uid,
                                'Votes.v_p_id = PostComments.pc_p_id'
                            ) 
                    ], 
                    'PostCommentsdaa' => [
                        'table' => 'post_comments',
                        'alias' => 'PostCommentsdaa',
                        'type' => 'LEFT',
                        'conditions' =>array(
                                'PostCommentsdaa.pc_p_id = PostComments.pc_p_id'
                            ) 
                    ]
                ])
        ->where([
            'PostComments.pc_u_id' => $data['id'],
        ]);
        //debug($query); exit;

        
        //print_r($query); exit;
        $data1 = $query;
        if(!empty($query)){
            $test['post'] =array();
            $query =  $query->toArray();
            foreach ($query as $postkey => $postvalue) {
                if(isset($postvalue['Votes']['vote'])){
                    $postvalue['post']['votes'] = $postvalue['Votes'];
                }else{
                     $postvalue['post']['votes'] = array();
                }
                
                $data1 =  $postvalue['post'];
                $data1['groups'] =  @$postvalue['post']['groups']?$postvalue['post']['groups']:'';
                $data1['is_vote'] = @$postvalue['post']['vote']?$postvalue['Votes']['vote']:0;
                $data1['post_comment_count'] = $postvalue['count'];
                $data1['photo'] = $postvalue['photo'] ? Router::url('/webroot/storage/post/'.$postvalue['photo'],true):'';

                array_push($test['post'],$data1);
                   
                 }
        $this->set([
            'data' => $test,
            'code' => '285',
            'page_count'=>$StaticPagesCount,
            'error' => '0',
            'url'  => '',
            'message'=> responseMsg(285),
            '_serialize' => ['error','code','url','data','message','page_count']
             ]);
       }else{
         $this->set([
            'data' => '',
            'code' => '429',
            'error' => '1',
            'url'  => '',
            'message'=> responseMsg(429),
            '_serialize' => ['error','code','url','data','message']
             ]);
       }
    }

    /**********************************************************************************************************
    * API                   => RecentPhotoList                                                                *
    * Description           => It is used for get Recent Photos List..                                        *
    * Required Parameters   =>                                                                                *
    * Created by            => Sunil                                                                          *
    **********************************************************************************************************/
    public function recentphotoList(){
        $data = $this->request->data;
        $PhotosModel = TableRegistry::get('Photos'); //use Cake\ORM\TableRegistry;
        $noOfRecord = 20;
        $pageNo = @$data['page']?$data['page']:1;
        $query = $PhotosModel->find();
        $query = $PhotosModel
            ->find()
            ->where([
                'p_u_id' => $data['id'],
            ])
            ->count();
        //$StaticPages = $query->toArray();
        $noOfPages             = intval($query) / intval($noOfRecord);
        $limitParam            = ($pageNo - 1) * $noOfRecord;
        $noOfPages             = ceil($noOfPages);
        $totalPage = ceil($query/$noOfRecord);
        $StaticPagesCount = $totalPage;

        $query = $PhotosModel
        ->find()
        ->limit(intval($noOfRecord))
        ->offset($limitParam)
        ->order(['p_id'=>'DESC'])
        ->where([
            'p_u_id' => $data['id'],
        ]);
        //debug($query); exit;
        $query =  $query->toArray();

        $data1['photo'] = $query;
        if(!empty($query)){
            $test['post'] =array();
            foreach ($query as $postkey => $postvalue) {
                //print_r($postvalue);
                $data1 =  $postvalue;
                if($postvalue['p_type'] == 2){
                    $data1['photo'] = $postvalue['photo'] ? Router::url('/webroot/storage/post/'.$postvalue['photo'],true):'';
                }else{
                    $data1['photo'] = $postvalue['photo'] ? Router::url('/webroot/storage/group/'.$postvalue['photo'],true):'';  
                }
                array_push($test['post'],$data1);
            }
        $this->set([
            'data' => $test,
            'code' => '286',
            'page_count'=>$StaticPagesCount,
            'error' => '0',
            'url'  => '',
            'message'=> responseMsg(286),
            '_serialize' => ['error','code','url','data','message','page_count']
             ]);
       }else{
         $this->set([
            'data' => '',
            'code' => '430',
            'error' => '1',
            'url'  => '',
            'message'=> responseMsg(430),
            '_serialize' => ['error','code','url','data','message']
             ]);
       }
           
    }


     /*********************************************************************************************************
    * API                   => Pages List                                                                  *
    * Description           => It is used for get Category List..                                             *
    * Required Parameters   =>                                                                                *
    * Created by            => Sunil                                                                          *
    **********************************************************************************************************/
    public function pagesList(){
        if ($this->request->is('post')){
            $data = $this->request->data;
            
            $PagesModel = TableRegistry::get('Pages'); //use Cake\ORM\TableRegistry;
            $query = $PagesModel
            ->find();
            // debug($query); exit;
            if(!empty($query)){
                $query =  $query->toArray();
                
                $this->set([
                    'data' => $query,
                    'code' => '288',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(288),
                    '_serialize' => ['error','code','url','data','message','page_count']
                 ]);
           }else{
                $this->set([
                    'data' => '',
                    'code' => '427',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(427),
                    '_serialize' => ['error','code','url','data','message']
                 ]);
           }
        }
    }



    
   
       

        /************************************************************************************************************
        * API                   => update_contact                                                                 *
        * Description           => It is used to update contact number of user.                                   *
        * Parameters            => access_token,contact_number                                                    *
        * Created by            => Sunil                                                                          *
        *************************************************************************************************************/
        public function updateContact(){
            if ($this->request->is('post')){
                $user = $this->request->data;
                $this->loadModel('Users');
                $user = $this->Users->get($user['id']);
                $user = $this->Users->patchEntity($user, $this->request->data);
                /*$user['contact_number'] = $this->request->data['contact_number']; 
                echo "<pre>"; print_r($user); exit;*/
                if ($this->Users->save($user)){
                    $this->set([
                    'data' => '',
                    'code' => '216',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(310),
                    '_serialize' => ['error','code','url','data','message']
                     ]);
                }else{
                    $this->set([
                    'data' => '',
                    'code' => '406',
                    'error' => '1',
                    'url'  => '',
                    'message'=> responseMsg(406),
                    '_serialize' => ['error','code','url','data','message']
                     ]);
                }
            }  
        }
       
    /************************************************************************************************************
    * API                   => Notification                                                                      *
    * Description           => It is used to get user today fill or  not..                                       *
    * Required Parameters   => id,access_token,k_id                                                              *
    * Created by            => Sunil                                                                             *
    *************************************************************************************************************/
    public function notification(){
       $this->loadModel('Keyins');
        $Keyins = $this->Keyins->newEntity();
        if ($this->request->is('post')){
            $keyinTable = TableRegistry::get('Keyins');
            $check_keyin = $keyinTable
            ->find()
            ->where(['k_id'=>$this->request->data['k_id']])
            ->first();
            if(!empty($check_keyin)){ 
                $check_keyin['filling_status'] = '1';
                 $this->set([
                    'data' => $check_keyin,
                    'code' => '280',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(280),
                    '_serialize' => ['error','code','url','data','message']
                     ]);
            }else{
              
                $this->set([
                    'data' => '',
                    'code' => '497',
                    'error' => '0',
                    'url'  => '',
                    'message'=> responseMsg(497),
                    '_serialize' => ['error','code','url','data','message']
                ]);
            }
        }
     }


     function phoneVerification($id, $code, $phonenumber, $country_code) {
        $phonenumber = $country_code . $phonenumber;
        require_once(ROOT . '/vendor' . DS  . 'Services_Twilio'  . DS . 'Twilio.php');
        $sid = "AC19273244ff3484ddf47b0537007b652c"; // Your Account SID from www.twilio.com/user/account
        $token = "6eddeb4cecf92cb26fe91097f78a08fd"; // Your Auth Token from www.twilio.com/user/account
        $client = new Services_Twilio ( $sid, $token );
        $from = '+12565888815';
        //$to = '"'.$phonenumber.'"';
        // pr($phonenumber);exit;
        $to = '+917340337597';
        // $code = $this->generateRandomNumber(5);
        // $requestData['User']['u_account_activation_code'] = $code;
        $message = __ ( "Welcome to Anamy!  Please verify your phone number by using this code $code" );
        try {
           
            $message = $client->account->messages->sendMessage ( $from, $to, $message );
        } catch ( Exception $e ) {
            $res ['status'] = 400;
            $res ['userMsg'] = __ ( "Phone Number not valid." );
            $devemsg = "Invalid Phone Number";
            $errcode = 'E105';
            $userdata = array ();
            // $this->responseApi($status, $userMsg , $devemsg , $errcode , $userdata);
            return $res = array (
                    'status' => 0 
            );
        }

        $userMsg = __ ( "Phone Number is valid." );
        $devemsg = "Valid Phone Number";
        $errcode = 'E105';
       /* $userdata = array (
                'code' => $code 
        );
        $data ['User'] ['id'] = $id;
        $data ['User'] ['verification_code'] = $code;
        $this->User->save ( $data );*/
        // $this->responseApi($status, $userMsg , $devemsg , $errcode , $userdata);
        return $res = array (
                'status' => 1 
        );
    }

    public function card(){

        \Stripe\Stripe::setApiKey("sk_test_VPAcaIKJ75bk3IuEXwpDJi6Z");
        try {
  
            $card =  \Stripe\Token::create(array(
          "card" => array(
            "number" => "4242424242424242",
            "exp_month" => 5,
            "exp_year" => 2018,
            "cvc" => "314"
          )
        ));

           print_r($card);  exit; 
          // Use Stripe's library to make requests...
        } catch(\Stripe\Error\Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $body = $e->getJsonBody();
          $err  = $body['error'];
          print_r($body);
          print('Status is:' . $e->getHttpStatus() . "\n");
          print('Type is:' . $err['type'] . "\n");
          print('Code is:' . $err['code'] . "\n");
          // param is '' in this case
          print('Param is:' . $err['param'] . "\n");
          print('Message is:' . $err['message'] . "\n");
        } catch (\Stripe\Error\RateLimit $e) {
          // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
          // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
        }
    }

    public function payment(){
            $stripe_secret_key = 'sk_test_VPAcaIKJ75bk3IuEXwpDJi6Z';
          \Stripe\Stripe::setApiKey($stripe_secret_key);
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => 10,//round($stripe_amount), // amount in cents, again
                "currency" => 'INR',$paymentDetail['CurrencyCode'],
                "source" => $stripeToken,
                "description" => "Payement Received From ".$name,
            ));

            $chargeArr = $charge->__toArray(true);
            $charge_id = $chargeArr['id'];
            $paid = $chargeArr['paid'];
            // prd($chargeArr);

            if ($paid == 1) {
                // Amount Paid Successfully
                // charge
                $balance_transaction = \Stripe\BalanceTransaction::retrieve($chargeArr['balance_transaction']);
                $balance_transaction_array = $balance_transaction->__toArray(true);
                $fee = $balance_transaction_array['fee'];
                $amount_deducted = $balance_transaction_array['amount'];
                $amount_sent = $stripe_amount * 100;
                $currency = $balance_transaction_array['currency'];
                if($currency != strtolower($paymentDetail['CurrencyCode'])){
                    // Payment was in other currency 
                    // Fee is always in the currency of account like if australian account and currency sent in usd then fee will be calculated in AUD so we need the conversion of fee  
                    $converted_fee  = ($amount_sent * $fee) / $amount_deducted;
                    $actual_stripe_tax  = number_format($converted_fee / 100 , 2,'.','');
                }else{
                    $actual_stripe_tax = number_format($fee / 100 , 2,'.','');
                }
                $api_tax_old = $paymentDetail['AmountTotal']['api_tax'];

                $paymentDetail['GrandTotal'] = $actual_stripe_tax + $amountWithoutTranTax;
                $paymentDetail['AmountTotal']['total'] = $paymentDetail['AmountTotal']['total'] - $api_tax_old + $actual_stripe_tax;
                $paymentDetail['AmountTotal']['api_tax'] = $actual_stripe_tax;
                
                unset($chargeArr['fraud_details']);
                unset($chargeArr['refunds']);
                unset($chargeArr['outcome']);
                unset($chargeArr['metadata']);
                unset($chargeArr['source']);
                $paymentDetail['stripe_transaction_array'] = $chargeArr;
                return $paymentDetail;
            }else{
                // Payment Failed
                $failure_message = $charge['failure_message'];
                $msg = $e_json['error']['message'];
                $response = array('msg'=>$e_json, 'status'=>0, 'msg_code'=>4061);
            }
        }catch (\Stripe\Error\ApiConnection $e) {
            // Network problem, perhaps try again.
            $e_json = $e->getJsonBody();
            $msg = $e_json['error']['message'];
            $response = array('msg'=>$msg, 'status'=>0, 'msg_code'=>4062);
        } catch (\Stripe\Error\InvalidRequest $e) {
            // You screwed up in your programming. Shouldn't happen!
            $e_json = $e->getJsonBody();
            $msg = $e_json['error']['message'];
            $response = array('msg'=>$msg, 'status'=>0, 'msg_code'=>4063);
        } catch (\Stripe\Error\Api $e) {
            // Stripe's servers are down!
            $e_json = $e->getJsonBody();
            $msg = $e_json['error']['message'];
            $response = array('msg'=>$msg, 'status'=>0, 'msg_code'=>4064);
        } catch (\Stripe\Error\Card $e) {
            // Card was declined.
            $e_json = $e->getJsonBody();
            $msg = $e_json['error']['message'];
            $response = array('msg'=>$msg, 'status'=>0, 'msg_code'=>4065);
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $e_json = $e->getJsonBody();
            $msg = $e_json['error']['message'];
            $response = array('msg'=>$msg, 'status'=>0, 'msg_code'=>4066);
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            $e_json = $e->getJsonBody();
            $msg = $e_json['error']['message'];
            $response = array('msg'=>$msg, 'status'=>0, 'msg_code'=>4067);
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send // yourself an email
            $e_json = $e->getJsonBody();
            $msg = $e_json['error']['message'];
            $response = array('msg'=>$msg, 'status'=>0, 'msg_code'=>4068);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $e_json = $e->getMessage();
            $msg = $e_json['error']['message'];
            $response = array('msg'=>$msg, 'status'=>0, 'msg_code'=>4069);
        }
    }
    /************************************************************************************************************
    * API                   => Testing section                                                                  *
    * Description           => It is used for test..                                                            *
    * Required Parameters   =>                                                                                  *
    * Created by            => Sunil                                                                            *
    *************************************************************************************************************/
    public function test(){     
        $_POST = array ();
        $_POST ['receiver_id'] = '1';
        $_POST ['relData'] = '1';//$data;
        $_POST ['message'] =  'sunil';
        //$this->sendPushNotification();
        $this->writeResponseLog($this->request->data);
    }


    public function valutcreate(){
        // ### CreditCard
        // A resource representing a credit card that is 
        // to be stored with PayPal.
        $card = new CreditCard();
        $card->setType("visa")
            ->setNumber("4917912523797702")
            ->setExpireMonth("11")
            ->setExpireYear("2019")
            ->setCvv2("012")
            ->setFirstName("Joe")
            ->setLastName("Shopper");
        // ### Additional Information
        // Now you can also store the information that could help you connect
        // your users with the stored credit cards.
        // All these three fields could be used for storing any information that could help merchant to point the card.
        // However, Ideally, MerchantId could be used to categorize stores, apps, websites, etc.
        // ExternalCardId could be used for uniquely identifying the card per MerchantId. So, combination of "MerchantId" and "ExternalCardId" should be unique.
        // ExternalCustomerId could be userId, user email, etc to group multiple cards per user.
       // $card->setMerchantId("MyStore1");
        //$card->setExternalCardId("CardNumber123" . uniqid());
       // $card->setExternalCustomerId("123123-myUser1@something.com");
        // For Sample Purposes Only.
        $request = clone $card;
        // ### Save card
        // Creates the credit card as a resource
        // in the PayPal vault. The response contains
        // an 'id' that you can use to refer to it
        // in future payments.
        // (See bootstrap.php for more on `ApiContext`)
        try {
            $apiContext = $this->configuration();
            $card->create($apiContext);
        } catch (Exception $ex) {
            
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            ResultPrinter::printError("Create Credit Card", "Credit Card", null, $request, $ex);
            exit(1);
        }

        print_r($card->getid()); exit;
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
         ResultPrinter::printResult("Create Credit Card", "Credit Card", $card->getId(), $request, $card);
        return $card;

    }

     public function cardPayment(){
        $apiContext = $this->configuration();
        // $card = require __DIR__ . '/../vault/CreateCreditCard.php';
         //print_r($apiContext);  exit;
        $card  =  require_once(ROOT . DS . 'vendor' . DS . "paypal" . DS . 'rest-api-sdk-php' .DS. 'sample' .DS. 'vault'.DS."CreateCreditCard.php");
        //echo $card ;  exit;
          // ### Credit card token
        // Saved credit card id from a previous call to
        // CreateCreditCard.php
        $creditCardToken = new CreditCardToken();
        $creditCardToken->setCreditCardId($card->getId());
        //$creditCardToken->setCreditCardId('CARD-0FH80093JX9409741LERI5OY');

        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // For stored credit card payments, set the CreditCardToken
        // field on this object.
        $fi = new FundingInstrument();
        $fi->setCreditCardToken($creditCardToken);

        // ### Payer
        // A resource representing a Payer that funds a payment
        // For stored credit card payments, set payment method
        // to 'credit_card'.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));

        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item1 = new Item();
        $item1->setName('Ground Coffee 40 oz')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(7.5);
        $item2 = new Item();
        $item2->setName('Granola bars')
            ->setCurrency('USD')
            ->setQuantity(5)
            ->setPrice(2);

        $itemList = new ItemList();
        $itemList->setItems(array($item1, $item2));

        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $details = new Details();
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.5);

        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(20)
            ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());
           // print($transaction);  exit;
        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));


        // For Sample Purposes Only.
        $request = clone $payment;

        // ###Create Payment
        // Create a payment by calling the 'create' method
        // passing it a valid apiContext.
        // (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state.
        try {
            print_r($apiContext); 
            $payment->create($apiContext);
          echo 'das';  exit;
        } catch (Exception $ex) {

            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            ResultPrinter::printError("Create Payment using Saved Card", "Payment", null, $request, $ex);
            exit(1);
        }
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
         ResultPrinter::printResult("Create Payment using Saved Card", "Payment", $payment->getId(), $request, $payment);
         print_r($card);  exit;
        return $card;


     }
    /*public function afterFilter(Event $event)
    {
       $data = $this->response;
       
       $content = $this->response->body();
        print_r( $content);
        foreach ($content as $key => $value) {
            $_POST[$key] = base64_encode($value);
            $this->request->data = $this->response->body();
        }
       // exit;
       
    }*/
}