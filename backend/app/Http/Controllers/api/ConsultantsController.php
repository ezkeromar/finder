<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\News;
use App\Models\Client;
use App\Models\Technology;
use App\Models\Mission;
use App\Models\Experience;
use App\Models\Certification;
use App\Models\Skill;
use App\Models\Diploma;
use App\Models\Country;
use App\Models\UserDiploma;
use App\Models\UserMission;
use App\Models\City;
use App\Models\Secteur;
use App\Models\Rate;
use App\Models\RequestContact;
use Carbon\Carbon;
use App\Models\Notification;
use App\Libraries\Notifications;
use DB;
use Storage;
use Cache;
use Mail;

class ConsultantsController extends Controller
{
    public function confirmRdv($rdv, $id) {
        $requestRdv = RequestContact::find($id);
        $consultant = User::find($requestRdv->consultant_id); //$requestRdv->consultant();
        $client = Client::find($requestRdv->client_id); //$requestRdv->client();
        //$la_date = Carbon::createFromFormat('d/m/Y', $requestRdv->date_start)->toDateString();
        $la_date = $requestRdv->date_start;
        //dd($requestRdv);
        //dd($consultant);
        $consultant_name = $consultant->firstname . " " . $consultant->lastname;
        $desc_notif_client = $desc_notif_consultant = '';
        if($rdv == 1 && !is_null($client)) {
            $requestRdv->status = 'confirmed';
            $desc_notif_consultant =  " \"{$client->name}\" a fixer une rendez-vous avec vous pour la date " . $la_date;
            $desc_notif_client = "Votre rendez-vous avec le consultant \"$consultant_name\" pour la date " . $la_date ." à été confirmé par l'équipe Imzii ";
        }
        elseif($rdv == 2) {
            $tempDate = $requestRdv->date_end;
            $requestRdv->date_end = $requestRdv->date_start;
            $requestRdv->date_start = $tempDate;
            $requestRdv->status = 'confirmed';
            $desc_notif_consultant = " \"{$client->name}\" a fixer une rendez-vous avec vous pour la date " . $la_date;
            $desc_notif_client = "Votre rendez-vous avec le consultant \"$consultant_name\" pour la date " . $la_date ." à été confirmé par l'équipe Imzii ";
        } elseif ($rdv == 3) {
            $requestRdv->status = 'rejected';
            $desc_notif_consultant = "Le rendez-vous avec \"{$client->name}\" pour la date " . $la_date . " à été refusé";
            $desc_notif_client = "Votre rendez-vous avec le consultant \"$consultant_name\" pour la date " . $la_date ." à été refusé par l'équipe Imzii ";
        }
        $requestRdv->save();
        $notification = new Notification();
        $params_consultant = array(
                'user_id' => $consultant->id, 
                'description' => $desc_notif_consultant, 
                'type' => 'consultant'
            );
        $params_client = array(
                'user_id' => $requestRdv->client_id, 
                'description' => $desc_notif_client, 
                'type' => 'client'
            );
        $notification->add($params_client);
        $notification->add($params_consultant);
        return redirect("/admin/request_contact");
    }

