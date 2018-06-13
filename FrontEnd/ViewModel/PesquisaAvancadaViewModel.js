$(function () {
    var pesquisaAvancadaVM = new PesquisaAvancadaVM();
    pesquisaAvancadaVM.init();
});

function PesquisaAvancadaVM() {
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.webserviceJubarteBaseURL = WEBSERVICE_JUBARTE_BASE_URL;
    this.restClient = new RESTClient();
    this.loaderApi = new LoaderAPI();

    this.modalApi = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.defaultModal = window.location != window.parent.location ? window.parent.getPrincipalVM().defaultModal : $('#defaultModal');

    this.selectPrioridade = $('[name="prioridade"]');
    this.selectStatus = $('[name="status"]');
    this.inputOrganograma = $('[name="organograma"]');
    this.inputSetor = $('[name="setor"]');
    this.inputPesquisaTags = $('[name="pesquisa"]');

    this.modernTreeview = null;
    this.dataTables = new ModernDataTable('tabelaSolicitacao');
}
PesquisaAvancadaVM.prototype.init = function () {
    var self = this;
    $('select').selectpicker();
    $('.tokenfield').tokenfield();
    self.events();
    self.listarSolicitacoes();
};
PesquisaAvancadaVM.prototype.events = function () {
    var self = this;
    self.selectPrioridade.on('change', function () {
        // Reload DataTables
        self.reloadSolicitacoes();
    });
    self.selectStatus.on('change', function () {
        // Reload DataTables
        self.reloadSolicitacoes();
    });
    self.inputOrganograma.on('click', function () {
        self.defaultModal.find('.modal-body').empty();
        self.defaultModal.find('.modal-body').append('<div id="modernTreeview" class="mtvContainer"></div>');
        self.getOrganogramas();
    });
    self.inputSetor.on('click', function () {
        self.defaultModal.find('.modal-body').empty();
        self.defaultModal.find('.modal-body').append('<div id="modernTreeview" class="mtvContainer"></div>');
        self.getSetores();
    });
    self.inputPesquisaTags.on('tokenfield:createdtoken', function (e) {
        var qtde = self.inputPesquisaTags.tokenfield('getTokensList').split(', ').length;
        var bgs = ['bg-indigo','bg-orange','bg-blue'];
        $(e.relatedTarget).addClass( bgs[qtde%3] );
        // Reload DataTables
        self.reloadSolicitacoes();
    });
    self.inputPesquisaTags.on('tokenfield:removedtoken', function () {
        var teste = self.inputPesquisaTags.tokenfield('getTokensList').split(', ');
        self.inputPesquisaTags.tokenfield('setTokens', teste);
        self.reloadSolicitacoes();
    });
};
PesquisaAvancadaVM.prototype.getOrganogramas = function () {
    var self = this;
    self.loaderApi.show();
    self.modernTreeview = new ModernTreeView(self.defaultModal.find('#modernTreeview'));
    self.modernTreeview.setupDisplayItems([
        {'key':'idOrganograma', 'type':ModernTreeView.ID},
        {'key':'text', 'type':ModernTreeView.LABEL}
    ]);
    self.modernTreeview.setExplorerStyle();
    self.modernTreeview.setMethodPOST();
    self.modernTreeview.setWebServiceURL(self.webserviceJubarteBaseURL + 'organogramas/hierarquia');
    self.modernTreeview.setOnLoadSuccessCallback(function (response) {
        self.loaderApi.hide();
        self.defaultModal.modal('show');
    });
    self.modernTreeview.setOnLoadErrorCallback(function (callback) {
        console.log(callback['responseJSON']);
        self.loaderApi.hide();
    });
    self.modernTreeview.setOnClick(function (data) {
        self.inputOrganograma.val(data['text']);
        self.inputOrganograma.attr('data-id',data['idOrganograma']);
        self.defaultModal.modal('hide');
        // Reload DataTables
        self.reloadSolicitacoes();
    });
    self.modernTreeview.load();
};
PesquisaAvancadaVM.prototype.getSetores = function () {
    var self = this;
    self.loaderApi.show($('#panelAtendimento'));
    self.modernTreeview = new ModernTreeView(self.defaultModal.find('#modernTreeview'));
    self.modernTreeview.setupDisplayItems([
        {'key': 'idSetor', 'type': ModernTreeView.ID},
        {'key': 'text', 'type': ModernTreeView.LABEL}
    ]);
    self.modernTreeview.setExplorerStyle();
    self.modernTreeview.setMethodPOST();
    self.modernTreeview.setDataToSend({
        'ativo': true
    });
    self.modernTreeview.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/hierarquia');
    self.modernTreeview.setOnLoadSuccessCallback(function () {
        self.loaderApi.hide();
        self.defaultModal.modal('show');
    });
    self.modernTreeview.setOnLoadErrorCallback(function (callback) {
        self.loaderApi.hide();
    });
    self.modernTreeview.setOnClick(function (data) {
        self.inputSetor.val(data['text']);
        self.inputSetor.attr('data-id',data['idSetor']);
        self.defaultModal.modal('hide');
        // Reload DataTables
        self.reloadSolicitacoes();
    });
    self.modernTreeview.load();
};
PesquisaAvancadaVM.prototype.listarSolicitacoes = function () {
    var self = this;
    self.dataTables.setDisplayCols([
        {'key':'numero', 'render':function (row) {
            var cor = PRIORIDADES_TEXTOS[row['prioridade']];
            return ' <span style="padding-left:8px" class="text-semibold '+ cor +'" style="font-weight:normal"> #'+row["ano"]+"."+row["numero"]+'</span>';
        }},
        {'key':'descricao', 'render': function (row) {
            var descricao = row['descricao'];
            var title = stripHtmlTags(descricao).split('"').join().split('"').join();
            return '<span title="'+title+'">'+ truncate(stripHtmlTags(descricao),50,true) +'</span>'
        }},
        {"key": "prioridade","align":"center","render":function (row) {
            var cor = PRIORIDADES_CORES[row['prioridade']];
            return '<span class="label '+cor+'">'+PRIORIDADES[row['prioridade']]+'</span>';
        }},
        {'key':'dataAbertura',"align":"center", "render":function (row) {
            return '<span title="'+ row['dataAbertura'] +'"><i class="icon-calendar2 position-left"></i> ' + sqlDateToBrasilDate(row['dataAbertura']) +'</span>';
        }},
        {'key':'dataFechamento', 'align':'center', 'render': function (row) {
            var fechamento = row['dataFechamento'];
            return !fechamento ? '-'
                : '<span title="'+ fechamento +'"><i class="icon-calendar2 position-left"></i> ' + sqlDateToBrasilDate(fechamento) +'</span>';
        }},
        {"key": "minStatus","align":"center","render": function (row) {
            return STATUS_ATENDIMENTO[row['minStatus']];
        }},
        {'key':'historico', 'align':'center', 'render': function (row) {
            return  '<a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg" class="img-circle img-xs" alt=""></a> ' +
                    '<a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg" class="img-circle img-xs" alt=""></a> ' +
                    '<a href="#" class="text-default">&nbsp;<i class="icon-plus22"></i></a>';
        }}
    ]);
    self.dataTables.setIsColsEditable(false);
    self.dataTables.defaultLoader(false);
    self.dataTables.hideTableHeader();
    self.dataTables.hideActionBtnDelete();
    self.dataTables.hideRowSelectionCheckBox();
    self.dataTables.setDataToSender({});
    self.dataTables.setSourceURL(self.webserviceCienteBaseURL + 'solicitacoes/pesquisa');
    self.dataTables.setSourceMethodPOST();
    self.dataTables.setOnClick(function (data) {
        console.log(data);
    });
    self.dataTables.setOnLoadedContent(function () {
        console.log('loaded');
    });
    self.dataTables.load();
};
PesquisaAvancadaVM.prototype.prepararJSON = function () {
    var self = this;
    var json = {};
    // Prioridade
    if ( self.selectPrioridade.selectpicker('val') ) {
        json['prioridade'] = self.selectPrioridade.selectpicker('val');
    }
    // Status
    if ( self.selectStatus.selectpicker('val') ) {
        var array = self.selectStatus.selectpicker('val');
        var idx = array.indexOf('encerrados');
        if ( idx >= 0 ) {
            array.splice(idx, 1);
            var encerrados = ['3','4','5','6'];
            for (var count in encerrados) array.push(encerrados[count]);
        }
        json['status'] = array;
    }
    // Lotação
    if ( self.inputOrganograma.attr('data-id') ) {
        json['idOrganograma'] = self.inputOrganograma.attr('data-id');
    }
    // Setor
    if ( self.inputSetor.attr('data-id') ) {
        json['idSetor'] = self.inputSetor.attr('data-id');
    }
    // Campo de TAGs
    if (self.inputPesquisaTags.tokenfield('getTokensList')) {
        json['search'] = self.inputPesquisaTags.tokenfield('getTokensList').split(', ');
    }
    console.log( JSON.stringify(json) );
    return json;
};
PesquisaAvancadaVM.prototype.reloadSolicitacoes = function () {
    var self = this;
    self.dataTables.setDataToSender( self.prepararJSON() );
    self.dataTables.load();
};