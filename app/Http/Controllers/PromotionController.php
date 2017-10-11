<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function create() {
    	return view('promotionCreate');
    }
}
