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

class Thunderball_Form_Hour extends Zend_Form
{
	private $decorator = array(
    	'ViewHelper',
    	'Description',
    	'Errors',
	array('HtmlTag', array('tag' => 'dd')),
	array('Label', array('tag' => 'dt', 'requiredSuffix' => ' * ')),
	);

	public function init()
	{
		$userId = Zend_Auth::getInstance()->getIdentity()->id;
		$projectService = new Thunderball_Service_ProjectMember();
		$all = $projectService->getByUserId($userId);
		$element = new Zend_Form_Element_Select('project');
		$element->setLabel('Projekt:');
		$element->setRequired(true);
		$element->setDecorators($this->decorator);
		$element->addMultiOption('', 'Bitte wählen');
		$element->setOptions(array('onchange' => 'projectOnChange(this.value)'));
		foreach ($all as $entity) {
			$element->addMultiOption($entity->id, $entity->name);
		}
		$this->addElement($element);
		
		
		$element = new Zend_Form_Element_Select('package');
		$element->setLabel('Arbeitspaket');
		$element->setRequired(true);
		$element->setDecorators($this->decorator);
		$element->setOptions(array('onchange' => 'packageOnChange(this.value)'));
		$this->addElement($element);
		
		$today = new DateTime();
		$this->addElement('text', 'hours', array(
							'label' => 'Stunden:',
				          	'required' => true,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'date', array(
							'label' => 'Datum:',
				          	'required' => true,
							'value' => $today->format('d.m.Y'),
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));
		
		$this->addElement('textarea', 'notice', array(
							'label' => 'Bemerkung:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'cols'    => '30',
							'rows'		=> '4',
							'decorators' => $this->decorator
		));
	}

	private function getSelectBox($name, $service, $label, $isRequired, $field)
	{
		$all = $service->fetchAll();
		$element = new Zend_Form_Element_Select($name);
		$element->setLabel($label);
		$element->setRequired($isRequired);
		$element->setDecorators($this->decorator);
		$element->addMultiOption('', 'Bitte wählen');
		$element->setOptions(array('onchange' => $name . 'OnChange(this.value)'));
		foreach ($all as $entity) {
			$element->addMultiOption($entity->id, $entity->$field);
		}
		return $element;
	}
}