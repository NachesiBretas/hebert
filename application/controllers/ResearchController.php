<?php

class ResearchController extends Zend_Controller_Action
{

    public function init()
    {
      $this->_helper->layout()->setLayout('dashboard');
      $authNamespace = new Zend_Session_Namespace('userInformation');
      $this->view->institution = $authNamespace->institution;
      $this->view->userId = $authNamespace->user_id;
    }

    public function indexAction()
    {
        $projectId = $this->getRequest()->getParam('pid');
        $this->view->pid = $projectId;

        $project = new Application_Model_Project();
        $this->view->projectName = $project->nameProject($projectId);

    }

    public function newAction()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 700);
      $projectId = $this->getRequest()->getParam('pid');
      $this->view->pid = $projectId;
      $project = new Application_Model_Project();
      $this->view->projectName = $project->nameProject($projectId);

      if( $this->getRequest()->isPost() ) 
      { 
        try
        {
          $data = new Application_Model_Research();
          $dataId = $data->loadFile($_FILES,$projectId);
          if($dataId)
          {
            $this->view->save = "success";
          }
          else
          {
            $this->view->save = "error";
          }
        }catch(Zend_Exception $e){
           $this->view->save = true;
        }
      }
    }

    public function viewAction()
    {
      try
      {
        $pagination = new Application_Model_Pagination();
        $research = new Application_Model_Research();
        $project = new Application_Model_Project();

        $page = $this->getRequest()->getParam('page');
        $projectId = $this->getRequest()->getParam('pid'); 
        $this->view->pid = $projectId; 
        $this->view->projectName = $project->nameProject($projectId); 
        $field = $this->getRequest()->getParam('field');
        $option = $this->getRequest()->getParam('option');
          if($page == '') $page = 1;
           if($field != "")
            {
              switch ($option){
               case 1:  
                $researches = $research->returnByLine($projectId,urldecode($field));
                Break;
               case 2:  
                $researches = $research->returnByDoor($projectId,urldecode($field));
                Break; 
               case 3:  
                $researches = $research->returnByOrigin($projectId,urldecode($field));
                Break; 
               case 4:  
                $researches = $research->returnByCard($projectId,urldecode($field));
                Break;

              }

                if(isset($researches) && count($researches))
                $this->view->list = $pagination->generatePagination($researches,$page,10);
              $this->view->field = $field;
              $this->view->option = $option;                
            }
            else{
                   $researches = $research->listResearches($projectId);
                   $this->view->list = $pagination->generatePagination($researches,$page,10); 
                }     
      }catch(Zend_Exception $e){
        $this->view->save = 'error';
        echo $e->getMessage();
      }
    }


    public function reportAction()
    {
      try
      {
        $project = new Application_Model_Project();

        $projectId = $this->getRequest()->getParam('pid');
        $this->view->pid = $projectId;
        $this->view->projectName = $project->nameProject($projectId);

      }catch(Zend_Exception $e){
        $this->view->save = 'error';
        echo $e->getMessage();
      }
    }



    public function errorReportAction()
    {
      try
      {
        $pagination = new Application_Model_Pagination();
        $research = new Application_Model_Research();
        $project = new Application_Model_Project();

        $page = $this->getRequest()->getParam('page');
        $projectId = $this->getRequest()->getParam('pid');
        $this->view->pid = $projectId;
        $this->view->projectName = $project->nameProject($projectId);

        $this->view->list = $research->listDataByCardCods($projectId);

      }catch(Zend_Exception $e){
        $this->view->save = 'error';
        echo $e->getMessage();
      }
    }


    public function desativateAction()
    {
      try
      {
        $research = new Application_Model_Research();
        $project = new Application_Model_Project();

        $projectId = $this->getRequest()->getParam('pid');
        $this->view->pid = $projectId;
        $this->view->projectName = $project->nameProject($projectId);
        $data = $this->getRequest()->getPost();

        $research->registerTemp($data['card_cod'],$projectId,$data['id'],2);
        $errors = $research->listNewErrors($data['card_cod'],$projectId);
        if(is_array($errors)){
          if(array_search('mi', $errors)){
            $this->view->list = $research->getDataErrorTempByCardCod($projectId, $data['card_cod']);
            $this->view->mi = 1;
          }
          else{
            $this->view->list = $research->getDataErrorTempByCardCod($projectId, $data['card_cod']);;
          }
        }
        else{
          $research->changeStatus($data['id'],$projectId,2);
          // $research->deleteErrorTemp();
          $this->_redirect('/research/error-report/pid/'.$projectId);
        }

      }catch(Zend_Exception $e){
        $this->view->save = 'error';
        echo $e->getMessage();
      }
    }


    public function pairLackAction()
    {
      try
      {
        $research = new Application_Model_Research();
        $project = new Application_Model_Project();

        $projectId = $this->getRequest()->getParam('pid');
        $this->view->pid = $projectId;
        $this->view->projectName = $project->nameProject($projectId);
        $data = $this->getRequest()->getPost();

        $research->registerTemp($data['card_cod'],$projectId,$data['id'],3);
        $errors = $research->listNewErrors($data['card_cod'],$projectId);
        if(is_array($errors)){
          if(array_search('mi', $errors)){
            $this->view->list = $research->getDataErrorTempByCardCod($projectId, $data['card_cod']);
            $this->view->mi = 1;
          }
          else{
            $this->view->list = $research->getDataErrorTempByCardCod($projectId, $data['card_cod']);;
          }
        }
        else{
          $research->changeStatus($data['id'],$projectId,3);
          // $research->deleteErrorTemp();
          $this->_redirect('/research/error-report/pid/'.$projectId);
        }

      }catch(Zend_Exception $e){
        $this->view->save = 'error';
        echo $e->getMessage();
      }
    }

    public function acceptAction()
    {
      try
      {
        $research = new Application_Model_Research();
        $project = new Application_Model_Project();

        $projectId = $this->getRequest()->getParam('pid');
        $this->view->pid = $projectId;
        $this->view->projectName = $project->nameProject($projectId);
        $data = $this->getRequest()->getPost();

        $research->registerTemp($data['card_cod'],$projectId,$data['id'],4);
        $errors = $research->listNewErrors($data['card_cod'],$projectId);
        if(is_array($errors)){
          if(array_search('mi', $errors)){
            $this->view->list = $research->getDataErrorTempByCardCod($projectId, $data['card_cod']);
            $this->view->mi = 1;
          }
          else{
            $this->view->list = $research->getDataErrorTempByCardCod($projectId, $data['card_cod']);;
          }
        }
        else{
          $research->changeStatus($data['id'],$projectId,4);
          // $research->deleteErrorTemp();
          $this->_redirect('/research/error-report/pid/'.$projectId);
        }

      }catch(Zend_Exception $e){
        $this->view->save = 'error';
        echo $e->getMessage();
      }
    }

    public function acceptNewErrorAction()
    {
        $research = new Application_Model_Research();
        $project = new Application_Model_Project();
        
        $projectId = $this->getRequest()->getParam('pid');
        $this->view->pid = $projectId;
        $this->view->projectName = $project->nameProject($projectId);
        $data = $this->getRequest()->getPost();
        $list = $research->getDataErrorTempByCardCod($projectId, $data['card_cod']);
        if($list){
          foreach ($list as $value) {
            $research->changeStatus($value->id,$projectId,4);
          } 
          // $research->deleteErrorTemp();
          $this->_redirect('/research/error-report/pid/'.$projectId);
        }
    }

    public function denyNewErrorAction()
    {
        $research = new Application_Model_Research();
        $project = new Application_Model_Project();
        
        $projectId = $this->getRequest()->getParam('pid');
        $this->view->pid = $projectId;
        $this->view->projectName = $project->nameProject($projectId);
        $data = $this->getRequest()->getPost();
        if($data){
          // $research->deleteErrorTemp();
          $this->_redirect('/research/error-report/pid/'.$projectId);
        }
    }


    public function managerAction()
    {
          $projectId = $this->getRequest()->getParam('pid');
          $this->view->pid = $projectId;

          $project = new Application_Model_Project();
          $this->view->projectName = $project->nameProject($projectId); 
      
    }

     public function buslinesAction()
    {
          $projectId = $this->getRequest()->getParam('pid');
          $this->view->pid = $projectId;

          $project = new Application_Model_Project();
          $this->view->projectName = $project->nameProject($projectId); 
      
    }



}

























































