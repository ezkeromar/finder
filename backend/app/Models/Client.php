<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Client extends Model {

	use CrudTrait;

    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
	protected $table = 'clients';
	// protected $primaryKey = 'id';
	protected $guarded = ['id'];
	// protected $hidden = ['id'];
	protected $fillable = ['name', 'logo', 'email', 'city_id', 'phone', 'status'];
	public $timestamps = true;

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/

	public function confirmClient($crud = false) {
		if($this->status == 'current')
			return '<a class="btn btn-xs btn-default" href="'.url("aprouve/client/".$this->id).'" data-toggle="tooltip" title="Apouvez un client."><i class="fa fa-user"></i>Confirmé un client</a>&nbsp;<a class="btn btn-xs btn-default" href="'.url("banne/client/".$this->id).'" data-toggle="tooltip" title="banné un client."><i class="fa fa-user"></i>Banné un client</a>';
	}

	public function setLogoAttribute($value) {
        $attribute_name = "logo";
        $disk = "uploads";
        $destination_path = "uploads/clients";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function city()
    {
        return $this->belongsTo('App\Models\City');
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