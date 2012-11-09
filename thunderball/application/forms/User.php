<?php
class Thunderball_Form_User extends Zend_Form
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
		$this->addElement('text', 'title', array(
							'label' => 'Titel:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));
		
		$this->addElement('text', 'firstname', array(
							'label' => 'Vorname:',
				          	'required' => true,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'lastname', array(
							'label' => 'Nachname:',
				          	'required' => true,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'email', array(
							'label' => 'E-Mail:',
				          	'required' => true,
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
		
		$this->addElement('text', 'hourly_rate', array(
							'label' => 'Stundensatz:',
				          	'required' => true,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('text', 'hours_of_work_per_day', array(
							'label' => 'Stundenwoche:',
				          	'required' => true,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$element = new Zend_Form_Element_Select('salutation');
		$element->setLabel('Anrede');
		$element->setRequired(true);
		$element->setDecorators($this->decorator);
		$element->addMultiOption('0', 'Herr');
		$element->addMultiOption('1', 'Frau');
		$this->addElement($element);
		
		$roleService = new Thunderball_Service_Role();
		$allRoles = $roleService->fetchAll();
		$element = new Zend_Form_Element_Select('role');
		$element->setLabel('Rolle:');
		$element->setRequired(true);
		$element->setDecorators($this->decorator);
		foreach ($allRoles as $role) {
			$element->addMultiOption($role->id, $role->name);
		}
		$this->addElement($element);
	}
}