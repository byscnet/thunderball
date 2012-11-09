<?php

class ProjectController extends Zend_Controller_Action
{
	private $projectService;
	private $categoryService;
	private $customerService;
	//private $companyService;
	private $statusService;
	private $memberService;

	public function init()
	{
		$this->projectService = new Thunderball_Service_Project();
		$this->categoryService = new Thunderball_Service_ProjectCategory();
		$this->customerService = new Thunderball_Service_Customer();
		//$this->companyService = new Thunderball_Service_Company();
		$this->statusService = new Thunderball_Service_ProjectStatus();
		$this->memberService = new Thunderball_Service_ProjectMember();
	}

	public function indexAction()
	{

	}

	public function detailAction()
	{
		if (!$this->_hasParam('id')) {
			return $this->redirect('project/list');
		}

		$project = $this->projectService->getById($this->_getParam('id'));
		
		if ($project == null) {
			return $this->redirect('project/list');
		}
		
		$memberList = $this->memberService->getByProjectId($project->id);
		
		$this->view->project = $project;
		$this->view->memberList = $memberList;
	}
	
	public function newAction()
	{
		$form = new Thunderball_Form_Project();
			
		if ($this->getRequest()->isPost())
		{
			if (!$form->isValid($_POST)) {
				// Fehlgeschlagene Prüfung; Form wieder anzeigen
				$this->view->form = $form;
				return $this->render('new');
			}

			$values = $this->getRequest()->getParams();
			$project = $this->storeProject(new Thunderball_Model_Project(), $values);
			return $this->redirect('project/detail/id/' . $project->id);
		}

		$this->view->form = $form;
	}

	public function editAction()
	{
		$user = $this->view->accountHelper()->getUser();
		if ($user->role == 'Mitarbeiter') {
			return $this->redirect('project/list');
		}
		
		if (!$this->_hasParam('id')) {
			return $this->redirect('project/list');
		}

		$form = new Thunderball_Form_Project();

		$project = $this->projectService->getById($this->_getParam('id'));
		
		if ($this->getRequest()->isPost())
		{
			if (!$form->isValid($_POST)) {
				// Fehlgeschlagene Prüfung; Form wieder anzeigen
				$this->view->form = $form;
				return $this->render('edit');
			}

			$values = $this->getRequest()->getParams();
			$this->storeProject($project, $values);
			return $this->redirect('project/list');
		}

		
		
		$parent = 0;
		if ($project->parent != null) {
			$parent = $project->parent->id;
		}
		$category = 0;
		if ($project->category != null) {
			$category = $project->category->id;
		}

		$enddate = '';
		if ($project->end_date != null) {
			$enddate = $project->end_date->format('d.m.Y');
		}

		$frozendate = '';
		if ($project->boocking_blocked_till != null) {
			$frozendate = $project->boocking_blocked_till->format('d.m.Y');
		}

		$garanteedate = '';
		if ($project->garantee_till != null) {
			$garanteedate = $project->garantee_till->format('d.m.Y');
		}

		$form->populate(array(
		'name' => $project->name,
		'notice' => $project->notice,
		'description' => $project->description,
		'parent' => $parent,
		'status' => $project->status->id,
		'customer' => $project->customer->id,
		'category' => $category,
		'risk' => $project->risk,
		'colour' => $project->colour,
		'start_date' => $project->start_date->format('d.m.Y'),
		'end_date' => $enddate,
		'planed_end_date' => $project->planed_end_date->format('d.m.Y'),
		'booking_blocked_till' => $frozendate,
		'garantee_till' => $garanteedate,
		));

		$this->view->form = $form;
		$this->view->projectId = $project->id;
	}

	public function costAction()
	{

	}

	public function listAction()
	{

	}

	// Todo: manche datetimes sind optional. wenn nix angegeben dann null!
	private function storeProject($project, $values)
	{
		//$project = new Thunderball_Model_Project();
		$project->name = $values['name'];
		$project->description = $values['description'];
		$project->notice = $values['notice'];
		$project->risk = $values['risk'];
		$project->start_date = new DateTime($values['start_date']);
		$project->end_date = new DateTime($values['end_date']);
		$project->planed_end_date = new DateTime($values['planed_end_date']);
		$project->boocking_blocked_till = new DateTime($values['booking_blocked_till']);
		$project->garantee_till = new DateTime($values['garantee_till']);
		$project->colour = $values['colour'];
		$project->parent = $this->projectService->getById($values['parent']);
		$project->category = $this->categoryService->getById($values['category']);
		$project->customer = $this->customerService->getById($values['customer']);
		//$project->company = $this->companyService->getById($values['company']);
		$project->status = $this->statusService->getById($values['status']);

			
		// optionales
			
			
		// projekt speichern
		$this->projectService->store($project);
		
		return $project;
	}

}

