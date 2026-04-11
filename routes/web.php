<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // ── Invoice routes (US 2.1) ──────────────────────────────────
    Route::get('/invoices', [InvoiceController::class, 'index'])
        ->name('invoices.index');

    // Generate invoice from an approved order
    Route::post('/orders/{order}/invoice', [InvoiceController::class, 'store'])
        ->name('invoices.store');

    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
        ->name('invoices.show');

    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])
        ->name('invoices.download');

});
