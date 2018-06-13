$(function () {
    var gerenciarEquipamentoModeloVM = new GerenciarEquipamentoModeloVM();
    gerenciarEquipamentoModeloVM.init();
});

function GerenciarEquipamentoModeloVM()
{
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.customLoading = new LoaderAPI();
    this.restClient = new RESTClient();
    this.modalClient = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.mainNavbar = window.location != window.parent.location ? window.parent.getMainNavbar() : null;

    //FORMULARIO CADASTRO DE MODELO DE EQUIPAMENTO
    this.formEquipamentoModelo = $('#formEquipamentoModelo');
    this.idEquipamentoModelo = null;
    this.selectTipoEquip = $('#idTipoEquipamento');
    this.data = $('#data');
    this.textareaDescricao = $('#descricao');
    this.checkAtivo = $('#ativo');
    this.btnCadEquipamentoModelo = $('#btnCadEquipamentoModelo');
    this.selectSetoresUsuario = $('#idSetoresUsuario');

    this.idPessoa = sessionStorage.getItem('idPessoa'); //usuario logado


    //LISTA TIPO DE EQUIPAMENTO
    this.tableEquipamentoModelo = $('#tableEquipamentoModelo');
    this.datatableEquipamentoModelo = new ModernDataTable('tableEquipamentoModelo');
}

GerenciarEquipamentoModeloVM.prototype.init = function () {
    var self = this;
    // Load de plugins
    self.selectTipoEquip.select2({
        //minimumResultsForSearch: Infinity
    });

    //self.selectSetoresUsuario.select2({
       // minimumResultsForSearch: Infinity
    //});

    $('.mskData').mask("##/##/###0");

    //$('.mskData').mask("00/00/0000", {clearIfNotMatch: true});
    //self.data.datepicker({
    //    "language": "pt-BR",
    //    "format": 'dd/mm/yyyy',
    //    "autoclose": true
    //});

    // Carregamento
    self.eventos();

    self.listarSetoresUsuario(); //combo com modelos do setor selecionado
    self.listaEquipamentoModelo(); //datagrid

};

GerenciarEquipamentoModeloVM.prototype.atualizaCampos = function () {
    var self = this;
    self.selectTipoEquip.select2({
        minimumResultsForSearch: Infinity
    });

};

GerenciarEquipamentoModeloVM.prototype.eventos = function () {
    var self = this;
    //evento salvar cadastro
    self.btnCadEquipamentoModelo.click(function () {
        self.saveEquipamentoModelo();
    });

    self.selectSetoresUsuario.change(function () {
        self.listarTipoEquip();

        //atualizar os dados do DataTables quando trocar o SETOR
        self.datatableEquipamentoModelo.setDataToSender({'idSetor':self.selectSetoresUsuario.val()});
        //console.log(self.selectSetoresUsuario.val());
        self.datatableEquipamentoModelo.reload();
    });


     self.datatableEquipamentoModelo.setOnClick(function(data){
        //console.log(data);
        self.preencheForm(data);
    });


    //preencher FORM com os dados do registro a ser editado (clicando no botao de edicao)
    $('#tableEquipamentoModelo').on('click','.btnEditar',function(){
        var indice = $(this).parents('tr').attr('data-index'); //pegar o indice da linha para edicao.
        var data = self.datatableEquipamentoModelo.getData()[indice]; //obter os dados da linha clicada
        self.preencheForm(data);
    });
};

GerenciarEquipamentoModeloVM.prototype.limpaForm = function (data) {
    var self = this;
    self.idTipoEquipamento=null;
    self.selectTipoEquip.val('');
    self.inputNome.val('');
    self.textareaDescricao.val('');
    self.checkAtivo.prop('checked',true);
    self.atualizaCampos();
};

