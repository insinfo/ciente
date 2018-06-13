$(function () {
    var gerenciarEquipamentoVM = new GerenciarEquipamentoVM();
    gerenciarEquipamentoVM.init();
});

function GerenciarEquipamentoVM()
{
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.customLoading = new LoaderAPI();
    this.restClient = new RESTClient();
    this.modalClient = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.mainNavbar = window.location != window.parent.location ? window.parent.getMainNavbar() : null;

    //FORMULARIO CADASTRO DE MODELO DE EQUIPAMENTO
    this.formEquipamento = $('#formEquipamento');
    this.idEquipamento = null;
    this.idModeloEquipamento = $('#idModeloEquipamento');
    this.idContrato = $('#idContrato');
    this.patrimonioPmro = $('#patrimonioPmro');
    this.patrimonioInterno = $('#patrimonioInterno');
    this.patrimonioEmpresa = $('#patrimonioEmpresa');
    this.observacao = $('#observacao');
    this.checkSituacao = $('#situacao');
    this.checkStatus = $('#status');
    this.btnCadEquipamento = $('#btnCadEquipamento');
    this.selectSetoresUsuario = $('#idSetoresUsuario');

    this.idPessoa = sessionStorage.getItem('idPessoa'); //usuario logado
    //this.idPessoa = 4;


    //LISTA TIPO DE EQUIPAMENTO
    this.tableEquipamento = $('#tableEquipamento');
    this.datatableEquipamento = new ModernDataTable('tableEquipamento');
}

GerenciarEquipamentoVM.prototype.init = function () {
    var self = this;
    // Load de plugins
    self.idModeloEquipamento.select2({
        //minimumResultsForSearch: Infinity
    });

    self.idContrato.select2({
       // minimumResultsForSearch: Infinity
    });

    // Carregamento
    self.eventos();

    self.listarSetoresUsuario(); //combo com modelos do setor selecionado
    self.listarContratos(); //popular combo dos contratos
    self.listaEquipamentoModelo(); //datagrid

};

GerenciarEquipamentoVM.prototype.atualizaCampos = function () {
    var self = this;
    self.idModeloEquipamento.select2({
        //minimumResultsForSearch: Infinity
    });
    self.idContrato.select2({
        //minimumResultsForSearch: Infinity
    });

};

GerenciarEquipamentoVM.prototype.eventos = function () {
    var self = this;
    //evento salvar cadastro
    self.btnCadEquipamento.click(function () {
        self.saveEquipamento();
    });


    self.selectSetoresUsuario.change(function () {
        self.listarEquipModelo();
        //atualizar os dados do DataTables quando trocar o SETOR
        self.datatableEquipamento.setDataToSender({'idSetor':self.selectSetoresUsuario.val()});
        self.datatableEquipamento.reload();
    });


     self.datatableEquipamento.setOnClick(function(data){
        //console.log(data);
        self.preencheForm(data);
    });


    //preencher FORM com os dados do registro a ser editado (clicando no botao de edicao)
    $('#tableEquipamento').on('click','.btnEditar',function(){
        var indice = $(this).parents('tr').attr('data-index'); //pegar o indice da linha para edicao.
        var data = self.datatableEquipamento.getData()[indice]; //obter os dados da linha clicada
        self.preencheForm(data);
    });
};

GerenciarEquipamentoVM.prototype.limpaForm = function (data) {
    var self = this;
    self.idTipoEquipamento=null;
    self.idModeloEquipamento.val('');
    self.idContrato.val('');
    self.patrimonioPmro.val('');
    self.patrimonioInterno.val('');
    self.patrimonioEmpresa.val('');
    self.observacao.val('');
    self.checkStatus.prop('checked',true);
    self.checkSituacao.prop('checked',true);
    self.atualizaCampos();
};

GerenciarEquipamentoVM.prototype.preencheForm = function (data) {
    var self = this;
    console.log(data);
    self.idTipoEquipamento=data['id'];
    self.selectTipoEquip.val(data['idTipoEquipamento']);
    self.textareaDescricao.val(data['descricao']);
    self.checkAtivo.prop('checked',data['ativo']);

    self.atualizaCampos();



    //abrir o form e colocar o foco no select
    $('#panel-form').trigger('click');
    self.selectTipoEquip.focus();

};

GerenciarEquipamentoVM.prototype.listaEquipamentoModelo = function () {
    var self = this;

    var columnsConfiguration = [
        {"key": "id"},
        //{"key": "idTipoEquip"},
        {"key": "descricao"},
        //{"key": "idModeloEquipamento"},
        //{"key": "descricao"},
        {"key": "patrimonioPmro"},
        {"key": "patrimonioInterno"},
        {"key": "patrimonioEmpresa"},
        {"key": "observacao"},
        {"key": "status","type":"boolLabel"}];

    self.datatableEquipamento.setDisplayCols(columnsConfiguration);
    self.datatableEquipamento.setIsColsEditable(false); //nao editar as linhas da grid

    self.datatableEquipamento.setSourceURL(self.webserviceCienteBaseURL+'equipamento');
    self.datatableEquipamento.setSourceMethodPOST();
    //    self.datatableEquipamento.setDataToSender({'idSetor':self.selectSetoresUsuario.val()});
    //    self.datatableEquipamento.load();

};

