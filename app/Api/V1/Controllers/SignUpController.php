<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use App\user_score;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpController extends Controller
{
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        $api_token = str_random(60);
        $user->api_token = $api_token;
        $email = User::where('email',$user->email)->first();
        if($email){
            return response()
            ->json([
                "responseCode" => 500,
                'message' => 'Signup Failed!'
            ]);
        }
         if(!$user->save()) {
            throw new HttpException(500);
        }
        if($user->type == "backend"){
        return redirect('/usr');
        }
        if(!Config::get('boilerplate.sign_up.release_token')) {
            $promotion = new user_score;
            $promotion->profile_id = $user->id;
            $promotion->total_score = "50";
            $promotion->api_token = $user->api_token;
            $promotion->save();
           return response()
            ->json([
                "responseCode" => 200,
                'message' => 'Signup Successfuly!',"token"=>$api_token
            ]);
        }

        $token = $JWTAuth->fromUser($user);
        return response()
            ->json([
                "responseCode" => 200,
                'message' => 'Signup Successfuly!',"token"=>$api_token
            ]);
    }
}
