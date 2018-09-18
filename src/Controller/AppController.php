<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\I18n\Time;
use Cake\I18n\FrozenTime;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\Controller\Controller;
//use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry; //For Query purpose
use Cake\Routing\Router;
use Cake\Mailer\Email;

//use Cake\Mailer\MailerAwareTrait;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
      //use MailerAwareTrait;
   
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    /*public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

         $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'index'
            ]
        ]);

        // Allow the display action so our pages controller
        // continues to work.
       // $this->Auth->allow(['display']);
    }*/
    public function initialize(){
        parent::initialize();


        Time::setJsonEncodeFormat('yyyy-MM-dd HH:mm:ss');  // For any mutable DateTime
        FrozenTime::setJsonEncodeFormat('yyyy-MM-dd HH:mm:ss');  // For any immutable DateTime
        Date::setJsonEncodeFormat('yyyy-MM-dd HH:mm:ss');  // For any mutable Date
        FrozenDate::setJsonEncodeFormat('yyyy-MM-dd HH:mm:ss');  // For any immutable Date
        //$this->loadComponent('Twilio.Twilio');
        $this->loadComponent('Flash');
        //$this->loadComponent('Twilio.Twilio');
        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Users',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login',
                'home'
            ]
        ]);  
         
    }
    public function beforeFilter(Event $event){
        $this->Auth->allow(['index', 'view', 'display']);
        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            $this->viewBuilder()->layout('admin');
            $this->loadComponent('Auth', [
                'authorize' => ['Controller'],
                'authenticate' => [
                    'Form' => [
                        'fields' => [
                            'username' => 'email',
                            'password' => 'password'
                        ],
                        'scope' => [
                            'user_type IN ' => array('0', '1', '4'), //Admin, sub admin and Support
                            'status IN ' => array('0', '1'), //Inactive and active
                        ]
                    ]
                ]
            ]);
            $this->Auth->__set('sessionKey', 'Auth.Admin');
            $this->Auth->loginAction = (['prefix' => 'admin', 'controller' => 'users', 'action' => 'login']);
            $this->Auth->loginRedirect = (['prefix' => 'admin', 'controller' => 'users', 'action' => 'dashboard']);
            $this->Auth->logoutRedirect = (['prefix' => 'admin', 'controller' => 'users', 'action' => 'login']);
        }else{
            $this->loadComponent('Auth', [
                'authorize' => ['Controller'],
                'authenticate' => [
                    'Form' => [
                        'fields' => [
                            'username' => 'email',
                            'password' => 'password'
                        ],
                        'scope' => [
                            'user_type IN ' => array('1'), //user
                            'user_status IN ' => array('0', '1'), //Inactive and active
                        ]
                    ]
                ]
            ]);
            $this->Auth->loginAction = array('controller' => 'Index', 'action' => 'index');
            $this->Auth->loginRedirect = array('controller' => 'Index', 'action' => 'index');
            $this->Auth->logoutRedirect = array('controller' => 'Index', 'action' => 'index');
        
        }
         //$this->loadCMSPages();
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event){
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ){
            $this->set('_serialize', true);
        }
    }

   
    public function user_access($data){
     
        $userTable = TableRegistry::get('Users');
        $check_user = $userTable
        ->find()
        ->where(['id'=>@$data['id']])
        ->first();

        if($check_user['token_id'] != 0){   
            $check_token = sha1(md5('youfeed'.$check_user['id'].'!@#$%'.$check_user['token_id']).")(*&^%$");
            if($check_token == $data['access_token']){
                // Token Match 
                $new_data ['status'] = '540';
                return $new_data ['status'] ;
            }else{
                // New Login
                $new_data ['status'] = '538';
                return $new_data ['status'] ;
                
            }   

        }
        
    }
    
    public function sendUserEmail($to,$subject,$msg){
        $ec = TableRegistry::get('EmailContents');
        $email_record = $ec->getMailContent('WELCOME_USER');
        if (isset($email_record) && !empty($email_record)) {
            $sub = $email_record['ec_subject'];
            $content = $email_record['ec_message'];
            $content = str_replace("{{receiver}}", '<b>' . $subject . '</b>', $content);
            $content = str_replace("{{link}}", '<b>' . $msg . '</b>', $content);
              // print_r($content);  exit;
            $email = new Email('default');
            $email
                ->transport('gmail')
                ->from(['cgtyoufeed@gmail.com' => 'cgtyoufeed@gmail.com'])
                ->to($to)
                ->subject($sub)
                ->emailFormat('html')
                ->template('default')
                ->viewVars(array('mailContents' => $content))
                ->send($content); 

        }
    }

    public function sendUserqueryEmail($to,$subject,$msg){
        $ec = TableRegistry::get('EmailContents');
        $email_record = $ec->getMailContent('KEY_QUERY');
        if (isset($email_record) && !empty($email_record)) {
            $sub = $email_record['ec_subject'];
            $content = $email_record['ec_message'];
           // $content = str_replace("{{receiver}}", '<b>' . '' . '</b>', $content);
            $content = str_replace("{{link}}", '<b>' . $msg . '</b>', $content);
              // print_r($content);  exit;
            $email = new Email('default');
            $email
                ->transport('gmail')
                ->from(['cgtbharat@gmail.com' => 'cgtbharat@gmail.com'])
                ->to($to)
                ->subject($sub)
                ->emailFormat('html')
                ->template('default')
                ->viewVars(array('mailContents' => $content))
                ->send($content); 

        }
    }
    public function sendUserEmailforgot($to,$name,$newpassword){
        $ec = TableRegistry::get('EmailContents');
        $email_record = $ec->getMailContent('FORGOT_PASSWORD');
        if (isset($email_record) && !empty($email_record)) {
            $sub = $email_record['ec_subject'];
            $content = $email_record['ec_message'];
            $content = str_replace("{{receiver}}", '<b>' . $name . '</b>', $content);
            $content = str_replace("{{link}}", '<b>' . $newpassword . '</b>', $content);
           
              // print_r($content);  exit;
       $email = new Email('default');
       $email
            ->transport('gmail')
            ->from(['cgtbharat@gmail.com' => 'cgtbharat@gmail.com'])
            ->to($to)
            ->subject($sub)
             ->emailFormat('html')
             ->template('default')
            ->viewVars(array('mailContents' => $content))
            ->send($content); 

        }
    }

    protected function checkUserStatus($user, $is_popup = 0) {

        $msg = '';
        if ($user['user_status'] == 0) {
            $msg = 'Your account is disabled by administrator.';
        } elseif ($user['user_status'] == 2) {
            $msg = 'Your account is deleted by administrator.';
        } elseif ($user['user_status'] == 3) {
            $msg = 'Your email verification is pending. Please, verify your email address.';
        }

        if (!empty($msg)) {
            if ($is_popup == 1) {
                return array('error' => 1, 'msg' => $msg);
            } else {
                $this->Flash->error(__($msg));
                return false;
            }
        } else {
            if ($is_popup == 1) {
                return array('error' => 0);
            } else {
                return true;
            }
        }
    }


    function sendPushNotification() {
        $this->loadModel('Users');
        $data                       = $_POST['relData'];
        $receiver_id                = trim($_POST['receiver_id']); 
        $message                    = trim($_POST['message']);
       // $badge                      = trim(@$_POST['badge']);
        
        if (strlen($message) > 189) {
            $message = substr($message, 0, 185);
            $message = $message . '...';
        }else{
            $message = $message;
        }
        
        $userTable = TableRegistry::get('Users');
            $check_user = $userTable
                    ->find()
                    ->where(['id'=> $receiver_id])
                    ->first();
                   
        if (empty($receiver_id)) {
            exit;
        }
        //print_r($check_user); exit;
        if ($check_user['device_type'] == 0) {
                $check_user['device_id'];
            
            $this->android_push($check_user['device_id'], $message,  $data, $badge);
        }else{
            $this->iphone_push($check_user['device_id'], $message,  $data, $badge=0);
        }
       
        return;
    }

    public function android_push($id, $message, $relData, $badge){
        header('Content-type: text/html; charset=utf-8');
        // API access key from Google API's Console
        $API_ACCESS_KEY  = 'AAAAVV9KqBI:APA91bE8Ctb73w4S0MRO3Cw3NTs2_vLUO_IPIA2Ag1YM2VKjh1yaCufSCYM-4eO3IKHEBg42JD35HTftkC8SWZMQBU7_69KVhywLkPvcCJ-RlzpMHPxAmIbGdwcIMq3yE5mXCT27CPRbz699gFiRJ-usuCpxAfbXSg';
       
    
        $registrationIds = array($id);
        //echo 'come'; exit;
        $msg['data']= array(
        'message' => $message,
        'noti_id' => '1',
        'badge' => (int)$badge,
        'relData' => $relData,
        //'vibrate' => 1,
        //'sound' => 1,
        //'data'=>$data
        );
       
        $fields = array(
                       'registration_ids' => $registrationIds,
                       'data' => $msg,
                       //'relData' => $relData
                        );
        $headers         = array(
        'Authorization: key=' . $API_ACCESS_KEY,
        'Content-Type: application/json'
        );
        $ch        = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
                    curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //curl_close($ch);
        $res = json_decode($result,true);
        //print_r($result); exit;
        if($res['success']){
        //echo 'complete'; exit;
        curl_close($ch);
        return 1;
        }else{
        //  echo 'not'; exit;
        curl_close($ch);
        return 0;
        }
    }
    
    // Iphone APNS
    public function iphone_push($id, $message, $relData, $badge) {
        header('Content-type: text/html; charset=utf-8');
       // print_r($relData);
        //  echo $deviceToken = $id; exit;
        // $deviceToken = '4ab26204eea4e9225414dd81e3518a1015da7e353e7f82ebf71eadaafae17fd8';
        // Put your private key's passphrase here:
        $deviceToken = $id;
        $passphrase  = 'techno';
        // Put your alert message here:
        // $message = 'My first push notification!';
        //echo $_SERVER['DOCUMENT_ROOT'].$this->webroot.'ck.pem' ;
        // //////////////////////////////////////////////////////////////////////////////
        $ctx         = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', './AnamyPush.pem');
        //echo stream_context_set_option($ctx, 'ssl', 'local_cert', $_SERVER['DOCUMENT_ROOT'].$this->webroot.'ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        //print_r($fp);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
            //echo 'Connected to APNS' . PHP_EOL;
            // Create the payload body
            //$resp = $this->cpSTR_to_utf8STR($message);
            //$this->writeResponseLog($resp);
            //$m = (string) $this->cpSTR_to_utf8STR($message);
        //echo strlen($message);
    
        $body['aps'] = array(
        'alert' => html_entity_decode($message, ENT_NOQUOTES, 'UTF-8'),
        'sound' => 'default',
        'noti_id' => '1',
        'badge' => (int)$badge,
        'relData' => $relData,
        
    
        );
        //print_r($body); 
        //$this->writeResponseLog($body);
        //echo $count;
        // Encode the payload as JSON
        $payload = json_encode($body);
        //echo strlen($payload);
        // Build the binary notification
        $msg     = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $msg     = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result  = fwrite($fp, $msg, strlen($msg));
        //print_r($result); exit;
               /* if (! $result)
                    echo 'Message not delivered' . PHP_EOL;
                else
                    echo 'Message successfully delivered' . PHP_EOL;*/
                    
                    //Close the connection to the server
                    fclose($fp);
                    return;
    }

     /**
     * Function use to Basic Websites Setting for Front user and Admin
     */
    protected function SiteSettings() {
        $this->loadModel('Globalsettings');
        $site_settings = $this->Globalsettings->get(1, [
            'contain' => []
        ]);
        foreach ($site_settings->toArray() as $key => $each_setting) {
            Configure::write(str_replace("gs_", "Site.", $key), $each_setting);
        }
        $adminEmail = Configure::read('Site.email');
        Configure::write('ADMIN_MAIL', $adminEmail);
    }
    public function writeResponseLog($ResponseData) {
             //$this->loadModel('Audit');
        $user_sis = $this->Auth->User('u_first_name');
    //  $this->Session->read('Message.flash.element');
        $user_sid = $this->Auth->User('u_id');

        $action_name = $this->request->params['action'];
        //$adminUse = array('admin_login','admin_index','admin_logout','admin_manage_settings','admin_json','admin_add','admin_edit','admin_changestatus','admin_change_password','admin_setting','admin_setprivilege','admin_manage_profile','admin_userreport','admin_allriderevenuereport','admin_driverrevenuereport','admin_creditsreport','admin_promocodesreport','admin_delete');
        if($this->request->params['controller'] == "Api"){
            
            
            $createfilename =  date('d F Y');
                
            
            $createdir = 'logs/api_'.$createfilename ;
            if (!file_exists($createdir)) {
                mkdir($createdir, 0777, true);
            }
                
            $currentfilename = $createdir.'/api_'.$createfilename.'.txt';
              exit;  
            if(!(file_exists($currentfilename)))
            {
                touch(WWW_ROOT.$currentfilename);
            }
            $file = fopen(WWW_ROOT.$currentfilename,"a");
            $controller =  $this->request->params['controller'];
        fwrite($file,"\n". date('Y-m-d H:i:s A')."\n User Name :- ".$user_sis.' User Id => ('.$user_sid.')' );
        fwrite($file,"\n Called from api :- ".$controller .'/'.$this->request->params['action']);
        fwrite($file,"\n Ip Address :-  ". $this->request->clientIp());
        fwrite($file,"\n ". print_r($_POST, true));
        
        fwrite($file,"\n ". print_r($ResponseData, true));
        if(!empty($_FILES))
        {
        
            fwrite($file,"\n ".print_r($_FILES, true));
        
        }
        
        if($ResponseData['error'] == '0')
        {
            $re = 'Success';
        }
        else{
            $re = 'Fail';
        }
            
        fwrite($file,"\n ". print_r(@$re, true));
        fclose($file);
            if($ResponseData['error'] == '0' ){
                $audit_data['Audit']['event'] = $this->request->params['action'];
                $audit_data['Audit']['model'] = $controller;
                $audit_data['Audit']['entity_id'] = json_encode( $ResponseData );
                $audit_data['Audit']['request_id'] = $user_sid ? $user_sid : 0 ;
                $audit_data['Audit']['json_object'] = json_encode( $_POST );
                $audit_data['Audit']['source_id'] = $re;
                $audit_data['Audit']['section']= 2;
                $audit_data['Audit']['name']= $user_sis ? $user_sis : 0 ;
                $audit_data['Audit']['ip']= $this->request->clientIp();
                $this->Audit->saveAll($audit_data);
            }elseif($ResponseData['error'] == '1'){
                $audit_data['Audit']['event'] = $this->request->params['action'];
                $audit_data['Audit']['model'] = $controller;
                $audit_data['Audit']['entity_id'] = json_encode( $ResponseData );
                $audit_data['Audit']['request_id'] = $user_sid ? $user_sid : 0 ;
                $audit_data['Audit']['json_object'] = json_encode( $_POST );
                $audit_data['Audit']['source_id'] = $re;
                $audit_data['Audit']['section']= 2;
                $audit_data['Audit']['name']= $user_sis ? $user_sis : 0 ;
                $audit_data['Audit']['ip']= $this->request->clientIp();
                $this->Audit->saveAll($audit_data);
            }
            
            
        }else{
            
            $createfilename =  date('d F Y');
            
                
            $createdir = 'files/website_'.$createfilename ;
            if (!file_exists($createdir)) {
                mkdir($createdir, 0777, true);
            }
            
            $currentfilename = $createdir.'/website_'.$createfilename.'.txt';
            
            if(!(file_exists($currentfilename)))
            {
                touch(WWW_ROOT.$currentfilename);
            }
            $file = fopen(WWW_ROOT.$currentfilename,"a");
            //fwrite($file,"\n". date('Y-m-d H:i:s A')."\n Called from api :- ".$this->here);
            $controller =  Inflector::pluralize($this->name);
            fwrite($file,"\n". date('Y-m-d H:i:s A')."\n User Name :- ".$user_sis.' User Id => ('.$user_sid.')' );
            fwrite($file,"\n Called from api :- ".$controller .'/'.$this->request->params['action']);
            fwrite($file,"\n ". print_r($_POST, true));
            fwrite($file,"\n Ip Address :-  ". $this->request->clientIp());
            //fwrite($file,"\n re :- ".  $ResponseData['error']);
            //fwrite($file,"\n ". print_r(json_encode( $_POST['data']['User'] ), true));
            if(!empty($_FILES))
            {
                    
                fwrite($file,"\n ".print_r($_FILES, true));
                    
            }
                
            //echo $ResponseData['error'];
            if($ResponseData['error']== 0)
            {
                $re = "Success";
            }
            else{
                $re = "Fail";
            }
            fwrite($file,"\n result :- ".  $re);
            fclose($file);
            if($ResponseData['error'] == '0' ){
                $audit_data['Audit']['event'] = $this->request->params['action'];
                $audit_data['Audit']['model'] = $controller;
                $audit_data['Audit']['entity_id'] = '';
                $audit_data['Audit']['request_id'] = $user_sid ? $user_sid : 0 ;
                $audit_data['Audit']['json_object'] = json_encode( $_POST );
                $audit_data['Audit']['source_id'] = $re;
                $audit_data['Audit']['section']= 3;
                $audit_data['Audit']['name']= $user_sis ? $user_sis : 0 ;
                $audit_data['Audit']['ip']= $this->request->clientIp();
                //  pr($audit_data); exit;
            
                $this->Audit->saveAll($audit_data);
            
            }elseif($ResponseData['error'] == '1'){
                $audit_data['Audit']['event'] = $this->request->params['action'];
                $audit_data['Audit']['model'] = $controller;
                $audit_data['Audit']['entity_id'] = '';
                $audit_data['Audit']['request_id'] = $user_sid ? $user_sid : 0 ;
                $audit_data['Audit']['json_object'] = json_encode( $_POST );
                $audit_data['Audit']['source_id'] = $re;
                $audit_data['Audit']['section']= 3;
                $audit_data['Audit']['name']= $user_sis ? $user_sis : 0 ;
                $audit_data['Audit']['ip']= $this->request->clientIp();
                $this->Audit->saveAll($audit_data);
            }
            
            
        }
        
        
        
        if(!(file_exists('files/log.txt')))
        {
            touch(WWW_ROOT.'files/log.txt');
        }
        
        $file = fopen(WWW_ROOT."files/log.txt","a");
        $controller =  Inflector::pluralize($this->name);
        fwrite($file,"\n". date('Y-m-d H:i:s A')."\n User Name :- ".$user_sis.' User Id => ('.$user_sid.')' );
        fwrite($file,"\n Called from api :- ".$controller .'/'.$this->request->params['action']);
        fwrite($file,"\n ". print_r($_POST, true));
        fwrite($file,"\n Ip Address :-  ". $this->request->clientIp());
        fwrite($file,"\n ". print_r($ResponseData, true));
    //  fwrite($file,"\n ". print_r(filesize('log.txt'), true));
        
        if(!empty($_FILES))
        {
        
            fwrite($file,"\n ".print_r($_FILES, true));
        
        }
        
        if($ResponseData['error']== '0')
        {
            $re = 'Success';
        }
        else{
            $re = 'Fail';
        }
            
        fwrite($file,"\n ". print_r(@$re, true));
        fclose($file);
        /* if($ResponseData['error'] == '0' ){
            $audit_data['Audit']['event'] = $this->request->params['action'];
            $audit_data['Audit']['model'] = $controller;
            $audit_data['Audit']['entity_id'] = '';
            $audit_data['Audit']['request_id'] = $user_sid ? $user_sid : 0 ;
            $audit_data['Audit']['json_object'] = json_encode( $_POST );
            $audit_data['Audit']['source_id'] = $re;
            $this->Audit->saveAll($audit_data);
        }elseif($ResponseData['error'] == '1'){
            $audit_data['Audit']['event'] = $this->request->params['action'];
            $audit_data['Audit']['model'] = $controller;
            $audit_data['Audit']['entity_id'] = '';
            $audit_data['Audit']['request_id'] = $user_sid ? $user_sid : 0 ;
            $audit_data['Audit']['json_object'] = json_encode( $_POST );
            $audit_data['Audit']['source_id'] = $re;
            $this->Audit->saveAll($audit_data);
        } */
            
    }
       
    public function send() { $sendSms = $this->Twilio->sendSms($to, $body, $from); }
}
