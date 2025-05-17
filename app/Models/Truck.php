<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plat_no',      // License plate number
        'brand_truk',   // Truck brand
        'no_stnk',      // Vehicle registration number
        'no_kir',       // Vehicle inspection number
        'no_pajak',     // Tax number
        'image',        // Path to the truck image
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime', // Cast created_at to a datetime object
        'updated_at' => 'datetime', // Cast updated_at to a datetime object
    ];
    public function serviceRecords()
{
    return $this->hasMany(ServiceRecord::class);
}

public function safetyChecks()
{
    return $this->hasMany(SafetyCheck::class);
}

public function reminders()
{
    return $this->hasMany(Reminder::class);
}
}