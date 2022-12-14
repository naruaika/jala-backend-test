<?php

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PurchaseOrderController as AdminPurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleOrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/sale-orders', [SaleOrderController::class, 'index'])->name('sale-orders.index');
    Route::post('/sale-orders', [SaleOrderController::class, 'store'])->name('sale-orders.store');

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::resource('products', AdminProductController::class)
            ->only(['index', 'store'])
            ->names([
                'index' => 'admin.products.index',
                'store' => 'admin.products.store',
            ]);
        Route::resource('purchase-orders', AdminPurchaseOrderController::class)
            ->only(['index', 'store'])
            ->names([
                'index' => 'admin.purchase-orders.index',
                'store' => 'admin.purchase-orders.store',
            ]);
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
