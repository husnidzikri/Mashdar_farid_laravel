@extends('layouts.app')

@section('title', 'Performa Intern - NawaTara InternTrack')

@section('header', 'Performa Intern')

@section('content')
<div class="space-y-4">
    <div class="flex flex-wrap items-end justify-between gap-2">
        <div>
            <h2 class="text-lg font-semibold text-white">Analisis Performa</h2>
            <p class="mt-1 text-sm text-gray-400">Ringkasan penyelesaian dan hasil evaluasi setiap intern.</p>
        </div>
        <span class="text-xs text-gray-400">{{ $interns->count() }} intern</span>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-2 2xl:grid-cols-3">
        @forelse($interns as $intern)
        @php
            $submissions = $intern->submissions;
            $total = $submissions->count();
            $approved = $submissions->whereIn('status', ['Disetujui', 'Selesai'])->count();
            $inProgress = $submissions->whereIn('status', ['Belum Dikerjakan', 'Dalam Proses', 'Menunggu Review'])->count();
            $revisions = $submissions->where('status', 'Revisi')->count();
            $progress = $total > 0 ? ($approved / $total) * 100 : 0;
            $reviewed = $approved + $revisions;
            $approvalRate = $reviewed > 0 ? ($approved / $reviewed) * 100 : 0;
        @endphp
        <article class="card rounded-xl p-4">
            <div class="flex items-center justify-between gap-3">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-500/15 text-sm font-semibold text-blue">
                        {{ strtoupper(substr($intern->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate text-sm font-semibold text-white">{{ $intern->name }}</h3>
                        <p class="truncate text-xs text-gray-400">{{ $intern->division ?: 'Divisi belum diatur' }}</p>
                    </div>
                </div>
                <a href="{{ route('supervisor.performance.report', $intern->id) }}" class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-gray-700 px-2.5 py-1.5 text-xs text-gray-300 transition hover:border-blue-500 hover:text-blue-700" aria-label="Cetak laporan {{ $intern->name }}">
                    <i class="fas fa-print" aria-hidden="true"></i>
                    <span>Laporan</span>
                </a>
            </div>

            <div class="mt-4 flex items-center gap-4">
                <div class="relative h-[72px] w-[72px] shrink-0 rounded-full" style="background: conic-gradient(#2563EB {{ $progress }}%, #334155 0);">
                    <div class="absolute inset-1 flex flex-col items-center justify-center rounded-full bg-[#0F1829]">
                        <span class="text-sm font-semibold text-white">{{ number_format($progress, 0) }}%</span>
                        <span class="text-[10px] text-gray-400">selesai</span>
                    </div>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="grid grid-cols-2 gap-x-3 gap-y-2 sm:grid-cols-4">
                        <div>
                            <p class="text-[10px] text-gray-500">Total</p>
                            <p class="text-sm font-semibold text-white">{{ $total }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500">Disetujui</p>
                            <p class="text-sm font-semibold text-green-400">{{ $approved }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500">Proses</p>
                            <p class="text-sm font-semibold text-yellow-400">{{ $inProgress }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500">Revisi</p>
                            <p class="text-sm font-semibold text-red-400">{{ $revisions }}</p>
                        </div>
                    </div>
                    <div class="mt-3 border-t border-gray-800 pt-2">
                        <div class="flex items-center justify-between gap-2 text-xs">
                            <span class="text-gray-400">Rasio persetujuan</span>
                            <span class="font-medium text-white">{{ number_format($approvalRate, 0) }}% <span class="font-normal text-gray-500">({{ $approved }} setuju / {{ $revisions }} revisi)</span></span>
                        </div>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-gray-700">
                            <div class="h-full rounded-full bg-green-500" style="width: {{ $approvalRate }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        @empty
        <div class="card rounded-xl p-8 text-center xl:col-span-2 2xl:col-span-3">
            <i class="fas fa-chart-line mb-3 text-2xl text-gray-500" aria-hidden="true"></i>
            <p class="text-sm text-gray-400">Belum ada data intern untuk dianalisis.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
