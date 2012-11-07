<?php

class UserController extends Zend_Controller_Action
{
private $userService;
    public function init()
    {
        $this->userService = new Thunderball_Service_User();
    }

    public function indexAction()
    {
        
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

		// user speichern
		$this->userService->store($user);
	}
}

