@extends('layouts.app')

@section('title', 'Task Saya - NawaTara InternTrack')

@section('header', 'Task Saya')

@section('content')
<div class="space-y-6">
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-200 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Daftar Tugas</h2>
                <p class="mt-1 text-sm text-slate-500">Cari dan pantau tugas yang diberikan kepada Anda.</p>
            </div>
            <p class="text-sm text-slate-500"><span id="task-visible-count" class="font-semibold text-slate-800">{{ $submissions->count() }}</span> tugas</p>
        </div>

        <div class="grid grid-cols-1 gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-4 sm:px-6 md:grid-cols-3">
            <label class="relative md:col-span-1">
                <span class="sr-only">Cari tugas</span>
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path stroke-linecap="round" d="m16 16 4 4"></path>
                </svg>
                <input id="task-search" type="search" placeholder="Cari judul tugas..." class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10">
            </label>
            <label>
                <span class="sr-only">Filter kategori</span>
                <select id="task-category-filter" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10">
                    <option value="">Semua kategori</option>
                    @foreach($taskCategories as $category)
                        <option value="{{ \Illuminate\Support\Str::lower($category) }}">{{ $category }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                <span class="sr-only">Filter status</span>
                <select id="task-status-filter" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10">
                    <option value="">Semua status</option>
                    <option value="dalam proses">Dalam Proses</option>
                    <option value="menunggu review">Menunggu Review</option>
                    <option value="perlu revisi">Perlu Revisi</option>
                    <option value="disetujui">Disetujui</option>
                </select>
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px]">
                <thead class="bg-white">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4">Judul Tugas</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Deadline</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="task-table-body" class="divide-y divide-slate-100 text-sm">
                    @forelse($submissions as $submission)
                    @php
                        $taskTitle = $submission->task->title ?? 'Tugas';
                        $taskCategory = $submission->task->category ?? '-';
                        $taskDeadline = $submission->task?->deadline?->format('d M Y') ?? '-';
                        $statusFilterValue = match ($submission->status) {
                            'Belum Dikerjakan', 'Dalam Proses' => 'dalam proses',
                            'Menunggu Review' => 'menunggu review',
                            'Revisi' => 'perlu revisi',
                            'Disetujui', 'Selesai' => 'disetujui',
                            default => \Illuminate\Support\Str::lower($submission->status),
                        };
                    @endphp
                    <tr
                        class="task-table-row transition-colors hover:bg-slate-50/80"
                        data-title="{{ \Illuminate\Support\Str::lower($taskTitle) }}"
                        data-category="{{ \Illuminate\Support\Str::lower($taskCategory) }}"
                        data-status="{{ $statusFilterValue }}"
                    >
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900">{{ $taskTitle }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $submission->task->priority ?? 'Tugas Intern' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">{{ $taskCategory }}</span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $taskDeadline }}</td>
                        <td class="px-6 py-4">
                            @if($submission->status === 'Disetujui' || $submission->status === 'Selesai')
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.2 7.26a1 1 0 0 1-1.42.003L3.3 9.18a1 1 0 1 1 1.4-1.43l4.08 3.99 6.51-6.56a1 1 0 0 1 1.414-.006Z" clip-rule="evenodd"/></svg>
                                    Disetujui
                                </span>
                            @elseif($submission->status === 'Revisi')
                                <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-medium text-rose-700">Perlu Revisi</span>
                            @elseif($submission->status === 'Menunggu Review')
                                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">Menunggu Review</span>
                            @elseif(in_array($submission->status, ['Belum Dikerjakan', 'Dalam Proses'], true))
                                <span class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-medium text-sky-700">Dalam Proses</span>
                            @else
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $submission->status }}</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if(in_array($submission->status, ['Belum Dikerjakan', 'Dalam Proses', 'Revisi'], true))
                                <button
                                    type="button"
                                    data-update-submission
                                    data-id="{{ $submission->id }}"
                                    data-link="{{ $submission->link_submission }}"
                                    data-notes="{{ $submission->notes }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-800 transition hover:border-blue-300 hover:bg-blue-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                >
                                    <i class="fas fa-arrow-up-from-bracket" aria-hidden="true"></i>
                                    {{ $submission->status === 'Belum Dikerjakan' ? 'Kumpulkan' : 'Update' }}
                                </button>
                            @elseif($submission->status === 'Menunggu Review')
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700"><i class="fas fa-clock" aria-hidden="true"></i> Terkirim</span>
                            @elseif(in_array($submission->status, ['Disetujui', 'Selesai'], true))
                                <button
                                    type="button"
                                    data-task-detail
                                    data-title="{{ $taskTitle }}"
                                    data-category="{{ $taskCategory }}"
                                    data-deadline="{{ $taskDeadline }}"
                                    data-description="{{ $submission->task->description ?? '' }}"
                                    data-status="{{ $submission->status }}"
                                    data-link="{{ $submission->link_submission }}"
                                    data-notes="{{ $submission->notes }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                >
                                    <i class="fas fa-circle-check text-emerald-600" aria-hidden="true"></i>
                                    Lihat detail
                                </button>
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="task-empty-state">
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400"><i class="fas fa-clipboard-list" aria-hidden="true"></i></div>
                            <p class="mt-3 font-medium text-slate-700">Belum ada tugas</p>
                            <p class="mt-1 text-sm text-slate-500">Tugas yang diberikan akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                    <tr id="task-no-results" class="hidden">
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">Tidak ada tugas yang cocok dengan pencarian atau filter.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<div id="taskDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="task-detail-title">
    <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-4">
            <div>
                <span id="task-detail-status" class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Disetujui</span>
                <h3 id="task-detail-title" class="mt-3 text-xl font-semibold text-slate-900"></h3>
                <p id="task-detail-meta" class="mt-1 text-sm text-slate-500"></p>
            </div>
            <button type="button" data-close-task-detail class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup detail tugas">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="mt-5 space-y-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Deskripsi</p>
                <p id="task-detail-description" class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700"></p>
            </div>
            <div id="task-detail-notes-wrap" class="hidden">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Catatan pengerjaan</p>
                <p id="task-detail-notes" class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700"></p>
            </div>
            <a id="task-detail-link" href="#" target="_blank" rel="noopener noreferrer" class="hidden inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-blue-50 px-3.5 py-1.5 text-sm font-semibold text-blue-600 shadow-sm transition-all duration-200 hover:border-blue-200 hover:bg-blue-100 hover:text-blue-700 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:ring-offset-1">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 4h6v6M20 4l-9 9"></path>
                    <path d="M18 13v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h5"></path>
                </svg>
                Buka hasil pengerjaan
            </a>
        </div>
        <div class="mt-6 flex justify-end border-t border-slate-100 pt-4">
            <button type="button" data-close-task-detail class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-700">Tutup</button>
        </div>
    </div>
