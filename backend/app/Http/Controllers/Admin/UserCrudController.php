<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\User;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UserCrudRequest as StoreRequest;
use App\Http\Requests\UserCrudRequest as UpdateRequest;

class UserCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\User");
        $this->crud->setRoute("admin/user");
        $this->crud->setEntityNameStrings('utilisateur', 'utilisateurs');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->setColumns(['id', 
            [ 'name' => 'firstname', 'label' => "Prénom" ], 
            [ 'name' => 'lastname', 'label' => "Nom" ],
            [ 'name' => 'email', 'label' => "E-mail" ], 
            [ 'name' => 'profession', 'label' => "Profession" ], 
            [ 'name' => 'phone', 'label' => "Téléphone" ], 
        ]);

        $this->crud->addClause('where', 'client_id', '!=', 0);


        $this->crud->addField([
            'name' => 'firstname',
            'label' => "Prénom"
        ]);

        $this->crud->addField([
            'name' => 'lastname',
            'label' => "Nom"
        ]);

        $this->crud->addField([ 
            'label' => "Photo de profil",
            'name' => "picture",
            'type' => 'upload',
            'upload' => true,
        ]);

        $this->crud->addField([
            'name' => 'email',
            'label' => "E-mail"
        ]);

        $this->crud->addField([
            'name' => 'phone',
            'label' => "Téléphone"
        ]);

        $this->crud->addField([
            'name' => 'address',
            'label' => "Adresse"
        ]);

        $this->crud->addField([ 
            'name' => 'city_id',
            'label' => 'Ville',
            'type' => "select2",
            'entity' => 'city', 
            'attribute' => "title", 
            'model' => "App\Models\City",
        ]);

        $this->crud->addField([
            'name' => 'profession',
            'label' => "Profession"
        ]);

        $this->crud->addField([ 
            'name' => 'client_id',
            'label' => 'Client',
            'type' => "select2",
            'entity' => 'client', 
            'attribute' => "name", 
            'model' => "App\Models\Client",
        ]);

        $this->crud->addField([ 
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select2_from_array',
            'options' => ['aprouved' => 'Aprouvé', 'banned' => 'Banné', 'current' => 'En cours'],
            'allows_null' => false,
        ]);

        $this->crud->addField([   // Hidden
            'name' => 'password',
            'type' => 'hidden',
            'value' => rand(1111111, 999999999)
        ]);

        $this->crud->addField([
            'name' => 'scripts',
            'type' => 'scripts'
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