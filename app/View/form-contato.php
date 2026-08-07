<div class="modal-body">
    <div class="container-form-item">
        <label for="nome{{tipoAcao}}">Nome</label>
        <input type="text" id="nome{{tipoAcao}}" maxlength="255" class="form-control" placeholder="Digite o Nome"/>
    </div>
    <div class="container-form-item">
        <label for="telefone{{tipoAcao}}">Telefone</label>
        <input type="tel" maxlength="15" id="telefone{{tipoAcao}}" maxlength="255" class="form-control telefone" placeholder="Digite o Telefone"/>
    </div>
    <div class="container-form-item">
        <label for="estado{{tipoAcao}}">Estado</label>
        <select id="estado{{tipoAcao}}" class="form-control formEstado">
        <option value="0" disabled selected>Selecione o Estado</option>
        {{optionEstados}}
        </select>
    </div>
    <div class="container-form-item">
        <label for="cidade{{tipoAcao}}">Cidade</label>
        <select id="cidade{{tipoAcao}}" class="form-control" disabled>
        <option value="default" disabled>Selecione a Cidade</option>
        </select>
    </div>
</div>