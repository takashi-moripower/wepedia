<?php

namespace App\Controller;

use Cake\Routing\Router;

if (!defined('ELFINDER_DIR')) {
        define('ELFINDER_DIR', ROOT . DS . 'webroot/upload' . DS);
}

if (!defined('ELFINDER_URL')) {
//		define('ELFINDER_URL', 'http://wenet.sakura.ne.jp' . DS . 'upload'. DS);
		define('ELFINDER_URL', Router::url('/',true) . 'upload'. DS);
}

define('ELFINDER_LIB', APP . '..' . DS . 'webroot' . DS . 'lib' . DS . 'elfinder' . DS . 'php' . DS);

require_once ELFINDER_LIB . 'elFinderConnector.class.php';
require_once ELFINDER_LIB . 'elFinder.class.php';
require_once ELFINDER_LIB . 'elFinderVolumeDriver.class.php';
require_once ELFINDER_LIB . 'elFinderVolumeLocalFileSystem.class.php';

use App\Controller\AppController;
use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;

class FilesController extends AppController {

        public $name = 'Files';
        public $uses = array();
        public $opts = array(
            'roots' => array(
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => ELFINDER_DIR, // path to files (REQUIRED)
                    'URL' => ELFINDER_URL, // URL to files (REQUIRED)
                    'tmbBgColor' => 'transparent'
                )
            )
        );

        public function beforeFilter(\Cake\Event\Event $event) {
                parent::beforeFilter($event);
        }

        public function index0() {
                $title_for_layout = 'Media Library';
                $this->set(compact('title_for_layout'));
        }
        
        public function connect(){
                if ($this->request->is('post') || $this->request->is('Ajax')) {
                $connector = new \ElFinderConnector(new \ElFinder($this->opts));
                $connector->run();
                }
        }
        
        public function index(){
                
        }

}
