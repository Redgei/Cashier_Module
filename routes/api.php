<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\BillingController;
// use App\Http\Controllers\AssessmentsController; // Assuming this controller is still needed and not in Api namespace

// Student Routes
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{student_id}', [StudentController::class, 'show']);
Route::delete('/students/{student_id}', [StudentController::class, 'destroy']);
Route::put('/students/{student_id}', [StudentController::class, 'update']);

// Billing Routes
Route::post('/billings', [BillingController::class, 'store']);
Route::get('/billings', [BillingController::class, 'index']);
Route::get('/billings/{billing_id}', [BillingController::class, 'show']);
Route::delete('/billings/{billing_id}', [BillingController::class, 'destroy']);
Route::put('/billings/{billing_id}', [BillingController::class, 'update']);

// Payment Routes
Route::post('/payments', [PaymentController::class, 'store']);