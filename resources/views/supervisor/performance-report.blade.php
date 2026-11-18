<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Performa - {{ $intern->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4;
            margin: 16mm 15mm;
        }

        @media print {
            html,
            body {
                width: 100%;
                min-height: 0;
                background: #fff !important;
                color: #0f172a !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body {
                padding: 0 !important;
                font-size: 10pt;
            }

            .no-print {
                display: none !important;
            }

            .report-shell {
                max-width: none !important;
                margin: 0 !important;
            }

            .report-card {
                break-inside: avoid;
                border-color: #dbe2ea !important;
                background: #fff !important;
                box-shadow: none !important;
            }

            .stat-card {
                background: #f8fafc !important;
            }

            .signature-section {
                break-inside: avoid;
            }

            .report-footer {
                color: #64748b !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 px-4 py-8 font-sans text-slate-900 antialiased sm:px-6">
    @php
        $totalAttendance = $attendances->count();
        $present = $attendances->whereIn('status', ['Hadir', 'Terlambat'])->count();
        $attendancePercent = $totalAttendance > 0 ? ($present / $totalAttendance) * 100 : 0;
        $approved = $submissions->where('status', 'Disetujui')->count();
        $totalTasks = $submissions->count();
        $inProgress = $submissions->whereIn('status', ['Belum Dikerjakan', 'Dalam Proses', 'Menunggu Review'])->count();
        $revisions = $submissions->where('status', 'Revisi')->count();
        $taskProgress = $totalTasks > 0 ? ($approved / $totalTasks) * 100 : 0;
        $printedAt = now()->timezone('Asia/Jakarta');
    @endphp

    <main class="report-shell mx-auto max-w-4xl">
        <div class="no-print mb-5 flex items-center justify-between gap-3">
            <a href="{{ route('supervisor.performance') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-600">
                <span aria-hidden="true">&larr;</span> Kembali
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M7 14h10v7H7z" />
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>

        <article class="report-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-10">
            <header class="border-b-2 border-slate-800 pb-5">
                <div class="flex items-center justify-between gap-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-blue-300">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 20V9l8-5 8 5v11M8 20v-6h8v6M2 20h20" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-800">NawaTara InternTrack</p>
                            <p class="mt-1 text-sm text-slate-500">Human Resources &amp; Internship Development</p>
                        </div>
                    </div>
                    <div class="hidden text-right sm:block">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Dokumen internal</p>
                        <p class="mt-1 text-sm font-medium text-slate-800">Laporan evaluasi kinerja</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Laporan evaluasi</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Rekapitulasi Performa Intern</h1>
                    <p class="mt-2 text-sm text-slate-600">Ringkasan kinerja, progres tugas, dan kehadiran intern.</p>
                </div>
            </header>

            <section class="mt-6" aria-labelledby="intern-profile-heading">
                <div class="mb-3 flex items-center justify-between">
                    <h2 id="intern-profile-heading" class="text-sm font-bold uppercase tracking-wider text-slate-800">Informasi Intern</h2>
                    <span class="rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-600">Periode: {{ $printedAt->translatedFormat('F Y') }}</span>
                </div>
                <dl class="grid grid-cols-1 overflow-hidden rounded-xl border border-slate-200 sm:grid-cols-2">
                    <div class="border-b border-slate-200 p-4 sm:border-r">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Nama Lengkap</dt>
                        <dd class="mt-1.5 text-sm font-semibold text-slate-900">{{ $intern->name }}</dd>
                    </div>
                    <div class="border-b border-slate-200 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Divisi</dt>
                        <dd class="mt-1.5 text-sm font-semibold text-slate-900">{{ $intern->division ?: '-' }}</dd>
                    </div>
                    <div class="border-b border-slate-200 p-4 sm:border-b-0 sm:border-r">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Email</dt>
                        <dd class="mt-1.5 break-all text-sm font-semibold text-slate-900">{{ $intern->email }}</dd>
                    </div>
                    <div class="p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Periode Laporan</dt>
                        <dd class="mt-1.5 text-sm font-semibold text-slate-900">{{ $printedAt->translatedFormat('F Y') }}</dd>
                    </div>
                </dl>
            </section>

            <section class="mt-7" aria-labelledby="attendance-heading">
                <div class="mb-3 flex items-end justify-between gap-3">
                    <div>
                        <h2 id="attendance-heading" class="text-base font-bold text-slate-900">Rekap Presensi</h2>
                        <p class="mt-1 text-xs text-slate-500">Ringkasan catatan presensi yang tersedia.</p>
                    </div>
                    <span class="text-xs font-medium text-slate-500">Total catatan: {{ $totalAttendance }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="stat-card rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-2xl font-bold tracking-tight text-emerald-700">{{ $present }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-600">Hadir</p>
                    </div>
                    <div class="stat-card rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-2xl font-bold tracking-tight text-amber-700">{{ $attendances->where('status', 'Izin')->count() }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-600">Izin</p>
                    </div>
                    <div class="stat-card rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-2xl font-bold tracking-tight text-rose-700">{{ $attendances->where('status', 'Sakit')->count() }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-600">Sakit</p>
                    </div>
                    <div class="stat-card rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-2xl font-bold tracking-tight text-blue-800">{{ number_format($attendancePercent, 1) }}%</p>
                        <p class="mt-1 text-xs font-medium text-slate-600">Persentase Hadir</p>
                    </div>
                </div>
            </section>

            <section class="mt-7" aria-labelledby="tasks-heading">
                <div class="mb-3">
                    <h2 id="tasks-heading" class="text-base font-bold text-slate-900">Rekap Penyelesaian Tugas</h2>
                    <p class="mt-1 text-xs text-slate-500">Status tugas berdasarkan data yang tercatat pada sistem.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="stat-card rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-2xl font-bold tracking-tight text-slate-900">{{ $totalTasks }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-600">Total Tugas</p>
                    </div>
                    <div class="stat-card rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-2xl font-bold tracking-tight text-emerald-700">{{ $approved }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-600">Disetujui</p>
                    </div>
                    <div class="stat-card rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-2xl font-bold tracking-tight text-amber-700">{{ $inProgress }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-600">Dalam Proses</p>
                    </div>
                    <div class="stat-card rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-2xl font-bold tracking-tight text-rose-700">{{ $revisions }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-600">Perlu Revisi</p>
                    </div>
                </div>

                <div class="stat-card mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <span class="text-sm font-semibold text-slate-700">Tingkat persetujuan tugas</span>
                        <span class="text-sm font-bold tabular-nums text-slate-900">{{ number_format($taskProgress, 1) }}%</span>
                    </div>
                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-200" role="progressbar" aria-label="Tingkat persetujuan tugas" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ number_format($taskProgress, 1) }}">
                        <div class="h-full rounded-full bg-blue-700" style="width: {{ $taskProgress }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">{{ $approved }} dari {{ $totalTasks }} tugas telah disetujui.</p>
                </div>
            </section>

            <section class="signature-section mt-10 border-t border-slate-200 pt-6" aria-label="Pengesahan laporan">
                <div class="mb-6 flex items-end justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Pengesahan</h2>
                        <p class="mt-1 text-xs text-slate-500">Laporan ini ditinjau dan disahkan oleh pihak terkait.</p>
                    </div>
                    <p class="text-right text-xs text-slate-500">Tanggal cetak<br><span class="mt-1 inline-block font-semibold text-slate-700">{{ $printedAt->translatedFormat('d F Y, H:i') }} WIB</span></p>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-16">
                    <div class="flex min-h-32 flex-col">
                        <p class="text-center text-sm font-medium text-slate-700">Intern</p>
                        <div class="mt-auto border-b border-slate-400"></div>
                        <p class="mt-2 text-center text-sm font-semibold text-slate-900">{{ $intern->name }}</p>
                        <p class="mt-1 text-center text-xs text-slate-500">Tanggal: ____________________</p>
                    </div>
                    <div class="flex min-h-32 flex-col">
                        <p class="text-center text-sm font-medium text-slate-700">Supervisor</p>
                        <div class="mt-auto border-b border-slate-400"></div>
                        <p class="mt-2 text-center text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="mt-1 text-center text-xs text-slate-500">Tanggal: ____________________</p>
                    </div>
                </div>
            </section>

            <footer class="report-footer mt-8 border-t border-slate-200 pt-4 text-center text-xs text-slate-500">
                Dokumen ini dibuat secara otomatis melalui NawaTara InternTrack pada {{ $printedAt->translatedFormat('d F Y, H:i') }} WIB.
            </footer>
        </article>
    </main>
</body>
</html>
