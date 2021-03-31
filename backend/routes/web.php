<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('usertextcv', 'WordextractController@extract');
Route::get('/aprouve/client/{id}', 'api\MissionsController@aprouveclient');
Route::get('/banne/client/{id}', 'api\MissionsController@banneclient');

Route::get('linkedin', 'socialLoginController@linkedinLogin');

Route::get('auth/linkedin', 'socialLoginController@linkedinLogin');

Route::get('auth/linkedin/callback', 'socialLoginController@handleLinkedinCallback');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/confirm/rdv/{rdv}/{id}', "api\ConsultantsController@confirmRdv");

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
	CRUD::resource('city', 'Admin\CityCrudController');
	CRUD::resource('news', 'Admin\NewsCrudController');
	CRUD::resource('client', 'Admin\ClientCrudController');
	CRUD::resource('technology', 'Admin\TechnologyCrudController');
	CRUD::resource('skill', 'Admin\SkillCrudController');
	CRUD::resource('diploma', 'Admin\DiplomaCrudController');
	CRUD::resource('mission', 'Admin\MissionCrudController');
	CRUD::resource('secteur', 'Admin\SecteurCrudController');
	CRUD::resource('user', 'Admin\UserCrudController');
	CRUD::resource('consultent', 'Admin\ConsultentCrudController');
	CRUD::resource('user-diploma', 'Admin\UserDiplomaCrudController');
	CRUD::resource('request_client', 'Admin\RequestClientCrudController');
	CRUD::resource('request_contact', 'Admin\RequestContactCrudController');
	CRUD::resource('assistance', 'Admin\AssistanceCrudController');
});