<?php

namespace App\Http\Controllers;

use App\Http\Requests\QualifySellerRequest;
use App\Mail\recoverPasswordMailable;
use App\Models\Locality;
use App\Models\Publication;
use App\Models\SellerQualification;
use App\Models\User;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public $model = User::class;
    public $s = "usuario";
    public $sp = "usuarios";
    public $ss = "usuario/s";
    public $v = "o"; 
    public $pr = "el"; 
    public $prp = "los";

    public function recover_password_user(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $user = User::where('email', $request->email)->first();

        if(!$user)
            return response()->json(['message' => 'No existe un usuario con el mail solicitado.'], 402);
        
        try {
            $new_password = Str::random(16);
            $user->password = Hash::make($new_password);
            $user->save();
            
            $data = [
                'name' => $user->nombre,
                'email' => $user->email,
                'password' => $new_password,
            ];
            Mail::to($user->email)->send(new recoverPasswordMailable($data));
        } catch (Exception $error) {
            return response(["error" => $error->getMessage()], 500);
        }
       
        return response()->json(['message' => 'Correo enviado con exito.'], 200);
        
    }

    public function update(Request $request)
    {
        if (Auth::check()) {

            $request->validate([
                'email' => 'unique:users,email,' . Auth::user()->id
            ]);

            if(!Locality::find($request->locality_id))
                return response()->json(['message' => 'Localidad no encontrada, por favor verifica el valor en locality_id.'], 400);

            $user = Auth::user();
        
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->locality_id = $request->locality_id;
            
            if(isset($request->password))
                $user->password = Hash::make($request->password);
    
            $user->save();
        }else{
            return response()->json(['message' => 'Usuario no autenticado.'], 400);
        }

        return response()->json([
            'message' => 'Usuario actualizado con exito.',
            'user' => $this->model::getAllDataUser($user->id)
        ]);
    }

    public function qualify_seller(QualifySellerRequest $request)
    {
        $user = User::find($request->user_id);

        if(!$user)
            return response(["message" => "No existe usuario con el user_id otorgado."], 400);
            
        $seller = User::find($request->seller_id);

        if(!$seller)
            return response(["message" => "No existe vendedor con el seller_id otorgado."], 400);

        $existing_qualification = SellerQualification::where('user_id', $request->user_id)->where('seller_id', $request->seller_id)->count();
        
        if($existing_qualification > 0)
            return response(["message" => "Este vendedor ya posee calificación de este usuario."], 400);

        $seller_qualification = SellerQualification::create($request->all());
        $message = "Vendedor calificado exitosamente";
        
        $seller_qualification = SellerQualification::get_all_seller_qualification($seller_qualification->id);
        
        return response(compact("message", "seller_qualification"));
    }

    public function vinculation_MP_user(Request $request)
    {
        try {
            $user = Auth::user();
            $url = $request->input('url');
            $code = '';

            if (strpos($url, 'code') !== false) {
                $code = explode('?code=', $url)[1];

                $response = Http::post(config('services.mercadopago.vinculation'), [
                    'client_secret' => 'APP_USR-1967661118313269-033015-4a20088a2a111891e29f18575ff28ba3-688827045',
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => config('services.front_end.url') . '/configuration/payments',
                ]);
    
                $user->mercadopago = $response->json(); // Nuevo Campo -> mercadopago
                $mercadopagoData = Http::get(config('services.mercadopago.users_data') . '/' . $user->mercadopago['user_id'])->json();
                $user->mercadopagoData = $mercadopagoData; // Nuevo Campo -> mercadopagoData
                $user->save();
    
                return response()->json($user, 200);
            }
        } catch (\Exception $error) {
            error_log($error);
            return response()->json(['message' => '500 Internal Server Error'], 500);
        }
    }

    public function update_profile_picture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required',
        ]);

        $user = Auth::user();
        
        $file_path = public_path($user->profile_picture);
        
        if (file_exists($file_path))
                 unlink($file_path);

        $path = $this->save_image_public_folder($request->profile_picture, "users/profiles/", null);
        
        $user->profile_picture = $path;
        $user->save();

        $message = "Usuario actualizado exitosamente";

        return response(compact("message", "user"));
    }

    public function save_image_public_folder($file, $path_to_save, $variable_id)
    {
        $fileName = Str::random(5) . time() . '.' . $file->extension();
                        
        if($variable_id){
            $file->move(public_path($path_to_save . $variable_id), $fileName);
            $path = "/" . $path_to_save . $variable_id . "/$fileName";
        }else{
            $file->move(public_path($path_to_save), $fileName);
            $path = "/" . $path_to_save . $fileName;
        }
        

        return $path;
    }
}
