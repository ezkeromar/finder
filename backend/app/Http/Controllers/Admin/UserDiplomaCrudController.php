<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DiplomaCrudRequest as StoreRequest;
use App\Http\Requests\DiplomaCrudRequest as UpdateRequest;

class UserDiplomaCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\UserDiploma");
        $this->crud->setRoute("admin/user-diploma");
        $this->crud->setEntityNameStrings('user diploma', 'users diplomas');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->setColumns(['id', [ 
                'name' => 'user_id',
                'label' => 'Utilisateur',
                'type' => "select",
                'entity' => 'user', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Models\User", // foreign key model
            ], [ 
                'name' => 'diploma_id',
                'label' => 'Diplomes',
                'type' => "select",
                'entity' => 'diploma', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\Models\Diploma", // foreign key model
            ], 'speciality'
        ]);

        $this->crud->addField([ 
            'name' => 'user_id',
            'label' => 'Utilisateur',
            'type' => "select",
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => "last_name", // foreign key attribute that is shown to user
            'model' => "App\Models\User", // foreign key model
        ]);

        $this->crud->addField([ 
            'name' => 'diploma_id',
            'label' => 'Diplomes',
            'type' => "select",
            'entity' => 'diploma', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\Models\Diploma", // foreign key model
        ]);

		$this->crud->addField([
            'name' => 'speciality',
            'label' => "Spécialité"
        ]);

        $this->crud->addField([
            'name' => 'school',
            'label' => "Etablissement"
        ]);

        $this->crud->addField([
            'name' => 'year',
            'label' => "Année",
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