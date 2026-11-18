@extends('layouts.app')

@section('title', 'Dashboard Intern - NawaTara InternTrack')

@section('header', 'Dashboard Intern')

@section('content')
<div class="space-y-5">
    <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-white">Selamat datang, {{ auth()->user()->name }}</h2>
            <p class="mt-1 text-sm text-gray-400">{{ auth()->user()->division }}</p>
        </div>
        <a href="{{ route('intern.tasks') }}" class="group inline-flex items-center gap-2 self-start rounded-lg border border-blue-100 bg-blue-50 px-3.5 py-2 text-sm font-semibold text-blue-700 shadow-sm transition duration-200 hover:border-blue-200 hover:bg-blue-100 hover:text-blue-800 hover:shadow focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:ring-offset-1 sm:self-auto">
            Buka daftar tugas
            <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14"></path>
                <path d="m13 6 6 6-6 6"></path>
            </svg>
        </a>
    </section>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        <div class="intern-stat-card card cursor-default rounded-lg p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs text-gray-400">Total Tugas</p>
            <p class="mt-1 text-2xl font-semibold text-white">{{ $totalTasks }}</p>
        </div>
        <div class="intern-stat-card card cursor-default rounded-lg p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs text-gray-400">Perlu Ditindaklanjuti</p>
            <p class="mt-1 text-2xl font-semibold text-blue">{{ $openTasks }}</p>
        </div>
        <div class="intern-stat-card card cursor-default rounded-lg p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs text-gray-400">Menunggu Review</p>
            <p class="mt-1 text-2xl font-semibold text-yellow-400">{{ $awaitingReviewTasks }}</p>
        </div>
        <div class="intern-stat-card card cursor-default rounded-lg p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs text-gray-400">Disetujui</p>
            <p class="mt-1 text-2xl font-semibold text-green-400">{{ $completedTasks }}</p>
        </div>
    </div>

    <section class="card rounded-lg p-4">
        <div class="mb-3 flex items-center justify-between gap-3">
            <h2 class="text-base font-semibold text-white">Pengumuman Supervisor</h2>
            <i class="fas fa-bullhorn text-blue" aria-hidden="true"></i>
        </div>
        <div class="space-y-3">
            @forelse($announcements as $announcement)
            <article class="border-l-2 border-blue pl-3">
                <p class="whitespace-pre-line text-sm leading-5 text-gray-200">{{ $announcement->message }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ $announcement->supervisor->name }} · {{ $announcement->created_at->format('d M Y, H:i') }}</p>
            </article>
            @empty
            <p class="py-2 text-sm text-gray-400">Belum ada pengumuman baru.</p>
            @endforelse
        </div>
    </section>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">
        <section class="card rounded-lg p-4 xl:col-span-3">
            <div class="mb-3 flex items-center justify-between gap-3">
                <h2 class="text-base font-semibold text-white">Deadline Terdekat</h2>
                <span class="text-xs text-gray-400">Tugas belum tuntas</span>
            </div>
            <div class="divide-y divide-gray-800">
                @forelse($upcomingDeadlines as $submission)
                @php
                    $daysRemaining = today()->diffInDays($submission->task->deadline, false);
                @endphp
                <article class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                    <div class="min-w-0">
                        <h3 class="truncate text-sm font-medium text-white">{{ $submission->task->title }}</h3>
                        <p class="mt-0.5 text-xs text-gray-400">{{ $submission->task->category }} · {{ $submission->task->deadline->format('d M Y') }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        @if($daysRemaining < 0)
                            <span class="text-xs font-medium text-red-400">Terlambat {{ abs($daysRemaining) }} hari</span>
                        @elseif($daysRemaining === 0)
                            <span class="text-xs font-medium text-yellow-400">Deadline hari ini</span>
                        @else
                            <span class="text-xs font-medium text-gray-300">{{ $daysRemaining }} hari lagi</span>
                        @endif
                        @if($submission->status === 'Revisi')
                            <p class="mt-0.5 text-[11px] text-red-400">Perlu diperbaiki</p>
                        @elseif($submission->status === 'Menunggu Review')
                            <p class="mt-0.5 text-[11px] text-yellow-400">Menunggu review</p>
                        @endif
                    </div>
                </article>
                @empty
                <p class="py-5 text-center text-sm text-gray-400">Tidak ada deadline aktif</p>
                @endforelse
            </div>
        </section>

        <section class="card rounded-lg p-4 xl:col-span-2">
            <div class="mb-3 flex items-center justify-between gap-3">
                <h2 class="text-base font-semibold text-white">Catatan Supervisor</h2>
                <a href="{{ route('intern.progress') }}" class="group inline-flex items-center rounded-lg border border-blue/20 bg-blue/10 px-3.5 py-1.5 text-xs font-medium text-blue transition-all duration-200 ease-in-out hover:border-blue/40 hover:bg-blue/20 hover:shadow-md hover:shadow-blue/10 active:scale-95">
                    <span class="inline-flex items-center gap-1.5 transition-transform duration-200 ease-in-out group-hover:translate-x-1">
                        Lihat progress
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3.5 10a.75.75 0 0 1 .75-.75h9.69L10.22 5.53a.75.75 0 1 1 1.06-1.06l5 5a.75.75 0 0 1 0 1.06l-5 5a.75.75 0 1 1-1.06-1.06l3.72-3.72H4.25A.75.75 0 0 1 3.5 10Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($latestFeedback as $feedback)
                <article class="border-l-2 border-blue pl-3">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="text-sm font-medium text-white">{{ $feedback->task->title }}</h3>
                        <time class="shrink-0 text-[11px] text-gray-500">{{ $feedback->updated_at?->format('d M Y') }}</time>
                    </div>
                    <p class="mt-1 text-sm leading-5 text-gray-300">{{ $feedback->supervisor_feedback }}</p>
                </article>
                @empty
                <p class="py-5 text-center text-sm text-gray-400">Belum ada catatan dari supervisor</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
