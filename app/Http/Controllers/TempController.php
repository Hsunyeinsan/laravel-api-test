<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TempController extends Controller
{
    public function index(){
        // Cache::add("my_name","hsu nyein san");

        if(Cache::has("my_name")){
            $myname=Cache::get("my_name");
            return response()->json([
                "message"=>"$myname from cached"
            ]);
        }

        $myname="hsu nyein san ";
        Cache::add("my_name",$myname);

        return response()->json([
                "message"=>"$myname is cached"
            ]);

        
    }
    public function destroy(){
        Cache::forget("my_name");

        return response()->json([
                "message"=>"cache forgot",
            ]);
    }
}
