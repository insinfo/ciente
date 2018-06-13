$(function () {
    var gerenciarAtendimentosVM = new GerenciarAtendimentosVM();
    gerenciarAtendimentosVM.init();
});

function GerenciarAtendimentosVM() {
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    
    this.restClient = new RESTClient();
    this.modalApi = window.location!=window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.loaderApi = new LoaderAPI();
    this.mainNavbar = window.location!=window.parent.location ? window.parent.getMainNavbar() : null;
    this.datagridAtendimento = new ModernDataGrid('solicitacaoGrade', 'cardAtendimento');
    this.datagridEquipe = new ModernDataGrid('listaUsuarios', 'media-usuario');
    this.filtroSetor = $('[name="selectFiltroSetor"]');
    this.icoReset = $('#icoReset');

    this.selectOrder = $('[name="selectOrder"]');
    this.selectStatus = $('[name="selectStatus"]');
    this.selectPrioridade = $('[name="selectPrioridade"]');
    this.checkProprios = $('[name="chkProprios"]');

    this.containerAtendimentos = $('#solicitacaoGrade');
    this.btnCriar = $('[name="btnCriar"]');
    this.btnAcoes = $('button.sidebar-detached-hide');
    this.inputSearch = $('[name="inputSearch"]');

    this.atendimentosVencendo = 0;
    this.atendimentosVencidos = 0;

    this.init = function() {
        var self = this;
        // Load de plugins
        $('.select-init').selectpicker();
        moment.locale('pt-br', {
            workinghours: {
                0: null,
                1: ['08:00:00', '17:00:00'],
                2: ['08:00:00', '17:00:00'],
                3: ['08:00:00', '11:30','12:30', '17:00:00'],
                4: ['08:00:00', '17:00:00'],
                5: ['08:00:00', '17:00:00'],
                6: null
            },
            holidays: []
        });
        // Carregamento
        self.renderInit();
        self.listarSetores();
        self.eventos();
    };
}

