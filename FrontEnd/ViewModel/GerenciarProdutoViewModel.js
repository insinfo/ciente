$(function () {
    var gerenciarProdutoVM = new GerenciarProdutoVM();
    gerenciarProdutoVM.init();
});

function GerenciarProdutoVM() {
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.customLoading = new LoaderAPI();
    this.restClient = new RESTClient();
    this.validaPForm = new FormValidationAPI('formProduto');
    this.modalAPI = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.mainNavbar = window.location != window.parent.location ? window.parent.getMainNavbar() : null;

    //FORMULARIO CADASTRO DE TIPO DE EQUIPAMENTO
    this.formProduto = $('#formProduto');
    this.idProduto = null;
    this.idSetor = $('#idSetor');
    this.nomeReduzido = $('#nomeReduzido');
    this.descricao = $('#descricao');
    this.origem = $('#origem');
    this.ativo = $('#ativo');
    this.btnCadProduto = $('#btnCadProduto');

    this.idPessoa = sessionStorage.getItem('idPessoa'); //usuario logado
    //this.idPessoa = 4;


    //LISTA Produto
    this.tableProduto = $('#tableProduto');
    this.datatableProduto = new ModernDataTable('tableProduto');
}

GerenciarProdutoVM.prototype.init = function () {
    var self = this;
    // Load de plugins
    $('select').select2();
    // Carregamento
    self.getSetores(); //combo
    self.events();
    self.getProduto(); //datagrid
};

