$(function () {
    var gerenciarContratoVM = new GerenciarContratoVM();
    gerenciarContratoVM.init();
});

function GerenciarContratoVM()
{
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.customLoading = new LoaderAPI();
    this.restClient = new RESTClient();
    this.modalClient = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.mainNavbar = window.location != window.parent.location ? window.parent.getMainNavbar() : null;

    //FORMULARIO CADASTRO DO CONTRATO
    this.formContrato = $('#formContrato');
    this.id = null;
    this.idPessoaJuridica = $("#idPessoaJuridica");
    this.descricao = $('#descricao');
    this.observacao = $('#observacao');
    this.ativo = $('#ativo');
    this.btnCadContrato = $('#btnCadContrato');


    //LISTA TIPO DE EQUIPAMENTO
    this.tablePessoaJuridica = $('#tablePessoaJuridica');
    this.dataTableContrato = new ModernDataTable('tableContrato');
}

GerenciarContratoVM.prototype.init = function () {
    var self = this;
    // Load de plugins
    self.idPessoaJuridica.select2({
        //minimumResultsForSearch: Infinity //cria um autocomplete automaticamente
    });

    // Carregamento
    self.listaContratos(); //datatables
    self.eventos();
    self.listarPessoaJuridica(); //combo de pessoas juridicas
};

GerenciarContratoVM.prototype.eventos = function () {
    var self = this;

    //evento salvar cadastro
    self.btnCadContrato.click(function () {
        self.saveContrato();
    });

    //preencher FORM com os dados do registro a ser editado
    self.dataTableContrato.setOnClick(function(data){
        //console.log(data);
        self.preencheForm(data);
    });

    //preencher FORM com os dados do registro a ser editado
    $('#tableContrato').on('click','.btnEditar',function(){
        var indice = $(this).parents('tr').attr('data-index');
        var data = self.dataTableContrato.getData()[indice];
        //console.log(data);
        self.preencheForm(data);
    });


    self.dataTableContrato.setOnDeleteItemAction(function(ids){
        self.deleteContrato(ids);
        //*console.log(ids);
    });

};

GerenciarContratoVM.prototype.atualizaCampos = function () {
    var self = this;
    self.idPessoaJuridica.select2({
        minimumResultsForSearch: Infinity
    });
}

GerenciarContratoVM.prototype.preencheForm = function (data) {
    var self = this;
    self.id=data['id'];
    self.descricao.val(data['descricao']);
    self.observacao.val(data['observacao']);
    self.idPessoaJuridica.val(data['idPessoaJuridica']);
    self.ativo.prop('checked',data['ativo']);

    self.atualizaCampos();

    //abrir o form e colocar o foco no select
    $('#panel-form').trigger('click');
    self.idPessoaJuridica.focus();
}

GerenciarContratoVM.prototype.limpaForm = function (data) {
    var self = this;
    self.id=null;
    self.descricao.val('');
    self.observacao.val('');
    self.idPessoaJuridica.val('');
    self.ativo.prop('checked',true);
    self.atualizaCampos();
}

GerenciarContratoVM.prototype.listaContratos = function () {
    var self = this;
    var columnsConfiguration = [
        /*
        {"key": "ativo","xrender":function(row){
                return "<a><i class='btnEditar glyphicon glyphicon-edit'></i></a>";
            }
        },*/
        {"key": "id"},
        //{"key": "idPessoaJuridica", visible:false},
        {"key": "nome"},
        {"key": "descricao"},
        {"key": "observacao"},
        {"key": "ativo","type":"boolLabel", "flag":"disabled"}
    ];

    self.dataTableContrato.setDisplayCols(columnsConfiguration);
    //self.dataTableContrato.setDataToSender({'id':'1'});// enviar

    self.dataTableContrato.setIsColsEditable(false); //nao editar as linhas da grid
    self.dataTableContrato.setSourceURL(self.webserviceCienteBaseURL + 'contrato');

    self.dataTableContrato.setRecordsPerPage(3); //quantidade de registros por pagina
    //*self.dataTableContrato.setPaginationBtnQuantity(2); //quantidade de botoes na paginacao

    //*self.dataTableContrato.setSourceURL('http://192.168.133.12/ciente/api/contrato');
    self.dataTableContrato.setSourceMethodPOST();
    self.dataTableContrato.load();

};