GerenciarAtendimentosVM.prototype.eventos = function () {
    var self = this;
    self.filtroSetor.on('change', function () {
        self.listarEquipe();
        self.exibirEstatisticaPrioridades();
        self.exibirEstatisticaStatus();
    }); // Altera o setor de exibição e dá reload nas estatísticas e atendimentos
    self.btnCriar.on('click', function () {
        window.location.replace('novaSolicitacao');
    }); // redireciona para página de nova solicitação
    self.btnAcoes.on('click', function () {
        if ( self.mainNavbar ) {
            var parentBody = self.mainNavbar.closest('body');
            if (!parentBody.hasClass('sidebar-xs')) {
                parentBody.addClass('sidebar-xs');
            }
        }
    }); // Ao clicar no botão ações, minimiza menu lateral
    self.selectOrder.on('change', function () {
        self.reloadListaAtendimentos();
    }); // Combo de ordenação
    self.selectStatus.on('change', function () {
        self.reloadListaAtendimentos();
    }); // Filtro de STATUS
    self.selectPrioridade.on('change', function () {
        self.reloadListaAtendimentos();
    }); // Filtro de PRIORIDADE
    self.checkProprios.on('change', function () {
        self.reloadListaAtendimentos();
    }); // Filtro de atendimentos próprios/setor
    self.icoReset.on('click', function () {
        self.resetarParamAtendimentos();
        self.inputSearch.val('');
        self.reloadListaAtendimentos();
    }); // Reset dos parametros e reload da tela
    self.inputSearch.on('keypress', function (e) {
        if (e.which == 13) {
            self.resetarParamAtendimentos();
            self.checkProprios.prop('checked',true);
            self.reloadListaAtendimentos();
        }
    }).on('focus', function () {
        $(this).select();
    }); // Efetua o search
    $('ul#listaUsuarios').on('click','[name="card-usuario"]', function () {
        self.loaderApi.show( $('.containerInsideIframe') );
        self.resetarParamAtendimentos();
        self.selectOrder.val('nomeResponsavel asc');
        self.selectOrder.selectpicker('refresh');
        self.checkProprios.prop('checked',true);
        // Adiciona o parametro idPessoa no JSON
        var parametros = self.paramAtendimentos();
        parametros['filters'].push( {"idResponsavel":$(this).attr('data-key')} );
        self.datagridAtendimento.setDivisor( 'divisor', self.paramDivisor() );
        self.datagridAtendimento.setDataToSend( parametros );
        self.datagridAtendimento.load();
        $('.containerInsideIframe').animate({scrollTop:80}, 350);
    }); // Filtra por usuário clicado
    $('div#solicitacaoGrade').on('hide.bs.select', '.cardItemSelectPrioridade', function () {
        var botao = $(this).closest('div.bootstrap-select').find('button');
        var classe = botao.attr('class').match(/\bbg\S+/g)[0];
        var novaClasse = botao.find('i').attr('class').match(/\btext\S+/g)[0].replace('text','bg');
        botao.removeClass(classe).addClass(novaClasse);
    }); // Ajusta cor da prioridade
    $('div#solicitacaoGrade').on('click', 'h6.cardItemTitle', function () {
        var idAtendimento = parseInt($(this).closest('[data-key]').attr('data-key'));
        sessionStorage.setItem('idAtendimento', idAtendimento);
        window.location = '/ciente/detalhe';
    }); // Abre detalhes do atendimento/solicitação
    // Procedimento de alteração de atendimento
    var valorOriginal; // Valor do select antes de ser alterado pelo usuário
    var idAtendimento; // ID do antendimento a ser alterado
    $('div#solicitacaoGrade').on('shown.bs.select','[name="cmb-responsavel"]', function () {
        valorOriginal = $(this).find('option:selected');
    }).on('changed.bs.select', '[name="cmb-responsavel"]', function () {
        var objeto = $(this);
        var novoValor = $(this).find('option:selected');
        var config = { botoes:[ {label:'Definir Responsável',callback:"callback"},
                {label:'Cancelar',callback:"callback"}
            ]};
        var isCancelado = true;
        self.modalApi.showTemplateModal(ModalAPI.PRIMARY, config, $('#modal-custom')).onLoaded(function (modalBody) {
            modalBody.find('[name="mensagem"]').ckeditor();
        }).onClick('Definir Responsável', function (response) {
            isCancelado = false;
            if (response['mensagem']) {
                var idAtendimento = objeto.closest('[data-key]').attr('data-key');
                response['idUsuario'] = novoValor[0].value;
                // Define responsável e adiciona o histórico no atendimento
                self.definirResponsavel(idAtendimento, novoValor[0].text, response, objeto, valorOriginal);
            } else {
                objeto.val(valorOriginal.val());
                objeto.selectpicker('refresh');
                self.modalApi.notify(ModalAPI.WARNING, 'Atendimento', 'Necessário adicionar um comentário para definir um responsável');
            }
        }).onClick('Cancelar', function () {
            self.retornarValorOriginal(objeto, valorOriginal.val(), 'Não foi efetuada nenhuma alteração no responsável do atendimento');
        }).onClosed(function () {
            if (isCancelado) {
                self.retornarValorOriginal(objeto, valorOriginal.val(), 'Não foi efetuada nenhuma alteração no responsável do atendimento');
            }
        });
    });
    $('div#solicitacaoGrade').on('shown.bs.select', '[name="cmb-status"]', function () {
        valorOriginal = $(this).find('option:selected');
    }).on('changed.bs.select', '[name="cmb-status"]', function () {
        var objeto = $(this);
        var novoValor = $(this).find('option:selected');
        var config = { botoes:[ {label:'Alterar Status',callback:"callback"},
                {label:'Cancelar',callback:"callback"}
            ]};
        var isCancelado = true;
        self.modalApi.showTemplateModal(ModalAPI.PRIMARY, config, $('#modal-custom')).onLoaded(function (modalBody) {
            modalBody.find('[name="mensagem"]').ckeditor();
        }).onClick('Alterar Status', function (response) {
            isCancelado = false;
            var idAtendimento = objeto.closest('[data-key]').attr('data-key');
            response['status'] = parseInt(novoValor[0].value);
            // Verifica se a mensagem não é nula com excessão do status 1 que não é exigido
            if ( response['status']!==1 && !response['mensagem'] ) {
                self.modalApi.notify(ModalAPI.WARNING, 'Atendimento', 'A alteração para o status "'+ novoValor[0].text +'" exige que se entre com um comentário para o atendimento');
                objeto.val(valorOriginal.val());
                objeto.selectpicker('refresh');
            } else {
                // Altera o status e adiciona histórico no atendimento
                self.alterarStatus(idAtendimento, novoValor[0].text, response, objeto, valorOriginal);
            }
        }).onClick('Cancelar', function () {
            self.retornarValorOriginal(objeto, valorOriginal.val(), 'Não foi efetuada nenhuma alteração no responsável do atendimento');
        }).onClosed(function () {
            if (isCancelado) {
                self.retornarValorOriginal(objeto, valorOriginal.val(), 'Não foi efetuada nenhuma alteração no responsável do atendimento');
            }
        });
    });
};

