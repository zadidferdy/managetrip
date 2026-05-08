<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'trip_code', 'departure_datetime', 'arrival_datetime',
        'from_location', 'to_location', 'trip_type', 'priority',
        'approver1', 'approver2', 'driver', 'vehicle', 'vehicle_id',
        'purpose', 'notes', 'status', 'approval_level', 'reject_reason',
        'km_start', 'km_end', 'km_used', 'fuel_used',
        'passengers', 'region', 'actual_departure', 'actual_arrival',
    ];

    protected $casts = [
        'departure_datetime' => 'datetime',
        'arrival_datetime'   => 'datetime',
        'actual_departure'   => 'datetime',
        'actual_arrival'     => 'datetime',
    ];

    public function vehicleRel()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function fuelLogs()
    {
        return $this->hasMany(FuelLog::class);
    }
}