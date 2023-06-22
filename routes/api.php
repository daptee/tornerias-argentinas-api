<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\UserController;
use App\Models\Publication;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

Route::controller(UserController::class)->group(function () {
    Route::post('recover-password', 'recover_password_user');
    Route::post('qualify_seller', 'qualify_seller');
});

Route::controller(PublicationController::class)->group(function () {
    Route::post('qualify_product', 'qualify_product');
});

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('users/update', [UserController::class, 'update']);
    Route::post('publications', [PublicationController::class, 'store']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('get_my_publications', [PublicationController::class, 'get_my_publications']);
    Route::get('get_my_orders', [OrderController::class, 'get_my_orders']);
});

Route::get('categories', [CategoryController::class, 'get_all_categories']);
Route::resource('publications', PublicationController::class);
Route::get('get_publications_filters', [PublicationController::class, 'get_publications_filters']);
Route::get('publications_featured', [PublicationController::class, 'get_featured']);
