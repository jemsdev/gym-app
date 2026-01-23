<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\BranchPrice;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::query()
            ->with(['branch'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->paginate(10);

        return view('member.bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    public function create(): View
    {
        $branches = Branch::query()
            ->where('is_active', true)
            ->with(['activePrice'])
            ->orderBy('name')
            ->get();

        return view('member.bookings.create', [
            'branches' => $branches,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:daily,monthly'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'date' => ['required_if:type,daily', 'date'],
            'month' => ['required_if:type,monthly', 'date_format:Y-m'],
        ]);

        $userId = $request->user()->id;
        $branchId = (int) $data['branch_id'];

        try {
            $booking = DB::transaction(function () use ($data, $userId, $branchId) {
                $branch = Branch::query()
                    ->whereKey($branchId)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->firstOrFail();

                $price = BranchPrice::query()
                    ->where('branch_id', $branch->id)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($data['type'] === Booking::TYPE_DAILY) {
                    $date = Carbon::parse($data['date'])->toDateString();

                    return Booking::create([
                        'user_id' => $userId,
                        'branch_id' => $branch->id,
                        'type' => Booking::TYPE_DAILY,
                        'start_date' => $date,
                        'end_date' => $date,
                        'amount' => $price->daily_price,
                        'status' => Booking::STATUS_PENDING,
                    ]);
                }

                $month = Carbon::createFromFormat('Y-m', $data['month']);
                $start = $month->copy()->startOfMonth()->toDateString();
                $end = $month->copy()->endOfMonth()->toDateString();

                $overlapExists = Booking::query()
                    ->where('user_id', $userId)
                    ->where('branch_id', $branch->id)
                    ->where('type', Booking::TYPE_MONTHLY)
                    ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_PAID])
                    ->whereDate('start_date', '<=', $end)
                    ->whereDate('end_date', '>=', $start)
                    ->lockForUpdate()
                    ->exists();

                if ($overlapExists) {
                    abort(422, 'Booking monthly pada cabang ini overlap dengan periode yang sudah ada.');
                }

                return Booking::create([
                    'user_id' => $userId,
                    'branch_id' => $branch->id,
                    'type' => Booking::TYPE_MONTHLY,
                    'start_date' => $start,
                    'end_date' => $end,
                    'amount' => $price->monthly_price,
                    'status' => Booking::STATUS_PENDING,
                ]);
            });
        } catch (QueryException $e) {
            // Likely unique constraint hit (double booking daily or duplicate start_date).
            return back()
                ->withInput()
                ->withErrors(['general' => 'Booking gagal: kemungkinan double booking pada tanggal/periode yang sama.']);
        }

        return redirect()
            ->route('member.bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat (PENDING). Silakan bayar untuk mengaktifkan.');
    }

    public function show(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id, 404);

        $booking->load(['branch']);

        return view('member.bookings.show', [
            'booking' => $booking,
        ]);
    }

    public function pay(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 404);

        DB::transaction(function () use ($booking) {
            /** @var Booking $locked */
            $locked = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== Booking::STATUS_PENDING) {
                abort(422, 'Booking hanya bisa dibayar saat status masih PENDING.');
            }

            $locked->status = Booking::STATUS_PAID;
            $locked->paid_at = now();
            $locked->booking_code = $locked->booking_code ?: $this->generateUniqueBookingCode();
            $locked->save();
        });

        return back()->with('success', 'Pembayaran berhasil (simulasi). Booking menjadi PAID.');
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 404);

        DB::transaction(function () use ($booking) {
            /** @var Booking $locked */
            $locked = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== Booking::STATUS_PENDING) {
                abort(422, 'Booking hanya bisa dibatalkan saat status masih PENDING.');
            }

            $locked->status = Booking::STATUS_CANCELED;
            $locked->save();
        });

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    private function generateUniqueBookingCode(): string
    {
        do {
            $code = 'GYM-'.Str::upper(Str::random(6));
        } while (Booking::query()->where('booking_code', $code)->exists());

        return $code;
    }
}

