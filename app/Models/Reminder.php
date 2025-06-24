<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'document_type',
        'deadline',
        'remind_every',
        'google_event_id',
        'renewed_at',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}