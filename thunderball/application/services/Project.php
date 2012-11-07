<?php
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
		$count = $this->_em->createQuery('SELECT count(u.id) AS num FROM Thunderball_Model_Project u');
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

