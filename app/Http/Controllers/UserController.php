<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\user_score;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $user = User::find(19);
        return view('userCreate',compact('user'));
    }
     public function change()
    {
         $user = User::find(19);
        return view('userChange',compact('user'));
    }
    public function login(){
        return view('login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;     
        $user->email = $request->email;    
        $password = bcrypt($request->password); 
        $user->password = $request->password;
        $user->save();
        return redirect()->back();     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('hellsadsadsao');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
         $admin = User::find(19);
        return view('editUser',compact('admin','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd('helllo');
    }

     public function userUpdate(Request $request, $id)
    {

       $user = User::find($id);
       $user->name = $request->name;
       $user->save();
       return redirect('/usr');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->destroy();
        return redirect('usr');
    }
     public function del($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back();
    }
    public function userget(){
        $user = User::find(19);
        return view('user',compact('user'));
    }
    public function checkLogin(Request $request){
        dd($this->auth->attempt($request->only('email', 'password')));
        $credentials = $request->only(['email', 'password']);
        $val = $JWTAuth->attempt($credentials);
        dd($val);
    }
    public function updateUser(Request $request){
        $user = User::find(19);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return redirect('/');

    }
    public function changestatus($id){
        $user = User::find($id);
        if($user->status == "on"){
            $user->status = "off";
        }
        else{
            $user->status = "on";
        }
        $user->save();
        return redirect('/usr');
    }
    public function refresApi(Request $request){
        $user = User::where('api_token',$request->invite_token)->first();
        if(empty($user)){
            return response()
            ->json([
                "responseCode" => 500,
                'message' => 'Token mismatch'
            ]);
        }
        $time = round(microtime(true) * 1000);
        $api_token = substr(md5($time), 0, 6);
        $user->api_token = $api_token;
        $user->save();
        $promotion = user_score::where('profile_id',$user->id)->first();
        $promotion->api_token = $api_token;
        $promotion->save();
        return response()
            ->json([
                "responseCode" => 200,
                'message' => 'Token Refresh!',"token"=>$user->api_token,"scores"=>$promotion->total_score
            ]);
    }
}
