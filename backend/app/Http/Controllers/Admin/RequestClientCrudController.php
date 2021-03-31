<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RequestClientCrudRequest as StoreRequest;
use App\Http\Requests\RequestClientCrudRequest as UpdateRequest;

class RequestClientCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\RequestClient");
        $this->crud->setRoute("admin/request_client");
        $this->crud->setEntityNameStrings('request client', 'request client');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->setColumns(['id', [
                'name' => 'subject',
                'label' => "Sujet"
            ],
            ['name' => 'client_id', 'label' => 'Consultant', 'type' => "select", 'entity' => 'consultant', 'attribute' => "full_name", 'model' => "App\Models\User"], 
            ['name' => 'consultant_id', 'label' => 'Client', 'type' => "select", 'entity' => 'client', 'attribute' => "name", 'model' => "App\Models\Client"], 
			['name' => 'created_at', 'label' => 'Date de création'],
        ]);

        $this->crud->orderBy('created_at','desc');


        $this->crud->addFilter([ // select2 filter
            'name' => 'subject', 'type' => 'select2', 'label'=> 'Sujet de la demande'],
        function() {
            return ['Voir le CV' => 'Voir le CV', "Voir le profile" => "Voir le profile", "Voir l'image" => "Voir l'image", ];
        }, function($value) { // if the filter is active
             $this->crud->addClause('where', 'subject', $value);
        });

        $this->crud->addField([
            'name' => 'subject',
            'label' => "Sujet",
            'type' => "text",
        ]);

        $this->crud->addField([ 
            'name' => 'client_id',
            'label' => 'Client',
            'type' => "select",
            'entity' => 'client', 
            'attribute' => "name", 
            'model' => "App\Models\Client",
        ]);

        $this->crud->addField([ 
            'name' => 'consultant_id',
            'label' => 'Consultant',
            'type' => "select",
            'entity' => 'consultant', 
            'attribute' => "full_name", 
            'model' => "App\Models\User",
        ]);

		$this->crud->addField([
            'name' => 'status',
            'label' => "Status de la demande",
            'type' => 'select',
            'options' => ['sent' => 'Envoyé', 'confirmed' => 'Confirmé', 'rejected' => 'Rejeté'],
            'allows_null' => false,
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