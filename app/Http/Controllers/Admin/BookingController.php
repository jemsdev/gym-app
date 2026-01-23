<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingBarcodeMail;
use App\Models\Booking;
use App\Models\BookingCheckin;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $branches = Branch::query()->orderBy('name')->get();

        $query = Booking::query()
            ->with(['user', 'branch'])
            ->withCount('checkins')
            ->orderByDesc('id');

        $query->when($request->filled('branch_id'), function (Builder $q) use ($request) {
            $q->where('branch_id', (int) $request->input('branch_id'));
        });

        $query->when($request->filled('status'), function (Builder $q) use ($request) {
            $q->where('status', $request->input('status'));
        });

        $query->when($request->filled('type'), function (Builder $q) use ($request) {
            $q->where('type', $request->input('type'));
        });

        $query->when($request->filled('date'), function (Builder $q) use ($request) {
            $date = $request->input('date');
            $q->whereDate('start_date', $date);
        });

        $bookings = $query->paginate(10)->withQueryString();

        return view('admin.bookings.index', [
            'branches' => $branches,
            'bookings' => $bookings,
        ]);
    }

    public function create(): View
    {
        $members = User::query()
            ->where('role', User::ROLE_MEMBER)
            ->orderBy('name')
            ->get();

        $branches = Branch::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.bookings.create', [
            'members' => $members,
            'branches' => $branches,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'type' => ['required', 'in:daily,monthly'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            // For both types: admin picks a start date.
            'date' => ['required', 'date'],
        ]);

        $user = User::query()->findOrFail((int) $data['user_id']);
        abort_unless($user->role === User::ROLE_MEMBER, 422);
        abort_unless((bool) $user->is_active, 422);

        $userId = (int) $data['user_id'];
        $branchId = (int) $data['branch_id'];
        $status = Booking::STATUS_PAID;

        try {
            $booking = DB::transaction(function () use ($data, $userId, $branchId, $status) {
                $branch = Branch::query()
                    ->whereKey($branchId)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->firstOrFail();

                $dailyPrice = (float) $branch->daily_price;
                $monthlyPrice = (float) $branch->monthly_price;

                if ($dailyPrice <= 0 || $monthlyPrice <= 0) {
                    abort(422, 'Harga cabang belum diset. Silakan set harga di menu Cabang.');
                }

                if ($data['type'] === Booking::TYPE_DAILY) {
                    $date = Carbon::parse($data['date'])->toDateString();

                    $booking = Booking::create([
                        'user_id' => $userId,
                        'branch_id' => $branch->id,
                        'type' => Booking::TYPE_DAILY,
                        'start_date' => $date,
                        'end_date' => $date,
                        'amount' => $dailyPrice,
                        'status' => $status,
                    ]);

                    return $this->applyPaidMeta($booking);
                }

                $start = Carbon::parse($data['date'])->startOfDay();
                $end = $start->copy()->addMonth()->subDay();

                $startDate = $start->toDateString();
                $endDate = $end->toDateString();

                $overlapExists = Booking::query()
                    ->where('user_id', $userId)
                    ->where('branch_id', $branch->id)
                    ->where('type', Booking::TYPE_MONTHLY)
                    ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_PAID])
                    ->whereDate('start_date', '<=', $endDate)
                    ->whereDate('end_date', '>=', $startDate)
                    ->lockForUpdate()
                    ->exists();

                if ($overlapExists) {
                    abort(422, 'Booking monthly pada cabang ini overlap dengan periode yang sudah ada.');
                }

                $booking = Booking::create([
                    'user_id' => $userId,
                    'branch_id' => $branch->id,
                    'type' => Booking::TYPE_MONTHLY,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'amount' => $monthlyPrice,
                    'status' => $status,
                ]);

                return $this->applyPaidMeta($booking);
            });
        } catch (QueryException) {
            return back()
                ->withInput()
                ->withErrors(['general' => 'Booking gagal: kemungkinan duplikat untuk tanggal/periode yang sama.']);
        }

        // Send booking barcode email (best-effort).
        try {
            $booking->loadMissing(['user']);
            if ($booking->user && $booking->booking_code) {
                Mail::to($booking->user->email)->send(new BookingBarcodeMail($booking));
            }
        } catch (\Throwable) {
            // ignore mail transport errors
        }

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', "Booking berhasil dibuat untuk {$booking->user?->name}.");
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:PENDING,PAID,CANCELED,EXPIRED'],
        ]);

        DB::transaction(function () use ($booking, $data) {
            /** @var Booking $locked */
            $locked = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();

            $newStatus = $data['status'];

            if ($newStatus === Booking::STATUS_PAID) {
                if (!$locked->booking_code) {
                    $locked->booking_code = $this->generateUniqueBookingCode();
                }
                if (!$locked->paid_at) {
                    $locked->paid_at = now();
                }
            } else {
                $locked->paid_at = null;
                $locked->booking_code = null;
            }

            $locked->status = $newStatus;
            $locked->save();
        });

        return back()->with('success', 'Status booking berhasil diupdate.');
    }

    public function checkin(Request $request, Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($request, $booking) {
            /** @var Booking $locked */
            $locked = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $locked->loadMissing(['user']);

            if (!$locked->user || $locked->user->role !== User::ROLE_MEMBER || !$locked->user->is_active) {
                abort(422, 'Member tidak aktif.');
            }

            if ($locked->status !== Booking::STATUS_PAID) {
                abort(422, 'Check-in hanya bisa untuk booking dengan status PAID.');
            }

            $today = Carbon::today();
            if (!$locked->start_date || !$locked->end_date || $today->lt($locked->start_date) || $today->gt($locked->end_date)) {
                abort(422, 'Membership sudah habis / tidak aktif untuk hari ini.');
            }

            if ($locked->checked_in_at && $locked->type === Booking::TYPE_DAILY) {
                abort(422, 'Booking daily hanya bisa check-in 1x.');
            }

            if ($locked->type === Booking::TYPE_MONTHLY) {
                BookingCheckin::create([
                    'booking_id' => $locked->id,
                    'checked_in_at' => now(),
                    'checked_in_by' => $request->user()?->id,
                ]);
            }

            $locked->checked_in_at = now();
            $locked->checked_in_by = $request->user()?->id;
            $locked->save();
        });

        return back()->with('success', 'Check-in berhasil.');
    }

    private function generateUniqueBookingCode(): string
    {
        do {
            $code = 'GYM-'.Str::upper(Str::random(6));
        } while (Booking::query()->where('booking_code', $code)->exists());

        return $code;
    }

    private function applyPaidMeta(Booking $booking): Booking
    {
        if ($booking->status === Booking::STATUS_PAID) {
            $booking->booking_code = $booking->booking_code ?: $this->generateUniqueBookingCode();
            $booking->paid_at = $booking->paid_at ?: now();
            $booking->save();

            return $booking;
        }

        $booking->paid_at = null;
        $booking->booking_code = null;
        $booking->save();

        return $booking;
    }
}