GerenciarProdutoVM.prototype.events = function () {
    var self = this;
    //evento salvar cadastro
    self.btnCadProduto.click(function () {
        self.save();
    });


    self.datatableProduto.setOnClick(function (data) {
        //console.log(data);
        self.fillForm(data);
    });


    //preencher FORM com os dados do registro a ser editado (clicando no botao de edicao)
    $('#tableProduto').on('click', '.btnEditar', function () {
        var indice = $(this).parents('tr').attr('data-index'); //pegar o indice da linha para edicao.
        var data = self.datatableProduto.getData()[indice]; //obter os dados da linha clicada
        self.fillForm(data);
    });
};
GerenciarProdutoVM.prototype.updateFields = function () {
    var self = this;
    $('select').select2();
};
GerenciarProdutoVM.prototype.resetForm = function (data) {
    var self = this;
    self.idProduto = null;
    self.idSetor.val('');
    self.nomeReduzido.val('');
    self.descricao.val('');
    self.origem.val('');
    self.ativo.prop('checked', true);
    self.updateFields();
};
GerenciarProdutoVM.prototype.fillForm = function (data) {
    var self = this;
    self.idProduto = data['id'];
    self.origem.val(data['origem']);
    self.idSetor.val(data['idSetor']);
    self.nomeReduzido.val(data['nomeReduzido']);
    self.descricao.val(data['descricao']);
    self.ativo.prop('checked', data['ativo']);
    self.updateFields();

    //abrir o form e colocar o foco no select
    $('#panel-form').trigger('click');
    self.nomeReduzido.focus();

};
GerenciarProdutoVM.prototype.getProduto = function () {
    var self = this;

    var columnsConfiguration = [
        /*
            {"key": "ativo","render":function(row){
                    return "<a><i class='btnEditar glyphicon glyphicon-edit'></i></a>";
                }
            },*/
        {"key": "id"},
        //{"key": "idSetor"},
        {"key": "sigla"},
        {"key": "nomeReduzido"},
        {"key": "descricao"},
        {
            "key": "origem", "render": function (data) {
                if (data.origem == 'P') {
                    return 'Próprio'
                }
                else if (data.origem == 'T') {
                    return 'Terceiro'
                }
                else if (data.origem == 'G') {
                    return 'Governo'
                }
                else if (data.origem == 'O') {
                    return 'Outros'
                }
            }
        },
        {"key": "ativo", "type": "boolLabel"}];

    self.datatableProduto.showActionBtnAdd();
    self.datatableProduto.setOnAddItemAction(function () {
        self.resetForm();
        $('#btn-cad').trigger('click');
        self.idSetor.focus();
    });


    self.datatableProduto.setDisplayCols(columnsConfiguration);
    self.datatableProduto.setIsColsEditable(false); //nao editar as linhas da grid
    //****self.datatableProduto.setDataToSender({'idSetor':'1'});

    self.restClient.setDataToSender({'idPessoa': self.idPessoa}); //console.log(self.idPessoa);

    //self.datatableProduto.setDataToSender({'idPessoa':self.idPessoa});
    self.datatableProduto.setSourceURL(self.webserviceCienteBaseURL + 'produtos');
    self.datatableProduto.setSourceMethodPOST();
    self.datatableProduto.load();

};
GerenciarProdutoVM.prototype.getSetores = function () {
    var self = this;
    self.customLoading.show();
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender({'idPessoa': self.idPessoa});
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'setores/usuario');
    self.restClient.setSuccessCallbackFunction(function (dados) {
        self.customLoading.hide();
        self.renderSelectSetores(dados);
    });
    self.restClient.setErrorCallbackFunction(function (response, status, callback) {
        self.customLoading.hide();
        self.modalAPI.showModal(ModalAPI.ERROR, "Conexão com Webservice", "Não foi possível acessar os dados do arquivo JSON", "OK").onClick(function () {
            console.log("callback do botao ok");
        });
    });
    self.restClient.exec();
};
GerenciarProdutoVM.prototype.renderSelectSetores = function (dados) {
    var self = this;
    dados = dados['data'];
    dados.forEach(function (item, index) {
        if (item['sigla'])
            var sigla = " (" + item['sigla'] + ") ";
        self.idSetor.append('<option value="' + item['idSetor'] + '">' + item['nome'] + sigla + '</option>');
        //self.selectSetor.append('<option value="' + item['id'] + '">' + item['sigla'] + ' - ' + item['nome'] + '</option>');
    });
};
GerenciarProdutoVM.prototype.validaForm = function () {
    var self = this;

    if (!self.validaPForm.validate(true, true)) {
        self.modalAPI.notify(ModalAPI.WARNING, 'Produto', 'É necessário entrar com os campos requeridos: ' + self.validaPForm.getInvalidFields());
        return false;
    }

    /*    if (!validarCamposRequeridos(self.formProduto)) {
            self.modalAPI.notify(ModalAPI.WARNING, 'Produto', 'É necessário entrar com os campos requeridos');
            return false;
        }
    */
    if (!self.idSetor.val()) {
        //self.modalAPI.showAlert("Selecione o setor!");
        //self.modalAPI.showModal(ModalAPI.WARNING, "", "Selecione o setor!",'OK');
        self.modalAPI.notify(ModalAPI.WARNING, "Setor", "Selecione o setor!");
        self.selectSetor.focus();
        return false;
    }

    if (!self.nomeReduzido.val()) {
        self.modalAPI.notify(ModalAPI.WARNING, "Nome", "Preencha !");
        self.nomeReduzido.focus();
        return false;
    }

    return true;
};
GerenciarProdutoVM.prototype.save = function () {
    var self = this;

    if (!self.validaForm()) {
        return false;
    }

    var id = self.idProduto ? self.idProduto : '';
    var dataToSender = self.formProduto.serializeJSON();

    self.customLoading.show();
    self.restClient.setDataToSender(dataToSender);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'produto/' + id);
    self.restClient.setMethodPUT();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.customLoading.hide();
        self.modalAPI.notify(ModalAPI.SUCCESS, "Produto", data['message']);
        self.datatableProduto.reload();
        if (self.idProduto != null) {
            $('#panel-datatables').trigger('click');
        }
        self.resetForm();

    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.customLoading.hide();
        self.modalAPI.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
    });
    self.restClient.exec();
};