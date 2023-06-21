<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\CatDoc;
use App\Models\User;
class DocumentosController extends Controller
{
    protected $model;

    public function __construct(Documento $docs)
    {
        $this->model = $docs;
    }

    public function index(Request $request){

        $categorias = CatDoc::all();
        $docs = $this->model->getDocumentos(colaborador: $request->colaborador, documento: $request->documento ?? '');
        $users = User::all();
        //dd($docs);
        
        //dd($users);
        return view('documentos.index', compact(['users', 'docs','categorias']));
    }

    public function create(){
        $users = User::all();
        return view('documentos.create', compact('users'));
    }

    public function store(Request $request){
        $data = $request->all();

        if($request->colaborador == ""){
            alert()->error('Selecione o colaborador!');
            return redirect()->route('documentos.index');
        }

        alert()->success('Emprestimo cadastrado com sucesso!');

        return redirect()->route('documentos.index');
        
    }
}
