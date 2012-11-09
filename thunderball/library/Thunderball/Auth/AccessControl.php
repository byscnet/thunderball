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

class Thunderball_Auth_AccessControl extends Zend_Controller_Plugin_Abstract
{
	public function __construct(Zend_Auth $auth, Zend_Acl $acl)
	{
		$this->_auth = $auth;
		$this->_acl  = $acl;
	}

	public function	routeStartup(Zend_Controller_Request_Abstract $request)
	{

	}

	public function	preDispatch(Zend_Controller_Request_Abstract $request)
	{
		if ($this->_auth->hasIdentity() &&is_object($this->_auth->getIdentity())) {
			$identity = $this->_auth->getIdentity();
			$role = $identity->roleId;
		} else {
			$role = 'guest';
		}

		$module = $request->getControllerName();
		$action = $request->getActionName();

		if (!$this->_acl->has($module)) {
			$resource = null;
		}
		if (!$this->_acl->isAllowed($role, $module, $action))
		{
			if ($this->_auth->hasIdentity())
			{
				$request->setModuleName('default');
				$request->setControllerName('index');
				$request->setActionName('index');
			}
			else
			{
				$request->setModuleName('default');
				$request->setControllerName('index');
				$request->setActionName('index');
			}
		}
	}
}