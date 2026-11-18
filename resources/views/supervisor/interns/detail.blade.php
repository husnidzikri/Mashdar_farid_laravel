@extends('layouts.app')

@section('title', 'Detail Intern - NawaTara InternTrack')

@section('header', 'Detail Intern')

@section('content')
@php
    $completedTasks = $submissions->whereIn('status', ['Disetujui', 'Selesai'])->count();
    $attendanceCount = $attendances->count();
    $presentCount = $attendances->whereIn('status', ['Hadir', 'Terlambat'])->count();
    $attendanceRate = $attendanceCount > 0 ? round(($presentCount / $attendanceCount) * 100) : null;
    $divisionTone = match ($intern->division) {
        'Web Development' => 'bg-sky-50 text-sky-700 ring-sky-600/10',
        'Content Writing' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
        'UI/UX Design' => 'bg-violet-50 text-violet-700 ring-violet-600/10',
        default => 'bg-amber-50 text-amber-700 ring-amber-600/10',
    };
    $initial = strtoupper(substr($intern->name, 0, 1));
@endphp

<div class="space-y-6">
    <a href="{{ route('supervisor.interns') }}" class="group inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition duration-200 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 hover:shadow focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:ring-offset-1">
        <svg class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m14 18-6-6 6-6"></path>
        </svg>
        Kembali ke Data Intern
    </a>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="intern-profile-name">
        <div class="h-2 bg-gradient-to-r from-indigo-600 via-sky-500 to-blue-400"></div>
        <div class="p-5 sm:p-7">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex min-w-0 items-center gap-4 sm:gap-6">
                    <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-600 to-sky-500 text-3xl font-bold text-white shadow-lg shadow-indigo-200 sm:h-24 sm:w-24 sm:text-4xl" aria-hidden="true">
                        {{ $initial }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Profil intern</p>
                        <h2 id="intern-profile-name" class="mt-1 truncate text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $intern->name }}</h2>
                        <span class="mt-2 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $divisionTone }}">
                            {{ $intern->division ?: 'Divisi belum ditentukan' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:min-w-[460px]">
                    <article class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <p class="text-xs font-medium text-slate-500">Tugas selesai</p>
                        <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $completedTasks }}<span class="ml-1 text-sm font-medium text-slate-400">/ {{ $submissions->count() }}</span></p>
                    </article>
                    <article class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <p class="text-xs font-medium text-slate-500">Kehadiran</p>
                        <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $attendanceRate === null ? '-' : $attendanceRate . '%' }}</p>
                        <p class="mt-0.5 text-[11px] text-slate-500">{{ $presentCount }} dari {{ $attendanceCount }} catatan</p>
                    </article>
                    <article class="col-span-2 rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:col-span-1">
                        <p class="text-xs font-medium text-slate-500">Total presensi</p>
                        <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $attendanceCount }}</p>
                    </article>
                </div>
            </div>

            <div class="mt-6 grid gap-3 border-t border-slate-100 pt-5 sm:grid-cols-2">
                <div class="flex min-w-0 items-center gap-3 rounded-xl bg-slate-50 px-4 py-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-slate-500 ring-1 ring-slate-200">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path d="m3 7 9 6 9-6"></path>
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">Email</p>
                        <p class="truncate text-sm font-medium text-slate-700">{{ $intern->email }}</p>
                    </div>
                </div>
                <div class="flex min-w-0 items-center gap-3 rounded-xl bg-slate-50 px-4 py-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-slate-500 ring-1 ring-slate-200">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6.6 3h3l1.5 4.5-2 1.5a15 15 0 0 0 5.9 5.9l1.5-2 4.5 1.5v3A2.6 2.6 0 0 1 18.4 20 16.4 16.4 0 0 1 4 5.6 2.6 2.6 0 0 1 6.6 3Z"></path>
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">Nomor kontak</p>
                        <p class="truncate text-sm font-medium text-slate-700">{{ $intern->phone ?: 'Belum tersedia' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 sm:px-6">
            <div class="-mb-px flex gap-6" role="tablist" aria-label="Informasi intern">
                <button id="tasks-tab-button" type="button" class="detail-tab inline-flex items-center gap-2 border-b-2 border-indigo-600 px-1 py-4 text-sm font-semibold text-indigo-700 transition" role="tab" aria-selected="true" aria-controls="tasks-panel" tabindex="0" data-tab-target="tasks-panel">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 6h11M9 12h11M9 18h11"></path>
                        <path d="m3.5 6 .8.8L6 5M3.5 12l.8.8L6 11M3.5 18l.8.8L6 17"></path>
                    </svg>
                    Tugas
                    <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs text-indigo-700">{{ $submissions->count() }}</span>
                </button>
                <button id="attendance-tab-button" type="button" class="detail-tab inline-flex items-center gap-2 border-b-2 border-transparent px-1 py-4 text-sm font-semibold text-slate-500 transition hover:text-slate-800" role="tab" aria-selected="false" aria-controls="attendance-panel" tabindex="-1" data-tab-target="attendance-panel">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                        <path d="M16 3v4M8 3v4M3 11h18"></path>
                    </svg>
                    Presensi
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">{{ $attendanceCount }}</span>
                </button>
            </div>
        </div>

        <div id="tasks-panel" class="detail-tab-panel" role="tabpanel" aria-labelledby="tasks-tab-button" tabindex="0">
            <div class="px-5 py-5 sm:px-6">
                <h3 class="font-semibold text-slate-900">Daftar tugas</h3>
                <p class="mt-1 text-sm text-slate-500">Status dan tautan pengumpulan tugas intern.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left">
                    <thead class="bg-slate-50/80">
                        <tr class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th scope="col" class="px-6 py-3.5">Judul tugas</th>
                            <th scope="col" class="px-6 py-3.5">Status</th>
                            <th scope="col" class="px-6 py-3.5">Tautan pengumpulan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($submissions as $submission)
                            @php
                                $taskStatusTone = match ($submission->status) {
                                    'Disetujui', 'Selesai' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                                    'Revisi' => 'bg-rose-50 text-rose-700 ring-rose-600/10',
                                    'Menunggu Review' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                                    default => 'bg-slate-100 text-slate-700 ring-slate-500/10',
                                };
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $submission->task?->title ?? 'Tugas tidak tersedia' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $taskStatusTone }}">{{ $submission->status }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($submission->link_submission)
                                        <a href="{{ $submission->link_submission }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition duration-200 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:ring-offset-1">
                                            Buka Tautan
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M14 5h5v5M19 5l-8 8"></path>
                                                <path d="M18 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-slate-400">Belum ada tautan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <p class="font-medium text-slate-700">Belum ada tugas</p>
                                    <p class="mt-1 text-sm text-slate-500">Tugas intern akan ditampilkan di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="attendance-panel" class="detail-tab-panel" role="tabpanel" aria-labelledby="attendance-tab-button" tabindex="0" hidden>
            <div class="px-5 py-5 sm:px-6">
                <h3 class="font-semibold text-slate-900">Riwayat presensi</h3>
                <p class="mt-1 text-sm text-slate-500">Catatan kehadiran, waktu, dan bukti foto.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-left">
                    <thead class="bg-slate-50/80">
                        <tr class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th scope="col" class="px-6 py-3.5">Tanggal</th>
                            <th scope="col" class="px-6 py-3.5">Status</th>
                            <th scope="col" class="px-6 py-3.5">Masuk</th>
                            <th scope="col" class="px-6 py-3.5">Pulang</th>
                            <th scope="col" class="px-6 py-3.5">Keterangan</th>
                            <th scope="col" class="px-6 py-3.5">Bukti foto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($attendances as $attendance)
                            @php
                                $attendanceStatusTone = match ($attendance->status) {
                                    'Hadir' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                                    'Terlambat' => 'bg-orange-50 text-orange-700 ring-orange-600/10',
                                    'Izin' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                                    default => 'bg-rose-50 text-rose-700 ring-rose-600/10',
                                };
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-slate-800">{{ $attendance->date->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $attendanceStatusTone }}">{{ $attendance->status }}</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $attendance->check_in_at ? substr($attendance->check_in_at, 0, 5) : '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $attendance->check_out_at ? substr($attendance->check_out_at, 0, 5) : '-' }}</td>
                                <td class="max-w-xs px-6 py-4 text-slate-600">{{ $attendance->keterangan ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($attendance->attendance_photo)
                                            <a href="{{ route('supervisor.attendance.photo', ['id' => $attendance->id, 'type' => 'check-in']) }}" data-attendance-photo data-photo-label="Foto absen masuk" aria-label="Pratinjau foto absen masuk" class="group relative block">
                                                <img src="{{ route('supervisor.attendance.photo', ['id' => $attendance->id, 'type' => 'check-in']) }}" alt="Foto absen masuk" title="Foto absen masuk" loading="lazy" class="h-11 w-11 rounded-lg border border-slate-200 object-cover shadow-sm transition group-hover:scale-105 group-hover:shadow-md">
                                            </a>
                                        @endif
                                        @if($attendance->check_out_photo)
                                            <a href="{{ route('supervisor.attendance.photo', ['id' => $attendance->id, 'type' => 'check-out']) }}" data-attendance-photo data-photo-label="Foto absen pulang" aria-label="Pratinjau foto absen pulang" class="group relative block">
                                                <img src="{{ route('supervisor.attendance.photo', ['id' => $attendance->id, 'type' => 'check-out']) }}" alt="Foto absen pulang" title="Foto absen pulang" loading="lazy" class="h-11 w-11 rounded-lg border border-slate-200 object-cover shadow-sm transition group-hover:scale-105 group-hover:shadow-md">
                                            </a>
                                        @endif
                                        @if(!$attendance->attendance_photo && !$attendance->check_out_photo)
                                            <span class="text-slate-400">Tidak ada foto</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <p class="font-medium text-slate-700">Belum ada data presensi</p>
                                    <p class="mt-1 text-sm text-slate-500">Riwayat presensi intern akan ditampilkan di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div id="attendance-photo-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/80 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="attendance-photo-modal-title" aria-hidden="true">
    <div class="relative w-full max-w-4xl rounded-2xl bg-white p-3 shadow-2xl sm:p-5">
        <div class="mb-3 flex items-center justify-between gap-4 px-1">
            <h2 id="attendance-photo-modal-title" class="text-sm font-semibold text-slate-800">Foto bukti presensi</h2>
            <button type="button" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-200" data-photo-modal-close>
                Tutup
            </button>
        </div>
        <img class="mx-auto max-h-[80vh] w-full rounded-xl bg-slate-100 object-contain" data-photo-modal-image alt="Foto bukti presensi ukuran penuh">
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const tabButtons = Array.from(document.querySelectorAll('.detail-tab'));
        const tabPanels = Array.from(document.querySelectorAll('.detail-tab-panel'));

        const activateTab = (button, moveFocus = false) => {
            const targetId = button.dataset.tabTarget;

            tabButtons.forEach((tabButton) => {
                const isActive = tabButton === button;
                tabButton.setAttribute('aria-selected', String(isActive));
                tabButton.tabIndex = isActive ? 0 : -1;
                tabButton.classList.toggle('border-indigo-600', isActive);
                tabButton.classList.toggle('text-indigo-700', isActive);
                tabButton.classList.toggle('border-transparent', !isActive);
                tabButton.classList.toggle('text-slate-500', !isActive);
                tabButton.classList.toggle('hover:text-slate-800', !isActive);
            });

            tabPanels.forEach((panel) => {
                panel.hidden = panel.id !== targetId;
            });

            if (moveFocus) button.focus();
        };

        tabButtons.forEach((button, index) => {
            button.addEventListener('click', () => activateTab(button));
            button.addEventListener('keydown', (event) => {
                if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;

                event.preventDefault();
                const nextIndex = event.key === 'Home'
                    ? 0
                    : event.key === 'End'
                        ? tabButtons.length - 1
                        : (index + (event.key === 'ArrowRight' ? 1 : tabButtons.length - 1)) % tabButtons.length;

                activateTab(tabButtons[nextIndex], true);
            });
        });

        const photoModal = document.getElementById('attendance-photo-modal');
        const photoModalImage = photoModal.querySelector('[data-photo-modal-image]');
        const photoModalTitle = document.getElementById('attendance-photo-modal-title');
        const closeButton = photoModal.querySelector('[data-photo-modal-close]');
        let lastFocusedElement = null;

        const closePhotoModal = () => {
            photoModal.classList.add('hidden');
            photoModal.classList.remove('flex');
            photoModal.setAttribute('aria-hidden', 'true');
            photoModalImage.removeAttribute('src');
            if (lastFocusedElement) lastFocusedElement.focus();
        };

        document.querySelectorAll('[data-attendance-photo]').forEach((photoLink) => {
            photoLink.addEventListener('click', (event) => {
                event.preventDefault();
                lastFocusedElement = photoLink;
                photoModalImage.src = photoLink.href;
                photoModalTitle.textContent = photoLink.dataset.photoLabel;
                photoModal.classList.remove('hidden');
                photoModal.classList.add('flex');
                photoModal.setAttribute('aria-hidden', 'false');
                closeButton.focus();
            });
        });

        closeButton.addEventListener('click', closePhotoModal);
        photoModal.addEventListener('click', (event) => {
            if (event.target === photoModal) closePhotoModal();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !photoModal.classList.contains('hidden')) {
                closePhotoModal();
            }
        });
    })();
</script>
@endpush
