<?php

class AccountController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{

	}

	public function loginAction()
	{
		if ($this->getRequest()->isPost())
		{
			if (!$this->_getParam('username') || !$this->_getParam('password')) {
				$this->view->message = Thunderball_Auth_Adapter::ACCESS_DENIED;
			}
			else {
				$this->startSession($this->_getParam('username'), $this->_getParam('password'), $this->_getParam('rememberMe'));
			}
		}

		if (!$this->view->accountHelper()->userIsLoggedIn()) {
			//$this->_forward('/');
		}
	}

	public function logoutAction()
	{
		// benutzer session löschen
		//$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('account/logout-complete');
	}
	
	public function logoutCompleteAction()
	{
		// der benutzer ist ausgeloggt
		//$this->_helper->viewRenderer->setNoRender();
		return $this->render('logout');
	}

	private function startSession($username, $password, $rememberMe)
	{
		$adapter = new Thunderball_Auth_Adapter($username, $password);
		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($adapter);

		if (Zend_Auth::getInstance()->hasIdentity())
		{
			$user = $this->view->accountHelper()->getUser();
			$userService = new Thunderball_Service_User();
			$user = $userService->getById($user->id);

			// remember me
			if ($rememberMe == 'on') {
				$auth->getStorage()->write($user);
				Zend_Session::rememberMe();
			}

			$this->_redirect('/');
		}
		else {
			$this->view->message = implode(' ', $result->getMessages());
		}
	}
}

