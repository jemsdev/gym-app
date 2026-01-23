<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $monthStart = Carbon::now()->startOfMonth()->toDateString();
        $monthEnd = Carbon::now()->endOfMonth()->toDateString();

        $totalThisMonth = Booking::query()
            ->whereBetween('start_date', [$monthStart, $monthEnd])
            ->count();

        $pendingThisMonth = Booking::query()
            ->whereBetween('start_date', [$monthStart, $monthEnd])
            ->where('status', Booking::STATUS_PENDING)
            ->count();

        $paidThisMonth = Booking::query()
            ->whereBetween('start_date', [$monthStart, $monthEnd])
            ->where('status', Booking::STATUS_PAID)
            ->count();

        $expiredThisMonth = Booking::query()
            ->whereBetween('start_date', [$monthStart, $monthEnd])
            ->where('status', Booking::STATUS_EXPIRED)
            ->count();

        $topBranches = Branch::query()
            ->leftJoin('bookings', 'branches.id', '=', 'bookings.branch_id')
            ->select('branches.*', DB::raw('COUNT(bookings.id) as bookings_count'))
            ->whereBetween('bookings.start_date', [$monthStart, $monthEnd])
            ->groupBy('branches.id')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
            'totalThisMonth' => $totalThisMonth,
            'pendingThisMonth' => $pendingThisMonth,
            'paidThisMonth' => $paidThisMonth,
            'expiredThisMonth' => $expiredThisMonth,
            'topBranches' => $topBranches,
        ]);
    }
}

