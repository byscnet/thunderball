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

class StatusAjaxController extends Zend_Controller_Action
{
	private $statusService;

	public function init()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$this->statusService = new Thunderball_Service_ProjectStatus();
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

		$item = $this->statusService->getById($this->_getParam('id'));
		$item->name = $this->_getParam('item');
		$this->statusService->store($item);
		die('1');
	}
	
	public function addAction()
	{
		if (!$this->_hasParam('item')) {
			die ('0');
		}
		
		$item = new Thunderball_Model_ProjectStatus();
		$item->name = $this->_getParam('item');
		$this->statusService->store($item);
		die ('1');
	}

	public function deleteAction()
	{
		if (!$this->_hasParam('id')) {
			die('0');
		}

		try {
			$this->statusService->delete($this->_getParam('id'));
			die('1');
		} catch (Exception $ex) {
			die ('0');
		}
	}

	public function listAction()
	{
		$results = $this->statusService->search($this->_getAllParams());

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
		$iTotalRecords = $this->statusService->getTotalCount();
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

