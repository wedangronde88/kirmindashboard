<?php
namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    // Show create reminder form
    public function create(Truck $truck)
    {
        $allTypes = ['STID', 'KIR', 'STNK', 'PKB', 'Plat Nomor'];
        $usedTypes = $truck->reminders->pluck('document_type')->toArray();
        $availableTypes = array_diff($allTypes, $usedTypes);

        return view('reminders.create', compact('truck', 'availableTypes'));
    }

    // Store new reminder
    public function store(Request $request, Truck $truck)
    {
        $request->validate([
            'document_type' => 'required|string|in:STID,KIR,STNK,PKB,Plat Nomor',
            'deadline' => 'required|date',
        ]);

        $reminder = $truck->reminders()->firstOrNew([
            'document_type' => $request->document_type,
        ]);
        $reminder->deadline = $request->deadline;
        $reminder->save();

        // Optionally: Sync with Google Calendar here

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Reminder saved.');
    }

    // Show edit form
    public function edit(Truck $truck, Reminder $reminder)
    {
        $allTypes = ['STID', 'KIR', 'STNK', 'PKB', 'Plat Nomor'];
        return view('reminders.edit', compact('truck', 'reminder', 'allTypes'));
    }

    // Update reminder
    public function update(Request $request, Truck $truck, Reminder $reminder)
    {
        $request->validate([
            'document_type' => 'required|string|in:STID,KIR,STNK,PKB,Plat Nomor',
            'deadline' => 'required|date',
        ]);

        $reminder->document_type = $request->document_type;
        $reminder->deadline = $request->deadline;
        $reminder->save();

        // Optionally: Sync with Google Calendar here

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Reminder updated.');
    }

    // Delete reminder
    public function destroy(Truck $truck, Reminder $reminder)
    {
        $reminder->delete();
        return redirect()->route('trucks.show', $truck->id)->with('success', 'Reminder deleted.');
    }

    // Renew reminder (AJAX)
    public function renew(Reminder $reminder)
    {
        $now = Carbon::now();
        switch ($reminder->document_type) {
            case 'STID':
                $newDeadline = $now->addYears(2);
                break;
            case 'KIR':
                $newDeadline = $now->addMonths(6);
                break;
            case 'STNK':
            case 'PKB':
            case 'Plat Nomor':
                $newDeadline = $now->addYears(5);
                break;
            default:
                $newDeadline = $now;
        }
        $reminder->deadline = $newDeadline;
        $reminder->renewed_at = now();
        $reminder->save();

        // Optionally: Sync with Google Calendar here

        return response()->json(['success' => true]);
    }

    // API for FullCalendar events
    public function events(Truck $truck)
    {
        $reminders = $truck->reminders()->get()->map(function ($reminder) {
            return [
                'title' => $reminder->document_type,
                'start' => $reminder->deadline,
                'description' => 'Deadline for ' . $reminder->document_type,
            ];
        });
        return response()->json($reminders);
    }
}