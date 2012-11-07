<?php

class AdministrationAjaxController extends Zend_Controller_Action
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

	public function userListAction()
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

