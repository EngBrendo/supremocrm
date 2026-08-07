<tr data-id="{{id}}" class="itemLista">
    <td>{{nome}}</td>
    <td>{{telefone}}</td>
    <td>{{cidade}}</td>
    <td>{{estado}}</td>
    <td>
        <div class="edit">
            <i class="material-icons" id="editContato" data-bs-toggle="modal" data-bs-target="#modalEdicao" title="editar" data-id="{{id}}" data-estado="{{idEstado}}" data-cidade="{{idCidade}}" data-nome="{{nome}}" data-telefone="{{telefone}}" data-uf="{{uf}}">edit</i>
        </div>
    </td>
    <td>
        <div class="delete">
            <i class="material-icons" title="deletar" data-id="{{id}}">delete_forever</i>
        </div>
    </td>
</tr>