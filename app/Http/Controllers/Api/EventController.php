<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class EventController
 * @package App\Http\Controllers\Api
 */
class EventController extends Controller
{
    /**
     * Create a new event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'user_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'result' => null
            ]);
        }
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'creator_id' => $request->user_id,
        ]);
        return response()->json([
            'error' => null,
            'result' => $event,
        ]);
    }

    /**
     * Get all events.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $events = Event::all();
        return response()->json([
            'error' => null,
            'result' => $events,
        ]);
    }

    /**
     * Check if a user is a participant of an event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isParticipant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'result' => null
            ]);
        }
        $event = Event::find($request->event_id);
        if (! $event) {
            return response()->json([
                'error' => 'Event not found',
                'result' => null
            ]);
        }
        if ($event->participants()->byUserId(Auth::id())) {
            return response()->json([
                'error' => null,
                'result' => true
            ]);
        }
        return response()->json([
            'error' => null,
            'result' => false
        ]);
    }

    /**
     * Participate in an event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function participate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'result' => null
            ]);
        }
        $event = Event::find($request->event_id);
        if (! $event) {
            return response()->json([
                'error' => 'Event not found',
                'result' => null
            ]);
        }
        if ($event->participants()->byUserId(Auth::id())) {
            return response()->json([
                'error' => 'You are already a participant of this event',
                'result' => null
            ]);
        }
        $event->participants()->create([
            'user_id' => Auth::id()
        ]);
        return response()->json([
            'error' => null,
            'result' => 'Participated to the event',
        ]);
    }

    /**
     * Un-participate in an event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unParticipate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'result' => null
            ]);
        }
        $event = Event::find($request->event_id);
        if (! $event) {
            return response()->json([
                'error' => 'Event not found',
                'result' => null
            ]);
        }
        if (! $event->participants()->byUserId(Auth::id())) {
            return response()->json([
                'error' => 'You are not a participant of this event',
                'result' => null
            ]);
        }
        $event->participants()->where('user_id', Auth::id())->delete();
        return response()->json([
            'error' => null,
            'event' => 'Un-participated to the event',
        ]);
    }

    /**
     * Get all participants of an event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function participants(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'result' => null
            ]);
        }
        $event = Event::find($request->event_id);
        if (! $event) {
            return response()->json([
                'error' => 'Event not found',
                'result' => null
            ]);
        }
        $participants = $event->participants()->with('user')->get();
        return response()->json([
            'error' => null,
            'result' => $participants,
        ]);
    }
}
