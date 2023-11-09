<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderPublication;
use App\Models\Publication;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public $model = Order::class;
    public $s = "compra";
    public $sp = "compras";
    public $ss = "compra/s";
    public $v = "a"; 
    public $pr = "la"; 
    public $prp = "las";
    
    public function store(OrderRequest $request)
    {
        $message = "Error al registrar {$this->pr} {$this->s}";
        $new = new $this->model();
        try {
            $new->user_id = Auth::user()->id;
            $new->dollar_cotization = $request->dollar_cotization;
            $new->save();
            $this->savePublicationsOrder($request->products, $new->id);
            $data = $this->model::getAllOrder($new->id);
        } catch (ModelNotFoundException $error) {
            return response(["message" => "No se encontro {$this->pr} {$this->s}", "error" => $error->getMessage()], 404);
        } catch (Exception $error) {
            return response(["message" => "Error al registrar {$this->s}", "error" => $error->getMessage()], 500);
        }
        $message = "{$this->s} creada exitosamente";
        return response(compact("message", "data"));
    }

    public function show($id)
    {
        $order = $this->model::with($this->model::SHOW)->find($id);
        if(!$order)
           return response(["message" => "Error al recuperar {$this->s}, ID order invalido"], 400);

        return response(compact("order"));
    }

    public function change_status_order(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required',
        ]);
        
        $order = $this->model::with($this->model::SHOW)->find($request->id);
        if(!$order)
           return response(["message" => "Error al recuperar {$this->s}, ID order invalido"], 400);
           
        try {
            $order->status_id = $request->status_id;
            $order->save();
        } catch (Exception $error) {
            return response(["message" => "Error al actualizar estado de {$this->s}", "error" => $error->getMessage()], 500);
        }  

        $message = "Estado de {$this->s} actualizado exitosamente.";
        return response(compact("message", "order"));
    }

    public function savePublicationsOrder($products, $order_id)
    {
        foreach ($products as $product) {
            $order_publication = new OrderPublication($product);
            $order_publication->order_id = $order_id;
            $order_publication->save();

            $publication = Publication::find($product['publication_id']);
            $publication->stock = $publication->stock - $product['quantity'];
            $publication->save();
        }
    }
    
    public function get_my_orders()
    {
        $orders = $this->model::with($this->model::SHOW)->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        return response(compact("orders"));
    } 
}