GerenciarContratoVM.prototype.listarPessoaJuridica = function () {
    var self = this;
    self.customLoading.show();
    self.restClient.setMethodPUT();
    //self.restClient.setWebServiceURL('Assets/pessoa_juridica.json');
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'contratos/pessoas');
    self.restClient.setSuccessCallbackFunction(function (dados) {
        //console.log(dados.data);
        self.customLoading.hide();
        self.renderSelectPJ(dados.data);
    });
    self.restClient.setErrorCallbackFunction(function (response, status, callback) {
        self.customLoading.hide();
        self.modalClient.showModal(ModalAPI.ERROR, "Conexão com Webservice", "Não foi possível acessar os dados do arquivo JSON", "OK").onClick(function () {
            console.log("callback do botao ok");
        });
    });
    self.restClient.exec();
};

GerenciarContratoVM.prototype.renderSelectPJ = function (dados) {
    var self = this;
    dados.forEach(function (item, index) {
        self.idPessoaJuridica.append('<option value="' + item['idPessoa'] + '">' + item['nomeFantasia'] + '</option>');
    });
};

GerenciarContratoVM.prototype.validaForm = function () {
    var self = this;

    if (!self.idPessoaJuridica.val())
    {
        //self.modalClient.showAlert("Selecione o setor!");
        //self.modalClient.showModal(ModalAPI.WARNING, "", "Selecione o setor!",'OK');
        self.modalClient.notify(ModalAPI.WARNING, "Pessoa Jurídica", "Selecione a empresa!");
        self.idPessoaJuridica.focus();
        return false;
    }

    if (!self.descricao.val())
    {
        self.modalClient.notify(ModalAPI.WARNING, "Descrição", "Preencha !");
        self.descricao.focus();
        return false;
    }

    return true;
};

GerenciarContratoVM.prototype.saveContrato = function () {
    var self = this;

    if (!self.validaForm())
    {
        return false;
    }
    /*var dataToSender = {
        "idPessoaJuridica": self.idPessoaJuridica.val(), "descricao": self.descricao.val(), "observacao": self.observacao.val(), "ativo": self.ativo.is(":checked")
      };*/

    var id = self.id ? '' + self.id : '';
    var dataToSender = self.formContrato.serializeJSON();
    console.log(self.formContrato.serializeJSON());

    self.customLoading.show();
    self.restClient.setDataToSender(dataToSender);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'contrato/' + id);
    self.restClient.setMethodPUT();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.customLoading.hide();
        //alert('Salvo com sucesso!');
        //self.modalClient.showModal(ModalAPI.SUCCESS, "Info", "Salvo com sucesso!", "OK");
        //self.modalClient.notify(ModalAPI.SUCCESS, "Contratos", data['message']);
        self.modalClient.notify(ModalAPI.SUCCESS, "Contratos", "Dados salvos com sucesso!");
        self.limpaForm();
        self.dataTableContrato.load();
        if(id) $('#panel-datatables').trigger('click');
    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.customLoading.hide();
        //alert('Erro oa salvar ');
        self.modalClient.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
        //self.modalClient.showModal(ModalAPI.ERROR, "Erro", {'message':'Erro ao tentar gravar os dados'}, "OK");

        //console.log(jqXHR);
    });
    self.restClient.exec();
};

GerenciarContratoVM.prototype.deleteContrato = function (ids) {
    if (!confirm('Tem certeza que deseja excluir o(s) dado(s)?'))
    {
        return;
    }

    if (ids.length === 0)
    {
        alert('Selecione pelo menos um item');
        return;
    }

    var self = this;
    var dataToSender = {'ids': ids};
    self.customLoading.show();
    self.restClient.setDataToSender(dataToSender);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'contrato');
    self.restClient.setMethodDELETE();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.customLoading.hide();
        console.log(data);
        //alert('Salvo com sucesso!');
        //self.modalClient.showModal(ModalAPI.SUCCESS, "Info", "Salvo com sucesso!", "OK");
        //self.modalClient.notify(ModalAPI.SUCCESS, "Contratos", data['message']);
        self.modalClient.notify(ModalAPI.SUCCESS, "Contratos", "Dados salvos com sucesso!");
        self.limpaForm();
        self.dataTableContrato.load();
    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.customLoading.hide();
        self.modalClient.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
        //self.modalClient.showModal(ModalAPI.ERROR, "Erro", {'message':'Erro ao tentar gravar os dados'}, "OK");
        //console.log(jqXHR);
    });
    self.restClient.exec();

};

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}