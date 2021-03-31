<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Mission;
use App\Models\Experience;
use App\Models\Technology;
use App\Models\UserMission;
use App\Libraries\Notifications;
use DB;
use Carbon\Carbon;
use Mail;
use App\Http\Requests\MissionCrudRequest;

class MissionController extends Controller
{
    public function missionList(Request $request) {
    	$client_id = $request->client_id;
        $user_id = $request->user_id;
        $loged_user = User::where('id', '=', $user_id)->first();
    	$missionsQuery = Mission::with(['technology', 'skill'])->where('client_id', '=', $client_id)->orderBy('created_at', 'asc');
        if($request->archive != 'archive')
            $missionsQuery->where('is_archived', '=', 0);
        if($loged_user->is_admin == 0)
            $missionsQuery->where('user_id', '=', $loged_user->id);
        $missions = $missionsQuery->paginate(3);
        return response()->json(compact('missions'));
    }

    public function missionConsultantList(Request $request) {
        $user_id = $request->user_id;
        $user = User::where('id', '=', $user_id)->first();
        if($user) {
            $missions = new \StdClass;
            $missions->current = Mission::with(['technology', 'skill', 'client', 'city'])
                                ->join('user_mission', 'missions.id', '=', 'user_mission.mission_id')
                                ->where(array('user_mission.user_id' => $user_id, 'type' => 'consultant'))
                                ->where('status', '<>', 'finished')
                                ->get();
            $missions->finished = Mission::with(['technology', 'skill', 'client', 'city'])
                                ->join('user_mission', 'missions.id', '=', 'user_mission.mission_id')
                                ->where(array('user_mission.user_id' => $user_id, 'type' => 'completed'))
                                ->where('status', '<>', 'finished')
                                ->paginate(2);
        }       
        return response()->json(compact('missions'));
    }

    public function getMissionOnly(Request $request) {
        $mission = Mission::with(['technology', 'skill'])->where('id', '=', $request->mission_id)->first();
        return response()->json(compact('mission'));
    }

