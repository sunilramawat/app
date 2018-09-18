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

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Controller\Component\AuthComponent;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry; //For Query purpose
use Cake\Utility\Inflector;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class IndexController extends AppController
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
        $this->Auth->allow(['index','loadcategory','guestpopup','catpopup','getVendors']);
    }
   
    public function index(){
        $this->set('title_for_layout', __('Home'));
        
        if ($this->request->is('post')) {
        }
        $this->set(compact('newsletter','allbanners','popular_cat','featured_product','latest_product','popular_product'));
        $this->set('_serialize', ['newsletter']);
    }
   
   
    
    
    public function getVendors () {
        
        $data = '';
        if($this->request->is(['post','ajax'])) {
            
            $postData = $this->request->data;
            
            $this->loadModel('Users');
            $users = $this->Users->find('all', [
                'conditions' => ['status' => 1,'user_type'=>3,'concat_ws(" ",first_name,last_name) LIKE' => '%'.$postData['q'].'%']
            ]);
            
            if(count($users->toArray()) > 0) {
                foreach ($users->toArray() as $key => $value) {
                    $data[] = [
                        'id'=>$value->id,
                        'label'=>  ucwords($value->first_name.' '.$value->last_name),
                    ];
                }
            }
        }
        
        //header('Content-type: application/json');
        echo json_encode($data);
        exit();
    }
}
