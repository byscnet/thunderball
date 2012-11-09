<?php
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
