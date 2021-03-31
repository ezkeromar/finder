<?php

namespace App\Libraries;

use DB;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserMission;
use App\Models\Client;
use Mail;


class Notifications {


	public function add($params = array()) {
		$notification = new Notification();
		$notification->is_read = (isset($params['is_read'])) ? $params['is_read'] : false;
		$notification->description = (isset($params['description'])) ? $params['description'] : '';
		$notification->user_id = (isset($params['user_id'])) ? $params['user_id'] : 0;
		$notification->type = (isset($params['type'])) ? $params['type'] : '';
		$notification->save();
		//$this->send_email($notification);
	}

	public function add_mission_notifs($mission) {
		//$data = count($mission->user());
		//$data = $mission->client->name;
		echo $mission->id;
		//dd($mission);
		$users = User::where(['user_mission.mission_id' => $mission->id])
					->join('user_mission', 'user_mission.user_id', '=', 'users.id')
					->get();
		// save client notification
		$message = "Votre mission \"{$mission->title}\" sera expiré dans 30 jours ";
        $notification = new Notifications();
		$notification->description = $message;
		$notification->user_id = $mission->client_id;
		$notification->type = 'client';
		$notification->save();
		//hiba
		$client_info = User::find($mission->client_id);
			$name = $client_info->firstname . " " . $client_info->lastname;
			$email = $client_info->email;
			$id_mission = $mission->id;
			Mail::send('email.expiry_mission', ['name' => $name,'id_mission'=>$id_mission], function ($m) use ($email, $name) {
                    $m->from('hibaamarir95@gmail.com', $name);
                    $m->to($email, 'imzii.com')->subject('[IMZII] L\'éxpiration de votre mission');
                });
		foreach ($users as $key => $user) {
			$message = "La mission \"{$mission->title}\" sera expiré dans 30 jours ";
            $notification = new Notifications();
			$notification->description = $message;
			$notification->user_id = $user->user_id;
			$notification->type = 'consultant';
			$notification->save();
			//hiba
			$consultant_info = User::find($user->user_id);
			$name = $consultant_info->firstname . " " . $consultant_info->lastname;
			$email = $consultant_info->email;
			$id_mission = $mission->id;
			Mail::send('email.expiry_mission', ['name' => $name,'id_mission'=>$id_mission], function ($m) use ($email, $name) {
                    $m->from('hibaamarir95@gmail.com', $name);
                    $m->to($email, 'imzii.com')->subject('[IMZII] L\'éxpiration de votre mission');
                });
				
		}
		//dd($users);
	}

	public function send_email($notification) {
		if($notification->type == 'consultant') {
			$user = User::find($notification->user_id);
	        $name = $user->firstname . ' ' . $user->lastname; 
	        $email = $user->email;	
		} else if($notification->type == 'client') {
			$client = Client::find($notification->user_id);
	        $name = $client->name; 
	        $email = $client->email;
		}		
        $message = $notification->description;
        //$data_old = ['request' => [$name, $email, $message]];
        //return response()->json(compact('data'));   
        Mail::send('email.send_notification', ['name' => $name, 'email' => $email, 'message_text' => $message], function ($m) use ($email, $name) {
                    $m->from('contact@imzii.com', 'Imzii');
                    $m->to($email, $name)->subject('[Imzii.ma] Notification');
                });
        //$data = ['message' => 'Votre message a bien été envoyé', 'request' => [$name, $email, $message]];
        //$data = ['message' => 'Votre message a bien été envoyé'];
        //return response()->json(compact('data', 'data_old'));   
    }

}


?>