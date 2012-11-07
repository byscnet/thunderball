<?php

class Thunderball_Auth_Acl extends Zend_Acl
{
	public function __construct()
	{
		$this->addRole(new Zend_Acl_Role('Gast'), 'Gast');
		$this->addRole(new Zend_Acl_Role('Administrator'), 'Administrator');

		$this->addResource(new Zend_Acl_Resource('index'));
		$this->addResource(new Zend_Acl_Resource('error'));
		$this->addResource(new Zend_Acl_Resource('administration'));
		$this->addResource(new Zend_Acl_Resource('hour'));
		$this->addResource(new Zend_Acl_Resource('project'));
			
		$this->deny(null, null);

		//Guest
		$this->allow('guest', 'startpage');
		$this->allow('guest', 'index', array('index'));
		$this->allow('guest', 'error');
		$this->allow('guest', 'account', array('index', 'password-forgotten'));

		//Mitarbeiter
		$this->allow('Mitarbeiter', 'settings');
		$this->allow('Mitarbeiter', 'account', array('password', 'logout'));
		$this->allow('Mitarbeiter', 'job-profile');
		$this->allow('Mitarbeiter', 'employee-profile');
		$this->allow('Mitarbeiter', 'jobprofile');
		$this->allow('Mitarbeiter', 'employeeprofile');
		$this->deny('Mitarbeiter', 'account', array('password-forgotten'));
		$this->deny('Mitarbeiter', 'employee-profile', array('delete', 'index'));
		$this->deny('Mitarbeiter', 'job-profile', array('delete', 'index', 'print'));

		//Bereichsleiter
		$this->allow('Bereichsleiter', 'job-profile', array('print'));


		//Pfleger
		$this->allow('Pfleger', 'employee-profile', array('index'));
		$this->allow('Pfleger', 'job-profile', array('index'));


		//Admin
		$this->allow('Administrator', 'administration');
		$this->allow('Administrator', 'user');
		$this->allow('Administrator', 'employee-profile', array('delete'));
		$this->allow('Administrator', 'job-profile', array('delete'));
		$this->allow('Administrator', 'ressourcen');
		$this->allow('Administrator', 'ressource-profile');
		$this->allow('Administrator', 'log');
		$this->allow('Administrator', 'change-log');
		$this->allow('Administrator', 'notification');
		$this->allow('Administrator', 'notification-administration');
	}
}

?>