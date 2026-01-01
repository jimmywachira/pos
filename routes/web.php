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

Route::get('/inventory/products', Products::class)->name('inventory.products')->middleware('auth');
Route::get('/inventory/batches', Batches::class)->name('inventory.batches')->middleware('auth');
Route::get('/reports/sales', Sales::class)->name('reports.sales')->middleware('auth');
Route::get('/customers', Management::class)->name('customers.management')->middleware('auth');
Route::get('/shifts', ShiftManagement::class)->name('shifts.management')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', PointOfSale::class)->name('pos');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/about', function () {
    return view('components.layouts.about');
})->name('about');

Route::get('/press', function () {
    return view('components.layouts.press');
})->name('press');

Route::get('/blog', function () {
    return view('components.layouts.blog');
})->name('blog');

Route::get('/jobs', function () {
    return view('components.layouts.jobs');
})->name('jobs');

Route::get('/pricing', function () {
    return view('components.layouts.pricing');
})->name('pricing');

Route::get('/documentation', function () {
    return view('components.layouts.documentation');
})->name('documentation');

Route::get('/guides', function () {
    return view('components.layouts.guides');
})->name('guides');

Route::get('/api-status', function () {
    return view('components.layouts.api-status');
})->name('api-status');


Route::get('/claim', function () {
    return view('components.layouts.claim');
})->name('claim');

Route::get('/privacy', function () {
    return view('components.layouts.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('components.layouts.terms');
})->name('terms');


require __DIR__.'/auth.php';
