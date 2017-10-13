<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Contracts\Auth\Guard;

use App\Http\Requests\Auth\LoginRequest;

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
        return view('userCreate');
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
        return view('editUser',compact('user'));
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
       return redirect()->back();
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
        return redirect()->back();
    }
     public function del($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back();
    }
    public function userget(){
        return view('user');
    }
    public function checkLogin(Request $request){
        dd($this->auth->attempt($request->only('email', 'password')));
        $credentials = $request->only(['email', 'password']);
        $val = $JWTAuth->attempt($credentials);
        dd($val);
    }
}
