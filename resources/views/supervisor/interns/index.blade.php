@extends('layouts.app')

@section('title', 'Data Intern - NawaTara InternTrack')

@section('header', 'Data Intern')

@section('content')
@php
    $divisionCounts = $interns->groupBy(fn ($intern) => $intern->division ?: 'Belum ditentukan');
    $divisionPalette = [
        ['icon' => 'bg-sky-50 text-sky-700 ring-sky-100', 'badge' => 'bg-sky-50 text-sky-700 ring-sky-600/10'],
        ['icon' => 'bg-violet-50 text-violet-700 ring-violet-100', 'badge' => 'bg-violet-50 text-violet-700 ring-violet-600/10'],
        ['icon' => 'bg-emerald-50 text-emerald-700 ring-emerald-100', 'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10'],
        ['icon' => 'bg-amber-50 text-amber-700 ring-amber-100', 'badge' => 'bg-amber-50 text-amber-700 ring-amber-600/10'],
        ['icon' => 'bg-rose-50 text-rose-700 ring-rose-100', 'badge' => 'bg-rose-50 text-rose-700 ring-rose-600/10'],
    ];
@endphp

<div class="space-y-6">
    <section aria-labelledby="intern-summary-title">
        <div class="mb-4">
            <h2 id="intern-summary-title" class="text-lg font-semibold tracking-tight text-slate-900">Ringkasan tim</h2>
            <p class="mt-1 text-sm text-slate-500">Pantau intern dan pembagian divisi dalam satu tampilan.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="cursor-default rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md sm:p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Intern</p>
                        <p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">{{ $interns->count() }}</p>
                        <p class="mt-1 text-xs text-slate-500">Intern terdaftar</p>
                    </div>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-100 sm:h-12 sm:w-12 sm:rounded-2xl">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="10" cy="7" r="4"></circle>
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </span>
                </div>
            </article>

            @foreach($divisionCounts as $division => $divisionInterns)
                @php
                    $tone = $divisionPalette[$loop->index % count($divisionPalette)];
                @endphp
                <article class="cursor-default rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md sm:p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-slate-500" title="{{ $division }}">{{ $division }}</p>
                            <p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">{{ $divisionInterns->count() }}</p>
                            <p class="mt-1 text-xs text-slate-500">Intern terdaftar</p>
                        </div>
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl ring-1 ring-inset {{ $tone['icon'] }} sm:h-12 sm:w-12 sm:rounded-2xl">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                <path d="M8 8h8M8 12h8M8 16h4"></path>
                            </svg>
                        </span>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="intern-list-title">
        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 id="intern-list-title" class="text-base font-semibold text-slate-900">Direktori intern</h2>
                    <p class="mt-1 text-sm text-slate-500">Cari dan filter informasi intern dengan cepat.</p>
                </div>
                <div class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">
                    <label class="relative block sm:min-w-72">
                        <span class="sr-only">Cari nama atau email intern</span>
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-4-4"></path>
                        </svg>
                        <input id="intern-search" type="search" autocomplete="off" placeholder="Cari nama atau email..." class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100">
                    </label>
                    <label class="sr-only" for="division-filter">Filter berdasarkan divisi</label>
                    <select id="division-filter" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100 sm:min-w-48">
                        <option value="">Semua Divisi</option>
                        @foreach($divisionCounts as $division => $count)
                            <option value="{{ $division }}">{{ $division }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead class="bg-slate-50/80">
                    <tr class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th scope="col" class="px-6 py-4">Nama</th>
                        <th scope="col" class="px-6 py-4">Email</th>
                        <th scope="col" class="px-6 py-4">Divisi</th>
                        <th scope="col" class="px-6 py-4">Nomor Kontak</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="intern-table-body" class="divide-y divide-slate-100 text-sm">
                    @forelse($interns as $intern)
                        @php
                            $division = $intern->division ?: 'Belum ditentukan';
                            $divisionIndex = $divisionCounts->keys()->search($division);
                            $tone = $divisionPalette[$divisionIndex % count($divisionPalette)];
                        @endphp
                        <tr class="intern-row transition-colors hover:bg-slate-50/80" data-name="{{ $intern->name }}" data-email="{{ $intern->email }}" data-division="{{ $division }}">
                            <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-900">{{ $intern->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $intern->email }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $tone['badge'] }}">{{ $division }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $intern->phone ?: '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('supervisor.interns.detail', $intern->id) }}" class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-3.5 py-2 text-xs font-semibold text-indigo-700 transition duration-200 hover:border-slate-200 hover:bg-slate-100 hover:text-indigo-800 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:ring-offset-1">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    Lihat Profil
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="intern-empty-row">
                            <td colspan="5" class="px-6 py-12 text-center">
                                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="10" cy="7" r="4"></circle>
                                        <path d="M20 8v6M23 11h-6"></path>
                                    </svg>
                                </span>
                                <p class="mt-3 font-medium text-slate-700">Belum ada intern</p>
                                <p class="mt-1 text-sm text-slate-500">Data intern akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="intern-no-results" hidden>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <p class="font-medium text-slate-700">Tidak ada intern yang cocok</p>
                            <p class="mt-1 text-sm text-slate-500">Coba ubah kata kunci atau filter divisi.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between border-t border-slate-100 px-6 py-3">
            <p id="intern-result-count" class="text-xs text-slate-500" aria-live="polite">
                Menampilkan {{ $interns->count() }} dari {{ $interns->count() }} intern
            </p>
        </div>
    </section>
</div>

<script>
    (() => {
        const searchInput = document.getElementById('intern-search');
        const divisionFilter = document.getElementById('division-filter');
        const rows = Array.from(document.querySelectorAll('.intern-row'));
        const noResults = document.getElementById('intern-no-results');
        const resultCount = document.getElementById('intern-result-count');
        const total = rows.length;

        const filterInterns = () => {
            const query = searchInput.value.trim().toLowerCase();
            const division = divisionFilter.value;
            let visible = 0;

            rows.forEach((row) => {
                const matchesQuery = `${row.dataset.name} ${row.dataset.email}`.toLowerCase().includes(query);
                const matchesDivision = !division || row.dataset.division === division;
                const isVisible = matchesQuery && matchesDivision;

                row.hidden = !isVisible;
                visible += isVisible ? 1 : 0;
            });

            noResults.hidden = visible > 0 || total === 0;
            resultCount.textContent = `Menampilkan ${visible} dari ${total} intern`;
        };

        searchInput.addEventListener('input', filterInterns);
        divisionFilter.addEventListener('change', filterInterns);
    })();
</script>
@endsection
