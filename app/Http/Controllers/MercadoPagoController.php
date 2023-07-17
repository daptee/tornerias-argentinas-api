<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago;

class MercadoPagoController extends Controller
{
    public function createPay(Request $request)
{
    // SDK de Mercado Pago
    require base_path('vendor/autoload.php');
    // Agrega credenciales
    MercadoPago\SDK::setAccessToken(config('services.mercadopago.token'));

    // Crea un objeto de preferencia
    $preference = new MercadoPago\Preference();
    // $preference->sandbox_mode = true; 
    $preference->back_urls = array(
        "success" => $request->url_back,
        "failure" => $request->url_back,
        "pending" => $request->url_back
    );
    $preference->auto_return = "approved";

    // Crea un ítem en la preferencia
    $item = new MercadoPago\Item();
    $item->title = $request->title;
    $item->quantity = $request->quantity;
    $item->unit_price = $request->unit_price;
    $preference->items = array($item);
    $preference->save();

    // $payer_email = $preference->payer_email;
    // Log::debug($payer_email);
    // dd($preference->payer);

    // Configurar tus credenciales de API de MercadoPago
    // $access_token = MercadoPago\SDK::getAccessToken();

     // Realizar una llamada a la API para obtener los datos del usuario actual
     $response = MercadoPago\SDK::get('/users/me');

     // Obtener el ID de usuario desde la respuesta
     $user_id = $response['body']['id'];
 
     // Imprimir el ID de usuario
     echo $user_id;
    Log::debug($user_id);

    // echo $email;
    // Log::debug($email);

    // Realizar transferencia al dueño de la publicación
    $transfer = new MercadoPago\Payment();
    $transfer->amount = 80; // Monto a transferir al dueño de la publicación
    $transfer->payer_email = 'usuariodeprueba@gmail.com'; // Dirección de correo electrónico del pagador
    $transfer->save();
    
    // Realizar transferencia a otro usuario (comisión)
    $commissionTransfer = new MercadoPago\Payment();
    $commissionTransfer->amount = 20; // Monto de la comisión
    $commissionTransfer->payer_email = $payer_email; // Dirección de correo electrónico del pagador
    $commissionTransfer->save();

    return response()->json(['preference' => $preference->id], 200);
}


}