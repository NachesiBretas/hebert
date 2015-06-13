<?php

class Application_Model_Project
{

	/**
	*	List all registered projects.
	*	
	* @access public
	* @return array
	*/
	public function listProjects(){
		$project = new Application_Model_DbTable_Project();

		$select = $project->select()->setIntegrityCheck(false);
		$select	->from(array('p' => 'project'),array('id','name'));
		return $project->fetchAll($select);
	}

	/**
	*	List all registered projects.
	*	
	* @access public
	* @return array
	*/
	public function nameProject($projectId){
		$project = new Application_Model_DbTable_Project();

		$select = $project->select()->setIntegrityCheck(false);
		$select	->from(array('p' => 'project'),array('name'))
				->where('p.id = ?',$projectId);
		return $project->fetchRow($select);
	}

}