<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\promotion;
use Illuminate\Support\Facades\DB;
use App\User;
use App\user_score;
use Redirect;
use Auth;
use Validator;

use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    public function create() {
        $user = User::find(19);
    	return view('promotionCreate',compact('user'));
    }

    public function editProm($id){
        $promotion = promotion::find($id);
        $user = User::find(19);
        return view('promotionUpdate',compact('promotion','user'));
    }

    public function updateProm(Request $request){
        $promotion = promotion::find($request->id);
        $promotion->title = $request->title;
            $promotion->description = $request->description;
            $promotion->link = $request->link;
            $promotion->score = $request->score;
            $promotion->save();
            return redirect('usr');
    }
    public function delProm($id){
        $promotion = promotion::find($id);
        $promotion->delete();
            return redirect('usr');
    }
    


    public function addpromotion(Request $request)
    {
    	if(isset($request))
    	{
    		$promotion = new promotion();
    		$promotion->title = $request->title;
    		$promotion->description = $request->description;
    		$promotion->link = $request->link;
    		$promotion->score = $request->score;

			if (!empty($request->file)) 
	        {
		        $file = $request->file('file');
		        // dd($file);
	            $ext = $file->getClientOriginalExtension();
	            if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg') 
	            {
	                $newFilename = "_img_" . time() . "_" . $file->getClientOriginalName();

	                $destinationPath = public_path() . '/uploads/promotions/';
	                $file->move($destinationPath, $newFilename);
	                $picPath = $newFilename;
	                $promotion->image = $picPath;
	            }
	        }

	        $promotion->save();
			return Redirect('promotionlist/');
    	}
    }

    public function promotionlist()
    {
    	$promotions = promotion::all();
        $user = User::find(19);
    	return view('promotionlist', compact('promotions','user'));
	}
    public function getPromotion(){
        $promotion = Promotion::all();
        return response()->json(["responseCode" => 200, "message" => "All Promotions","promotion"=> $promotion]);
    }
    public function userScore(Request $request){

        $promotion = user_score::where('profile_id',$request->profile_id)->first();
        if(isset($promotion->api_token)){
        if($promotion->api_token != $request->api_token){
            return response()->json(["responseCode" => 500, "message" => "Api Token Mismatch"]);
        }
        }
        if(isset($promotion->profile_id)){
            $promotion->total_score = $request->scores;
            $promotion->save();
            return response()->json(["responseCode" => 200, "message" => "Score Updated"]);
        }
        else{
            $promotion = new user_score;
            $promotion->profile_id = $request->profile_id;
            $promotion->total_score = $request->scores;
            $promotion->api_token = $request->api_token;
            $promotion->save();
            return response()->json(["responseCode" => 200, "message" => "Score Saved"]);
        }
        
    }


}
