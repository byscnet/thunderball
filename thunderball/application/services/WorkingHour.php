<?php
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

