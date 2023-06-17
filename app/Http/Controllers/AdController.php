<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Mail\SendEmail;
use App\Models\Adiantamento;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AdController extends Controller
{   

    protected $model;

    public function __construct(Adiantamento $pag)
    {
        $this->model = $pag;
    }

    public function index(Request $request){
        
        $search = $request->search;
        //dd($request->colaborador);
        $pags = $this->model->getPagamentos(colaborador: $request->colaborador, mes: $request->mes, status: $request->status ?? '');
        //dd($pags);
        $users = User::all();
        //dd($pags);
        $title = 'Excluir!';
        $text = "Deseja excluir esse adiantamento?";
        confirmDelete($title, $text);
        return view('adiantamento.index', compact('pags','users'));
    }

    public function create(){
        $users = User::all();
        return view('adiantamento.create', compact('users','users'));
    }
    
    public function store(Request $request){
        $data = $request->all();
        //dd($data);
        $extension = $request->arquivo->getClientOriginalExtension();

        if($extension == ""){
            alert()->error('Carregue um arquivo em PDF!');
            return redirect()->route('adiantamento.index');
        }
        if(!$request->colaborador){
            alert()->error('Selecione o colaborador!');
            return redirect()->route('adiantamento.index');
        }elseif(!$request->email){
            alert()->error('Preencha o campo e-mail');
            return redirect()->route('adiantamento.index');  
        }elseif(!$request->arquivo){
            alert()->error('Carregue um arquivo em PDF!');
            return redirect()->route('adiantamento.index');    
        }elseif($extension != "pdf"){
            alert()->error('Carregue um arquivo PDF!');
            return redirect()->route('adiantamento.index');    
        }else{
            
            $data['arquivo'] = $request->arquivo->storeAs('contracheques', 'adiantamento-' . $request->colaborador . ".{$extension}");
        }
        
        //Mail::to( config('mail.from.address'))->send(new SendEmail($data));

        if($this->model->create($data)){
            alert()->success('Contracheque cadastrado com sucesso!');
            return redirect()->route('adiantamento.index');
        }
    }

    public function destroy($id){
        if(!$pag = $this->model->find($id)){
            alert()->error('Erro ao excluír o adiantamento!');
        }
        
        if($pag->delete()){
            alert()->success('Adiantamento excluído com sucesso!');
        }

        return redirect()->route('adiantamento.index');
    }

    public function deleteAll(Request $request){
        $ids = $request->ids;
        Adiantamento::whereIn('id', $ids)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Adiantamentos excluídos com sucesso!'
      ]);
        
    }
}
