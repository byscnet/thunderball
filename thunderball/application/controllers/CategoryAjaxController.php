<?php

class CategoryAjaxController extends Zend_Controller_Action
{
	private $categoryService;

	public function init()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$this->categoryService = new Thunderball_Service_ProjectCategory();
	}

	public function indexAction()
	{
		echo "Dieser Kontroller kann nicht direkt aufgerufen werden.";
	}

	public function saveAction()
	{
		if (!$this->_hasParam('id')) {
			die('0');
		}

		$item = $this->categoryService->getById($this->_getParam('id'));
		$item->name = $this->_getParam('item');
		$this->categoryService->store($item);
		die('1');
	}
	
	public function addAction()
	{
		if (!$this->_hasParam('item')) {
			die ('0');
		}
		
		$item = new Thunderball_Model_ProjectCategory();
		$item->name = $this->_getParam('item');
		$this->categoryService->store($item);
		die ('1');
	}

	public function deleteAction()
	{
		if (!$this->_hasParam('id')) {
			die('0');
		}

		try {
			$this->categoryService->delete($this->_getParam('id'));
			die('1');
		} catch (Exception $ex) {
			die ('0');
		}
	}

	public function listAction()
	{
		$results = $this->categoryService->search($this->_getAllParams());

		// output
		$rows = array();

		foreach ($results as $aRow)
		{
			$options = array();
			$options[] = $this->view->layoutHelper()->getEditButton($aRow->id);
			$options[] = $this->view->layoutHelper()->getDeleteButton($aRow->id);
			$options[] = $this->view->layoutHelper()->getSaveButton($aRow->id);
			$options[] = $this->view->layoutHelper()->getCancelButton($aRow->id);
			$row = array();
			$row[] = $this->getEditableTextbox($aRow->name, $aRow->id);
			$row[] = join('', $options);
			$rows[] = $row;
		}

		// paging fix (if is using search)
		$iTotalRecords = $this->categoryService->getTotalCount();
		$iTotalDisplayRecords = $iTotalRecords;
		if ($this->_getParam('sSearch') != '') {
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

	private function getEditableTextbox($item, $id)
	{
		$html = '<span name="item_view" rel-data="' . $id . '" rel-type="item">' . $item . '</span>';
		$html .= '<input type="text" name="item_edit" rel-data="' . $id . '" rel-type="item" value="' . $item . '" style="display: none" size="50">';
		return $html;
	}
}

