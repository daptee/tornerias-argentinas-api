<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\LocalityProvinceController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\UserController;
use App\Models\Publication;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    
    // User Controller
    Route::post('users/update', [UserController::class, 'update']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users/update/profile/picture', [UserController::class, 'update_profile_picture']);
    Route::post('qualify_seller', [UserController::class,'qualify_seller']);
    
    // Publication Controller
    Route::post('publications', [PublicationController::class, 'store']);
    Route::post('publications/update/{id_publication}', [PublicationController::class, 'update']);
    Route::delete('publications/{publication}', [PublicationController::class, 'destroy']);
    Route::post('publications/pause/{publication}', [PublicationController::class, 'pause_publication']);
    Route::get('get_my_publications', [PublicationController::class, 'get_my_publications']);
    Route::post('qualify_product', [PublicationController::class, 'qualify_product']);
    Route::post('publications/new/ask', [PublicationController::class, 'new_ask_answer_publication']);
    Route::post('publications/new/answer', [PublicationController::class, 'new_ask_answer_publication']);

    // Order Controller
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('get_my_orders', [OrderController::class, 'get_my_orders']);

    // Mercado Pago
    Route::post('users/vinculation/mp/data', [UserController::class, 'vinculation_MP_user']);

});

// Category Controller
Route::get('categories', [CategoryController::class, 'get_all_categories']);

// Publication Controller
Route::resource('publications', PublicationController::class);
Route::get('get_publications_filters', [PublicationController::class, 'get_publications_filters']);
Route::get('publications_featured', [PublicationController::class, 'get_featured']);

// Localities
Route::get('localities', [LocalityProvinceController::class, 'get_localities']);
Route::get('provinces', [LocalityProvinceController::class, 'get_provinces']);

// Clear cache
Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('optimize');

    return response()->json([
        "message" => "Cache cleared successfully"
    ]);
});

Route::post('payment/mercadopago/preference', [MercadoPagoController::class, 'create_pay']);
Route::post('form/contact', [GeneralController::class, 'form_contact']);