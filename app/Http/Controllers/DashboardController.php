<?php

namespace App\Http\Controllers;

use App\Models\Trip;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalTrip'      => Trip::whereMonth('departure_datetime', now()->month)->count(),
            'pendingApproval'=> Trip::where('status', 'Requested')->count(),
            'approved'       => Trip::where('status', 'Approved')->count(),
            'started'        => Trip::where('status', 'Start')->count(),
            'finished'       => Trip::where('status', 'Finished')->count(),
            'rejected'       => Trip::where('status', 'Rejected')->count(),
            'recentTrips'    => Trip::latest()->take(5)->get(),
        ]);
    }
}