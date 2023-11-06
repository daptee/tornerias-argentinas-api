<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderPublication;
use App\Models\Publication;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            $new->price = $request->price;
            $new->save();
            $this->savePublicationsOrder($request->products, $new->id);
            $data = $this->model::getAllOrder($new->id);
        } catch (ModelNotFoundException $error) {
            return response(["message" => "No se encontro {$this->pr} {$this->s}", "error" => $error->getMessage()], 404);
        } catch (Exception $error) {
            return response(["message" => "Error al recuperar {$this->s}", "error" => $error->getMessage()], 500);
        }
        $message = "{$this->s} creada exitosamente";
        return response(compact("message", "data"));
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
