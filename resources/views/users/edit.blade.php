@extends('layouts.app')

@section('title', 'Editar usuário')

@section('content')
<h1 class="text-2x1 font-semibold leading-tigh py-2">Editar usuário</h1>

@include('includes/validations-form')

<form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('users._partials.form-edit-user')
</form>
@endsection