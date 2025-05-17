<?php
namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Truck;
use Illuminate\Http\Request;

class ServiceRecordController extends Controller
{
    public function index(Truck $truck)
    {
        $serviceRecords = $truck->serviceRecords;
        return view('service_records.index', compact('serviceRecords', 'truck'));
    }

    public function create(Truck $truck)
    {
        return view('service_records.create', compact('truck'));
    }

    public function store(Request $request, Truck $truck)
    {
        $request->validate([
            'service_date' => 'required|date',
            'service_type' => 'required|string',
            'costs' => 'required|numeric',
            'remarks' => 'nullable|string',
        ]);

        $truck->serviceRecords()->create($request->all());

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Service record added successfully.');
    }

    public function edit(Truck $truck, ServiceRecord $serviceRecord)
    {
        return view('service_records.edit', compact('truck', 'serviceRecord'));
    }

    public function update(Request $request, Truck $truck, ServiceRecord $serviceRecord)
    {
        $request->validate([
            'service_date' => 'required|date',
            'service_type' => 'required|string',
            'costs' => 'required|numeric',
            'remarks' => 'nullable|string',
        ]);

        $serviceRecord->update($request->all());

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Service record updated successfully.');
    }

    public function destroy(Truck $truck, ServiceRecord $serviceRecord)
    {
        $serviceRecord->delete();

        return redirect()->route('trucks.show', $truck->id)->with('success', 'Service record deleted successfully.');
    }
}