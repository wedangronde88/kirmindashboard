<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'pick_up_point',
        'destination',
        'trip_date',
        'pdf_file',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}