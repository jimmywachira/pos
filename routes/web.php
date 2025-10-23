<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Receipt;
use App\Livewire\PointOfSale;
use App\Livewire\Inventory\Products;
use App\Livewire\Inventory\Batches;
use App\Livewire\Reports\Sales;
use App\Livewire\Customers\Management;
use App\Livewire\Settings\General;
use App\Livewire\Users\Management as UserManagement;
use App\Livewire\Shifts\Management as ShiftManagement;

Route::get('/', fn () => redirect()->route('login'));
Route::get('receipt/{sale}', Receipt::class)->name('receipt.print');

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', General::class)->name('settings');
});

Route::get('/users', UserManagement::class)->name('users.management')->middleware('can:users.manage');

Route::get('/inventory/products', Products::class)->name('inventory.products');
Route::get('/inventory/batches', Batches::class)->name('inventory.batches');
Route::get('/reports/sales', Sales::class)->name('reports.sales');
Route::get('/customers', Management::class)->name('customers.management');
Route::get('/shifts', ShiftManagement::class)->name('shifts.management');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', PointOfSale::class)->name('pos');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
