<?php

namespace App\Http\Controllers;

use App\Http\Requests\QualifySellerRequest;
use App\Mail\recoverPasswordMailable;
use App\Models\Locality;
use App\Models\Publication;
use App\Models\SellerQualification;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
