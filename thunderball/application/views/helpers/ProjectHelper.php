<?php
/*
 *   Copyright 2012 by byscnet.de - OpenSource Solutions
 * 
 * 	 This file is part of Thunderball Project Payment.
 *
 *   Thunderball Project Payment is free software: you can redistribute 
 *   it and/or modify it under the terms of the GNU General Public License 
 *   as published by the Free Software Foundation, either version 3 of the 
 *   License, or (at your option) any later version.

 *   Thunderball Project Payment is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Thunderball Project Payment.  If not, see <http://www.gnu.org/licenses/>.
 *
 *   Diese Datei ist Teil von Thunderball Project Payment.

 *   Thunderball Project Payment ist Freie Software: Sie können es unter den Bedingungen
 *   der GNU General Public License, wie von der Free Software Foundation,
 *   Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren
 *   veröffentlichten Version, weiterverbreiten und/oder modifizieren.

 *   Thunderball Project Payment wird in der Hoffnung, dass es nützlich sein wird, aber
 *   OHNE JEDE GEWÄHELEISTUNG, bereitgestellt; sogar ohne die implizite
 *   Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
 *   Siehe die GNU General Public License für weitere Details.

 *   Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
 *   Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
 */

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

		$roleService = new Thunderball_Service_ProjectRole();
		$allRoles = $roleService->fetchAll();
		foreach ($allRoles as $role) {
			$html[] = '<option value="' . $role->id . '">' . $role->name . '</option>';
		}

		$html[] = '</select>';

		return join('', $html);
	}

}