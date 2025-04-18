<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function index(Event $event)
    {
        $attendees = $event->attendees()->latest();

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1,
        ]);
        return new AttendeeResource($attendee);
    }

    public function show(Event $event, $id)
    {
        $attendee = Attendee::find($id);
        if (!$attendee) {
            return response()->json(['message' => 'Attendee not found'], 404);
        }
        return response()->json(['message'=>'Attendee fetched successfully', 'data' => AttendeeResource::make($attendee)]);
        //return new AttendeeResource($attendee);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Event $event, $id)
    {
        $attendee = Attendee::find($id);
        if (!$attendee) {
            return response()->json(['message' => 'Attendee not found'], 404);
        }
        $attendee->delete();
        return response()->json(['message' => 'Attendee deleted successfully']);
    }
}
