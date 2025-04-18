<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EventResource::collection(
            Event::with(['user','attendees'])->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
           'name' => 'required|string|max:255',
           'description' => 'nullable|string',
           'start_time' => 'required|date',
           'end_time' => 'required|date|after:start_time',
        ]);

        $validated['user_id'] = 1;
        $event = Event::create($validated);
        if ($event) {
            return response()->json(['message'=>'Event created successfully.', 'data' => new EventResource($event)], 201);
        }
        return response()->json(['message'=>'Unable to create event.'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Event::find($id);

        if ($event != null)
        {
            //return EventResource::collection(Event::with('user')->where('id', $id)->get());
            $event->load('user');
            $event->load('attendees');
            //return new EventResource($event);
            return response()->json(['message' => 'Event fetched successfully','data' => EventResource::make($event)], 200);
        }
        else
            return response()->json(['message' => 'Event not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
        ]);

        $event->update($validated);

        return response()->json([
            'message' => 'Event updated successfully',
            'data' => new EventResource($event)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'],
            );
        }

        $event->delete();
        return response()->json(['message' => 'Event deleted successfully'], 200);
    }
}
