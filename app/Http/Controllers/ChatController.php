<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchmessages()
    {
        return Message::with('user')->get();
    }

    public function sendmessages(Request $request)
    {
       
        $message = auth()->user()->messages()->create([
            'message'=>$request->message
        ]);

       $data =  broadcast(new MessageSent($message->load('user')))->toOthers();    
           
       return response()->json(['success' => true]);


    }
}
