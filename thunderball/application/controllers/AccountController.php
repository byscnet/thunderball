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

	public function lostPwdAction()
	{
		$form = new Thunderball_Form_Password();
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost())
		{
			if (!$this->_hasParam('email')
			|| !$form->isValid($_POST)) {
				$this->view->form = $form;
				return $this->render('lost-pwd');
			}	
			
			$email = $this->_getParam('email');
			$userService = new Thunderball_Service_User();
			$user = $userService->getByEmail($email);
			
			if ($user == null) {
				return $this->render('lost-pwd');
			}
			
			$newPassword = $userService->generatePassword();
			$user->password = $userService->getPasswordHash($newPassword, $email);
			$userService->store($user);
			
			$mailer = new Thunderball_Mailer_Mail(Thunderball_Mailer_Mail::TYPE_NEW_PASSWORD);
			$mailer->addTo($user->email);
			$mailer->addPlaceholder('password', $newPassword);
			$mailer->addPlaceholder('salutation', $userService->getSalutation($user));
			$mailer->send();
			
			
			$this->view->message = $this->view->layoutHelper()->getNotification('Neues Passwort', 'Ihnen wurde ein neues Passwort per E-Mail zugesendet. Bitte überprüfen Sie Ihr Postfach.');
			return $this->forward('index');
		}
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

