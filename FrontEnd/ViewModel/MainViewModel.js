$(function () {
    var mainViewVM = new MainViewVM();
    mainViewVM.iniciar();
});

function MainViewVM() {
    this.restClient = new RESTClient();
    this.modalClient = window.location!=window.parent.location ? window.parent.getModal() : new ModalAPI();
    this.btnStatistcs = $('[name="btn-statistics"]');
    this.btnInvoices = $('[name="btn-invoices"]');
    // Metodos
    this.iniciar = function () {
        var self = this;
        // Eventos
        self.btnStatistcs.on('click', function () {
            self.restClient = new RESTClient();
            self.restClient.setMethodGET();
            self.restClient.setWebServiceURL('//jubarte.riodasostras.rj.gov.br/siscec/api/modalErro');
            self.restClient.setSuccessCallbackFunction(function () {
                self.modalClient.showAlert('Executado com sucesso');
            });
            self.restClient.setErrorCallbackFunction(function (callback) {
                self.modalClient.showModal(ModalAPI.ERROR, 'Duis eget', callback.responseJSON, 'Quisque').onClick(function () {
                    alert('Cliquei em Quisque');
                });
            });
            self.restClient.exec();
        });
        self.btnInvoices.on('click', function () {
            self.modalClient.notify(ModalAPI.SUCCESS, "Lorem Ipsum", "Curabitur non purus orci. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sagittis metus non lorem facilisis, nec laoreet urna malesuada.");
        });
    };
};