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
                return response()->json(["responseCode" => 200, "message" => "Logged in successull","token"=>$user->api_token]);
            }
            else{
                $user = new User;
                $user->social_login = $request->fb_login;
                $user->name = $request->name;
                $api_token = str_random(60);
                $user->api_token = $api_token;
                $user->save();
                return response()->json(["responseCode" => 200, "message" => "Logged in successull","token"=>$user->api_token]);
            }
        }

        try {
            $token = $JWTAuth->attempt($credentials);
            if($token){
            $user = $JWTAuth->toUser($token);
            if($user->role == "admin"){
            return redirect('/usr');
            }
            }
            if(!$token) {
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

        return response()
            ->json([
                "responseCode" => 200,
                'message' => 'Login Successfuly!',"token"=>$user->api_token
            ]);

             // return response()->json(["responseCode" => 200, "message" => "Logged in successull", "response" => $user]);
    }
}
