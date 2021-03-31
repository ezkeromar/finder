<?php

use Illuminate\Http\Request; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'api\AuthController@authenticate');
Route::post('login-token', 'api\AuthController@linkedinlogin');
Route::post('register', 'api\AuthController@register');
Route::post('logout', 'api\AuthController@logout');
Route::post('reset-pass', 'api\AuthController@resetpass');
Route::post('update-pass', 'api\AuthController@updatepass');
Route::post('aprouve-consultant', 'api\AuthController@aprouveConsultant');
	

Route::group([
    'middleware' => 'jwt.auth',
], function ($router) {
	Route::get('profile', 'api\AuthController@profile');
	Route::get('search/consultants', 'api\ConsultantsController@search');
	Route::get('/get/skills', 'api\ConsultantsController@skills');
	Route::get('/get/diplomas', 'api\ConsultantsController@diplomas');
	Route::get('/get/technologies', 'api\ConsultantsController@technologies');
	Route::post('/get/client/missions', 'api\ConsultantsController@missions');
	Route::get('/get/messages', 'api\AssistanceController@getMessages');
	Route::post('/get/message/add', 'api\AssistanceController@sendMessage');
	Route::get('/get/consultent', 'api\ConsultantsController@consultent');


	// Profile
	Route::post('/get/profile/client', 'api\ProfileController@clientInfo');
	Route::get('/get/profile/consultent', 'api\ProfileController@consultentInfo');
	Route::post('/load/consultent', 'api\ProfileController@loadconsultant');
	Route::post('/get/profile/adduser', 'api\ProfileController@clientUserInsert');
	Route::post('/get/profile/deleteuser', 'api\ProfileController@clientUserRemove');
	Route::post('/get/mission/insert', 'api\ProfileController@missionInsert');
	Route::post('/get/mission/addexperience', 'api\MissionController@likeExperience');
	Route::post('/get/request/send', 'api\SearchController@requestInsert');
	Route::post('/get/client/missions', 'api\ConsultantsController@missions');
	Route::post('/get/profile/deleteexperience', 'api\ConsultantsController@experienceRemove');
	Route::post('/get/profile/deletediploma', 'api\ConsultantsController@diplomaRemove');
	Route::post('/get/profile/deletecertif', 'api\ConsultantsController@certifRemove');
	Route::post('/add/user/client', 'api\ConsultantsController@adduserToClient');
	Route::post('/update/client', 'api\ConsultantsController@UpdateClient');

	// Consultant
	Route::post('/consultant/edit', 'api\ConsultantsController@edit');
	Route::post('/consultant/rate', 'api\ConsultantsController@AddRate');

	// Mission 
	Route::get('/get/mission/list', 'api\MissionController@missionList');
	Route::get('/get/mission/consultant/list', 'api\MissionController@missionConsultantList');
	Route::post('/get/mission', 'api\MissionController@getMission');
	Route::post('/get/only/mission', 'api\MissionController@getMissionOnly');
	Route::get('/get/cities', 'api\ConsultantsController@cities');
	Route::get('/get/secteurs', 'api\ConsultantsController@secteurs');
	Route::get('/get/countries', 'api\ConsultantsController@countries');
	Route::post('/get/mission/add', 'api\MissionController@missionAdd');
	Route::post('/get/mission/delete', 'api\MissionController@missionDelete');
	Route::post('/get/mission/toggle', 'api\MissionController@missiontoggle');
	Route::post('/add/user/mission', 'api\MissionController@addUserToMission');
	Route::post('update/consultant/select', 'api\MissionController@UpdateConsultentSelection');
	Route::post('delete/consultant/select', 'api\MissionController@DeleteConsultentSelection');

	// Agenda
	Route::post('/get/client/request_contact', 'api\ConsultantsController@requestContactInsert');
	Route::post('/get/client/agenda', 'api\ConsultantsController@requestContactList');

	// Notifications
	Route::get('get/notifications', 'api\NotificationsController@getList');
	Route::get('get/read/notifications', 'api\NotificationsController@read');


	Route::get('search/missions', 'api\MissionController@search');
	Route::get('search/consultants', 'api\ConsultantsController@search');
	

	
	//Route::get('/get/contact/send', 'api\AssistanceController@sendContact');



	Route::get('/get/skills', 'api\ConsultantsController@skills');

	Route::get('/cron/missions/expired', 'api\MissionController@expired');

	Route::get('/get/profile/consultent', 'api\ProfileController@consultentInfo');
	Route::get('/get/mission/consultant/list', 'api\MissionController@missionConsultantList');
});

Route::get('search/front/consultants', 'api\ConsultantsController@searchfront');
Route::get('news/front', 'api\ConsultantsController@newsfront');
Route::get('/get/mission/home', 'api\MissionController@lastMissions');
// Contact
Route::post('/get/contact/send', 'api\AssistanceController@sendContact');



