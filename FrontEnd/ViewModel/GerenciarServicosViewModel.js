$(function () {
    var gerenciarServicosVM = new GerenciarServicosVM();
    gerenciarServicosVM.init();
});

function GerenciarServicosVM() {
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.restClient = new RESTClient();
    this.loaderApi = new LoaderAPI();
    this.modalApi = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.dataTableServicos = new ModernDataTable('tableListServicos');
    this.filtroSetor = $('#selectFiltroSetorUsuario');

    this.formulario = $('#formCadServico');
    this.idServico = null;
    this.contrato = $('#selectIdContrato');
    this.idTipoServico = $('[name="idTipoServico"]');
    this.codigoRef = $('[name="codigoRef"]');
    this.nome = $('[name="nome"]');
    this.descricao = $('[name="descricao"]');
    this.prazoBaixa = $('[name="prazoBaixa"]');
    this.prazoNormal = $('[name="prazoNormal"]');
    this.prazoAlta = $('[name="prazoAlta"]');
    this.prazoUrgente = $('[name="prazoUrgente"]');
    this.inProduto = $('[name="inProduto"]');
    this.ativo = $('[name="ativo"]');

    this.btnSalvarServico = $('[name="btnSalvarServico"]');
    this.lnkNovoServico = $('#linkNovoPerfil');
    this.btnOpenListaServicos = $('#btnOpenListaServicos');
}