</div>

<!-- Update Submission Modal -->
<div id="updateModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-[#0b1329] rounded-xl p-6 w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-white">Update Tugas</h3>
            <button onclick="closeUpdateModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="updateForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Link Pengerjaan (Google Docs, Canva, GitHub)</label>
                    <input type="url" name="link_submission" id="link_submission" class="w-full px-4 py-2 bg-[#111c3a] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500" placeholder="https://...">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Catatan</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 bg-[#111c3a] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500" placeholder="Catatan pengerjaan..."></textarea>
                </div>
                <button type="submit" class="w-full bg-blue hover-blue text-white py-2 rounded-lg transition">
                    Kirim Pengerjaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const updateModal = document.getElementById('updateModal');
    const updateForm = document.getElementById('updateForm');
    const taskDetailModal = document.getElementById('taskDetailModal');
    const taskDetailStatus = document.getElementById('task-detail-status');
    const taskDetailLink = document.getElementById('task-detail-link');

    function openUpdateModal(id, link, notes) {
        document.getElementById('link_submission').value = link;
        document.getElementById('notes').value = notes;
        updateModal.classList.remove('hidden');
        updateModal.classList.add('flex');
        updateForm.action = `/intern/submission/${id}`;
    }

    function closeUpdateModal() {
        updateModal.classList.add('hidden');
        updateModal.classList.remove('flex');
    }

    function openTaskDetail(button) {
        const detail = button.dataset;
        document.getElementById('task-detail-title').textContent = detail.title;
        document.getElementById('task-detail-meta').textContent = `${detail.category} · Deadline ${detail.deadline}`;
        document.getElementById('task-detail-description').textContent = detail.description || 'Tidak ada deskripsi tugas.';
        taskDetailStatus.textContent = detail.status;

        const notesWrap = document.getElementById('task-detail-notes-wrap');
        const notes = document.getElementById('task-detail-notes');
        notes.textContent = detail.notes || '';
        notesWrap.classList.toggle('hidden', !detail.notes);

        taskDetailLink.classList.add('hidden');
        taskDetailLink.removeAttribute('href');
        if (detail.link) {
            try {
                const url = new URL(detail.link, window.location.origin);
                if (url.protocol === 'https:' || url.protocol === 'http:') {
                    taskDetailLink.href = url.href;
                    taskDetailLink.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Unable to open the saved task submission link.', error);
            }
        }

        taskDetailModal.classList.remove('hidden');
        taskDetailModal.classList.add('flex');
        taskDetailModal.querySelector('[data-close-task-detail]').focus();
    }

    function closeTaskDetail() {
        taskDetailModal.classList.add('hidden');
        taskDetailModal.classList.remove('flex');
    }

    document.querySelectorAll('[data-update-submission]').forEach((button) => {
        button.addEventListener('click', () => {
            openUpdateModal(button.dataset.id, button.dataset.link || '', button.dataset.notes || '');
        });
    });

    document.querySelectorAll('[data-task-detail]').forEach((button) => {
        button.addEventListener('click', () => openTaskDetail(button));
    });

    document.querySelectorAll('[data-close-task-detail]').forEach((button) => {
        button.addEventListener('click', closeTaskDetail);
    });

    taskDetailModal.addEventListener('click', (event) => {
        if (event.target === taskDetailModal) closeTaskDetail();
    });

    const taskSearch = document.getElementById('task-search');
    const taskCategoryFilter = document.getElementById('task-category-filter');
    const taskStatusFilter = document.getElementById('task-status-filter');
    const taskRows = Array.from(document.querySelectorAll('.task-table-row'));
    const taskVisibleCount = document.getElementById('task-visible-count');
    const taskNoResults = document.getElementById('task-no-results');

    function filterTasks() {
        const query = taskSearch.value.trim().toLocaleLowerCase('id');
        const category = taskCategoryFilter.value;
        const status = taskStatusFilter.value;
        let visibleCount = 0;

        taskRows.forEach((row) => {
            const matches = row.dataset.title.includes(query)
                && (!category || row.dataset.category === category)
                && (!status || row.dataset.status === status);
            row.classList.toggle('hidden', !matches);
            if (matches) visibleCount += 1;
        });

        taskVisibleCount.textContent = visibleCount;
        taskNoResults.classList.toggle('hidden', visibleCount > 0 || taskRows.length === 0);
    }

    taskSearch.addEventListener('input', filterTasks);
    taskCategoryFilter.addEventListener('change', filterTasks);
    taskStatusFilter.addEventListener('change', filterTasks);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeUpdateModal();
            closeTaskDetail();
        }
    });
</script>
@endpush
