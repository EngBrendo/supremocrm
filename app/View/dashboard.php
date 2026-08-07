<div class="containerTopo">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastro">
    Novo cadastro
  </button>
  <div class="containerBusca">
    <input type="text" placeholder="Busque um contato" id="busca" class="form-control"/>
    <button id="btnBusca" class="btn btn-primary" title="pesquisar" disabled>
      <i class="material-icons">search</i>
      <div class="spinner-border"></div>
    </button>
  </div>
</div>

<div class="ContainerTable">
  <table class="table">
    <thead class="table-light">
      <tr>
        <th scope="col">Nome</th>
        <th scope="col">Telefone</th>
        <th scope="col">Cidade</th>
        <th scope="col">Estado</th>
        <th scope="col">Cadastro</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr class="emptyContatos {{tableHidden}}"><td colspan="5">Nenhum contato cadastrado.</td></tr>
      {{contatos}}
    </tbody>
  </table>
</div>

<!-- Modal Cadastro-->
<div class="modal fade" id="modalCadastro">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Novo Cadastro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="container-form-item">
        <label for="nomeCadastro">Nome</label>
        <input type="text" id="nomeCadastro" maxlength="255" class="form-control" placeholder="Digite o Nome"/>
      </div>
      <div class="container-form-item">
        <label for="telefoneCadastro">Telefone</label>
        <input type="tel" maxlength="15" id="telefoneCadastro" maxlength="255" class="form-control telefone" placeholder="Digite o Telefone"/>
      </div>
      <div class="container-form-item">
        <label for="estadoCadastro">Estado</label>
        <select id="estadoCadastro" class="form-control">
          <option value="0" disabled selected>Selecione o Estado</option>
          {{optionEstados}}
        </select>
      </div>
      <div class="container-form-item">
        <label for="cidadeCadastro">Cidade</label>
        <select id="cidadeCadastro" class="form-control" disabled>
          <option value="default" disabled>Selecione a Cidade</option>
        </select>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="cadastrar">Cadastrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Mensagem-->
<div class="modal fade" id="modalMensagem">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastro">Voltar ao cadastro</button>
      </div>
    </div>
  </div>
</div>
<div