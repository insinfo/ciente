$(function () {
    var gerenciarSetoresVM = new GerenciarSetoresVM();
    gerenciarSetoresVM.init();
});

function GerenciarSetoresVM() {
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.modalApi = window.location!=window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.parentModal = window.location!=window.parent.location ? window.parent.getPrincipalVM().defaultModal : $('#defaultModal');
    this.loaderApi = new LoaderAPI();
    this.restClient = new RESTClient();
    this.dataTableSetor = new ModernDataTable('tableListSetores');
    this.treeView = new ModernTreeView('treeViewContainer');
    this.treeViewModal = null;

    this.painel = $('#painelSetores');
    this.formulario = $('#formCadSetores');
    this.idSetor = null;
    this.idPai = $('[name="idPai"]');
    this.dataInicio = $('[name="dataInicio"]');
    this.sigla = $('[name="sigla"]');
    this.nome = $('[name="nome"]');
    this.ativo = $('[name="ativo"]');

    this.dataIniOriginal = null;
    this.btnCadSetor = $('#btn-cadastrar-setor');
    this.btnSalvar = $('[name="btnSalvarServico"]');
    this.inputFiltroData = $('#inputFiltroData');

    // Botões de Navegação
    this.btnOpenArvoreSetor = $('#btnOpenArvoreSetor');
    this.btnOpenHistorico = $('#btnOpenHistorico');
    this.btnOpenCadastro = $('#btnOpenCadastro');
}

