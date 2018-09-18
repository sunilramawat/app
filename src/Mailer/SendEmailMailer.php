<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * SendEmail mailer.
 */
class SendEmailMailer extends Mailer
{

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

            $this->to(trim('cgtbharat@gmail.com'));

            $from = 'punit@matellio.com';//strtolower(Configure::read('Site.email'));
            $title = 'KeyApp';//strtolower(Configure::read('Site.title'));


            $this->from(array($from => $title));
            $this->subject($sub);
            $this->emailFormat('html');
            $this->template('default');
            //print_r($content);  exit;

            $this->viewVars(array('mailContents' => $content));
        }
        return FALSE;
    }
public function sendEmail()
{
     $this->from('test@gmail.com','Users')
                ->to('cgtbharat@gmail.com')
                ->subject(sprintf('Welcome %s', 'sunil'))
                ->template('default','default')
                ->set(['data'=>'s']);
}
    /**
     * Mailer's name.
     *
     * @var string
     */
  //  static public $name = 'SendEmail';
}
