<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notifications;

class NotificationsController extends Controller
{
    public function getList(Request $request) { 
    	$notifications = Notifications::where(array('user_id' => $request->user_id, 'type' => $request->type_profile))->get();
    	$notif_read = Notifications::where(array('user_id' => $request->user_id, 'type' => $request->type_profile, 'is_read' => '0'))->get();
    	$notif_count = count($notif_read);
        return response()->json(compact('notifications', 'notif_count'));
    }

    public function read(Request $request) {
    	if($request->user_id) {
    		Notifications::where(array('user_id' => $request->user_id))->update(['is_read' => 1]);
            $notifications = Notifications::where(array('user_id' => $request->user_id))->get();
            $notif_read = Notifications::where(array('user_id' => $request->user_id, 'is_read' => '0'))->get();
            $notif_count = count($notif_read);
	    	return response()->json(compact('notifications', 'notif_count'));
    	}
    	$response = array('status' => false, 'message' => 'Non connecté');
    	return response()->json(compact('response'));
    }

}
