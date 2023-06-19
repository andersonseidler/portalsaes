<div class="row">
    <div class="col-lg-12">
        <div class="mb-3">
            <label">Selecione a categoria</label>
                
                <select class="form-select" name="categoria_id">
                    @foreach ($cats as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nome_doc }}</option>
                    @endforeach
                  </select>
                
        </div>
        <div class="mb-3">
            <label">Nome da subcategoria</label>
                <input type="text" name="nome_subcat" class="form-control">
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


