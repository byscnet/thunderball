<?php
class Thunderball_Service_ProjectCategory extends Thunderball_Service_Base
{
	protected $domain = 'Thunderball_Model_ProjectCategory';

	public function search($params)
	{
		$aColumns = array('d.name', );
		$searchInColumns = array('d.name', );

		return $this->searchQuery($params, $aColumns, $searchInColumns)->getResult();
	}

	public function getTotalCount()
	{
		$count = $this->_em->createQuery('SELECT count(c.id) AS num FROM Thunderball_Model_ProjectCategory c');
		return $count->getSingleScalarResult();
	}
}

