@extends('layouts.app')

@section('title', 'Task Management - NawaTara InternTrack')

@section('header', 'Task Management')

@section('content')
<style>
    .task-deadline-input::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 1;
        filter: invert(68%) sepia(11%) saturate(460%) hue-rotate(177deg) brightness(92%) contrast(88%);
    }
</style>

<div class="space-y-6">
    <!-- Search and Filter -->
    <div class="task-filter-card card cursor-default rounded-xl border border-slate-200 p-4 shadow-sm transition-all duration-300 ease-in-out hover:border-blue-200 hover:shadow-md">
        <div class="flex flex-col items-stretch justify-between gap-3 md:flex-row md:items-center">
            <div class="w-full flex-1">
                <label for="search" class="sr-only">Cari tugas</label>
                <input type="text" id="search" placeholder="Cari tugas..."
                    class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
            </div>
            <div class="flex w-full flex-col gap-2 sm:flex-row md:w-auto">
                <label for="filterCategory" class="sr-only">Filter kategori tugas</label>
                <select id="filterCategory" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 sm:min-w-44 md:w-auto">
                    <option value="">Semua Kategori</option>
                    <option value="Konten">Konten</option>
                    <option value="Coding">Coding</option>
                    <option value="Design">Design</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
                <button type="button" onclick="openModal()" class="inline-flex min-h-10 items-center justify-center whitespace-nowrap rounded-lg bg-blue px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-blue-600 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-plus mr-2" aria-hidden="true"></i>Buat Task Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="card rounded-xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full" id="tasksTable">
                <thead>
                    <tr class="text-left text-gray-400 text-sm border-b border-gray-700">
                        <th class="pb-3">Judul Tugas</th>
                        <th class="pb-3">Kategori</th>
                        <th class="pb-3">Prioritas</th>
                        <th class="pb-3">Deadline</th>
                        <th class="pb-3">Assignee</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($tasks as $task)
                    <tr class="table-row border-b border-gray-800">
                        <td class="py-3 text-white">{{ $task->title }}</td>
                        <td class="py-3 text-gray-300">
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs">{{ $task->category }}</span>
                        </td>
                        <td class="py-3 text-gray-300">
                            @if($task->priority === 'Tinggi')
                                <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded text-xs">Tinggi</span>
                            @elseif($task->priority === 'Sedang')
                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs">Sedang</span>
                            @else
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Rendah</span>
                            @endif
                        </td>
                        <td class="py-3 text-gray-300">{{ $task->deadline->format('d M Y') }}</td>
                        <td class="py-3 text-gray-300">
                            {{ $task->submissions->pluck('intern.name')->implode(', ') }}
                        </td>
                        <td class="py-3">
                            <button type="button" data-delete-task data-delete-url="{{ route('supervisor.tasks.delete', $task->id) }}" data-task-title="{{ $task->title }}" aria-label="Hapus tugas {{ $task->title }}" class="rounded-lg p-2 text-red-600 transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                                <i class="fas fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400">Belum ada tugas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="deleteTaskModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="delete-task-title" aria-describedby="delete-task-description">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl shadow-black/20 sm:p-7">
        <div class="flex flex-col items-center text-center">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600">
                <i class="fas fa-trash-can text-lg" aria-hidden="true"></i>
            </div>
            <h2 id="delete-task-title" class="text-lg font-semibold text-gray-900">Hapus tugas?</h2>
            <p id="delete-task-description" class="mt-2 text-sm leading-6 text-gray-600">
                Apakah Anda yakin ingin menghapus tugas <span class="font-semibold text-gray-900" data-delete-task-name></span>? Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <form id="delete-task-form" method="POST" class="mt-7 grid grid-cols-2 items-center gap-3">
            @csrf
            @method('DELETE')
            <button type="button" data-delete-cancel class="inline-flex min-h-11 w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400">
                Batal
            </button>
            <button type="submit" class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
                <i class="fas fa-trash-can" aria-hidden="true"></i>Ya, Hapus
            </button>
        </form>
    </div>
</div>