    public function AddRate(Request $request) {
        $user_mission = UserMission::where('user_id', '=', $request->consultent_id)->where('mission_id', '=', $request->mission_id)->where('type', '=', 'consultant')->first();
        
		$user_mission->type = 'completed';
        $user_mission->save();
        $rate = new Rate;
        $rate->satisfaction = $request->satisfaction;
        $rate->competance = $request->competance;
        $rate->methodology = $request->methodology;
        $rate->reach_goal = $request->reach_goal;
        $rate->respect_details = $request->respect_details;
        $rate->respect_rules = $request->respect_rules;
        $rate->qualities = $request->qualities;
        $rate->consultent_id = $request->consultent_id;
        $rate->mission_id = $request->mission_id;
        if($rate->save()) {
            $mission = Mission::find($request->mission_id);
            $notification = new Notification();
            $desc_notif_consultant = "Vous avez un taux d'évaluation pour la mission \"{$mission->title}\" ";
            $params_consultant = array(
                    'user_id' => $request->consultent_id, 
                    'description' => $desc_notif_consultant, 
                    'type' => 'consultant'
                );
				
            $notification->add($params_consultant);
			
			
			$consultant_info = User::find($request->consultent_id);
			
			$client = User::find($mission->user_id);
			
					$name = $consultant_info->firstname . " " . $consultant_info->lastname;
					$email = $consultant_info->email;
					$base_url = config('constants.BASE_URL');
					$name_client = $client->name;
					Mail::send('email.evaluation_mail', ['base_url' => $base_url,'name' => $name,'name_client'=>$name_client], function ($m) use ($email, $name) {
                    $m->from(config('constants.MAIL_FROM'), 'IMZII ');
                    $m->to($email,$name )->subject("[IMZII] Avoir une évaluation");
					 });
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function UpdateClient(Request $request) {
        $client = Client::find($request->is_client);
        if(!empty($client)){
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->city_id = $request->city;
            if(!empty($request->logo)) {
                $pict = '/uploads/clients/'.md5(uniqid(rand(), true)).'.jpg';
                Storage::disk('uploads')->put($pict, file_get_contents($request->logo));
                $client->logo = $pict;
            }
            $client->save();
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function adduserToClient(Request $request) {
        $user_exit = User::where(array('email' => $request->email))->get();
        if(count($user_exit) != 0) {
            return response()->json(['status' => 'error', 'message' => 'Cet adresse mail est déjà existe'], 200);
        }
        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->client_id = $request->is_client;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;
        $user->note = 0;
        $user->linkedin_id = '';
        $user->profession = $request->position;
        $user->status = 'aprouved';
        if(!empty($request->logo)) {
            $pict = 'uploads/users/images/'.md5(uniqid(rand(), true)).'.jpg';
            Storage::disk('uploads')->put($pict, file_get_contents($request->logo));
            $user->picture = $pict;
        }
        $user->save();
        return response()->json(['status' => 'success'], 200);
    }

    public function searchfront() {
        $consultants = User::select('users.id', 'users.name', 'users.profession', 'users.picture', 'users.tjm')->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->where('show_home', '=', 1)->limit(8)->inRandomOrder()->get();
        return response()->json(compact('consultants'), 200);
    }

    public function newsfront() {
        $news = News::where('state', '=', 1)->limit(10)->get();
        return response()->json(compact('news'), 200);
    }
	
	private function getParams(Request $request) {
		$key = '';
        if(!empty($request->secteur)) {
			$key .= $request->secteur;
        }
		
        if(!empty($request->tjmmax)) {
			$key .= $request->tjmmax;
        }
		
        if(!empty($request->tjmmin)) {
			$key .= $request->tjmmin;
        }
		
        if(!empty($request->skills)) {
			$key .= $request->skills;
        }
		
        if(!empty($request->technologies)) {
			$key .= $request->technologies;
        }
		
        if(!empty($request->disponibilities)) {
			$key .= $request->disponibilities;
        }

        if(!empty($request->seniorities)) {
			$key .= $request->seniorities;
        }
		
        if(!empty($request->rates)) {
			$key .= $request->rates;
        }
		
        if(!empty($request->searchTerm)) {
			$key .= $request->searchTerm;
        }
        if(!empty($request->page)) {
			$key .= $request->page;
        }
		
		return md5($key);
	}

    public function search_elastic(Request $request) { 
        //$consultants = User::where('users.profession', 'LIKE', '%'.$request->searchTerm.'%')->paginate(12);
		//$consultants = User::search($request->searchTerm)->select('users.id', 'users.name', 'users.profession', 'users.picture', 'users.tjm')->where('users.client_id', '=', 0)->where('users.status', '=', 'aprouved')->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->paginate(12);
		//$consultants = User::search($request->searchTerm)->with(['attributesToRetrieve' => ['firstname', 'lastname','users.id', 'name', 'profession', 'picture', 'tjm'], 'hitsPerPage' => 12])->paginate(12);
		//$consultants = User::search($request->searchTerm)->where('users.secteur', 1)->paginate(12);
		$consultant_ids = User::search($request->searchTerm)->get()->pluck('id');//->paginate(12);
        //return response()->json(compact('consultants'));
		//, ['attributesToRetrieve' => ['firstname', 'lastname',], 'hitsPerPage' => 12]
		
		
		
		//DB::raw('LOCATE("' . $request->searchTerm . '", users.profession), year_start_experience')
    	//$query = User::select('users.id', 'users.name', 'users.profession', 'users.picture', 'users.tjm')->where('users.client_id', '=', 0)->where('users.status', '=', 'aprouved')->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->orderBy($raw_db);
		$query = User::search($request->searchTerm)->select(DB::raw("users.id, users.name, users.profession, users.picture, users.tjm, MATCH (profession) AGAINST ('" . $request->searchTerm . "' IN BOOLEAN MODE) as relavance"))->where('users.client_id', '=', 0)->where('users.status', '=', 'aprouved')->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->orderBy("relavance", "desc")->orderBy("year_start_experience");
        if(!empty($request->secteur)) {
            $query->whereHas('secteur', function($q) use($request) {
                $q->where('id', '=', $request->secteur);
            });
        }
		/*
        if(!empty($request->tjmmax)) {
            $query->where('users.tjm', '<=', $request->tjmmax);
        }
		
        if(!empty($request->tjmmin)) {
            $query->where('users.tjm', '>=', $request->tjmmin);
        }
		*/
        if(!empty($request->skills)) {
            $query->join('user_skill', function($join) {
              $join->on('users.id', '=', 'user_skill.user_id');
            })->whereIn('user_skill.skill_id', $request->skills);
        }
        if(!empty($request->technologies)) {
            $query->join('user_technology', function($join) {
              $join->on('users.id', '=', 'user_technology.user_id');
            })->whereIn('user_technology.technology_id', $request->technologies);
        }
		
        if(!empty($request->disponibilities)) {
            foreach ($request->disponibilities as $key => $disponibility) {
                if($key == 0) {
                    if($disponibility == 1)
                        $query->where('users.disponibility_date', '<=', Carbon::now()->toDateTimeString());
                    if($disponibility == 2)
                        $query->where('users.disponibility_date', '<=', Carbon::now()->addDays(15)->toDateTimeString())->where('users.disponibility_date', '>', Carbon::now()->addDays(0)->toDateTimeString());
                    if($disponibility == 3)
                        $query->where('users.disponibility_date', '<=', Carbon::now()->addDays(30)->toDateTimeString())->where('users.disponibility_date', '>', Carbon::now()->addDays(15));
                    if($disponibility == 4)
                        $query->where('users.disponibility_date', '>', Carbon::now()->addDays(30)->toDateTimeString());
                } else {
                    if($disponibility == 1)
                        $query->orWhere('users.disponibility_date', '<=', Carbon::now()->toDateTimeString());
                    if($disponibility == 2)
                        $query->orWhere('users.disponibility_date', '<=', Carbon::now()->addDays(15)->toDateTimeString())->where('users.disponibility_date', '>', Carbon::now()->addDays(0)->toDateTimeString());
                    if($disponibility == 3)
                        $query->orWhere('users.disponibility_date', '<=', Carbon::now()->addDays(30)->toDateTimeString())->where('users.disponibility_date', '>', Carbon::now()->addDays(15));
                    if($disponibility == 4)
                        $query->orWhere('users.disponibility_date', '>', Carbon::now()->addDays(30)->toDateTimeString());
                }
            }
        }

        if(!empty($request->seniorities)) { 
            $query->where(function($qu) use($request) {
                $firstwhere=false;
                foreach ($request->seniorities as $key => $seniority) {
                    if($seniority == 1){
                        if($firstwhere == false) {
                            $qu->whereBetween('users.year_start_experience', [Carbon::now()->year-3, Carbon::now()->year]);
                            $firstwhere = true;
                        }
                        else {
                            $qu->orWhereBetween('users.year_start_experience', [Carbon::now()->year-3, Carbon::now()->year]);
                        }
                    }
                    if($seniority == 2) {
                        if($firstwhere == false) {
                            $qu->whereBetween('users.year_start_experience', [Carbon::now()->year-5, Carbon::now()->year-3]);
                            $firstwhere = true;
                        } else {
                            $qu->orWhereBetween('users.year_start_experience', [Carbon::now()->year-5, Carbon::now()->year-3]);
                        }
                    }
                    if($seniority == 3) {
                        if($firstwhere == false) {
                            $qu->whereBetween('users.year_start_experience', [Carbon::now()->year-10, Carbon::now()->year-5]);
                            $firstwhere = true;
                        } else {
                            $qu->orWhereBetween('users.year_start_experience', [Carbon::now()->year-10, Carbon::now()->year-5]);
                        }
                    }
                    if($seniority == 4) {
                        if($firstwhere == false) {
                            $qu->where('users.year_start_experience', '<', Carbon::now()->year-10);
                            $firstwhere = true;
                        } else {
                            $qu->orWhere('users.year_start_experience', '<', Carbon::now()->year-10);
                        }
                    }
                }
            });
        }
        $query->leftJoin('missions', function($join) {
              $join->on('users.id', '=', 'missions.user_id');
            })->addSelect(DB::raw('AVG(missions.rate) as rate'));
        if(!empty($request->rates)) {
            foreach ($request->rates as $key => $rate) {
                if($rate == 1)
                    $query->where('rate', '=', 1);
                if($rate == 2)
                    $query->where('rate', '=', 2);
                if($rate == 3)
                    $query->where('rate', '=', 3);
            }
        }
        $query->groupBy('users.id', 'users.firstname', 'users.lastname', 'users.name', 'users.profession', 'users.picture', 'users.email', 'users.tjm');
        if(!empty($request->searchTerm)) {
			//$query->search($request->searchTerm);
        }
        // =========
		/*
        $client_id = $request->client_id;
        $query->leftJoin('user_mission', function($join) {
              $join->on('missions.id', '=', 'user_mission.mission_id');
            })
            //->orWhereHas('missions.client_id' , '=', $request->client_id)

            ->orWhereHas('usermission', function($q) use($client_id){
                        //$q->where('user_mission.mission_id' , '=', $client_id);
                        $q->leftJoin('missions', function($join) use($client_id) {
                          $join->on('missions.id', '=', 'user_mission.mission_id');
                        })->where('missions.client_id', '=', $client_id);
                        //->addSelect(DB::raw('COUNT(mission_id) as missions_number'));
                    })
            ->addSelect(DB::raw('COUNT(mission_id) as missions_number'));
		*/
		$query->whereIn('id', $consultant_ids);
        // ==========
        $consultants = $query->paginate(12);
        return response()->json(compact('consultants'));
    }

    public function search(Request $request) {
		/*
		$key = $this->getParams($request);
		//var_dump($key);
		if(Cache::has($key)) {
			$consultants = Cache::get($key);
		} else {*/

		$consultant_ids = User::search($request->searchTerm)->take(5000)->get()->pluck('id');
		//var_dump($consultant_ids->toArray());
		//$raw_db = DB::raw("CASE WHEN users.profession LIKE '%" . $request->searchTerm . "%' THEN 1 ELSE 2 END");
		//DB::raw('LOCATE("' . $request->searchTerm . '", users.profession), year_start_experience')
    	//$query = User::select('users.id', 'users.name', 'users.profession', 'users.picture', 'users.tjm')->where('users.client_id', '=', 0)->where('users.status', '=', 'aprouved')->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->orderBy($raw_db);
		//$query = User::select(DB::raw("users.id, users.name, users.profession, users.picture, users.tjm, MATCH (profession) AGAINST ('" . $request->searchTerm . "' IN BOOLEAN MODE) as relavance"))->where('users.client_id', '=', 0)->where('users.status', '=', 'aprouved')->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->orderBy("relavance", "desc")->orderBy("year_start_experience");
		
		$query = User::select(DB::raw("users.id, users.name, users.profession, users.picture, users.tjm"))->where('users.client_id', '=', 0)->where('users.status', '=', 'aprouved')->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'));//->orderBy("year_start_experience");
        if(!empty($request->secteur)) {
            $query->whereHas('secteur', function($q) use($request) {
                $q->where('id', '=', $request->secteur);
            });
        }
		
        if(!empty($request->tjmmax)) {
            $query->where('users.tjm', '<=', $request->tjmmax);
        }
		
        if(!empty($request->tjmmin)) {
            $query->where('users.tjm', '>=', $request->tjmmin);
        }
		
        if(!empty($request->skills)) {
            $query->join('user_skill', function($join) {
              $join->on('users.id', '=', 'user_skill.user_id');
            })->whereIn('user_skill.skill_id', $request->skills);
        }
        if(!empty($request->technologies)) {
            $query->join('user_technology', function($join) {
              $join->on('users.id', '=', 'user_technology.user_id');
            })->whereIn('user_technology.technology_id', $request->technologies);
        }
		
        if(!empty($request->disponibilities)) {
            $query->where(function($qu) use($request) {
            foreach ($request->disponibilities as $key => $disponibility) {
                if($key == 0) {
                    if($disponibility == 1)
                        $qu->where('users.disponibility_date', '<=', Carbon::now()->toDateTimeString());
                    if($disponibility == 2)
                        $qu->where('users.disponibility_date', '<=', Carbon::now()->addDays(15)->toDateTimeString())->where('users.disponibility_date', '=>', Carbon::now()->addDays(0)->toDateTimeString());
                    if($disponibility == 3)
                        $qu->where('users.disponibility_date', '<=', Carbon::now()->addDays(30)->toDateTimeString())->where('users.disponibility_date', '=>', Carbon::now()->addDays(15));
                    if($disponibility == 4)
                        $qu->where('users.disponibility_date', '=>', Carbon::now()->addDays(30)->toDateTimeString());
                } else {
                    if($disponibility == 1)
                        $qu->orWhere('users.disponibility_date', '<=', Carbon::now()->toDateTimeString());
                    if($disponibility == 2)
                        $qu->orWhere('users.disponibility_date', '<=', Carbon::now()->addDays(15)->toDateTimeString())->where('users.disponibility_date', '=>', Carbon::now()->addDays(0)->toDateTimeString());
                    if($disponibility == 3)
                        $qu->orWhere('users.disponibility_date', '<=', Carbon::now()->addDays(30)->toDateTimeString())->where('users.disponibility_date', '=>', Carbon::now()->addDays(15));
                    if($disponibility == 4)
                        $qu->orWhere('users.disponibility_date', '=>', Carbon::now()->addDays(30)->toDateTimeString());
                }
            }
            });
        }

        if(!empty($request->seniorities)) { 
            $query->where(function($qu) use($request) {
                $firstwhere=false;
                foreach ($request->seniorities as $key => $seniority) {
                    if($seniority == 1){
                        if($firstwhere == false) {
                            $qu->whereBetween('users.year_start_experience', [Carbon::now()->year-3, Carbon::now()->year]);
                            $firstwhere = true;
                        }
                        else {
                            $qu->orWhereBetween('users.year_start_experience', [Carbon::now()->year-3, Carbon::now()->year]);
                        }
                    }
                    if($seniority == 2) {
                        if($firstwhere == false) {
                            $qu->whereBetween('users.year_start_experience', [Carbon::now()->year-5, Carbon::now()->year-3]);
                            $firstwhere = true;
                        } else {
                            $qu->orWhereBetween('users.year_start_experience', [Carbon::now()->year-5, Carbon::now()->year-3]);
                        }
                    }
                    if($seniority == 3) {
                        if($firstwhere == false) {
                            $qu->whereBetween('users.year_start_experience', [Carbon::now()->year-10, Carbon::now()->year-5]);
                            $firstwhere = true;
                        } else {
                            $qu->orWhereBetween('users.year_start_experience', [Carbon::now()->year-10, Carbon::now()->year-5]);
                        }
                    }
                    if($seniority == 4) {
                        if($firstwhere == false) {
                            $qu->where('users.year_start_experience', '<=', Carbon::now()->year-10);
                            $firstwhere = true;
                        } else {
                            $qu->orWhere('users.year_start_experience', '<=', Carbon::now()->year-10);
                        }
                    }
                }
            });
        }
        $query->leftJoin('missions', function($join) {
              $join->on('users.id', '=', 'missions.user_id');
            })->addSelect(DB::raw('AVG(missions.rate) as rate'));
        if(!empty($request->rates)) {
            foreach ($request->rates as $key => $rate) {
                if($rate == 1)
                    $query->where('rate', '=', 1);
                if($rate == 2)
                    $query->where('rate', '=', 2);
                if($rate == 3)
                    $query->where('rate', '=', 3);
            }
        }
        $query->groupBy('users.id', 'users.firstname', 'users.lastname', 'users.name', 'users.profession', 'users.picture', 'users.email', 'users.tjm');
        /*
		if(!empty($request->searchTerm)) {
			//$query->search($request->searchTerm);
			
            $search_terms = explode(' ', $request->searchTerm);
            foreach ($search_terms as $key => $search_term) {
                $query->where(function($qu) use($search_term) {
                	$qu->where(DB::raw("MATCH (users.profession, users.cv_content) AGAINST ('" . $search_term . "')")) //users.profession', 'LIKE', '%'.$search_term.'%');
                    //$qu->orWhere('users.cv_content', 'LIKE', '%'.$search_term.'%')
					->orWhereHas('skill', function($q) use($search_term){
                        $q->where('title', 'LIKE', '%'.$search_term.'%');
                    })->orWhereHas('technology', function($q) use($search_term){
                        $q->where('title', 'LIKE', '%'.$search_term.'%');
                    })->orWhereHas('diploma', function($q) use($search_term){
                        $q->where('title', 'LIKE', '%'.$search_term.'%');
                    })->orWhereHas('experience', function($q) use($search_term){
                        $q->where('title', 'LIKE', '%'.$search_term.'%');
                        $q->orWhere('description', 'LIKE', '%'.$search_term.'%');
                        $q->orWhere('client', 'LIKE', '%'.$search_term.'%');
                    })->orWhereHas('experience.technology', function($q) use($search_term){
                        $q->where('title', 'LIKE', '%'.$search_term.'%');
                    })->orWhereHas('experience.skill', function($q) use($search_term){
                        $q->where('title', 'LIKE', '%'.$search_term.'%');
                    });
                });
            }
        }*/
        // =========
		/*
        $client_id = $request->client_id;
        $query->leftJoin('user_mission', function($join) {
              $join->on('missions.id', '=', 'user_mission.mission_id');
            })
            //->orWhereHas('missions.client_id' , '=', $request->client_id)

            ->orWhereHas('usermission', function($q) use($client_id){
                        //$q->where('user_mission.mission_id' , '=', $client_id);
                        $q->leftJoin('missions', function($join) use($client_id) {
                          $join->on('missions.id', '=', 'user_mission.mission_id');
                        })->where('missions.client_id', '=', $client_id);
                        //->addSelect(DB::raw('COUNT(mission_id) as missions_number'));
                    })
            ->addSelect(DB::raw('COUNT(mission_id) as missions_number'));
					*/
        // ==========
		//var_dump(implode(',', $consultant_ids->toArray()));
		$query->whereIn('users.id', $consultant_ids->toArray())->orderByRaw(\DB::raw("FIELD(users.id," . implode(',', $consultant_ids->toArray()) . ")"));
        $consultants = $query->paginate(12);
		//Insert to cache
		//Cache::forever($key, $consultants);
		//}
        return response()->json(compact('consultants'));
    }

    public function consultent(Request $request) {
        $query = User::with(['experience', 'userdiplome.diploma', 'certif', 'technology', 'skill'])->select('users.id', 'users.name', 'users.profession', 'users.picture', 'users.email', 'users.tjm', 'address', 'users.curriculum_vitae')->where('users.id', '=', $request->id)->addSelect(DB::raw('UPPER(LEFT(users.firstname, 1)) as firstname'))->addSelect(DB::raw('UPPER(LEFT(users.lastname, 1)) as lastname'))->addSelect(DB::raw('DATEDIFF(disponibility_date, CURDATE()) AS exp_days'));
        $query->leftJoin('cities', function($join) {
              $join->on('users.city_id', '=', 'cities.id');
            })->addSelect(DB::raw('cities.title AS city'));
        $query->leftJoin('countries', function($join) {
              $join->on('users.country_id', '=', 'countries.id');
            })->addSelect(DB::raw('LOWER(countries.code) AS country'));
        $query->leftJoin('missions', function($join) {
              $join->on('users.id', '=', 'missions.user_id');
            })->addSelect(DB::raw('AVG(missions.rate) as rate'));
        //$query->groupBy('users.id', 'users.firstname', 'users.lastname', 'users.name', 'users.profession', 'users.picture', 'users.email', 'users.tjm', 'address', 'users.curriculum_vitae', 'users.disponibility_date');
        $query->groupBy('users.id', 'users.firstname', 'users.lastname', 'users.name', 'users.profession', 'users.picture', 'users.email', 'users.tjm', 'address', 'users.curriculum_vitae', 'users.disponibility_date', 'cities.title', 'missions.rate', 'countries.code');
        $query->leftJoin('experience', function($join) {
                  $join->on('users.id', '=', 'experience.user_id');
                })->addSelect(DB::raw('MAX(experience.date_end) AS end_date'));
        $query->leftjoin('user_skill', function($join) {
            $join->on('users.id', '=', 'user_skill.user_id');
        });

        
        $query->leftjoin('user_technology', function($join) {
            $join->on('users.id', '=', 'user_technology.user_id');
        });

        $consultent = $query->first();
        return response()->json(compact('consultent'));
    }

    public function skills() { 
        $skills = Skill::orderBy('title')->get(['id as value', 'title as label']);
        return response()->json(compact('skills'));
    }

    public function diplomas() { 
        $diplomas = Diploma::get(['id as value', 'title as label']);
        return response()->json(compact('diplomas'));
    }

    public function technologies() {
        $technologies = Technology::get(['id as value', 'title as label']);
        $technologies = Technology::orderBy('title')->get(['id as value', 'title as label']);
        return response()->json(compact('technologies'));
    }

    public function countries() {
        $countries = Country::get(['id as value', 'name as label']);
        return response()->json(compact('countries'));
    }

    public function cities() {
        $cities = City::get(['id as value', 'title as label']);
        return response()->json(compact('cities'));
    }

    public function secteurs() {
        $secteurs = Secteur::orderBy('title')->get(['id as value', 'title as label']);
        return response()->json(compact('secteurs'));
    }

    public function missions(Request $request) {
        $user_id = $request->user_id;
        $loged_user = User::where('id', '=', $user_id)->first();
        $missionsQuery = Mission::where('client_id', '=', $request->client_id)->where('is_archived', '=', 0);
        if($loged_user->is_admin == 0)
            $missionsQuery->where('user_id', '=', $loged_user->id);
        $missions = $missionsQuery->get(['id as value', 'title as label']);
        return response()->json(compact('missions'));
    }

    public function requestContactInsert(Request $request) {
        $status = FALSE;
        $request_contact = new RequestContact;
        $request_contact->mission_id = $request->mission_id;
        $request_contact->consultant_id = $request->consultant_id;
        $request_contact->client_id = $request->client_id;
        $request_contact->status = 'sent';
        $min_end = $request->min_end;
        $min_start = $request->min_start; 
        if(strlen($request->min_end) < 2)
            $min_end = '0'.$request->min_end;
        if(strlen($request->min_start) < 2)
            $min_start = '0'.$request->min_start;
        $request_contact->date_end = Carbon::createFromFormat('d/m/Y H:i', $request->date_end.' '.$request->hour_end.':'.$min_end)->toDateTimeString();
        $request_contact->date_start = Carbon::createFromFormat('d/m/Y H:i', $request->date_start.' '.$request->hour_start.':'.$min_start)->toDateTimeString();
        if($request_contact->save()) {
            $status = TRUE;
        }
        
        return response()->json(compact('status', 'request_contact'));        
    }

    public function edit(Request $request) {
        $consultentInfo = json_decode($request->consultentInfo);
        //return response()->json(['result' => $consultentInfo->country->label]);
        
        
        /*$pict = 'uploads/users/images/'.md5(uniqid(rand(), true)).'.jpg';
        Storage::disk('uploads')->put($pict, file_get_contents($request->logo));

        return response()->json(['result' => $request->logo]);*/

        $consultant_info = json_decode($request->consultentInfo);
        $experience = $consultant_info->experience;
        $userdiplome = $consultant_info->userdiplome;
        $certifs = $consultant_info->certif;
        $technologies = $consultant_info->technology;
        $skills = $consultant_info->skill;
        $country = $consultant_info->country->label;
        
        $status = false; $message = "Erreur système";
        $consultant_id = $consultant_info->id;
        $consultant = User::find($consultant_id);
        if(!empty($request->logopict)) {
            $pict = 'uploads/users/images/'.md5(uniqid(rand(), true)).'.jpg';
            Storage::disk('uploads')->put($pict, file_get_contents($request->logopict));
            User::where(['id' => $consultant_id])->update(['picture' => $pict]);
            return response()->json(['result' => $consultant->picture]);
        }
        //return response()->json(['consultent' => $consultant]);
        if($consultant) {
            
            $consultant->firstname = $consultant_info->firstname;
            $consultant->lastname = $consultant_info->lastname;
            $consultant->phone = $consultant_info->phone;
            //$consultant->email = $consultant_info->email;
            $consultant->address = $consultant_info->address;
            $consultant->tjm = $consultant_info->tjm;
            $consultant->country_id = $consultant_info->country->value;
            $consultant->profession = $consultant_info->profession;
            //if(!empty($request->logo)) {
                /*$pict = 'uploads/users/images/'.md5(uniqid(rand(), true)).'.jpg';
                Storage::disk('uploads')->put($pict, file_get_contents($request->logo));
                $consultant->picture = $pict;
                return response()->json(['result' => $pict]);*/
            //}
            /*if($disp_date = $consultant_info['disponibility_date'])
                $consultant->disponibility_date = 
                    Carbon::createFromFormat('d/m/Y', $disp_date)->toDateString();*/
            if($consultant->save()) {
                $status = TRUE;
                foreach ($experience as $key => $value) {
                    if(!isset($value->id)) {
                        $raw_exp = new Experience;
                        $raw_exp->user_id = $consultant_id;
                        $raw_exp->title = $value->title;
                        $raw_exp->client = $value->client;
                        $raw_exp->date_start = Carbon::createFromFormat('d/m/Y', $value->date_start)->toDateString();
                        $raw_exp->date_end = Carbon::createFromFormat('d/m/Y', $value->date_end)->toDateString();
                        $raw_exp->description = $value->title;
                        $raw_exp->save();
                    } else {
                        $raw_exp = Experience::where(['id' => $value->id])->first();
                        $raw_exp->title = $value->title;
                        $raw_exp->client = $value->client;
                        $raw_exp->date_start = Carbon::createFromFormat('Y-m-d', $value->date_start)->toDateString();
                        $raw_exp->date_end = Carbon::createFromFormat('Y-m-d', $value->date_end)->toDateString();
                        $raw_exp->description = $value->title;
                        $raw_exp->save();
                    }
                }
                foreach ($userdiplome as $key => $value) {
                    if(!isset($value->id) && isset($value->diploma_id->value)) {
                        $raw_diploma = new UserDiploma;
                        $raw_diploma->user_id = $consultant_id;
                        $raw_diploma->diploma_id = $value->diploma_id->value;
                        $raw_diploma->year = '2012'; //$value->year;
                        $raw_diploma->school = $value->school;
                        $raw_diploma->speciality = $value->speciality;
                        $raw_diploma->save();
                    }
                }
                foreach ($certifs as $key => $value) {
                    if(!isset($value->id)) {
                        $certif = new Certification;
                        $certif->user_id = $consultant_id;
                        $certif->title = $value->title;
                        $certif->certif = $value->certif;
                        $certif->save();
                    }
                }
                // ==== insert technologies ===
                $consultant->technology()->detach();
                $consultant_technologies = array_column($technologies, 'value');
                $consultant->technology()->attach($consultant_technologies);
                // ============================
                // ==== insert skills ===
                $consultant->skill()->detach();
                $consultant_skills = array_column($skills, 'value');
                $consultant->skill()->attach($consultant_skills);
                // ============================
                $message = "Votre profil a été mis à jour avec succés";
            }
        } else {
            $message = "Compte introuvable!!";
        }
        $consultant_data = User::with(['experience'])->find($consultant_id);
        $data = array('status' => $status, 'message' => $message, 'experience' => $consultant->experience);
        return response()->json(compact('data'));
    }

    public function requestContactList(Request $request) {
        $client_id = $request->client_id;
        $mission_id = $request->mission_id;
        $user_id = $request->user_id;
        $loged_user = User::where('id', '=', $user_id)->first();
        if($client_id) {
            $agenda_list_query = Mission::with(['requestContact.consultant'])->whereHas('requestContact', function($q) use($client_id) {
                $q->where('client_id', '=', $client_id);
                $q->where('status', '=', 'confirmed');
            });    
        } else {
            $agenda_list_query = Mission::with(['requestContact.consultant'])->whereHas('requestContact', function($q) use($user_id) {
                $q->where('consultant_id', '=', $user_id);
                $q->where('status', '=', 'confirmed');
            });
        }
        
        if($loged_user->is_admin == 0)
            $agenda_list_query->where('user_id', '=', $loged_user->id);
        if(!empty($mission_id))
            $agenda_list_query->where('id', '=', $mission_id);
        $agenda_list = $agenda_list_query->get();
        $data = [];
        foreach ($agenda_list as $key => $value) {
            foreach ($value->requestContact as $key => $requestContact) {
                $d = [];
                $d['date'] = [];
                $d['title'] = $value->title;
                $d['class'] = 'rdv1';
                $d['desc'] = "Consultant: ";
                if(!empty($requestContact->consultant)) {
                    $d['desc'] .= $requestContact->consultant->firstname." ";
                    $d['desc'] .= $requestContact->consultant->lastname." |";
                }
                if(!empty($value->requestContact)) {
                    $dt = Carbon::parse($requestContact->date_start);
                    $d['desc'] .= " Heure: ".$dt->hour."h ".$dt->minute. "min";
                    $d['date'] = $dt->format('Y/m/d');
                }
                array_push($data, $d);
            }
        }
        return response()->json(compact('status', 'data'));
    }


    public function experienceRemove(Request $request) {
        $experience_id = $request->experience_id;
        $experience = Experience::find($experience_id);
        $data = array('status' => FALSE, 'message' => '');
        if($experience->delete()) 
            $data = array('status' => TRUE, 'message' => 'Expérience supprimé avec succés');
        return response()->json(compact('data'));
    }

    public function diplomaRemove(Request $request) {
        $diploma_id = $request->diploma_id;
        $diploma = UserDiploma::find($diploma_id);
        $data = array('status' => FALSE, 'message' => '');
        if($diploma->delete()) 
            $data = array('status' => TRUE, 'message' => 'Diplôme supprimé avec succés');
        return response()->json(compact('data'));
    }

    public function certifRemove(Request $request) {
        $certif_id = $request->certif_id;
        $certif = Certification::find($certif_id);
        $data = array('status' => FALSE, 'message' => '');
        if($certif->delete()) 
            $data = array('status' => TRUE, 'message' => 'Certification supprimé avec succés');
        return response()->json(compact('data'));
    }


}
