<?php

namespace App\Http\Controllers;

use App\Models\SafetyCheck;
use App\Models\Truck;
use Illuminate\Http\Request;

class SafetyCheckController extends Controller
{
    public function index(Truck $truck)
    {
        $safetyChecks = $truck->safetyChecks;
        return view('safety_checks.index', compact('safetyChecks', 'truck'));
    }

    public function create(Truck $truck)
    {
        return view('safety_checks.create', compact('truck'));
    }

    public function store(Request $request, Truck $truck)
    {
        $request->validate([
            'pick_up_point' => 'required|string',
            'destination' => 'required|string',
            'pdf_file' => 'required|file|mimes:pdf|max:2048',
        ]);

        $pdfPath = $request->file('pdf_file')->store('safety_checks', 'public');

        $truck->safetyChecks()->create([
            'pick_up_point' => $request->pick_up_point,
            'destination' => $request->destination,
            'pdf_file' => $pdfPath,
        ]);

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Safety check added successfully.');
    }

    public function edit(Truck $truck, SafetyCheck $safetyCheck)
    {
        return view('safety_checks.edit', compact('truck', 'safetyCheck'));
    }

    public function update(Request $request, Truck $truck, SafetyCheck $safetyCheck)
    {
        $request->validate([
            'pick_up_point' => 'required|string',
            'destination' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('safety_checks', 'public');
            $safetyCheck->update(['pdf_file' => $pdfPath]);
        }

        $safetyCheck->update($request->only(['pick_up_point', 'destination']));

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Safety check updated successfully.');
    }

    public function destroy(Truck $truck, SafetyCheck $safetyCheck)
    {
        $safetyCheck->delete();

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Safety check deleted successfully.');
    }
}