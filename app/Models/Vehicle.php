<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
   protected $fillable = [
    'plate_number',
    'name',
    'brand',
    'year',
    'type',
    'ownership',        // tambah
    'rental_company',   // tambah
    'rental_start',     // tambah
    'rental_end',       // tambah
    'last_service_date',
    'last_service_km',
    'status',
    'notes',
];

protected $casts = [
    'last_service_date' => 'date',
    'rental_start'      => 'date',  // tambah
    'rental_end'        => 'date',  // tambah
];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function fuelLogs()
    {
        return $this->hasMany(FuelLog::class);
    }

    // Label untuk dropdown
    public function getLabelAttribute(): string
    {
        return "{$this->name} — {$this->plate_number}";
    }
}