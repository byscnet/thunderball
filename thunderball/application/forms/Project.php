<?php
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

		$this->addElement(
		$this->getSelectBox('customer', new Thunderball_Service_Customer(), 'Kunde:', true, 'name'));
	
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