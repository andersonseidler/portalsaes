@extends('layouts.app')

@section('title', 'Usuários')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Usuários</li>
                </ol>
            </div>
            <h3 class="page-title">Usuários</h3>
        </div>
    </div>
</div>
<br>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <form action="{{ route('users.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" placeholder="Pesquisar" class="form-control">
                            <button type="submit" class="btn btn-primary">Pesquisar</button>    
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3 text-end">
                    <a href="{{ route('users.create') }}" class="btn btn-success ">Cadastrar</a>
                    <button class="btn btn-secondary" id="deleteAllSelectedRecord" disabled >Excluir</button> 
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-centered mb-0">
                    <thead>
                        <tr>
                            {{-- <th><input type="checkbox" name="" id="select_all_ids"></th> --}}
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Perfil</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr id="employee_ids{{ $user->id }}">
                            {{-- <td><input type="checkbox" name="ids" class="checkbox_ids" value="{{$user->id}}"></td> --}}
                            <td class="table-user">
                                @if($user->image)
                                    <img src="{{ url("storage/{$user->image}") }}" class="me-2 rounded-circle">
                                @else
                                <img src="{{ url("assets/img/icon_user.png") }}" class="me-2 rounded-circle">
                                @endif
                                {{ $user->name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telefone }}</td>
                            <td>{{ $user->perfil }}</td>
                            <td class="table-action">
                                <a href="{{ route('users.show', $user->id) }}" class="action-icon"> <i class="mdi mdi-eye"></i></a>
                                <a href="{{ route('users.edit', $user->id) }}" class="action-icon"> <i class="mdi mdi-pencil"></i></a>
                                <a href="{{ route('users.destroy', $user->id) }}" class="action-icon mdi mdi-delete" data-confirm-delete="true"></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            {{ $users->appends([
                'search' => request()->get('search', '')
            ])->links('components.pagination') }}
        </div>
    
        <script>
            $(function(e){
                $("#select_all_ids").click(function(){
                    $('.checkbox_ids').prop('checked',$(this).prop('checked'));
                    
                    // Verificar se o checkbox está marcado
                    if ($(this).prop('checked')) {
                    // Adicionar uma classe ao botão quando o checkbox estiver marcado
                        $('#deleteAllSelectedRecord').removeClass('btn btn-secondary');
                        $('#deleteAllSelectedRecord').addClass('btn btn-danger');
                        $('#deleteAllSelectedRecord').prop('disabled', false);
                    } else {
                    // Remover a classe do botão quando o checkbox estiver desmarcado
                        $('#deleteAllSelectedRecord').removeClass('btn btn-danger');
                        $('#deleteAllSelectedRecord').addClass('btn btn-secondary');
                        $('#deleteAllSelectedRecord').prop('disabled', true);
                        
                    }
                });
        
                $(".checkbox_ids").click(function(){
                    // Verificar se o checkbox está marcado
                    if ($('.checkbox_ids:checked').length > 0) {
                    // Adicionar uma classe ao botão quando o checkbox estiver marcado
                        $('#deleteAllSelectedRecord').removeClass('btn btn-secondary');
                        $('#deleteAllSelectedRecord').addClass('btn btn-danger');
                        $('#deleteAllSelectedRecord').prop('disabled', false);
                    } else {
                    // Remover a classe do botão quando o checkbox estiver desmarcado
                        $('#deleteAllSelectedRecord').removeClass('btn btn-danger');
                        $('#deleteAllSelectedRecord').addClass('btn btn-secondary');
                        $('#deleteAllSelectedRecord').prop('disabled', true);
                        
                    }
                });
        
                $('#deleteAllSelectedRecord').click(function(e){
                    e.preventDefault();
                    var all_ids = [];
                    $('input:checkbox[name=ids]:checked').each(function(){
                        all_ids.push($(this).val());
                    });
        
                    Swal.fire({
                        title: 'Excluír',
                        text: `Deseja excluir os usuários selecionados?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('users.delete') }}",
                                type: "DELETE",
                                data:{
                                    ids:all_ids,
                                    _token: '{{ csrf_token() }}'
                                },
                        success:function(response){
                            $.each(all_ids,function(key,val){
                                $('#employee_ids'+val).remove();
                                if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Successo',
                                    text: response.message
                                    });
                                } 
                                })
                            }
                        });
                        }
                    });
                });
            });
        </script>
@endsection