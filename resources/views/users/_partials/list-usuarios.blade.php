<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-centered mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Perfil</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($users as $user)
                <tr data-user-id="{{ $user->id }}">
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
                        <a href="{{ route('users.destroy', $user->id) }}" class="action-icon mdi mdi-delete delete-user"></a>

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