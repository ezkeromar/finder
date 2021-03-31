<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\RequestClient;

class SearchController extends Controller
{
    public function requestInsert(Request $request) {
    	$status = false;
    	$request_client = new RequestClient;
    	// 'subject', 'consultant_id', 'client_id', 'status'
        $subjects = array(
                    '1' => 'Voir le CV',
                    '2' => "Voir le profile",
                    '3' => "Voir l'image"
        );
        $request_client->subject = $subjects[$request->subject];
        $request_client->consultant_id = $request->consultant_id;
        $request_client->client_id = $request->client_id;
       if($request_client->save()) 
        	$status = TRUE;
        return response()->json(compact('status', 'request_client'));
    }

}
