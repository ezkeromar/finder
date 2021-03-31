<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Client;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;


class AuthController extends Controller
{
    public function profile() {
        $data['user'] = auth()->user();
        if(!empty($data['user']->client_id))
            $data['client'] = Client::find($data['user']->client_id);
        return response()->json(compact('data'));
    }

    public function linkedinlogin(Request $request) {
        $token = $request->token;
        JWTAuth::setToken($token);
        JWTAuth::authenticate();
        $data['user'] = JWTAuth::parseToken()->toUser();
        // $token = JWTAuth::getToken();
        return response()->json(compact('token', 'data'));
    }

    public function resetpass(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if(!empty($user)) {
            $token = JWTAuth::fromUser($user);
            $base_url = config('constants.BASE_URL');
            $data['token'] = $token;
            $data['email'] = $user->email;
			
            Mail::send('email.resetpass', ['base_url' => $base_url, 'name' => $user->firstname, 'email' => $user->email, 'link' => $token], function ($m) use ($user) {
                    $name = $user->firstname . " " . $user->lastname;
                    $m->from(config('constants.MAIL_FROM'), 'IMZII ');
                    $m->to($user->email, $name)->subject('[IMZII] Rénitialiser mon mot de passe');
                });
            return response()->json(compact('data'));
        } else {
            return response()->json(['token' => null]);
        }
    }

    public function updatepass(Request $request) {
        JWTAuth::setToken($request->token);
        $user = JWTAuth::authenticate();
        $user->password = bcrypt($request->password);
        if($user->save())
            return response()->json(['state' => 1]);
        else
            return response()->json(['state' => 0]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $data['user'] = auth()->user();
        if($data['user']->status == 'aprouved') {
            if(!empty($data['user']->client_id))
                $data['client'] = Client::find($data['user']->client_id);
            return response()->json(compact('token', 'data'));
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }

    public function register(Request $request)
    {	
        if($request->is_client == 1){
            $client = new Client;
            $client->name = $request->brand;
            $client->email = $request->email;
            $client->phone = $request->phone;
			$client->status ='';
            $client->save();
            $user = new User;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->phone = $request->phone;
            $user->client_id = $client->id;
            $user->status = 'current';
            $user->note = 0;
            $user->save();
            $data= $client;
            $base_url = config('constants.BASE_URL');
            Mail::send('email.welcome_client', ['base_url' => $base_url,'name' => $user->firstname, 'email' => $user->email], function ($m) use ($user) {
                    $m->from(config('constants.MAIL_FROM'), 'imzii.ma');
                    $m->to($user->email, 'IMZII')->subject('[IMZII] Votre Compte va être confirmé soon');
                });

            return response()->json(compact('data'));
            
        }

        if($request->is_client == 0){
			
            $user_test = User::where(array('email' => $request->email))->first();
            if(!empty($user_test)) {
				
                $data = array('status' => false, 'message' => "Cet email déjà existe",'user'=>null);
				
                return response()->json(['token' =>null,'data'=>$data]);
            }
            $user = new User;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->phone = $request->phone;
            $user->note = 0;
            $user->status = 'current';
            $user->save();
        
            $credentials = $request->only('email', 'password');

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            $base_url = $_SERVER['HTTP_ORIGIN'];
            Mail::send('email.welcome_user', ['name' => $user->firstname, 'email' => $user->email, 'base_url' => $base_url, 'link' => $token], function ($m) use ($user) {
                    $m->from(config('constants.MAIL_FROM'), 'IMZII');
                    $m->to($user->email, 'IMZII')->subject('[IMZII] Bienvenu dans notre siteweb');
                });

            $data['user'] = auth()->user();
            return response()->json(compact('data'));
        }
    }

    public function aprouveConsultant(Request $request) {
		
        JWTAuth::setToken($request->token);
		$user = JWTAuth::authenticate();
        $user->status = 'aprouved';
        if($user->save())
            return response()->json(['state' => 1]);
        else
            return response()->json(['state' => 0]);
    }

    public function logout() {
        auth()->logout();
        return response()->json();
    }
}