<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/ping', function () {
    return ['pong' => true];
});

// Quando se utiliza sanctum ou JWT deve ser utilizada uma rota com name = login
Route::get('/unauthenticated', function () {
    return ['error'=> 'Usuário não logado'];
})->name('login');

Route::post('/user',[AuthController::class,'create']);

// Logout com Sanctum
Route::middleware('auth:sanctum')->get('/auth/logout',[AuthController::class,'logout']);

// Logout com JWT
Route::middleware('auth:api')->post('/auth/logoutSanctum',[AuthController::class,'logoutSanctum']);


Route::middleware('auth:api')->get('/auth/me',[AuthController::class,'me']);


// Autenticando com Sanctum
Route::post('/auth',[AuthController::class,'login']);

// Autenticando com JWT
Route::post('/auth/login',[AuthController::class,'loginJWT']);

// Protegendo a rota utilizando sanctum
//Route::middleware('auth:sanctum')->post('/todo',[ApiController::class,'createTodo']);

// Protegendo a rota utilizando JWT
Route::middleware('auth:api')->post('/todo',[ApiController::class,'createTodo']);
Route::get('/todos',[ApiController::class,'readAllTodos']);
Route::get('/todo/{id}',[ApiController::class,'readTodo']);
Route::put('/todo/{id}',[ApiController::class,'updateTodo']);
Route::delete('/todo/{id}',[ApiController::class,'deleteTodo']);

