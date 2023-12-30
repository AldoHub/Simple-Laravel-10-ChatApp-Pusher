<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//add the pusher event
use App\Events\PusherBroadcast;



class PusherController extends Controller
{
    //

    public function index(){
        return view('index');
    }

    public function broadcast(Request $request){
       
        //pusher broadcast event with the message
        broadcast(new PusherBroadcast($request->get('message')))->toOthers();
        //return the message
        return view('broadcast', ['message' => $request->get("message")]);
       
    }

    public function receive(Request $request){

        return view('receive', ['message' => $request->get("message")]);
    }

}
