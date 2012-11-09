<?php
/*
 *   Copyright 2012 by byscnet.de - OpenSource Solutions
 * 
 * 	 This file is part of Thunderball Project Payment.
 *
 *   Thunderball Project Payment is free software: you can redistribute 
 *   it and/or modify it under the terms of the GNU General Public License 
 *   as published by the Free Software Foundation, either version 3 of the 
 *   License, or (at your option) any later version.

 *   Thunderball Project Payment is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Thunderball Project Payment.  If not, see <http://www.gnu.org/licenses/>.
 *
 *   Diese Datei ist Teil von Thunderball Project Payment.

 *   Thunderball Project Payment ist Freie Software: Sie können es unter den Bedingungen
 *   der GNU General Public License, wie von der Free Software Foundation,
 *   Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren
 *   veröffentlichten Version, weiterverbreiten und/oder modifizieren.

 *   Thunderball Project Payment wird in der Hoffnung, dass es nützlich sein wird, aber
 *   OHNE JEDE GEWÄHELEISTUNG, bereitgestellt; sogar ohne die implizite
 *   Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
 *   Siehe die GNU General Public License für weitere Details.

 *   Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
 *   Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
 */

class Thunderball_Auth_Acl extends Zend_Acl
{
	const ROLE_ADMIN = 'r1';
	const ROLE_PROJEKTLEITER = 'r2';
	const ROLE_MITARBEITER = 'r3';
	
	public function __construct()
	{
		$this->addRole(new Zend_Acl_Role('guest'))
		->addRole(new Zend_Acl_Role(self::ROLE_MITARBEITER), 'guest')
		->addRole(new Zend_Acl_Role(self::ROLE_PROJEKTLEITER), self::ROLE_MITARBEITER)
		->addRole(new Zend_Acl_Role(self::ROLE_ADMIN), self::ROLE_PROJEKTLEITER);

		$this->addResource(new Zend_Acl_Resource('index'));
		$this->addResource(new Zend_Acl_Resource('error'));
		$this->addResource(new Zend_Acl_Resource('startpage'));
		$this->addResource(new Zend_Acl_Resource('help'));
		
		$this->addResource(new Zend_Acl_Resource('project'));
		$this->addResource(new Zend_Acl_Resource('project-list'));
		$this->addResource(new Zend_Acl_Resource('project-detail'));
		$this->addResource(new Zend_Acl_Resource('project-cost'));
		$this->addResource(new Zend_Acl_Resource('project-new'));
		$this->addResource(new Zend_Acl_Resource('project-edit'));
		
		$this->addResource(new Zend_Acl_Resource('hour'));
		$this->addResource(new Zend_Acl_Resource('hour-new'));
		$this->addResource(new Zend_Acl_Resource('hour-report'));
		
		$this->addResource(new Zend_Acl_Resource('administration'));
		$this->addResource(new Zend_Acl_Resource('admin-account'));
		$this->addResource(new Zend_Acl_Resource('admin-user'));
		
		$this->addResource(new Zend_Acl_Resource('account'));
		$this->addResource(new Zend_Acl_Resource('account-login'));
		$this->addResource(new Zend_Acl_Resource('account-logout'));
		$this->addResource(new Zend_Acl_Resource('account-logout-complete'));
		$this->addResource(new Zend_Acl_Resource('logout-complete'));
			
		$this->addResource(new Zend_Acl_Resource('user'));
		$this->addResource(new Zend_Acl_Resource('role'));
		$this->addResource(new Zend_Acl_Resource('customer'));
		$this->addResource(new Zend_Acl_Resource('status'));
		$this->addResource(new Zend_Acl_Resource('category'));
		
		$this->addResource(new Zend_Acl_Resource('project-ajax'));
		$this->addResource(new Zend_Acl_Resource('hour-ajax'));
		$this->addResource(new Zend_Acl_Resource('administration-ajax'));
		$this->addResource(new Zend_Acl_Resource('user-ajax'));
		$this->addResource(new Zend_Acl_Resource('customer-ajax'));
		$this->addResource(new Zend_Acl_Resource('role-ajax'));
		$this->addResource(new Zend_Acl_Resource('status-ajax'));
		$this->addResource(new Zend_Acl_Resource('category-ajax'));
		
		$this->deny(null, null);

		//Guest
		$this->allow('guest', 'startpage');
		$this->allow('guest', 'error');
		$this->allow('guest', 'index', array('index'));
		$this->allow('guest', 'account', array('login', 'logout-complete'));
		$this->allow('guest', 'logout-complete');

		//Mitarbeiter
		$this->allow(self::ROLE_MITARBEITER, 'account', array('logout'));
		$this->allow(self::ROLE_MITARBEITER, 'project');
		$this->allow(self::ROLE_MITARBEITER, 'project-ajax');
		$this->allow(self::ROLE_MITARBEITER, 'project-list');
		$this->allow(self::ROLE_MITARBEITER, 'project-detail');
		$this->allow(self::ROLE_MITARBEITER, 'hour');
		$this->allow(self::ROLE_MITARBEITER, 'hour-ajax');
		$this->allow(self::ROLE_MITARBEITER, 'hour-new');
		$this->allow(self::ROLE_MITARBEITER, 'hour-report');
		$this->allow(self::ROLE_MITARBEITER, 'administration');
		$this->allow(self::ROLE_MITARBEITER, 'admin-account');
		$this->allow(self::ROLE_MITARBEITER, 'help');
		$this->allow(self::ROLE_MITARBEITER, 'index', array('help'));
		
		//Projektleiter
		$this->allow(self::ROLE_PROJEKTLEITER, 'project-cost');
		$this->allow(self::ROLE_PROJEKTLEITER, 'project-new');
		$this->allow(self::ROLE_PROJEKTLEITER, 'project-edit');
		$this->allow(self::ROLE_PROJEKTLEITER, 'customer');
		$this->allow(self::ROLE_PROJEKTLEITER, 'customer-ajax');
		$this->allow(self::ROLE_PROJEKTLEITER, 'role');
		$this->allow(self::ROLE_PROJEKTLEITER, 'role-ajax');
		$this->allow(self::ROLE_PROJEKTLEITER, 'status');
		$this->allow(self::ROLE_PROJEKTLEITER, 'status-ajax');
		$this->allow(self::ROLE_PROJEKTLEITER, 'category');
		$this->allow(self::ROLE_PROJEKTLEITER, 'category-ajax');
		
		//Admin
		$this->allow(self::ROLE_ADMIN, 'administration-ajax');
		$this->allow(self::ROLE_ADMIN, 'admin-user');
		$this->allow(self::ROLE_ADMIN, 'user');
		$this->allow(self::ROLE_ADMIN, 'user-ajax');
		
		
	}
}

?>