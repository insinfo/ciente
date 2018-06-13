$(function () {
    var gerenciarServicoTipoVM = new GerenciarServicoTipoVM();
    gerenciarServicoTipoVM.init();
});

function GerenciarServicoTipoVM() {
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.customLoading = new LoaderAPI();
    this.restClient = new RESTClient();
    this.modalClient = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.mainNavbar = window.location != window.parent.location ? window.parent.getMainNavbar() : null;

    //FORMULARIO CADASTRO DO CONTRATO
    this.formTipoServico = $('#formTipoServico');
    this.id = null;
    this.idSetor = $("#idSetor");
    this.nome = $('#nome');
    this.descricao = $('#descricao');
    this.prazoAtendimentoInicial = $('#prazoAtendimentoInicial');
    this.ativo = $('#ativo');
    this.btnCadTipoServico = $('#btnCadTipoServico');
    this.idPessoa = sessionStorage.getItem('idPessoa');
    //this.idPessoa = "4";

    //LISTA TIPO DE Servico
    this.tableTipoServico = $('#tableTipoServico');
    this.dataTableTipoServico = new ModernDataTable('tableTipoServico');
}

GerenciarServicoTipoVM.prototype.init = function () {
    var self = this;
    // Load de plugins
    $('select').select2();
    $('.mskInteger').mask("#.##0", {reverse: true});
    // Carregamento
    self.getTipoServico(); //datatables
    self.events();
    self.getSetores(); //combo de Setores
};
GerenciarServicoTipoVM.prototype.events = function () {
    var self = this;

    //evento salvar cadastro
    self.btnCadTipoServico.click(function () {
        self.save();
    });

    //preencher FORM com os dados do registro a ser editado - clicando em qualquer parte da linha
    self.dataTableTipoServico.setOnClick(function (data) {
        //console.log(data);
        self.fillForm(data);
    });

    //preencher FORM com os dados do registro a ser editado (clicando no botao de edicao)
    $('#tableTipoServico').on('click', '.btnEditar', function () {
        var indice = $(this).parents('tr').attr('data-index'); //pegar o indice da linha para edicao.
        var data = self.dataTableTipoServico.getData()[indice]; //obter os dados da linha clicada
        self.fillForm(data);
    });


    self.dataTableTipoServico.setOnDeleteItemAction(function (ids) {
        self.delete(ids);
        //*console.log(ids);
    });

};
GerenciarServicoTipoVM.prototype.updateFields = function () {
    var self = this;
    $('select').select2();
};
GerenciarServicoTipoVM.prototype.fillForm = function (data) {
    var self = this;
    self.id = data['id'];
    self.nome.val(data['nome']);
    self.descricao.val(data['descricao']);
    self.idSetor.val(data['idSetor']);
    self.prazoAtendimentoInicial.val(data['prazoAtendimentoInicial']);
    self.ativo.prop('checked', data['ativo']);

    self.updateFields();

    //abrir o form e colocar o foco no select
    $('#panel-form').trigger('click');
    self.idSetor.focus();
};
GerenciarServicoTipoVM.prototype.resetForm = function (data) {
    var self = this;
    self.id = null;
    self.descricao.val('');
    self.nome.val('');
    self.idSetor.val('');
    self.prazoAtendimentoInicial.val('');
    self.ativo.prop('checked', true);
    self.updateFields();
};
//definicao do DataTables
GerenciarServicoTipoVM.prototype.getTipoServico = function () {
    var self = this;
    var columnsConfiguration = [
        {"key": "id"},
        {"key": "nome"},//nome do servico
        {"key": "siglaSetor", 'align': 'center'},
        {"key": "descricao"},
        {"key": "prazoAtendimentoInicial", 'align': 'center'},
        {"key": "ativo", "type": "boolLabel", 'align': 'center'}
    ];
    self.dataTableTipoServico.setDisplayCols(columnsConfiguration);
    //self.dataTableTipoServico.setDataToSender({'idSetor':'21'});//enviar de forma forçada um parametro

    self.restClient.setDataToSender({'idPessoa': self.idPessoa});

    self.dataTableTipoServico.setIsColsEditable(false); //nao editar as linhas da grid
    self.dataTableTipoServico.setSourceURL(self.webserviceCienteBaseURL + 'servicoTipo/usuario');

    //self.dataTableTipoServico.setRecordsPerPage(3); //quantidade de registros por pagina
    //*self.dataTableTipoServico.setPaginationBtnQuantity(2); //quantidade de botoes na paginacao

    self.dataTableTipoServico.setSourceMethodPOST();
    self.dataTableTipoServico.setOnLoadedContent(function (response, data) {

    });
    self.dataTableTipoServico.load();

};
//listagem dos setores do pmroPadrao
GerenciarServicoTipoVM.prototype.getSetores = function () {
    var self = this;
    self.customLoading.show();
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender({'idPessoa': self.idPessoa}); //console.log(self.idPessoa);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/usuario');
    self.restClient.setSuccessCallbackFunction(function (dados) {
        self.customLoading.hide();
        self.renderSelectSetor(dados);
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.customLoading.hide();
        self.modalClient.showModal(ModalAPI.ERROR, "Setores", callback['responseJSON'], "OK").onClick(function () {
            console.log("callback do botao ok");
        });
    });
    self.restClient.exec();
};
GerenciarServicoTipoVM.prototype.renderSelectSetor = function (dados) {
    var self = this;
    //console.log(dados['data']);
    dados['data'].forEach(function (item, index) {
        if (item['sigla'])
            var sigla = " (" + item['sigla'] + ") ";

        //self.idSetor.append('<option value="' + item['id'] + '">' + item['nome'] + sigla + '</option>');
        self.idSetor.append('<option value="' + item['idSetor'] + '">' + item['nome'] + sigla + '</option>');
    });
};
GerenciarServicoTipoVM.prototype.validaForm = function () {
    var self = this;

    if (!self.idSetor.val()) {
        //self.modalClient.showAlert("Selecione o setor!");
        //self.modalClient.showModal(ModalAPI.WARNING, "", "Selecione o setor!",'OK');
        self.modalClient.notify(ModalAPI.WARNING, "Setor", "Selecione o Setor!");
        self.idPessoaJuridica.focus();
        return false;
    }

    if (!self.nome.val()) {
        self.modalClient.notify(ModalAPI.WARNING, "Nome do Serviço", "Preencha!");
        self.nome.focus();
        return false;
    }

    //if (!parseInt(self.prazoAtendimentoInicial.val()) > 0)
    if (parseInt(self.prazoAtendimentoInicial.val()).toString() === 'NaN') {
        self.modalClient.notify(ModalAPI.WARNING, "Prazo Inicial", "Apenas números!");
        self.prazoAtendimentoInicial.focus();
        return false;
    }


    return true;
};
GerenciarServicoTipoVM.prototype.save = function () {
    var self = this;

    if (!self.validaForm()) {
        return false;
    }

    var id = self.id ? self.id : '';
    var dataToSender = self.formTipoServico.serializeJSON();

    self.customLoading.show();
    self.restClient.setDataToSender(dataToSender);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'servicoTipo/' + id);
    self.restClient.setMethodPUT();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.customLoading.hide();
        self.modalClient.notify(ModalAPI.SUCCESS, "Tipo de Serviço", data['message']);

        self.dataTableTipoServico.reload();
        if (self.id != null) {
            $('#panel-datatables').trigger('click');
        }
        self.resetForm();
    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.customLoading.hide();
        self.modalClient.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarServicoTipoVM.prototype.delete = function (ids) {
    if (!confirm('Tem certeza que deseja excluir o(s) dado(s)?')) {
        return;
    }

    if (ids.length === 0) {
        alert('Selecione pelo menos um item');
        return;
    }

    var self = this;
    var dataToSender = {'ids': ids};
    self.customLoading.show();
    self.restClient.setDataToSender(dataToSender);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'servicoTipo');
    self.restClient.setMethodDELETE();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.customLoading.hide();
        self.modalClient.notify(ModalAPI.SUCCESS, "Tipo de Serviço", "Excluído com sucesso!");
        self.resetForm();
        self.dataTableTipoServico.load();
    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.customLoading.hide();
        self.modalClient.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
    });
    self.restClient.exec();

};


