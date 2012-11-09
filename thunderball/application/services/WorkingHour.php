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

class Thunderball_Service_WorkingHour extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_WorkingHour';

	public function getSumByPackateId($packageId)
	{
		$dql = "SELECT SUM(w.hours) FROM Thunderball_Model_WorkingHour w WHERE w.working_package = :packageId";
		$sum = $this->_em->createQuery($dql)
		->setParameter('packageId', $packageId)
		->getSingleScalarResult();

		return $sum;
	}

	public function getSumByProjectId($projectId)
	{
		$dql = "SELECT SUM(w.hours) FROM Thunderball_Model_WorkingHour w WHERE w.project = :projectId";
		$sum = $this->_em->createQuery($dql)
		->setParameter('projectId', $projectId)
		->getSingleScalarResult();

		return $sum;
	}

	public function search($params)
	{
		$aColumns = array('d.date', 'd.hours', 'd.project', 'd.working_package', 'd.notice', );
		$searchInColumns = array('d.notice', );

		return $this->searchQuery($params, $aColumns, $searchInColumns)->getResult();
	}

	public function getTotalCount()
	{
		$count = $this->_em->createQuery('SELECT count(u.id) AS num FROM Thunderball_Model_WorkingHour u');
		return $count->getSingleScalarResult();
	}
	
	public function remove($userId, $hoursId)
	{
		$dql = 'DELETE \Thunderball_Model_WorkingHour wh WHERE wh.id = :hoursId AND wh.user = :userId';
		$query = $this->_em->createQuery($dql);
		$query->setParameter('hoursId', $hoursId);
		$query->setParameter('userId', $userId);

		return $query->getResult();
	}
}

