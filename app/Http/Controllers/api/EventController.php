<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\canLoadRealtionhips;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller

{
    use canLoadRealtionhips;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Event::all();
        $query = $this->loadRealtions(Event::query());



        return EventResource::collection( $query->latest()->paginate(100));
        // return new EventResource($query);
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

        $validatedRequest["user_id"] = $request->user()->id;

        $event = Event::create($validatedRequest);

        // return $event;
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // return $event;
        // return EventResource::make($event);
        // $event->load('user', 'attendees');
        
        return new EventResource($this->loadRealtions($event));
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

        // return $event;
        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(status: 204);

        // return response()->json([
        //     "message"=> "Event is successfully deleted"
        // ]);
    }
}
