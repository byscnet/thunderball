<?php
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
		$this->addElement(
		$this->getSelectBox('project', new Thunderball_Service_Project(), 'Projekt:', true, 'name'));
		
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