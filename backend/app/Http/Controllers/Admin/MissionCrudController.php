<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MissionCrudRequest as StoreRequest;
use App\Http\Requests\MissionCrudRequest as UpdateRequest;

class MissionCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\Mission");
        $this->crud->setRoute("admin/mission");
        $this->crud->setEntityNameStrings('mission', 'missions');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->setColumns(['id', [
                'name' => 'title',
                'label' => "Titre"
            ], 
            ['name' => 'duration', 'label' => "Duration"], 
            ['name' => 'date_start', 'label' => "Date de début"], 
            ['name' => 'tjm', 'label' => "TJM"], 
            ['name' => 'city_id', 'label' => 'Ville', 'type' => "select", 'entity' => 'city', 'attribute' => "title", 'model' => "App\Models\City"], 
            ['name' => 'client_id', 'label' => 'Client', 'type' => "select", 'entity' => 'client', 'attribute' => "name", 'model' => "App\Models\Client"], 
        	['name' => 'created_at', 'label' => 'Date de création'],
        ]);

        $this->crud->orderBy('created_at','desc');


        $this->crud->addField([ 
            'name' => 'client_id',
            'label' => 'Client',
            'type' => "select2",
            'entity' => 'client', 
            'attribute' => "name", 
            'model' => "App\Models\Client",
        ]);

		$this->crud->addField([
            'name' => 'title',
            'label' => "Titre"
        ]);

        $this->crud->addField([
            'name' => 'duration',
            'label' => "Duration",
            'type' => 'number',
            'suffix' => "jours"
        ]);

        $this->crud->addField([
            'name' => 'tjm',
            'label' => "TJM",
            'type' => 'number'
        ]);

        $this->crud->addField([
            'name' => 'rate',
            'label' => "Rate",
            'type' => 'number'
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
               'name' => 'date_start',
               'type' => 'date_picker',
               'label' => 'Date de début',
               // optional:
               'date_picker_options' => [
                  'todayBtn' => true,
                  'format' => 'yyyy-mm-dd',
                  'language' => 'fr'
               ],
        ]);

        $this->crud->addField([ 
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select2_from_array',
            'options' => ['planned' => 'Planifié', 'current' => 'En cours', 'finished' => 'Terminé'],
            'allows_null' => false,
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
            'name' => 'user_id',
            'label' => 'User',
            'type' => "select2",
            'entity' => 'user', 
            'attribute' => "name", 
            'model' => "App\Models\User",
        ]);

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Technologies",
            'type' => 'select2_multiple',
            'name' => 'technology', // the method that defines the relationship in your Model
            'entity' => 'technology', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\Models\Technology", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        $this->crud->addField([
            'label' => "Compétence",
            'type' => 'select2_multiple',
            'name' => 'skill', 
            'entity' => 'skill',
            'attribute' => 'title',
            'model' => "App\Models\Skill",
            'pivot' => true, 
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