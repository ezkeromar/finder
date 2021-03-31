<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AssistanceCrudRequest as StoreRequest;
use App\Http\Requests\AssistanceCrudRequest as UpdateRequest;
use Request;
use App\Models\Assistance;

class AssistanceCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\Assistance");
        $this->crud->setRoute("admin/assistance");
        $this->crud->setEntityNameStrings('message', 'messages');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->addButtonFromModelFunction('line', 'give_feedback', 'giveFeedback', 'beginning');

        $this->crud->setColumns(['id', [
                'name' => 'user_from',
                'label' => "Type de profile"
            ], 
            [ 
                'name' => 'user_id',
                'label' => 'Prénom',
                'type' => 'select',
                'entity' => 'user', // the method that defines the relationship in your Model
                'attribute' => "firstname", // foreign key attribute that is shown to user
                'model' => "App\Models\User", // foreign key model
            ], 
            [ 
                'name' => 'user_id',
                'label' => 'Nom',
                'type' => 'select',
                'entity' => 'user', // the method that defines the relationship in your Model
                'attribute' => "lastname", // foreign key attribute that is shown to user
                'model' => "App\Models\User", // foreign key model
            ], 
        ]);
        $this->crud->addField([ // select_from_array
            'name' => 'user_from',
            'label' => "Type d'utilisateur",
            'type' => 'select2_from_array',
            'options' => ['consultant' => 'consultant', 'client' => 'client', 'admin' => 'admin'],
            'allows_null' => false,
            'default' => 'admin',
        ]);
		$this->crud->addField([ 
            'name' => 'user_id',
            'label' => 'Prénom',
            'type' => 'select',
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => "firstname", // foreign key attribute that is shown to user
            'model' => "App\Models\User", // foreign key model
        ]);
        $this->crud->addField([
            'name' => 'message',
            'label' => "Message"
        ]);

        $this->crud->addFilter([ // select2 filter
          'name' => 'user_id',
          'type' => 'select2',
          'label'=> 'Utilisateur'
        ], function() {
            return \App\Models\User::all()->pluck('firstname', 'id')->toArray();
        }, function($value) { // if the filter is active
                $this->crud->addClause('where', 'user_id', $value);
        });

        if(!empty($_GET['user_id'])) {
            $this->crud->removeField('user_id');
            $this->crud->removeField('user_from');
            $this->crud->addField([
                'name' => 'user_hidden_id',
                'type' => "hidden",
                'value' => $_GET['user_id']
            ]);
        }

    }

	public function store(StoreRequest $request)
	{
        if(!empty($request->user_hidden_id)) {
            $assistance = new Assistance;
            $assistance->user_id = $request->user_hidden_id;
            $assistance->user_from = 'admin';
            $assistance->message = $request->message;
            $assistance->save();
            return redirect("admin/assistance");
        }else {
    		return parent::storeCrud();
	    }
    }

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}