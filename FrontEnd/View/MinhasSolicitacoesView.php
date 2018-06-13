<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ciente - Jubarte - Prefeitura Municipal de Rio das Ostras - RJ</title>

    <!-- ESTILO LIMITLESS -->
    <link href="/cdn/Assets/fonts/material-icons/material-icons.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/fonts/roboto/roboto.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/core.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/components.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/colors.css" rel="stylesheet" type="text/css">
    <!-- ESTILO LIMITLESS -->

    <!-- JS CORE LIMITLESS -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/selects/bootstrap_multiselect.js"></script>

    <!-- /JS CORE LIMITLESS -->

    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/notifications/jgrowl.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ui/ripple.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/bootstrap-select-1.12.4/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/bootstrap-select-1.12.4/defaults-pt_BR.min.js"></script>

    <!-- DEPENDEICIAS JUBARTE -->
    <link href="/cdn/Assets/css/jSwitch.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/jubarteStyle.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/modernDataTable.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/ModernTreeView.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/moment.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/locale/pt-br.js"></script>

    <!-- DEPENDECIAS DA VIEW MODEL -->
    <script type="text/javascript" src="/cdn/utils/utils.js"></script>
    <script type="text/javascript" src="/cdn/utils/jubarte.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModalAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernBlockUI.js"></script>
    <script type="text/javascript" src="/cdn/utils/LoaderAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/RESTClient.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernDataTable.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernTreeView.js"></script>

    <!-- VIEW MODEL -->
    <script type="text/javascript" src="ViewModel/Constants.js"></script>
    <script type="text/javascript" src="ViewModel/MinhasSolicitacoesViewModel.js"></script>

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
                    <div class="customPageHeader">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="row ">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                                        <h4>
                                            <i class="icon-grid position-left"></i>
                                            <span class="text-semibold">Minhas Solicitações</span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <ul class="breadcrumb">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 mt-15">
                                <div class="row text-right customBootstrapSelect" style="margin-right: 20px;">
                                    <a href="/ciente/novaSolicitacao" class="btn bg-blue btn-labeled heading-btn" name="btnCriar">
                                        <b><i class="icon-task"></i></b>
                                        Criar Solicitação
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /page header -->

                    <!-- Content area -->
                    <div class="content">

                        <!-- Detached content -->
                        <div class="container-detached">
                            <div class="content-detached">
                                <div class="panel panel-body no-padding">
                                    <!-- form filtros -->
                                    <div class="row p-20">
                                        <div class="col-md-4 col-lg-5">
                                            <label><span class="text-semibold">Pesquisar</span></label>
                                            <div class="has-feedback has-feedback-left">
                                                <input id="inputFiltroSearch" type="text" class="form-control"
                                                       placeholder="digite algo a ser pesquisado..." value="">
                                                <div class="form-control-feedback">
                                                    <i class="icon-search4 text-size-base text-muted"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group mb-5">
                                                <label><span class="text-semibold">Prioridade</span></label>
                                                <div class="multi-select-full">
                                                    <select id="selectFiltroPrioridade" class="multiselect-select-all-filtering" multiple="multiple" style="display: none;">
                                                        <option value="0">Urgente</option>
                                                        <option value="1">Alta</option>
                                                        <option value="2">Normal</option>
                                                        <option value="3">Baixa</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group mb-5">
                                                <label><span class="text-semibold">Status</span></label>
                                                <div class="multi-select-full">
                                                    <select id="selectFiltroStatus" class="multiselect-select-all-filtering" multiple="multiple" style="display: none;">
                                                        <option value="0">Aberto</option>
                                                        <option value="1">Em Andamento</option>
                                                        <option value="2">Pendente</option>
                                                        <option value="3">Concluído</option>
                                                        <option value="4">Cancelado</option>
                                                        <option value="5">Duplicado</option>
                                                        <option value="6">Sem Solução</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group mb-5">
                                                <label>
                                                    <span class="text-semibold">Setor(Responsável Atual)</span>
                                                </label>
                                                <input data-id="" readonly id="inputFiltroSetor" value="" data-toggle="modal" data-target="#modalSetor" type="text" class="form-control" placeholder="Selecione...">
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-lg-1 text-right">
                                            <div class="form-group mb-5">
                                                <button id="btnBuscar" class="btn bg-orange btn-icon legitRipple" title="Buscar">
                                                    <i class="icon-search4"></i>
                                                </button>
                                                <button id="btnReload" class="btn bg-default btn-icon legitRipple" title="Recarregar">
                                                    <i class="icon-reload-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /form filtros -->
                                    <!-- tabela lista solicitações  -->
                                    <div class="modernDataTable">
                                        <table id="tableListSolicitacao" class="tableFixPadding">
                                            <thead>
                                            <tr>
                                                <th class="pl-20">Número</th>
                                                <th>Descrição</th>
                                                <th style="text-align:center">Prioridade</th>
                                                <th style="text-align:center">Abertura</th>
                                                <th style="text-align:center">Status</th>
                                                <th style="text-align:center">Atendimentos</th>
                                                <th>Responsavel</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <!-- /tabela lista solicitações  -->
                            </div>
                        </div>
                        <!-- /detached content -->

                    </div>
                    <!-- /content area -->

                </div>
                <!-- /main content -->


            </div>
            <!-- /page content -->

            <!-- Footer -->
            <div class="footer text-muted"></div>
            <!-- /footer -->

        </div>
        <!-- /page container -->
    </div>
</div>

<!-- Modal Organograma -->
<div id="modalOrganograma" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-large">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Selecione o Organograma</h6>
            </div>
            <div class="modal-body">
                <div id="treeViewOrganograma" class="mtvContainer"></div>
            </div>
        </div>
    </div>
</div>
<!-- /Modal Organograma -->


<!-- Modal Setor -->
<div id="modalSetor" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-large">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Selecione o Setor</h6>
            </div>
            <div class="modal-body">
                <div id="treeViewSetor" class="mtvContainer"></div>
            </div>
        </div>
    </div>
</div>
<!-- /Modal Setor -->

</body>
</html>