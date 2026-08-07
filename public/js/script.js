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

// modal de cadastro fecha. limpa o nome e desabilita o botão
$('#modalCadastro').on('hidden.bs.modal', function() {
    $('#nomeCadastro').val('');
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
            nome: nome
        },
        url      : '/ajax/cadastrar-cidadao.php',
        dataType : "json",
        success  : function(data){
            $('#modalCadastro').modal('hide');

            var titulo   = 'Erro no Cadastro!';
            var mensagem = data.mensagem;

            if(data.sucesso){
                titulo   = 'Cadastrado com sucesso!';
                mensagem = 'Nome: '+nome+'<br>NIS: <strong>'+data.nis+'</strong>';

                inserirCidadaoTabela(data.layout);
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
        url      : '/ajax/buscar-cidadao.php',
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

//deleta um cidadão
$(document).on("click", ".delete i", function(){
    var nis = this.dataset.nis;
    if(nis.length != 11) return;

    var icon   = this;
    var loader = $(this.parentNode.parentNode).find('.spinner-border')[0];
    var row    = this.parentNode.parentNode.parentNode;

    jQuery.ajax({
        type     : "POST",
        data     : {
            nis: nis
        },
        url      : '/ajax/deletar-cidadao.php',
        dataType : "json",
        success  : function(data){
            var mensagem = data.mensagem;

            if(data.sucesso){
                mensagem = 'NIS '+nis+' excluído com sucesso!';
                row.remove();
                if(($('.table tbody tr').length == 1)) $('.emptyCidadaos').show();
            }

            showAlert(mensagem, data.sucesso);
        },
        error: function(data){
            $(icon).show();
            $(loader).hide();
            alert('Um problema impediu a exclusão.');
        },
        beforeSend:function(data){
            $(icon).hide();
            $(loader).show();
        },
        complete:function(data){
            $(icon).show();
            $(loader).hide();
        }
    });
});

// insere a linha com o cidadão cadastrado na tabela
function inserirCidadaoTabela(layout) {
    $('.emptyCidadaos').hide();
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
            console.log(data);
            
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
            $('#loader').show();
            $('#cidadeCadastro').empty();
        },
        complete:function(data){
            $('#loader').hide();
        }
    });
});