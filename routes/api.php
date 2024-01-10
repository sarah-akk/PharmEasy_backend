<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->group(function (){
    Route::get('logout',[AuthController::class,'logout_user']);
    Route::post('/search',[MedicineController::class,'search']);
    Route::get('/choseToShow/{Id}',[MedicineController::class,'choseToShow']);
    Route::get('/showAll',[MedicineController::class,'showAll']);
    Route::get('/medicines_category_Id/{Id}',[MedicineController::class,'MedicinesByCategoryId']);
    Route::post('/store',[MedicineController::class,'store']);
   // Route::get('/viewOrder',[OrderController::class,'viewOrder']);
});
Route::post ('register',[AuthController::class,'register_user']);
Route::post ('login ',[AuthController::class,'login_user']);
Route::post('login/admin ',[AuthController::class,'logintoAdmin']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware'=>['auth:sanctum','admin']], function () {
    Route::post('/store',[MedicineController::class,'store']);
    Route::get('/viewAdmin',[OrderController::class,'viewOrderAdmin']);
    Route::put('/changeOrder/{Id}', [OrderController::class, 'changeOrderStatus']);
    Route::put('/changeOrderPaid/{Id}', [OrderController::class, 'changePaymentStatus']);
    Route::post('/report',[OrderController::class,'orderSalesReport']);
    Route::post('/orderReport',[OrderController::class,'orderReport']);

});
Route::group(['middleware'=>['auth:sanctum','pharmacy']], function () {
    Route::post('/makeOrder',[OrderController::class,'makeOrder']);
    Route::post('/favorite',[OrderController::class,'favorite_medicine']);
   Route::get('/viewOrder',[OrderController::class,'viewOrder']);
    Route::get('/favorite/{id}',[OrderController::class,'favorite_medicine']);
    Route::get('/unfavorite/{id}',[OrderController::class,'unfavorite_medicine']);
    Route::get('/checkfavorite/{id}',[OrderController::class,'checkFavoriteStatus']);
    Route::get('/list',[OrderController::class,'list']);
});

