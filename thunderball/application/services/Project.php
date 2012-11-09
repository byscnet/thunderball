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

class Thunderball_Service_Project extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_Project';

	public function search($params)
	{
		$aColumns = array('d.name', 'd.notice', );
		$searchInColumns = array('d.name', 'd.notice', );

		return $this->searchQuery($params, $aColumns, $searchInColumns)->getResult();
	}

	public function getTotalCount()
	{
		$count = $this->_em->createQuery('SELECT count(p.id) AS num FROM Thunderball_Model_Project p');
		return $count->getSingleScalarResult();
	}
	
	public function getHourInformations($project)
	{
		$packageService = new Thunderball_Service_WorkingPackage();
		$hourService = new Thunderball_Service_WorkingHour();
		
		$packageSum = $packageService->getSumByProjectId($project->id);
		$hourSum = $hourService->getSumByProjectId($project->id);
		
		$sumTotal = $packageSum; // später für plankorrektur
		
		$result = array();
		$result['Gebucht'] = number_format($hourSum, 1, '.', '.');
		$result['Geplant'] = number_format($packageSum, 1, '.', '.');
		$result['Plankorrektur'] = number_format($sumTotal, 1, '.', '.');
		$result['Summe'] = number_format($packageSum, 1, '.', '.');
		$result['Rest'] = number_format($packageSum - $hourSum, 1, '.', '.');
				
		return $result;
	}
}

