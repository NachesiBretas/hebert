<?php

class DashboardController extends Zend_Controller_Action
{

    public function init()
    {
      $this->_helper->layout()->setLayout('dashboard');
    }

    public function indexAction()
    {
    	$project = new Application_Model_Project();
        $this->view->list = $project->listProjects();
    }


}

