@extends('layouts.app')

@section('title', 'Categorias')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Categorias</li>
                    </ol>
                </div>
                <h3 class="page-title">Categorias</h3>
            </div>
        </div>
    </div>
    <br>

    <div class="card">
        <div class="card-body">
            <div class="row">
                @if ($errors->any())
                    <ul class="errors">
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">{{ $error }}</div>
                        @endforeach
                    </ul>
                @endif
                <div class="col-sm-12">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Categorias</h4>
                        <div class="dropdown">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#standard-modal">Cadastrar</button>
                            <button class="btn btn-secondary btn-sm" id="deleteAllSelectedRecord" disabled><i
                                    class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    <table class="table table-centered table-nowrap table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Cadastrado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($cats as $cat)
                                <tr>
                                    <td>{{ $cat->nome_doc }}</td>
                                    <td>{{ Carbon\Carbon::parse($cat->create_at)->format('d/m') }}</td>
                                    <td class="table-action">
                                        <a href="{{ route('category.edit', $cat->id) }}" class="action-icon"> <i class="mdi mdi-pencil"></i></a>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#standard-modal-edit" data-nome-doc="{{ $cat->nome_doc }}">Cadastrar</button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>

        <!-- Standard modal -->
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Nova categoria</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        @include('category.create')
                    </div>
                </div>
            </div>
        </div>
        <!-- /.Standard modal -->

        <!-- Standard modal -->
        <div id="standard-modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Nova categoria</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('category.update', $cat->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            {{-- @include('users._partials.form-edit-user') --}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label">Nome da categoria</label>
                                            <input type="text" name="nome_doc" value="{{ $cat->nome_doc ?? old('nome_doc') }}"  class="form-control" id="nome-doc-input">
                                    </div>
                                </div>
                            </div>
                            
                            <br>
                            <div class="row">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.Standard modal -->
        <script>
            $('#standard-modal-edit').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var nomeDoc = button.data('nome_doc');
                console.log(nomeDoc);
                var nomeDocInput = $(this).find('#nome-doc-input');
                nomeDocInput.val(nomeDoc);
            });
        </script>
@endsection
