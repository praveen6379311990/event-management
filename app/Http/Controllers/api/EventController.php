<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\canLoadRealtionhips;
use App\Models\Event;
// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller

{
    use canLoadRealtionhips;

    //it used for Gate
    // use AuthorizesRequests;

    // public static function middleware(): array
    // {
    //     return [
    //         new Middleware('auth:sanctum', except: ['index', 'show']),
    //     ];
    // }

    // public static function authorizations(): array
    // {
    //     return [
    //         ['model' => Event::class, 'parameter' => 'event']
    //     ];
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        Gate::authorize('viewAny', Event::class);
        // $this->authorize('viewAny', Event::class);
        // return Event::all();
        $query = $this->loadRealtions(Event::query());



        return EventResource::collection($query->latest()->paginate(100));
        // return new EventResource($query);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->user());
        Gate::authorize('create',Event::class);
        $validatedRequest = $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "start_date" => "required|date",
            "end_date" => "required|date|after:start_date"
        ]);

        $validatedRequest["user_id"] = $request->user()->id;

        $event = Event::create($validatedRequest);

        // return $event;
        return new EventResource($this->loadRealtions($event));
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

        // if(Gate::denies('update-event', $event)){
        //     abort(403,'You are not Authorized person');
        // }

        Gate::authorize('update',$event);
        // $this->authorize('update-event', $event);

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
        Gate::authorize('delete',$event);
        $event->delete();

        return response(status: 204);

        // return response()->json([
        //     "message"=> "Event is successfully deleted"
        // ]);
    }
}
