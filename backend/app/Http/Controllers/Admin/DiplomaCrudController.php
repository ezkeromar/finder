<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DiplomaCrudRequest as StoreRequest;
use App\Http\Requests\DiplomaCrudRequest as UpdateRequest;

class DiplomaCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\Diploma");
        $this->crud->setRoute("admin/diploma");
        $this->crud->setEntityNameStrings('diploma', 'diplomas');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->setColumns(['id', [
                'name' => 'title',
                'label' => "Titre"
            ], 
            [
                'name' => 'nbr_years',
                'label' => "Nombre d'années",
            ], 
        ]);

		$this->crud->addField([
            'name' => 'title',
            'label' => "Titre"
        ]);

        $this->crud->addField([
            'name' => 'nbr_years',
            'label' => "Nombre d'années",
            'type' => 'number'
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