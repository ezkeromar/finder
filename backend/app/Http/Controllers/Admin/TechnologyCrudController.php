<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TechnologyCrudRequest as StoreRequest;
use App\Http\Requests\TechnologyCrudRequest as UpdateRequest;

class TechnologyCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\Technology");
        $this->crud->setRoute("admin/technology");
        $this->crud->setEntityNameStrings('technology', 'technologies');

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