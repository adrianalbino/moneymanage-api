<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EntryController;

// Route::resource('account', AccountController::class);

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Private routes
Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::get('/token', [AuthController::class, 'getUserDetails']);
  Route::post('/logout', [AuthController::class, 'logout']);

  // Account
  Route::post('/account/{id}', [AccountController::class, 'store']);
  Route::put('/account/{id}', [AccountController::class, 'update']);
  Route::get('/account', [AccountController::class, 'index']);
  Route::get('/account/get/{id}', [AccountController::class, 'showById']);
  Route::get('/account/{id}', [AccountController::class, 'show']);

  //Category
  Route::post('/category/{id}', [CategoryController::class, 'store']);
  Route::get('/category', [CategoryController::class, 'index']);
  Route::get('/category/getAccountCategories/{id}', [CategoryController::class, 'show']);
  Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

  // Entry
  Route::post('/entry/{id}', [EntryController::class, 'store']);
  Route::get('/entry/showByUser/{id}', [EntryController::class, 'showByUser']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});
