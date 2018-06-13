<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ciente - Jubarte - Prefeitura Municipal de Rio das Ostras - RJ</title>

    <!-- Global stylesheets -->
    <link href="/cdn/Assets/fonts/material-icons/material-icons.css" rel="stylesheet">
    <link href="/cdn/Assets/fonts/roboto/roboto.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/core.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/components.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/jquery_ui/widgets.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/selects/select2.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/app.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/notifications/jgrowl.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ui/ripple.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/jquery.mask/1.14.11/jquery.mask.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/select2/4.0.6/select2.min.js"></script>
    <!-- /theme JS files -->


    <link href="/cdn/Vendor/bootstrap-datepicker/1.7.1/bootstrap-datepicker.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/cdn/Vendor/bootstrap-datepicker/1.7.1/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.pt-BR.min.js"></script>


    <link href="/cdn/Assets/css/jSwitch.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/jubarteStyle.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/modernDataTable.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/ModernTreeView.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/moment.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/locale/pt-br.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/pickers/pickadate/picker.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/pickers/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/pickers/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/pickers/pickadate/pt_BR.js"></script>

    <script type="text/javascript" src="/cdn/utils/utils.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModalAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernBlockUI.js"></script>
    <script type="text/javascript" src="/cdn/utils/LoaderAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/RESTClient.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernTreeView.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernDataTable.js"></script>

    <script type="text/javascript" src="ViewModel/Constants.js"></script>
    <script type="text/javascript" src="ViewModel/GerenciarSetoresViewModel.js"></script>
</head>
<body class="sidebar-detached-hidden">


<div class="sidebar-xs has-detached-right">
    <!-- Main content -->
    <div class="containerInsideIframe">
        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <!-- Main content -->
                <div class="content-wrapper">

                    <!-- Page header -->
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">
                                <h4><i class="icon-grid position-left"></i> <span class="text-semibold">Setores</span></h4>
                                <ul class="breadcrumb position-right"></ul>
                            </div>
                            <div class="heading-elements">
                                <button id='btnOpenArvoreSetor' class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-list"></i></b>Listar Setor</button>
                                <button id='btnOpenHistorico' class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-history"></i></b>Histórico</button>
                                <button id='btnOpenCadastro' class="btn bg-indigo btn-labeled heading-btn legitRipple"><b><i class="icon-file-plus"></i></b>Cadastrar Setor</button>
                            </div>
                        </div>
                    </div>
                    <!-- /page header -->

                    <!-- Content area -->
                    <div class="content">

                        <div id="accordion-controls" class="panel-group accordion-sortable content-group-lg ui-sortable">
                            <div class="panel panel-white">
                                <a><!--  data-toggle="collapse" data-parent="#accordion-controls" href="#blocoRelacaoSetoresCadastrados" aria-expanded="true" -->
                                    <div class="panel-heading border3-darkOrange custom" id="headingListaSetores">
                                        <h6 class="panel-title">
                                            Listar setores cadastrados
                                        </h6>
                                        <div class="heading-elements">
                                            <form class="form-group text-right">
                                                <i class="icon-calendar2"></i>
                                                <input id="inputFiltroData" type="button" class="btn bg-green heading-btn text-regular" />
                                            </form>
                                        </div>
                                    </div>
                                </a>
                                <div id="blocoRelacaoSetoresCadastrados" class="panel-collapse collapse in" aria-expanded="true" style="">
                                    <div class="panel-body border3-orange">
                                        <!-- SETORES -->
                                        <div class="tab-pane has-padding active" id="tab-setores">
                                            <div id="treeViewContainer" class="mtvContainer"></div>
                                        </div>
                                        <!-- /SETORES -->
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-white" id="painelHistoricoSetores">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoHistoricoSetores" aria-expanded="false">
                                    <div class="panel-heading border3-darkPrimary">
                                        <h6 class="panel-title">
                                            Histórico
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoHistoricoSetores" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="panel-body border3-primary">
                                        <!-- HISTORICO -->
                                        <div class="tab-pane has-padding" id="tab-historico">
                                            <!-- ModernDataTable -->
                                            <div class="modernDataTable">
                                                <table id="tableListSetores" class="">
                                                    <thead>
                                                    <tr>
                                                        <th>Setor Pai</th>
                                                        <th>Data início</th>
                                                        <th>Sigla</th>
                                                        <th>Nome</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                                <div class="alert alert-info alert-styled-left">
                                                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                                    * Para visualizar o histórico, favor <span class="text-semibold">selecionar</span> algum setor cadastrado.
                                                </div>
                                            </div><!-- Fim ModernDataTable -->
                                        </div>
                                        <!-- /HISTORICO -->
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-white">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoDetalhesSetoresCadastrados" aria-expanded="false">
                                    <div class="panel-heading border3-darkIndigo">
                                        <h6 class="panel-title">
                                            Cadastrar Setor
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoDetalhesSetoresCadastrados" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="panel-body border3-indigo">
                                        <!-- DETALHES -->
                                        <div class="tab-pane has-padding" id="tab-detalhes">
                                            <!-- Form horizontal -->
                                            <p class="content-group-lg">Todos os campos marcados com (<code>*</code>) são requeridos e devem ser preenchidos</p>
                                            <div class="form-horizontal" id="formCadSetores">
                                                <fieldset class="content-group">
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-2">Setor pai</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group">
                                                                <input name="idPai" type="text" class="form-control cursor-pointer" placeholder="Setor pai" readonly>
                                                                <span id="icoEraser" class="input-group-addon cursor-pointer" title="Apagar"><i class="icon-eraser"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-sm-2">Data início </label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group">
                                                                <input name="dataInicio" type="text" class="form-control mskData" placeholder="Data início">
                                                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-sm-2">Sigla <span class="text-danger">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input name="sigla" type="text" class="form-control text-uppercase" placeholder="Sigla do setor" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-sm-2">Nome <span class="text-danger">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input name="nome" type="text" class="form-control" placeholder="Nome do setor" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-sm-2">Ativo</label>
                                                        <div class="col-sm-10">
                                                            <div class="jSwitch jSwitchAjustes">
                                                                <label>
                                                                    <input name="ativo" type="checkbox" checked>
                                                                    <span class="jThumb"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <div class="text-right">
                                                    <button name="btnSalvarServico" class="btn btn-primary btn-sm btn-labeled btn-labeled-right heading-btn">Salvar <b><i class="icon-arrow-right14"></i></b></button>
                                                </div>
                                            </div>
                                            <!-- /Form horizontal -->
                                        </div>
                                        <!-- /DETALHES -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /content area -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

            <!-- Footer -->
            <div class="footer text-muted"> </div>
            <!-- /footer -->

        </div>
        <!-- /page container -->
    </div>
</div>

<!-- Modais -->
<div id="defaultModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-large">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Selecione o Setor</h6>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- /Modais -->

</body>
</html>