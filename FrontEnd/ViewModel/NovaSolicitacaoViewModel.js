
function NovaSolicitacaoVM() {
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.webserviceJubarteBaseURL = WEBSERVICE_JUBARTE_BASE_URL;
    this.loaderApi = new LoaderAPI();
    this.restClient = new RESTClient();
    this.modalApi = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.parentModal = window.location != window.parent.location ? window.parent.getPrincipalVM().defaultModal : $('#defaultModal');
    this.mtvOrganograma = null;
    this.mtvSetores = null;
    this.dataTablesPessoa = new ModernDataTable('tableListaPessoa');

    // solicitação
    this.prioridade = $('[name="select-prioridade"]');
    this.lotacao = $('[name="lotacao"]');   // ORGANOGRAMA
    this.solicitante = $('[name="solicitante"]');
    this.solicitanteSec = $('[name="solicitanteSec"]');
    this.equipamento = $('[name="equipamento"]');
    this.operador = $('[name="operador"]');
    this.descricao = $('[name="editorDescricao"]');
    this.observacao = $('[name="observacao"]');
    this.produto = $("#product-row");
    // atendimento
    this.setor = $('[name="setor"]');
    this.tipoServico = $('[name="tipoServico"]'); // não vai no json
    this.servico = $('[name="servico"]');


    this.btnSolicitar = $(".btn-solicitar");
    this.dropzone = null;
    this.dropzoneFilesCount = 0;

    this.init = function () {
        var self = this;
        // Load de plugins
        self.initPlugins();
        // carrega equipamentos
        self.listarEquipamentos();

        // Carregamentos
        self.carregarOperador();

        // Eventos
        self.eventos();

        /* configure moment */
        if (moment) {
            moment.updateLocale('pt-br', {
                workinghours: {
                    0: null,
                    1: ['08:00:00', '17:00:00'],
                    2: ['08:00:00', '17:00:00'],
                    3: ['08:00:00', '17:00:00'],
                    4: ['08:00:00', '17:00:00'],
                    5: ['08:00:00', '17:00:00'],
                    6: null
                },
                holidays: []
            });
        }
        self.renderPrevisaoTempo();

        self.initDropzone();

    };
}
NovaSolicitacaoVM.prototype.initPlugins = function () {
    self = this;

    $('select').selectpicker();
    self.descricao.ckeditor();
    /*$("#dropzone_multiple").dropzone({
        paramName: "file", // The name that will be used to transfer the file
        dictDefaultMessage: 'Arraste arquivos para upload <span>ou CLIQUE</span>',
        maxFilesize: 1.0 // MB
    });*/
};
NovaSolicitacaoVM.prototype.initDropzone = function () {
    var self = this;
    self.dropzone = new Dropzone($("#dropzoneMultiple").get(0), {
        'paramName': 'arquivo',
        'method': 'post',
        'headers': {
            'Authorization': 'Bearer ' + sessionStorage.YWNjZXNzX3Rva2Vu
        },
        'autoProcessQueue': false,
        'acceptFiles': 'image/*,application/pdf,.psd',
        'init': function () {
            this.on("addedfile", function (file) {
                self.eventDropzoneAddFile(file);
            });

            this.on("success", function (file) {
                self.eventDropzoneSuccess(file);
            });

            this.on("complete", function () {
                // get server response
                location.href = "/ciente/novaSolicitacao";
            });
        }
    });

    $("#dropzoneMultiple").addClass("dropzone");

}
NovaSolicitacaoVM.prototype.eventDropzoneAddFile = function () {
    var self = this;

    $(".dropzone.dz-started .dz-message").css({
        'opacity': 0
    });

    self.dropzoneFilesCount += 1;
}
NovaSolicitacaoVM.prototype.eventDropzoneSuccess = function (file) {
    var self = this;

    $(file.previewElement).fadeOut(function () {
        $(".dropzone.dz-started .dz-message").css({
            'opacity': 1
        });
    });
}