GerenciarEquipamentoModeloVM.prototype.preencheForm = function (data) {
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

GerenciarEquipamentoModeloVM.prototype.listaEquipamentoModelo = function () {
    var self = this;

    var columnsConfiguration = [
        {"key": "id"},
        //{"key": "idTipoEquip"},
        {"key": "sigla"},
        {"key": "nomeequiptipo"},
        {"key": "descricao"},
        {"key": "ativo","type":"boolLabel"}];

    self.datatableEquipamentoModelo.setDisplayCols(columnsConfiguration);
    self.datatableEquipamentoModelo.setIsColsEditable(false); //nao editar as linhas da grid

    self.datatableEquipamentoModelo.setSourceURL(self.webserviceCienteBaseURL+'equipamentoModelo');
    self.datatableEquipamentoModelo.setSourceMethodPOST();
    //    self.datatableEquipamentoModelo.setDataToSender({'idSetor':self.selectSetoresUsuario.val()});
    //    self.datatableEquipamentoModelo.load();

};

GerenciarEquipamentoModeloVM.prototype.listarTipoEquip = function () {
    var self = this;
    self.customLoading.show();
    self.restClient.setMethodPOST();

    //self.restClient.setWebServiceURL('/ciente/Assets/setores.json');

    self.restClient.setDataToSender({'idSetor':self.selectSetoresUsuario.val()}); //console.log(self.idSetor);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL+'equipamentoTipo');

    self.restClient.setSuccessCallbackFunction(function (dados) {
        self.customLoading.hide();
        //self.renderselectTipoEquipes(dados);
        fillSelect(self.selectTipoEquip,dados['data'],'id','nome');
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

GerenciarEquipamentoModeloVM.prototype.renderselectTipoEquipes = function (dados) {
    var self = this;
    dados = dados['data'];
    dados.forEach(function (item, index) {
    self.selectTipoEquip.append('<option value="' + item['id'] + '">' + item['nome'] +  '</option>');
    });
};

GerenciarEquipamentoModeloVM.prototype.validaForm = function () {
    var self = this;

    if (!self.selectTipoEquip.val())
    {
        //self.modalClient.showAlert("Selecione o setor!");
        //self.modalClient.showModal(ModalAPI.WARNING, "", "Selecione o setor!",'OK');
        self.modalClient.notify(ModalAPI.WARNING, "Setor", "Selecione o setor!");
        self.selectTipoEquip.focus();
        return false;
    }

    return true;
};

GerenciarEquipamentoModeloVM.prototype.saveEquipamentoModelo = function () {
    var self = this;

    if (!self.validaForm())
    {
        return false;
    }
    //var dataToSender = {
    //    "idSetor": self.selectTipoEquip.val(), "nome": self.inputNome.val(), "descricao": self.textareaDescricao.val(), "ativo": self.checkAtivo.is(":checked")
    //  };

    var id = self.idEquipamentoModelo ? self.idEquipamentoModelo : '';

    var dataToSender = self.formEquipamentoModelo.serializeJSON();

    console.log(dataToSender);

    self.customLoading.show();
    self.restClient.setDataToSender(dataToSender); //dados
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'equipamentoModelo/' + id); //rota pra gravar
    self.restClient.setMethodPUT(); //metodo

    self.restClient.setSuccessCallbackFunction(function (data) {
        self.customLoading.hide();
        //self.modalClient.showModal(ModalAPI.SUCCESS, "Info", "Salvo com sucesso!", "OK");
        //self.modalClient.notify(ModalAPI.SUCCESS, "Modelo de equipamentos", data['message']);
        self.modalClient.notify(ModalAPI.SUCCESS, "Modelo de Equipamentos", "Salvo com sucesso!", "OK");
        self.limpaForm();
        self.datatableEquipamentoModelo.load();
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

GerenciarEquipamentoModeloVM.prototype.listarSetoresUsuario = function () {
    var self = this;
    self.customLoading.show();
    self.restClient.setMethodPOST();

    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL+'setores/usuario');

    self.restClient.setSuccessCallbackFunction(function (dados) {
        self.customLoading.hide();
        //self.renderselectTipoEquipes(dados);

        //*fillSelect(self.selectSetoresUsuario,dados['data'],'idSetor','nome'); //tras com a primeira opção vazia (deixa o usuario escolher)
        populateSelect(self.selectSetoresUsuario,dados['data'],'idSetor','nome',false);

        self.listarTipoEquip(); //combo equipamento tipo

        //inicializar os dados do DataTables com o Setor "padrao"
        self.datatableEquipamentoModelo.setDataToSender({'idSetor':self.selectSetoresUsuario.val()});
        self.datatableEquipamentoModelo.load();

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