GerenciarSetoresVM.prototype.init = function () {
    var self = this;
    // Objetos
    // Carregamentos
    self.listarSetores();
    self.inputFiltroData.val( moment().format('L') );
    // Load de plugins
    $('select').select2({
        minimumResultsForSearch: Infinity
    });
    $('.mskData').mask("00/00/0000", {clearIfNotMatch: true});
    self.inputFiltroData.datepicker({
        "language": "pt-BR",
        "format": 'dd/mm/yyyy',
        "autoclose": true
    });
    // Eventos
    this.eventos();
};
GerenciarSetoresVM.prototype.eventos = function () {
    var self = this;
    $('.icon-eraser').on('click', function () {
        self.idPai.val('');
        self.idPai.removeAttr('data-content');
        self.dataInicio.select();
    });
    self.idPai.on('click', function () {
        self.listarSetoresModal();
    });
    self.btnCadSetor.on('click', function () {
        $('#blocoRelacaoSetoresCadastrados').collapse('hide');
        $('#blocoHistoricoSetores').collapse('hide');
        $('#blocoDetalhesSetoresCadastrados').collapse('show');
        self.treeView.load();
        self.listarHistoricoSetor(-1);
        self.resetFormulario();
    });
    self.btnSalvar.on('click', function () {
        self.salvarSetor();
    });
    // toggle dos botoes
    $('a.toggle').on('click', function () {
        console.log( $(this).attr('href') );
    });
    // evento click no filtro por data do tree view setores
    $('#headingListaSetores').on('click', function () {
        $('#blocoRelacaoSetoresCadastrados').collapse('toggle');
    });
    $('.heading-elements').on('click', function (e) {
        e.stopPropagation();
    });
    self.inputFiltroData.on('click',function (e) {
        e.stopPropagation();
    });
    self.inputFiltroData.on('changeDate', function () {
        console.log($(this).val());
        self.listarSetores();
    });

    // Botões de Navegação
    this.btnOpenArvoreSetor.click(function () {
        $('#blocoRelacaoSetoresCadastrados').collapse('toggle');
    });
    this.btnOpenHistorico.click(function () {
        $('#blocoHistoricoSetores').collapse('toggle');
    });
    this.btnOpenCadastro.click(function () {
        $('#blocoDetalhesSetoresCadastrados').collapse('toggle');
    });
};
GerenciarSetoresVM.prototype.carregarSetor = function (setor) {
    var self = this;
    self.idSetor = setor['idSetor'];
    self.idPai.attr('data-content', setor['idPai']);
    self.idPai.val(setor['nomePai']);
    self.dataInicio.val( sqlDateToBrasilDate(setor['dataInicio']) );
    self.sigla.val(setor['sigla']);
    self.nome.val(setor['nome']);
    self.ativo.prop('checked', setor['ativo']);
    self.dataIniOriginal = setor['dataInicio'];
};
GerenciarSetoresVM.prototype.listarSetores = function () {
    var self = this;
    self.loaderApi.show(self.painel);
    self.treeView.setupDisplayItems([
        {'key':'idSetor', 'type':ModernTreeView.ID},
        {'key':'text', 'type':ModernTreeView.LABEL},
        {'key':'ativo', 'type':ModernTreeView.ENABLED}
    ]);
    self.treeView.setExplorerStyle();
    self.treeView.setMethodPOST();
    self.treeView.setDataToSend({
        dataInicio:self.inputFiltroData.val()
    });
    self.treeView.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/hierarquia');
    self.treeView.setOnLoadSuccessCallback(function () {
        self.loaderApi.hide();
    });
    self.treeView.setOnLoadErrorCallback(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Setores", callback.responseJSON, "OK");
    });
    self.treeView.setOnClick(function (response) {
        self.idSetor = response['idSetor'];
        self.listarHistoricoSetor(response['idSetor']);
        $('#blocoRelacaoSetoresCadastrados').collapse('hide');
        $('#blocoHistoricoSetores').collapse('show');
        $('#blocoDetalhesSetoresCadastrados').collapse('hide');
    });
    self.treeView.load();
};
GerenciarSetoresVM.prototype.listarSetoresModal = function () {
    var self = this;
    self.parentModal.find('.modal-body').append('<div id="treeViewModal" class="mtvContainer"></div>');
    self.treeViewModal = new ModernTreeView( self.parentModal.find('#treeViewModal') );
    self.treeViewModal.setupDisplayItems([
        {'key':'idSetor', 'type':ModernTreeView.ID},
        {'key':'text', 'type':ModernTreeView.LABEL},
        {'key':'ativo', 'type':ModernTreeView.ENABLED}
    ]);
    self.treeViewModal.setExplorerStyle();
    self.treeViewModal.setMethodPOST();
    self.treeViewModal.setDataToSend({
        dataInicio:self.inputFiltroData.val()
    });
    self.treeViewModal.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/hierarquia');
    self.treeViewModal.setOnLoadSuccessCallback(function (response) {
        self.loaderApi.hide();
    });
    self.treeViewModal.setOnLoadErrorCallback(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Setores", callback.responseJSON, "OK");
    });
    self.treeViewModal.setOnClick(function (response) {
        var idSetor = response['idSetor'];
        self.idPai.attr('data-content', idSetor);
        self.idPai.val(response['text']);
        self.parentModal.modal('hide');
    });
    self.treeViewModal.load();

    self.parentModal.modal('show');
};
GerenciarSetoresVM.prototype.listarHistoricoSetor = function (idSetor) {
    var self = this;
    self.loaderApi.show();
    self.dataTableSetor.setDisplayCols([
        {'key':'idSetor'},
        {'key':'nomePai', 'render':function (row) {
            if (!row['idPai']) return '';
            else return row['nomePai'];
        }},
        {'key':'dataInicio', 'type':'date'},
        {'key':'sigla'},
        {'key':'nome'}
    ]);
    self.dataTableSetor.setIsColsEditable(false);
    self.dataTableSetor.showActionBtnAdd();
    self.dataTableSetor.setPrimaryKey('idSetor');
    self.dataTableSetor.setSourceMethodPOST();
    self.dataTableSetor.setDataToSender({"draw":1, "length":10, "start":0, "search":"", "idSetor":idSetor});
    self.dataTableSetor.setSourceURL(self.webserviceCienteBaseURL + 'setores');
    self.dataTableSetor.defaultLoader(false);
    self.dataTableSetor.setOnLoadedContent(function () {
        self.loaderApi.hide();
    });
    self.dataTableSetor.setOnClick(function (setor) {
        self.carregarSetor(setor);
        $('#blocoRelacaoSetoresCadastrados').collapse('hide');
        $('#blocoHistoricoSetores').collapse('hide');
        $('#blocoDetalhesSetoresCadastrados').collapse('show');
    });
    self.dataTableSetor.setOnAddItemAction(function () {
        var idSetor = self.idSetor;
        self.resetFormulario();
        self.idSetor = idSetor;
        $('#blocoRelacaoSetoresCadastrados').collapse('hide');
        $('#blocoHistoricoSetores').collapse('hide');
        $('#blocoDetalhesSetoresCadastrados').collapse('show');
    });
    self.dataTableSetor.setOnDeleteItemAction(function (ids, td) {
        self.modalApi.showConfirmation(ModalAPI.WARNING, 'Confirmação', 'Tem certeza que deseja remover o(s) históricos(s) selecionado(s)? A operação não poderá ser desfeita.', 'Sim', 'Não')
            .onClick('Sim', function () {
                var json = [];
                for (var i=0; i<td.length; i++) {
                    json[i] = {idSetor:td[i]['idSetor'],dataInicio:td[i]['dataInicio']};
                }
                self.apagarHistorico(json);
            });
    });
    self.dataTableSetor.load();
};
GerenciarSetoresVM.prototype.preparePostData = function () {
    var self = this;
    var idPai = self.idPai.is('[data-content]') ? self.idPai.attr('data-content') : null;
    var setores = {'ativo':self.ativo.is(':checked')};
    var setoresHistorico = {
        'dataInicio':self.dataInicio.val()!=='' ? self.dataInicio.val() : null,
        'sigla':self.sigla.val().toUpperCase(),
        'nome':self.nome.val(),
        'idPai': idPai
    };
    return {'setor':setores, 'setorHistorico':setoresHistorico}
};
GerenciarSetoresVM.prototype.resetFormulario = function () {
    var self = this;
    self.idSetor = null;
    self.idPai.val('');
    self.idPai.removeAttr('data-content');
    self.dataInicio.val('');
    self.sigla.val('');
    self.nome.val('');
    self.ativo.prop('checked', true);
    self.dataIniOriginal = null;
};
GerenciarSetoresVM.prototype.salvarSetor = function () {
    var self = this;

    if (!validarCamposRequeridos(self.formulario)) {
        self.modalApi.notify(ModalAPI.WARNING, 'Setores', 'É necessário entrar com os campos requeridos');
        return false;
    }

    var parametros = self.idSetor ? self.idSetor : '';
    parametros += self.dataIniOriginal ? '/'+self.dataIniOriginal : '';
    self.loaderApi.show();
    self.restClient.setMethodPUT();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/' + parametros);
    self.restClient.setDataToSender( self.preparePostData() );
    self.restClient.setSuccessCallbackFunction(function (response) {
        if (self.idSetor) {
            $('#blocoRelacaoSetoresCadastrados').collapse('show');
            $('#blocoHistoricoSetores').collapse('hide');
            $('#blocoDetalhesSetoresCadastrados').collapse('hide');
        } else {
            $('#blocoRelacaoSetoresCadastrados').collapse('hide');
            $('#blocoHistoricoSetores').collapse('show');
            $('#blocoDetalhesSetoresCadastrados').collapse('hide');
        }
        self.resetFormulario();
        self.treeView.load();
        self.dataTableSetor.load();
        self.loaderApi.hide();
        self.modalApi.notify(ModalAPI.SUCCESS, 'Setores', response['message']);
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Setores", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarSetoresVM.prototype.apagarHistorico = function (json) {
    var self = this;
    self.loaderApi.show( $('#painelHistoricoSetores') );
    self.restClient.setMethodDELETE();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'setores');
    self.restClient.setDataToSender( json );
    self.restClient.setSuccessCallbackFunction(function (response) {
        $('#blocoRelacaoSetoresCadastrados').collapse('hide');
        $('#blocoHistoricoSetores').collapse('show');
        $('#blocoDetalhesSetoresCadastrados').collapse('hide');
        self.resetFormulario();
        self.treeView.load();
        self.dataTableSetor.load();
        self.loaderApi.hide();
        self.modalApi.notify(ModalAPI.SUCCESS, 'Setores', response['message']);
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Setores", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};