NovaSolicitacaoVM.prototype.eventos = function () {
    var self = this;
    self.prioridade.on('hide.bs.select', function () {
        var botao = $(this).closest('div.bootstrap-select').find('button');
        var classe = botao.attr('class').match(/\bbg\S+/g)[0];
        var novaClasse = botao.find('i').attr('class').match(/\btext\S+/g)[0].replace('text', 'bg');
        botao.removeClass(classe).addClass(novaClasse);
        self.calcularTempoConclusao();
    });

    self.lotacao.on('click', function () {
        self.parentModal.find('.modal-body').empty();
        self.listarLotacoes();
    });

    self.solicitante.on('click', function () {
        self.listarPessoaFisica($(this), {
            "callback": function (data) {
                self.listarSolicitacoesPor('solicitante');
            }
        });
    });
    self.solicitanteSec.on('click', function () {
        self.listarPessoaFisica($(this));
    });

    self.eventosCamposAtendimentos();

    self.btnSolicitar.on('click', function () {
        self.salvarSolicitacao();
    });

    $('.panel.panel-flat').each(function () {
        var panel = this;

        $(panel).find('a[data-action="reload"]').each(function() {
            var list = $(panel).find('.media-list');
            $(this).click(function () {
                self.listarSolicitacoesPor( (list.attr('id') + '').split('-')[2] );
            });
        });

    });

};
NovaSolicitacaoVM.prototype.eventosCamposAtendimentos = function () {
    var self = this;
    self.setor.on('click', function () {
        self.tipoServico.empty().selectpicker("refresh");
        self.servico.empty().selectpicker("refresh");
        self.parentModal.find('.modal-body').empty();
        self.listarSetoresCiente();
    });
    self.tipoServico.on('changed.bs.select', function () {
        self.servico.empty().selectpicker("refresh");
        self.listarServicos(parseInt($(this).val()));
    });
    self.servico.on('changed.bs.select', function () {
        self.listarSolicitacoesPor('servico');
        self.calcularTempoConclusao();

        self.removerListaProdutos();
        var selectValue = this.value;

        $(this).find("option").each(function () {
            if(this.value == selectValue && this.dataset.inProduct == "true") {
                self.mostrarListaProdutos();
                return false;
            }
        });
    });
}

