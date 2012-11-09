<?php
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

