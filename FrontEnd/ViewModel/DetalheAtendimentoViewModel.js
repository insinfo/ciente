function DetalheAtendimentoVM() {

    this.dadosAtendimentoSessionKeyName = 'atendimento-data-s';
    this.restClient = new RESTClient();
    this.customLoading = new LoaderAPI();

    this.modal = window.location!=window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.modalApi = this.modal; // alias, temporário, por que tem partes do código que estão com este nome

    this.mainNavbar = window.location!=window.parent.location ? window.parent.getMainNavbar() : null;
    this.parentModal = window.location != window.parent.location ? window.parent.getPrincipalVM().defaultModal : $('#defaultModal');

    this.renderTimeShow = true;

    this.htmlObjects = {};

    /* reaproveitamento do codigo ja implementado no NovaSolicitacaoViewModel */
    this.solicitacaoVM = new NovaSolicitacaoVM();
    this.solicitacaoVM.eventosCamposAtendimentos();
}

// Metodos uteis
{

    /**
     * Essa variável deve ser chamada quando há necessidade de bloquear outra ação.
     *
     * Isso é necessário por exemplo, quando uma requisição é realizada e nenhuma outra pode acontecer enquanto
     * não houver uma resposta do servidor.
     *
     * @type {boolean}
     */
    DetalheAtendimentoVM.prototype.bloqueiaOutraAcao = false;

    /**
     * Verifica se a ação está bloqueada mas também envia uma mensagem ao usuário informando que o sistema está trabalhando
     *
     * @returns {boolean}
     */
    DetalheAtendimentoVM.prototype.acaoBloqueada = function () {

        if (this.bloqueiaOutraAcao) {
            this.modalApi.notify(
                ModalAPI.WARNING, "Trabalhando", "Aguarde, o sistema está trabalhando em outra solicitação."
            );
        }

        return this.bloqueiaOutraAcao;
    }

    /**
     * Bloqueia outras ações
     * @returns {DetalheAtendimentoVM}
     */
    DetalheAtendimentoVM.prototype.bloquearAcao = function () {
        this.bloqueiaOutraAcao = true;
        return this;
    }

    /**
     * Desbloqueia outras ações
     * @returns {DetalheAtendimentoVM}
     */
    DetalheAtendimentoVM.prototype.desbloquearAcao = function () {
        this.bloqueiaOutraAcao = false;
        return this;
    }

    DetalheAtendimentoVM.prototype.reloaderHistoricos = function (historicos) {
        if (historicos) {
            var dataSession = this.getDataSession();

            historicos = dataSession.historicos.concat(historicos);
            dataSession.historicos = historicos;
            this.saveDataSession(dataSession);
            this.gridHistorico.loadFromJson(historicos);
        }
    }

    DetalheAtendimentoVM.prototype.reloadAtendimento = function (id) {
        sessionStorage.setItem('idAtendimento', id);
        this.reInit();
    }

    DetalheAtendimentoVM.prototype.alterarNomeResponsavel = function (destinatario) {
        if (destinatario) {
            // alterar o nome do responsável na tabela
            var self = this;
            this.htmlObjects.solicitacaoAtendimentosTable.find("tbody tr.success").each(function (index, elem) {
                var col = $(elem).find("td:nth-child(3)");
                if (col) {
                    col.html(destinatario.nome);
                    self.htmlObjects.detalhesSolicitacao.responsavel.html(destinatario.nome);
                }
            });
        }
    }

    DetalheAtendimentoVM.prototype.alterarStatusAtendimento = function (status) {
        if (status) {
            // alterar o nome do responsável na tabela
            var self = this;
            this.htmlObjects.solicitacaoAtendimentosTable.find("tbody tr.success").each(function (index, elem) {
                var col = $(elem).find("td:nth-child(5)");
                if (col) {
                    col.html(status);
                }
            });
        }
    }

    DetalheAtendimentoVM.prototype.getMomentFormat = function (data) {
        var data = moment(data);
        return data.format('DD/MM/YY HH:mm');
    }

    DetalheAtendimentoVM.prototype.alterarStatus = function (idAtendimento, labelStatus, historico, objeto, valorOriginal) {
        var self = this;
        self.customLoading.show();
        self.restClient.setMethodPOST();
        self.restClient.setDataToSender(historico);
        self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/'+ idAtendimento +'/trocarStatus');
        self.restClient.setSuccessCallbackFunction(function (data) {
            self.modalApi.notify(ModalAPI.SUCCESS, 'Atendimento', 'Status do atendimento alterado para "'+ labelStatus +'"');
            self.alterarStatusAtendimento(labelStatus);
            self.reloaderHistoricos(data.historicos);
            self.customLoading.hide();
        });
        self.restClient.setErrorCallbackFunction(function (callback) {
            objeto.val(valorOriginal.val());
            objeto.selectpicker('refresh');
            self.customLoading.hide();
            self.modalApi.showModal(ModalAPI.ERROR, "Erro", callback.responseJSON, "OK");
        });
        self.restClient.exec();
    };

    DetalheAtendimentoVM.prototype.retornarValorOriginal = function (select, valorOriginal, notificacao) {
        select.val(valorOriginal);
        select.selectpicker('refresh');
        this.modalApi.notify(ModalAPI.WARNING, 'Atendimento', notificacao);
    };

}

