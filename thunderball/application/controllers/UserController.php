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

class UserController extends Zend_Controller_Action
{
	private $userService;
	private $roleService;

	public function init()
	{
		$this->userService = new Thunderball_Service_User();
		$this->roleService = new Thunderball_Service_Role();
	}

	public function indexAction()
	{

	}

	public function showAction()
	{
		if (!$this->_hasParam('id')) {
			return $this->redirect('user/index');
		}

		$user = $this->userService->getById($this->_getParam('id'));
		
		$this->view->user = $user;
		$this->view->headline = $this->userService->getCompleteName($user);
	}
	
	public function newAction()
	{
		$form = new Thunderball_Form_User();

		if ($this->getRequest()->isPost())
		{
			if (!$form->isValid($_POST)) {
				// Fehlgeschlagene Prüfung; Form wieder anzeigen
				$this->view->form = $form;
				return $this->render('new');
			}

			$values = $this->getRequest()->getParams();
			$newUser = new Thunderball_Model_User();
			$newPassword = $this->userService->generatePassword();
			$newUser->password = $this->userService->getPasswordHash($newPassword, $values['email']);
				
			$user = $this->storeUser($newUser, $values);
				
			$mailer = new Thunderball_Mailer_Mail(Thunderball_Mailer_Mail::TYPE_ERROR);
			$mailer->addTo($user->email);
			$mailer->addPlaceholder('password', $newPassword);
			$mailer->send();
			
			return $this->redirect('user/index');
		}

		$this->view->form = $form;
	}

	public function editAction()
	{
		if (!$this->_hasParam('id')) {
			return $this->redirect('user/index');
		}

		$form = new Thunderball_Form_User();

		$user = $this->userService->getById($this->_getParam('id'));

		if ($this->getRequest()->isPost())
		{
			if (!$form->isValid($_POST)) {
				// Fehlgeschlagene Prüfung; Form wieder anzeigen
				$this->view->form = $form;
				return $this->render('edit');
			}

			$values = $this->getRequest()->getParams();
			$this->storeUser($user, $values);
		}

		$form->populate(array(
		'firstname' => $user->firstname,
		'lastname' => $user->lastname,
		'email' => $user->email,
		'notice' => $user->notice,
		'hourly_rate' => $user->hourly_rate,
		'hours_of_work_per_day' => $user->hours_of_work_per_day,
		'salutation' => $user->salutation,
		'role' => $user->role->id,
		'title' => $user->title,
		));

		$this->view->form = $form;
		$this->view->userId = $user->id;
	}

	private function storeUser($user, $values)
	{
		$user->firstname = $values['firstname'];
		$user->lastname = $values['lastname'];
		$user->email = $values['email'];
		$user->notice = $values['notice'];
		$user->hourly_rate = $values['hourly_rate'];
		$user->hours_of_work_per_day = $values['hours_of_work_per_day'];
		$user->salutation = $values['salutation'];
		$user->role = $this->roleService->getById($values['role']);
		$user->title = $values['title'];

		// user speichern
		$this->userService->store($user);
		
		return $user;
	}
}

