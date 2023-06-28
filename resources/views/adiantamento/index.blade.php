@extends('layouts.app')

@section('title', 'Adiantamento')

@section('content')


    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Adiantamento</li>
                    </ol>
                </div>
                <h3 class="page-title">Adiantamento</h3>
            </div>
        </div>
    </div>
    <br>
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('adiantamento.index') }}" method="GET">
                        <div class="filter-select-container">
                            <select class="filter-select select-arrow-animation" name="colaborador" id="colaborador-select" data-toggle="select2">
                                <option value="" selected>Colaborador</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <select class="filter-select" id="mes-select" name="mes" data-toggle="select2">
                                <option value="" selected>Mês referente</option>
                                <option value="Janeiro">Janeiro</option>
                                <option value="Fevereiro">Fevereiro</option>
                                <option value="Março">Março</option>
                                <option value="Abril">Abril</option>
                                <option value="Maio">Maio</option>
                                <option value="Junho">Junho</option>
                                <option value="Julho">Julho</option>
                                <option value="Agosto">Agosto</option>
                                <option value="Setembro">Setembro</option>
                                <option value="Outubro">Outubro</option>
                                <option value="Novembro">Novembro</option>
                                <option value="Dezembro">Dezembro</option>
                            </select>
                            <select class="filter-select" id="status-select" name="status" data-toggle="select2">
                                <option value="" selected>Status</option>
                                <option value="A confirmar">A confirmar</option>
                                <option value="Recebido">Recebido</option>
                                <option value="Arquivado">Arquivado</option>
                            </select>
                        </div>
                </div>
    
                <div class="col-md-6 text-end">
                    <button type="submit" class="filter-btn btn-light">Filtrar</button>
                </div>
                </form>
            </div>
        </div>
    </div>


                                                
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Adiantamentos lançados</h4>
                        <div class="dropdown">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#standard-modal">Cadastrar</button>
                            <button class="btn btn-secondary btn-sm" id="deleteAllSelectedRecord" disabled><i
                                    class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    @if ($pags->total() != 0)
                        
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead class="">
                                <tr>
                                    <!--<th><input type="checkbox" name="" id="select_all_ids"></th>-->
                                    <th><input type="checkbox" name="" id="select_all_ids"></th>
                                    <th>Colaborador</th>
                                    <th>Mês referente</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pags as $pag)
                                    <tr id="employee_ids{{ $pag->id }}">
                                        <td><input type="checkbox" name="ids" class="checkbox_ids"
                                                value="{{ $pag->id }}"></td>
                                        <td class="table-user">
                                            <img src="{{ url("storage/{$pag->foto}") }}" alt="{{ $pag->name }}"
                                                class="me-2 rounded-circle">
                                            {{ $pag->colaborador }}
                                        </td>
                                        <td>{{ $pag->mes }}</td>
                                        <td>{{ Carbon\Carbon::parse($pag->data)->format('d/m/Y') }}</td>
                                        <td><span class="{{ $pag->class_status }}">{{ $pag->status }}</span></td>
                                        <td class="table-action">
                                            <a href="storage/{{ $pag->arquivo }}" class="action-icon" download> <i
                                                    class="mdi mdi-download"></i></a>
                                            <a href="storage/{{ $pag->arquivo }}" class="action-icon" target="blank"> <i
                                                    class="mdi mdi-printer"></i></a>
                                            <a href="{{ route('adiantamentos.destroy', $pag->id) }}"
                                                class="action-icon mdi mdi-delete" data-confirm-delete="true"></a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif($pags->total() == 0)
                        <div class="alert alert-warning" role="alert">
                            NENHUM RESULTADO ENCONTRADO!
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            {{ $pags->appends([
                    'colaborador' => request()->get('colaborador', ''),
                ])->links('components.pagination') }}
        </div>
    </div>

    <form action="{{ route('lerConteudoPDF') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" class="form-control" name="path">
        <button type="submit">Enviar</button>
    </form>

    <!-- Standard modal -->
    <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Novo pagamento</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    @include('adiantamento.create')
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <script>
        $(function(e) {
            $("#select_all_ids").click(function() {
                $('.checkbox_ids').prop('checked', $(this).prop('checked'));

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

            $(".checkbox_ids").click(function() {
                // Verificar se o checkbox está marcado
                if ($('.checkbox_ids:checked').length > 1) {
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

            $('#deleteAllSelectedRecord').click(function(e) {
                e.preventDefault();
                var all_ids = [];
                $('input:checkbox[name=ids]:checked').each(function() {
                    all_ids.push($(this).val());
                });

                Swal.fire({
                    title: 'Excluír',
                    text: `Deseja excluir os adiantamentos selecionados?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('adiantamento.delete') }}",
                            type: "DELETE",
                            data: {
                                ids: all_ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $.each(all_ids, function(key, val) {
                                    $('#employee_ids' + val).remove();
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
