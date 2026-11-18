@extends('layouts.app')

@section('title', 'Dashboard Supervisor - NawaTara InternTrack')

@section('header', 'Dashboard Supervisor')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('supervisor.interns') }}" class="card group flex cursor-pointer flex-col rounded-xl p-6 transition hover:border-blue-500/50 hover:bg-[#111c3a] focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Intern</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalInterns }}</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-100">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="10" cy="7" r="4"></circle>
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-blue opacity-0 transition group-hover:opacity-100 group-focus:opacity-100">Buka data intern <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></p>
        </a>

        <a href="{{ route('supervisor.attendance') }}" class="card group flex cursor-pointer flex-col rounded-xl p-6 transition hover:border-blue-500/50 hover:bg-[#111c3a] focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Hadir Hari Ini</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $todayAttendance }}</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-green-50 text-green-700 ring-1 ring-inset ring-green-100">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                        <path d="M16 3v4M8 3v4M3 11h18"></path>
                        <path d="m9 16 2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-blue-600 opacity-0 transition group-hover:opacity-100 group-focus:opacity-100">Buka presensi intern <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></p>
        </a>

        <a href="{{ route('supervisor.reviews') }}" class="card group flex cursor-pointer flex-col rounded-xl p-6 transition hover:border-yellow-500/50 hover:bg-[#111c3a] focus:outline-none focus:ring-2 focus:ring-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Menunggu Review</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $pendingTasks }}</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-100">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-yellow-400 opacity-0 transition group-hover:opacity-100 group-focus:opacity-100">Buka antrean review <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></p>
        </a>

        <a href="{{ route('supervisor.reviews') }}" class="card group flex cursor-pointer flex-col rounded-xl p-6 transition hover:border-red-500/50 hover:bg-[#111c3a] focus:outline-none focus:ring-2 focus:ring-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Perlu Revisi</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $revisionTasks }}</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-100">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 8v4M12 16h.01"></path>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-red-400 opacity-0 transition group-hover:opacity-100 group-focus:opacity-100">Buka review pekerjaan <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i></p>
        </a>
    </div>

    <!-- Supervisor Announcements -->
    <section class="card rounded-xl p-5">
        <div>
            <h2 class="text-base font-semibold text-white">Pesan untuk Intern</h2>
            <p class="mt-1 text-sm text-gray-400">Terbitkan instruksi atau pengumuman yang dapat dibaca semua intern.</p>
            <form action="{{ route('supervisor.announcements.store') }}" method="POST" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label for="announcement-message" class="mb-1 block text-sm text-gray-300">Catatan / instruksi</label>
                    <textarea id="announcement-message" name="message" rows="4" maxlength="2000" required class="w-full rounded-lg border border-gray-700 bg-[#111c3a] px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none" placeholder="Tulis pengumuman untuk semua intern...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue px-4 py-2 text-sm font-medium text-white transition hover-blue focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-bullhorn" aria-hidden="true"></i> Terbitkan
                </button>
            </form>
        </div>
    </section>

    <!-- Chart Section -->
    <div class="weekly-activity-card card cursor-default rounded-xl p-6 transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg">
        <div class="mb-5 flex flex-wrap items-end justify-between gap-2">
            <div>
                <h3 class="text-lg font-semibold text-white">Grafik Aktivitas Mingguan</h3>
                <p class="mt-1 text-sm text-gray-500">Tugas yang disetujui dalam tujuh hari terakhir</p>
            </div>
            <span class="text-xs text-gray-500">Pilih titik untuk melihat rincian</span>
        </div>
        <div id="weekly-chart-container" class="relative opacity-0 transition-opacity duration-700 ease-out">
            <canvas id="weeklyChart" height="100" aria-label="Grafik jumlah tugas yang disetujui selama tujuh hari terakhir" role="img"></canvas>
        </div>
        <section id="weekly-activity-detail" class="mt-5 hidden rounded-xl border border-blue-100 bg-slate-50 p-4 opacity-0 transition duration-300 ease-out" aria-live="polite" aria-label="Rincian aktivitas harian">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h4 id="weekly-activity-date" class="font-semibold text-gray-900"></h4>
                <span id="weekly-activity-count" class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-800"></span>
            </div>
            <ul id="weekly-activity-tasks" class="mt-3 space-y-2"></ul>
        </section>
    </div>

    <!-- Recent Tasks -->
    <div class="card rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Tugas Terbaru</h3>
            <a href="{{ route('supervisor.tasks') }}" class="group inline-flex items-center gap-1.5 rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 transition-all duration-200 hover:border-blue-200 hover:bg-blue-100 hover:text-blue-800 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:ring-offset-1">
                Lihat Semua
                <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm border-b border-gray-700">
                        <th class="pb-3">Judul Tugas</th>
                        <th class="pb-3">Kategori</th>
                        <th class="pb-3">Deadline</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($recentTasks as $task)
                    <tr class="table-row border-b border-gray-800">
                        <td class="py-3 text-white">{{ $task->title }}</td>
                        <td class="py-3 text-gray-300">
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs">{{ $task->category }}</span>
                        </td>
                        <td class="py-3 text-gray-300">{{ $task->deadline->format('d M Y') }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Aktif</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-400">Belum ada tugas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Intern Progress Summary -->
    <div class="card rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Ringkasan Progress Intern</h3>
            <a href="{{ route('supervisor.performance') }}" class="group inline-flex items-center gap-1.5 rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 transition-all duration-200 hover:border-blue-200 hover:bg-blue-100 hover:text-blue-800 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:ring-offset-1">
                Lihat Detail
                <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm border-b border-gray-700">
                        <th class="pb-3">Nama</th>
                        <th class="pb-3">Divisi</th>
                        <th class="pb-3">Total Tugas</th>
                        <th class="pb-3">Progress</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($interns as $intern)
                    <tr class="table-row border-b border-gray-800">
                        <td class="py-3 text-white">{{ $intern->name }}</td>
                        <td class="py-3 text-gray-300">{{ $intern->division }}</td>
                        <td class="py-3 text-gray-300">{{ $intern->submissions_count }}</td>
                        <td class="py-3">
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-700 rounded-full h-2 mr-2">
                                    @php
                                        $approved = $intern->submissions->where('status', 'Disetujui')->count();
                                        $total = $intern->submissions->count();
                                        $progress = $total > 0 ? ($approved / $total) * 100 : 0;
                                    @endphp
                                    <div class="bg-blue h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <span class="text-gray-400">{{ number_format($progress, 0) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-400">Belum ada intern</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(session('success'))
    <div id="success-toast" role="status" aria-live="polite" class="fixed left-1/2 top-6 z-50 flex w-[calc(100%-2rem)] max-w-md -translate-x-1/2 items-center gap-4 rounded-xl border border-green-500/30 bg-[#0F1829] px-5 py-4 text-white shadow-xl shadow-black/30 transition duration-300 ease-out">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-500/15">
            <i class="fas fa-check text-lg text-green-400" aria-hidden="true"></i>
        </span>
        <span>
            <span class="block text-sm font-semibold text-green-300">Berhasil Terbit!</span>
            <span class="mt-1 block text-sm text-gray-300">Pesan Anda telah dibagikan dan dapat dibaca oleh seluruh intern.</span>
        </span>
    </div>
@endif

@push('scripts')
<script>
    const successToast = document.getElementById('success-toast');
    if (successToast) {
        window.setTimeout(() => {
            successToast.classList.add('translate-y-2', 'opacity-0');
            window.setTimeout(() => successToast.remove(), 300);
        }, 3500);
    }

    const weeklyActivityDetails = @json($weeklyActivityDetails);
    const weeklyActivityChart = document.getElementById('weeklyChart');
    const weeklyActivityDetail = document.getElementById('weekly-activity-detail');
    const weeklyActivityDate = document.getElementById('weekly-activity-date');
    const weeklyActivityCount = document.getElementById('weekly-activity-count');
    const weeklyActivityTasks = document.getElementById('weekly-activity-tasks');
    const weeklyChartContainer = document.getElementById('weekly-chart-container');
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let selectedActivityIndex = null;
    let detailHideTimeout = null;

    if (prefersReducedMotion) {
        weeklyChartContainer.classList.remove('transition-opacity', 'duration-700');
        weeklyActivityDetail.classList.remove('transition', 'duration-300');
    }

    const weeklySelectionPlugin = {
        id: 'weeklyActivitySelection',
        afterDatasetsDraw(chart) {
            const activeElements = chart.getActiveElements();
            const activeIndex = activeElements.length ? activeElements[0].index : null;
            const highlightedIndex = activeIndex ?? selectedActivityIndex;
            if (highlightedIndex === null) return;

            const point = chart.getDatasetMeta(0).data[highlightedIndex];
            if (!point) return;
            const { x, y } = point.getProps(['x', 'y'], true);
            const { top, bottom, left, right } = chart.chartArea;
            const context = chart.ctx;

            context.save();
            context.beginPath();
            context.rect(left, top, right - left, bottom - top);
            context.clip();
            context.setLineDash([4, 5]);
            context.lineWidth = 1;
            context.strokeStyle = 'rgba(8, 145, 178, 0.28)';
            context.beginPath();
            context.moveTo(x, top);
            context.lineTo(x, bottom);
            context.moveTo(left, y);
            context.lineTo(right, y);
            context.stroke();
            context.setLineDash([]);
            context.beginPath();
            context.arc(x, y, selectedActivityIndex === highlightedIndex ? 11 : 9, 0, Math.PI * 2);
            context.lineWidth = 2;
            context.strokeStyle = selectedActivityIndex === highlightedIndex
                ? 'rgba(8, 145, 178, 0.35)'
                : 'rgba(8, 145, 178, 0.2)';
            context.stroke();
            context.restore();
        }
    };

    const renderActivityDetails = (index) => {
        const detail = weeklyActivityDetails[index];
        window.clearTimeout(detailHideTimeout);
        weeklyActivityDate.textContent = detail.date;
        weeklyActivityCount.textContent = `${detail.count} tugas disetujui`;
        weeklyActivityTasks.replaceChildren();

        if (detail.tasks.length === 0) {
            const emptyItem = document.createElement('li');
            emptyItem.className = 'text-sm text-gray-500';
            emptyItem.textContent = 'Tidak ada tugas yang disetujui pada hari ini.';
            weeklyActivityTasks.appendChild(emptyItem);
        } else {
            detail.tasks.forEach((task) => {
                const item = document.createElement('li');
                item.className = 'flex flex-wrap items-center justify-between gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm';
                const title = document.createElement('span');
                title.className = 'font-medium text-gray-800';
                title.textContent = task.title;
                const intern = document.createElement('span');
                intern.className = 'text-gray-500';
                intern.textContent = task.intern;
                item.append(title, intern);
                weeklyActivityTasks.appendChild(item);
            });
        }

        weeklyActivityDetail.classList.remove('hidden');
        requestAnimationFrame(() => weeklyActivityDetail.classList.remove('opacity-0'));
    };

    const weeklyChart = new Chart(weeklyActivityChart.getContext('2d'), {
        type: 'line',
        data: {
            labels: @json($weeklyActivityLabels),
            datasets: [{
                label: 'Tugas disetujui',
                data: @json($weeklyActivityCounts),
                borderColor: '#0891b2',
                backgroundColor: (context) => {
                    const { chart } = context;
                    const { ctx, chartArea } = chart;
                    if (!chartArea) return 'rgba(8, 145, 178, 0.12)';
                    const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                    gradient.addColorStop(0, 'rgba(8, 145, 178, 0.22)');
                    gradient.addColorStop(1, 'rgba(8, 145, 178, 0.015)');
                    return gradient;
                },
                pointRadius: 4,
                pointHoverRadius: 7,
                pointHitRadius: 14,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#0891b2',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#0891b2',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3,
                fill: true,
                tension: 0.38
            }]
        },
        plugins: [weeklySelectionPlugin],
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                duration: prefersReducedMotion ? 0 : 1100,
                easing: 'easeOutQuart',
                onComplete: () => weeklyChartContainer.classList.remove('opacity-0')
            },
            interaction: {
                mode: 'nearest',
                intersect: true
            },
            onClick: (event, elements) => {
                if (!elements.length) {
                    selectedActivityIndex = null;
                    weeklyActivityDetail.classList.add('opacity-0');
                    detailHideTimeout = window.setTimeout(() => weeklyActivityDetail.classList.add('hidden'), 250);
                    weeklyChart.update('none');
                    return;
                }

                selectedActivityIndex = elements[0].index;
                renderActivityDetails(selectedActivityIndex);
                weeklyChart.update('none');
            },
            onHover: (event, elements) => {
                event.native.target.style.cursor = elements.length ? 'pointer' : 'default';
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    borderColor: 'rgba(34, 211, 238, 0.35)',
                    borderWidth: 1,
                    cornerRadius: 12,
                    padding: 12,
                    displayColors: false,
                    caretSize: 6,
                    caretPadding: 8,
                    boxPadding: 4,
                    titleFont: { family: 'Inter, sans-serif', size: 12, weight: '600' },
                    bodyFont: { family: 'Inter, sans-serif', size: 12, weight: '500' },
                    callbacks: {
                        title: (items) => weeklyActivityDetails[items[0].dataIndex].date,
                        label: (item) => `${item.parsed.y} tugas disetujui`,
                        afterLabel: () => 'Klik titik untuk melihat rincian'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: '#6B7280', padding: 10 },
                    grid: { color: 'rgba(148, 163, 184, 0.2)', drawBorder: false }
                },
                x: {
                    ticks: { color: '#6B7280', padding: 8 },
                    grid: { display: false, drawBorder: false }
                }
            }
        }
    });
</script>
@endpush
@endsection
