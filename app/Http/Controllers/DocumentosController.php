<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\User;
class DocumentosController extends Controller
{
    public function index(){

        $docs = Documento::all();
        $users = User::all();
        //dd($emprestimos);
        
        //dd($users);
        return view('documentos.index', compact(['users', 'docs']));
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
