<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripHistory extends Model
{
    protected $fillable = [
        'truck_id',
        'safety_check_id',
        'trip_date',
        'driver',
        'truck_brand',
        'truck_plate',
        'price',
        'driver_fee',
        'gas_price',
        'highway_price',
        'crossing_ferry_and_other_cost',
        'incentive',
        'helper_fee',
        'balance',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function safetyCheck()
    {
        return $this->belongsTo(SafetyCheck::class);
    }
}