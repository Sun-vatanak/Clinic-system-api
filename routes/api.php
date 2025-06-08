<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\DrugController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NotificationController;

// USERS
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// APPOINTMENTS
Route::get('/appointments', [AppointmentController::class, 'index']);
Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);

// MEDICAL RECORDS
Route::get('/medical-records', [MedicalRecordController::class, 'index']);
Route::get('/medical-records/{id}', [MedicalRecordController::class, 'show']);
Route::post('/medical-records', [MedicalRecordController::class, 'store']);
Route::put('/medical-records/{id}', [MedicalRecordController::class, 'update']);
Route::delete('/medical-records/{id}', [MedicalRecordController::class, 'destroy']);

// PRESCRIPTIONS
Route::get('/prescriptions', [PrescriptionController::class, 'index']);
Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show']);
Route::post('/prescriptions', [PrescriptionController::class, 'store']);
Route::put('/prescriptions/{id}', [PrescriptionController::class, 'update']);
Route::delete('/prescriptions/{id}', [PrescriptionController::class, 'destroy']);

// DRUGS
Route::get('/drugs', [DrugController::class, 'index']);
Route::get('/drugs/{id}', [DrugController::class, 'show']);
Route::post('/drugs', [DrugController::class, 'store']);
Route::put('/drugs/{id}', [DrugController::class, 'update']);
Route::delete('/drugs/{id}', [DrugController::class, 'destroy']);

// SALES
Route::get('/sales', [SaleController::class, 'index']);
Route::get('/sales/{id}', [SaleController::class, 'show']);
Route::post('/sales', [SaleController::class, 'store']);
Route::put('/sales/{id}', [SaleController::class, 'update']);
Route::delete('/sales/{id}', [SaleController::class, 'destroy']);

// PURCHASES
Route::get('/purchases', [PurchaseController::class, 'index']);
Route::get('/purchases/{id}', [PurchaseController::class, 'show']);
Route::post('/purchases', [PurchaseController::class, 'store']);
Route::put('/purchases/{id}', [PurchaseController::class, 'update']);
Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy']);

// SUPPLIERS
Route::get('/suppliers', [SupplierController::class, 'index']);
Route::get('/suppliers/{id}', [SupplierController::class, 'show']);
Route::post('/suppliers', [SupplierController::class, 'store']);
Route::put('/suppliers/{id}', [SupplierController::class, 'update']);
Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy']);

// INVOICES
Route::get('/invoices', [InvoiceController::class, 'index']);
Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
Route::post('/invoices', [InvoiceController::class, 'store']);
Route::put('/invoices/{id}', [InvoiceController::class, 'update']);
Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy']);

// NOTIFICATIONS
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/{id}', [NotificationController::class, 'show']);
Route::post('/notifications', [NotificationController::class, 'store']);
Route::put('/notifications/{id}', [NotificationController::class, 'update']);
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
