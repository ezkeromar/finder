<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CityCrudRequest as StoreRequest;
use App\Http\Requests\CityCrudRequest as UpdateRequest;

class CityCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\City");
        $this->crud->setRoute("admin/city");
        $this->crud->setEntityNameStrings('city', 'cities');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->setColumns(['id', [
                'name' => 'title',
                'label' => "Titre de la ville"
            ]
        ]);
		$this->crud->addField([
            'name' => 'title',
            'label' => "Titre de la ville"
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