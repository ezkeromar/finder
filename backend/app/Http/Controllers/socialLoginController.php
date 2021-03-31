<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Socialite;
use App\Models\User;
use App\Models\Country;
use Storage;
use Auth;
use DB;
use Redirect;
use JWTAuth;

class socialLoginController extends Controller
{
    public function linkedinLogin() {
    	return Socialite::driver('linkedin')->redirect();
    }

    public function handleLinkedinCallback() {
    	try {
            $user = Socialite::driver('linkedin')->user();
            $userModel = User::where('email', "=", $user->email)->whereNull('linkedin_id')->first();
            if(!empty($userModel)) {
                return Redirect::away(env('FRONT_URL')."auth/signin");
            }
            $userModel = User::where('linkedin_id', "=", $user->id)->first();
            if(empty($userModel)) {
	            $userModel = new User;
	            $userModel->name = $user->name;
	            $userModel->firstname = $user->user["firstName"];
		        $userModel->lastname = $user->user["lastName"];
		        $userModel->profession = $user->user['industry'];
		        $userModel->status = "aprouved";
		        // $userModel->profession = $User->headline;
	            $userModel->email = $user->email;
                $userModel->linkedin_id = $user->id;
                if(!empty($user->user['location']['country']['code'])) {
                	$country = Country::where('code', "=", $user->user['location']['country']['code'])->first();
                	if(!empty($country))
                		$userModel->country_id = $country->id;
                }
	            $userModel->save();
	            $pict = '/uploads/clients/'.md5(uniqid(rand(), true)).'.jpg';
                Storage::disk('uploads')->put($pict, file_get_contents($user->avatar_original));
                DB::statement("UPDATE users SET picture = '".$pict."' WHERE id = ".$userModel->id);
                $userModel->save();
	        }
            Auth::loginUsingId($userModel->id);
            $token = JWTAuth::fromUser(Auth::user());
            // return redirect('/');
            return Redirect::away(env('FRONT_URL')."auth/linkedin/loged?token=".$token);
        } catch (\Exception $e) {
            // return redirect('auth/linkedin');
            return Redirect::away(env('FRONT_URL')."auth/signin");
        }
    }
}
