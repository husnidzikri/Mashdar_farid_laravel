@extends('layouts.app')

@section('title', 'Presensi - NawaTara InternTrack')

@section('header', 'Presensi')

@section('content')
<div class="space-y-6">
    <section class="card overflow-hidden rounded-xl p-4 sm:p-5 lg:p-6">
        <div class="grid items-stretch gap-4 lg:grid-cols-2 lg:gap-6">
            <div class="attendance-intro-panel">
                <div>
                    <p class="text-sm font-medium text-blue">{{ now('Asia/Jakarta')->translatedFormat('l, d F Y') }} · WIB</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">Presensi Harian</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-400">Catat waktu masuk dan pulang Anda. Absen masuk setelah pukul 08.00 WIB akan tercatat sebagai terlambat.</p>
                </div>
                <div class="attendance-clock-panel mt-6 rounded-lg border border-gray-200 bg-gray-50 px-4 py-4">
                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-500">Waktu saat ini</p>
                    <div class="mt-1 flex flex-wrap items-baseline justify-between gap-2">
                        <div class="flex items-baseline gap-2">
                            <time id="attendance-live-clock" class="font-mono text-2xl font-semibold tabular-nums tracking-tight text-white sm:text-3xl" aria-label="Jam saat ini">--:--:--</time>
                            <span class="text-xs font-semibold text-blue">WIB</span>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 border-t border-gray-200 pt-3 text-xs">
                        <div class="rounded-lg bg-white px-3 py-2">
                            <p class="text-gray-500">Jam masuk</p>
                            <p class="mt-1 font-semibold text-gray-800">08.00 WIB</p>
                        </div>
                        <div class="rounded-lg bg-white px-3 py-2">
                            <p class="text-gray-500">Jam pulang</p>
                            <p class="mt-1 font-semibold text-gray-800">17.00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="attendance-form-panel w-full rounded-xl border border-gray-200 bg-white p-4 sm:p-5">
                @if(session('success'))
                    <div class="mb-4 flex items-center gap-2 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300" role="status">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>{{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 flex items-center gap-2 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300" role="alert">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>{{ session('error') }}
                    </div>
                @endif
                @error('keterangan')
                    <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300" role="alert">{{ $message }}</div>
                @enderror

                @if(!$todayAttendance)
                    <form action="{{ route('intern.attendance.check-in') }}" method="POST" enctype="multipart/form-data" class="space-y-3" data-attendance-form>
                        @csrf
                        <div>
                            <label for="attendance-work-mode" class="mb-1.5 block text-sm font-medium text-gray-300">Mode kerja</label>
                            <select id="attendance-work-mode" name="keterangan" required class="w-full rounded-lg border border-gray-700 bg-[#0B132B] px-3 py-3 text-sm text-white transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="" disabled {{ old('keterangan') ? '' : 'selected' }}>Pilih mode kerja</option>
                                <option value="WFO" {{ old('keterangan') === 'WFO' ? 'selected' : '' }}>WFO (Work From Office)</option>
                                <option value="WFH" {{ old('keterangan') === 'WFH' ? 'selected' : '' }}>WFH (Work From Home)</option>
                            </select>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Bukti foto kehadiran</p>
                                <p class="mt-1 text-xs leading-5 text-gray-600">Ambil foto langsung dengan kamera sebelum melakukan presensi.</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-700">Foto kehadiran (wajib)</p>
                                <div class="overflow-hidden rounded-lg border border-gray-200 bg-gray-900">
                                    <video class="hidden max-h-64 w-full object-cover" data-camera-video autoplay playsinline muted></video>
                                    <img class="hidden max-h-64 w-full object-contain" data-camera-preview alt="Pratinjau foto kehadiran">
                                    <canvas class="hidden" data-camera-canvas></canvas>
                                    <div class="flex min-h-32 items-center justify-center px-4 py-6 text-center text-sm text-gray-300" data-camera-placeholder>
                                        Kamera belum diaktifkan.
                                    </div>
                                </div>
                                <input type="file" name="attendance_photo" accept="image/jpeg" class="hidden" data-camera-photo>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" class="inline-flex min-h-9 items-center gap-2 rounded-lg bg-blue px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" data-camera-start>
                                        <i class="fas fa-camera" aria-hidden="true"></i>Aktifkan Kamera
                                    </button>
                                    <button type="button" class="hidden min-h-9 items-center gap-2 rounded-lg bg-blue px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" data-camera-capture>
                                        <i class="fas fa-circle" aria-hidden="true"></i>Ambil Foto
                                    </button>
                                    <button type="button" class="hidden min-h-9 items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" data-camera-retake>
                                        <i class="fas fa-rotate-left" aria-hidden="true"></i>Ambil Ulang
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600" data-camera-status role="status" aria-live="polite">Foto kamera langsung diperlukan untuk melanjutkan.</p>
                                @error('attendance_photo')
                                    <p class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-blue px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue/10 transition hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-[#0F1829]">
                            <i class="fas fa-sign-in-alt" aria-hidden="true"></i><span>Absen Masuk</span>
                        </button>
                    </form>
                @elseif($todayAttendance->check_in_at && !$todayAttendance->check_out_at)
                    <form action="{{ route('intern.attendance.check-out') }}" method="POST" enctype="multipart/form-data" class="space-y-3" data-attendance-form>
                            @csrf
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Bukti foto kehadiran</p>
                                    <p class="mt-1 text-xs leading-5 text-gray-600">Ambil foto langsung dengan kamera sebelum melakukan presensi.</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm font-medium text-gray-700">Foto kehadiran (wajib)</p>
                                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-gray-900">
                                        <video class="hidden max-h-64 w-full object-cover" data-camera-video autoplay playsinline muted></video>
                                        <img class="hidden max-h-64 w-full object-contain" data-camera-preview alt="Pratinjau foto kehadiran">
                                        <canvas class="hidden" data-camera-canvas></canvas>
                                        <div class="flex min-h-32 items-center justify-center px-4 py-6 text-center text-sm text-gray-300" data-camera-placeholder>
                                            Kamera belum diaktifkan.
                                        </div>
                                    </div>
                                    <input type="file" name="check_out_photo" accept="image/jpeg" class="hidden" data-camera-photo>
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" class="inline-flex min-h-9 items-center gap-2 rounded-lg bg-blue px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" data-camera-start>
                                            <i class="fas fa-camera" aria-hidden="true"></i>Aktifkan Kamera
                                        </button>
                                        <button type="button" class="hidden min-h-9 items-center gap-2 rounded-lg bg-blue px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" data-camera-capture>
                                            <i class="fas fa-circle" aria-hidden="true"></i>Ambil Foto
                                        </button>
                                        <button type="button" class="hidden min-h-9 items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" data-camera-retake>
                                            <i class="fas fa-rotate-left" aria-hidden="true"></i>Ambil Ulang
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-600" data-camera-status role="status" aria-live="polite">Foto kamera langsung diperlukan untuk melanjutkan.</p>
                                    @error('check_out_photo')
                                        <p class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-amber-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/10 transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-[#0F1829]">
                                <i class="fas fa-sign-out-alt" aria-hidden="true"></i><span>Absen Pulang</span>
                            </button>
                    </form>
                @elseif($todayAttendance->check_out_at)
                    <div class="flex items-center gap-3 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-4 text-green-300" role="status">
                        <i class="fas fa-check-circle text-xl" aria-hidden="true"></i>
                        <div>
                            <p class="font-semibold">Presensi Hari Ini Selesai</p>
                            <p class="mt-1 text-sm text-green-200/80">Masuk {{ substr($todayAttendance->check_in_at, 0, 5) }} · Pulang {{ substr($todayAttendance->check_out_at, 0, 5) }} WIB</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 rounded-lg border border-gray-700 bg-[#0B132B]/70 px-4 py-4 text-gray-300" role="status">
                        <i class="fas fa-info-circle text-blue" aria-hidden="true"></i>
                        Presensi hari ini sudah tercatat.
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="intern-attendance-stat card cursor-default rounded-xl p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-gray-400 text-sm">Total Hadir</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $attendanceCounts->get('Hadir', 0) + $attendanceCounts->get('Terlambat', 0) }}</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="m8.5 12 2.25 2.25L15.75 9"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="intern-attendance-stat card cursor-default rounded-xl p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-gray-400 text-sm">Total Izin</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $attendanceCounts->get('Izin', 0) }}</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="intern-attendance-stat card cursor-default rounded-xl p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-gray-400 text-sm">Total Sakit</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $attendanceCounts->get('Sakit', 0) }}</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M10 5h4v5h5v4h-5v5h-4v-5H5v-4h5z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="intern-attendance-stat card cursor-default rounded-xl p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-gray-400 text-sm">Persentase Kehadiran</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($attendancePercentage, 1) }}%</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3v9h9"></path>
                        <path d="M20.2 15A9 9 0 1 1 9 3.8"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="card rounded-xl p-5 sm:p-6">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <h3 class="text-lg font-semibold text-white">Riwayat Kehadiran</h3>
            <p class="text-xs text-gray-500">Jam ditampilkan dalam WIB</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm border-b border-gray-700">
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Absen Masuk</th>
                        <th class="pb-3">Absen Pulang</th>
                        <th class="pb-3">Keterangan</th>
                        <th class="pb-3 text-center">Foto Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($attendances as $attendance)
                    <tr class="table-row border-b border-gray-800">
                        <td class="py-3 text-white">{{ $attendance->date->format('d M Y') }}</td>
                        <td class="py-3">
                            @if($attendance->status === 'Hadir')
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Hadir</span>
                            @elseif($attendance->status === 'Terlambat')
                                <span class="px-2 py-1 bg-orange-500/20 text-orange-300 rounded text-xs">Terlambat</span>
                            @elseif($attendance->status === 'Izin')
                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs">Izin</span>
                            @else
                                <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded text-xs">Sakit</span>
                            @endif
                        </td>
                        <td class="py-3 text-gray-300">{{ $attendance->check_in_at ? substr($attendance->check_in_at, 0, 5) . ' WIB' : '-' }}</td>
                        <td class="py-3 text-gray-300">{{ $attendance->check_out_at ? substr($attendance->check_out_at, 0, 5) . ' WIB' : '-' }}</td>
                        <td class="py-3 text-gray-300">{{ $attendance->keterangan ?? '-' }}</td>
                        <td class="py-3 align-middle">
                            @if($attendance->attendance_photo)
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('intern.attendance.photo', ['id' => $attendance->id, 'type' => 'check-in']) }}" class="block" data-attendance-photo aria-label="Lihat foto absen masuk">
                                        <img src="{{ route('intern.attendance.photo', ['id' => $attendance->id, 'type' => 'check-in']) }}" alt="Foto absen masuk" title="Foto absen masuk" loading="lazy" class="h-10 w-10 rounded-lg border border-gray-200 object-cover shadow-sm transition hover:scale-105">
                                    </a>
                                </div>
                            @endif
                            @if($attendance->check_out_photo)
                                <div class="mt-2 flex items-center justify-center gap-2">
                                    <a href="{{ route('intern.attendance.photo', ['id' => $attendance->id, 'type' => 'check-out']) }}" class="block" data-attendance-photo aria-label="Lihat foto absen pulang">
                                        <img src="{{ route('intern.attendance.photo', ['id' => $attendance->id, 'type' => 'check-out']) }}" alt="Foto absen pulang" title="Foto absen pulang" loading="lazy" class="h-10 w-10 rounded-lg border border-gray-200 object-cover shadow-sm transition hover:scale-105">
                                    </a>
                                </div>
                            @endif
                            @if(!$attendance->attendance_photo && !$attendance->check_out_photo)
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400">Belum ada data presensi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $attendances->links() }}
    </div>