// Loaders
NovaSolicitacaoVM.prototype.carregarOperador = function () {
    var self = this;
    self.operador.attr('data-content', getIdPessoa());
    self.operador.val(getNomePessoa());
};
NovaSolicitacaoVM.prototype.consultarUsuariosSetor = function (idSetor, labelSetor) {
    var self = this;
    self.loaderApi.show($('#panelAtendimento'));
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender({'idSetor': idSetor});
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/usuario/quantidade');
    self.restClient.setSuccessCallbackFunction(function (response) {
        self.loaderApi.hide();
        if (response['total'] > 0) {
            self.setor.attr('data-content', idSetor);
            self.setor.val(labelSetor);
            self.listarServicosTipo(idSetor);
        } else {
            self.modalApi.notify(ModalAPI.WARNING, "Setores", "Nenhum usuário disponível para receber a solicitação no setor escolhido. A seleção foi abortada!");
        }
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Setores", callback['responseJSON'], "OK");
    });
    self.restClient.exec();
};
NovaSolicitacaoVM.prototype.listarLotacoes = function () { // ORGANOGRAMA
    var self = this;
    self.loaderApi.show($('#panelSolicitante'));
    self.parentModal.find('.modal-body').append('<div id="mtvOrganograma" class="mtvContainer"></div>');
    self.mtvOrganograma = new ModernTreeView(self.parentModal.find('#mtvOrganograma'));
    self.mtvOrganograma.setupDisplayItems([
        {'key': 'idOrganograma', 'type': ModernTreeView.ID},
        {'key': 'text', 'type': ModernTreeView.LABEL}
    ]);
    self.mtvOrganograma.setExplorerStyle();
    self.mtvOrganograma.setMethodPOST();
    self.mtvOrganograma.setWebServiceURL(self.webserviceJubarteBaseURL + 'organogramas/hierarquia');
    self.mtvOrganograma.setOnLoadSuccessCallback(function () {
        self.loaderApi.hide();
        self.parentModal.modal('show');
    });
    self.mtvOrganograma.setOnLoadErrorCallback(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Lotações", callback['responseJSON'], "OK");
    });
    self.mtvOrganograma.setOnClick(function (response) {
        var idOrganograma = response['idOrganograma'];
        self.lotacao.attr('data-content', idOrganograma);
        self.lotacao.val(response['text']);
        self.parentModal.modal('hide');

        self.listarSolicitacoesPor('lotacao');
    });
    self.mtvOrganograma.load();
};
NovaSolicitacaoVM.prototype.listarPessoaFisica = function (obj, opts) {
    var self = this;

    if (!opts) {
        opts = {};
    }

    self.loaderApi.show($('#panelSolicitante'));
    self.dataTablesPessoa.setDisplayCols([
        {"key": "id"},
        {"key": "nome"},
        {"key": "dataNascimento", "type": "date"},
        {"key": "sexo"},
        {"key": "cpf"},
        {"key": "rg"}
    ]);
    self.dataTablesPessoa.setIsColsEditable(false);
    self.dataTablesPessoa.hideActionBtnDelete();
    self.dataTablesPessoa.hideRowSelectionCheckBox();
    self.dataTablesPessoa.setDataToSender({"tipo": "fisica"});
    self.dataTablesPessoa.setSourceURL(self.webserviceJubarteBaseURL + 'pessoas');
    self.dataTablesPessoa.setSourceMethodPOST();
    self.dataTablesPessoa.setOnLoadedContent(function () {
        self.loaderApi.hide();
        $('#modalPessoaFisica').modal('show');
    });
    self.dataTablesPessoa.setOnClick(function (response) {
        var idPessoa = response['id'];
        obj.attr('data-content', idPessoa);
        obj.val(response['nome']);
        $('#modalPessoaFisica').modal('hide');

        if (opts.hasOwnProperty("callback")) {
            opts.callback(response);
        }
    });
    self.dataTablesPessoa.load();
};
NovaSolicitacaoVM.prototype.listarSetoresCiente = function () {
    var self = this;
    self.loaderApi.show($('#panelAtendimento'));
    self.parentModal.find('.modal-body').append('<div id="mtvSetores" class="mtvContainer"></div>');
    self.mtvSetores = new ModernTreeView(self.parentModal.find('#mtvSetores'));
    self.mtvSetores.setupDisplayItems([
        {'key': 'idSetor', 'type': ModernTreeView.ID},
        {'key': 'text', 'type': ModernTreeView.LABEL}
    ]);
    self.mtvSetores.setExplorerStyle();
    self.mtvSetores.setMethodPOST();
    self.mtvSetores.setDataToSend({
        'ativo': true
    });
    self.mtvSetores.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/hierarquia'); // Preciso de setores ATIVOS
    self.mtvSetores.setOnLoadSuccessCallback(function () {
        self.loaderApi.hide();
        self.parentModal.modal('show');
    });
    self.mtvSetores.setOnLoadErrorCallback(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Setores", callback['responseJSON'], "OK");
    });
    self.mtvSetores.setOnClick(function (response) {
        var idSetor = response['idSetor'];
        var labelSetor = response['text'];
        self.parentModal.modal('hide');
        self.consultarUsuariosSetor(idSetor, labelSetor);
    });
    self.mtvSetores.load();

    self.removerListaProdutos();
};
NovaSolicitacaoVM.prototype.listarServicosTipo = function (idSetor) {
    var self = this;
    self.loaderApi.show($('#panelAtendimento'));
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender({'idSetor': idSetor});
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'servicoTipo');
    self.restClient.setSuccessCallbackFunction(function (response) {
        self.loaderApi.hide();
        populateSelect(self.tipoServico, response['data'], "id", "nome", false);
        self.tipoServico.selectpicker('refresh');
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Tipo de serviço", callback['responseJSON'], "OK");
    });
    self.restClient.exec();

    self.removerListaProdutos();

};
NovaSolicitacaoVM.prototype.listarServicos = function (idTipoServico) {
    var self = this;
    self.loaderApi.show($('#panelAtendimento'));
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender({'idTipoServico': idTipoServico});
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'servicos');
    self.restClient.setSuccessCallbackFunction(function (response) {
        self.loaderApi.hide();
        populateSelect2(self.servico, response['data'], "id", "nome", function(option, val) {
            option[0].dataset.inProduct = val.inProduto;
        });
        self.servico.selectpicker('refresh');
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Serviço", callback['responseJSON'], "OK");
    });
    self.restClient.exec();
};
NovaSolicitacaoVM.prototype.mostrarListaProdutos = function () {
    var self = this;

    var productDiv = this.produto;
    productDiv.removeClass("hide");

    var cmbSelect = productDiv.find('select');
    self.loaderApi.show(this.produto);

    self.restClient.setMethodPOST();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'produtos');
    self.restClient.setDataToSender({
        'idSetor': $("input[name=setor]")[0].dataset.content
    });
    self.restClient.setSuccessCallbackFunction(function (response) {
        self.loaderApi.hide();
        populateSelect(cmbSelect, response['data'], "id", "nomeReduzido");
        cmbSelect.selectpicker('refresh');
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Produtos", callback['responseJSON'], "OK");
    });
    self.restClient.exec();

}
NovaSolicitacaoVM.prototype.removerListaProdutos = function () {
    var productDiv = this.produto;
    productDiv.addClass("hide");
    productDiv.find("select").empty();
}
NovaSolicitacaoVM.prototype.getFormDataAtendimento = function () {
    return {
        'idSetor': this.setor.data('content'),
        'idServico': this.servico.val(),
        'idProduto': this.produto.find("select").val()
    };
}
NovaSolicitacaoVM.prototype.getFormData = function () {
    return {
        'prioridade': this.prioridade.val(),
        'idOrganograma': this.lotacao.data('content'),
        'idSolicitante': this.solicitante.data('content'),
        'idSolicitanteSec': this.solicitanteSec.data('content'),
        'idEquipamento': this.equipamento.val(),
        'idOperador': this.operador.data('content'),
        'idSetor': this.setor.data('content'),
        'idServico': this.servico.val(),
        'idProduto': this.produto.find("select").val(),
        'descricao': this.descricao.val(),
        'observacao': this.observacao.val(),
        'status': 1
    };
}
NovaSolicitacaoVM.prototype.salvarSolicitacao = function () {
    var self = this;

    var data = this.getFormData();

    var endpoint = self.webserviceCienteBaseURL + 'solicitacao';

    self.loaderApi.show();
    self.restClient.setDataToSender(data); //dados
    self.restClient.setWebServiceURL(endpoint);
    self.restClient.setMethodPUT();

    self.restClient.setSuccessCallbackFunction(function (data) {

        // faz upload dos arquivos
        atendimento_id = data.atendimentoId;

        if (atendimento_id) {
            self.dropzone.options.url = function () {
                return self.webserviceCienteBaseURL + 'atendimento/' + atendimento_id + '/upload';
            }
        }

        if (self.dropzoneFilesCount) {
            self.dropzone.processQueue();
        }

        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.SUCCESS, "Solicitação", data.message , "OK")
            .onClick(function () {
                if (!self.dropzoneFilesCount) {
                    document.location.href="/ciente/novaSolicitacao";
                }
            });
    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
    });
    self.restClient.exec();

}
NovaSolicitacaoVM.prototype.validaForm = function (data) {

    var self = this;

    var data = data? data: this.getFormData();

    var key = 'prioridade';
    if (key in data && !Boolean(data[key])) {
        self.modalApi.notify(ModalAPI.WARNING, "Prioridade", "Selecione a prioridade!");
        self.prioridade.focus();
        return false;
    }

    var key = 'idOrganograma';
    if (key in data && !Boolean(data[key])) {
        self.modalApi.notify(ModalAPI.WARNING, "Lotação", "Selecione uma lotação!");
        self.lotacao.focus();
        return false;
    }

    var key = 'idSolicitante';
    if (key in data && !Boolean(data[key])) {
        self.modalApi.notify(ModalAPI.WARNING, "Solicitante", "Selecione o solicitante!");
        self.solicitante.focus();
        return false;
    }

    var key = 'idOperador';
    if (key in data && !Boolean(data[key])) {
        self.modalApi.notify(ModalAPI.WARNING, 'Operador', 'Selecione o operador!');
        self.operador.focus();
        return false;
    }

    var key = 'idSetor';
    if (key in data && !Boolean(data[key])) {
        self.modalApi.notify(ModalAPI.WARNING, 'Setor', 'Selecione um setor!');
        self.setor.focus();
        return false;
    }

    var key = 'idServico';
    if (key in data && !Boolean(data[key])) {
        self.modalApi.notify(ModalAPI.WARNING, 'Serviço', 'Selecione um serviço!');
        self.servico.focus();
        return false;
    }

    var key = "descricao";
    if (key in data && !Boolean(data[key])) {
        self.modalApi.notify(ModalAPI.WARNING, 'Descrição', 'Preencha este campo, por favor!');
        return false;
    }

    var _key = 'idProduto';
    var _return = true;


    this.servico.find("option").each(function (key, item) {
        if (self.servico.val() == item.value && item.dataset.inProduct == "true" && _key in data && !data[_key]) {
            self.modalApi.notify(ModalAPI.WARNING, 'Produto', 'Preencha este campo, por favor!');
            _return = false;
        }
    });
    if (!_return) return false;


    return true;
}

