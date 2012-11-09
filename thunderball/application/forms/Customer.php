<?php
class Thunderball_Form_Customer extends Zend_Form
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
		$this->addElement('text', 'new_company', array(
							'label' => 'Neue Firma:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));
		
		$this->addElement('text', 'name', array(
							'label' => 'Organisationseinheit:',
				          	'required' => true,
				      		'filters' => array('StringTrim'),
							'decorators' => $this->decorator
		));

		$this->addElement('textarea', 'description', array(
							'label' => 'Beschreibung:',
				          	'required' => false,
				      		'filters' => array('StringTrim'),
							'cols'    => '30',
							'rows'		=> '8',
							'decorators' => $this->decorator
		));
		
		$companyService = new Thunderball_Service_Company();
		$allRoles = $companyService->fetchAll();
		$element = new Zend_Form_Element_Select('company');
		$element->setLabel('Firma:');
		$element->setRequired(true);
		$element->setDecorators($this->decorator);
		$element->addMultiOption(0, 'Neue Firma anlegen...');
		foreach ($allRoles as $role) {
			$element->addMultiOption($role->id, $role->name);
		}
		$this->addElement($element);
	}
}