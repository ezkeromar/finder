<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Laravel\Scout\Searchable;

class Mission extends Model {

	use CrudTrait;
	use Searchable;

    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
	protected $table = 'missions';
	// protected $primaryKey = 'id';
	protected $guarded = ['id'];
	// protected $hidden = ['id'];
	protected $fillable = ['title', 'description', 'duration', 'tjm', 'status', 'date_start', 'city_id', 'client_id', 'rate', 'user_id', 'is_archived'];
	public $timestamps = true;

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function requestContact() {
		return $this->hasMany('App\Models\RequestContact', 'mission_id');
	}

	public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }
    public function technology() {
        return $this->belongsToMany('App\Models\Technology', 'mission_technology')
            ->withPivot('mission_id', 'technology_id');
    }
    public function skill() {
        return $this->belongsToMany('App\Models\Skill', 'mission_skill')
            ->withPivot('mission_id', 'skill_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function searchableAs()
    {
        return 'missions_index';
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