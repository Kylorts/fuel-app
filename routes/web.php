<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// ── Public ───────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ── Auth ─────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Authenticated ─────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // ── Orders (US 1.x) ──────────────────────────────────────────
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');

    // ── Invoices (US 2.1) ────────────────────────────────────────
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    // Generate invoice from an approved order (POST to order's sub-resource)
    Route::post('/orders/{order}/invoice', [InvoiceController::class, 'store'])->name('invoices.store');

    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

});
