<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes (if using Laravel Breeze)
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('customers/{customer}/invoices', [CustomerController::class, 'invoices'])
        ->name('customers.invoices');

    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::prefix('invoices')->group(function () {
        Route::get('{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])
            ->name('invoices.pdf');
        Route::post('{invoice}/send', [InvoiceController::class, 'sendToCustomer'])
            ->name('invoices.send');
        Route::get('{invoice}/print', [InvoiceController::class, 'print'])
            ->name('invoices.print');
        Route::post('{invoice}/send', [InvoiceController::class, 'send'])
            ->name('invoices.send');
    });

    // Products
    Route::resource('products', ProductController::class);

    // Payments
    Route::resource('payments', PaymentController::class);
    Route::prefix('payments')->group(function () {
        Route::get('invoice/{invoice}', [PaymentController::class, 'createForInvoice'])
            ->name('payments.create-for-invoice');
        Route::post('stripe/webhook', [PaymentController::class, 'stripeWebhook'])
            ->name('payments.stripe-webhook');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])
            ->name('settings.index');
        Route::put('/', [SettingController::class, 'update'])
            ->name('settings.update');
        Route::post('logo', [SettingController::class, 'updateLogo'])
            ->name('settings.logo');
    });

    // Profile routes (if using Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // API routes for dynamic data
    Route::prefix('api')->group(function () {
        Route::get('customers/search', [CustomerController::class, 'search'])
            ->name('api.customers.search');
        Route::get('products/search', [ProductController::class, 'search'])
            ->name('api.products.search');
        Route::get('invoices/{invoice}/calculate', [InvoiceController::class, 'calculate'])
            ->name('api.invoices.calculate');
    });

    Route::get('invoices/{invoice}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});

// Error pages
Route::fallback(function () {
    return view('errors.404');
});
