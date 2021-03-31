<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Mission;
use App\Models\UserMission;
use DB;
use Carbon\Carbon;

use App\Http\Requests\MissionCrudRequest;

class ProfileController extends Controller
{
    public function clientInfo(Request $request) { 
    	$id = $request->client_id;
        $user_id = $request->user_id;
        $loged_user = User::where('id', '=', $user_id)->first();
        $client_info = Client::with(['city'])->where('id', '=', $id)->first();
        if(empty($client_info)) {
        	$return = array();
        	return response()->json(compact('return'));
        }
        $client_info->users = User::where(array('client_id' => $id))->get();
        if($loged_user->is_admin == 1) {
            $client_info->missions = Mission::with(['technology', 'skill'])->where('client_id', '=', $id)->limit(3)->get();
        } else {
            $client_info->missions = Mission::with(['technology', 'skill'])->where('is_archived', '=', 0)->where('user_id', '=', $loged_user->id)->where('client_id', '=', $id)->limit(3)->get();
        }
        return response()->json(compact('client_info'));
    }

    public function loadconsultant(Request $request) {
        $consultent_info = User::where(array('id' => $request->user_id))->first();
        return response()->json(compact('consultent_info'));
    }

    public function consultentInfo(Request $request) {
        $id = $request->user_id;
        $consultent_info = User::with(['city', 'experience', 'userdiplome', 'certif', 'country', 'technology', 'skill'])->where(array('id' => $id))->first();
        if(!$consultent_info) {
            $return = array();
            return response()->json(compact('return'));
        }
        //$consultent_info->users = User::where(array('client_id' => $id))->get();
        $consultent_info->missions_current = Mission::with(['technology', 'skill', 'client', 'city'])
                            ->join('user_mission', 'missions.id', '=', 'user_mission.mission_id')
                            ->where(array('user_mission.user_id' => $id, 'type' => 'consultant'))
                            ->get();
        $consultent_info->missions_finished = Mission::with(['technology', 'skill', 'client', 'city'])
                            ->join('user_mission', 'missions.id', '=', 'user_mission.mission_id')
                            ->where(array('user_mission.user_id' => $id, 'type' => 'completed'))
                            ->paginate(2);
        foreach ($consultent_info->technology as $key => $value) {
            $consultent_info->technology[$key]->value = $value->id;
            $consultent_info->technology[$key]->label = $value->title;
        }
        foreach ($consultent_info->skill as $key => $value) {
            $consultent_info->skill[$key]->value = $value->id;
            $consultent_info->skill[$key]->label = $value->title;
        }
        $country = $consultent_info->country;
        unset($consultent_info->country);
        $consultent_info->country->value = $country->id;
        $consultent_info->country->label = $country->name;
        return response()->json(compact('consultent_info'));
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