GerenciarEquipamentoVM.prototype.listarEquipModelo = function () {
    var self = this;
    self.customLoading.show();
    self.restClient.setMethodPOST();

    //self.restClient.setWebServiceURL('/ciente/Assets/setores.json');

    self.restClient.setDataToSender({'idSetor':self.selectSetoresUsuario.val()}); //console.log(self.idSetor);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL+'equipamentoModelo');

    self.restClient.setSuccessCallbackFunction(function (dados) {
        self.customLoading.hide();
        //self.renderselectTipoEquipes(dados);
        fillSelect(self.idModeloEquipamento,dados['data'],'id','descricao');
    });
    self.restClient.setErrorCallbackFunction(function (response, status, callback) {
        self.customLoading.hide();
        self.modalClient.showModal(ModalAPI.ERROR, "Conexão com Webservice", "Não foi possível acessar os dados do arquivo JSON", "OK").onClick(function () {
            console.log("callback do botao ok");
        });
    });

    //self.customLoading.hide();
    self.restClient.exec();
};

GerenciarEquipamentoVM.prototype.renderselectTipoEquipes = function (dados) {
    var self = this;
    dados = dados['data'];
    dados.forEach(function (item, index) {
    self.selectTipoEquip.append('<option value="' + item['id'] + '">' + item['nome'] +  '</option>');
    });
};

GerenciarEquipamentoVM.prototype.validaForm = function () {
    var self = this;

    if (!validarCamposRequeridos(self.formEquipamento)) {
        self.modalClient.notify(ModalAPI.WARNING, 'Equipamento', 'É necessário entrar com os campos requeridos');
        return false;
    }
/*
    if (!self.selectTipoEquip.val())
    {
        //self.modalClient.showAlert("Selecione o setor!");
        //self.modalClient.showModal(ModalAPI.WARNING, "", "Selecione o setor!",'OK');
        self.modalClient.notify(ModalAPI.WARNING, "Setor", "Selecione o setor!");
        self.selectTipoEquip.focus();
        return false;
    }
*/
    return true;
};

GerenciarEquipamentoVM.prototype.saveEquipamento = function () {
    var self = this;

    if (!self.validaForm())
    {
        return false;
    }
    //var dataToSender = {
    //    "idSetor": self.selectTipoEquip.val(), "nome": self.inputNome.val(), "descricao": self.textareaDescricao.val(), "ativo": self.checkAtivo.is(":checked")
    //  };

    var id = self.idEquipamento ? self.idEquipamento : '';

    var dataToSender = self.formEquipamento.serializeJSON();

    console.log(dataToSender);

    self.customLoading.show();
    self.restClient.setDataToSender(dataToSender); //dados
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'equipamento/' + id); //rota pra gravar
    self.restClient.setMethodPUT(); //metodo

    self.restClient.setSuccessCallbackFunction(function (data) {
        self.customLoading.hide();
        //self.modalClient.showModal(ModalAPI.SUCCESS, "Info", "Salvo com sucesso!", "OK");
        //self.modalClient.notify(ModalAPI.SUCCESS, "Modelo de equipamentos", data['message']);
        self.modalClient.notify(ModalAPI.SUCCESS, "Equipamentos", "Salvo com sucesso!", "OK");
        self.limpaForm();
        self.datatableEquipamento.load();
        if(id) $('#panel-datatables').trigger('click');

    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.customLoading.hide();
        //alert('Erro oa salvar ');
        //jqXHR['responseJSON'] = "ERRO NA BAGAÇA";
        self.modalClient.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");

        //console.log(jqXHR);
    });
    self.restClient.exec();
};

GerenciarEquipamentoVM.prototype.listarSetoresUsuario = function () {
    var self = this;
    self.customLoading.show();
    self.restClient.setMethodPOST();

    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL+'setores/usuario');

    self.restClient.setSuccessCallbackFunction(function (dados) {
        self.customLoading.hide();
        //self.renderselectTipoEquipes(dados);

        //*fillSelect(self.selectSetoresUsuario,dados['data'],'idSetor','nome'); //tras com a primeira opção vazia (deixa o usuario escolher)
        populateSelect(self.selectSetoresUsuario,dados['data'],'idSetor','nome',false);

        self.listarEquipModelo(); //combo equipamento tipo

        //inicializar os dados do DataTables com o Setor "padrao"
        self.datatableEquipamento.setDataToSender({'idSetor':self.selectSetoresUsuario.val()});
        self.datatableEquipamento.load();

        //self.listaEquipamentoModelo(); //datatables

    });
    self.restClient.setErrorCallbackFunction(function (response, status, callback) {
        self.customLoading.hide();
        self.modalClient.showModal(ModalAPI.ERROR, "Conexão com Webservice", "Não foi possível acessar os dados do arquivo JSON", "OK").onClick(function () {
            console.log("callback do botao ok");
        });
    });

    self.customLoading.hide();
    self.restClient.exec();
};

GerenciarEquipamentoVM.prototype.listarContratos = function () {
    var self = this;
    self.customLoading.show();
    self.restClient.setMethodPOST();

    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL+'contratos/ativos');

    self.restClient.setSuccessCallbackFunction(function (dados) {
    self.customLoading.hide();
    console.log(dados);

        fillSelect(self.idContrato,dados['data'],'id','descricao'); //tras com a primeira opção vazia (deixa o usuario escolher)
        //populateSelect(self.idContrato,dados['data'],'id','descricao',false);

    });

    self.restClient.setErrorCallbackFunction(function (response, status, callback) {
        self.customLoading.hide();
        self.modalClient.showModal(ModalAPI.ERROR, "Conexão com Webservice", "Não foi possível acessar os dados do arquivo JSON", "OK").onClick(function () {
            console.log("callback do botao ok");
        });
    });

    self.customLoading.hide();
    self.restClient.exec();
};
