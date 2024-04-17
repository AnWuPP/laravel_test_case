<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Show details of an event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $currentEvent = Event::findOrFail($id)->with(['creator', 'participants'])->first();
        $participoned = Auth::check() ? Auth::user()->events->where('id', $id)->count() > 0 : false;
        $events = Event::all(['id', 'title']);
        $myEvents = Auth::check() ? Auth::user()->events->all(['id', 'title']) : [];
        return view('welcome', ['events' => $events, 'currentEvent' => $currentEvent, 'participoned' => $participoned, 'myEvents' => $myEvents]);
    }

    /**
     * Switch participation of an event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function participate($id)
    {
        $event = Event::findOrFail($id);
        if (Auth::user()->events->where('id', $id)->count() > 0) {
            $event->participants()->where('user_id', Auth::user()->id)->delete();
        } else {
            $event->participants()->create([
                'user_id' => Auth::user()->id
            ]);
        }
        return redirect()->route('event', $id);
    }
}
