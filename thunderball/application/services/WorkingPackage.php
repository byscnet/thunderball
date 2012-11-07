<?php
class Thunderball_Service_WorkingPackage extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_WorkingPackage';

	public function getByProjectId($projectId)
	{
		$dql = "SELECT w FROM Thunderball_Model_WorkingPackage w WHERE w.project = :projectId";
		$packageList = $this->_em->createQuery($dql)
		->setParameter('projectId', $projectId)
		->getResult();

		return $packageList;
	}

	public function getSumByProjectId($projectId)
	{
		$dql = "SELECT SUM(w.hours) FROM Thunderball_Model_WorkingPackage w WHERE w.project = :projectId";
		$sum = $this->_em->createQuery($dql)
		->setParameter('projectId', $projectId)
		->getSingleScalarResult();

		return $sum;
	}

	public function getHourInformations($package)
	{
		$hourService = new Thunderball_Service_WorkingHour();
		$sum = $hourService->getSumByPackateId($package->id);

		$result = array();
		$result['Gebucht'] = number_format($sum, 1, '.', '.');
		$result['Geplant'] = number_format($package->hours, 1, '.', '.');
		return $result;
	}
}

