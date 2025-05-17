<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TruckStoreRequest;
use App\Http\Requests\TruckUpdateRequest;
class TruckController extends Controller
{
    /**
     * Display a listing of the trucks.
     */
    public function index()
    {
        $trucks = Truck::all();
        return view('truckunit.index', compact('trucks'));
    }

    /**
     * Show the form for creating a new truck.
     */
    public function create()
    {
        return view('truckunit.create');
    }

    /**
     * Store a newly created truck in storage.
     */
    public function store(TruckStoreRequest $request)
    {
        $data = $request->validated();

        // Handle image upload
        $imagePath = $data->file('image') ? $data->file('image')->store('trucks', 'public') : null;

        // Create the truck
        Truck::create([
            'plat_no' => $data->plat_no,
            'brand_truk' => $data->brand_truk,
            'no_stnk' => $data->no_stnk,
            'no_kir' => $data->no_kir,
            'no_pajak' => $data->no_pajak,
            'image' => $imagePath,
        ]);

        return redirect()->route('trucks.index')->with('success', 'Truck added successfully.');
    }

    /**
     * Show the form for editing the specified truck.
     */
    public function edit(Truck $truck)
    {
        return view('truckunit.edit', compact('truck'));
    }

    /**
     * Update the specified truck in storage.
     */
    public function update(TruckUpdateRequest $request, Truck $truck)
    {
        

        $data = $request->validated();

        if ($data->hasFile('image')) {
            if ($truck->image) {
                Storage::disk('public')->delete($truck->image);
            }

            $imagePath = $data->file('image')->store('trucks', 'public');
            $truck->image = $imagePath;
        }

        // Update the truck
        $truck->fill($data);

        return redirect()->route('trucks.index')->with('success', 'Truck updated successfully.');
    }

    /**
     * Remove the specified truck from storage.
     */
    public function destroy(Truck $truck)
    {
        // Delete the image if it exists
        if ($truck->image) {
            Storage::disk('public')->delete($truck->image);
        }

        // Delete the truck
        $truck->delete();

        return redirect()->route('trucks.index')->with('success', 'Truck deleted successfully.');
    }

    public function show(Truck $truck)
{
    // Pass the truck data to the view
    return view('truckunit.show', compact('truck'));
}
}