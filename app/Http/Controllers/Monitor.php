<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Visit;


class Monitor extends Controller{
    public function record(Request $request){
    	$visit = new Visit();
    	$visit->save();
    	return Str::random(15);
    }

    public function page(Request $request){
    	return view('monitor');
    }

    public function data(Request $request){
		$limit = (int)$request->query()['limit'] ?? 10;
    	return Visit::orderBy('created_at', 'desc')->limit($limit)->get();
    }

    public function delete(Request $request){
    	return Visit::truncate();
    }
}
