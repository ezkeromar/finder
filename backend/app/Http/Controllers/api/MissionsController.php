<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Technology;
use App\Models\Mission;
use App\Models\Skill;
use App\Models\City;
use App\Models\RequestContact;
use Carbon\Carbon;
use DB;

class MissionsController extends Controller
{
    public function aprouveclient($id) {
    	$client = Client::find($id);
    	$client->status = 'aprouved';
    	$client->save();
    	$users = User::where('client_id', '=', $id)->update(['status' => 'aprouved']);
        return redirect("/admin/client");
    }

    public function banneclient($id) {
    	$client = Client::find($id);
    	$client->status = 'banned';
    	$client->save();
    	$users = User::where('client_id', '=', $id)->update(['status' => 'banned']);
        return redirect("/admin/client");
    }

}
