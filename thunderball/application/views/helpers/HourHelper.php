<?php

class Zend_View_Helper_HourHelper extends Zend_View_Helper_Abstract
{
	public function hourHelper()
	{
		return $this;
	}

	public function getInfoBlock($info)
	{
		$html = array();
		$html[] = '<table>';
		$html[] = '<tr>';
		$html[] = '<td><b>geplante Stunden:</b></td>';
		$html[] = '<td>' . $info['Geplant'] . '</td>';
		$html[] = '</tr><tr>';
		$html[] = '<td><b>gebuchte Stunden:</b></td>';
		$html[] = '<td>' . $info['Gebucht'] . '</td>';
		$html[] = '</tr>';
		$html[] = '<tr>';
		$html[] = '<td><b>Stunden buchbar:</b></td>';
		$html[] = '<td>' . $info['Rest'] . '</td>';
		$html[] = '</tr></table>';
		return join('', $html);
	}
}



		
			
			
		
		
			
			
		
		
			
			
		
	