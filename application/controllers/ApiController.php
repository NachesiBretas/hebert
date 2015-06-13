<?php

class ApiController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->setLayout('ajax');
    }

    public function indexAction()
    {
        // action body
    }

    public function returnUserAction()
    {
      $user= new Application_Model_User(); 
      header("Content-Type: application/json");
      $aux= $user->findAjaxByName($_GET["query"]);
      echo Zend_Json::encode($aux);
    }


}











