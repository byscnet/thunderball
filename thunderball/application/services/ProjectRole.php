<?php
class Thunderball_Service_ProjectRole extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_ProjectRole';

	public function search($params)
	{
		$aColumns = array('d.name', );
		$searchInColumns = array('d.name', );

		return $this->searchQuery($params, $aColumns, $searchInColumns)->getResult();
	}

	public function getTotalCount()
	{
		$count = $this->_em->createQuery('SELECT count(r.id) AS num FROM Thunderball_Model_ProjectRole r');
		return $count->getSingleScalarResult();
	}
}

