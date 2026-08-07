function mascaraTelefone(valor) {
  valor = valor.replace(/\D/g, '').substring(0, 11);

  if (valor.length > 10) {
    return valor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
  }

  if (valor.length > 6) {
    return valor.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
  }

  if (valor.length > 2) {
    return valor.replace(/(\d{2})(\d+)/, '($1) $2');
  }

  if (valor.length > 0) {
    return valor.replace(/(\d+)/, '($1');
  }

  return valor;
}

// Aplica em todos os campos com classe "telefone"
document.querySelectorAll('.telefone').forEach(campo => {    
  campo.addEventListener('input', e => {
    e.target.value = mascaraTelefone(e.target.value);
  });
});

// modal de cadastro fecha
$('#modalCadastro').on('hidden.bs.modal', function() {
    $('#cadastrar').html('Cadastrar');
});

$('#btnCadastrar').click(function() {
    limparForm();
});

$('#btnRemoverBusca').click(function() {
    window.location.reload();
});

// modal de edição aberta
$('#modalEdicao').on('show.bs.modal', function() {    
    const botao = event.target;

    $('#editar').data('id', botao.dataset.id);
    $('#nomeEdicao').val(botao.dataset.nome);
    $('#telefoneEdicao').val(botao.dataset.telefone);
    $('#estadoEdicao').val(botao.dataset.estado);
    buscarCidadesPorUf(botao.dataset.uf, 'Edicao', true, botao.dataset.cidade);
});

//editar um contato
$('#editar').click(function() {
    var nome     = ($('#nomeEdicao').val()).trim();
    var telefone = ($('#telefoneEdicao').val()).trim();
    var estado   = ($('#estadoEdicao').val());
    var cidade   = ($('#cidadeEdicao').val());
    var id       = $('#editar').data('id');    

    // validações do form
    if(nome == '' || telefone == '' || estado == null || cidade == null) return;

    jQuery.ajax({
        type     : "POST",
        data     : {
            id: id,
            nome: nome,
            telefone: telefone,
            idCidade: cidade,
            idEstado: estado
        },
        url      : '/ajax/editar-contato.php',
        dataType : "json",
        success  : function(data){
            $('#modalEdicao').modal('hide');

            var titulo   = 'Erro na Edição!';
            var mensagem = data.mensagem;

            if(data.sucesso){
                titulo   = 'Editado com sucesso!';
                mensagem = 'Nome: '+nome+'<br>Telefone: <strong>'+telefone+'</strong>';

                $('tr[data-id="'+id+'"]').replaceWith(data.layout);
                limparForm();
            }

            $('#modalEdicao').modal('hide');

            $('#modalMensagem .modal-title').html(titulo);
            $('#modalMensagem .modal-body').html(mensagem);

            $('#modalMensagem').modal('show');
        },
        error: function(data){
            $('#editar').html('Editar');
            alert('Um problema impediu a edição.');
        },
        beforeSend:function(data){
            $('#loader').css('display', 'flex');
            $('#editar').html('Editando...');
            $('#modalMensagem .modal-footer').hide();
        },
        complete:function(data){
            $('#editar').html('Editar');
            $('#loader').hide();
        }
    });
});


//cadastrar um contato
$('#cadastrar').click(function() {
    var nome     = ($('#nomeCadastro').val()).trim();
    var telefone = ($('#telefoneCadastro').val()).trim();
    var estado   = ($('#estadoCadastro').val());
    var cidade   = ($('#cidadeCadastro').val());

    // validações do form
    if(nome == '' || telefone == '' || estado == null || cidade == null) return;

    jQuery.ajax({
        type     : "POST",
        data     : {
            nome: nome,
            telefone: telefone,
            idCidade: cidade,
            idEstado: estado
        },
        url      : '/ajax/cadastrar-contato.php',
        dataType : "json",
        success  : function(data){
            $('#modalCadastro').modal('hide');

            var titulo   = 'Erro no Cadastro!';
            var mensagem = data.mensagem;

            if(data.sucesso){
                titulo   = 'Cadastrado com sucesso!';
                mensagem = 'Nome: '+nome+'<br>Telefone: <strong>'+telefone+'</strong>';

                inserirContatoTabela(data.layout);
                limparForm();
            }

            $('#modalCadastro').modal('hide');

            $('#modalMensagem .modal-title').html(titulo);
            $('#modalMensagem .modal-body').html(mensagem);
            $('#modalMensagem .modal-footer').show();

            $('#modalMensagem').modal('show');
        },
        error: function(data){
            $('#cadastrar').html('Cadastrar');
            alert('Um problema impediu o cadastro.');
        },
        beforeSend:function(data){
            $('#loader').css('display', 'flex');
            $('#cadastrar').html('Cadastrando...');
        },
        complete:function(data){
            $('#cadastrar').html('Cadastrar');
            $('#loader').hide();
        }
    });
});


