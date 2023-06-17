<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PgController extends Controller
{   

    protected $model;

    public function __construct(Pagamento $pag)
    {
        $this->model = $pag;
    }

    public function index(Request $request){
        
        $search = $request->search;

        $pags = $this->model->getPagamentos(colaborador: $request->colaborador, mes: $request->mes, status: $request->status ?? '');
        $users = User::all();
        //dd($pags);
        $title = 'Excluir!';
        $text = "Deseja excluir esse pagamento?";
        confirmDelete($title, $text);

        return view('pagamento.index', compact('pags','users'));
    }

    public function create(){
        $users = User::all();
        return view('pagamento.create', compact('users','users'));
    }
    
    public function store(Request $request){
        $data = $request->all();
        //dd($request->file());

        if($request->arquivo){
            $extension = $request->arquivo->getClientOriginalExtension();
            $data['arquivo'] = $request->arquivo->storeAs('contracheques', 'pagamento-' . $request->colaborador . ".{$extension}");
            
        }
        
        //Mail::to( config('mail.from.address'))->send(new SendEmail($data));

        if($this->model->create($data)){
            alert()->success('Contracheque cadastrado com sucesso!');
            return redirect()->route('pagamento.index');
        }
    }

    public function destroy($id){
        if(!$pag = $this->model->find($id)){
            alert()->error('Erro ao excluír o pagamento!');
        }
        
        if($pag->delete()){
            alert()->success('Pagamento excluído com sucesso!');
        }

        return redirect()->route('pagamento.index');
    }

    public function deleteAll(Request $request){
        $ids = $request->ids;
        Pagamento::whereIn('id', $ids)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Salários excluídos com sucesso!'
      ]);
        
    }
}
