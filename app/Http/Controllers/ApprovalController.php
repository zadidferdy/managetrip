<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        $query = Trip::query()->latest();

        match ($tab) {
            'pending'  => $query->where('status', 'Requested'),
            'approved' => $query->whereIn('status', ['Approved', 'Start', 'Finished']),
            'rejected' => $query->where('status', 'Rejected'),
            default    => null, // 'all' — no filter
        };

        $trips        = $query->get();
        $pendingCount = Trip::where('status', 'Requested')->count();

        return view('transportation.ApprovalTransportation', compact('trips', 'tab', 'pendingCount'));
    }

    public function approve(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        if ($trip->status !== 'Requested') {
            return redirect()->route('approval.index')
                ->with('error', 'Trip sudah tidak dalam status Requested.');
        }

        $trip->approval_level = min($trip->approval_level + 1, 2);

        if ($trip->approval_level >= 2) {
            $trip->status = 'Approved';
        }

        // Assign driver / vehicle if provided
        if ($request->filled('driver'))  $trip->driver  = $request->driver;
        if ($request->filled('vehicle')) $trip->vehicle = $request->vehicle;

        $trip->save();

        $msg = $trip->status === 'Approved'
            ? "Trip {$trip->trip_code} telah disetujui sepenuhnya!"
            : "Trip {$trip->trip_code} telah melewati Level {$trip->approval_level}. Menunggu persetujuan berikutnya.";

        return redirect()->route('approval.index')
            ->with('success', $msg);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|min:5',
        ]);

        $trip = Trip::findOrFail($id);

        $trip->update([
            'status'        => 'Rejected',
            'reject_reason' => $request->reject_reason,
        ]);

        return redirect()->route('approval.index')
            ->with('success', "Trip {$trip->trip_code} telah ditolak.");
    }
}