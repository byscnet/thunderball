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

class AdministrationController extends Zend_Controller_Action
{
	private $userService;

    public function init()
    {
        $this->userService = new Thunderball_Service_User();
    }

    public function indexAction()
    {
        
    }

    public function accountAction()
    {
    	$form = new Thunderball_Form_Account();
    	$user = $this->view->accountHelper()->getUser();
    	$entity = $this->userService->getById($user->id);
			
		if ($this->getRequest()->isPost())
		{
			if (!$form->isValid($_POST)) {
				$this->view->form = $form;
				return $this->render('account');
			}

			$values = $this->getRequest()->getParams();
			
			$entity = $this->userService->getById($user->id);
			$entity->hourly_rate = $this->_getParam('hourly_rate');
			$entity->hours_of_work_per_day = $this->_getParam('hours_of_work_per_day');
			$this->userService->store($entity);
		}

		$form->populate(array(
		'hourly_rate' => $entity->hourly_rate,
		'hours_of_work_per_day' => $entity->hours_of_work_per_day,
		));
		
		$this->view->form = $form;
    }
}

