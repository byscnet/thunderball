<?php

class Zend_View_Helper_ProjectHelper extends Zend_View_Helper_Abstract
{
	public function projectHelper()
	{
		return $this;
	}

	public function getProjectStructureHeader($project, $info)
	{
		$projectService = new Thunderball_Service_Project();
		$html = array();
		$html[] = '<span class="structure-header">' . $project->name . '</span><br />';
		$html[] = '<span class="structure-info">';
		$html[] = $this->buildHourInformations($info);
		$html[] = '</span>';
		$html[] = '<br />';
		$html[] = $this->drawGeplantBar(750, $info['Geplant'], '#0f0');
		$html[] = '<br />';
		$html[] = $this->drawGebuchtBar(750, $info['Geplant'], $info['Gebucht'], '#00f');
		$html[] = '<br />';
		return join('', $html);
	}

	public function getPackageInfoBlock($package, $info, $total)
	{
		$packageService = new Thunderball_Service_WorkingPackage();
		$html = array();
		$html[] = '<span class="structure-header">' . $package->name . '</span><br />';
		$html[] = '<span class="structure-info">';
		$html[] = $this->buildHourInformations($info);
		$html[] = '</span>';
		$html[] = '<br />';
		$html[] = $this->drawGeplantBar($total, $info['Geplant'], '#0f0');
		$html[] = '<br />';
		$html[] = $this->drawGebuchtBar($total, $info['Geplant'], $info['Gebucht'], '#00f');
		$html[] = '<br />';
		return join('', $html);
	}

	public function drawGeplantBar($total, $value, $color)
	{
		$html = array();
		$html[] = '<div>';
		$html[] = '<div style="float: left; width: 100px;" class="structure-info">Geplant</div>';
		$html[] = $this->drawBar($color, $total);
		$html[] = '</div>';
		return join('', $html);
	}

	public function drawGebuchtBar($total, $hoursTotal, $value, $color)
	{
		$widthPercent = 0;
		$width = 0;
		if ($hoursTotal > 0) {
			$widthPercent = $value * 100 / $hoursTotal;
			$width = $widthPercent * 750 / 100;
		}
		
		$html = array();
		$html[] = '<div>';
		$html[] = '<div style="float: left; width: 100px;" class="structure-info">Gebucht (' . number_format($widthPercent, 1, '.', '.') . '%)</div>';
		$html[] = $this->drawBar($color, $width);
		$html[] = '</div>';
		return join('', $html);

	}

	public function drawBar($color, $width)
	{
		return '<div style="float: left; margin-top: 3px; height: 5px; background-color: ' . $color . '; width: ' . $width . 'px"></div>';
	}

	public function buildHourInformations($hourInfo)
	{
		$html = array();
		foreach ($hourInfo as $info => $value) {
			$html[] = $info . ': ' . $value;
		}
		return join(' ', $html);
	}

	public function getUserDropBox($name)
	{
		$html = array();
		$html[] = '<select id="' . $name . '">';

		$userService = new Thunderball_Service_User();
		$allUsers = $userService->fetchAll();
		foreach ($allUsers as $user) {
			$html[] = '<option value="' . $user->id . '">' . $userService->getCompleteName($user). '</option>';
		}

		$html[] = '</select>';

		return join('', $html);
	}

	public function getRoleDropBox($name)
	{
		$html = array();
		$html[] = '<select id="' . $name . '">';

		$roleService = new Thunderball_Service_Role();
		$allRoles = $roleService->fetchAll();
		foreach ($allRoles as $role) {
			$html[] = '<option value="' . $role->id . '">' . $role->name . '</option>';
		}

		$html[] = '</select>';

		return join('', $html);
	}

}