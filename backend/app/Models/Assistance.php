<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Assistance extends Model {

	use CrudTrait;

    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
	protected $table = 'assistance';
	// protected $primaryKey = 'id';
	protected $guarded = ['id'];
	// protected $hidden = ['id'];
	protected $fillable = ['user_id', 'message', 'user_from'];
	public $timestamps = true;

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/

	public function giveFeedback($crud = false) {
		if($this->user_from != 'admin')
			return '<a class="btn btn-xs btn-default" href="'.url("/admin/assistance/create?user_id=".$this->user_id).'" data-toggle="tooltip" title="Répondre."><i class="fa fa-reply"></i>Répondre</a>';
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
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