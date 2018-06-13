$(function () {
    var gerenciarEquipamentoTipoVM = new GerenciarEquipamentoTipoVM();
    gerenciarEquipamentoTipoVM.init();
});

function GerenciarEquipamentoTipoVM()
{
    this.webserviceCienteBaseURL = WEBSERVICE_CIENTE_BASE_URL;
    this.customLoading = new LoaderAPI();
    this.restClient = new RESTClient();
    this.modalClient = window.location != window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.mainNavbar = window.location != window.parent.location ? window.parent.getMainNavbar() : null;

    //FORMULARIO CADASTRO DE TIPO DE EQUIPAMENTO
    this.formTipoEquipamento = $('#formEquipamentoTipo');
    this.idTipoEquipamento = null;
    this.selectSetor = $('#selectSetor');
    this.inputNome = $('#inputNome');
    this.textareaDescricao = $('#textareaDescricao');
    this.checkAtivo = $('#checkAtivo');
    this.btnCadTipoEquipamento = $('#btnCadTipoEquipamento');

    this.idPessoa = sessionStorage.getItem('idPessoa'); //usuario logado
    //this.idPessoa = 4;


    //LISTA TIPO DE EQUIPAMENTO
    this.tableEquipamentoTipo = $('#tableEquipamentoTipo');
    this.datatableEquipamentoTipo = new ModernDataTable('tableEquipamentoTipo');
}

GerenciarEquipamentoTipoVM.prototype.init = function () {
    var self = this;
    // Load de plugins
    self.selectSetor.select2({
        //minimumResultsForSearch: Infinity
    });

    // Carregamento
    self.listarSetores(); //combo
    self.eventos();
    self.listaTipoEquipamento(); //datagrid
};

GerenciarEquipamentoTipoVM.prototype.atualizaCampos = function () {
    var self = this;
    self.selectSetor.select2({
        minimumResultsForSearch: Infinity
    });
};

GerenciarEquipamentoTipoVM.prototype.eventos = function () {
    var self = this;
    //evento salvar cadastro
    self.btnCadTipoEquipamento.click(function () {
        self.saveTipoEquipamento();
    });


    self.datatableEquipamentoTipo.setOnClick(function(data){
        //console.log(data);
        self.preencheForm(data);
    });


    //preencher FORM com os dados do registro a ser editado (clicando no botao de edicao)
    $('#tableEquipamentoTipo').on('click','.btnEditar',function(){
        var indice = $(this).parents('tr').attr('data-index'); //pegar o indice da linha para edicao.
        var data = self.datatableEquipamentoTipo.getData()[indice]; //obter os dados da linha clicada
        self.preencheForm(data);
    });
};

GerenciarEquipamentoTipoVM.prototype.limpaForm = function (data) {
    var self = this;
    self.idTipoEquipamento=null;
    self.selectSetor.val('');
    self.inputNome.val('');
    self.textareaDescricao.val('');
    self.checkAtivo.prop('checked',true);
    self.atualizaCampos();
};

GerenciarEquipamentoTipoVM.prototype.preencheForm = function (data) {
    var self = this;
    console.log(data);
    self.idTipoEquipamento=data['id'];
    self.selectSetor.val(data['idSetor']);
    self.inputNome.val(data['nome']);
    self.textareaDescricao.val(data['descricao']);
    self.checkAtivo.prop('checked',data['ativo']);
    self.atualizaCampos();

    //abrir o form e colocar o foco no select
    $('#panel-form').trigger('click');
    self.selectSetor.focus();

};

