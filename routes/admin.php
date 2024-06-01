<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\admin\BrandModelsController;
use App\Http\Controllers\admin\UsersController;

use App\Http\Controllers\admin\VehiclesController;



// Route::get('/brands', [App\Http\Controllers\admin\BrandsController::class, 'index'])->name('admin.brands.index');
// Route::get('/brands/create', [App\Http\Controllers\admin\BrandsController::class, 'create'])->name('admin.brands.create');
// Route::post('/brands/store', [App\Http\Controllers\admin\BrandsController::class, 'store'])->name('admin.brands.store');
// Route::get('/brands/{id}/edit', [App\Http\Controllers\admin\BrandsController::class,'edit'])->name('admin.brands.edit');
// Route::put('/brands/{id}/update', [App\Http\Controllers\admin\BrandsController::class, 'update'])->name('admin.brands.update');
// Route::put('/brands/{id}/destroy', [App\Http\Controllers\admin\BrandsController::class, 'destroy'])->name('admin.brands.destroy');
Route::resource('brands',BrandsController::class)->names('admin.brands');
Route::resource('models',BrandModelsController::class)->names('admin.models');
Route::resource('vehicles',VehiclesController::class)->names('admin.vehicles');
Route::post('vehicles/{id}/assign', [VehiclesController::class, 'assignOccupants'])->name('admin.vehicles.assignOccupants');
Route::get('modelsbybrand/{id}', [BrandModelsController::class, 'modelsbybrand'])->name('admin.modelsbybrand');
Route::resource('users',UsersController::class)->names('admin.users');


?>
