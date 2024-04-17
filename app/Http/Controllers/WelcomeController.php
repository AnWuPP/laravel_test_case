<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $events = Event::all(['id', 'title']);
        $myEvents = Auth::check() ? Auth::user()->events->all(['id', 'title']) : [];
        return view('welcome', ['events' => $events, 'myEvents' => $myEvents]);
    }
}
