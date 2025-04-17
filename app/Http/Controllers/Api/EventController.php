<?php

namespace App\Http\Controllers\Api;

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
        $validated = $request->validate([
           'name' => 'required|string|max:255',
           'description' => 'nullable|string',
           'start_time' => 'required|date',
           'end_time' => 'required|date|after:start_time',
        ]);

        $validated['user_id'] = 1;
        $event = Event::create($validated);
        return $event;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Event::find($id);

        if ($event != null)
            return $event;
        else
            return response()->json(['message' => 'Event not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
            ])
        );
        return $event;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $deleting = $event->delete();
        return response($deleting, 204);


        //return response()->json([$deleting]);
    }
}