// busca equipamentos
NovaSolicitacaoVM.prototype.listarEquipamentos = function () {
    var self = this;
    self.loaderApi.show();
    self.restClient.setMethodPOST();
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'equipamento');
    self.restClient.setSuccessCallbackFunction(function (response) {
        if (response instanceof Object && response.data instanceof Array) {
            var default_value = 'Selecione um equipamento';

            response['data'].push({
                'id': '',
                'observacao': default_value
            });

            populateSelect(self.equipamento, response['data'], "id", "observacao", default_value, false);
            self.equipamento.selectpicker('refresh');
        } else {
            console.log(response);
        }

        self.loaderApi.hide();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        self.modalApi.showModal(ModalAPI.ERROR, "Equipamento", callback['responseJSON'], "OK");
    });
    self.restClient.exec();
}
NovaSolicitacaoVM.prototype.listarSolicitacoesPor = function (value) {
    var self = this;

    if (!value) {
        value = "lotacao";
    }

    var div_html_id = "#solicitacoes-por-lotacao";
    var field_name_id = "idOrganograma";
    var input_name = "lotacao";

    switch (value) {
        case 'solicitante': {
            div_html_id = "#solicitacoes-por-solicitante";
            field_name_id = 'idSolicitante';
            input_name = 'solicitante';
        } break;

        case 'servico': {
            div_html_id = "#solicitacoes-por-servico";
            field_name_id = 'idServico';
            input_name = 'servico';
        } break;
    }

    var list_html = $(div_html_id);
    list_html.empty();

    self.loaderApi.show(list_html);
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender({
        "search": "",
        "length":3,
        "start":0,
        "order":"id|desc",
        "fields_filter": [
            ["descricao","html"]
        ],
        "fields": [
            "descricao",
            "ano",
            "numero",
            "observacao"
        ]
    });

    var id = $("[name='" + input_name + "']")[0].dataset.content;
    if (!id) {
        id = $("[name='" + input_name + "']").val();
    }


    var path = 'solicitacoes/por/' + field_name_id + '/' + id;
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + path);

    self.restClient.setSuccessCallbackFunction(function (response) {
        self.loaderApi.hide();

        var data = response['data'];

        var template = {
            "lista": '<li class="outrosChamados" data-popup="popover" data-placement="left" title="" data-trigger="hover" data-content="" data-original-title=""></li>',
            "listaVasia": '<li class="outrosChamados text-center text-uppercase text-semibold text-muted">Nenhuma solicitação localizada</li>'
        };

        // remover itens da lista

        if (data.length > 0) {
            for (var i = 0; i < data.length; i ++) {

                var new_item = $(template.lista).clone();
                new_item[0].dataset.content = data[i].descricao;
                new_item.attr('data-original-title', '#' + data[i].ano + '.' + data[i].numero);
                new_item.html((i + 1) + '. ' + data[i].descricao.substr(0, 30) );
                new_item.popover();

                list_html.append(new_item);
            }
        } else {
            var new_item = $(template.listaVasia).clone();
            list_html.append(new_item);
        }
    });

    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        // self.modalApi.showModal(ModalAPI.ERROR, "Serviço", callback['responseJSON'], "OK");
    });

    self.restClient.exec();
}

