@extends('layouts.app')

@section('title', 'Perfil')

@section('content')

<div class="row">
    <div class="col-xl-4 col-lg-5">
        <div class="card text-center">
            <div class="card-body">
                <img src="storage/{{ auth()->user()->image }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                <h4 class="mb-0 mt-2">{{ auth()->user()->name }}</h4>
                <p class="text-muted font-14">{{ auth()->user()->cargo }}</p>
              
                <div class="text-start mt-3">
                    <p class="text-muted mb-2 font-13"><strong>Nome: :</strong> <span class="ms-2">{{ auth()->user()->name }}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Telefone:</strong><span class="ms-2">{{ auth()->user()->telefone }}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Email:</strong> <span class="ms-2 ">{{ auth()->user()->email }}</span></p>

                    <p class="text-muted mb-1 font-13"><strong>Endereço:</strong> <span class="ms-2">{{ auth()->user()->logradouro }} - {{ auth()->user()->bairro }}/{{ auth()->user()->cidade }}</span></p>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->

    </div> <!-- end col-->

    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('perfil.update', auth()->user()->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                <h5 class="mb-3 text-uppercase p-2"><i class="mdi mdi-account-circle me-1"></i> Informações pessoais</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="useremail" class="form-label">Senha</label>
                                
                                <input type="text" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userpassword" class="form-label">Confirmar senha</label>
                                <input type="password" class="form-control" name="confirmar_senha">
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-sm">Salvar</button>
                    </div>
                </form>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>

@endsection