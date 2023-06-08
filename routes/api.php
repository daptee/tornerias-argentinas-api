<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
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
});

Route::controller(PublicationController::class)->group(function () {
    Route::post('qualify_product', 'qualify_product');
});

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('publications', PublicationController::class);
    Route::get('publications_featured', [PublicationController::class, 'get_featured']);
    Route::get('get_publications_filters', [PublicationController::class, 'get_publications_filters']);
});

Route::get('categories', [CategoryController::class, 'get_all_categories']);
