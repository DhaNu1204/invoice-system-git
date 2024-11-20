use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\InvoiceEmailController;

Route::middleware(['auth'])->group(function () {
    Route::resources([
        'clients' => ClientController::class,
        'invoices' => InvoiceController::class,
    ]);

    Route::get('/invoices/{invoice}/pdf/download', [InvoicePdfController::class, 'download'])
        ->name('invoices.pdf.download');
    Route::get('/invoices/{invoice}/pdf/view', [InvoicePdfController::class, 'stream'])
        ->name('invoices.pdf.view');
}); 

Route::middleware(['auth'])->group(function () {
    // ... existing routes ...
    
    Route::get('/invoices/{invoice}/email', [InvoiceEmailController::class, 'create'])
        ->name('invoices.email.create');
    Route::post('/invoices/{invoice}/email', [InvoiceEmailController::class, 'send'])
        ->name('invoices.email.send');
});

// Payment routes
Route::get('/invoices/{invoice}/payment', [InvoicePaymentController::class, 'create'])
    ->name('invoices.payment.create');
Route::get('/invoices/{invoice}/payment/store', [InvoicePaymentController::class, 'store'])
    ->name('invoices.payment.store');
Route::get('/invoices/{invoice}/payment/success', [InvoicePaymentController::class, 'success'])
    ->name('invoices.payment.success');