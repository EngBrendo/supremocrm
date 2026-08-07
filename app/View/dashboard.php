<div class="containerTopo">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastro" id="btnCadastrar">
    Novo contato
  </button>
  <div class="containerBusca">
    <input type="text" placeholder="Busque um contato" id="busca" class="form-control"/>
    <button id="btnRemoverBusca" class="btn-remove btn" title="remover busca">
      <i class="material-icons">close</i>
    </button>
    <button id="btnBusca" class="btn btn-primary" title="pesquisar">
      <i class="material-icons">search</i>
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
        <th></th>
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
        <h5 class="modal-title">Novo Contato</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {{formContatoCadastro}}
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="cadastrar">Cadastrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edição-->
<div class="modal fade" id="modalEdicao">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Contato</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {{formContatoEdicao}}
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="editar">Editar</button>
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