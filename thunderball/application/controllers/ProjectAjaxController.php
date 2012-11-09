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

class ProjectAjaxController extends Zend_Controller_Action
{
	private $projectService;
	private $memberService;
	private $userService;
	private $projectRoleService;
	private $packageService;

	public function init()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$this->projectService = new Thunderball_Service_Project();
		$this->memberService = new Thunderball_Service_ProjectMember();
		$this->userService = new Thunderball_Service_User();
		$this->projectRoleService = new Thunderball_Service_ProjectRole();
		$this->packageService = new Thunderball_Service_WorkingPackage();
	}

	public function indexAction()
	{
		echo "Dieser Kontroller kann nicht direkt aufgerufen werden.";
	}

	public function getStructureAction()
	{
		if (!$this->_hasParam('projectId')) {
			die ('ungültige Project ID');
		}

		$project = $this->projectService->getById($this->_getParam('projectId'));

		if ($project == null) {
			die ('ungültige Project ID');
		}

		$packageList = $this->packageService->getByProjectId($project->id);
		$info = $this->projectService->getHourInformations($project);
		
		$html = array();
		
		$html[] = $this->view->projectHelper()->getProjectStructureHeader($project, $info);
		$html[] = '<span class="structure-info-block">';
		foreach ($packageList as $package) {
			$pInfo = $this->packageService->getHourInformations($package);
			$html[] = '' . $this->view->projectHelper()->getPackageInfoBlock($package, $pInfo, 750) . '<br />';
		}
		$html[] = '</span>';
		die(json_encode(join('', $html)));
	}

	public function managePackageAction()
	{
		if (!$this->_hasParam('mode')
		|| !$this->_hasParam('projectId')) {
			die('0');
		}

		switch($this->_getParam('mode')) {
			case 'add':
				if (!$this->_hasParam('name')
				|| !$this->_hasParam('hours')) {
					die('0');
				}

				$package = new Thunderball_Model_WorkingPackage();
				$package->name = $this->_getParam('name');
				$package->hours = $this->_getParam('hours');
				$package->start_date = new DateTime();
				$package->end_date = new DateTime();
				$package->planed_end_date = new DateTime();
				$package->is_closed = false;
				$package->project = $this->projectService->getById($this->_getParam('projectId'));
				$this->packageService->store($package);
				break;
		}

		die('1');
	}

	public function manageMemberAction()
	{
		if (!$this->_hasParam('mode')
		|| !$this->_hasParam('projectId')) {
			die('0');
		}

		switch($this->_getParam('mode')) {
			case 'add':
				if (!$this->_hasParam('userId')
				|| !$this->_hasParam('roleId')) {
					die('0');
				}

				$member = new Thunderball_Model_ProjectMember();
				$member->since = new DateTime();
				$member->user = $this->userService->getById($this->_getParam('userId'));
				$member->project = $this->projectService->getById($this->_getParam('projectId'));
				$member->role = $this->projectRoleService->getById($this->_getParam('roleId'));
				$this->memberService->store($member);
				break;

			case 'remove':
				if (!$this->_hasParam('memberId')) {
					die('0');
				}

				$this->memberService->remove($this->_getParam('memberId'));
				break;
		}

		die('1');
	}

	public function membersAction()
	{
		if (!$this->_hasParam('projectId')) {
			die ('0');
		}

		$params = $this->_getAllParams();
		$params['projectId'] = $this->_getParam('projectId');

		$results = $this->memberService->search($params);

		// output
		$rows = array();

		foreach ($results as $aRow)
		{
			$options = array();
			if ($this->view->accountHelper()->isProjektleiter()
			|| $this->view->accountHelper()->isAdmin()) {
				$options[] = $this->view->layoutHelper()->getDeleteButton($aRow->id);
			}
			$row = array();
			$row[] = $aRow->role->name;
			$row[] = $this->userService->getCompleteName($aRow->user);
			$row[] = $aRow->user->hourly_rate;
			$row[] = $aRow->since->format('d.m.Y');
			$row[] = join('', $options);
			$rows[] = $row;
		}

		// paging fix (if is using search)
		$iTotalRecords = $this->memberService->getTotalCount();
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

	public function listAction()
	{
		$results = $this->projectService->search($this->_getAllParams());

		// output
		$rows = array();

		foreach ($results as $aRow)
		{
			$options = array();
			if ($this->view->accountHelper()->isProjektleiter()
			|| $this->view->accountHelper()->isAdmin()) {
				$options[] = $this->view->layoutHelper()->getEditButton($aRow->id);
			}
			$options[] = $this->view->layoutHelper()->getDetailButton($aRow->id);
			$row = array();
			$row[] = $this->getColourPreview($aRow->colour);
			$row[] = $aRow->name;
			$row[] = ($aRow->category != null) ? ($aRow->category->name) : ('');
			$row[] = $aRow->start_date->format('d.m.Y');
			$row[] = $aRow->customer->name;
			$row[] = $aRow->status->name;
			$row[] = join('', $options);
			$rows[] = $row;
		}

		// paging fix (if is using search)
		$iTotalRecords = $this->projectService->getTotalCount();
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

	private function getColourPreview($color)
	{
		$html = '<div style="width: 15px; height: 15px; background-color:#';
		if ($color != null && $color != '') {
			$html .= $color;
		} else {
			$html .= 'fff';
		}
		$html .= '"></div>';
		return $html;
	}
}

