<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $query = Trip::with('vehicleRel')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('trip_code',      'like', "%$s%")
                  ->orWhere('from_location', 'like', "%$s%")
                  ->orWhere('to_location',   'like', "%$s%");
            });
        }

        if ($request->filled('status'))    $query->where('status',   $request->status);
        if ($request->filled('priority'))  $query->where('priority', $request->priority);
        if ($request->filled('date_from')) $query->whereDate('departure_datetime', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('departure_datetime', '<=', $request->date_to);

        $trips = $query->paginate(15);

        return view('transportation.trip', compact('trips'));
    }

    public function create()
    {
        $vehicles   = Vehicle::where('status', 'Aktif')->orderBy('name')->get();
        $tripTypes  = Vehicle::select('type')->distinct()->orderBy('type')->pluck('type');
        $approvers1 = User::where('role_user', 'admin_trans')->orderBy('nama_user')->get();
        $approvers2 = User::where('role_user', 'manager')->orderBy('nama_user')->get();
        $drivers    = User::where('role_user', 'driver')->orderBy('nama_user')->get();

        return view('transportation.partial.TripCreate',
            compact('vehicles', 'tripTypes', 'approvers1', 'approvers2', 'drivers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'departure_datetime' => 'required|date',
            'arrival_datetime'   => 'nullable|date|after:departure_datetime',
            'from_location'      => 'required|string|max:255',
            'to_location'        => 'required|string|max:255',
            'trip_type'          => 'required|string',
            'priority'           => 'required|in:Flexible,Normal,Urgent,Emergency',
            'region'             => 'nullable|string|max:100',
            'vehicle_id'         => 'nullable|exists:vehicles,id',
            'driver'             => 'nullable|string',
            'approver1'          => 'required|string',
            'approver2'          => 'required|string',
            'purpose'            => 'required|string',
            'notes'              => 'nullable|string',
            'km_start'           => 'nullable|integer|min:0',
        ]);

        if (!empty($data['vehicle_id'])) {
            $v = Vehicle::find($data['vehicle_id']);
            $data['vehicle'] = $v->name . ' - ' . $v->plate_number;
        }

        $year    = now()->year;
        $last    = Trip::whereYear('created_at', $year)
                       ->orderByDesc('id')
                       ->value('trip_code');

        $nextNum = $last ? ((int) substr($last, -4)) + 1 : 1;

        do {
            $tripCode = 'TRP-' . $year . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
            $nextNum++;
        } while (Trip::where('trip_code', $tripCode)->exists());

        $data['trip_code']      = $tripCode;
        $data['status']         = 'Requested';
        $data['approval_level'] = 0;

        Trip::create($data);

        return redirect()->route('trip.index')
            ->with('success', "Pemesanan {$data['trip_code']} berhasil dikirim!");
    }

    public function edit($id)
    {
        $trip       = Trip::findOrFail($id);
        $vehicles   = Vehicle::where('status', 'Aktif')->orderBy('name')->get();
        $tripTypes  = Vehicle::select('type')->distinct()->orderBy('type')->pluck('type');
        $approvers1 = User::where('role_user', 'admin_trans')->orderBy('nama_user')->get();
        $approvers2 = User::where('role_user', 'manager')->orderBy('nama_user')->get();
        $drivers    = User::where('role_user', 'driver')->orderBy('nama_user')->get();

        return view('transportation.partial.TripEdit',
            compact('trip', 'vehicles', 'tripTypes', 'approvers1', 'approvers2', 'drivers'));
    }

    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $data = $request->validate([
            'departure_datetime' => 'required|date',
            'arrival_datetime'   => 'nullable|date|after:departure_datetime',
            'from_location'      => 'required|string|max:255',
            'to_location'        => 'required|string|max:255',
            'trip_type'          => 'required|string',
            'priority'           => 'required|in:Flexible,Normal,Urgent,Emergency',
            'region'             => 'nullable|string|max:100',
            'vehicle_id'         => 'nullable|exists:vehicles,id',
            'driver'             => 'nullable|string',
            'approver1'          => 'required|string',
            'approver2'          => 'required|string',
            'purpose'            => 'required|string',
            'notes'              => 'nullable|string',
            'km_start'           => 'nullable|integer|min:0',
        ]);

        if (!empty($data['vehicle_id'])) {
            $v = Vehicle::find($data['vehicle_id']);
            $data['vehicle'] = $v->name . ' - ' . $v->plate_number;
        }

        $trip->update($data);

        return redirect()->route('trip.index')
            ->with('success', "Trip {$trip->trip_code} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);
        $code = $trip->trip_code;
        $trip->delete();

        return redirect()->route('trip.index')
            ->with('success', "Trip {$code} berhasil dihapus.");
    }

    public function start($id)
    {
        $trip = Trip::findOrFail($id);
        $trip->update([
            'status'           => 'Start',
            'actual_departure' => now(),
        ]);

        return redirect()->route('trip.index')
            ->with('success', "Trip {$trip->trip_code} telah dimulai.");
    }

    public function finish(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $data = $request->validate([
            'km_end'     => 'required|integer|min:' . ($trip->km_start ?? 0),
            'fuel_used'  => 'required|numeric|min:0',
            'passengers' => 'nullable|integer|min:0',
        ]);

        $kmUsed = ($trip->km_start !== null)
            ? ($data['km_end'] - $trip->km_start)
            : null;

        $trip->update([
            'status'         => 'Finished',
            'actual_arrival' => now(),
            'km_end'         => $data['km_end'],
            'km_used'        => $kmUsed,
            'fuel_used'      => $data['fuel_used'],
            'passengers'     => $data['passengers'] ?? null,
        ]);

        return redirect()->route('trip.index')
            ->with('success', "Trip {$trip->trip_code} telah selesai.");
    }

    public function detail($id)
    {
        $trip = Trip::with('vehicleRel')->findOrFail($id);

        return response()->json([
            'trip_code' => $trip->trip_code,
            'from'      => $trip->from_location,
            'to'        => $trip->to_location,
            'depart'    => $trip->departure_datetime
                            ? \Carbon\Carbon::parse($trip->departure_datetime)->format('d M Y H:i')
                            : '—',
            'arrival'   => $trip->arrival_datetime
                            ? \Carbon\Carbon::parse($trip->arrival_datetime)->format('d M Y H:i')
                            : '—',
            'type'      => $trip->trip_type  ?? '—',
            'region'    => $trip->region     ?? '—',
            'priority'  => $trip->priority,
            'status'    => $trip->status,
            'vehicle'   => $trip->vehicleRel
                            ? $trip->vehicleRel->name . ' — ' . $trip->vehicleRel->plate_number
                            : ($trip->vehicle ?? '—'),
            'driver'    => $trip->driver   ?? '—',
            'km_start'  => $trip->km_start !== null ? number_format($trip->km_start, 0, ',', '.') . ' km' : '—',
            'km_end'    => $trip->km_end   !== null ? number_format($trip->km_end,   0, ',', '.') . ' km' : '—',
            'km_used'   => $trip->km_used  !== null ? number_format($trip->km_used,  0, ',', '.') . ' km' : '—',
            'fuel'      => $trip->fuel_used !== null ? $trip->fuel_used . ' liter' : '—',
            'approver1' => $trip->approver1 ?? '—',
            'approver2' => $trip->approver2 ?? '—',
            'purpose'   => $trip->purpose   ?? '—',
            'notes'     => $trip->notes     ?? '—',
        ]);
    }
}