// Events
{
    DetalheAtendimentoVM.prototype.events = function () {
        var self = this;

        self.htmlObjects.io.comboPrioridade.on('hide.bs.select', function () {self.eventCmbPrioridadeHide(this);})
            .on('rendered.bs.select', function () { self.eventCmbPrioridadeChanged(this); });

        self.htmlObjects.io.comboEquipe.on('changed.bs.select', function () {
            self.eventCmbEquipeChanged();
        });

        self.htmlObjects.io.comboStatus.on('changed.bs.select', function () {
            self.eventCmbStatusChanged();
        });

        self.htmlObjects.io.button.comment.click(function () {
            self.eventButtonCommentClick();
        });

        self.htmlObjects.io.button.transfer.click(function() {
            self.eventButtonTransferClick();
        });

        self.htmlObjects.io.button.btnAtendimentoAssumir.click(function () {
            self.eventButtonPegarAtendimento();
        });

        self.htmlObjects.io.button.concluirTransferencia.click(function () {
            self.eventButtonConcluirTransferencia();
        });

        self.htmlObjects.io.button.checkboxTransferirManter.change(function () {
            self.eventCheckboxTransferirManter(this);
        });


    }

    DetalheAtendimentoVM.prototype.eventCmbPrioridadeChanged = function (elm) {
        var botao = $(elm).closest('div.bootstrap-select').find('button');
        var classe = botao.attr('class').match(/\bbg\S+/g)[0];
        var novaClasse = botao.find('i').attr('class').match(/\btext\S+/g)[0].replace('text', 'bg');
        botao.removeClass(classe).addClass(novaClasse);
    }
    DetalheAtendimentoVM.prototype.eventCmbPrioridadeHide = function (elm) {
        var botao = $(elm).closest('div.bootstrap-select').find('button');
        var classe = botao.attr('class').match(/\bbg\S+/g)[0];
        var novaClasse = botao.find('i').attr('class').match(/\btext\S+/g)[0].replace('text', 'bg');
        botao.removeClass(classe).addClass(novaClasse);
    }
    DetalheAtendimentoVM.prototype.eventButtonTransferClick = function () {
        var self = this;

        // abrir modal para mostrar os campos setor,
        self.htmlObjects.panelTransfer.panel.hide().removeClass("hide").slideDown();
    }
    DetalheAtendimentoVM.prototype.eventCmbStatusChanged = function () {resp_atual
        var self = this;
        var novoValor = self.htmlObjects.io.comboStatus.val();


    }
    DetalheAtendimentoVM.prototype.eventCmbEquipeChanged = function () {
        var self = this;

        var resp_selecionado = this.htmlObjects.io.comboEquipe.find('option:selected').val();
        var resp_atual = self.getDataSession().idUsuario;
        var usuario_logado = sessionStorage.getItem('idPessoa');

        if (resp_selecionado == resp_atual) {
            // se usuario e o mesmo
            self.htmlObjects.io.button.comment.find('span').text('Adicionar comentário');
        } else if (resp_selecionado != resp_atual && resp_selecionado != usuario_logado && Boolean(resp_selecionado)) {
            // se mudar o usuario
            self.htmlObjects.io.button.comment.find("span").text("Comentar e encaminhar");
        } else if (resp_selecionado == usuario_logado) {
            self.htmlObjects.io.button.comment.find("span").text("Pegar atendimento");
        } else {
            self.htmlObjects.io.button.comment.find('span').text('Adicionar comentário');
        }

    }
    DetalheAtendimentoVM.prototype.eventCheckboxTransferirManter = function (checkbox) {
        var combo = this.htmlObjects.io.comboStatusTransferir;
        if (checkbox.checked) {
            combo[0].disabled = true;
            combo.addClass("disabled");
        } else {
            combo[0].disabled = false;
            combo.removeClass("disabled");
        }
        combo.selectpicker("refresh");
    }

    /* eventos de ações, eventos bloqueantes */
    DetalheAtendimentoVM.prototype.eventButtonConcluirTransferencia = function () {
        if (this.acaoBloqueada()) return false;

        var self = this;


        var atendimentoData = self.solicitacaoVM.getFormDataAtendimento();

        if (self.solicitacaoVM.validaForm(atendimentoData) ) {
            // prepara encaminhamento

            var atendimento_id = self.getDataSession().id;
            var resource = ["atendimento",atendimento_id,"transferir",atendimentoData.idSetor].join("/");
            var data_sender = {
                "idServico": atendimentoData.idServico
            };

            if ("idProduto" in atendimentoData && atendimentoData.idProduto) {
                data_sender.idProduto = atendimentoData.idProduto;
            }

            var mensagem = this.htmlObjects.io.commentTransfer.val();
            if (mensagem == "") {
                this.modal.notify(ModalAPI.DANGER, "Para realizar a transferência, é obrigatório adicionar uma mensagem.");
                return false;
            }
            data_sender.mensagem = mensagem;

            if (this.htmlObjects.io.button.checkboxTransferirManter[0].checked === false) {
                data_sender.status = this.htmlObjects.io.comboStatusTransferir.val();
                data_sender.manter_aberto = false;
            } else {
                data_sender.manter_aberto = true;
            }

            var resource_url = self.webserviceCienteBaseURL + resource;

            self.customLoading.show();
            self.restClient.setMethodPOST();
            self.restClient.setWebServiceURL(resource_url);
            self.restClient.setDataToSender(data_sender);
            self.restClient.setSuccessCallbackFunction(function (data) {

                /* redirect from page /ciente/grid */
                self.customLoading.hide();
                self.desbloquearAcao();
                self.modal.showModal(ModalAPI.SUCCESS, "Sucesso", data.message, "OK");

                location.href = "/ciente/grid";

                /*
                    if (data && data.novoAtendimento) {
                        sessionStorage.setItem("idAtendimento", data.novoAtendimento.id);
                        self.reInit();
                    }

                */
            });

            self.restClient.setErrorCallbackFunction(function (jqXHR) {
                self.customLoading.hide();
                self.desbloquearAcao();
                self.modal.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
            });

            self.bloquearAcao();
            self.restClient.exec();

        }

    }
    DetalheAtendimentoVM.prototype.eventButtonPegarAtendimento = function () {
        if (this.acaoBloqueada()) return false;

        var self = this;
        var atendimento_id = self.getDataSession().id;

        this.restClient.setMethodPOST();
        this.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/' + atendimento_id + '/assumir');
        this.restClient.setSuccessCallbackFunction(function (data) {
            self.desbloquearAcao();
            self.customLoading.hide();

            // faz um reload nos historicos
            self.reloaderHistoricos(data.historicos);

            // altera o nome do responsável na tabela
            self.alterarNomeResponsavel(data.responsavel);

            self.modalApi.notify(
                ModalAPI.SUCCESS, "Assumir atendimento", "Agora você é o responsável por este atendimento"
            );

        });

        this.restClient.setErrorCallbackFunction(function (jqXHR) {
            self.desbloquearAcao();
            self.customLoading.hide();
            self.modal.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
        });

        this.modalApi.showConfirmation(ModalAPI.WARNING,
            "Assumir atendimento",
            "Você confirma que deseja assumir este atendimento?",
            "Ok",
            "Cancelar").onClick("ok", function (){

                self.customLoading.show();
                self.bloquearAcao();
                self.restClient.exec();
        });

    }

    /* comentar e ou encaminhar */
    DetalheAtendimentoVM.prototype.eventButtonCommentClick = function () {
        if (this.acaoBloqueada()) return false;

        var self = this;

        var editor = self.htmlObjects.io.comment;
        var comment = editor.val();

        // pega o usuario responsavel
        var resp_selecionado = self.htmlObjects.io.comboEquipe.val();
        var status_id = self.htmlObjects.io.comboStatus.val();
        var data_session = self.getDataSession();
        var atendimento_id = data_session.id;
        var usuario_logado = sessionStorage.getItem("idPessoa");
        var responsavel_atual = self.getDataSession().idUsuario;


        if (!comment) {
            self.modalApi.notify(
                ModalAPI.WARNING, "Comentário", "Preencha o campo editor de comentário"
            );
        } else {

            if (resp_selecionado == responsavel_atual && Boolean(resp_selecionado)) { // comenta
                self.customLoading.show();
                self.restClient.setMethodPOST();
                self.restClient.setDataToSender({
                    'responsavel_id': resp_selecionado,
                    'mensagem': comment
                });
                self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/' + atendimento_id + '/comentario');
                self.restClient.setSuccessCallbackFunction(function (data) {
                    self.desbloquearAcao();
                    self.customLoading.hide();

                    self.reloaderHistoricos(data.historicos);

                    self.modal.notify(ModalAPI.SUCCESS, "Sucesso", "Comentário enviado com sucesso!", "OK");

                    // comentario enviado, esvazia editor
                    editor.val("");
                });

                self.restClient.setErrorCallbackFunction(function (jqXHR) {
                    self.desbloquearAcao();
                    self.customLoading.hide();
                    self.modal.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
                });

                this.bloquearAcao();
                self.restClient.exec();

            }
            else if (resp_selecionado != responsavel_atual && resp_selecionado != usuario_logado && Boolean(resp_selecionado)) {
                // encaminhar

                self.customLoading.show();
                self.restClient.setMethodPOST();
                self.restClient.setDataToSender({
                    'idUsuario': resp_selecionado,
                    'mensagem': comment
                });
                self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/' + atendimento_id + '/encaminhar');
                self.restClient.setSuccessCallbackFunction(function (data) {
                    self.desbloquearAcao();
                    self.customLoading.hide();

                    self.reloaderHistoricos(data.historicos);

                    self.modal.notify(ModalAPI.SUCCESS, "Sucesso", "Transferência realizada com sucesso!", "OK");

                    // depois de comentar, voltar o combo para o id do usuário logado.

                    self.htmlObjects.io.comboEquipe.find('option').each(function(){
                        if (parseInt(this.value) == parseInt(usuario_logado)) {
                            this.selected = true;
                        }
                    });
                    self.htmlObjects.io.comboEquipe.selectpicker("refresh");

                    self.alterarNomeResponsavel(data.destinatario);

                    // comentario enviado, esvazia editor
                    editor.val("");
                });

                self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
                    self.desbloquearAcao();
                    self.customLoading.hide();
                    self.modal.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
                });
                this.bloquearAcao();
                self.restClient.exec();
            } else if (resp_selecionado == usuario_logado && Boolean(resp_selecionado)) {
                // pegar atendimento
                this.eventButtonPegarAtendimento();
            } else {
                // comenta passando o usuário atual

                self.customLoading.show();
                self.restClient.setMethodPOST();
                self.restClient.setDataToSender({
                    'responsavel_id': usuario_logado,
                    'mensagem': comment
                });
                self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/' + atendimento_id + '/comentario');
                self.restClient.setSuccessCallbackFunction(function (data) {
                    self.desbloquearAcao();
                    self.customLoading.hide();

                    self.reloaderHistoricos(data.historicos);

                    self.modal.notify(ModalAPI.SUCCESS, "Sucesso", "Comentário enviado com sucesso!", "OK");

                    // comentario enviado, esvazia editor
                    editor.val("");
                });

                self.restClient.setErrorCallbackFunction(function (jqXHR) {
                    self.desbloquearAcao();
                    self.customLoading.hide();
                    self.modal.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
                });

                this.bloquearAcao();
                self.restClient.exec();

            }
        }
    }
    DetalheAtendimentoVM.prototype.eventTableAtendimentoRowClick = function(row) {
        this.reloadAtendimento(row.dataset.id);
    }
    DetalheAtendimentoVM.prototype.eventComboStatus = function () {
        var self = this;

        var valorOriginal = null;
        self.htmlObjects.io.comboStatus.on('shown.bs.select', function () {
            valorOriginal = $(this).find('option:selected');
        }).on('changed.bs.select', function () {
            var objeto = $(this);
            var novoValor = $(this).find('option:selected');
            var config = { botoes:[ {label:'Alterar Status',callback:"callback"},
                    {label:'Cancelar',callback:"callback"}
                ]};
            var isCancelado = false;
            self.modalApi.showTemplateModal(ModalAPI.PRIMARY, config, $('#modal-custom')).onLoaded(function (modalBody) {
                modalBody.find('[name="mensagem"]').ckeditor();
            }).onClick('Alterar Status', function (response) {
                isCancelado = false;
                response['status'] = parseInt(novoValor[0].value);

                // Verifica se a mensagem não é nula com excessão do status 1 que não é exigido
                if ( response['status']!==1 && !response['mensagem'] ) {
                    self.modalApi.notify(ModalAPI.WARNING, 'Atendimento', 'A alteração para o status "'+ novoValor[0].text +'" exige que se entre com um comentário para o atendimento');
                    objeto.val(valorOriginal.val());
                    objeto.selectpicker('refresh');
                } else {
                    // Altera o status e adiciona histórico no atendimento
                    var atendimentoId = sessionStorage.getItem('idAtendimento');
                    if (atendimentoId) {
                        self.alterarStatus(atendimentoId, novoValor[0].text, response, objeto, valorOriginal);
                    }
                }
            }).onClick('Cancelar', function () {
                isCancelado = true;
            }).onClosed(function () {
                if (isCancelado) {
                    self.retornarValorOriginal(objeto, valorOriginal.val(), 'Não foi efetuada nenhuma alteração no responsável do atendimento');
                }
            });
        });
    }
    DetalheAtendimentoVM.prototype.eventDropzoneAddFile = function () {
        $(".dropzone.dz-started .dz-message").css({
            'opacity': 0
        });
    }
    DetalheAtendimentoVM.prototype.eventDropzoneSuccess = function (file) {
        var self = this;

        console.log(file);

        $(file.previewElement).fadeOut(function () {
            $(".dropzone.dz-started .dz-message").css({
                'opacity': 1
            });
        });

        // get server response

        if (file.xhr.response) {
            var data = JSON.parse(file.xhr.response);

            // adicionar nova linha ao histórico
            self.reloaderHistoricos(data.historicos);

            // atualiza listagem de arquivos
            self.gridAnexos.loadDataFromURL();
        }

        self.initLinksFiles();
    }

}

