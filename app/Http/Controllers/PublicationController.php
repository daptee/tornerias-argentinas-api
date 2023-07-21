<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicationRequest;
use App\Http\Requests\QualifyProductRequest;
use App\Models\Publication;
use App\Models\PublicationCategory;
use App\Models\PublicationFile;
use App\Models\PublicationQualification;
use App\Models\PublicationQuestionAnswer;
use App\Models\PublicationStatus;
use App\Models\PublicationStatusHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicationController extends Controller
{

    public $model = Publication::class;
    public $s = "publicacion";
    public $sp = "publicaciones";
    public $ss = "publicacion/es";
    public $v = "a"; 
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

    public function get_featured()
    {
        $data = $this->model::select($this->model::SELECT_INDEX)->with($this->model::INDEX)
        ->orderBy('id', 'desc')->take(6)
        ->get();

        return response(compact("data"));
    }

    public function get_publications_filters(Request $request)
    {
        $message = "Error al traer listado de {$this->sp}.";
        try {
            // ->when($request->category_id, function ($query) use ($request) {
            //     return $query->whereHas('categories', function ($subQuery) use ($request) {
            //         $subQuery->where('category_id', $request->category_id);
            //     });
            // })
            
            // filtro de status = 2 
            $query = $this->model::select($this->model::SELECT_INDEX)->with($this->model::INDEX)
            ->when($request->price_from, function ($query) use ($request) {
                return $query->where('price', '>=', $request->price_from);
            })
            ->when($request->price_to, function ($query) use ($request) {
                return $query->where('price', '<=', $request->price_to);
            })
            ->when($request->q, function ($query) use ($request) {
                return $query->where('title', 'LIKE', '%'.$request->q.'%');
            })
            ->when($request->categories != null, function ($query) use ($request) {
                return $query->WhereHas('categories', function ($q) use ($request) {
                    $q->whereIn('category_id', $request->categories);
                });
            })
            ->orderBy('id', 'desc');
            
            $total = $query->count();
            $total_per_page = 30;
            $data  = $query->paginate($total_per_page);
            $current_page = $request->page ?? $data->currentPage();
            $last_page = $data->lastPage();

        } catch (ModelNotFoundException $error) {
            return response(["message" => "No se encontraron " . $this->sp . "."], 404);
        } catch (Exception $error) {
            return response(["message" => $message, "error" => $error->getMessage()], 500);
        }
        $message = ucfirst($this->sp) . " encontrad{$this->v}s exitosamente.";

        return response(compact("message", "data", "total", "total_per_page", "current_page", "last_page"));
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
            $new->user_id = Auth::user()->id;
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
            $existing_publication_category = PublicationCategory::where('publication_id', $publication_id)->where('category_id', $category)->first();
            if(!$existing_publication_category) {
                $publication_category = new PublicationCategory();
                $publication_category->publication_id = $publication_id;
                $publication_category->category_id = $category;
                $publication_category->save();
            }
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

    public function deleteImagesPublication($array_files_id, $publication_id)
    {
        foreach ($array_files_id as $file_id) {
            $publication_file = PublicationFile::find($file_id);
            
            if($publication_file && $publication_file->publication_id == $publication_id){
                // Obtiene la ruta completa del archivo en el sistema de archivos
                $file_path = public_path($publication_file->url);
    
                // Verifica si el archivo existe antes de intentar eliminarlo
                if (file_exists($file_path)) {
                    // Elimina el archivo físico
                    unlink($file_path);
                }
    
                $publication_file->delete();
            }
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
        $publication = Publication::find($id);
        if(!isset($publication))
            return response(["message" => "Publicacion no existente."], 400);

        $publication = $this->getAllPublication($id);
        return response(compact("publication"));
    }

    public function getAllPublication($id)
    {
        $publication = $this->model::select($this->model::SELECT_SHOW)->with($this->model::SHOW)->find($id);
        $publication->related_publications = $this->get_related_publications($id);
        return $publication;
    }

    public function get_related_publications($id)
    {
        $array_publication_categories = PublicationCategory::where('publication_id', $id)->pluck('category_id')->toArray();

        $related_publications = PublicationCategory::where('publication_id', '!=', $id)
                                ->whereIn('category_id', $array_publication_categories)
                                ->get();

        $array_related_publications = [];
        foreach ($related_publications as $related_publication) {
            if(!in_array($related_publication->publication_id, $array_related_publications))
                $array_related_publications[] = $related_publication->publication_id;
        };

        return $this->model::select($this->model::SELECT_SHOW)->with($this->model::SHOW)->where('id', '!=', $id)
                                            ->whereIn('id', $array_related_publications)
                                            ->get();
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
    public function update(Request $request, $id_publication)
    {
        $publication = Publication::find($id_publication);
        if(!$publication)
            return response(["message" => "No existe publicación con el publication_id otorgado."], 400);
        
        if($publication->user_id != Auth::user()->id)
            return response(["message" => "No puede modificar esta publicación."], 400);
     
        try {
            DB::transaction(function () use($publication, $request) {
                $publication->update($request->all()); 
                $this->saveCategoriesPublication($request->categories, $publication->id);
                
                if($request->delete_files)
                    $this->deleteImagesPublication($request->delete_files, $publication->id);
                
                if($request->publication_files)
                    $this->saveFilesPublication($request->publication_files, $publication->id);
            });
            $publication = $this->getAllPublication($publication->id);
        } catch (\Throwable $th) {
            Log::debug(print_r([$th->getMessage() . ", error al pausar publicación ID: $publication->id", $th->getLine()],  true));
        }

        $message = "Publicación actualizada con exito.";
        return response(compact("publication", "message"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {   
        try {
            if($publication->user_id != Auth::user()->id)
                return response(["message" => "No puede modificar esta publicación."], 400);

            $publication->delete();
        } catch (ModelNotFoundException $exception) {
            return response(["message" => "Publicación no existente."], 400);
        }

        $message = "Publicación eliminada con exito.";
        return response(compact("message"));
    }

    public function qualify_product(QualifyProductRequest $request)
    {
        $publication = $this->model::find($request->publication_id);

        if(!$publication)
            return response(["message" => "No existe publicacion con el publication_id otorgado."], 400);

        $user = User::find($request->user_id);

        if(!$user)
            return response(["message" => "No existe usuario con el user_id otorgado."], 400);

        $existing_qualification = PublicationQualification::where('publication_id', $request->publication_id)->where('user_id', $request->user_id)->count();
        
        if($existing_qualification > 0)
            return response(["message" => "Esta publicacion ya posee calificación de este usuario."], 400);

        $publication_qualification = PublicationQualification::create($request->all());
        $message = "Producto calificado exitosamente";
        
        $publication_qualification = PublicationQualification::get_all_publication_qualification($publication_qualification->id);
        
        return response(compact("message", "publication_qualification"));
    }

    public function get_my_publications()
    {
        $publications = $this->model::with($this->model::SHOW)->where('user_id', Auth::user()->id)->get();

        return response(compact("publications"));
    } 

    public function pause_publication(Request $request, $id_publication)
    {
        $publication = Publication::find($id_publication);
        
        if(!$publication)
            return response(["message" => "No existe publicación con el publication_id otorgado."], 400);

        if($publication->user_id != Auth::user()->id)
            return response(["message" => "No puede modificar esta publicación."], 400);

        try {
            DB::transaction(function () use($publication) {
                $publication->status_id = PublicationStatus::PAUSED; // $request->status_id;
                $publication->save();

                $publication_status_history = new PublicationStatusHistory();
                $publication_status_history->publication_id = $publication->id;
                $publication_status_history->status_id = PublicationStatus::PAUSED; // $request->status_id;
                $publication_status_history->save();
            });
        } catch (\Throwable $th) {
            Log::debug(print_r([$th->getMessage() . ", error al pausar publicación ID: $publication->id", $th->getLine()],  true));
        }

        $publication = $this->getAllPublication($publication->id);
        $message = "Publicación pausada con exito.";
        return response(compact("publication", "message"));
    }

    public function new_ask_answer_publication(Request $request)
    {
        if($request->ask){
            $request->validate([
                "publication_id" => ['required', 'integer', Rule::exists('publications', 'id')],
            ]);
            $ask = new PublicationQuestionAnswer();
            $ask->publication_id = $request->publication_id;
            $ask->user_id = Auth::user()->id;
            $ask->ask = $request->ask;
            $ask->ask_date = now()->format('Y-m-d H:i:s');
            $ask->save();

            $message = "Pregunta guardada exitosamente";
        }else{
            $request->validate([
                "ask_id" => ['required', 'integer', Rule::exists('publications_questions_answers', 'id')],
                'answer' => 'required',
            ]);
            $ask = PublicationQuestionAnswer::find($request->ask_id);
            if($ask->publication->user->id != Auth::user()->id)
                return response(["message" => "No es posible cargar la respuesta, usuario invalido."], 400);

            $ask->answer = $request->answer;
            $ask->answer_date = now()->format('Y-m-d H:i:s');
            $ask->save();

            $message = "Respuesta guardada exitosamente";
        }

        return response(compact("message", "ask"));
    }
}
