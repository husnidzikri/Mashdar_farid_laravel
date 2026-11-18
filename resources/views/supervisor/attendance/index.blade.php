@extends('layouts.app')

@section('title', 'Rekap Presensi Intern - NawaTara InternTrack')



@section('content')
<div class="space-y-6">
    <!-- Attendance Filters -->
    <div class="card rounded-xl p-4">
        <h3 class="text-base font-semibold text-white mb-3">Filter Presensi</h3>
        <form action="{{ route('supervisor.attendance') }}" method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div>
                <label for="intern_id" class="mb-1 block text-xs text-gray-300">Nama Intern</label>
                <select id="intern_id" name="intern_id" onchange="this.form.requestSubmit()" class="w-full rounded-lg border border-gray-700 bg-[#111c3a] px-3 py-1.5 text-sm text-white focus:border-blue-500 focus:outline-none">
                    <option value="">Semua Intern</option>
                    @foreach($interns as $intern)
                    <option value="{{ $intern->id }}" {{ request('intern_id') == $intern->id ? 'selected' : '' }}>{{ $intern->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="attendance_date" class="mb-1 block text-xs text-gray-300">Tanggal</label>
                <input id="attendance_date" type="date" name="date" value="{{ request('date') }}" onchange="this.form.requestSubmit()" style="color-scheme: dark;" class="w-full rounded-lg border border-gray-700 bg-[#111c3a] px-3 py-1.5 text-sm text-white focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label for="attendance_status" class="mb-1 block text-xs text-gray-300">Status</label>
                <select id="attendance_status" name="status" onchange="this.form.requestSubmit()" class="w-full rounded-lg border border-gray-700 bg-[#111c3a] px-3 py-1.5 text-sm text-white focus:border-blue-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="Hadir" {{ request('status') === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Terlambat" {{ request('status') === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="Izin" {{ request('status') === 'Izin' ? 'selected' : '' }}>Izin</option>
                    <option value="Sakit" {{ request('status') === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('supervisor.attendance') }}" class="inline-flex h-[34px] w-full items-center justify-center rounded-lg border border-gray-600 px-3 text-sm text-gray-300 transition hover:bg-gray-800 hover:text-white">Reset</a>
            </div>
        </form>
    </div>

    <!-- Attendance Table -->
    <div class="card rounded-xl p-6">
        <h3 class="mb-4 text-lg font-semibold text-white">Riwayat Presensi</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm border-b border-gray-700">
                        <th class="pb-3">Nama</th>
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
                        <td class="py-3 text-white">{{ $attendance->user->name }}</td>
                        <td class="py-3 text-gray-300">{{ $attendance->date->format('d M Y') }}</td>
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
                        <td class="py-3 text-gray-300">{{ $attendance->check_in_at ? substr($attendance->check_in_at, 0, 5) : '-' }}</td>
                        <td class="py-3 text-gray-300">{{ $attendance->check_out_at ? substr($attendance->check_out_at, 0, 5) : '-' }}</td>
                        <td class="py-3 text-gray-300">{{ $attendance->keterangan ?? '-' }}</td>
                        <td class="py-3 align-middle">
                            @if($attendance->attendance_photo)
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('supervisor.attendance.photo', ['id' => $attendance->id, 'type' => 'check-in']) }}" class="block" data-attendance-photo aria-label="Lihat foto absen masuk">
                                        <img src="{{ route('supervisor.attendance.photo', ['id' => $attendance->id, 'type' => 'check-in']) }}" alt="Foto absen masuk" title="Foto absen masuk" loading="lazy" class="h-10 w-10 rounded-lg border border-gray-200 object-cover shadow-sm transition hover:scale-105">
                                    </a>
                                </div>
                            @endif
                            @if($attendance->check_out_photo)
                                <div class="mt-2 flex items-center justify-center">
                                    <a href="{{ route('supervisor.attendance.photo', ['id' => $attendance->id, 'type' => 'check-out']) }}" class="block" data-attendance-photo aria-label="Lihat foto absen pulang">
                                        <img src="{{ route('supervisor.attendance.photo', ['id' => $attendance->id, 'type' => 'check-out']) }}" alt="Foto absen pulang" title="Foto absen pulang" loading="lazy" class="h-10 w-10 rounded-lg border border-gray-200 object-cover shadow-sm transition hover:scale-105">
                                    </a>
                                </div>
                            @endif
                            @if(!$attendance->attendance_photo && !$attendance->check_out_photo)
                                <span class="block text-center text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">Belum ada data presensi</td>
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
</script>
@endpush
@endsection
