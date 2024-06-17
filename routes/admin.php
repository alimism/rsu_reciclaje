<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\admin\BrandModelsController;
use App\Http\Controllers\admin\RoutesController;
use App\Http\Controllers\admin\RoutezonesController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\UsertypesController;
use App\Http\Controllers\admin\VehiclesController;
use App\Http\Controllers\admin\VehicletypesController;
use App\Http\Controllers\admin\ZonecoordsController;
use App\Http\Controllers\admin\ZonesController;
use App\Models\BrandModel;
use App\Models\User;

// Route::get('/brands', [App\Http\Controllers\admin\BrandsController::class, 'index'])->name('admin.brands.index');
// Route::get('/brands/create', [App\Http\Controllers\admin\BrandsController::class, 'create'])->name('admin.brands.create');
// Route::post('/brands/store', [App\Http\Controllers\admin\BrandsController::class, 'store'])->name('admin.brands.store');
// Route::get('/brands/{id}/edit', [App\Http\Controllers\admin\BrandsController::class,'edit'])->name('admin.brands.edit');
// Route::put('/brands/{id}/update', [App\Http\Controllers\admin\BrandsController::class, 'update'])->name('admin.brands.update');
// Route::put('/brands/{id}/destroy', [App\Http\Controllers\admin\BrandsController::class, 'destroy'])->name('admin.brands.destroy');
Route::resource('brands',BrandsController::class)->names('admin.brands');
Route::resource('models',BrandModelsController::class)->names('admin.models');
Route::resource('vehicles',VehiclesController::class)->names('admin.vehicles');
Route::resource('vehicletypes',VehicletypesController::class)->names('admin.vehicletypes');
Route::resource('usertypes',UsertypesController::class)->names('admin.usertypes');
Route::post('vehicles/{id}/assign', [VehiclesController::class, 'assignOccupants'])->name('admin.vehicles.assignOccupants');
Route::get('modelsbybrand/{id}', [BrandModelsController::class, 'modelsbybrand'])->name('admin.modelsbybrand');
Route::resource('users',UsersController::class)->names('admin.users');
Route::resource('zones',ZonesController::class)->names('admin.zones');
Route::resource('zonecoords',ZonecoordsController::class)->names('admin.zonecoords');
Route::resource('routes',RoutesController::class)->names('admin.routes');
Route::post('routes/{route}/assignZone', [RoutezonesController::class, 'assignZone'])->name('admin.routes.assignZone');
Route::delete('routes/{route}/unassignZone/{zone}', [RoutezonesController::class, 'unassignZone'])->name('admin.routes.unassignZone');
?>
