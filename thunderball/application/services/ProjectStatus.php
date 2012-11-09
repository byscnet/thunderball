<?php
class Thunderball_Service_ProjectStatus extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_ProjectStatus';

	public function search($params)
	{
		$aColumns = array('d.name', );
		$searchInColumns = array('d.name', );

		return $this->searchQuery($params, $aColumns, $searchInColumns)->getResult();
	}

	public function getTotalCount()
	{
		$count = $this->_em->createQuery('SELECT count(s.id) AS num FROM Thunderball_Model_ProjectStatus s');
		return $count->getSingleScalarResult();
	}
}

