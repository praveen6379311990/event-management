<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class AttendeeController extends Controller
{
        //it used for Gate
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    //  public static function middleware(): array
    // {
    //    return [
    //         new Middleware('auth:sanctum', except: ['index','show','update']),
    //     ];
    // }
    public function index(Event $event)
    {
        $attendees =  $event->attendees()->latest();

        return AttendeeResource::collection($attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            "user_id" => 1,
        ]);

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee )
    {
        $this->authorize('delete-attendee',[$event, $attendee]);
        $attendee->delete();

        return response(status:200);
    }
}
