<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'service_date',
        'service_type',
        'costs',
        'remarks',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}