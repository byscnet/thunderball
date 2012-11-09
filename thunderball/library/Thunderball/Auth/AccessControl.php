<?php

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