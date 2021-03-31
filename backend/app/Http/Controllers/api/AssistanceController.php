<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Assistance;
use Mail;

class AssistanceController extends Controller
{
    public function getMessages(Request $request) {
    	$messages = Assistance::where(array('user_id' => $request->user_id))->orderBy('created_at', 'ASC')->get();
    	return response()->json(compact('messages'));
    }

    public function sendMessage(Request $request) {
        if($request->user_id && $request->message) {
            $message = new Assistance;
            $message->user_id = $request->user_id;
            $message->message = $request->message;
            $message->user_from = $request->user_from;
            $message->save();
            $messages = Assistance::where(array('user_id' => $request->user_id))->get();
            return response()->json(compact('messages'));
        }
    }

    public function sendContact(Request $request) {
        $name = $request->name; 
        $email = $request->email;
        $message = $request->message;
        $data_old = ['request' => [$name, $email, $message]];
		
        //return response()->json(compact('data'));   
		 $base_url = config('constants.BASE_URL');
        Mail::send('email.send_contact', ['base_url' => $base_url,'name' => $name, 'email' => $email, 'message_text' => $message], function ($m) use ($email, $name) {
                    $m->from($email, $name);
                    $m->to(config('constants.MAIL_FROM'), 'IMZII ')->subject('[IMZII] un message reçu');
					
                });
		Mail::send('email.form_contact', ['base_url' => $base_url,'name' => $name], function ($m) use ($email, $name) {
                    $m->from(config('constants.MAIL_FROM'), $name);
                    $m->to($email, 'imzii.com')->subject('[IMZII] Envoi message contact');
					
                });
        $data = ['message' => 'Votre message a bien été envoyé', 'request' => [$name, $email, $message]];
        //$data = ['message' => 'Votre message a bien été envoyé'];
        return response()->json(compact('data', 'data_old'));   
    }

}
