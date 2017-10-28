<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use session;
use Illuminate\Http\Request;
use App\User;
use Redirect;
use App\user_score;

class LoginController extends Controller
{
    // public function _construct(){

    // }
    public function login(Request $request, JWTAuth $JWTAuth)
    {
        $credentials = $request->only(['email', 'password']);
        if(isset($request->fb_login)){
            $user = User::where('social_login',$request->fb_login)->first();
            if(isset($user->social_login)){
                $promotion = user_score::where('profile_id',$user->id)->first();
                $time = round(microtime(true) * 1000);
                $api_token = substr(md5($time), 0, 6);
                $user->api_token = $api_token;
                $user->save();
                return response()->json(["responseCode" => 200, "message" => "Logged in Successfuly","token"=>$user->api_token,"scores"=>$promotion->total_score]);
            }
            else{
                $user = new User;
                $user->social_login = $request->fb_login;
                $user->name = $request->name;
                $time = round(microtime(true) * 1000);
                $api_token = substr(md5($time), 0, 6);
                $user->api_token = $api_token;
                $user->save();
                $promotion = new user_score;
                $promotion->profile_id = $user->id;
                $promotion->total_score = "0";
                $promotion->api_token = $user->api_token;
                $promotion->save();
                return response()->json(["responseCode" => 200, "message" => "Logged in Successfuly","token"=>$user->api_token,"scores"=>$promotion->total_score]);
            }
        }

        try {
            $token = $JWTAuth->attempt($credentials);
            if($token){
            $user = $JWTAuth->toUser($token);
            $time = round(microtime(true) * 1000);
            $api_token = substr(md5($time), 0, 6);
            $user->api_token = $api_token;
            $user->save();
            if($user->role == "admin"){
            return redirect('/usr');
            }
            }
            if(!$token) {
                if(isset($request->val) && $request->val == "admin"){
                    return Redirect::back()->withErrors('msg','Your Credentials are nor correct');

                   
                }



                 return response()
                ->json([
                "responseCode" => 500,
                'message' => 'Login Failed!'
            ]);
            }

        } catch (JWTException $e) {
            throw new HttpException(500);
        }
        if($user->status == "off"){
               return response()->json(["responseCode" => 50, "message" => "User Not Active"]);
        }
        $score = user_score::where('profile_id',$user->id)->first();
        return response()
            ->json([
                "responseCode" => 200,
                'message' => 'Login Successfuly!',"token"=>$user->api_token,'scores' => $score->total_score
            ]);

             // return response()->json(["responseCode" => 200, "message" => "Logged in successull", "response" => $user]);
    }
}
