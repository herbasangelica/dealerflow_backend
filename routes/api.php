<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ManufacturerController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CarModelController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DealerController;
use App\Http\Controllers\Api\DealerVehicleController;
use App\Http\Controllers\Api\ManufacturerVehicleController;
use App\Http\Controllers\Api\SaleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, "login"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/users", [UserController::class, "getUser"]);
Route::post("/create-user", [UserController::class, "create"]);


Route::post("/manufacturer", [ManufacturerController::class, "createManufacturer"]);
Route::get("/manufacturer", [ManufacturerController::class, "getManufacturer"]);


Route::post("/create-brand", [BrandController::class, "createBrand"]);
Route::get("/get-brands", [BrandController::class, "getBrand"]);


Route::post("/create-car-model", [CarModelController::class, "createCarModel"]);
Route::get("/get-car-model", [CarModelController::class, "getCarModel"]);
Route::get("/specific-model-brand/{brandId}", [CarModelController::class, "getCarModelByBrand"]);


Route::post("/create-dealer", [DealerController::class, "createDealer"]);
Route::get("/get-dealers", [DealerController::class, "getDealer"]);


Route::post("/create-manufacturer-vehicle", [ManufacturerVehicleController::class, "createManufacturerVehicle"]);
Route::get("/get-manufacturer-vehicle", [ManufacturerVehicleController::class, "getManufacturerVehicle"]);


Route::post("/create-dealer-vehicle", [DealerVehicleController::class, "createDealerVehicle"]);
Route::get("/get-dealer-vehicle", [DealerVehicleController::class, "getDealerVehicle"]);
Route::get("/dealer-vehicle-by-dealer/{brandId}", [DealerVehicleController::class, "getDealerVehicleByDealer"]);
Route::get("/dealer-vehicle-by-car-model/{modelId}", [DealerVehicleController::class, "getDealerVehicleByCarModel"]);
Route::get("/search-vin", [DealerVehicleController::class, "getDealerVehicleVINSearch"]);


Route::post("/create-customer", [CustomerController::class, "createCustomer"]);
Route::get("/get-customer", [CustomerController::class, "getCustomer"]);


Route::post("/create-sales", [SaleController::class, "createSale"]);
Route::get("/get-sales", [SaleController::class, "getSale"]);
Route::get("/get-sales-by-dealer/{dealerId}", [SaleController::class, "getSaleByDealer"]);
Route::get("/search-sale-vin", [SaleController::class, "searchSaleByVin"]);
