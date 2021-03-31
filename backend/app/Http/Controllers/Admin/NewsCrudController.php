<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CityCrudRequest as StoreRequest;
use App\Http\Requests\CityCrudRequest as UpdateRequest;

class NewsCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\News");
        $this->crud->setRoute("admin/news");
        $this->crud->setEntityNameStrings('new', 'news');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->setColumns(['id', [
                'name' => 'title',
                'label' => "Titre"
            ],
        	['name' => 'created_at', 'label' => 'Date de création'], 
        ]);

        $this->crud->orderBy('created_at','desc');
		$this->crud->addField([
            'name' => 'title',
            'label' => "Titre"
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => "Description",
            'type' => 'textarea'
        ]);
        $this->crud->addField([ 
            'label' => "Photo de profil",
            'name' => "picture",
            'type' => 'upload',
            'upload' => true,
        ]);

        $this->crud->addField([
            'name' => 'state',
            'label' => 'Status',
            'type' => 'checkbox'
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