<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\InternController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Supervisor Routes
Route::middleware(['auth', 'role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/dashboard', [SupervisorController::class, 'dashboard'])->name('dashboard');
    Route::post('/announcements', [SupervisorController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::get('/tasks', [SupervisorController::class, 'tasks'])->name('tasks');
    Route::post('/tasks', [SupervisorController::class, 'storeTask'])->name('tasks.store');
    Route::delete('/tasks/{id}', [SupervisorController::class, 'deleteTask'])->name('tasks.delete');
    Route::get('/interns', [SupervisorController::class, 'interns'])->name('interns');
    Route::get('/interns/{id}', [SupervisorController::class, 'internDetail'])->name('interns.detail');
    Route::get('/attendance', [SupervisorController::class, 'attendance'])->name('attendance');
    Route::get('/attendance/{id}/photo/{type}', [SupervisorController::class, 'attendancePhoto'])->name('attendance.photo');
    Route::get('/reviews', [SupervisorController::class, 'reviews'])->name('reviews');
    Route::post('/reviews/{id}', [SupervisorController::class, 'reviewSubmission'])->name('reviews.submit');
    Route::get('/performance', [SupervisorController::class, 'performance'])->name('performance');
    Route::get('/performance/{id}', [SupervisorController::class, 'performanceReport'])->name('performance.report');
});

// Intern Routes
Route::middleware(['auth', 'role:intern'])->prefix('intern')->name('intern.')->group(function () {
    Route::get('/dashboard', [InternController::class, 'dashboard'])->name('dashboard');
    Route::get('/tasks', [InternController::class, 'tasks'])->name('tasks');
    Route::get('/progress', [InternController::class, 'progress'])->name('progress');
    Route::get('/attendance', [InternController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/check-in', [InternController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [InternController::class, 'checkOut'])->name('attendance.check-out');
    Route::get('/attendance/{id}/photo/{type}', [InternController::class, 'attendancePhoto'])->name('attendance.photo');
    Route::post('/submission/{id}', [InternController::class, 'updateSubmission'])->name('update-submission');
});