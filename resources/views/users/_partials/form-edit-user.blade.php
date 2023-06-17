<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Informações pessoais</h4>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label">Nome</label>
                            <input type="text" name="name" value="{{ $user->name ?? old('name') }}" class="form-control" placeholder=" " required />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label">E-mail</label>
                            <input type="text" name="email" value="{{ $user->email ?? old('email') }}" class="form-control" placeholder=" " required />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label">Telefone</label>
                            <input type="text" name="telefone" value="{{ $user->telefone ?? old('telefone') }}" class="form-control" placeholder=" " required />
                        </div>
                    </div>


                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label">Perfil</label>
                            <select class="form-select" name="perfil" >
                                @if($user->perfil == "Administrador")
                                <option value="Administrador">Administrador</option>
                                <option value="Usuário">Usuário</option>
                                @else
                                <option value="Usuário">Usuário</option>
                                <option value="Administrador">Administrador</option>
                                @endif
                              </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label">Cargo</label>
                            <input type="text" name="cargo" value="{{ $user->cargo ?? old('cargo') }}" class="form-control" placeholder=" " required />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label">Data de nascimento</label>
                            <input type="date" name="nascimento" value="{{ $user->nascimento ?? old('nascimento') }}" class="form-control" placeholder=" " required />
                        </div>
                    </div>
                </div>


                <div class="row">
                    <h4 class="header-title">Endereço</h4>
                    <div class="col-lg-1">
                        <div class="mb-3">
                            <label">CEP</label>
                            <input type="text" name="cep" onblur="pesquisacep(this.value);" onkeyup="handleZipCode(event)" value="{{ $user->cep ?? old('cep') }}" id="cep" class="form-control" placeholder=" " required />
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label">Logradouro</label>
                            <input type="text" name="logradouro" value="{{ $user->logradouro ?? old('logradouro') }}" id="rua" class="form-control" placeholder=" " required />
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label">Bairro</label>
                            <input type="text" name="bairro" value="{{ $user->bairro ?? old('bairro') }}" id="bairro" class="form-control" placeholder=" " required />
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label">Cidade</label>
                            <input type="text" name="cidade" value="{{ $user->cidade ?? old('cidade') }}" id="cidade" class="form-control" placeholder=" " required />
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label">Estado</label>
                            <input type="text" name="estado" value="{{ $user->estado ?? old('estado') }}" id="uf" class="form-control" placeholder=" " required />
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label">Complemento</label>
                            <input type="text" name="complemento" value="{{ $user->complemento ?? old('complemento') }}" id="complemento" class="form-control"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="header-title">Acesso</h4>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label">Senha</label>
                            <input type="text" name="senha" class="form-control" placeholder=" " />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label">Confirmar senha</label>
                            <input type="text" name="confirmar_senha" class="form-control" placeholder=" " />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="header-title">Foto de perfil</h4>
                    <div class="col-lg">
                    <input class="form-control" type="file" name="image">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-6">
                    </div>
                    <div class="col-lg-6 text-end">
                        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-secondary btn-sm">Voltar</button></a>
                    <button type="button" onclick="form.reset();" class="btn btn-warning btn-sm">Limpar</button>
                    <button type="submit" class="btn btn-success btn-sm">Cadastrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
