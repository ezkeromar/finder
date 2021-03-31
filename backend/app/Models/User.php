<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Laravel\Scout\Searchable;

class User extends Model {

	use CrudTrait;
    use Searchable;

    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
	protected $table = 'users';
	// protected $primaryKey = 'id';
	protected $guarded = ['id'];
	// protected $hidden = ['id'];
	protected $fillable = ['name', 'firstname', 'lastname', 'email', 'picture', 'tjm', 'status', 'year_start_experience', 'profession', 'curriculum_vitae', 'phone', 'address', 'city_id', 'country_id', 'secteur_id', 'disponibility_date', 'cv_content', 'client_id', 'password', 'is_admin', 'note'];
	public $timestamps = true;

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public function setPictureAttribute($value) {
        $attribute_name = "picture";
        $disk = "uploads";
        $destination_path = "uploads/users/images";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }
    public function setCurriculumVitaeAttribute($value) {
        $attribute_name = "curriculum_vitae";
        $disk = "uploads";
        $destination_path = "uploads/users/cv";
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
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
    public function secteur()
    {
        return $this->belongsTo('App\Models\Secteur');
    }
    public function userdiplome()
    {
        return $this->hasMany('App\Models\UserDiploma', 'user_id')->orderBy('year', 'desc');
    }
    public function usermission()
    {
        return $this->hasMany('App\Models\UserMission', 'user_id');
    }
    public function skill() {
        return $this->belongsToMany('App\Models\Skill', 'user_skill')
            ->withPivot('user_id', 'skill_id');
    }
    public function technology() {
        return $this->belongsToMany('App\Models\Technology', 'user_technology')
            ->withPivot('user_id', 'technology_id');
    }
    public function diploma() {
        return $this->belongsToMany('App\Models\Diploma', 'user_diploma')
            ->withPivot('user_id', 'diploma_id')->orderBy('year', 'desc');
    }
    public function experience() {
        return $this->hasMany('App\Models\Experience', 'user_id')->orderBy('date_start', 'desc');
    }
    public function certif() {
        return $this->hasMany('App\Models\Certification', 'user_id');
    }

	public function getFullNameAttribute() {
		return $this->firstname . ' ' . $this->lastname;
	}
	
	public function toSearchableArray(){
		$array = array();//$this->toArray();
		$array['id'] = $this->id;
		$array['profession'] = $this->profession;
		$array['cv_content'] = $this->cv_content;
		//sector id
		//$array['secteur'] = $this->secteur->id;
		//Dispobility as timestamp
		//$timestp = Carbon::createFromFormat('Y-m-d H:i:s', $this->disponibility_date)->timestamp;
		//$array['disponibility_date'] = $timestp;
		$array['year_start_experience'] = $this->year_start_experience;
		$array['skills'] = array_map(function ($data) {
		                                        return $data['title'];
		                                }, $this->skill->toArray());
		$array['diplomas'] = array_map(function ($data) {
		                                        return $data['title'];
		                                }, $this->diploma->toArray());
		$array['experiences'] = array_map(function ($data) {
		                                        return $data['title'];
		                                }, $this->experience->toArray());
		$array['technologies'] = array_map(function ($data) {
		                                        return $data['title'];
		                                }, $this->technology->toArray());
		//$array['skills'] = getSkillsArray();
		// Customize array...
		return $array;
	}
	
	/**
     * Get the value used to index the model.
     *
     * @return mixed
     */
    public function getScoutKey()
    {
        return $this->id;
    }

    public function searchableAs()
    {
        return 'users_index';
    }
	
	public function shouldBeSearchable()
	{
	    return ($this->status == 'aprouved') && ($this->client_id == 0);
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