NovaSolicitacaoVM.prototype.renderPrevisaoTempo = function (res, prazoInHours) {

    if (!res && ! prazoInHours) {
        var m = moment();
        var dateConclusion = m.addWorkingTime(0, 'hours');
    } else {
        var m = moment(res);
        var dateConclusion = m.addWorkingTime(prazoInHours, 'hours');
    }

    var i = 0;
    $("ul.timer li[class!='dots']").each(function () {

        switch (i) {
            case 0: {
                var day = addZeroEsquerda(dateConclusion.date().toString());
                var span = "<span> dia </span>";
                $(this).html(day + span);
            } break;

            case 1: {
                var month = dateConclusion.month() + 1;
                month = addZeroEsquerda(month.toString());
                var span = "<span> mês </span>";
                $(this).html(month + span);
            } break;

            case 2: {
                var year = addZeroEsquerda(dateConclusion.year().toString());
                var span = "<span> ano </span>";
                $(this).html(year[2] + '' + year[3] + span);
            } break;

            case 3: {
                var h = addZeroEsquerda(dateConclusion.hours().toString());
                var m = addZeroEsquerda(dateConclusion.minutes().toString());
                var time = h + ":" + m +
                    '<span class="mt-15">horário</span>';
                $(this).html(time);
            } break;
        }

        i++;
    });
}

NovaSolicitacaoVM.prototype.calcularTempoConclusao = function () {
    var self = this;
    self.pri = self.prioridade.val();
    self.servicoId = self.servico.val();

    self.prioridades = [
        "prazoUrgente",
        "prazoAlta",
        "prazoNormal",
        "prazoBaixa"
    ];

    var path = self.webserviceCienteBaseURL + "servicos/" + self.servicoId;

    self.restClient.setMethodGET();
    self.restClient.setWebServiceURL(path);

    self.restClient.setSuccessCallbackFunction(function (response) {

        var prazo = self.prioridades[self.pri];
        var prazoInHours = response[prazo];

        // get server time
        var path = self.webserviceJubarteBaseURL + "datetime/timestamp";

        var rest = new RESTClient();
        rest.setMethodGET();
        rest.setWebServiceURL(path);
        rest.setSuccessCallbackFunction(function (response) {
            self.renderPrevisaoTempo(response.forMoment, prazoInHours)
        })
        rest.exec();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.loaderApi.hide();
        // self.modalApi.showModal(ModalAPI.ERROR, "Serviço", callback['responseJSON'], "OK");
    });

    self.restClient.exec();

}