<?php

namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class UserMailer extends Mailer {

    /**
     * function for forgot password email
     */
    public function ForgotPassword($email = '', $name = '',$newpassword='') {
        $ec = TableRegistry::get('EmailContents');
        $email_record = $ec->getMailContent('FORGOT_PASSWORD');
        if (isset($email_record) && !empty($email_record)) {
            $sub = $email_record['ec_subject'];
            $content = $email_record['ec_message'];
            $content = str_replace("{{receiver}}", '<b>' . $name . '</b>', $content);
            $content = str_replace("{{link}}", '<b>' . $newpassword . '</b>', $content);
            $this->to(trim($email));
            //$this->to(trim('cgtbharat@gmail.com'));
            $from = 'punit@matellio.com';//strtolower(Configure::read('Site.email'));
            $title = 'Anamy';//strtolower(Configure::read('Site.title'));

            $this->from(array($from => $title));
            $this->subject($sub);
            $this->emailFormat('html');
            $this->template('default');
         

            $this->viewVars(array('mailContents' => $content));
        }
        return FALSE;
    }

    /**
     * function for customer registration
     */
    public function Registration($email = '', $name = '', $linik = '') {
        $ec = TableRegistry::get('EmailContents');
        $email_record = $ec->getMailContent('WELCOME_USER');

        if (isset($email_record) && !empty($email_record)) {
            $sub = $email_record['ec_subject'];
            $content = $email_record['ec_message'];
            $content = str_replace("{{receiver}}", '<b>' . $name . '</b>', $content);
            $content = str_replace("{{link}}", '<b>' . $linik . '</b>', $content);

            $this->to(trim($email));

            $from = 'punit@matellio.com';//strtolower(Configure::read('Site.email'));
            $title = 'Anamy';//strtolower(Configure::read('Site.title'));

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

        
            /*$this->from(array($from => $title));
            $this->subject($sub);
            $this->emailFormat('html');
            $this->template('default');
          //  print_r($content);  exit;

            $this->viewVars(array('mailContents' => $content));*/
        }
        return FALSE;
    }
    
    
    /**
     * Contact us Mail for front..
     */
    public function contactUsMail($userName, $userEmail, $msg) {
        $ec = TableRegistry::get('EmailContents');
        $email_record = $ec->getMailContent('CONTACT_US');
        if (isset($email_record) && !empty($email_record)) {
            $sub = $email_record['ec_subject'];
            $content = $email_record['ec_message'];
            $dummy_arg = array('{{name}}', '{{email}}', '{{message}}');
            $real_value = array($userName, $userEmail, $msg);
            $content = str_replace($dummy_arg, $real_value, $content);
            $email = Configure::read('ADMIN_MAIL');

            $this->to(trim($email));

            $from = strtolower(Configure::read('Site.email'));
            $title = strtolower(Configure::read('Site.title'));

            $this->from(array($from => $title));
            $this->subject($sub);
            $this->emailFormat('html');
            $this->template('default');

            $this->viewVars(array('mailContents' => $content));
        }
        return FALSE;
    }
    
   
   
}

?>