@extends('layouts.app')

@section('title', 'Review Hasil Pekerjaan - NawaTara InternTrack')

@section('header', 'Review Hasil Pekerjaan')

@section('content')
<div class="space-y-4">
    @if(session('success'))
        <div role="status" class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <i class="fa-solid fa-check" aria-hidden="true"></i>
            </span>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm" aria-label="Filter pekerjaan">
        <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
            <div class="flex w-full min-w-0 gap-0.5 overflow-x-auto rounded-lg bg-slate-100/80 p-1 md:w-auto" role="group" aria-label="Filter status review">
                <button type="button" data-review-filter="all" aria-pressed="false" class="review-filter inline-flex shrink-0 items-center gap-2 rounded-md px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-200 hover:text-slate-900">Semua <span data-filter-count="all" class="min-w-5 rounded-full px-1.5 py-0.5 text-center text-[10px] font-medium leading-none transition-colors"></span></button>
                <button type="button" data-review-filter="pending" aria-pressed="false" class="review-filter inline-flex shrink-0 items-center gap-2 rounded-md px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-200 hover:text-slate-900">Menunggu Review <span data-filter-count="pending" class="min-w-5 rounded-full px-1.5 py-0.5 text-center text-[10px] font-medium leading-none transition-colors"></span></button>
                <button type="button" data-review-filter="approved" aria-pressed="false" class="review-filter inline-flex shrink-0 items-center gap-2 rounded-md px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-200 hover:text-slate-900">Disetujui <span data-filter-count="approved" class="min-w-5 rounded-full px-1.5 py-0.5 text-center text-[10px] font-medium leading-none transition-colors"></span></button>
                <button type="button" data-review-filter="revision" aria-pressed="false" class="review-filter inline-flex shrink-0 items-center gap-2 rounded-md px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-200 hover:text-slate-900">Perlu Revisi <span data-filter-count="revision" class="min-w-5 rounded-full px-1.5 py-0.5 text-center text-[10px] font-medium leading-none transition-colors"></span></button>
            </div>
            <label class="relative block h-10 w-full shrink-0 md:max-w-xs">
                <span class="sr-only">Cari nama intern atau judul tugas</span>
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400" aria-hidden="true"></i>
                <input id="review-search" type="search" value="{{ request('search') }}" placeholder="Cari intern atau tugas..." class="h-10 w-full rounded-lg border border-slate-200 bg-white pl-9 pr-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
            </label>
        </div>
    </section>

    <div id="review-list" class="space-y-3">
    @forelse($submissions as $submission)
        @php
            $reviewStatusTone = match ($submission->status) {
                'Disetujui', 'Selesai' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                'Revisi' => 'bg-rose-50 text-rose-700 ring-rose-600/10',
                default => 'bg-amber-50 text-amber-700 ring-amber-600/10',
            };
            $reviewFilterStatus = match ($submission->status) {
                'Disetujui', 'Selesai' => 'approved',
                'Revisi' => 'revision',
                default => 'pending',
            };
        @endphp
        <article id="submission-{{ $submission->id }}" data-review-card data-status="{{ $reviewFilterStatus }}" data-name="{{ $submission->intern?->name ?? 'Intern tidak tersedia' }}" data-task="{{ $submission->task?->title ?? 'Tugas tidak tersedia' }}" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:border-slate-300 hover:shadow-md">
            <header class="flex flex-col gap-2 border-b border-slate-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500">{{ $submission->intern?->name ?? 'Intern tidak tersedia' }}</p>
                    <h2 class="mt-0.5 truncate text-base font-semibold tracking-tight text-slate-900">{{ $submission->task?->title ?? 'Tugas tidak tersedia' }}</h2>
                </div>
                <span data-status-badge class="inline-flex w-fit shrink-0 items-center rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $reviewStatusTone }}">{{ $submission->status }}</span>
            </header>

            <div class="space-y-3 p-4 sm:p-5">
                <div class="grid grid-cols-1 gap-2 rounded-lg border border-slate-100 bg-slate-50/50 p-2.5 sm:grid-cols-2 sm:gap-3">
                    <div class="min-w-0 space-y-2">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Link Pengerjaan</p>
                            @if($submission->link_submission)
                                <a href="{{ $submission->link_submission }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-flex max-w-full items-center gap-2 rounded-lg border border-blue-100 bg-blue-50 px-3.5 py-1.5 text-[13px] font-semibold text-blue-600 shadow-sm transition-all duration-200 hover:border-blue-200 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:ring-offset-1">
                                    <span class="truncate">Buka hasil pekerjaan</span>
                                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M14 4h6v6M20 4l-9 9"></path>
                                        <path d="M18 13v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h5"></path>
                                    </svg>
                                </a>
                            @else
                                <p class="mt-0.5 text-[13px] text-slate-400">Belum ada tautan</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Catatan Intern</p>
                            <p class="mt-0.5 break-words text-[13px] leading-5 text-slate-700">{{ $submission->notes ?: 'Tidak ada catatan.' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 border-t border-slate-200/80 pt-2 sm:border-l sm:border-t-0 sm:pl-3 sm:pt-0">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Deadline</p>
                            <p class="mt-0.5 text-[13px] font-semibold text-slate-800">{{ $submission->task?->deadline?->format('d M Y') ?? 'Tidak ditentukan' }}</p>
                        </div>
                    </div>
                </div>

                @if($submission->supervisor_feedback)
                    <div class="rounded-lg border border-blue-100 bg-blue-50/60 px-3 py-1.5">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-blue-800">{{ $submission->status === 'Disetujui' || $submission->status === 'Selesai' ? 'Riwayat Catatan Revisi' : 'Catatan Revisi Sebelumnya' }}</p>
                        <p class="mt-0.5 break-words text-[13px] leading-5 text-slate-700">{{ $submission->supervisor_feedback }}</p>
                    </div>
                @endif

                @if(!in_array($submission->status, ['Disetujui', 'Selesai'], true))
                <form action="{{ route('supervisor.reviews.submit', $submission->id) }}" method="POST" class="border-t border-slate-100 pt-2">
                    @csrf
                    <input type="hidden" name="action" value="" data-review-action>
                    <input type="hidden" name="return_filter" value="{{ request('filter', 'pending') }}">
                    <input type="hidden" name="return_search" value="{{ request('search') }}">
                    <p data-review-action-error role="alert" class="mb-2 hidden text-xs font-medium text-rose-700">Silakan pilih tindakan Setujui atau Minta Revisi.</p>
                    <div>
                        <label for="supervisor-feedback-{{ $submission->id }}" class="mb-1 block text-xs font-semibold text-slate-800">Catatan Revisi</label>
                        <textarea id="supervisor-feedback-{{ $submission->id }}" name="supervisor_feedback" rows="2" class="w-full resize-y rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[13px] leading-5 text-slate-700 shadow-sm placeholder:text-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30" placeholder="Masukkan catatan atau instruksi revisi...">{{ old('supervisor_feedback', $submission->status === 'Revisi' ? $submission->supervisor_feedback : '') }}</textarea>
                    </div>
                    <div class="mt-2 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                        <button type="submit" name="action" value="revise" class="review-submit inline-flex min-h-8 items-center justify-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 transition hover:border-rose-300 hover:bg-rose-100 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-300 focus:ring-offset-1">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 12a9 9 0 1 0 2.64-6.36L3 8"></path>
                                <path d="M3 3v5h5M12 7v5l3 2"></path>
                            </svg>
                            <span class="button-label">Minta Revisi</span>
                        </button>
                        <button type="submit" name="action" value="approve" class="review-submit inline-flex min-h-8 items-center justify-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700 hover:shadow focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-1">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m5 12 4 4L19 6"></path>
                            </svg>
                            <span class="button-label">Setujui</span>
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </article>
    @empty
        <div class="rounded-2xl border border-slate-200 bg-white px-6 py-14 text-center shadow-sm">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 5h16v14H4z"></path>
                    <path d="M8 9h8M8 13h5"></path>
                    <path d="m15 17 2 2 4-4"></path>
                </svg>
            </span>
            <p class="mt-3 font-semibold text-slate-800">Belum ada hasil pekerjaan untuk direview</p>
            <p class="mt-1 text-sm text-slate-500">Hasil pekerjaan intern akan muncul di sini setelah dikirim.</p>
        </div>
    @endforelse
        <div id="review-no-results" class="hidden rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
            <p class="font-semibold text-slate-700">Tidak ada hasil yang cocok</p>
            <p class="mt-1 text-sm text-slate-500">Coba ubah filter status atau kata kunci pencarian.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const cards = [...document.querySelectorAll('[data-review-card]')];
        const filters = [...document.querySelectorAll('[data-review-filter]')];
        const search = document.getElementById('review-search');
        const emptyState = document.getElementById('review-no-results');
        const params = new URLSearchParams(window.location.search);
        const validFilters = ['all', 'pending', 'approved', 'revision'];
        let activeFilter = validFilters.includes(params.get('filter')) ? params.get('filter') : 'pending';
        let stickyUpdatedId = params.get('updated');

        const updateUrl = () => {
            const nextParams = new URLSearchParams(window.location.search);
            nextParams.set('filter', activeFilter);
            if (search.value.trim()) nextParams.set('search', search.value.trim());
            else nextParams.delete('search');
            if (stickyUpdatedId) nextParams.set('updated', stickyUpdatedId);
            else nextParams.delete('updated');
            window.history.replaceState({}, '', `${window.location.pathname}?${nextParams.toString()}`);
        };

        const render = () => {
            const query = search.value.trim().toLocaleLowerCase();
            let visibleCount = 0;
            cards.forEach((card) => {
                const statusMatch = activeFilter === 'all'
                    || card.dataset.status === activeFilter
                    || (activeFilter === 'pending' && card.id === `submission-${stickyUpdatedId}`);
                const searchMatch = `${card.dataset.name} ${card.dataset.task}`.toLocaleLowerCase().includes(query);
                const visible = statusMatch && searchMatch;
                card.classList.toggle('hidden', !visible);
                if (visible) visibleCount += 1;
            });
            emptyState.classList.toggle('hidden', visibleCount > 0);

            filters.forEach((button) => {
                const selected = button.dataset.reviewFilter === activeFilter;
                button.setAttribute('aria-pressed', selected ? 'true' : 'false');
                button.classList.toggle('bg-white', selected);
                button.classList.toggle('text-slate-900', selected);
                button.classList.toggle('font-semibold', selected);
                button.classList.toggle('shadow-sm', selected);
                button.classList.toggle('text-slate-500', !selected);
                button.classList.toggle('font-medium', !selected);
                const badge = button.querySelector('[data-filter-count]');
                badge.classList.toggle('bg-blue-100', selected);
                badge.classList.toggle('text-blue-600', selected);
                badge.classList.toggle('bg-slate-200/60', !selected);
                badge.classList.toggle('text-slate-600', !selected);
            });
            document.querySelectorAll('input[name="return_filter"]').forEach((input) => {
                input.value = activeFilter;
            });
            document.querySelectorAll('input[name="return_search"]').forEach((input) => {
                input.value = search.value.trim();
            });
            updateUrl();
        };

        const counts = cards.reduce((totals, card) => {
            totals.all += 1;
            totals[card.dataset.status] += 1;
            return totals;
        }, { all: 0, pending: 0, approved: 0, revision: 0 });
        document.querySelectorAll('[data-filter-count]').forEach((count) => {
            count.textContent = counts[count.dataset.filterCount];
        });

        filters.forEach((button) => button.addEventListener('click', () => {
            activeFilter = button.dataset.reviewFilter;
            stickyUpdatedId = null;
            render();
        }));
        search.addEventListener('input', render);
        document.querySelectorAll('form[action*="/reviews/"]').forEach((form) => {
            const actionInput = form.querySelector('[data-review-action]');
            const actionError = form.querySelector('[data-review-action-error]');

            form.querySelectorAll('.review-submit').forEach((button) => {
                button.addEventListener('click', () => {
                    actionInput.value = button.value;
                    actionError.classList.add('hidden');
                });
            });

            form.addEventListener('submit', (event) => {
                const submitter = event.submitter;
                if (submitter && submitter.classList.contains('review-submit')) {
                    actionInput.value = submitter.value;
                }
                if (!['approve', 'revise'].includes(actionInput.value)) {
                    event.preventDefault();
                    actionError.classList.remove('hidden');
                    return;
                }
                form.querySelectorAll('.review-submit').forEach((button) => {
                    button.disabled = true;
                    button.classList.add('cursor-wait', 'opacity-70');
                });
                if (!submitter || !submitter.classList.contains('review-submit')) return;
                submitter.setAttribute('aria-busy', 'true');
                submitter.querySelector('.button-label').textContent = 'Menyimpan...';
                const spinner = document.createElement('i');
                spinner.className = 'fa-solid fa-spinner fa-spin';
                spinner.setAttribute('aria-hidden', 'true');
                submitter.prepend(spinner);
            });
        });

        render();
    })();
</script>
@endpush
