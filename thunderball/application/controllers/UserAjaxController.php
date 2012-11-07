<?php

class UserAjaxController extends Zend_Controller_Action
{
	private $userService;

	public function init()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$this->userService = new Thunderball_Service_User();
	}

	public function indexAction()
	{
		echo "Dieser Kontroller kann nicht direkt aufgerufen werden.";
	}

	public function resetAction()
	{
		if (!$this->_hasParam('id')) {
			die('0');
		}

		$user = $this->userService->getById($this->_getParam('id'));

		$newPassword = $this->userService->generatePassword();
		$user->password = $this->userService->getPasswordHash($newPassword, $user->email);
		$this->userService->store($user);

		$mailer = new Thunderball_Mailer_Mail(Thunderball_Mailer_Mail::TYPE_ERROR);
		$mailer->addTo($user->email);
		$mailer->addPlaceholder('password', $newPassword);
		$mailer->send();
		
		die(json_encode($user->email));
	}

	public function listAction()
	{
		$results = $this->userService->search($this->_getAllParams());

		// output
		$rows = array();

		foreach ($results as $aRow)
		{
			$options = array();
			$options[] = $this->view->layoutHelper()->getEditButton($aRow->id);
			$options[] = $this->view->layoutHelper()->getDeleteButton($aRow->id);
			$options[] = $this->view->layoutHelper()->getDetailButton($aRow->id);
			$row = array();
			$row[] = $this->userService->getCompleteName($aRow);
			$row[] = $aRow->email;
			$row[] = $aRow->hourly_rate;
			$row[] = $aRow->hours_of_work_per_day;
			$row[] = join('', $options);
			$rows[] = $row;
		}

		// paging fix (if is using search)
		$iTotalRecords = $this->userService->getTotalCount();
		$iTotalDisplayRecords = $iTotalRecords;
		if ($this->_getParam('sSearch') != '')
		{
			$iTotalDisplayRecords = count($rows);
		}

		$output = array(
			"sEcho" => intval($this->_getParam('sEcho')),
			"iTotalRecords" => $iTotalRecords,
			"iTotalDisplayRecords" => $iTotalDisplayRecords,
			"aaData" => $rows
		);

		echo json_encode($output);
	}
}

