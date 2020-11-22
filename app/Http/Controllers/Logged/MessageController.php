<?php

namespace App\Http\Controllers\Logged;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // SELEZIONE DEI MESSAGGI DELL'UTENTE
        $messages = Message::select('*', 'messages.id as id_msg')
        ->join('apartments', 'messages.apartment_id', '=', 'apartments.id')
        ->where('apartments.user_id', '=', Auth::user()->id)
        ->orderBy('read', 'asc')
        ->get();
        return view('logged.messages',compact('messages'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // HO TOLTO MESSAGE $MESSAGE RICAVARMI LA LISTA MESSAGGI di USER
    public function show(User $user)
    {
       

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
       $message = Message::find($id);
       $read = !$message->read;
      
       if($message->apartment->user_id  == Auth::id()){
              Message::where('id',$id)->update(['read'=> $read]);
              return redirect()->route('messages.index');
       }
       return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {   
        if($message->apartment->user_id = Auth::id()) {
            $message->delete();
            return redirect()->back()->with('status', 'Hai eliminato il messaggio');

        } else {
            return redirect()->back()->with('status', 'messaggio non eliminato');
        }


    }
}
