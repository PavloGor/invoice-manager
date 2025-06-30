<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Invoice Manager Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your invoice manager package.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

// Dashboard route
Route::get('/dashboard', function () {
    $totalInvoices = \App\Models\Invoice::count();
    $paidInvoices = \App\Models\Invoice::where('status', 'paid')->count();
    $sentInvoices = \App\Models\Invoice::where('status', 'sent')->count();
    $draftInvoices = \App\Models\Invoice::where('status', 'draft')->count();
    $pendingInvoices = $sentInvoices + $draftInvoices;
    $totalAmount = \App\Models\Invoice::sum('amount');
    $recentInvoices = \App\Models\Invoice::latest()->take(5)->get();
    $overdueInvoices = \App\Models\Invoice::where('due_date', '<', now())
                                          ->where('status', '!=', 'paid')
                                          ->count();
    
    return view('dashboard', compact(
        'totalInvoices', 
        'paidInvoices', 
        'sentInvoices', 
        'draftInvoices', 
        'pendingInvoices', 
        'totalAmount', 
        'recentInvoices', 
        'overdueInvoices'
    ));
})->name('dashboard');

// Invoice routes
Route::resource('invoices', InvoiceController::class);

// Additional invoice routes
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});