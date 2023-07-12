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
                    {{-- <button class="btn btn-secondary" id="deleteAllSelectedRecord" disabled >Excluir</button>  --}}
                </div>
            </div>
        </div>
        <br>
        @include('users._partials.list-usuarios')
    


<script>
    const deleteLinks = document.querySelectorAll('.delete-user');
    
    const handleDeleteUser = (event) => {
      event.preventDefault();
    
      const deleteUrl = event.target.href;
    
      Swal.fire({
        title: 'Excluír',
        text: 'Tem certeza que deseja excluir esse usuário?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          })
            .then(response => response.json())
            .then(data => {
              if (data.message) {
                Swal.fire('Sucesso', data.message, 'success');
    
                const deletedRow = event.target.closest('tr');
                deletedRow.remove();
    
                // Atualizar a lista de registros via AJAX
                fetch('{{ route('users.index') }}')
                  .then(response => response.text())
                  .then(html => {
                    const tableContainer = document.getElementById('table-container');
                    tableContainer.innerHTML = html;
                  })
                  .catch(error => {
                    console.error('Ocorreu um erro:', error);
                  });
              }
            })
            .catch(error => {
              console.error('Ocorreu um erro:', error);
            });
        }
      });
    };
    
    deleteLinks.forEach(link => {
      link.addEventListener('click', handleDeleteUser);
    });
    </script>
@endsection