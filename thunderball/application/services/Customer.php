<?php
class Thunderball_Service_Customer extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_Customer';

	public function search($params)
	{
		$aColumns = array('d.name', 'd.description', );
		$searchInColumns = array('d.name', 'd.description', );

		return $this->searchQuery($params, $aColumns, $searchInColumns)->getResult();
	}

	public function getTotalCount()
	{
		$count = $this->_em->createQuery('SELECT count(u.id) AS num FROM Thunderball_Model_Customer u');
		return $count->getSingleScalarResult();
	}
}

