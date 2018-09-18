<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

/**
 * EmailContents Controller
 *
 * @property \App\Model\Table\EmailContentsTable $EmailContents
 */
class EmailContentsController extends AppController {

    function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        
        $this->set('title_for_layout', __("Email Contents List"));

        $emailContents = $this->paginate($this->EmailContents);

        $this->set(compact('emailContents'));
        $this->set('_serialize', ['emailContents']);
    }

    /**
     * View method
     *
     * @param string|null $id Email Content id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function preview_email($id = null) {
        
        if (empty($id)) {
            header('HTTP/1.1 401 Mail id not found.' , true, 401);
            exit;
        }
        
        $this->viewBuilder()->layout(false);

        if ($this->request->is('ajax')) {
            
            $email_id = $id;
            
            $EmailContentData = $this->EmailContents->get($email_id, [
                'contain' => []
            ]);
            
            $this->set('EmailContentData', $EmailContentData);
            $this->set('_serialize', ['EmailContentData']);
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Email Content id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        if (empty($id)) {
            $this->Flash->error(__('Invalid  id.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->set('title_for_layout', __('Edit Email Contents'));

        $emailContent = $this->EmailContents->get($id, [
            'contain' => []
        ]);

        $keywords = $emailContent->ec_keywords;
        if ($this->request->is(['patch', 'post', 'put'])) {

            $allKey = explode(",", $keywords);
            $count_key = 0;
            $message = $this->request->data['ec_message'];
            for ($j = 0; $j < count($allKey); $j++) {
                if (strstr($message, trim($allKey[$j]))) {
                    $count_key++;
                }
            }

            if ($count_key == count($allKey)) {
                $emailContent = $this->EmailContents->patchEntity($emailContent, $this->request->data);
                if ($this->EmailContents->save($emailContent)) {
                    $this->Flash->success(__('The email content has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The email content could not be saved. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('Please add all keywords in message.'));
            }
        }
        $this->set(compact('emailContent'));
        $this->set('_serialize', ['emailContent']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Email Content id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function compose_mail($type = Null) {
        $this->set('title_for_layout', __('Compose Mail'));
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $redirectto_url = ['controller' => 'email_contents', 'action' => 'index', 'prefix' => 'admin'];
            if (isset($type) && !empty($type)) {
                switch ($type) {
                    case '1': //Customer User
                        $redirectto_url = ['controller' => 'Users', 'action' => 'customers', 'prefix' => 'admin'];
                        break;
                    case '2': //Vendor User
                        $redirectto_url = ['controller' => 'Users', 'action' => 'retailers', 'prefix' => 'admin'];
                        break;
                    case '4': //Support User
                        $redirectto_url = ['controller' => 'Users', 'action' => 'support_index', 'prefix' => 'admin'];
                        break;
                    case '5': //Newsletters
                        $redirectto_url = ['controller' => 'Newsletters', 'action' => 'index', 'prefix' => 'admin'];
                        break;
                    case '6': //Trusted Retailer 
                        $redirectto_url = ['controller' => 'Users', 'action' => 'trustedretailers', 'prefix' => 'admin'];
                        break;
                    case '7': //Trusted Customer 
                        $redirectto_url = ['controller' => 'Users', 'action' => 'trustedreseller', 'prefix' => 'admin'];
                        break;    
                    default:
                        $redirectto_url = ['controller' => 'email_contents', 'action' => 'index', 'prefix' => 'admin'];
                        break;
                }
            }

            $this->set('cancel_url', $redirectto_url);
            if (isset($data['submit']) && $data['submit'] == 'send_mail') {
                if ($type == 5) {
                    $cntry_model = TableRegistry::get('Newsletters');
                    
                    $ns_idsArr = explode(',', $data['ns_ids']);
                    $NewsletterTable = TableRegistry::get('Newsletters');
                    $user_emails = $NewsletterTable->find('list',[
                        'keyField' => 'id',
                        'valueField' => 'email'
                    ])
                    ->where([
                        'id IN'          => $ns_idsArr,
                        'status != '  => 2
                        ])
                    ->toArray();
                } else {
                    $userArr = explode(',', $data['user_ids']);
                    $Users = TableRegistry::get('Users');
                    $user_emails = $Users->find('list',[
                        'keyField' => 'id',
                        'valueField' => 'email'
                    ])
                    ->where([
                        'id IN'          => $userArr,
                        'status != '  => 2
                        ])
                    ->toArray();
                }
                $this->set('user_email', implode(',', $user_emails));
                $this->request->data['title'] = implode(',', $user_emails);
            } else {
                $new_data = $this->request->data;
                $emailsnow = explode(',', $new_data['title']);
                $notsent = array();
                $this->loadModel('EmailContents');
                foreach ($emailsnow as $reciever) {
                    if (filter_var(trim($reciever), FILTER_VALIDATE_EMAIL)) {
                        $sent = $this->EmailContents->__SendMail(trim($reciever), $new_data['subject'], $new_data['message']);

                        if ($sent !== true) {
                            $notsent[] = trim($reciever);
                        }
                    }
                }
                if (isset($notsent) && !empty($notsent)) {
                    if (count($notsent) == count($emailsnow)) {
                        // Not Sent to All
                        $this->Flash->error(__('Mail could not be Sent'));
                    } else {
                        // Not Sent to some
                        $this->Flash->error(__('Mail  could not be sent to Reciepients => ' . implode(' ,', $notsent)));
                    }
                    return $this->redirect(['action' => 'compose_mail']);
                } else {
                    $this->Flash->success(__('Mail Successfully Sent.'));
                    return $this->redirect($redirectto_url);
                }
            }
        }
    }

}