// Inits
{
    DetalheAtendimentoVM.prototype.init = function () {
        var self = this;

        self.atendimentoId = sessionStorage.getItem("idAtendimento");
        /*
            TODO Remover id do atendimento da sessão
                Remover id do atendimento da sessão após finalizar o trabalho, assim o usuário não poderá voltar
                a esta pagina com os dados já pré enviados. Ele terá que por obrigação clicar novamente
                sobre um atendimento para ver os detalhes.
         */

        if (!self.atendimentoId) {
            self.modal.notify(
                ModalAPI.ERROR,
                'Atendimento',
                'Selecione um atendimento!');

            location.href = "/ciente/grid";
        }

        self.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;

        // capta todos os elementos htmls utilizados para controle da página
        self.initHTMLObjects();

        // Load plugins
        self.initPlugins();

        // get data
        self.initLoadData();

        // inicializa grid histórico
        self.initGridHistorico();

        // inicia grid de anexos
        self.initDataGridAnexos();

        // init renders
        self.initRender();

        // init combo status event
        self.eventComboStatus();

        // call events
        self.events();

    }
    DetalheAtendimentoVM.prototype.initHTMLObjects = function() {
        this.htmlObjects = {
            'solicitacaoNumero': $('#solicitacao-numero'),
            'solicitacaoDescricao': $('#solicitacao-descricao'),
            'solicitacaoAtendimentosTable': $("#solicitacao-atendimentos-table"),
            'selectFiltroStatus': $('#selectFiltroStatus'),
            'solicitacaoTempo': {
                'horas': $('#solicitacao-horas'),
                'minutos': $('#solicitacao-minutos'),
                'segundos': $('#solicitacao-segundos')
            },
                'detalhesSolicitacao': {
                'status': $("#detalhes-solicitacao-status"),
                'atualizacao': $("#detalhes-soliciticao-atualizacao"),
                'responsavel': $("#detalhes-solicitacao-responsavel"),
                'criacao': $("#detalhes-solicitacao-criacao"),
                'solicitante': $("#detalhes-solicitacao-solicitante"),
                'solicitanteSec': $("#detalhes-solicitacao-solicitante-sec"),
                'equipamento': $("#detalhes-solicitacao-equipamento"),
                    'produto': $("#detalhes-solicitacao-produto")
            },
                'io': {
                'comboEquipe': $("#comboEquipe"),
                'comboStatus': $("#comboStatus"),
                'comboStatusTransferir': $("#cmb-status-transferir"),
                'comboPrioridade': $("#cmb-prioridade"),
                'comment': $("#editor"),
                'commentTransfer': $("#editor-message-transfer"),
                'button': {
                    'transfer': $("#btn-transfer"),
                    'concluirTransferencia': $("#btn-concluir-transferencia"),
                    'comment': $("#btn-comment"),
                    'btnAtendimentoAssumir': $("#btnPegarSolicitacao"),
                    'checkboxTransferirManter': $("#checkbox-transferir-manter")
                },
            },
            'panelTransfer': {
                'panel': $("#encaminharAtendimento")
            }
        };
    }
    DetalheAtendimentoVM.prototype.initPlugins = function () {
        var self = this;
        $('.editor').ckeditor();
        this.htmlObjects.io.comboPrioridade.selectpicker();

        // $('select').select2({minimumResultsForSearch: Infinity});
        // init selects
        self.modalApi = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
        $("dropzone_multiple").dropzone();

    }
    DetalheAtendimentoVM.prototype.initLoadData = function () {
        var self = this;
        self.getInformacoesAtendimento();
    }
    DetalheAtendimentoVM.prototype.initLinksFiles = function () {
        var self = this;

        $(".anexo-name a").off();
        $(".anexo-name a").click(function (e) {
            e.preventDefault();
            var filename = $(this).attr('href');

            var listObject = document.getElementById(filename.substr(1, filename.length));

            var newValue = parseInt($(listObject).offset().top);

            console.log(newValue);


            $('html, body').css({
                width: 100 + '%',
                height: 100 + '%',
                margin: 0,
                display: 'inline-block'
            }).animate({
                scrollTop: newValue
            }, 1000);
        });

        $("#list-files .media").each(function () {

            var media = this;
            $(media).find(".link-remove-file").off();
            $(media).find(".link-remove-file").click(function (e) {
                e.preventDefault();
                var file_id = this.dataset.id;

                var atendimento_id = self.getDataSession().id;

                self.restClient.setMethodDELETE();
                self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/' + atendimento_id + '/anexos/' + file_id);
                self.restClient.setSuccessCallbackFunction(function (data) {
                    // faz um reload nos historicos
                    self.reloaderHistoricos(data.historicos);

                    self.modalApi.notify(
                        ModalAPI.SUCCESS, "Remover anexo", "Anexo removido com sucesso!"
                    );

                });

                self.restClient.setErrorCallbackFunction(function (jqXHR) {
                    self.modal.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK")
                        .onClick(function () {
                            $(media).fadeIn();
                        });
                });

                self.modalApi.showConfirmation(ModalAPI.WARNING,
                    "Remover anexo",
                    "Deseja realmente remover este anexo?",
                    "Sim",
                    "Não").onClick("sim", function (){

                        // remove arquivo da lista
                        $(media).fadeOut();

                    self.restClient.exec();
                });

            });
        });
    }
    DetalheAtendimentoVM.prototype.initGridHistorico = function () {
        var self = this;

        ModernDataGrid.prototype.loadFromJson = function (arr) {
            var self = this;

            if (arr instanceof Array && arr.length > 0) {
                self._recordsFiltered = arr.length;
                self.dataLoaded['data'] = arr;
            } else {
                self.dataLoaded['data'] = [{
                    'tipo': 'empty',
                    'historico': "Ainda não há comentários neste atendimento"
                }];
            }

            if (this.onLoadSuccess) {
                this.onLoadSuccess(arr);
            }

            self.draw();
        };

        this.gridHistorico = new ModernDataGrid('historico-container', 'template-historico');
        this.gridHistorico.onLoadSuccess = function (data) {
            self.initLinksFiles();
        }

        this.gridHistorico.onItemDraw = function (template, item) {
            if (item.tipo == 'A') {
                var s = '<div class="historico-action media-heading">';
                s += '<span class="historico-mensagem"></span>';
                s += '<span class="media-annotation dotted historico-horario"></span>';
                s += '</div>';
                template.html(s);
            } else if (item.tipo == "empty") {
                template.html('<p class="historico-mensagem"></p>');
            }
        }

        this.gridHistorico.setupDisplayItems([
            {
                'key': 'historico', 'target': 'historico-mensagem', render: function (html, template, item) {
                return html;
            }
            },
            {
                'key': 'data', 'target': 'historico-horario', render: function (data) {
                    return self.getMomentFormat(data);
            }
            },
            {
                'key': 'nomePessoa', 'target': 'historico-u-nome', render: function (data) {
                return getFullName(data);
            }
            },
            {
                'key': 'imagemPessoa', 'target': 'historico-u-imagem', 'tag': 'IMG'
            }
        ]);

    }
    DetalheAtendimentoVM.prototype.initDataGridAnexos = function () {
        var self = this;
        this.gridAnexos = new ModernDataGrid('list-files', 'template-list-files');
        this.gridAnexos.onLoadSuccess = function (data) {
            self.initLinksFiles();
        }
        this.gridAnexos.setMethodGET();
        this.gridAnexos.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/' + self.atendimentoId + '/anexos');
        this.gridAnexos.loadDataFromURL();
        this.gridAnexos.setupDisplayItems([
            {
                'key': 'nome', 'target': 'file-name', "render": function (val, template, item) {
                    template.attr("id", item.nomeFisico);
                    return '<a href="' + item.url +'" class="media-heading text-semibold">' + val + '</a>';
                }
            },
            {
                'key': 'idAtendimentoHistorico', 'target': 'li-remove-file', "render": function (val, template, item) {
                    if (item.deletable) {
                        return '<a href="#" class="link-remove-file" data-id="' + val + '"><i class="icon-trash"></i></a>';
                    }
                    return '';
                }
            },
            {
                'key': 'extensao', 'target': 'icon', "render": function (val, template, item_data) {
                    var item = template.find('.icon');
                    item.removeClass('icon-file-word');

                    var extensao = item_data['extensao'];

                    /**
                     *
                     PDF - icon-file-pdf
                     Excel - icon-file-stats
                     Word - icon-file-word
                     PPT(Power Point) - icon-file-presentation
                     TXT - icon-file-text2
                     ZIP, RAR - icon-file-zip
                     HTML, PHP, ASP e XML - icon-file-xml
                     JS e CSS - icon-file-css
                     Video(MP4, WMV, MOV, AVI) - icon-file-video
                     Audio(MP3, WAV) - icon-file-music
                     Imagem (GIF, JPG, PNG, BMP) - icon-file-picture

                     */

                    var icons = {
                        'zip,rar': 'zip',
                        'php,html,asp,xml': 'xml',
                        'pdf': 'pdf',
                        'css,js': 'css',
                        'xls,xlsx,xlsm,csv,ods': 'stats',
                        'ppt,pptx,odp': 'presentation',
                        'doc,docx,dot,odt': 'word',
                        'mp4,wmv,mov,avi': 'video',
                        'mp3,ogg,wav': 'music',
                        'txt,rtf': 'text2',
                        'jpg,jpeg,png,gif,bmp': 'picture',
                    };

                    var ok = false;

                    for (key in icons) {
                        var values = key.split(',');

                        if (values instanceof Array) {
                            for (var i = 0; i < values.length; i++) {
                                if (values[i] == extensao) {
                                    item.addClass('icon-file-' + icons[key]);
                                    ok = true;
                                    break;
                                }
                            }
                        }
                    }

                    if (!ok) {
                        item.addClass('icon-file-empty');
                    }

                }
            },
            {
                'key': 'size', 'target': 'file-size', "render": function (val, template, item) {
                    val = parseInt(val);

                    if (val != -1) {
                        var base = 1000;
                        var medida = "KB";

                        if (val > base && val < Math.pow(base, 2)) {
                            val = val / base;
                        } else if (val > Math.pow(base, 2) && val < Math.pow(base, 3)) {
                            val = val / Math.pow(base, 2);
                            medida = "MB";
                        }
                        else if (val > Math.pow(base, 3)) {
                            val = val / Math.pow(base, 3);
                            medida = "MB";
                        } else if (val < base) {
                            medida = "Byte";
                        }


                        return '<span>Size: ' +  val + ' ' + medida + '</span>';
                    }
                }
            },
            {
                'key': 'nomePessoa', 'target': 'file-by', "tag": "span"
            }
        ]);

    }
    DetalheAtendimentoVM.prototype.initRender = function () {
    };
    DetalheAtendimentoVM.prototype.initDropzone = function () {
        var self = this;
        $("#dropzoneMultiple").dropzone({
            'url': '/ciente/api/atendimento/' + self.getDataSession().id + '/upload',
            'paramName': 'arquivo',
            'method': 'post',
            'headers': {
                'Authorization': 'Bearer ' + sessionStorage.YWNjZXNzX3Rva2Vu
            },
            'acceptFiles': 'image/*,application/pdf,.psd',
            'init': function () {
                this.on("addedfile", function (file) {
                    self.eventDropzoneAddFile(file);
                });

                this.on("success", function (file) {
                    self.eventDropzoneSuccess(file);
                });
            }
        }).addClass("dropzone");

    }
    DetalheAtendimentoVM.prototype.listarEquipe = function () {
        var self = this;

        self.datagridEquipe = new ModernDataGrid('listaUsuarios', 'media-usuario');
        self.datagridEquipe.setupDisplayItems([
            {
                "key": 'nome', "target": 'data-nome', 'render': function (nome) {
                return nome.split(' ')[0] + ' ' + nome.split(' ')[nome.split(' ').length - 1];
            }
            },
            {"key": 'cargo', "target": 'data-cargo', "tag": 'SPAN'},
            {"key": 'imagem', "target": 'data-avatar', "tag": 'IMG'},
            {'key': 'atendimentos', 'target': 'data-badge', 'tag': 'SPAN'}
        ]);
        self.datagridEquipe.setWebServiceURL(self.webserviceCienteBaseURL + 'usuarios/setor/' + self.getDataSession().idSetor);
        self.datagridEquipe.setMethodGET();
        self.datagridEquipe.setOnLoadSuccessCallback(function () {
            self.renderListaEquipeTemplate(self.datagridEquipe.dataLoaded);
        });
        self.datagridEquipe.setOnLoadErrorCallback(function () {
            self.renderListaEquipeTemplate([]);
        });
        self.datagridEquipe.load();
    };
    DetalheAtendimentoVM.prototype.reInit = function () {
        this.emptyForms();
        this.init();
    }
}

