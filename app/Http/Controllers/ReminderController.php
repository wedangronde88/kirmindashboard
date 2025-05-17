<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Truck;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
class ReminderController extends Controller
{
    public function index(Truck $truck)
    {
        $reminders = $truck->reminders;
    
        if (request()->wantsJson()) {
            return response()->json($reminders->map(function ($reminder) {
                return [
                    'id' => $reminder->id,
                    'title' => "{$reminder->document_type} Reminder",
                    'start' => $reminder->remind_from,
                    'description' => "Repeats every {$reminder->remind_every} years",
                ];
            }));
        }
    
        return view('reminders.index', compact('reminders', 'truck'));
    }

    public function create(Truck $truck)
    {
        return view('reminders.create', compact('truck'));
    }

    public function store(Request $request, Truck $truck)
{
    $request->validate([
        'document_type' => 'required|string', // Validate document type
        'remind_from' => 'required|date',
        'remind_every' => 'required|integer|min:1', // Number of years
    ]);

    // Create the reminder in the database
    $reminder = $truck->reminders()->create($request->all());

    // Add the event to Google Calendar
    $event = new Event();
    $event->name = "{$request->document_type} Reminder for Truck {$truck->plat_no}";
    $event->startDate = Carbon::parse($request->remind_from);
    $event->endDate = Carbon::parse($request->remind_from);
    $event->recurrence = ["RRULE:FREQ=YEARLY;INTERVAL={$request->remind_every}"]; // Recurring event
    $googleEvent = $event->save();

    // Save the Google event ID to the reminder
    $reminder->update(['google_event_id' => $googleEvent->id]);

    return redirect()->route('trucks.show', $truck->id)->with('success', 'Reminder added successfully and synced with Google Calendar.');
}
    public function edit(Truck $truck, Reminder $reminder)
    {
        return view('reminders.edit', compact('truck', 'reminder'));
    }

    public function update(Request $request, Truck $truck, Reminder $reminder)
    {
        $request->validate([
            'remind_from' => 'required|date',
            'remind_every' => 'required|integer|min:1', // Number of years
        ]);
    
        $reminder->update($request->all());
    
        // Update Google Calendar Event (if applicable)
        if ($reminder->google_event_id) {
            $event = Event::find($reminder->google_event_id);
            $event->name = "Reminder for Truck {$truck->plat_no}";
            $event->startDate = Carbon::parse($request->remind_from); // Convert to Carbon instance
            $event->endDate = Carbon::parse($request->remind_from);   // Convert to Carbon instance
            $event->save();
        }
    
        return redirect()->route('trucks.show', $truck->id)->with('success', 'Reminder updated successfully and synced with Google Calendar.');
    }
public function destroy(Truck $truck, Reminder $reminder)
{
    // Delete Google Calendar Event (if applicable)
    if ($reminder->google_event_id) {
        $event = Event::find($reminder->google_event_id);
        if ($event) {
            $event->delete();
        }
    }

    $reminder->delete();

    return redirect()->route('trucks.show', $truck->id)->with('success', 'Reminder deleted successfully and removed from Google Calendar.');
}
}