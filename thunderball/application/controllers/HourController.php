<?php

class HourController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
    }

	public function newAction()
	{
		$form = new Thunderball_Form_Hour();
			
		if ($this->getRequest()->isPost())
		{
			/*
			if (!$form->isValid($_POST)) {
				// Fehlgeschlagene Prüfung; Form wieder anzeigen
				$this->view->form = $form;
				return $this->render('new');
			}

			$values = $this->getRequest()->getParams();
			$project = $this->storeProject(new Thunderball_Model_Project(), $values);
			return $this->redirect('project/detail/id/' . $project->id);
			#*/
		}

		$this->view->form = $form;
	}
	
	public function reportAction()
	{
		
	}
}

