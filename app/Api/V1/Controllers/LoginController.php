<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use session;
// use App\User;

class LoginController extends Controller
{
    // public function _construct(){

    // }
    public function login(LoginRequest $request, JWTAuth $JWTAuth)
    {
        $credentials = $request->only(['email', 'password']);

        try {
            $token = $JWTAuth->attempt($credentials);
            $user = $JWTAuth->toUser($token);
            if($user->role == "admin"){
                $value =  session('usr', $user);
                dd($value = session('usr'));
            return view('user');
            }
            if(!$token) {
                throw new AccessDeniedHttpException();
            }

        } catch (JWTException $e) {
            throw new HttpException(500);
        }

        return response()
            ->json([
                'status' => 'ok'
                // 'token' => $token
            ]);

             // return response()->json(["responseCode" => 200, "message" => "Logged in successull", "response" => $user]);
    }
}
