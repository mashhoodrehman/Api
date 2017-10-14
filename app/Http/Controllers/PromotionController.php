<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\promotion;
use Illuminate\Support\Facades\DB;
use App\User;
use Redirect;
use Auth;
use Validator;

use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    public function create() {
    	return view('promotionCreate');
    }

    public function editProm($id){
        $promotion = promotion::find($id);
        return view('promotionUpdate',compact('promotion'));
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

    	return view('promotionlist', compact('promotions'));
	}
}
