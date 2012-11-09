<?php

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

