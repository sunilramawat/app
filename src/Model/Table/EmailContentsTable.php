<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Mailer\Email;
use Cake\Core\Configure;

class EmailContentsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->table($this->table());
        $this->primaryKey('ec_id');
    }
    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {   
        
        $validator
            ->notEmpty('ec_title')
            ->notBlank('ec_title')
            ->add('ec_title', [
                 'maxLength' => [
                    'rule' => ['maxLength', 55],
                    'message' => 'Title must be no larger than 55 characters long.'
                ]
            ]);

        $validator
            ->notEmpty('ec_message')
            ->notBlank('ec_message')
            ->add('ec_message', [
                 'maxLength' => [
                    'rule' => ['maxLength', 500],
                    'message' => 'Message must be no larger than 500 characters long.'
                ]
            ]);

        $validator
            ->notEmpty('ec_subject')
            ->notBlank('ec_subject')
            ->add('ec_subject', [
                 'maxLength' => [
                    'rule' => ['maxLength', 55],
                    'message' => 'Message must be no larger than 55 characters long.'
                ]
            ]);
        return $validator;
    }
    
     /**
     * function for Send Mail..
     */
    public function __SendMail($to, $subject, $content,$attachment=NULL){
        //http://book.cakephp.org/3.0/en/core-libraries/email.html
        
        $cake_email = new Email('gmail');

        $cake_email->config('smtp');
        $cake_email->to($to);

        $from = strtolower(Configure::read('Site.email'));
        $title = strtolower(Configure::read('Site.title'));

        $cake_email->from(array($from => $title));
        $cake_email->subject($subject);
        $cake_email->emailFormat('html');
        $cake_email->template('default');
        if (!empty($attachment) && $attachment != '' && is_array($attachment)) {
            $cake_email->attachments($attachment);
        }
        $cake_email->viewVars(array('mailContents' => $content));
        $is_development_mode = Configure::read('Site.is_development_mode');
        
        if($cake_email->send()){
//            echo '1';exit();
            return true;
        };
        /*
        if($is_development_mode!=1){
            //prd($cake_email->send());
            $cake_email->send();
        }else{
            echo 'To   : ';
            echo 'From : '.$from;
            echo 'Title: '.$title;
            echo 'Sub  : '.$subject;
        }*/
       
        return true;
    }
    /**
     * Common Function for getting mail content using unique name used from Mailer folders..................
     */
    public function getMailContent($unique_name){
        $conditions = array('conditions' => array('ec_key LIKE' => $unique_name), 'recursive' => 1 );
        $mail_content = $this->find('all', $conditions)->first();
        if(isset($mail_content) && !empty($mail_content)){
            return $mail_content;
        }else{
            return false;
        }
    }

}
