<?php

class Application_Model_User
{

	public function newUser($data)
	{
		if($data['password'] == $data['confirm_password'] && strlen($data['password']) > 4 && strlen($data['username']) > 4 )
		{
			$user = new Application_Model_DbTable_User();
			$userRow = $user->createRow();
			$userRow->name = $data['name'];
			$userRow->username = $data['username'];
			$userRow->password = sha1($data['password']);
			$userRow->date = new Zend_Db_Expr('NOW()');
			$userRow->institution = $data['institution'];
			return $userRow->save();
		}
    return false;
	}

	public function lists()
	{
		$user = new Application_Model_DbTable_User();
		return $user->fetchAll();
	}

	public function editUser($data,$userId)
	{
		$user = new Application_Model_DbTable_User();
		$userRow = $user->fetchRow($user->select()->where('id = ?',$userId));
		if($userRow)
		{
			$userRow->name = $data['name'];
			$userRow->username = $data['username'];
			$userRow->institution = $data['institution'];
			if($data['cpf']) $userRow->cpf = $data['cpf'];
			return $userRow->save();
		}
		return false;
	}

	public function returnById($userId)
	{
		$user = new Application_Model_DbTable_User();
		$select = $user->select()->setIntegrityCheck(false);
		$select	->from(array('u' => 'user'));
		return $user->fetchRow($user->select()->where('id = ?',$userId));
	}

	public function findByName($name)
	{
		$user = new Application_Model_DbTable_User();
		return $user->fetchAll($user->select()->where('name LIKE ?', '%'.$name.'%'));
	}

	public function findAjaxByName($query)
	{

		$user = new Application_Model_DbTable_User();
		$userRow = $user->fetchAll($user->select()->where('name LIKE ?', '%'.$query.'%'));
		$aux=array();
		foreach($userRow as $flag)
		{
			$aux1=array('id'=>$flag->id, 'label'=>$flag->name);
			array_push($aux,$aux1);
		}

		return $aux;
	}
}