GerenciarEquipamentoTipoVM.prototype.listaTipoEquipamento = function () {
    var self = this;

    var columnsConfiguration = [
    /*
        {"key": "ativo","render":function(row){
                return "<a><i class='btnEditar glyphicon glyphicon-edit'></i></a>";
            }
        },*/
        {"key": "id"},
        //{"key": "idSetor"},
        {"key": "siglaSetor"},
        {"key": "nome"},
        {"key": "descricao"},
        {"key": "ativo","type":"boolLabel"}];

    self.datatableEquipamentoTipo.setDisplayCols(columnsConfiguration);
    self.datatableEquipamentoTipo.setIsColsEditable(false); //nao editar as linhas da grid
    //****self.datatableEquipamentoTipo.setDataToSender({'idSetor':'1'});

    //self.restClient.setDataToSender({'idPessoa':self.idPessoa}); //console.log(self.idPessoa);

    self.datatableEquipamentoTipo.setDataToSender({'idPessoa':self.idPessoa});
    self.datatableEquipamentoTipo.setSourceURL(self.webserviceCienteBaseURL + 'equipamentoTipo');
    self.datatableEquipamentoTipo.setSourceMethodPOST();
    self.datatableEquipamentoTipo.load();

};

GerenciarEquipamentoTipoVM.prototype.listarSetores = function () {
    var self = this;
    self.customLoading.show();
    self.restClient.setMethodPOST();
    //*self.restClient.setWebServiceURL('/ciente/Assets/setores.json');

    self.restClient.setDataToSender({'idPessoa':self.idPessoa}); //console.log(self.idPessoa);

    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL+'setores/usuario');
    self.restClient.setSuccessCallbackFunction(function (dados) {
        self.customLoading.hide();
        self.renderSelectSetores(dados);
    });
    self.restClient.setErrorCallbackFunction(function (response, status, callback) {
        self.customLoading.hide();
        self.modalClient.showModal(ModalAPI.ERROR, "Conexão com Webservice", "Não foi possível acessar os dados do arquivo JSON", "OK").onClick(function () {
            console.log("callback do botao ok");
        });
    });
    self.restClient.exec();
};

GerenciarEquipamentoTipoVM.prototype.renderSelectSetores = function (dados) {
    var self = this;
    dados = dados['data'];
    dados.forEach(function (item, index) {
        if(item['sigla'])
            var sigla = " ("+item['sigla']+") ";
        self.selectSetor.append('<option value="' + item['idSetor'] + '">' + item['nome'] + sigla + '</option>');
        //self.selectSetor.append('<option value="' + item['id'] + '">' + item['sigla'] + ' - ' + item['nome'] + '</option>');
    });
};

GerenciarEquipamentoTipoVM.prototype.validaForm = function () {
    var self = this;

    if (!self.selectSetor.val())
    {
        //self.modalClient.showAlert("Selecione o setor!");
        //self.modalClient.showModal(ModalAPI.WARNING, "", "Selecione o setor!",'OK');
        self.modalClient.notify(ModalAPI.WARNING, "Setor", "Selecione o setor!");
        self.selectSetor.focus();
        return false;
    }

    if (!self.inputNome.val())
    {
        self.modalClient.notify(ModalAPI.WARNING, "Nome", "Preencha !");
        self.selectSetor.focus();
        return false;
    }

    return true;
};

GerenciarEquipamentoTipoVM.prototype.saveTipoEquipamento = function () {
    var self = this;

    if (!self.validaForm())
    {
        return false;
    }
    var dataToSender = {
        "idSetor": self.selectSetor.val(), "nome": self.inputNome.val(), "descricao": self.textareaDescricao.val(), "ativo": self.checkAtivo.is(":checked")
      };

    var id = self.idTipoEquipamento ? self.idTipoEquipamento : '';
    //var dataToSender = self.formTipoEquipamento.serializeJSON();
    console.log(dataToSender);

    self.customLoading.show();
    self.restClient.setDataToSender(dataToSender);
    self.restClient.setWebServiceURL(self.webserviceCienteBaseURL + 'equipamentoTipo/' + id);
    self.restClient.setMethodPUT();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.customLoading.hide();
        //alert('Salvo com sucesso!');
        //self.modalClient.showModal(ModalAPI.SUCCESS, "Info", "Salvo com sucesso!", "OK");
        self.modalClient.notify(ModalAPI.SUCCESS, "Tipos de equipamentos", data['message']);
        self.limpaForm();
        self.datatableEquipamentoTipo.load();
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
