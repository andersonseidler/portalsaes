<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCatDoc;
use App\Models\CatDoc;
class SubCatController extends Controller
{
    protected $model;

    public function __construct(SubCatDoc $cats)
    {
        $this->model = $cats;
    }

    public function index(Request $request){

        $title = 'Excluir!';
        $text = "Deseja excluir essa subcategoria?";
        confirmDelete($title, $text);

        $subs = SubCatDoc::all();
        $cats = CatDoc::all();
        //dd($cats);
        return view('subcategory.index', compact('subs','cats'));
    }

    public function create(){
        return view('subcategory.create');
    }

    public function store(Request $request){
        $data = $request->all();
        //dd($data);
        if($this->model->create($data)){
            alert()->success('Subcategoria cadastrada com sucesso!');

            return redirect()->route('subcategory.index');
        }   
    }

    public function edit($id){
        if(!$cats = $this->model->find($id)){
            return redirect()->route('subcategory.index');
        }
        return view('subcategory.edit', compact('cats'));
    }

    public function update(Request $request, $id){
        //dd($request);
        //dd($data);
        $data = $request->all();
        if(!$cats = $this->model->find($id))
            return redirect()->route('subcategory.index');

        if($cats->update($data)){
            alert()->success('Subcategoria editada com sucesso!');
            return redirect()->route('subcategory.index');
        }
    }

    public function destroy($id){
        if(!$doc = $this->model->find($id)){
            alert()->error('Erro ao excluír o pagamento!');
        }
        
        if($doc->delete()){
            alert()->success('Subcategoria excluída com sucesso!');
        }

        return redirect()->route('subcategory.index');
    }
}
