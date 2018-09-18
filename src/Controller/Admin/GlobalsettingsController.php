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
namespace App\Controller\Admin; // THIS IS THE CORRECT NAME SPACE

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Controller\Component\AuthComponent;

use Cake\ORM\TableRegistry;


/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class GlobalsettingsController extends AppController
{
    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    //public $helpers = array('Captcha'); 
    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow(['']);
    }
    
    public function edit(){

        $this->set('title_for_layout',__("Edit Global Settings"));
        $global_setting = $this->Globalsettings->get(1, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $global_setting = $this->Globalsettings->patchEntity($global_setting, $this->request->data);
            if ($this->Globalsettings->save($global_setting)) {
                if($this->request->data['gs_default_country_id'] != $global_setting->gs_default_country_id){
                    $countries = TableRegistry::get('Countries');
                    $query = $countries->query();
                    $query->update()
                        ->set(['country_is_default' => 0])
                        ->execute();
                    $query->update()
                        ->set(['country_is_default' => 1])
                        ->where(['country_id' => $this->request->data['gs_default_country_id']])
                        ->execute();
                }

                $this->Flash->success(__('Global Setting have been updated successfully.'));
                return $this->redirect(['action' => 'edit']);
            } else {
                $this->Flash->error(__('Global Setting could not be updated. Please, try again.'));
            }
        }
        
        $this->set(compact('global_setting'));
        $this->set('_serialzie', ['global_setting']);
    }
}
