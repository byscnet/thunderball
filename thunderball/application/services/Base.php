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

class Thunderball_Service_Base
{
	public function __construct()
	{
		$registry = Zend_Registry::getInstance();
		$this->_em = $registry->entityManager;
	}

	public function searchQuery($params, $aColumns, $searchInColumns)
	{
		$qb = $this->fetchAllQuery();

		if (array_key_exists('sSearch', $params) && $params['sSearch'] != '')
		{
			$search = array();
			foreach ($searchInColumns as $column) {
				$search[] = $column . " LIKE '%" . $params['sSearch'] . "%'";
			}
				
			$qb->where(join($search, ' OR '));
		}

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
			
		return $qb->getQuery();
	}

	public function getById($id)
	{
		return $this->_em->find($this->domain, (int)$id);
	}

	public function fetchAll()
	{
		return $this->fetchAllQuery()->getQuery()->getResult();
	}

	public function fetchAllQuery()
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('d')
		->from($this->domain, 'd');

		return $qb;
	}

	public function delete($id)
	{
		$dql = 'DELETE \\' . $this->domain . ' e WHERE e.id = :itemId';
		$query = $this->_em->createQuery($dql);
		$query->setParameter('itemId', $id);

		return $query->getResult();
	}

	public function store($entity)
	{
		$this->_em->getConnection()->beginTransaction(); // suspend auto-commit
		try
		{
			$this->_em->persist($entity);
			$this->_em->flush();
			$this->_em->getConnection()->commit();
		}
		catch (Exception $e)
		{
			$this->_em->getConnection()->rollback();
			$this->_em->close();
			throw $e;
		}
	}
}