GerenciarServicosVM.prototype.init = function () {
    var self = this;
    // inicializa plugins
    $('select').select2();
    $('.mskInteger').mask("#.##0", {reverse: true});
    // Carregamento
    self.getSetores();
    self.getContratos();
    // Eventos
    self.events();
};
GerenciarServicosVM.prototype.events = function () {
    var self = this;
    self.filtroSetor.on('change', function () {
        console.log($(this).val());
        self.getTipoServico($(this).val());
        self.dataTableServicos.setDataToSender({'idSetor': self.filtroSetor.val()});
        self.dataTableServicos.reload();
    });
    self.btnSalvarServico.on('click', function () {
        self.save();
    });
    self.lnkNovoServico.on('click', function () {
        self.resetForm();
        $('#blocoCadServico').collapse('show');
        self.contrato.focus();
    });
};
GerenciarServicosVM.prototype.updateFields = function () {
    var self = this;
    $('select').select2();
};
GerenciarServicosVM.prototype.delete = function (ids) {
    var self = this;
    if (ids.length === 0) {
        self.modalApi.showModal(ModalAPI.DANGER, 'Serviços', 'É necessário selecionar ao menos um serviço!', 'OK');
        return;
    } else {
        self.modalApi.showConfirmation(ModalAPI.WARNING, 'Confirmação', 'Tem certeza que deseja remover o(s) serviço(s) selecionado(s)? A operação não poderá ser desfeita.', 'Sim', 'Não')
            .onClick('Sim', function () {
                self.loaderApi.show($('#tableServicos'));
                var post = {'ids': ids};
                self.restClient.setDataToSender(post);
                self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'servicos');
                self.restClient.setMethodDELETE();
                self.restClient.setSuccessCallbackFunction(function () {
                    self.dataTableServicos.reload();
                    self.loaderApi.hide();
                    self.modalApi.notify(ModalAPI.SUCCESS, 'Serviços', 'Serviço(s) excluído(s) com sucesso');
                });
                self.restClient.setErrorCallbackFunction(function (callback) {
                    self.modalApi.showModal(ModalAPI.ERROR, 'Serviços', callback.responseJSON, 'OK');
                    self.loaderApi.hide();
                });
                self.restClient.exec();
            });
    }
};
GerenciarServicosVM.prototype.getServicos = function () {
    var self = this;
    self.loaderApi.show();
    self.dataTableServicos.setDisplayCols([
        {'key': 'id'},
        {'key': 'servicoTipoNome'},
        {
            'key': 'codigoReferencia', 'align': 'center', 'render': function (obj) {
                if (obj['codigoReferencia']) return obj['codigoReferencia'];
                else return '-';
            }
        },
        {'key': 'nome'},
        {'key': 'prazoUrgente', 'align': 'center'},
        {'key': 'prazoAlta', 'align': 'center'},
        {'key': 'prazoNormal', 'align': 'center'},
        {'key': 'prazoBaixa', 'align': 'center'},
        {"key": "inProduto", 'align': 'center', "type": "boolLabel"},
        {"key": "ativo", 'align': 'center', "type": "boolLabel"}
    ]);

    self.dataTableServicos.showActionBtnAdd();
    self.dataTableServicos.setIsColsEditable(false);
    self.dataTableServicos.setSourceMethodPOST();
    self.dataTableServicos.setDataToSender({'idSetor': self.filtroSetor.val()});
    self.dataTableServicos.setSourceURL(self.webserviceCienteBaseURL + 'servicos/usuario');
    self.dataTableServicos.defaultLoader(false);
    self.dataTableServicos.setOnReloadAction(function () {
        self.loaderApi.show($('#tableServicos'));
    });
    self.dataTableServicos.setOnLoadedContent(function () {
        self.loaderApi.hide();
    });
    self.dataTableServicos.setOnClick(function (obj) {
        self.fillForm(obj);
        $('#linkOpenForm').trigger('click');
        self.contrato.focus();
    });
    self.dataTableServicos.setOnAddItemAction(function () {
        self.resetForm();
        $('#linkOpenForm').trigger('click');
        self.contrato.focus();
    });
    self.dataTableServicos.setOnDeleteItemAction(function (ids) {
        self.delete(ids);
    });
    self.dataTableServicos.load();
    self.loaderApi.hide();
};
GerenciarServicosVM.prototype.getSetores = function () {
    var self = this;
    self.loaderApi.show();
    self.restClient.setMethodPOST();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/usuario');
    self.restClient.setSuccessCallbackFunction(function (response) {
        self.loaderApi.hide();
        populateSelect(self.filtroSetor, response['data'], "idSetor", "sigla", false, false);
        self.getTipoServico(self.filtroSetor.val());
        self.getServicos();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarServicosVM.prototype.getContratos = function () {
    var self = this;
    self.loaderApi.show();
    self.restClient.setMethodPOST();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'contratos/ativos');
    self.restClient.setSuccessCallbackFunction(function (dados) {
        self.loaderApi.hide();
        fillSelect(self.contrato, dados['data'], "id", "descricao");
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarServicosVM.prototype.getTipoServico = function (idSetor) {
    var self = this;
    self.loaderApi.show($('#panelFormulario'));
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender({"idSetor": idSetor});
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'servicoTipo/usuario');
    self.restClient.setSuccessCallbackFunction(function (response) {
        self.loaderApi.hide();
        fillSelect(self.idTipoServico, response['data'], "id", "nome");
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarServicosVM.prototype.resetForm = function () {
    var self = this;
    resetarValidacao(self.formulario);
    self.idServico = null;
    self.contrato.val('null').trigger('change');
    self.idTipoServico.val('').trigger('change');
    self.codigoRef.val('');
    self.nome.val('');
    self.descricao.val('');
    self.prazoUrgente.val('');
    self.prazoAlta.val('');
    self.prazoNormal.val('');
    self.prazoBaixa.val('');
    self.inProduto.prop('checked', true);
    self.ativo.prop('checked', true);

};
GerenciarServicosVM.prototype.fillForm = function (obj) {
    var self = this;
    self.idServico = obj['id'];
    self.contrato.val(obj['idContrato']).trigger('change');
    self.idTipoServico.val(obj['idTipoServico']).trigger('change');
    self.codigoRef.val(obj['codigoReferencia']);
    self.nome.val(obj['nome']);
    self.descricao.val(obj['descricao']);
    self.prazoUrgente.val(obj['prazoUrgente']);
    self.prazoAlta.val(obj['prazoAlta']);
    self.prazoNormal.val(obj['prazoNormal']);
    self.prazoBaixa.val(obj['prazoBaixa']);

    self.inProduto.prop('checked', obj['inProduto']);
    self.ativo.prop('checked', obj['ativo']);

};
GerenciarServicosVM.prototype.save = function () {
    var self = this;

    if (!validarCamposRequeridos(self.formulario)) {
        self.modalApi.notify(ModalAPI.WARNING, 'Serviço', 'É necessário entrar com os campos requeridos');
        return false;
    }
    if (!self.validarPrazos()) {
        self.modalApi.showModal(ModalAPI.WARNING, 'Prazos', 'Os prazos devem seguir a ordem do menor para o maior valor (Urgência, Alta, Normal e Baixa)', 'OK');
        return false;
    }

    var id = self.idServico ? self.idServico : '';
    var servico = self.formulario.serializeJSON();

    self.loaderApi.show();

    self.restClient.setMethodPUT();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'servicos/' + id);
    self.restClient.setDataToSender(servico);
    self.restClient.setSuccessCallbackFunction(function (dados) {
        self.loaderApi.hide();
        self.modalApi.notify(ModalAPI.SUCCESS, 'Serviços', dados['message']);
        self.dataTableServicos.reload();
        if (self.idServico !== null) {
            self.btnOpenListaServicos.trigger('click');
        } else {
            self.contrato.focus();
        }
        self.resetForm();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Serviços", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarServicosVM.prototype.validarPrazos = function () {
    var self = this;
    var urgente = parseInt(self.prazoUrgente.val());
    var alta = parseInt(self.prazoAlta.val());
    var normal = parseInt(self.prazoNormal.val());
    var baixa = parseInt(self.prazoBaixa.val());
    if (urgente >= alta || urgente >= normal || urgente >= baixa) return false;
    else if (alta >= normal || alta >= baixa) return false;
    else if (normal >= baixa) return false;
    else return true;
};