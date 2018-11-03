<?php
use Yaf\Application;
use Yaf\Dispatcher;
class OrderController extends Yaf\Controller_Abstract  {
    public $db;
    public function init(){
        $this->db = new dbModel();
    }
    public function indexAction(){
        $this->getView()->assign("content", "xxxxxx");
    }
    public function payAction(){
        $this->getView()->assign("content", "xxxxxx");
    }
    public function receiveAction(){
        $this->getView()->assign("content", "xxxxxx");
    }
    public function completeAction(){
        $this->getView()->assign("content", "xxxxxx");
    }

}