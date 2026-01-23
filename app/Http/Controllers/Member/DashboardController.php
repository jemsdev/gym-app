<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $active = Booking::query()
            ->with(['branch'])
            ->where('user_id', $user->id)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_PAID])
            ->whereDate('end_date', '>=', $today)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $history = Booking::query()
            ->with(['branch'])
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->paginate(10);

        return view('member.dashboard', [
            'active' => $active,
            'history' => $history,
        ]);
    }
}

