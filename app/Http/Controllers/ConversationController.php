<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Conversation;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversation = Conversation::where('sender_id', auth()->user()->id)->orWhere('recipient_id', '=', auth()->user()->id)->with('sender','recipient')->get();

        if(!empty($conversation)){
            $response = [
                'status' => 'success',
                'data' => [
                    'conversation' => $conversation,
                    // 'sender' => $conversation->sender->name,
                    // 'recipient' => \App\User::find($conversation->recipient_id)
                ]
            ];
        }else{
            $response = [
              'status' => 'error',
              'message' => 'no conversation found'  
            ];
        }
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $conversation = Conversation::where([['sender_id', '=', auth()->user()->id], ['recipient_id', '=', $request->recipient_id]])
                        ->orWhere([['sender_id', '=', $request->recipient_id], ['recipient_id', '=', auth()->user()->id]])
                        ->first();

        if(empty($conversation)){
            $conv = new Conversation;
            $create = $conv->create([
                'sender_id' => auth()->user()->id,
                'recipient_id' => $request->recipient_id
            ]);

            $response = [
                'status' => 'success',
                'message' => $create
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'conversation exists'
            ];
        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($conversation)
    {
        $conversation = Conversation::where('id', $conversation)->with('sender','message','recipient')->first();
        
        if(!empty($conversation)){
            $response = [
                'status' => 'success',
                'data' => [
                    'conversation' => $conversation,
                ]
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'conversation not found'
            ];
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $conversation = Conversation::find($id);

        if(!empty($conversation)){
            $delete = $conversation->delete();
            $response = [
                'status' => 'success',
                'message' => 'conversation delete'
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'conversation not found'
            ];
        }

        return response()->json($response);
    }
}
