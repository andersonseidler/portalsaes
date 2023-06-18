@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')

@include('includes/validations-form')

<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="form-edit">
    @csrf
    @include('users._partials.form-cad-user')
</form>
@endsection
