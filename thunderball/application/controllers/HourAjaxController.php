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

class HourAjaxController extends Zend_Controller_Action
{
	private $packageService;
	private $hourService;
	private $projectService;
	private $userService;

	public function init()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$this->packageService = new Thunderball_Service_WorkingPackage();
		$this->hourService = new Thunderball_Service_WorkingHour();
		$this->projectService = new Thunderball_Service_Project();
		$this->userService = new Thunderball_Service_User();
	}

	public function indexAction()
	{
		echo "Dieser Kontroller kann nicht direkt aufgerufen werden.";
	}

	public function manageHoursAction()
	{
		if (!$this->_hasParam('mode')) {
			die ('0');
		}

		$userId = $this->view->accountHelper()->getUserId();

		switch($this->_getParam('mode')) {
			case 'add':
				if (!$this->_hasParam('projectId')
				|| !$this->_hasParam('packageId')
				|| !$this->_hasParam('hours')
				|| !$this->_hasParam('date')) {
					die ('0');
				}

				// TODO darf der benutzer überhaupt darauf buchen?

				$user = $this->userService->getById($userId);

				// sind noch genügend stunden übrig?
				$hours = $this->_getParam('hours');
				$package = $this->packageService->getById($this->_getParam('packageId'));
				$info = $this->packageService->getHourInformations($package);
				if (($info['Geplant'] - $info['Gebucht']) < $hours) {
					die('2'); // nicht genügend stunden über
				}

				$hour = new Thunderball_Model_WorkingHour();
				$hour->notice = $this->_getParam('notice');
				$hour->date = new DateTime($this->_getParam('date'));
				$hour->working_package = $package;
				$hour->project = $this->projectService->getById($this->_getParam('projectId'));
				$hour->hours = $hours;
				$hour->user = $user;

				$this->hourService->store($hour);
				die('1'); // erfolg
				break;

			case 'remove':
				if ($this->_hasParam('hoursId')) {
					$this->hourService->remove($userId, $this->_getParam('hoursId'));
					die('1');
				}
				break;

		}

		die ('0'); // fehler
	}

	public function listPackageAction()
	{
		if (!$this->_hasParam('projectId')) {
			die ('0');
		}

		$result = array();

		$packageList = $this->packageService->getByProjectId($this->_getParam('projectId'));
		foreach ($packageList as $package) {
			$result[$package->id] = $package->name;
		}

		die(json_encode($result));
	}

	public function hourInfoAction()
	{
		if (!$this->_hasParam('projectId')
		|| !$this->_hasParam('packageId')) {
			die ('0');
		}

		$package = $this->packageService->getById($this->_getParam('packageId'));
		$info = $this->packageService->getHourInformations($package);
		$info['Rest'] = number_format(($info['Geplant'] - $info['Gebucht']), 1, '.', '.');

		die(json_encode($this->view->hourHelper()->getInfoBlock($info)));
	}

	public function listAction()
	{
		$results = $this->hourService->search($this->_getAllParams());

		// output
		$rows = array();
		$userId = $this->view->accountHelper()->getUserId();

		foreach ($results as $aRow)
		{
			if ($aRow->user->id != $userId) {
				continue;
			}

			if ($this->_hasParam('projectId')) {
				if ($this->_getParam('projectId') != $aRow->project->id) {
					continue;
				}
			}

			if ($this->_hasParam('packageId')) {
				if ($this->_getParam('packageId') != $aRow->working_package->id) {
					continue;
				}
			}

			$options = array();
			//$options[] = $this->view->layoutHelper()->getEditButton($aRow->id);
			$options[] = $this->view->layoutHelper()->getDeleteButton($aRow->id);
			$row = array();
			$row[] = $aRow->date->format('d.m.Y');
			$row[] = number_format($aRow->hours, 1, '.', '.');
			$row[] = $aRow->project->name;
			$row[] = $aRow->working_package->name;
			$row[] = $aRow->notice;
			$row[] = join('', $options);
			$rows[] = $row;
		}

		// paging fix (if is using search)
		$iTotalRecords = $this->hourService->getTotalCount();
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
}

