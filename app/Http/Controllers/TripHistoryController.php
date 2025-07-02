<?php
namespace App\Http\Controllers;

use App\Models\TripHistory;
use App\Models\Truck;
use App\Models\SafetyCheck;
use Illuminate\Http\Request;

class TripHistoryController extends Controller
{
    public function index()
    {
        $trips = TripHistory::with(['truck', 'safetyCheck'])
            ->whereNotNull('safety_check_id')
            ->latest()
            ->get();

        $tripData = $trips->map(function($trip) {
            return [
                'id' => $trip->id,
                'route' => $trip->safetyCheck ? $trip->safetyCheck->pick_up_point . ' - ' . $trip->safetyCheck->destination : '-',
                'trip_date' => $trip->trip_date,
                'driver' => $trip->driver,
                'truck_brand' => $trip->truck_brand,
                'truck_plate' => $trip->truck_plate,
                'price' => $trip->price,
                'driver_fee' => $trip->driver_fee,
                'gas_price' => $trip->gas_price,
                'highway_price' => $trip->highway_price,
                'crossing_ferry_and_other_cost' => $trip->crossing_ferry_and_other_cost,
                'incentive' => $trip->incentive,
                'helper_fee' => $trip->helper_fee,
                'balance' => $trip->balance,
            ];
        })->values()->toArray();

        return view('trip_histories.index', compact('tripData'));
    }

    // Batch update method for multiple rows
    public function updateMultiple(Request $request)
    {
        foreach ($request->rows as $row) {
            TripHistory::where('id', $row['id'])->update([
                'driver' => $row['driver'],
                'truck_brand' => $row['truck_brand'],
                'truck_plate' => $row['truck_plate'],
                'price' => $row['price'],
                'driver_fee' => $row['driver_fee'],
                'gas_price' => $row['gas_price'],
                'highway_price' => $row['highway_price'],
                'crossing_ferry_and_other_cost' => $row['crossing_ferry_and_other_cost'],
                'incentive' => $row['incentive'],
                'helper_fee' => $row['helper_fee'],
                'balance' => (float)$row['gas_price'] - (float)$row['highway_price'],
            ]);
        }
        return response()->json(['success' => true]);
    }
}