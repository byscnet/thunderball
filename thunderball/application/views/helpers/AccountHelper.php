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
		$user = $this->getUser();
		return $user->id;
	}

	public function isMitarbeiter()
	{
		return $this->isRole(Thunderball_Auth_Acl::ROLE_MITARBEITER);
	}

	public function isProjektleiter()
	{
		return $this->isRole(Thunderball_Auth_Acl::ROLE_PROJEKTLEITER);
	}

	public function isAdmin()
	{
		return $this->isRole(Thunderball_Auth_Acl::ROLE_ADMIN);
	}

	public function isRole($role)
	{
		$user = $this->getUser();
		return ($user->roleId == $role);
	}

	public function getSalutation()
	{
		$user = $this->getUser();
		$html = array();
		$html[] = 'Hallo';
		$html[] = $user->firstname;
		$html[] = $user->lastname;
		$html[] = '(' . $user->role . ')';
		return join(' ', $html);
	}

}