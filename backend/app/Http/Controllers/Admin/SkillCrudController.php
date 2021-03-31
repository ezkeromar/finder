<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SkillCrudRequest as StoreRequest;
use App\Http\Requests\SkillCrudRequest as UpdateRequest;

class SkillCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\Skill");
        $this->crud->setRoute("admin/skill");
        $this->crud->setEntityNameStrings('skill', 'skills');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->setColumns(['id', [
                'name' => 'title',
                'label' => "Titre"
            ], 
        ]);

		$this->crud->addField([
            'name' => 'title',
            'label' => "Titre"
        ]);        

    }

	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}