<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class RequestContact extends Model {

	use CrudTrait;

    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
	protected $table = 'request_contact';
	// protected $primaryKey = 'id';
	protected $guarded = ['id'];
	// protected $hidden = ['id'];
	protected $fillable = ['mission_id', 'consultant_id', 'client_id', 'status', 'date_start', 'date_end'];
	public $timestamps = true;

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/

	public function confirmRdv($crud = false) {
		if($this->status == 'sent')
			return '<a class="btn btn-xs btn-default" href="'.url("/confirm/rdv/1/".$this->id).'" data-toggle="tooltip" title="Confirmé un rendez-vous un."><i class="fa fa-meetup"></i>Confirmé rdv un</a>&nbsp;<a class="btn btn-xs btn-default" href="'.url("/confirm/rdv/2/".$this->id).'" data-toggle="tooltip" title="Confirmé un rendez-vous deux."><i class="fa fa-meetup"></i>Confirmé rdv deux</a>&nbsp;<a class="btn btn-xs btn-default" href="'.url("/confirm/rdv/3/".$this->id).'" data-toggle="tooltip" title="Annulé un rendez-vous."><i class="fa fa-meetup"></i>Annulé rdv</a>';
	}
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/

	public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function consultant()
    {
        return $this->belongsTo('App\Models\User', 'consultant_id');
    }

	public function mission()
    {
        return $this->belongsTo('App\Models\Mission');
    }
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| ACCESORS
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}