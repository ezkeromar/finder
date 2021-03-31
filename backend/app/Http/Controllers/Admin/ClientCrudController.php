<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ClientCrudRequest as StoreRequest;
use App\Http\Requests\ClientCrudRequest as UpdateRequest;

class ClientCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\Client");
        $this->crud->setRoute("admin/client");
        $this->crud->setEntityNameStrings('client', 'clients');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->addButtonFromModelFunction('line', 'confirm_client', 'confirmClient', 'beginning');

        $this->crud->setColumns(['id', [
                'name' => 'name',
                'label' => "Nom du client"
            ],[ 
                'name' => 'email', 
                'label' => 'Email du client',
                //'type' => 'image',
            ],
            [ 
                'name' => 'logo',
                'label' => 'Logo',
                'type' => 'image',
                'height' => '30px',
                'width' => '30px',
            ], 
            [ 
                'name' => 'city_id',
                'label' => 'Ville',
                'type' => "select",
                'entity' => 'city', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\Models\City", // foreign key model
            ], 
        	['name' => 'created_at', 'label' => 'Date de création'],
        ]);

        $this->crud->orderBy('created_at','desc');
		$this->crud->addField([
            'name' => 'name',
            'label' => "Nom du client"
        ]);
        $this->crud->addField([
            'name' => 'email',
            'label' => "Email du client"
        ]);
        $this->crud->addField([
            'name' => 'phone',
            'label' => "Téléphone"
        ]);
        $this->crud->addField([ 
            'name' => 'city_id',
            'label' => 'Ville',
            'type' => "select2",
            'entity' => 'city', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\Models\City", // foreign key model
        ]);

        $this->crud->addField([ // image
            'label' => "Logo du client",
            'name' => "logo",
            'type' => 'upload',
            'upload' => true,
            // 'prefix' => 'uploads/images/profile_pictures/' // in case you only store the filename in the database, this text will be prepended to the database value
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