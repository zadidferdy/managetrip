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

        $currentUser = auth()->user();
        $role        = $currentUser->role_user;

        // Level 1: admin_trans belum approve
        if ($trip->approval_level < 1 && in_array($role, ['admin_trans', 'manager'])) {
            $trip->approval_level = 1;

            // Assign driver / vehicle jika disediakan
            if ($request->filled('driver'))  $trip->driver  = $request->driver;
            if ($request->filled('vehicle')) $trip->vehicle = $request->vehicle;

            $trip->save();

            return redirect()->route('approval.index')
                ->with('success', "Trip {$trip->trip_code} telah disetujui Level 1. Menunggu persetujuan Level 2.");
        }

        // Level 2: manager approve (setelah level 1 selesai)
        if ($trip->approval_level === 1 && $role === 'manager') {
            $trip->approval_level = 2;
            $trip->status         = 'Approved';

            $trip->save();

            return redirect()->route('approval.index')
                ->with('success', "Trip {$trip->trip_code} telah disetujui sepenuhnya!");
        }

        return redirect()->route('approval.index')
            ->with('error', 'Kamu tidak memiliki wewenang untuk approve pada level ini.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|min:5',
        ], [
            'reject_reason.required' => 'Alasan penolakan wajib diisi.',
            'reject_reason.min'      => 'Alasan minimal 5 karakter.',
        ]);

        $trip = Trip::findOrFail($id);

        if ($trip->status !== 'Requested') {
            return redirect()->route('approval.index')
                ->with('error', 'Trip sudah tidak dalam status Requested.');
        }

        $trip->status        = 'Rejected';
        $trip->reject_reason = $request->reject_reason;
        $trip->save();

        return redirect()->route('approval.index')
            ->with('success', "Trip {$trip->trip_code} telah ditolak.");
    }
}