// limpa o form
function limparForm(){
    $('#nomeCadastro').val('');
    $('#telefoneCadastro').val('');
    $('#estadoCadastro option:eq(0)').prop('selected', true);
    $('#cidadeCadastro').empty();

    $('#nomeEdicao').val('');
    $('#telefoneEdicao').val('');
    $('#estadoEdicao option:eq(0)').prop('selected', true);
    $('#cidadeEdicao').empty();
}

//busca um contato pelo nome
$('#btnBusca').click(function() {
    var busca = $('#busca').val();
    if(busca == '') return;
    
    buscarContato(busca);
});

// enter na busca
$(document).on("keydown", "#busca", function(){
    if(event.key === "Enter" && event.target.value != '') {
        event.preventDefault(); 
        buscarContato(event.target.value);
    }
});

function buscarContato(busca){
    jQuery.ajax({
        type     : "POST",
        data     : {
            busca: busca
        },
        url      : '/ajax/buscar-contato.php',
        dataType : "json",
        success  : function(data){
            if(data.sucesso){
                $('.itemLista').remove();
                $('.ContainerTable tbody').append(data.layout);
            }
        },
        error: function(data){
            alert('Um problema impediu a busca.');
        },
        beforeSend:function(data){
            $('#loader').css('display', 'flex');
        },
        complete:function(data){
            $('#loader').hide();
            $('#btnRemoverBusca').css('display', 'flex');
        }
    });
}

//deleta um contato
$(document).on("click", ".delete i", function(){
    var idContato = this.dataset.id;
    var row       = $(this).closest('tr');

    jQuery.ajax({
        type     : "POST",
        data     : {
            idContato: idContato
        },
        url      : '/ajax/deletar-contato.php',
        dataType : "json",
        success  : function(data){
            var mensagem = data.mensagem;

            if(data.sucesso){
                mensagem = 'Contato excluído com sucesso!';
                row.remove();
                if(($('.table tbody tr').length == 1)) $('.emptyContatos').show();
            }

            showAlert(mensagem, data.sucesso);
        },
        error: function(data){
            $('#loader').hide();
            alert('Um problema impediu a exclusão.');
        },
        beforeSend:function(data){
            $('#loader').css('display', 'flex');
        },
        complete:function(data){
            $('#loader').hide();
        }
    });
});

// insere a linha com o contato cadastrado na tabela
function inserirContatoTabela(layout) {
    $('.emptyContatos').hide();
    $(".table tbody").prepend(layout);
}

// mostra um alerta na tela
function showAlert(mensagem, sucesso = true) {
    var alert = document.createElement('div');

    alert.textContent = mensagem;
    alert.className   = 'alert alert-' + (sucesso ? 'success' : 'danger');
    
    $('.containerAlert').append(alert);
    $(alert).fadeIn();

    setTimeout(function () {
        $(alert).fadeOut();
    }, 2000);
}

$('.formEstado').on('change', function() {
    var selectedUF = $(this).find('option:selected').text();
    
    if(selectedUF == '' || selectedUF == undefined || selectedUF == null) return;
    
    tipoForm = this.id == 'estadoCadastro' ? 'Cadastro' : 'Edicao';    

    buscarCidadesPorUf(selectedUF, tipoForm);
});


// carrega as cidades pela UF
function buscarCidadesPorUf(uf, tipoForm, setarCidade = false, idCidade = null){
    jQuery.ajax({
        type     : "POST",
        data     : {
            uf: uf
        },
        url      : '/ajax/buscar-cidade-por-uf.php',
        dataType : "json",
        success  : function(data){            
            if(data.sucesso){                                
                $('#cidade'+tipoForm).prop('disabled', false);
                $('#cidade'+tipoForm).append(data.data);
                if(setarCidade) $('#cidade'+tipoForm).val(idCidade);
            }else{
                alert(data.mensagem);
            }
        },
        error: function(data){
            alert('Um problema impediu a busca.');
        },
        beforeSend:function(data){            
            $('#loader').css('display', 'flex');
            $('#cidade'+tipoForm).empty();
        },
        complete:function(data){
            $('#loader').hide();
        }
    });
}