</div>

<div id="attendance-photo-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/75 p-4" role="dialog" aria-modal="true" aria-label="Foto bukti presensi">
    <div class="relative w-full max-w-3xl rounded-xl bg-white p-3 shadow-2xl sm:p-4">
        <button type="button" class="absolute right-5 top-5 z-10 rounded-full bg-black/60 px-3 py-1.5 text-sm font-medium text-white hover:bg-black/80" data-photo-modal-close aria-label="Tutup foto">Tutup</button>
        <img class="mx-auto max-h-[85vh] w-full rounded-lg object-contain" data-photo-modal-image alt="Foto bukti presensi ukuran penuh">
    </div>
</div>

@if(session('attendance_check_in_success'))
    <div id="attendance-success-toast" role="status" aria-live="polite" class="fixed left-1/2 top-6 z-50 flex w-[calc(100%-2rem)] max-w-md -translate-x-1/2 items-center gap-4 rounded-xl border border-green-500/30 bg-[#0F1829] px-5 py-4 text-white shadow-xl shadow-black/30 transition duration-300 ease-out">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-500/15">
            <i class="fas fa-check text-lg text-green-400" aria-hidden="true"></i>
        </span>
        <span>
            <span class="block text-sm font-semibold text-green-300">Absen Masuk Berhasil</span>
            <span class="mt-1 block text-sm text-gray-300">{{ session('attendance_check_in_success') }}</span>
        </span>
    </div>
