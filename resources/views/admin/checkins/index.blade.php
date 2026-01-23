@extends('layouts.admin')

@section('title', 'Check-in')

@section('content')
    <x-ui.page-header title="Check-in" subtitle="Scan / input booking code untuk check-in member." />

    @if (session('checkin_info'))
        @php
            $ci = session('checkin_info');
            $fmtDate = function ($s) {
                try { return $s ? \Carbon\Carbon::parse($s)->format('d-m-Y') : '-'; } catch (\Throwable) { return $s ?: '-'; }
            };
            $fmtDateTime = function ($s) {
                try { return $s ? \Carbon\Carbon::parse($s)->format('d-m-Y H:i:s') : '-'; } catch (\Throwable) { return $s ?: '-'; }
            };
        @endphp

        <!-- Success popup -->
        <div class="modal fade" id="checkinSuccessModal" tabindex="-1" aria-labelledby="checkinSuccessModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content admin-card">
                    <div class="modal-header border-0">
                        <div>
                            <div class="fw-semibold" id="checkinSuccessModalLabel">Check-in Berhasil</div>
                            <div class="text-muted small">Informasi member & booking.</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">Member</div>
                                <div class="fw-semibold">{{ $ci['member_name'] ?? '-' }}</div>
                                <div class="text-muted small mt-1">{{ $ci['member_email'] ?? '-' }}</div>
                                @if (!empty($ci['member_phone']))
                                    <div class="text-muted small">{{ $ci['member_phone'] }}</div>
                                @endif
                                @if (!empty($ci['member_address']))
                                    <div class="text-muted small">{{ $ci['member_address'] }}</div>
                                @endif
                                <div class="mt-2">
                                    @if (!empty($ci['member_is_active']))
                                        <span class="badge text-bg-success">ACTIVE</span>
                                    @else
                                        <span class="badge text-bg-secondary">INACTIVE</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small">Booking</div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <div class="fw-semibold">{{ $ci['booking_code'] ?? '-' }}</div>
                                    @if (!empty($ci['booking_status']))
                                        <x-ui.status-badge :status="$ci['booking_status']" />
                                    @endif
                                    @if (!empty($ci['booking_type']))
                                        <span class="badge text-bg-light border" style="border-color: var(--border) !important;">
                                            {{ strtoupper($ci['booking_type']) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-2">
                                    <div class="text-muted small">Cabang</div>
                                    <div class="fw-semibold">{{ $ci['branch_name'] ?? '-' }}</div>
                                </div>

                                <div class="mt-2">
                                    <div class="text-muted small">Check-in time</div>
                                    <div class="fw-semibold">{{ $fmtDateTime($ci['checked_in_at'] ?? null) }}</div>
                                </div>

                                <div class="mt-2">
                                    <div class="text-muted small">Aktif sampai</div>
                                    <div class="fw-semibold">{{ $fmtDate($ci['period_end'] ?? null) }}</div>
                                    <div class="text-muted small">Periode: {{ $fmtDate($ci['period_start'] ?? null) }} s/d {{ $fmtDate($ci['period_end'] ?? null) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="btnCheckinAgain">Check-in lagi</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <x-ui.card>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <div class="fw-semibold">Scan via Kamera</div>
                                <div class="text-muted small">Klik “Mulai Scan”, pilih kamera jika perlu, lalu arahkan QR ke kamera.</div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="btnStartScan">Mulai Scan</button>
                                <button type="button" class="btn btn-outline-secondary" id="btnStopScan" disabled>Stop</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center justify-content-between">
                            <div class="text-muted small">
                                <span class="fw-semibold">Debug:</span>
                                <span id="scanDebug">loading…</span>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <label for="cameraSelect" class="text-muted small mb-0">Camera</label>
                                <select id="cameraSelect" class="form-select form-select-sm" style="min-width: 240px;" disabled>
                                    <option value="">(pilih kamera)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden border" style="border-color: var(--border) !important; background: rgba(17,24,39,.04);">
                            <video id="qrVideo" autoplay playsinline muted style="object-fit: cover;"></video>
                        </div>
                        <div class="text-muted small mt-2" id="scanStatus">Status: belum mulai.</div>
                    </div>

                    <div class="col-12">
                        <hr style="border-color: var(--border)">
                    </div>

                    <div class="col-12">
                        <div class="fw-semibold mb-2">Manual Input</div>
                        <form method="POST" action="{{ route('admin.checkins.store') }}" id="checkinForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Booking Code</label>
                            <input name="booking_code" id="bookingCodeInput" class="form-control form-control-lg" autofocus placeholder="contoh: GYM-ABC123" value="{{ old('booking_code') }}" required>
                            @error('booking_code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <button class="btn btn-success w-100" type="submit">Check-in</button>
                    </form>

                    <div class="text-muted small mt-3">
                        Syarat check-in: <strong>member aktif</strong>, status booking <strong>PAID</strong>, periode booking berlaku hari ini. Daily 1x check-in, monthly bisa berkali-kali sampai masa habis.
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <script>
        (function () {
            const video = document.getElementById('qrVideo');
            const btnStart = document.getElementById('btnStartScan');
            const btnStop = document.getElementById('btnStopScan');
            const statusEl = document.getElementById('scanStatus');
            const debugEl = document.getElementById('scanDebug');
            const cameraSelect = document.getElementById('cameraSelect');
            const input = document.getElementById('bookingCodeInput');
            const form = document.getElementById('checkinForm');

            let stream = null;
            let rafId = null;
            let detector = null;
            let selectedDeviceId = '';

            function setDebug(text) {
                debugEl.textContent = text;
            }

            function setStatus(text) {
                statusEl.textContent = 'Status: ' + text;
            }

            function stopScan() {
                if (rafId) {
                    cancelAnimationFrame(rafId);
                    rafId = null;
                }
                if (stream) {
                    stream.getTracks().forEach(t => t.stop());
                    stream = null;
                }
                video.srcObject = null;
                btnStart.disabled = false;
                btnStop.disabled = true;
                setStatus('berhenti.');
            }

            async function refreshDevices() {
                if (!navigator.mediaDevices?.enumerateDevices) return;
                const devices = await navigator.mediaDevices.enumerateDevices();
                const cams = devices.filter(d => d.kind === 'videoinput');

                cameraSelect.innerHTML = '';
                if (!cams.length) {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = '(kamera tidak ditemukan)';
                    cameraSelect.appendChild(opt);
                    cameraSelect.disabled = true;
                    return;
                }

                cams.forEach((cam, idx) => {
                    const opt = document.createElement('option');
                    opt.value = cam.deviceId;
                    opt.textContent = cam.label || `Camera ${idx + 1}`;
                    cameraSelect.appendChild(opt);
                });

                if (selectedDeviceId && cams.some(c => c.deviceId === selectedDeviceId)) {
                    cameraSelect.value = selectedDeviceId;
                } else {
                    selectedDeviceId = cameraSelect.value || cams[0].deviceId;
                    cameraSelect.value = selectedDeviceId;
                }
                cameraSelect.disabled = false;
            }

            async function requestStream() {
                const baseConstraints = { audio: false, video: {} };
                if (selectedDeviceId) {
                    baseConstraints.video = { deviceId: { exact: selectedDeviceId } };
                } else {
                    // This may fail on some laptops; we fallback to video:true below.
                    baseConstraints.video = { facingMode: { ideal: 'environment' } };
                }

                try {
                    return await navigator.mediaDevices.getUserMedia(baseConstraints);
                } catch (e1) {
                    return await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                }
            }

            async function startScan() {
                if (!navigator.mediaDevices?.getUserMedia) {
                    setStatus('browser tidak mendukung kamera.');
                    return;
                }

                // Prefer native BarcodeDetector, but fallback to jsQR if not available.
                const useNative = ('BarcodeDetector' in window);
                detector = null;
                if (useNative) {
                    try {
                        detector = new BarcodeDetector({ formats: ['qr_code'] });
                    } catch (e) {
                        detector = null;
                    }
                }

                // Load jsQR if we can't use native detector
                async function loadJsQr() {
                    if (window.jsQR) return true;
                    return await new Promise((resolve) => {
                        const s = document.createElement('script');
                        s.src = 'https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js';
                        s.async = true;
                        s.onload = () => resolve(true);
                        s.onerror = () => resolve(false);
                        document.head.appendChild(s);
                    });
                }

                try {
                    stream = await requestStream();
                    video.srcObject = stream;
                    await video.play();
                    await refreshDevices(); // after permission, labels become available
                } catch (e) {
                    const msg = (e && e.name) ? e.name : 'unknown_error';
                    setStatus('gagal akses kamera: ' + msg);
                    return;
                }

                btnStart.disabled = true;
                btnStop.disabled = false;
                setStatus('scan berjalan… arahkan QR ke kamera.');

                const canUseJsQr = !detector ? await loadJsQr() : false;
                if (!detector && !canUseJsQr) {
                    stopScan();
                    setStatus('Scan QR tidak tersedia: BarcodeDetector tidak ada dan gagal load jsQR (cek internet/CDN).');
                    return;
                }

                // Setup canvas for jsQR fallback
                let canvas = null;
                let ctx = null;
                if (!detector) {
                    canvas = document.createElement('canvas');
                    ctx = canvas.getContext('2d', { willReadFrequently: true });
                }

                const tick = async () => {
                    if (!video.videoWidth || !video.videoHeight) {
                        rafId = requestAnimationFrame(tick);
                        return;
                    }

                    try {
                        let value = '';

                        if (detector) {
                            const barcodes = await detector.detect(video);
                            if (barcodes && barcodes.length) {
                                value = (barcodes[0].rawValue || '').trim();
                            }
                        } else if (window.jsQR && ctx && canvas) {
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            const code = window.jsQR(imageData.data, imageData.width, imageData.height, {
                                inversionAttempts: 'dontInvert',
                            });
                            value = (code && code.data) ? String(code.data).trim() : '';
                        }

                        if (value) {
                            input.value = value;
                            setStatus('QR terdeteksi: ' + value);
                            stopScan();
                            // Auto submit (optional). Comment line below if you don't want auto submit.
                            form.requestSubmit();
                            return;
                        }
                    } catch (e) {
                        // ignore and continue
                    }
                    rafId = requestAnimationFrame(tick);
                };

                rafId = requestAnimationFrame(tick);
            }

            btnStart.addEventListener('click', startScan);
            btnStop.addEventListener('click', stopScan);
            cameraSelect.addEventListener('change', async (e) => {
                selectedDeviceId = e.target.value || '';
                if (btnStart.disabled) {
                    stopScan();
                    await startScan();
                }
            });

            // Stop camera when leaving page
            window.addEventListener('beforeunload', stopScan);

            // Debug info
            const secure = window.isSecureContext ? 'secure' : 'not-secure';
            const hasBD = ('BarcodeDetector' in window) ? 'BarcodeDetector:yes' : 'BarcodeDetector:no';
            setDebug(`${secure} • ${hasBD}`);
            refreshDevices().catch(() => {});
        })();
    </script>

    @if (session('checkin_info'))
        <script>
            window.addEventListener('load', function () {
                const el = document.getElementById('checkinSuccessModal');
                if (el && window.bootstrap?.Modal) {
                    new bootstrap.Modal(el).show();
                }

                const btnAgain = document.getElementById('btnCheckinAgain');
                const input = document.getElementById('bookingCodeInput');
                if (btnAgain && input) {
                    btnAgain.addEventListener('click', function () {
                        setTimeout(() => input.focus(), 50);
                    });
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            window.addEventListener('load', function () {
                const input = document.getElementById('bookingCodeInput');
                if (input) input.focus();
            });
        </script>
    @endif
@endsection

