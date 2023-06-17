<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SubCatDoc;

class SubCatController extends Controller
{   

    protected $model;

    public function __construct(SubCatDoc $subcat)
    {
        $this->model = $subcat;
    }

    public function index(Request $request){
        
        $subcat = SubCatDoc::get();
        //dd($pags);
        return view('category.index', compact('cat'));
    }
    
}
