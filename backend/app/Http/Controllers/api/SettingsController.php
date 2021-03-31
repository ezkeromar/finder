<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use DB;
use Carbon\Carbon;

use App\Http\Requests\MissionCrudRequest;

class SettingsController extends Controller
{
    public function clientInfo(Request $request) {
    	$id = $request->client_id;
        $client_info = Client::with(['city'])->where(array('id' => $id))->first();
        if(!$client_info) {
        	$return = array();
        	return response()->json(compact('return'));
        }
        $client_info->users = User::where(array('client_id' => $id))->get();
        $client_info->missions = Mission::with(['technology', 'skill'])->where(array('client_id' => $id))->get();
        return response()->json(compact('client_info'));
    }

    public function missionInsert(Request $request) {
    	$status = false;
    	$mission = new Mission;
    	// 'title', 'description', 'duration', 'tjm', 'status', 'date_start', 'city_id', 'client_id', 'rate', 'user_id'
        $mission->title = $request->title;
        $mission->description = $request->description;
        $mission->duration = $request->duration;
        $mission->tjm = $request->tjm;
        $mission->status = $request->status;
        $mission->date_start = $request->date_start;
        $mission->city_id = $request->city_id;
        $mission->client_id = $request->client_id;
        $mission->rate = $request->rate;
        $mission->user_id = $request->user_id;
        if($mission->save()) 
        	$status = TRUE;
        $technologies = '' ;
        foreach ($request->technologies as $key => $technologie) {
        	//DB::table('')->insert();
        }
        //$request->skills
        return response()->json(compact('status', 'mission'));
    }

    public function clientUserInsert(Request $request) {
    	$user = new User;
        //$user->name = $request->username;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;
        $user->client_id = $request->client_id;
        $user->status = 'aprouved';
        if(!$user->save())
        	$user = array();
        return response()->json(compact('user'));
    }

    public function clientUserRemove(Request $request) {
    	$user_id = $request->user_id;
    	$user = User::find($user_id);
    	$response = array('status' => FALSE);
		if($user->delete()) 
			$response = array('status' => TRUE);
		return response()->json(compact('response'));
    }

}
