<?php

namespace App\Http\Controllers;

use App\Mail\formContactMailable;
use App\Models\formContact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GeneralController extends Controller
{
    public function form_contact(Request $request)
    {
        $request->validate(['name', 'last_name', 'email', 'text']);

        try {
            $form_contact = new formContact($request->all());
            $form_contact->save();
            
            $data = [
                'name' => $request->name . ' ' . $request->last_name,
                'email' => $request->email,
                'text' => $request->text,
            ];

            // Mail::to('info@torneriasargentinas.com')->send(new formContactMailable($data));
            Mail::to('enzo100amarilla@gmail.com')->send(new formContactMailable($data));
        } catch (Exception $error) {
            return response(["error" => $error->getMessage()], 500);
        }
       
        return response()->json(['message' => 'Contacto guardado y enviado con exito.'], 200);
    }
}