// Renders
{
    DetalheAtendimentoVM.prototype.render = function () {
        var self = this;

        // get data save in session
        self.renderNumeroSolicitacao();
        self.renderSelectStatus();
        self.renderTableAtendimentos();
        self.renderDetalhesSolicitacao();
        self.renderGridHistorico();
        self.listarEquipe();
        self.renderSelectTransferStatus();
        self.renderComboPrioridade();

        var timerId = setInterval(function () {
            if (self.renderTimeShow) {
                self.renderTempoSolicitacao();
            } else {
                clearInterval(timerId);
            }
        }, 800);

        self.afterRender();

    }
    DetalheAtendimentoVM.prototype.renderNumeroSolicitacao = function () {
        var data = this.getDataSession();
        this.htmlObjects.solicitacaoNumero.html("#" + data.ano + "." + data.numero);
        this.htmlObjects.solicitacaoDescricao.html(data.descricao);
    }
    DetalheAtendimentoVM.prototype.renderTableAtendimentos = function () {
        var self = this;
        var data = self.getDataSession();

        var tbody_old = self.htmlObjects.solicitacaoAtendimentosTable[0].getElementsByTagName("tbody");

        if (tbody_old.length > 0) {
            this.htmlObjects.solicitacaoAtendimentosTable[0].removeChild(tbody_old[0]);
        }

        var tbody = document.createElement('tbody');
        for (var i = 0; i < data.solicitacaoAtendimentos.length; i++) {
            var line = data.solicitacaoAtendimentos[i];
            var row = document.createElement('tr');
            var id = document.createElement('td');
            var setor = document.createElement('td');
            var responsavel = document.createElement('td');
            var dataEncaminhamento = document.createElement('td');
            var status = document.createElement('td');

            row.dataset.id = line.id;

            if (this.atendimentoId == line.id) {
                row.className = "success";
            } else {
                var parent = row;
                row.addEventListener('click', function (evt) {
                    self.eventTableAtendimentoRowClick(this, evt, self);
                });
            }

            setor.title = line.nomeSetor;

            $(id).html(line.id);
            $(setor).html(addValueOrDefault(line.siglaSetor));
            $(responsavel).html(addValueOrDefault(getFullName(line.nomeResponsavel)));
            $(dataEncaminhamento).html(addValueOrDefault(self.getMomentFormat(line.dataEncaminhamento)));
            $(status).html(data.lista_status[line.status] );

            row.appendChild(id);
            row.appendChild(setor);
            row.appendChild(responsavel);
            row.appendChild(dataEncaminhamento);
            row.appendChild(status);

            tbody.appendChild(row);
        }
        this.htmlObjects.solicitacaoAtendimentosTable[0].appendChild(tbody);
    }
    DetalheAtendimentoVM.prototype.renderSelectStatus = function() {
        var data = this.getDataSession();
        this.htmlObjects.io.comboStatus.empty();

        for (var i = 0; i < data.lista_status.length; i++) {
            var opt = document.createElement("option");

            if (i == data.status) {
                opt.selected = true;
            }

            if (i == 0) {
                opt.disabled = true;
            }

            opt.value = i;
            $(opt).html(data.lista_status[i]);
            this.htmlObjects.io.comboStatus[0].appendChild(opt);
        }

        this.htmlObjects.io.comboStatus.selectpicker("refresh");
    }
    DetalheAtendimentoVM.prototype.renderSelectTransferStatus = function () {
        var data = this.getDataSession();
        var combo = this.htmlObjects.io.comboStatusTransferir;

        combo.empty();

        for (var i = 0; i < data.lista_status.length; i++) {

            if (i < 3) {
                continue;
            }

            var opt = document.createElement("option");

            opt.value = i;
            $(opt).html(data.lista_status[i]);
            combo[0].appendChild(opt);
        }

        combo.selectpicker("refresh");

    }
    DetalheAtendimentoVM.prototype.renderTempoSolicitacao = function () {
        var data = this.getDataSession();

        var date_init = new Date(data.dataEncaminhamento);
        var date_now  = new Date();

        var diff = moment(date_now).workingDiff(moment(date_init)) / 1000; // diferença em milisegundos para segundos

        var hours = parseInt(diff / 3600); rest_hours = diff % 3600;
        var minutes = parseInt(rest_hours / 60); var rest_minutes = rest_hours % 60;
        var seconds = parseInt(rest_minutes);


        this.htmlObjects.solicitacaoTempo.horas.html(addZeroEsquerda(hours));
        this.htmlObjects.solicitacaoTempo.minutos.html(addZeroEsquerda(minutes));
        this.htmlObjects.solicitacaoTempo.segundos.html(addZeroEsquerda(seconds));

    }
    DetalheAtendimentoVM.prototype.renderDetalhesSolicitacao = function () {
        var data = this.getDataSession();

        // this.htmlObjects.detalhesSolicitacao.status.html();

        // ultima atualizaçao
        if (data.historicos.length) {
            var ultimo_historico = data.historicos[0];
            this.htmlObjects.detalhesSolicitacao.atualizacao.html(this.getMomentFormat(ultimo_historico.data));
        }

        this.htmlObjects.detalhesSolicitacao.criacao.html(this.getMomentFormat(data.dataAbertura));

        this.htmlObjects.detalhesSolicitacao.responsavel.html(addValueOrDefault(getFullName(data.nomeResponsavel)));
        this.htmlObjects.detalhesSolicitacao.solicitante.html(addValueOrDefault(getFullName(data.nomeSolicitante)));
        this.htmlObjects.detalhesSolicitacao.solicitanteSec.html(addValueOrDefault(getFullName(data.nomeSolicitanteSecundario)));
        this.htmlObjects.detalhesSolicitacao.equipamento.html(addValueOrDefault(data.equipamento));

    }
    DetalheAtendimentoVM.prototype.renderGridHistorico = function () {
        var data = this.getDataSession();

        if (!data.historicos) {
            data.historicos = [];
        }

        this.gridHistorico.loadFromJson(data.historicos);



    }
    DetalheAtendimentoVM.prototype.renderListaEquipeTemplate = function (dados) {
        var self = this;
        var cmbResponsavel = this.htmlObjects.io.comboEquipe;
        cmbResponsavel.empty();
        cmbResponsavel.selectpicker("destroy");

        if (dados.data.length) {

            var option = $('<option>', {
                value: "",
                text: "Selecionar Responsável"
            });

            cmbResponsavel.append( option );

            $.each(dados.data, function (idx, item) {

                var option = $('<option>', {
                    value: item['idPessoa'],
                    text: item['nome'],
                    'data-content': '<i class="icon-user"></i> '+ item['nome']
                });

                if (item['idPessoa'] == self.getDataSession().idUsuario) {
                    option[0].selected = true;
                }

                cmbResponsavel.append( option );
            });
        }

        cmbResponsavel.selectpicker();
        cmbResponsavel.on("changed.bs.select", function () {
            self.eventCmbEquipeChanged();
        });

    };
    DetalheAtendimentoVM.prototype.renderComboPrioridade = function () {
        var data = this.getDataSession();
        var solicitacao_prioridade = data.prioridade;

        this.htmlObjects.io.comboPrioridade.find("option").each(function () {
            if (this.value == solicitacao_prioridade) {
                this.selected = true;
            }
        });

        this.htmlObjects.io.comboPrioridade.selectpicker("refresh");
    }
    DetalheAtendimentoVM.prototype.afterRender = function () {
        var self = this;
        self.initDropzone();
        self.initLinksFiles();
    }
}

