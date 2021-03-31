<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Libraries\Notifications;
use App\Models\Client;
use App\Models\User;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RequestContactCrudRequest as StoreRequest;
use App\Http\Requests\RequestContactCrudRequest as UpdateRequest;
use Mail;
use Illuminate\Http\Request;

class RequestContactCrudController extends CrudController {

	public function setup() {
        $this->crud->setModel("App\Models\RequestContact");
        $this->crud->setRoute("admin/request_contact");
        $this->crud->setEntityNameStrings('demande de contact', 'demandes des contacts');

        $this->crud->allowAccess(['list', 'create', 'delete']);

        $this->crud->addButtonFromModelFunction('line', 'confirm_rdv', 'confirmRdv', 'beginning');

        $this->crud->setColumns(['id', 
            ['name' => 'mission_id', 'label' => 'Mission', 'type' => "select", 'entity' => 'mission', 'attribute' => "title", 'model' => "App\Models\Mission"],
            ['name' => 'consultant_id', 'label' => 'Consultant', 'type' => "select", 'entity' => 'consultant', 'attribute' => "firstname", 'model' => "App\Models\User"], 
            ['name' => 'client_id', 'label' => 'Client', 'type' => "select", 'entity' => 'client', 'attribute' => "name", 'model' => "App\Models\Client"],
            ['name' => 'date_start', 'label' => 'Date de début'],
        	['name' => 'created_at', 'label' => 'Date de création'],
        ]);

        $this->crud->orderBy('created_at','desc');

        $this->crud->addField([ 
            'name' => 'mission_id',
            'label' => 'Mission',
            'type' => "select2",
            'entity' => 'mission', 
            'attribute' => "title", 
            'model' => "App\Models\Mission",
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
            'name' => 'consultant_id',
            'label' => 'Consultant',
            'type' => "select2",
            'entity' => 'consultant', 
            'attribute' => "lastname", 
            'model' => "App\Models\User",
        ]);

        $this->crud->addField([
               'name' => 'date_start',
               'type' => 'datetime_picker',
               'label' => 'Date de début',
               'date_picker_options' => [
                  'todayBtn' => true,
                  'format' => 'dd-mm-yyyy HH:mm',
                  'language' => 'fr'
               ],
        ]);

        $this->crud->addField([
               'name' => 'date_end',
               'type' => 'datetime_picker',
               'label' => 'Date de fin',
               'date_picker_options' => [
                  'todayBtn' => true,
                  'format' => 'dd-mm-yyyy HH:mm',
                  'language' => 'fr'
               ],
        ]);

		$this->crud->addField([
            'name' => 'status',
            'label' => "Status de la demande",
            'type' => 'select2_from_array',
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
	
    $request_contact = parent::updateCrud();
    if($this->request->request->get('status') == 'confirmed') {
      $notification = new Notifications();
      $client = Client::find($this->request->request->get('client_id'));
      $params = array(
             'user_id' => $this->request->request->get('consultant_id'), 
              'description' => "{$client->name} vous a envoyé une demande de contact", 
              'type' => 'request_contact'
          );
      $notification->add($params);
	  
	  $consultant_info = User::find($this->request->request->get('consultant_id'));
	  $name = $consultant_info->firstname ;
	  $email = $consultant_info->email;
	  $base_url = config('constants.BASE_URL');
	  $name_client = $client->name;
	  $client_id = $this->request->request->get('client_id');
		Mail::send('email.demande_contact', ['base_url' => $base_url,'name' => $name,'name_client'=>$name_client,'base_url' => $base_url], function ($m) use ($email, $name) {
			$m->from(config('constants.MAIL_FROM'), 'IMZII ');
			$m->to($email,$name )->subject("[IMZII] Demande contact");
		});
	  
      //dd($this->request->request);
    }    
    return $request_contact;
		//return parent::updateCrud();
	}
}