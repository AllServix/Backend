<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\UsersController;
use App\http\Controllers\AppointmentsController;

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


Route::prefix('/users')->group(function(){

    Route::put('/register', [UsersController::class, 'register']);
    Route::post('/login', [UsersController::class, 'login']);
    Route::post('/recoverPassword', [UsersController::class, 'recoverPassword']);

});

Route::prefix('/appointments')->group(function(){

    Route::middleware('auth:sanctum')->put('/registerAppointments',[AppointmentsController::class,'registerAppointments']);
    Route::middleware('auth:sanctum')->delete('/deleteAppointment',[AppointmentsController::class,'deleteAppointment']);
    
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
