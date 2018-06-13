$(function () {
    var minhasSolicitacoesViewModel = new MinhasSolicitacoesViewModel();
    minhasSolicitacoesViewModel.init();
});
function MinhasSolicitacoesViewModel() {
    this.webserviceJubarteBaseURL = WEBSERVICE_JUBARTE_BASE_URL;
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;

    this.loaderApi = new LoaderAPI();
    this.restClient = new RESTClient();
    this.modalApi = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.mainNavbar = window.location != window.parent.location ? window.parent.getMainNavbar() : null;

    //FORM FILTROS
    this.inputFiltroSearch = $('#inputFiltroSearch');
    this.selectFiltroPrioridade = $('#selectFiltroPrioridade');
    this.selectFiltroStatus = $('#selectFiltroStatus');
    this.inputFiltroSetor = $('#inputFiltroSetor');
    this.btnBuscar = $('#btnBuscar');
    this.btnReload = $('#btnReload');
    this.prioridades = [];
    this.status = [];

    //LISTA TIPO DE SERVICO
    this.tableListSolicitacao = $('#tableListSolicitacao');
    this.dataTableListSolicitacao = new ModernDataTable('tableListSolicitacao');

    //LISTA ORGANOGRAMA
    this.tvOrganograma = new ModernTreeView('treeViewOrganograma');
    this.modalOrganograma =  $('#modalOrganograma');

    //LISTA SETOR
    this.modalSetor =  $('#modalSetor');
    this.tvSetor = new ModernTreeView('treeViewSetor');
}
MinhasSolicitacoesViewModel.prototype.init = function () {
    var self = this;
    self.loadPlugins();
    self.getOrganogramas();
    self.getSetoresCiente();
    self.getSolicitacoes();
    self.events();
};
MinhasSolicitacoesViewModel.prototype.loadPlugins = function () {
    var self = this;
    // Select All and Filtering features
    $('.multiselect-select-all-filtering').multiselect({
        includeSelectAllOption: false,
        enableCaseInsensitiveFiltering: true,
        enableFiltering: true,
        selectAllText: 'Tudo',
        allSelectedText:"Selecionado tudo",nonSelectedText:"Selecione",
        templates: {
            filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
        },
        onSelectAll: function() {
            $.uniform.update();

        },
        onChange:function (option, checked, select) {
           // var selected = this.$select.val();
            var selec = option.closest('select');
            var options = selec.find('option:selected');

            if(selec.attr('id') === self.selectFiltroPrioridade.attr('id')){
                emptyArray(self.prioridades);
                options.each(function(index, item){
                    self.prioridades.push($(this).val());
                });
            }

            if(selec.attr('id') === self.selectFiltroStatus.attr('id')){
                emptyArray(self.status);
                options.each(function(index, item){
                    self.status.push($(this).val());
                });
            }

        }
    });
    //Related plugins
    // Styled checkboxes and radios dos multiselect
    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});
};
MinhasSolicitacoesViewModel.prototype.events = function () {
    var self = this;
    self.btnBuscar.click(function () {
        var filtros = {
            "prioridade":self.prioridades,
            "status":self.status,
            "setorResponsavel":self.inputFiltroSetor.attr('data-id'),
            "search":self.inputFiltroSearch.val()
        };
        self.dataTableListSolicitacao.setDataToSender(filtros);
        self.dataTableListSolicitacao.reload();
    });
    self.btnReload.click(function () {
        self.dataTableListSolicitacao.reload();
    });

};
MinhasSolicitacoesViewModel.prototype.getSolicitacoes = function () {
    var self = this;

    self.dataTableListSolicitacao.setDisplayCols([
        {"key": "id"},
        {"key": "numero", 'render':function (row) {
                var cor = PRIORIDADES_TEXTOS[row['prioridade']];
                return '<span style="padding-left:8px" class="text-semibold '+ cor +'" style="font-weight:normal"> #'+row["ano"]+"."+row["numero"]+'</span>';
            }},
        {"key": "descricao","render":function (row) {
                var descricao = row['descricao'];
                return truncate(stripHtmlTags(descricao),30,true);
            }},
        {"key": "prioridade","align":"center","render":function (row) {
            var cor = PRIORIDADES_CORES[row['prioridade']];
                return '<span class="label '+cor+'">'+PRIORIDADES[row['prioridade']]+'</span>';
            }},
        {
            "key": "dataAbertura","align":"center", "render": function (row) {
                return sqlDateToBrasilDate(row['dataAbertura']);
            }
        },
        {"key": "minStatus","align":"center","render": function (row) {
                return STATUS_ATENDIMENTO[row['minStatus']];
            }},
        {"key": "totalAtendimentos","align":"center","render": function (row) {
                var cor = PRIORIDADES_CORES[row['prioridade']];
                return '<span class="badge '+cor+'">'+row['totalAtendimentos']+'</span>';
            }},
        {"key": "setorResponsavelSigla","render":function (row) {
                var setorResponsavelSigla = row['setorResponsavelSigla'];
                return truncate(setorResponsavelSigla,20,true);
            }
        }
    ]);
    self.dataTableListSolicitacao.hideTableHeader();
    self.dataTableListSolicitacao.hideRowSelectionCheckBox();
    self.dataTableListSolicitacao.setIsColsEditable(false);
    self.dataTableListSolicitacao.setDataToSender({});
    self.dataTableListSolicitacao.setSourceURL(self.webserviceCienteBaseURL + 'solicitacoes/usuario');
    self.dataTableListSolicitacao.setSourceMethodPOST();
    self.dataTableListSolicitacao.setOnClick(function (data) {
    });
    //desabilita o Loader padrão
    self.dataTableListSolicitacao.setOnReloadAction(function () {
    });
    // mostra o Loader quando clicar no botão reload do dataTable
    self.dataTableListSolicitacao.setOnLoadedContent(function () {
    });
    self.dataTableListSolicitacao.load();

};
MinhasSolicitacoesViewModel.prototype.getOrganogramas = function () {
    var self = this;
    self.loaderApi.show();
    self.tvOrganograma.setupDisplayItems(
        [
            {'key':'idOrganograma', 'type':ModernTreeView.ID},
            {'key':'text', 'type':ModernTreeView.LABEL}
        ]
    );
    self.tvOrganograma.setExplorerStyle();
    self.tvOrganograma.setMethodPOST();
    self.tvOrganograma.setWebServiceURL(self.webserviceJubarteBaseURL + 'organogramas/hierarquia');
    self.tvOrganograma.setOnLoadSuccessCallback(function (response) {
        self.loaderApi.hide();
    });
    self.tvOrganograma.setOnLoadErrorCallback(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Organogramas", callback['responseJSON'], "OK");
    });
    self.tvOrganograma.setOnClick(function (data) {
        self.inputOrganograma.val(data['sigla']);
        self.inputOrganograma.attr('data-id',data['idOrganograma']);
        self.modalOrganograma.modal('hide')
    });
    self.tvOrganograma.load();
};
MinhasSolicitacoesViewModel.prototype.getSetoresCiente = function () {
    var self = this;
    self.loaderApi.show();
    self.tvSetor.setupDisplayItems([
        {'key': 'idSetor', 'type': ModernTreeView.ID},
        {'key': 'text', 'type': ModernTreeView.LABEL}
    ]);
    self.tvSetor.setExplorerStyle();
    self.tvSetor.setMethodPOST();
    self.tvSetor.setDataToSend({
        'ativo': true
    });
    self.tvSetor.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/hierarquia');
    self.tvSetor.setOnLoadSuccessCallback(function () {
        self.loaderApi.hide();
    });
    self.tvSetor.setOnLoadErrorCallback(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Setores", callback['responseJSON'], "OK");
    });
    self.tvSetor.setOnClick(function (data) {
        self.inputFiltroSetor.val(data['sigla']);
        self.inputFiltroSetor.attr('data-id',data['idSetor']);
        self.modalSetor.modal('hide')
    });
    self.tvSetor.load();

};