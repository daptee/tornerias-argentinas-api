<?php

namespace App\Http\Controllers;

use App\Mail\recoverPasswordMailable;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
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
}
