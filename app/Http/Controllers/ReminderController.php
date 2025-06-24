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
        return view('reminders.index', compact('reminders', 'truck'));
    }

    public function create(Truck $truck)
{
    $allTypes = ['SIM', 'STNK', 'KIR'];
    $usedTypes = $truck->reminders->pluck('document_type')->toArray();
    $availableTypes = array_diff($allTypes, $usedTypes);

    return view('reminders.create', compact('truck', 'availableTypes'));
}

    public function store(Request $request, Truck $truck)
    {
        $request->validate([
            'document_type' => 'required|string|in:STNK,SIM,KIR',
            'deadline' => 'required|date',
        ]);

        // Only one reminder per document per truck
        $reminder = $truck->reminders()->firstOrNew([
            'document_type' => $request->document_type,
        ]);

        $reminder->deadline = $request->deadline;

        // Google Calendar event
        if ($reminder->google_event_id) {
            // Update existing event
            $event = Event::find($reminder->google_event_id);
            if ($event) {
                $event->name = "{$request->document_type} Reminder for Truck {$truck->plat_no}";
                $event->startDate = Carbon::parse($request->deadline);
                $event->endDate = Carbon::parse($request->deadline);
                $event->save();
            }
        } else {
            // Create new event
            $event = new Event();
            $event->name = "{$request->document_type} Reminder for Truck {$truck->plat_no}";
            $event->startDate = Carbon::parse($request->deadline);
            $event->endDate = Carbon::parse($request->deadline);
            $googleEvent = $event->save();
            $reminder->google_event_id = $googleEvent->id;
        }

        $reminder->save();

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Reminder saved and synced with Google Calendar.');
    }

    public function edit(Truck $truck, Reminder $reminder)
    {
        return view('reminders.edit', compact('truck', 'reminder'));
    }

    public function update(Request $request, Truck $truck, Reminder $reminder)
    {
        $request->validate([
            'deadline' => 'required|date',
        ]);

        $reminder->deadline = $request->deadline;
        $reminder->save();

        // Update Google Calendar Event
        if ($reminder->google_event_id) {
            $event = Event::find($reminder->google_event_id);
            if ($event) {
                $event->name = "{$reminder->document_type} Reminder for Truck {$truck->plat_no}";
                $event->startDate = Carbon::parse($request->deadline);
                $event->endDate = Carbon::parse($request->deadline);
                $event->save();
            }
        }

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Reminder updated and synced with Google Calendar.');
    }

    public function destroy(Truck $truck, Reminder $reminder)
    {
        // Delete Google Calendar Event
        if ($reminder->google_event_id) {
            $event = Event::find($reminder->google_event_id);
            if ($event) {
                $event->delete();
            }
        }

        $reminder->delete();

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Reminder deleted and removed from Google Calendar.');
    }

    // Add this method for AJAX renewal
    public function renew(Reminder $reminder)
    {
        $newDeadline = Carbon::now()->addYears(5);
        $reminder->deadline = $newDeadline;
        $reminder->renewed_at = now();
        $reminder->save();

        // Update Google Calendar event
        if ($reminder->google_event_id) {
            $event = Event::find($reminder->google_event_id);
            if ($event) {
                $event->startDate = $newDeadline;
                $event->endDate = $newDeadline;
                $event->save();
            }
        }

        return response()->json(['success' => true]);
    }
}