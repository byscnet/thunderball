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

class Thunderball_Form_Project extends Zend_Form
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
		/*
		 * Projektbezeichnung
		 */
		$this->addElement('text', 'name', array(
							'label' => 'Bezeichnung:',
				          	'required' => true,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'risk', array(
							'label' => 'Kalkuliertes Risiko:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'start_date', array(
							'label' => 'Startdatum:',
				          	'required' => true,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'end_date', array(
							'label' => 'Ist-Enddatum:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'planed_end_date', array(
							'label' => 'Soll-Enddatum:',
				          	'required' => true,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'booking_blocked_till', array(
							'label' => 'Buchungsdaten eingefroren bis:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'garantee_till', array(
							'label' => 'Gewährleistung bis bis:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'colour', array(
							'label' => 'Farbe:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('textarea', 'notice', array(
							'label' => 'Bemerkung:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'cols'    => '30',
							'rows'		=> '8',
							'decorators' => $this->decorator
		));
		
		$this->addElement('textarea', 'description', array(
							'label' => 'Ausführliche Projektbeschreibung:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'cols'    => '30',
							'rows'		=> '8',
							'decorators' => $this->decorator
		));
	
		
		$customerService = new Thunderball_Service_Customer();
		$all = $customerService->fetchAll();
		$element = new Zend_Form_Element_Select('customer');
		$element->setLabel('Kunde:');
		$element->setRequired(true);
		$element->setDecorators($this->decorator);
		$element->addMultiOption('0', 'Bitte wählen');
		foreach ($all as $entity) {
			$element->addMultiOption($entity->id, $entity->company->name . ' - ' . $entity->name);
		}
		$this->addElement($element);
		
		$this->addElement(
		$this->getSelectBox('parent', new Thunderball_Service_Project(), 'Hauptprojekt:', false, 'name'));
		
		$this->addElement(
		$this->getSelectBox('status', new Thunderball_Service_ProjectStatus(), 'Status:', true, 'name'));
		
		$this->addElement(
		$this->getSelectBox('category', new Thunderball_Service_ProjectCategory(), 'Kategorie:', false, 'name'));
		
	}

	private function getSelectBox($name, $service, $label, $isRequired, $field)
	{
		$all = $service->fetchAll();
		$element = new Zend_Form_Element_Select($name);
		$element->setLabel($label);
		$element->setRequired($isRequired);
		$element->setDecorators($this->decorator);
		$element->addMultiOption('0', 'Bitte wählen');
		foreach ($all as $entity) {
			$element->addMultiOption($entity->id, $entity->$field);
		}
		return $element;
	}
}