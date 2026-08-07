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


//cadastrar um cidadão
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
            $('#cadastrar').html('Cadastrando...');
        },
        complete:function(data){
            $('#cadastrar').html('Cadastrar');
        }
    });
});

//busca um cidadão pelo nis
$('#btnBusca').click(function() {
    var nis = $('#nisBusca').val();
    if(nis.length != 11) return;
    
    jQuery.ajax({
        type     : "POST",
        data     : {
            nis: nis
        },
        url      : '/ajax/buscar-contato.php',
        dataType : "json",
        success  : function(data){
            var mensagem = data.sucesso ? 'Nome: '+data.nome+'<br>NIS: <strong>'+nis+'</strong>' : data.mensagem;

            $('#modalMensagem .modal-title').html('Busca por NIS');
            $('#modalMensagem .modal-body').html(mensagem);
            $('#modalMensagem .modal-footer').hide();

            $('#modalMensagem').modal('show');
        },
        error: function(data){
            $('#btnBusca').prop("disabled", false);
            alert('Um problema impediu a busca.');
        },
        beforeSend:function(data){
            $('#btnBusca').prop("disabled", true);
            $('#btnBusca i').hide();
            $('#btnBusca .spinner-border').show();
        },
        complete:function(data){
            $('#btnBusca').prop("disabled", false);
            $('#btnBusca i').show();
            $('#btnBusca .spinner-border').hide();
        }
    });
});

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

// insere a linha com o cidadão cadastrado na tabela
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

$('#estadoCadastro').on('change', function() {
    var selectedUF = $(this).find('option:selected').text();
    
    if(selectedUF == '' || selectedUF == undefined || selectedUF == null) return;

    jQuery.ajax({
        type     : "POST",
        data     : {
            uf: selectedUF
        },
        url      : '/ajax/buscar-cidade-por-uf.php',
        dataType : "json",
        success  : function(data){            
            if(data.sucesso){                                
                $('#cidadeCadastro').prop('disabled', false);
                $('#cidadeCadastro').append(data.data);
            }else{
                alert(data.mensagem);
            }
        },
        error: function(data){
            alert('Um problema impediu a exclusão.');
        },
        beforeSend:function(data){            
            $('#loader').css('display', 'flex');
            $('#cidadeCadastro').empty();
        },
        complete:function(data){
            $('#loader').hide();
        }
    });
});