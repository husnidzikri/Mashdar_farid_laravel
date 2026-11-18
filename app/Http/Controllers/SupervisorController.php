<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Attendance;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupervisorController extends Controller
{
    public function dashboard()
    {
        $totalInterns = User::where('role', 'intern')->count();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $todayAttendance = Attendance::where('date', $today)->whereIn('status', ['Hadir', 'Terlambat'])->count();
        $pendingTasks = TaskSubmission::where('status', 'Menunggu Review')->count();
        $revisionTasks = TaskSubmission::where('status', 'Revisi')->count();
        $completedTasks = TaskSubmission::where('status', 'Disetujui')->count();

        $weekEnd = Carbon::now('Asia/Jakarta')->endOfDay();
        $weekStart = $weekEnd->copy()->subDays(6)->startOfDay();
        $weeklySubmissions = TaskSubmission::with(['task', 'intern'])
            ->where('status', 'Disetujui')
            ->whereBetween('updated_at', [
                $weekStart->copy()->utc(),
                $weekEnd->copy()->utc(),
            ])
            ->get()
            ->groupBy(fn ($submission) => $submission->updated_at->timezone('Asia/Jakarta')->toDateString());
        $weeklyActivityLabels = [];
        $weeklyActivityCounts = [];
        $weeklyActivityDetails = [];
        for ($day = $weekStart->copy(); $day->lte($weekEnd); $day->addDay()) {
            $date = $day->toDateString();
            $dailySubmissions = $weeklySubmissions->get($date, collect());
            $weeklyActivityLabels[] = $day->translatedFormat('D');
            $weeklyActivityCounts[] = $dailySubmissions->count();
            $weeklyActivityDetails[] = [
                'date' => $day->translatedFormat('l, d F Y'),
                'count' => $dailySubmissions->count(),
                'tasks' => $dailySubmissions->map(fn ($submission) => [
                    'title' => $submission->task?->title ?? 'Tugas tanpa judul',
                    'intern' => $submission->intern?->name ?? 'Intern tidak tersedia',
                ])->values(),
            ];
        }

        $recentTasks = Task::with('supervisor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $interns = User::where('role', 'intern')->get();
        foreach($interns as $intern) {
            $intern->submissions_count = TaskSubmission::where('intern_id', $intern->id)->count();
        }

        return view('supervisor.dashboard', compact(
            'totalInterns',
            'todayAttendance',
            'pendingTasks',
            'revisionTasks',
            'completedTasks',
            'weeklyActivityLabels',
            'weeklyActivityCounts',
            'weeklyActivityDetails',
            'recentTasks',
            'interns'
        ));
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        Announcement::create([
            'supervisor_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        return redirect()->route('supervisor.dashboard')->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    public function tasks()
    {
        $tasks = Task::with('supervisor')
            ->orderBy('created_at', 'desc')
            ->get();
        $interns = User::where('role', 'intern')->get();

        return view('supervisor.tasks.index', compact('tasks', 'interns'));
    }

    public function interns()
    {
        $interns = User::where('role', 'intern')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'division', 'phone']);

        return view('supervisor.interns.index', compact('interns'));
    }

    public function internDetail($id)
    {
        $intern = User::where('role', 'intern')->findOrFail($id);
        
        $submissions = TaskSubmission::where('intern_id', $intern->id)
            ->with('task')
            ->get();
        
        $attendances = Attendance::where('user_id', $intern->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('supervisor.interns.detail', compact('intern', 'submissions', 'attendances'));
    }

    public function attendance(Request $request)
    {
        $filters = $request->validate([
            'intern_id' => 'nullable|integer|exists:users,id,role,intern',
            'date' => 'nullable|date_format:Y-m-d',
            'status' => 'nullable|in:Hadir,Terlambat,Izin,Sakit',
        ]);

        $interns = User::where('role', 'intern')->orderBy('name')->get();
        $attendances = Attendance::with('user')
            ->when($filters['intern_id'] ?? null, function ($query, $internId) {
                $query->where('user_id', $internId);
            })
            ->when($filters['date'] ?? null, function ($query, $date) {
                $query->whereDate('date', $date);
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20)
            ->appends($request->query());

        return view('supervisor.attendance.index', compact('interns', 'attendances'));
    }

    public function attendancePhoto($id, $type)
    {
        abort_unless(in_array($type, ['check-in', 'check-out'], true), 404);

        $attendance = Attendance::findOrFail($id);
        $photoPath = $type === 'check-in'
            ? $attendance->attendance_photo
            : $attendance->check_out_photo;

        abort_unless($photoPath && Storage::disk('local')->exists($photoPath), 404);

        return response()->file(Storage::disk('local')->path($photoPath), [
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Konten,Coding,Design,Lainnya',
            'priority' => 'required|in:Rendah,Sedang,Tinggi',
            'deadline' => 'required|date|after_or_equal:today',
            'interns' => 'required|array|min:1',
            'interns.*' => 'exists:users,id,role,intern',
        ], [
            'deadline.after_or_equal' => 'Deadline tugas tidak boleh menggunakan tanggal yang sudah berlalu.',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'deadline' => $validated['deadline'],
            'supervisor_id' => auth()->id(),
        ]);

        foreach ($validated['interns'] as $internId) {
            TaskSubmission::create([
                'task_id' => $task->id,
                'intern_id' => $internId,
                'status' => 'Belum Dikerjakan',
            ]);
        }

        return redirect()->route('supervisor.tasks')->with('success', 'Tugas berhasil dibuat');
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('supervisor.tasks')->with('success', 'Tugas berhasil dihapus');
    }

    public function reviews()
    {
        $submissions = TaskSubmission::with(['task', 'intern'])
            ->whereIn('status', ['Menunggu Review', 'Revisi', 'Disetujui', 'Selesai'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('supervisor.reviews.index', compact('submissions'));
    }

    public function reviewSubmission(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'action' => 'required|in:approve,revise',
                'supervisor_feedback' => 'required_if:action,revise|nullable|string',
            ],
            [
                'action.required' => 'Pilih tindakan yang ingin dilakukan.',
                'action.in' => 'Tindakan review tidak valid.',
                'supervisor_feedback.required_if' => 'Catatan revisi wajib diisi jika meminta revisi.',
                'supervisor_feedback.string' => 'Catatan revisi harus berupa teks.',
            ],
            [
                'supervisor_feedback' => 'catatan revisi',
            ]
        );
        $status = $validated['action'] === 'approve'
            ? 'Disetujui'
            : 'Revisi';

        $submission = TaskSubmission::findOrFail($id);
        $updates = ['status' => $status];
        if ($status === 'Revisi') {
            $updates['supervisor_feedback'] = $validated['supervisor_feedback'];
        }
        $submission->update($updates);

        $filter = $request->input('return_filter', 'pending');
        if (! in_array($filter, ['all', 'pending', 'approved', 'revision'], true)) {
            $filter = 'pending';
        }
        $search = trim((string) $request->input('return_search', ''));

        return redirect()->route('supervisor.reviews', array_filter([
            'filter' => $filter,
            'search' => $search,
            'updated' => $submission->id,
        ], fn ($value) => $value !== ''))->with(
            'success',
            $status === 'Disetujui'
                ? 'Pekerjaan berhasil disetujui.'
                : 'Catatan revisi berhasil dikirim.'
        );
    }

   public function performance()
    {
        $interns = User::where('role', 'intern')
            ->with(['submissions' => function ($query) {
                $query->select('id', 'intern_id', 'status');
            }])
            ->orderBy('name')
            ->get(['id', 'name', 'division']);

        return view('supervisor.performance.index', compact('interns'));
    }

    public function performanceReport($id)
    {
        $intern = User::where('role', 'intern')->findOrFail($id);
        
        $submissions = TaskSubmission::where('intern_id', $intern->id)
            ->with('task')
            ->get();
        
        $attendances = Attendance::where('user_id', $intern->id)->get();

        return view('supervisor.performance-report', compact('intern', 'submissions', 'attendances'));
    }
}