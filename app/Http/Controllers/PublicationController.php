<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicationRequest;
use App\Models\Publication;
use App\Models\PublicationCategory;
use App\Models\PublicationFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicationController extends Controller
{

    public $model = Publication::class;
    public $s = "publicacion";
    public $sp = "publicaciones";
    public $ss = "publicacion/es";
    public $v = "o"; 
    public $pr = "la"; 
    public $prp = "las";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->model::select($this->model::SELECT_INDEX)->with($this->model::INDEX)
                            ->orderBy('id', 'desc')->take(4)
                            ->get();

        return response(compact("data"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PublicationRequest $request)
    {
        $message = "Error al crear {$this->pr} {$this->s}";
        $data = $request->all();
        $new = new $this->model($data);
        try {
            $new->save();
            $this->saveCategoriesPublication($request->categories, $new->id);
            $this->saveFilesPublication($request->publication_files, $new->id);
            $data = $this->model::with($this->model::SHOW)->findOrFail($new->id);
        } catch (ModelNotFoundException $error) {
            return response(["message" => "No se encontro {$this->pr} {$this->s}", "error" => $error->getMessage()], 404);
        } catch (Exception $error) {
            return response(["message" => "Error al recuperar {$this->s}", "error" => $error->getMessage()], 500);
        }
        $message = "{$this->s} creada exitosamente";
        return response(compact("message", "data"));
    }

    public function saveCategoriesPublication($categories, $publication_id)
    {
        foreach($categories as $category){
            $publication_category = new PublicationCategory();
            $publication_category->publication_id = $publication_id;
            $publication_category->category_id = $category;
            $publication_category->save();
        }
    }

    public function saveFilesPublication($files, $publication_id)
    {
        foreach($files as $file){
            $fileName = Str::random(5) . time() . '.' . $file->extension();
                        
            $file->move(public_path("publications/$publication_id"), $fileName);
            
            $path = "/publications/$publication_id/$fileName";
            
            $publication_category = new PublicationFile();
            $publication_category->publication_id = $publication_id;
            $publication_category->url = $path;
            $publication_category->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
