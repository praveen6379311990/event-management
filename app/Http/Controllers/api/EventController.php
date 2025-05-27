<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Event::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedRequest = $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "start_date" => "required|date",
            "end_date" => "required|date|after:start_date"
        ]);

        $validatedRequest["user_id"] = 1;

        $event = Event::create($validatedRequest);

        return $event;
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return $event;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update($request->validate([
             "name" => "sometimes|string|max:255",
            "description" => "nullable|string",
            "start_date" => "sometimes|date",
            "end_date" => "sometimes|date|after:start_date"
        ]));

        return $event;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(status:204);

        // return response()->json([
        //     "message"=> "Event is successfully deleted"
        // ]);
    }
}
