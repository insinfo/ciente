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

    <link href="/cdn/Assets/css/jSwitch.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/jubarteStyle.css" rel="stylesheet" type="text/css">
    <!--    <link href="Assets/css/cienteStyle.css" rel="stylesheet" type="text/css">-->

    <!-- Modern Datatables -->
    <link rel="stylesheet" type="text/css" href="/cdn/Assets/css/modernDataTable.css"/>
    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/moment.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/locale/pt-br.js"></script>
    <script type="text/javascript" src="/cdn/utils/utils.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModalAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/RESTClient.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernDataTable.js"></script>

    <!-- custom loading -->
    <script src="/cdn/utils/ModernBlockUI.js"></script>
    <script src="/cdn/utils/LoaderAPI.js"></script>

    <script type="text/javascript" src="ViewModel/Constants.js"></script>
    <script type="text/javascript" src="ViewModel/GerenciarEquipamentoViewModel.js"></script>

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
                                <h4>
                                    <i class="icon-grid position-left"></i>
                                    <span class="text-semibold">Equipamento</span>
                                </h4>

                                <ul class="breadcrumb position-right">

                                </ul>
                            </div>
                            <div class="heading-elements">
                                <select id='idSetoresUsuario' name='idSetoresUsuario' _class="select form-control" class="btn bg-purple btn-labeled heading-btn legitRipple">
                                </select>

                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" href="#blocoCadastrarEquipamento" aria-expanded="true" class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-file-plus"></i></b>Cadastrar</a>
                                <a id='panel-datatables' data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarEquipamento" aria-expanded="false" class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-list"></i></b>Listar</a>
                            </div>
                        </div>
                    </div>
                    <!-- /page header -->

                    <!-- Content area -->
                    <div class="content">

                        <div class="panel-group accordion-sortable content-group-lg ui-sortable" id="accordion-controls">
                            <div class="panel panel-white">
                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" href="#blocoCadastrarEquipamento" aria-expanded="true">
                                    <div class="panel-heading border3-darkOrange">
                                        <h6 class="panel-title">
                                            Cadastrar Equipamento
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoCadastrarEquipamento" class="panel-collapse collapse in" aria-expanded="true" style="">
                                    <div class="panel-body border3-orange">

                                        <!-- Form horizontal -->
                                        <div>
                                            <div class="panel-body">
                                                <p class="content-group-lg">Tela para cadastrar todos os equipamentos.</p>

                                                <form id='formEquipamento' class="form-horizontal" action="#" onSubmit='return false'>
                                                    <div class="form-horizontal">
                                                        <fieldset class="content-group">

                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Modelo do Equipamento<span class="text-danger">*</span></label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <select id="idModeloEquipamento" name="idModeloEquipamento" class="select" data-placeholder="Selecione o modelo do equipamento" required> <!--form-control-->
                                                                        <!--<option disabled selected>Selecione</option>-->
                                                                        <option value=""/>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Contrato<span class="text-danger">*</span></label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <select id="idContrato" name="idContrato" class="select" data-placeholder="Selecione o contrato" required> <!--form-control-->
                                                                        <!--<option disabled selected>Selecione</option>-->
                                                                        <option value=""/>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">N° Patrimônio PMRO </label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <input id="patrimonioPmro" name="patrimonioPmro" type="text" class="form-control" placeholder="Digite número do Patrimônio PMRO" >
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">N° Patrimônio Interno </label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <input id="patrimonioInterno" name="patrimonioInterno" type="text" class="form-control" placeholder="Digite número do Patrimônio PMRO" >
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">N° Patrimônio da Empresa </label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <input id="patrimonioEmpresa" name="patrimonioEmpresa" type="text" class="form-control" placeholder="Digite número do Patrimônio da Empresa" >
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Observação</label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <textarea id="observacao" name="observacao" rows="3" cols="5" class="form-control" placeholder="Descrição do equipamento"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Situação</label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <div class="jSwitch jSwitchAjustes">
                                                                        <label>
                                                                            <input id="situacao" type="checkbox" checked>
                                                                            <span class="jThumb"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Status</label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <div class="jSwitch jSwitchAjustes">
                                                                        <label>
                                                                            <input id="status" type="checkbox" checked>
                                                                            <span class="jThumb"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </fieldset>


                                                        <div class="text-right">
                                                            <button id="btnCadEquipamento" class="btn btn-primary btn-sm btn-labeled btn-labeled-right heading-btn">Salvar <b><i class="icon-arrow-right14"></i></b></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- /form horizontal -->

                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-white">
                                <a id='panel-datatables' class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarEquipamento" aria-expanded="false">
                                    <div class="panel-heading border3-darkPrimary">
                                        <h6 class="panel-title">
                                            Lista dos Equipamentos Cadastrados
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoListarEquipamento" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="panel-body border3-primary no-padding">
                                        <div class="modernDataTable">
                                            <table id="tableEquipamento" class="table tasks-list table-lg dataTable no-footer">
                                                <thead>
                                                <tr>
                                                    <th>Contrato</th>
                                                    <th>Modelo</th>
                                                    <th>Patrimônio PMRO</th>
                                                    <th>Patrimônio Empresa</th>
                                                    <th>Patrimônio Interno</th>
                                                    <th>Observação</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
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
            <div class="footer"> </div>
            <!-- /footer -->

        </div>
        <!-- /page container -->
    </div>
</div>
</body>
</html>