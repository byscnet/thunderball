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

class Thunderball_Service_ProjectMember extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_ProjectMember';

	public function getByProjectId($projectId)
	{
		$dql = "SELECT m FROM Thunderball_Model_ProjectMember m WHERE m.project = :projectId";
		$memberList = $this->_em->createQuery($dql)
		->setParameter('projectId', $projectId)
		->getResult();

		return $memberList;
	}

	public function getByUserId($userId)
	{
		$dql = "SELECT m FROM Thunderball_Model_ProjectMember m JOIN m.project p WHERE m.user = :userId AND p.status != :statusId";
		$memberList = $this->_em->createQuery($dql)
		->setParameter('userId', $userId)
		->setParameter('statusId', 3) // status: abgeschlossen
		->getResult();

		$projectList = array();
		foreach ($memberList as $member) {
			$projectList[] = $member->project;
		}
		
		return $projectList;
	}

	public function search($params)
	{
		$aColumns = array('d.user', 'd.role', 'd.project', );

		$qb = $this->fetchAllQuery();
		$qb->where('d.project = ' . $params['projectId']);


		if ( isset( $params['iSortCol_0'] ) )
		{
			for ( $i=0 ; $i<intval( $params['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($params['iSortCol_'.$i]) ] == "true" )
				{
					$qb->add('orderBy', $aColumns[$params['iSortCol_'.$i]] . ' ' . $params['sSortDir_'.$i]);
				}
			}
		}

		if ( isset( $params['iDisplayStart'] ) && $params['iDisplayLength'] != '-1' )
		{
			$qb->setFirstResult( $params['iDisplayStart'] );
			$qb->setMaxResults( $params['iDisplayLength'] );
		}
			
		return $qb->getQuery()->getResult();
	}

	public function getTotalCount()
	{
		$count = $this->_em->createQuery('SELECT count(u.id) AS num FROM Thunderball_Model_ProjectMember u');
		return $count->getSingleScalarResult();
	}

	public function remove($memberId)
	{
		$dql = 'DELETE \Thunderball_Model_ProjectMember pm WHERE pm.id = :memberId';
		$query = $this->_em->createQuery($dql);
		$query->setParameter('memberId', $memberId);

		return $query->getResult();
	}
}

