@extends('layouts.app')

@section('title', 'Progress Tugas - NawaTara InternTrack')

@section('header', 'Progress Tugas')

@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
        <div class="intern-stat-card card cursor-default rounded-lg p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs text-gray-400">Total Tugas</p>
            <p class="mt-1 text-2xl font-semibold text-white">{{ $totalTasks }}</p>
        </div>
        <div class="intern-stat-card card cursor-default rounded-lg p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs text-gray-400">Disetujui</p>
            <p class="mt-1 text-2xl font-semibold text-green-400">{{ $completedTasks }}</p>
        </div>
        <div class="intern-stat-card card cursor-default rounded-lg p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs text-gray-400">Menunggu Review</p>
            <p class="mt-1 text-2xl font-semibold text-yellow-400">{{ $awaitingReviewTasks }}</p>
        </div>
        <div class="intern-stat-card card cursor-default rounded-lg p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs text-gray-400">Perlu Revisi</p>
            <p class="mt-1 text-2xl font-semibold text-red-400">{{ $revisionTasks }}</p>
        </div>
    </div>

    <section class="card rounded-xl p-5">
        <div class="mb-3 flex items-center justify-between gap-3">
            <h2 class="text-base font-semibold text-white">Progress Penyelesaian</h2>
            <span class="text-sm font-medium text-blue">{{ number_format($completionPercentage, 1) }}%</span>
        </div>
        <div class="h-3 w-full rounded-full bg-gray-700" role="progressbar" aria-label="Progress penyelesaian tugas" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ round($completionPercentage) }}">
            <div class="h-3 rounded-full bg-blue transition-all duration-500" style="width: {{ $completionPercentage }}%"></div>
        </div>
        <p class="mt-2 text-xs text-gray-400">{{ $completedTasks }} dari {{ $totalTasks }} tugas disetujui</p>
    </section>

    <section class="card rounded-xl p-5">
        <h2 class="mb-4 text-base font-semibold text-white">Riwayat Evaluasi</h2>
        <div class="space-y-3">
            @forelse($evaluations as $evaluation)
            <article class="border-l-2 border-blue pl-3">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-medium text-white">{{ $evaluation->task->title }}</h3>
                        <p class="text-xs text-gray-400">{{ $evaluation->task->category }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded px-2 py-1 text-xs @if($evaluation->status === 'Disetujui') bg-green-500/20 text-green-400 @else bg-red-500/20 text-red-400 @endif">{{ $evaluation->status }}</span>
                        <time class="text-xs text-gray-400">{{ $evaluation->updated_at?->format('d M Y, H:i') }}</time>
                    </div>
                </div>
                <div class="mt-2 rounded bg-[#111c3a] px-3 py-2">
                    <p class="mb-1 text-xs text-gray-400">Feedback Supervisor</p>
                    <p class="text-sm text-white">{{ $evaluation->supervisor_feedback ?: 'Tidak ada catatan feedback.' }}</p>
                </div>
            </article>
            @empty
            <p class="py-4 text-center text-sm text-gray-400">Belum ada evaluasi dari supervisor</p>
            @endforelse
        </div>
    </section>
</div>
@endsection