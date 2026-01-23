<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingCheckin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckinController extends Controller
{
    public function index(): View
    {
        return view('admin.checkins.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'booking_code' => ['required', 'string', 'max:50'],
        ]);

        $bookingCode = trim($data['booking_code']);

        $payload = null;

        try {
            DB::transaction(function () use ($request, $bookingCode, &$payload) {
                /** @var Booking $booking */
                $booking = Booking::query()
                    ->where('booking_code', $bookingCode)
                    ->lockForUpdate()
                    ->firstOrFail();

                $booking->loadMissing(['user', 'branch']);

                if (!$booking->user || $booking->user->role !== User::ROLE_MEMBER || !$booking->user->is_active) {
                    abort(422, 'Member tidak aktif.');
                }

                if ($booking->status !== Booking::STATUS_PAID) {
                    abort(422, 'Check-in hanya bisa untuk booking dengan status PAID.');
                }

                $today = Carbon::today();
                if (!$booking->start_date || !$booking->end_date || $today->lt($booking->start_date) || $today->gt($booking->end_date)) {
                    abort(422, 'Membership sudah habis / tidak aktif untuk hari ini.');
                }

                if ($booking->checked_in_at && $booking->type === Booking::TYPE_DAILY) {
                    abort(422, 'Booking daily hanya bisa check-in 1x.');
                }

                if ($booking->type === Booking::TYPE_MONTHLY) {
                    BookingCheckin::create([
                        'booking_id' => $booking->id,
                        'checked_in_at' => now(),
                        'checked_in_by' => $request->user()?->id,
                    ]);
                }

                $booking->checked_in_at = now();
                $booking->checked_in_by = $request->user()?->id;
                $booking->save();

                $payload = [
                    'member_name' => $booking->user?->name,
                    'member_email' => $booking->user?->email,
                    'member_phone' => $booking->user?->phone ?? null,
                    'member_address' => $booking->user?->address ?? null,
                    'member_is_active' => (bool) ($booking->user?->is_active ?? false),
                    'branch_name' => $booking->branch?->name,
                    'booking_type' => $booking->type,
                    'booking_status' => $booking->status,
                    'booking_code' => $booking->booking_code,
                    'period_start' => optional($booking->start_date)->toDateString(),
                    'period_end' => optional($booking->end_date)->toDateString(),
                    'checked_in_at' => optional($booking->checked_in_at)->toDateTimeString(),
                ];
            });
        } catch (ModelNotFoundException) {
            return back()
                ->withErrors(['booking_code' => 'Booking code tidak ditemukan.'])
                ->withInput();
        } catch (HttpException $e) {
            if ($e->getStatusCode() === 422) {
                return back()
                    ->withErrors(['booking_code' => $e->getMessage() ?: 'Member tidak bisa check-in.'])
                    ->withInput();
            }
            throw $e;
        } catch (\Throwable) {
            return back()
                ->withErrors(['booking_code' => 'Terjadi kesalahan saat proses check-in. Silakan coba lagi.'])
                ->withInput();
        }

        return back()
            ->with('success', 'Check-in berhasil.')
            ->with('checkin_info', $payload);
    }
}