<!-- Create Task Modal -->
<div id="taskModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-[#0b1329] rounded-xl p-6 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-white">Buat Task Baru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('supervisor.tasks.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Judul Tugas</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 bg-[#111c3a] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                    <textarea name="description" required rows="3" class="w-full px-4 py-2 bg-[#111c3a] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Kategori</label>
                        <select name="category" required class="w-full px-4 py-2 bg-[#111c3a] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500">
                            <option value="Konten">Konten</option>
                            <option value="Coding">Coding</option>
                            <option value="Design">Design</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Prioritas</label>
                        <select name="priority" required class="w-full px-4 py-2 bg-[#111c3a] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500">
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang" selected>Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Deadline</label>
                    <input id="task-deadline" type="date" name="deadline" value="{{ old('deadline') }}" min="{{ now()->toDateString() }}" required style="color-scheme: dark;" class="task-deadline-input w-full rounded-lg border border-gray-700 bg-[#111c3a] px-4 py-2 text-white focus:border-blue-500 focus:outline-none">
                    @error('deadline')
                        <p class="mt-1 text-sm text-red-400" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Assign ke Intern</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($interns as $intern)
                        <label class="flex items-center text-gray-300">
                            <input type="checkbox" name="interns[]" value="{{ $intern->id }}" class="mr-2">
                            {{ $intern->name }} ({{ $intern->division }})
                        </label>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue hover-blue text-white py-2 rounded-lg transition">
                    Simpan Tugas
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const deadlineInput = document.querySelector('input[name="deadline"]');
    const pastDeadlineMessage = 'Deadline tugas tidak boleh menggunakan tanggal yang sudah berlalu.';

    deadlineInput.addEventListener('input', () => {
        deadlineInput.setCustomValidity(
            deadlineInput.value && deadlineInput.value < deadlineInput.min ? pastDeadlineMessage : ''
        );
    });
    deadlineInput.addEventListener('invalid', () => {
        if (deadlineInput.validity.rangeUnderflow) {
            deadlineInput.setCustomValidity(pastDeadlineMessage);
        }
    });

    @if($errors->has('deadline'))
        openModal();
    @endif

    function openModal() {
        document.getElementById('taskModal').classList.remove('hidden');
        document.getElementById('taskModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('taskModal').classList.add('hidden');
        document.getElementById('taskModal').classList.remove('flex');
    }

    const deleteTaskModal = document.getElementById('deleteTaskModal');
    const deleteTaskForm = document.getElementById('delete-task-form');
    const deleteTaskName = deleteTaskModal.querySelector('[data-delete-task-name]');
    const deleteTaskCancel = deleteTaskModal.querySelector('[data-delete-cancel]');
    let deleteTaskTrigger = null;

    function closeDeleteTaskModal() {
        deleteTaskModal.classList.add('hidden');
        deleteTaskModal.classList.remove('flex');
        deleteTaskForm.removeAttribute('action');
        if (deleteTaskTrigger) deleteTaskTrigger.focus();
    }

    document.querySelectorAll('[data-delete-task]').forEach((button) => {
        button.addEventListener('click', () => {
            deleteTaskTrigger = button;
            deleteTaskForm.action = button.dataset.deleteUrl;
            deleteTaskName.textContent = button.dataset.taskTitle;
            deleteTaskModal.classList.remove('hidden');
            deleteTaskModal.classList.add('flex');
            deleteTaskCancel.focus();
        });
    });

    deleteTaskCancel.addEventListener('click', closeDeleteTaskModal);
    deleteTaskModal.addEventListener('click', (event) => {
        if (event.target === deleteTaskModal) closeDeleteTaskModal();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !deleteTaskModal.classList.contains('hidden')) {
            closeDeleteTaskModal();
        }
    });

    // Search and Filter
    document.getElementById('search').addEventListener('input', filterTasks);
    document.getElementById('filterCategory').addEventListener('change', filterTasks);

    function filterTasks() {
        const search = document.getElementById('search').value.toLowerCase();
        const category = document.getElementById('filterCategory').value;
        const rows = document.querySelectorAll('#tasksTable tbody tr');

        rows.forEach(row => {
            const title = row.cells[0].textContent.toLowerCase();
            const taskCategory = row.cells[1].textContent;
            const matchesSearch = title.includes(search);
            const matchesCategory = !category || taskCategory.includes(category);
            row.style.display = matchesSearch && matchesCategory ? '' : 'none';
        });
    }
</script>
@endpush
@endsection