@endif

@push('scripts')
<script>
    const attendancePhotoModal = document.getElementById('attendance-photo-modal');
    const attendancePhotoModalImage = attendancePhotoModal.querySelector('[data-photo-modal-image]');
    const closeAttendancePhotoModal = () => {
        attendancePhotoModal.classList.add('hidden');
        attendancePhotoModal.classList.remove('flex');
        attendancePhotoModalImage.removeAttribute('src');
    };
    document.querySelectorAll('[data-attendance-photo]').forEach((photoLink) => {
        photoLink.addEventListener('click', (event) => {
            event.preventDefault();
            attendancePhotoModalImage.src = photoLink.href;
            attendancePhotoModal.classList.remove('hidden');
            attendancePhotoModal.classList.add('flex');
        });
    });
    attendancePhotoModal.querySelectorAll('[data-photo-modal-close]').forEach((button) => {
        button.addEventListener('click', closeAttendancePhotoModal);
    });
    attendancePhotoModal.addEventListener('click', (event) => {
        if (event.target === attendancePhotoModal) closeAttendancePhotoModal();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeAttendancePhotoModal();
    });

    document.querySelectorAll('[data-attendance-form]').forEach((form) => {
        const cameraStartButton = form.querySelector('[data-camera-start]');
        const cameraCaptureButton = form.querySelector('[data-camera-capture]');
        const cameraRetakeButton = form.querySelector('[data-camera-retake]');
        const cameraVideo = form.querySelector('[data-camera-video]');
        const cameraPreview = form.querySelector('[data-camera-preview]');
        const cameraCanvas = form.querySelector('[data-camera-canvas]');
        const cameraPlaceholder = form.querySelector('[data-camera-placeholder]');
        const cameraPhoto = form.querySelector('[data-camera-photo]');
        const cameraStatus = form.querySelector('[data-camera-status]');
        let cameraStream = null;
        let cameraPreviewUrl = null;

        const stopCamera = () => {
            if (cameraStream) {
                cameraStream.getTracks().forEach((track) => track.stop());
                cameraStream = null;
            }
            cameraVideo.srcObject = null;
        };

        const startCamera = async () => {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                cameraStatus.textContent = 'Kamera tidak tersedia. Buka halaman melalui HTTPS atau localhost dan gunakan browser yang mendukung kamera.';
                cameraStatus.classList.add('text-red-600');
                return;
            }

            cameraStartButton.disabled = true;
            cameraStatus.classList.remove('text-red-600', 'text-green-700');
            cameraStatus.textContent = 'Meminta izin kamera...';
            try {
                stopCamera();
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    audio: false,
                    video: { facingMode: 'user' }
                });
                cameraVideo.srcObject = cameraStream;
                await cameraVideo.play();
                cameraVideo.classList.remove('hidden');
                cameraPreview.classList.add('hidden');
                cameraPlaceholder.classList.add('hidden');
                cameraCaptureButton.classList.remove('hidden');
                cameraStartButton.classList.add('hidden');
                cameraRetakeButton.classList.add('hidden');
                cameraStatus.textContent = 'Posisikan wajah di dalam bingkai, lalu ambil foto.';
            } catch (error) {
                stopCamera();
                const messages = {
                    NotAllowedError: 'Izin kamera ditolak. Aktifkan izin kamera pada browser untuk melanjutkan.',
                    NotFoundError: 'Kamera tidak ditemukan pada perangkat ini.',
                    NotReadableError: 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.'
                };
                cameraStatus.textContent = messages[error.name] || 'Kamera gagal diaktifkan. Periksa izin perangkat lalu coba lagi.';
                cameraStatus.classList.add('text-red-600');
                cameraStartButton.disabled = false;
            }
        };

        cameraStartButton.addEventListener('click', startCamera);
        cameraRetakeButton.addEventListener('click', () => {
            cameraPhoto.value = '';
            if (cameraPreviewUrl) URL.revokeObjectURL(cameraPreviewUrl);
            cameraPreviewUrl = null;
            cameraPreview.removeAttribute('src');
            cameraPreview.classList.add('hidden');
            cameraRetakeButton.classList.add('hidden');
            cameraPlaceholder.classList.remove('hidden');
            cameraStartButton.classList.remove('hidden');
            cameraStartButton.disabled = false;
            cameraStatus.textContent = 'Ambil foto ulang untuk melanjutkan presensi.';
            startCamera();
        });
        cameraCaptureButton.addEventListener('click', () => {
            if (!cameraVideo.videoWidth || !cameraVideo.videoHeight) {
                cameraStatus.textContent = 'Kamera belum siap. Tunggu sebentar lalu coba lagi.';
                cameraStatus.classList.add('text-red-600');
                return;
            }

            const scale = Math.min(1, 1280 / cameraVideo.videoWidth, 1280 / cameraVideo.videoHeight);
            cameraCanvas.width = Math.round(cameraVideo.videoWidth * scale);
            cameraCanvas.height = Math.round(cameraVideo.videoHeight * scale);
            cameraCanvas.getContext('2d').drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);
            cameraCaptureButton.disabled = true;
            cameraCanvas.toBlob((blob) => {
                if (!blob) {
                    cameraStatus.textContent = 'Foto gagal diproses. Silakan ambil ulang.';
                    cameraStatus.classList.add('text-red-600');
                    cameraCaptureButton.disabled = false;
                    return;
                }

                try {
                    const file = new File([blob], 'attendance-selfie.jpg', { type: 'image/jpeg' });
                    const transfer = new DataTransfer();
                    transfer.items.add(file);
                    cameraPhoto.files = transfer.files;
                    cameraPreviewUrl = URL.createObjectURL(blob);
                    cameraPreview.src = cameraPreviewUrl;
                    cameraVideo.classList.add('hidden');
                    cameraPreview.classList.remove('hidden');
                    cameraCaptureButton.classList.add('hidden');
                    cameraRetakeButton.classList.remove('hidden');
                    cameraCaptureButton.disabled = false;
                    cameraStatus.textContent = 'Foto siap digunakan. Anda dapat mengambil ulang sebelum mengirim presensi.';
                    cameraStatus.classList.remove('text-red-600');
                    cameraStatus.classList.add('text-green-700');
                    stopCamera();
                } catch (error) {
                    cameraStatus.textContent = 'Browser tidak dapat menyiapkan foto kamera. Gunakan browser versi terbaru lalu coba lagi.';
                    cameraStatus.classList.add('text-red-600');
                    cameraCaptureButton.disabled = false;
                }
            }, 'image/jpeg', 0.82);
        });

        form.addEventListener('submit', (event) => {
            if (!cameraPhoto.files.length) {
                event.preventDefault();
                cameraStatus.textContent = 'Ambil foto langsung dengan kamera sebelum mengirim presensi.';
                cameraStatus.classList.add('text-red-600');
                cameraStartButton.focus();
            }
        });

        window.addEventListener('pagehide', () => {
            stopCamera();
            if (cameraPreviewUrl) URL.revokeObjectURL(cameraPreviewUrl);
        }, { once: true });
    });

    const attendanceLiveClock = document.getElementById('attendance-live-clock');
    if (attendanceLiveClock) {
        const updateAttendanceClock = () => {
            attendanceLiveClock.textContent = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hourCycle: 'h23'
            }).format(new Date());
        };
        updateAttendanceClock();
        window.setInterval(updateAttendanceClock, 1000);
    }

    const attendanceSuccessToast = document.getElementById('attendance-success-toast');
    if (attendanceSuccessToast) {
        window.setTimeout(() => {
            attendanceSuccessToast.classList.add('translate-y-2', 'opacity-0');
            window.setTimeout(() => attendanceSuccessToast.remove(), 300);
        }, 3500);
    }
</script>
@endpush
@endsection
