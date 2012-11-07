<?php

class Zend_View_Helper_AccountHelper extends Zend_View_Helper_Abstract
{
	public function accountHelper()
	{
		return $this;
	}

	public function userIsLoggedIn()
	{
		return Zend_Auth::getInstance()->hasIdentity();
	}

	public function getUser()
	{
		return Zend_Auth::getInstance()->getIdentity();
	}

	public function getUserId()
	{
		return 1;
	}
	
	public function getUserFullName()
	{
		$userService = new Wendy_Service_UserService();
		
		$user = $this->getUser();
/*
		if($user->role == 'Bereichsleiter'){
			$completeUserObject = $userService->getById($user->id);

			return $user->firstname . ' ' . $user->lastname . ' (' . $user->role . ': ' . $completeUserObject->business_segment->name . ') ';
		}

		else{
			return $user->firstname . ' ' . $user->lastname . ' (' . $user->role . ') ';
		}
		*/
	}

}