GerenciarAtendimentosVM.prototype.definirResponsavel = function (idAtendimento, nomeResponsavel, historico, objeto, valorOriginal) {
    var self = this;
    self.loaderApi.show(self.containerAtendimentos);
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender(historico);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/'+ idAtendimento +'/encaminhar');
    self.restClient.setSuccessCallbackFunction(function () {
        self.modalApi.notify(ModalAPI.SUCCESS, 'Atendimento', '"'+ nomeResponsavel +'" foi definido(a) como responsável pelo atendimento.');
        self.listarEquipe();
        self.loaderApi.hide();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        objeto.val(valorOriginal.val());
        objeto.selectpicker('refresh');
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarAtendimentosVM.prototype.alterarStatus = function (idAtendimento, labelStatus, historico, objeto, valorOriginal) {
    var self = this;
    self.loaderApi.show(self.containerAtendimentos);
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender(historico);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/'+ idAtendimento +'/trocarStatus');
    self.restClient.setSuccessCallbackFunction(function () {
        self.modalApi.notify(ModalAPI.SUCCESS, 'Atendimento', 'Status do atendimento alterado para "'+ labelStatus +'"');
        self.reloadListaAtendimentos();
        self.loaderApi.hide();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        objeto.val(valorOriginal.val());
        objeto.selectpicker('refresh');
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarAtendimentosVM.prototype.exibirEstatisticaPrioridades = function () {
    var self = this;
    var prioridades = $('.estatisticasPrioridades');
    self.restClient = new RESTClient();
    self.restClient.setMethodGET();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'estatisticas/prioridade/solicitacao/setor/' + self.filtroSetor.val());
    self.restClient.setSuccessCallbackFunction(function (response) {
        // Armazena e totaliza as estatísticas
        var estatisticas = [];
        var atendimentos = 0;
        for (var i=0; i<=3; i++) {
            estatisticas.push(0);
            for (var j=0; j<response['data'].length; j++) {
                if (i === response['data'][j]['prioridade']) {
                    estatisticas[i] = response['data'][j]['count'];
                    atendimentos += response['data'][j]['count'];
                }
            }
        }
        // Exibe statisticas no container
        for (var i=0; i<estatisticas.length; i++) {
            var largura = Math.round(estatisticas[i]*100/atendimentos);
            var item = prioridades.find('[data-key="'+ i +'"]');
            item.find('div.progress-bar').css('width',largura+'%');
            item.find('span.atendimentos').text(estatisticas[i]);
        }
        // TODO Separar as estatísticas de totalização de atendimentos
        $('#badgeAtendAtivos').text(atendimentos);
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarAtendimentosVM.prototype.exibirEstatisticaStatus = function () {
    var self = this;
    var status = $('.estatisticasStatus');
    self.restClient = new RESTClient();
    self.restClient.setMethodGET();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'estatisticas/status/atendimento/setor/' + self.filtroSetor.val());
    self.restClient.setSuccessCallbackFunction(function (response) {
        // Armazena e totaliza as estatísticas
        var estatisticas = [];
        var atendimentos = 0;
        for (var i=0; i<=3; i++) {
            estatisticas.push(0);
            for (var j=0; j<response['data'].length; j++) {
                if (i === response['data'][j]['status']) {
                    estatisticas[i] = response['data'][j]['count'];
                    atendimentos += response['data'][j]['count'];
                }
            }
        }
        // Exibe statisticas no container
        for (var i=0; i<estatisticas.length; i++) {
            var largura = Math.round(estatisticas[i]*100/atendimentos);
            var item = status.find('[data-key="'+ i +'"]');
            item.find('div.progress-bar').css('width',largura+'%');
            item.find('span.atendimentos').text(estatisticas[i]);
        }
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarAtendimentosVM.prototype.listarSetores = function () {
    var self = this;
    self.loaderApi.show();
    self.restClient.setMethodPOST();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/usuario');
    self.restClient.setSuccessCallbackFunction(function (response) {
        self.loaderApi.hide();
        populateSelect(self.filtroSetor, response['data'], "idSetor", "sigla", false, false);
        self.filtroSetor.selectpicker('refresh');
        self.listarEquipe();
        self.exibirEstatisticaPrioridades();
        self.exibirEstatisticaStatus();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback.responseJSON, "OK");
    });
    self.restClient.exec();
};
GerenciarAtendimentosVM.prototype.listarEquipe = function () {
    var self = this;
    self.datagridEquipe = new ModernDataGrid('listaUsuarios', 'media-usuario');
    self.datagridEquipe.setDatakey('idPessoa');
    self.datagridEquipe.setupDisplayItems([
        {"key":'nome', "target":'data-nome', 'render':function (nome) {
            return nome.split(' ')[0] +' '+ nome.split(' ')[nome.split(' ').length-1];
        }},
        {"key":'cargo', "target":'data-cargo', "tag":'SPAN'},
        {"key":'imagem', "target":'data-avatar', "tag":'IMG'},
        {'key':'atendimentos', 'target':'data-badge', 'tag':'SPAN'}
    ]);
    self.datagridEquipe.setWebServiceURL(self.webserviceCienteBaseURL + 'usuarios/setor/' + self.filtroSetor.val());
    self.datagridEquipe.setMethodGET();
    self.datagridEquipe.setOnLoadSuccessCallback(function () {
        self.renderListaEquipeTemplate(self.datagridEquipe.dataLoaded);
        self.listarAtendimentos();
    });
    self.datagridEquipe.setOnLoadErrorCallback(function (callback) {
        self.modalApi.showModal(ModalAPI.ERROR, 'Equipe', callback.responseJSON, 'OK');
    });
    self.datagridEquipe.load();
};
GerenciarAtendimentosVM.prototype.listarAtendimentos = function () {
    var self = this;
    self.loaderApi.show( $('.containerInsideIframe') );
    self.atendimentosVencendo = 0;
    self.atendimentosVencidos = 0;
    // Carrega dados via AJAX nativo
    self.datagridAtendimento.setDatakey('_atendimento.id');
    self.datagridAtendimento.setupDisplayItems([
        {"key":'ano', "target":'data-ano', "tag":'SPAN'},
        {"key":'numero', "target":'data-numero', "tag":'SPAN'},
        {"key":'descricao', "target":'data-descricao', "tag":'P'},
        {"key":'prioridade', "target":'data-prioridade', "tag":'SELECT', "readonly":false},
        {"key":'_atendimento.dataEncaminhamento', "target":'data-encaminhamento', "tag":'SPAN', "type":ModernDataGrid.SHORTDATETIME},
        {"key":'_atendimento._prazo', "target":'data-vencimento', 'render': function (prazo, template, item) {
            var mVencendo = moment( item['_atendimento']['dataEncaminhamento'] ).addWorkingTime(prazo*0.8, 'hours');
            var mVencimento = moment( item['_atendimento']['dataEncaminhamento'] ).addWorkingTime(prazo, 'hours');
            if ( moment().isAfter(mVencimento) ) {
                template.find('.cardFooter').addClass('vencido');
                self.atendimentosVencidos++;
            } else if ( moment().isAfter(mVencendo) ) {
                template.find('.cardFooter').addClass('vencendo');
                self.atendimentosVencendo++;
            }
            return moment(mVencimento).format('DD/MM/YY - HH:mm');
        }},
        {'key':'_solicitante.nomeSolicitante', 'target':'data-solicitante', 'tag':'DIV'},
        {'key':'_organograma.nome', 'target':'data-setor', 'tag':'DIV'},
        {"key":'_atendimento.idUsuario', "target":'data-responsavel', "tag":'SELECT'},
        {"key":'_atendimento.status', "target":'data-status', "tag":'SELECT'},
        {"key":'_atendimento._historico', 'target':'cardItemThumbnailUser', 'render': function (dados) {
            if (dados[0]) return self.renderUsuariosHistorico(dados);
            else return;
        }}
    ]);
    self.datagridAtendimento.setItemsPerPage(12);
    // Divisor SETUP
    self.datagridAtendimento.setDivisor( 'divisor', self.paramDivisor() );
    // Pagination SETUP
    self.datagridAtendimento.setButtonsQuantity(5);
    self.datagridAtendimento.setPaginationStyleCaroulsel();
    // Ajax SETUP
    self.datagridAtendimento.setWebServiceURL(self.webserviceCienteBaseURL + 'listarAtendimentos');
    self.datagridAtendimento.setDataToSend( self.paramAtendimentos() );
    self.datagridAtendimento.setOnLoadSuccessCallback(function(){
        // select-icons
        $('.select-template').selectpicker();
        var prioridades = self.datagridAtendimento.container.find('li.itemPrioridade ');
        $.each(prioridades, function(idx, obj){
            var sufixClass = $(obj).find(":selected").attr('data-class');
            // CARD
            var card = $( $(obj).closest('.cardItem') );
            card.addClass('border-left-'+ sufixClass );
            var title = $( $(obj).closest('.cardItem').find('.numeroSolicitacao') );
            title.addClass('text-'+ sufixClass);
            // SELECT PRIORIDADE
            var botao = $(obj).find('button');
            botao.addClass('bg-'+sufixClass);



            // STATISTICAS
            // $('#badgeAtendVencendo').text(self.atendimentosVencendo);
            // $('#badgeAtendVencido').text(self.atendimentosVencidos);
        });
        self.accessControl();
        self.loaderApi.hide();
    });
    self.datagridAtendimento.setOnLoadErrorCallback(function(callback){
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback['responseJSON'], "OK");
    });
    self.datagridAtendimento.setOnPageChangeCallback(function(pageNumber){
        $('.containerInsideIframe').animate({scrollTop:80}, 350);
    });
    self.datagridAtendimento.load();
};
GerenciarAtendimentosVM.prototype.paramAtendimentos = function () {
    var self = this;
    var postData = {"filters":[], "order":[]};
    // Filters definition
    var setor = {"idSetor":parseInt(self.filtroSetor.val())};
    postData['filters'].push(setor);
    var statusFilter =  {};
    switch (self.selectStatus.val()) {
        case 'acessivel':
            statusFilter['status'] = {'operator':'<', 'value':'3'};
            break;
        case 'encerrados':
            statusFilter['status'] = {'operator':'>=', 'value':'3'};
            break;
        default:
            statusFilter['status'] = parseInt(self.selectStatus.val());
            break;
    }
    postData['filters'].push(statusFilter);
    var prioridadeFilter = self.selectPrioridade.val()==='null' ? null : {};
    if (prioridadeFilter) {
        prioridadeFilter['prioridade'] = parseInt(self.selectPrioridade.val());
        postData['filters'].push(prioridadeFilter);
    }
    if ( !self.checkProprios.is(':checked') ) {
        var usuario = {"idResponsavel":parseInt(sessionStorage.idPessoa)};
        postData['filters'].push(usuario);
    }
    // Order definition
    var valor = self.selectOrder.val();
    var order = {};
    order[valor.substring(0,valor.indexOf(' ')).trim()] = valor.substring(valor.indexOf(' ')).trim();
    postData['order'].push(order);
    // Seach definition
    postData['search'] = self.inputSearch.val();

    console.log(JSON.stringify(postData));
    return postData;
};
GerenciarAtendimentosVM.prototype.paramDivisor = function () {
    var json = {'target':'data-divisor'};
    var orderBy = this.selectOrder.val().split(' ')[0];
    switch (orderBy) {
        case 'status':
            json['key'] = '_atendimento.status';
            json['render'] = function (index) {
                return STATUS_ATENDIMENTO[index];
            };
            break;
        case 'nomeResponsavel':
            json['key'] = '_atendimento._responsavel.nomeResponsavel';
            break;
        case 'nomeOrganograma':
            json['key'] = '_organograma.nome';
            break;
        case 'prioridade':
            json['key'] = orderBy;
            json['render'] = function (index) {
                return PRIORIDADES[index];
            };
            break;
        default:
            // json['key'] = orderBy;
            // if (orderBy.indexOf('data') >= 0) json['type'] = ModernDataGrid.DATE;
            // Sem divisores para ordenação por data
            json = {};
            break;
    }
    return json;
};
GerenciarAtendimentosVM.prototype.reloadListaAtendimentos = function () {
    var self = this;
    self.loaderApi.show( $('.containerInsideIframe') );
    self.datagridAtendimento.setDivisor( 'divisor', self.paramDivisor() );
    self.datagridAtendimento.setDataToSend( self.paramAtendimentos() );
    self.datagridAtendimento.load();
};
GerenciarAtendimentosVM.prototype.renderInit = function () {
    var self = this;
    if (self.mainNavbar != null) self.mainNavbar.find('.dynamic').removeClass('hidden');
    $('#solicitacaoTabela').hide();
};
GerenciarAtendimentosVM.prototype.renderListaEquipeTemplate = function (dados) {
    var template = $('#cardAtendimento');
    var content = template.find('div')[0] ? template.find('div') : $(template[0].content);
    var cmbResponsavel = content.find('.data-responsavel');
    cmbResponsavel.empty();
    $.each(dados.data, function (idx, item) {
        var option = $('<option>', {
            value: item['idPessoa'],
            text: item['nome'],
            'data-content': '<i class="icon-user"></i> '+ item['nome']
        });
        cmbResponsavel.append( option );
    });
};
GerenciarAtendimentosVM.prototype.renderUsuariosHistorico = function (dados) {
    var self = this;
    var maxImgs = 7;
    var usuariosSetor = self.datagridEquipe.dataLoaded['data'];
    var html = '';
    var count = 0;
    for (var i=dados.length-1; count<maxImgs && i>=0; i--) {
        var idUserHist = dados[i]['idUsuario'];
        var user = findValueInArray('idPessoa', idUserHist, usuariosSetor);
        html += '<a href="#"> <img src="'+ user['imagem'] +'" class="img-circle img-xs" alt="" title="'+ user['nome'] +'"></a>';
        count++;
    }
    var diff = dados.length - maxImgs;
    var plus = ' <span class="badge badge-default">+'+diff+'</span>';
    html += diff>0 ? plus : '';
    return html;
};
GerenciarAtendimentosVM.prototype.resetarParamAtendimentos = function () {
    var self = this;
    self.selectOrder.selectpicker('val','dataAbertura asc');
    self.selectStatus.selectpicker('val','acessivel');
    self.selectPrioridade.selectpicker('val','null');
    self.checkProprios.prop('checked',false);
};
GerenciarAtendimentosVM.prototype.retornarValorOriginal = function (select, valorOriginal, notificacao) {
    select.val(valorOriginal);
    select.selectpicker('refresh');
    this.modalApi.notify(ModalAPI.WARNING, 'Atendimento', notificacao);
};
// Controle de acesso a recursos da página
GerenciarAtendimentosVM.prototype.accessControl = function () {
    var self = this;
    if (sessionStorage.idPerfil === '1' || sessionStorage.idPerfil === '6') { // PRIORIDADES para perfil "LÍDER"
        // self.datagridAtendimento.container.find('[name="select-prioridade"]').prop('disabled', false);
        // self.datagridAtendimento.container.find('[name="select-prioridade"]').selectpicker('refresh');
    }
    if ("3,4,5,6,encerrados".search( self.selectStatus.val() )!==-1) {
        self.datagridAtendimento.container.find('[name="cmb-responsavel"]').prop('disabled', true);
        self.datagridAtendimento.container.find('[name="cmb-responsavel"]').selectpicker('refresh');
        self.datagridAtendimento.container.find('[name="cmb-status"]').prop('disabled', true);
        self.datagridAtendimento.container.find('[name="cmb-status"]').selectpicker('refresh');
    }
};