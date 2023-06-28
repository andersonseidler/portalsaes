<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateUserFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function index(Request $request){
        
        $search = $request->search;

        $users = $this->model->getUsers(search: $request->search ?? '');

        

        return view('users.index', compact('users'));
    }
    //metodo exibir o usuario
    public function show($id){
        if(!$user = $this->model->find($id)){
            return redirect()->route('users.index');
        }

        $title = 'Excluir!';
        $text = "Deseja excluir esse usuário?";
        confirmDelete($title, $text);
        
        return view('users.show', compact('user'));
    }
    //metodo para direcionar a pagina para o cadastro
    public function create(){
        return view('users.create');
    }
    //metodo para cadastrar o usuario no banco
    public function store(Request $request){
        $data = $request->all();
        //dd($data);
        $data['password'] = bcrypt($request->password);
        if(!$request->name){
            alert()->error('Preencha o nome!');
        }

        if ($request->image) {
            $extension = $request->image->getClientOriginalExtension();
            $data['image'] = $request->image->storeAs("users/{$request->name}", $request->name . ".{$extension}");
        }
        
        if($this->model->create($data)){
            alert()->success('Usuário cadastrado com sucesso!');

            return redirect()->route('users.index');
        }

        
    }
    //direcionar para a pagina EDITAR usuario. se nao existe o usuario retorna para index
    public function edit($id){
        //dd($id);
        if(!$user = $this->model->find($id)){
            return redirect()->route('users.index');
        }
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id){
        
        if($request->password){
            $data['password'] = bcrypt($request->password);
            $data['password_confirm'] = bcrypt($request->password);
        }else{
            $data = $request->except('password','password_confirm');
        }
        
        if(!$user = $this->model->find($id)){
            return redirect()->route('users.index');
        }

        if($request->image){
            $data['image'] = $request->image->store('users');
        }

        if($request->password != $request->password_confirm){
            alert()->error('Senhas não coincidem!');
            return redirect()->route('users.edit', $id);
        }

        $user->update($data);
        alert()->success('Usuário editado com sucesso!');

        return redirect()->route('users.index');
    }

    /* public function destroy($id){
        if (Auth::user()->id === (int) $id) {
            alert()->error('Você não pode se excluir!');
            return redirect()->route('users.index');
        }

        if(!$user = $this->model->find($id)){
            return redirect()->route('users.index');
        }
        
        if($user->delete()){
            alert()->success('Usuário excluído com sucesso!');
            return redirect()->route('users.index'); 
        }
    } */

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['message' => 'Usuário excluído com sucesso!']);
        }

        return response()->json(['message' => 'Usuário não encontrado.']);
    }
    

    public function deleteAll(Request $request){
        $ids = $request->ids;
        //dd($ids);
        User::whereIn('id', $ids)->delete();
        //alert()->success('Usuários excluídos com sucesso!');
        return response()->json([
            'success' => true,
            'message' => 'Usuários excluídos com sucesso!'
      ]);
        
    }
}
