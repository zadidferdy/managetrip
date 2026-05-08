<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',          'like', "%$s%")
                  ->orWhere('plate_number', 'like', "%$s%")
                  ->orWhere('brand',        'like', "%$s%");
            });
        }

        if ($request->filled('type'))      $query->where('type',      $request->type);
        if ($request->filled('status'))    $query->where('status',    $request->status);
        if ($request->filled('ownership')) $query->where('ownership', $request->ownership);

        $vehicles = $query->paginate(15);

        return view('transportation.Vehicle', compact('vehicles'));
    }

    public function create()
    {
        return view('transportation.partial.VehicleCreate');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number'      => 'required|string|max:20|unique:vehicles,plate_number',
            'name'              => 'required|string|max:100',
            'brand'             => 'required|string|max:100',
            'year'              => 'required|integer|min:1990|max:' . now()->year,
            'type'              => 'required|in:Angkutan Orang,Angkutan Barang',
            'ownership'         => 'required|in:Milik Perusahaan,Sewa',
            'rental_company'    => 'nullable|string|max:255|required_if:ownership,Sewa',
            'rental_start'      => 'nullable|date|required_if:ownership,Sewa',
            'rental_end'        => 'nullable|date|after_or_equal:rental_start',
            'last_service_date' => 'nullable|date',
            'last_service_km'   => 'nullable|integer|min:0',
            'status'            => 'required|in:Aktif,Dalam Perbaikan,Tidak Aktif',
            'notes'             => 'nullable|string',
        ]);

        Vehicle::create($data);

        return redirect()->route('vehicle.index')
            ->with('success', "Kendaraan {$data['name']} berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('transportation.partial.VehicleEdit', compact('vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $data = $request->validate([
            'plate_number'      => 'required|string|max:20|unique:vehicles,plate_number,' . $id,
            'name'              => 'required|string|max:100',
            'brand'             => 'required|string|max:100',
            'year'              => 'required|integer|min:1990|max:' . now()->year,
            'type'              => 'required|in:Angkutan Orang,Angkutan Barang',
            'ownership'         => 'required|in:Milik Perusahaan,Sewa',
            'rental_company'    => 'nullable|string|max:255|required_if:ownership,Sewa',
            'rental_start'      => 'nullable|date|required_if:ownership,Sewa',
            'rental_end'        => 'nullable|date|after_or_equal:rental_start',
            'last_service_date' => 'nullable|date',
            'last_service_km'   => 'nullable|integer|min:0',
            'status'            => 'required|in:Aktif,Dalam Perbaikan,Tidak Aktif',
            'notes'             => 'nullable|string',
        ]);

        $vehicle->update($data);

        return redirect()->route('vehicle.index')
            ->with('success', "Kendaraan {$vehicle->name} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        if ($vehicle->trips()->whereIn('status', ['Approved', 'Start'])->exists()) {
            return redirect()->route('vehicle.index')
                ->with('error', 'Kendaraan tidak dapat dihapus karena sedang digunakan dalam trip aktif.');
        }

        $name = $vehicle->name;
        $vehicle->delete();

        return redirect()->route('vehicle.index')
            ->with('success', "Kendaraan {$name} berhasil dihapus.");
    }

    public function detail($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        return response()->json([
            'plate_number'      => $vehicle->plate_number,
            'name'              => $vehicle->name,
            'brand'             => $vehicle->brand,
            'year'              => $vehicle->year,
            'type'              => $vehicle->type,
            'ownership'         => $vehicle->ownership,
            'rental_company'    => $vehicle->rental_company   ?? '—',
            'rental_start'      => $vehicle->rental_start     ? $vehicle->rental_start->format('d M Y')     : '—',
            'rental_end'        => $vehicle->rental_end       ? $vehicle->rental_end->format('d M Y')       : '—',
            'last_service_date' => $vehicle->last_service_date ? $vehicle->last_service_date->format('d M Y') : '—',
            'last_service_km'   => $vehicle->last_service_km  ? number_format($vehicle->last_service_km, 0, ',', '.') . ' km' : '—',
            'status'            => $vehicle->status,
            'notes'             => $vehicle->notes ?? '—',
        ]);
    }
}