// Data
{
    DetalheAtendimentoVM.prototype.saveDataSession = function (data) {
        sessionStorage.setItem(this.dadosAtendimentoSessionKeyName, JSON.stringify(data));
    }
    DetalheAtendimentoVM.prototype.getDataSession = function () {
        return JSON.parse(sessionStorage.getItem(this.dadosAtendimentoSessionKeyName));
    }
    DetalheAtendimentoVM.prototype.getInformacoesAtendimento = function () {
        var self = this;

        self.customLoading.show();
        self.restClient.setMethodGET();
        self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'atendimento/' + self.atendimentoId);
        self.restClient.setSuccessCallbackFunction(function (data) {
            // desbloquia ações
            self.desbloquearAcao();

            self.customLoading.hide();
            // save data from sessionStorage
            self.saveDataSession(data);
            // init draw
            self.render();

        });

        self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
            self.customLoading.hide();
            self.modal.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");

            // informa sobre erro e tenta novamente
            // self.modal.notify(ModalAPI.DANGER, "Problema com a solicitação, tentaremos realizar a requisição novamente");

        });

        self.bloquearAcao();
        self.restClient.exec();
    }
}

// Empty data form
{
    DetalheAtendimentoVM.prototype.emptyForms = function () {

    }
}

var app;
$(function () {
    (app = new DetalheAtendimentoVM()).init();
});


