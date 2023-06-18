@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')

@include('includes/validations-form')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></li>
                    <li class="breadcrumb-item active">Cadastrar</li>
                </ol>
            </div>
            <h3 class="page-title">Novo usuário</h3>
        </div>
    </div>
</div>
<br>
<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="form-edit">
    @csrf
    @include('users._partials.form-cad-user')
</form>
@endsection