    public function getMission(Request $request) {
        $mission = Mission::with(['technology', 'skill'])->where('id', '=', $request->mission_id)->first();
        if($request->archive == 'archive') {
            $mission['completed'] = User::select("id", "picture")->whereHas('usermission', function($query) use($mission) {
                $query->where('mission_id', '=', $mission->id);
                $query->where('type', '=', 'completed');
            })->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))
			->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->get();
        } else {        
            $mission['shortlist'] = User::select("id", "picture")->whereHas('usermission', function($query) use($mission) {
                $query->where('mission_id', '=', $mission->id);
                $query->where('type', '=', 'shortlist');
            })->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))
			->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->get();
			
            $mission['select'] = User::select("id", "picture")->whereHas('usermission', function($query) use($mission) {
                $query->where('mission_id', '=', $mission->id);
                $query->where('type', '=', 'select');
            })->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))
			->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->get();
			
            $mission['consultant'] = User::select("id", "picture")->whereHas('usermission', function($query) use($mission) {
                $query->where('mission_id', '=', $mission->id);
                $query->where('type', '=', 'consultant');
            })->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))
			->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->get();
            $mission['condidat'] = User::select("id", "picture")->whereHas('usermission', function($query) use($mission) {
                $query->where('mission_id', '=', $mission->id);
                $query->where('type', '=', 'candidate');
            })->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))
			->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->get();
        }
        return response()->json(compact('mission'));
    }

    public function likeExperience(Request $request) {
        $mission = Mission::where(['id' => $request->mission_id])->with(['client'])->first();
        $response = ['status' => false, 'message' => 'erreur system'];
        if($mission) {
            $experience = new Experience;
            $experience->user_id = $request->user_id;
            $experience->title = $mission->title;
            $experience->description = $mission->description;
            $experience->date_start = $mission->date_start;
            $experience->date_end = $mission->date_start;
            $experience->tjm = $mission->tjm;
            $experience->client = $mission->client->name;
            $experience->save();
            $response = ['status' => TRUE, 'message' => 'La mission "' . $mission->title . '" est ajouter comme expérience a votre CV'];
        } else {
            $response = ['status' => false, 'message' => 'Mission introuvable'];
        }
        return response()->json(compact('response'));
    }

    public function missionDelete(Request $request) {
    	$mission_id = $request->mission_id;
    	$mission = Mission::find($mission_id);
    	$response = array('status' => FALSE);
        $mission->is_archived = 1;
		if($mission->save()) 
			$response = array('status' => TRUE);
		return response()->json(compact('response'));
    }

    public function missiontoggle(Request $request) {
        $mission_id = $request->mission_id;
        $mission = Mission::find($mission_id);
        $response = array('status' => FALSE);
        if($mission->status == 'current'){
            $mission->status = 'finished';
		    $etat=false;
        }else{
            $mission->status = 'current';
			 $etat=true;
		}
        if($mission->save()) 
            $response = array('status' => TRUE,'etat'=>$etat);
        return response()->json(compact('response'));
    }

    public function missionAdd(Request $request) {
	
    	$status = false;
        if(empty($request->id))
        	$mission = new Mission;
        else {
            DB::table('mission_technology')->where('mission_id', '=', $request->id)->delete();
            DB::table('mission_skill')->where('mission_id', '=', $request->id)->delete();
            $mission = Mission::find($request->id);
        }
        $mission->title = $request->title;
        $mission->description = $request->description;
        $mission->duration = $request->duration;
        $mission->tjm = $request->tjm;
        $mission->status = 'planned';
        $mission->user_id = $request->user_id;
        $mission->date_start = Carbon::createFromFormat('d/m/Y', $request->date_start)->toDateString();
        $mission->city_id = $request->city_id;
        $mission->client_id = $request->client_id;
        $mission->rate = 0;
        if($mission->save()) 
        	$status = TRUE;
        $technologies = array();
        foreach ($request->technologies as $key => $tech_id) {
        	$mission->technology()->attach($tech_id);
        }
        foreach ($request->skills as $key => $skill_id) {
        	$mission->skill()->attach($skill_id);
        }
        return response()->json(compact('status', 'mission'));
    }

    public function missionPublish(Request $request) {
    	$user = new User;
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

    public function addUserToMission(Request $request) {
	    
        $user_mission = new UserMission;
        $user_mission->user_id = $request->user_id;
        $user_mission->mission_id = $request->mission_id;
        if($request->type == 1) {
            $user_mission->type = "shortlist";
        } else if($request->type == 0) {
            $user_mission->type = "select";
        } else if($request->type == 2) {
            $user_mission->type = "candidate";
        }
        $desc_notif_consultant = "";
        $um = UserMission::where('user_id', '=', $request->user_id)->where('mission_id', '=', $request->mission_id)->first();
        if(empty($um)) {
            if($user_mission->save()) {
                if($request->type == 1) {
                    // notification consultant
                    $mission = Mission::find($user_mission->mission_id);
                    $notification = new Notifications();
                    $desc_notif_consultant = "Vous avez affecté à la mission \"{$mission->title}\"";
                    $params_consultant = array(
                            'user_id' => $request->user_id, 
                            'description' => $desc_notif_consultant, 
                            'type' => 'consultant'
                        );
                    $notification->add($params_consultant);
					
					$consultant_info = User::find($request->user_id);
					$name = $consultant_info->firstname . " " . $consultant_info->lastname;
					$email = $consultant_info->email;
					$title = $mission->title;
					$base_url = config('constants.BASE_URL');
					Mail::send('email.affectation_mission', ['base_url' => $base_url,'name' => $name,'title'=>$title], function ($m) use ($email, $name) {
                    $m->from(config('constants.MAIL_FROM'), 'IMZII ');
                    $m->to($email, $name)->subject("[IMZII] L'affectation d'une mission");
					// end 
                });
                }
                return response()->json(['status'=>'seccuess', 'notification' => $desc_notif_consultant]);
            }
        } else {
            return response()->json(['status'=>'seccuess', 'test' => 'hello']);
        }
    }

    public function UpdateConsultentSelection(Request $request) {
        foreach ($request->shortlist as $key => $shortlist) {
            $um = UserMission::where('user_id', '=', $shortlist)->where('mission_id', '=', $request->mission)->first();
            if(!empty($um) && $um->type != 'consultant')
                $um->update(['type' => 'shortlist']);
        }
        foreach ($request->select as $key => $selected) {
            $um = UserMission::where('user_id', '=', $selected)->where('mission_id', '=', $request->mission)->first();
            if(!empty($um) && $um->type != 'consultant')
                $um->update(['type' => 'select']);
        }
        foreach ($request->condidat as $key => $condidat) {
            $um = UserMission::where('user_id', '=', $condidat)->where('mission_id', '=', $request->mission)->first();
            if(!empty($um) && $um->type != 'candidate')
                $um->update(['type' => 'candidate']);
        }
        foreach ($request->consultant as $key => $consultant) {
            $mission = Mission::find($request->mission);
            $notification = new Notifications();
            $desc_notif_consultant = "La mission \"{$mission->title}\" vous avez affecté à été commencé";
            $params_consultant = array(
                    'user_id' => $consultant, 
                    'description' => $desc_notif_consultant, 
                    'type' => 'consultant'
                );
            $notification->add($params_consultant);
			
			$consultant_info = User::find($consultant);
			$name = $consultant_info->firstname . " " . $consultant_info->lastname;
			$email = $consultant_info->email;
			$base_url = config('constants.BASE_URL');
			Mail::send('email.start_mission', ['base_url' => $base_url,'name' => $name], function ($m) use ($email, $name) {
                    $m->from(config('constants.MAIL_FROM'), 'IMZII ');
                    $m->to($email, $name)->subject('[IMZII] La mission commence');
                });
		    // end 
            $um = UserMission::where('user_id', '=', $consultant)->where('mission_id', '=', $request->mission)->update(['type' => 'consultant']);
        }
        return response()->json(['status' => 'success']);
    }

    public function DeleteConsultentSelection(Request $request) {
        $um = UserMission::where('user_id', '=', $request->consultant)->where('mission_id', '=', $request->mission)->delete();
        return response()->json(['status' => 'success']);
    }

    public function search(Request $request) {
        $query = 
        Mission::with(['technology', 'skill', 'client'])->where('missions.status', '<>', 'planned');
        if(!empty($request->tjmmax)) {
            $query->where('missions.tjm', '<=', $request->tjmmax);
        }
        if(!empty($request->tjmmin)) {
            $query->where('missions.tjm', '>=', $request->tjmmin);
        }
        if(!empty($request->disponibilities)) {
            foreach ($request->disponibilities as $key => $disponibility) {
                if($disponibility == 1)
                    $query->where('missions.date_start', '<=', Carbon::now()->toDateString());
                if($disponibility == 2)
                    $query->where('missions.date_start', '<=', Carbon::now()->addDays(15)->toDateString());
                if($disponibility == 3)
                    $query->where('missions.date_start', '<=', Carbon::now()->addDays(30)->toDateString());
                if($disponibility == 4)
                    $query->where('missions.date_start', '>', Carbon::now()->addDays(30)->toDateString());
            }
        }
        if(!empty($request->searchTerm)) {
            $query->where('missions.title', 'LIKE', '%'.$request->searchTerm.'%');
            $query->orWhere(function($qu) use($request) {
                $qu->where('missions.description', 'LIKE', '%'.$request->searchTerm.'%');
                $qu->whereHas('skill', function($q) use($request){
                    $q->where('title', 'LIKE', '%'.$request->searchTerm.'%');
                });
                $qu->whereHas('city', function($q) use($request){
                    $q->where('title', 'LIKE', '%'.$request->searchTerm.'%');
                });
                $qu->whereHas('technology', function($q) use($request){
                    $q->where('title', 'LIKE', '%'.$request->searchTerm.'%');
                });
            });
        }
        $query->where('status', '!=', 'finished');
        $missions = $query->paginate(2);
        return response()->json(compact('missions'));
    }
	

    public function lastMissions(Request $request) {
    	/*$missionsQuery = Mission::with(['technology', 'skill'])->orderBy('created_at', 'asc');
		$missionsQuery->where('is_archived', '=', 0);
        $missionsQuery->where('status', '!=', 'finished');//->where('missions.status', '<>', 'planned');
        $missions = $missionsQuery->limit(4)->get();
        return response()->json(compact('missions'));*/
		
		$missions = Mission::with(['technology', 'skill', 'client', 'city'])
                                ->where('status', '!=', 'finished')
                                //->join('user_mission', 'missions.id', '=', 'user_mission.mission_id')
                                ->offset(0)
                                ->limit(5)
                                ->orderBy('missions.created_at', 'desc')
                                ->get();
        return response()->json(compact('missions'));
		
    }

    /**
    CRON
    */
    public function expired() {
        /*$date_expired = new \DateTime();
        echo 'date before day adding: ' . $date_expired->format('Y-m-d'); 
        $date_expired->modify('+30 day');
        echo '<br>date after adding 30 day: ' . $date_expired->format('Y-m-d H:i:s');
        echo '<br>year after adding 30 day: ' . $date_expired->format('Y');
        echo '<br>month after adding 30 day: ' . $date_expired->format('m');
        echo '<br>day after adding 30 day: ' . $date_expired->format('d');*/
        $missions = Mission::where(['status' => 'current'])->get();
        $now = Carbon::now();
        $notification = new Notifications();
        foreach ($missions as $key => $mission) {
            echo "<br>=======<br>";
            $date_start = new Carbon($mission->date_start);
            $nbr_days = $date_start->diff($now)->days;
            $diff_days = $mission->duration - $nbr_days;
            echo "mission title = {$mission->title} ; date start = {$mission->date_start} ; nbr days = $nbr_days ; ";
            echo "diff days = $diff_days";
            echo "<br>=======<br>";
            if($diff_days == 30) {
                $notification->add_mission_notifs($mission);
            }
        }
        dd($missions);
    }

}
