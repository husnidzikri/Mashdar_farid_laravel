<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Attendance;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class InternController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:intern');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $totalTasks = $user->submissions()->count();
        $completedTasks = $user->submissions()->whereIn('status', ['Disetujui', 'Selesai'])->count();
        $openTasks = $user->submissions()
            ->whereIn('status', ['Belum Dikerjakan', 'Dalam Proses', 'Revisi'])
            ->count();
        $awaitingReviewTasks = $user->submissions()->where('status', 'Menunggu Review')->count();

        $upcomingDeadlines = $user->submissions()
            ->with('task')
            ->whereNotIn('status', ['Disetujui', 'Selesai'])
            ->whereHas('task')
            ->get()
            ->sortBy(fn ($submission) => $submission->task->deadline)
            ->take(5)
            ->values();

        $latestFeedback = $user->submissions()
            ->with('task')
            ->whereNotNull('supervisor_feedback')
            ->where('supervisor_feedback', '!=', '')
            ->orderByDesc('updated_at')
            ->take(4)
            ->get();

        $announcements = Announcement::with('supervisor')
            ->latest()
            ->take(5)
            ->get();

        return view('intern.dashboard', compact(
            'totalTasks',
            'completedTasks',
            'openTasks',
            'awaitingReviewTasks',
            'upcomingDeadlines',
            'latestFeedback',
            'announcements'
        ));
    }

    public function tasks()
    {
        $user = auth()->user();
        $submissions = $user->submissions()
            ->with('task')
            ->orderByDesc('updated_at')
            ->get();
        $taskCategories = $submissions
            ->map(fn ($submission) => $submission->task?->category)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('intern.tasks', compact('submissions', 'taskCategories'));
    }

    public function progress()
    {
        $user = auth()->user();
        $submissions = $user->submissions()
            ->with('task')
            ->orderByDesc('updated_at')
            ->get();

        $totalTasks = $submissions->count();
        $completedTasks = $submissions->whereIn('status', ['Disetujui', 'Selesai'])->count();
        $awaitingReviewTasks = $submissions->where('status', 'Menunggu Review')->count();
        $revisionTasks = $submissions->where('status', 'Revisi')->count();
        $completionPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
        $evaluations = $submissions->whereIn('status', ['Disetujui', 'Revisi'])->values();

        return view('intern.progress', compact(
            'submissions',
            'totalTasks',
            'completedTasks',
            'awaitingReviewTasks',
            'revisionTasks',
            'completionPercentage',
            'evaluations'
        ));
    }

    public function attendance()
    {
        $user = auth()->user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $attendances = $user->attendances()
            ->orderBy('date', 'desc')
            ->orderByDesc('id')
            ->paginate(20);
        $attendanceCounts = $user->attendances()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $totalAttendance = $attendanceCounts->sum();
        $presentAttendance = $attendanceCounts->get('Hadir', 0) + $attendanceCounts->get('Terlambat', 0);
        $attendancePercentage = $totalAttendance > 0 ? ($presentAttendance / $totalAttendance) * 100 : 0;
        $todayAttendance = $user->attendances()->where('date', $today)->first();

        return view('intern.attendance', compact(
            'attendances',
            'attendanceCounts',
            'attendancePercentage',
            'todayAttendance'
        ));
    }

    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'keterangan' => 'required|in:WFO,WFH',
            'attendance_photo' => 'required|image|mimes:jpg,jpeg|max:5120',
        ], [
            'keterangan.required' => 'Silakan pilih lokasi kerja sebelum absen masuk.',
            'keterangan.in' => 'Pilihan lokasi kerja tidak valid.',
            'attendance_photo.required' => 'Ambil foto langsung menggunakan kamera sebelum absen masuk.',
            'attendance_photo.image' => 'Foto kamera tidak valid. Silakan ambil ulang.',
            'attendance_photo.mimes' => 'Foto kehadiran harus berformat JPEG.',
            'attendance_photo.max' => 'Ukuran foto maksimal 5 MB.',
        ]);

        $now = Carbon::now('Asia/Jakarta');
        $photoPath = $this->storeAttendancePhoto($request->file('attendance_photo'));
        try {
            $attendance = DB::transaction(fn () => Attendance::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'date' => $now->toDateString(),
                ],
                [
                    'status' => $now->format('H:i:s') > '08:00:00' ? 'Terlambat' : 'Hadir',
                    'keterangan' => $validated['keterangan'],
                    'check_in_at' => $now->format('H:i:s'),
                    'attendance_photo' => $photoPath,
                ]
            ));
        } catch (Throwable $exception) {
            $this->deleteAttendancePhoto($photoPath);
            throw $exception;
        }

        $checkedIn = $attendance->wasRecentlyCreated;
        if (!$checkedIn) {
            $this->deleteAttendancePhoto($photoPath);
        }

        return back()->with(
            $checkedIn ? 'attendance_check_in_success' : 'error',
            $checkedIn ? 'Absen masuk berhasil dicatat.' : 'Presensi hari ini sudah dimulai.'
        );
    }

    public function checkOut(Request $request)
    {
        $validated = $request->validate([
            'check_out_photo' => 'required|image|mimes:jpg,jpeg|max:5120',
        ], [
            'check_out_photo.required' => 'Ambil foto langsung menggunakan kamera sebelum absen pulang.',
            'check_out_photo.image' => 'Foto kamera tidak valid. Silakan ambil ulang.',
            'check_out_photo.mimes' => 'Foto kehadiran harus berformat JPEG.',
            'check_out_photo.max' => 'Ukuran foto maksimal 5 MB.',
        ]);

        $now = Carbon::now('Asia/Jakarta');
        $photoPath = $this->storeAttendancePhoto($request->file('check_out_photo'));
        try {
            $checkedOut = DB::transaction(function () use ($now, $validated, $photoPath) {
                $attendance = Attendance::where('user_id', auth()->id())
                    ->where('date', $now->toDateString())
                    ->lockForUpdate()
                    ->first();

                if (!$attendance || !$attendance->check_in_at || $attendance->check_out_at) {
                    return false;
                }

                $attendance->update([
                    'check_out_at' => $now->format('H:i:s'),
                    'check_out_photo' => $photoPath,
                ]);

                return true;
            });
        } catch (Throwable $exception) {
            $this->deleteAttendancePhoto($photoPath);
            throw $exception;
        }
        if (!$checkedOut) {
            $this->deleteAttendancePhoto($photoPath);
        }

        return back()->with(
            $checkedOut ? 'success' : 'error',
            $checkedOut ? 'Absen pulang berhasil dicatat.' : 'Absen masuk belum dilakukan atau presensi hari ini sudah selesai.'
        );
    }

    public function attendancePhoto($id, $type)
    {
        abort_unless(in_array($type, ['check-in', 'check-out'], true), 404);

        $attendance = auth()->user()->attendances()->findOrFail($id);
        $photoPath = $type === 'check-in'
            ? $attendance->attendance_photo
            : $attendance->check_out_photo;

        abort_unless($photoPath && Storage::disk('local')->exists($photoPath), 404);

        return response()->file(Storage::disk('local')->path($photoPath), [
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    private function deleteAttendancePhoto(?string $photoPath): void
    {
        if ($photoPath && Storage::disk('local')->exists($photoPath) && !Storage::disk('local')->delete($photoPath)) {
            throw new RuntimeException('Foto bukti presensi gagal dibersihkan.');
        }
    }

    private function storeAttendancePhoto(UploadedFile $photo): string
    {
        $photoPath = $photo->store('attendance', 'local');
        if (!is_string($photoPath) || $photoPath === '') {
            throw new RuntimeException('Foto bukti presensi gagal disimpan.');
        }

        return $photoPath;
    }



    public function updateSubmission(Request $request, $id)
    {
        $submission = TaskSubmission::findOrFail($id);

        if ($submission->intern_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized access');
        }

        if (!in_array($submission->status, ['Belum Dikerjakan', 'Dalam Proses', 'Revisi'], true)) {
            return back()->with('error', 'Tugas ini belum dapat diperbarui.');
        }

        $validated = $request->validate([
            'link_submission' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        $submission->update([
            'link_submission' => $validated['link_submission'] ?? $submission->link_submission,
            'notes' => $validated['notes'] ?? $submission->notes,
            'status' => 'Menunggu Review',
        ]);

        return back()->with('success', 'Tugas berhasil dikirim');
    }
}
