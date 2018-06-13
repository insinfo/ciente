<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ciente - Jubarte - Prefeitura Municipal de Rio das Ostras - RJ</title>

    <!-- Global stylesheets -->
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Roboto+Mono|Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
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
    <script type="text/javascript" src="ViewModel/GerenciarContratoViewModel.js"></script>

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
                                    <span class="text-semibold">Contratos</span>
                                </h4>

                                <ul class="breadcrumb position-right">

                                </ul>
                            </div>
                            <div class="heading-elements">
                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoCadastrarContrato" class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-file-plus"></i></b>Cadastrar</a>
                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoListarContrato" class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-list"></i></b>Listar</a>
                            </div>
                        </div>
                    </div>
                    <!-- /page header -->

                    <!-- Content area -->
                    <div class="content">
                        <!-- panel-group-->
                        <div class="panel-group accordion-sortable content-group-lg ui-sortable" id="accordion-controls">
                            <!-- Form horizontal -->
                            <div class="panel panel-flat">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoCadastrarContrato" aria-expanded="false">
                                    <div class="panel-heading border3-darkOrange">
                                        <h6 id='panel-form' class="panel-title" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoCadastrarContrato">
                                            Cadastrar Contrato
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoCadastrarContrato" class="panel-collapse collapse in" aria-expanded="true" style="">
                                    <div class="panel-body border3-orange">

                                        <p class="content-group-lg">Tela para cadastramento de Contratos</p>

                                        <form id='formContrato' class="form-horizontal" action="#" onSubmit='return false'>
                                            <!--<div id='formContrato' class="form-horizontal" >-->
                                            <fieldset class="content-group">

                                                <div class="form-group">
                                                    <label class="control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Pessoa Jurídica</label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                        <select id='idPessoaJuridica' name="idPessoaJuridica" class="select form-control" data-placeholder="Selecione...">
                                                            <option></option>
                                                            <!--<option value="opt2">Empresa 1</option>
                                                            <option value="opt3">Empresa 2</option>-->
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Descrição</label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <textarea id="descricao" name="descricao" rows="3" cols="5" class="form-control" placeholder="Descrição do Contrato"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Observação</label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <textarea id="observacao" name="observacao" rows="3" cols="5" class="form-control materialize-textarea" placeholder="Observação"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Ativo</label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <div class="jSwitch jSwitchAjustes">
                                                            <label>
                                                                <input type="checkbox" id="ativo" name="ativo" checked>
                                                                <span class="jThumb"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <div class="text-right">
                                                <!--<button id='btnCadContrato' class="btn btn-primary btn-sm btn-labeled btn-labeled-right heading-btn">Cadastrar <b><i class="icon-arrow-right14"></i></b></button>-->
                                                <button id='btnCadContrato' class="btn btn-primary btn-sm btn-labeled btn-labeled-right heading-btn">Salvar <b><i class="icon-arrow-right14"></i></b></button>
                                            </div>
                                        </form>
                                        <!--</div> sem form-->
                                    </div>
                                </div><!--blocoCadContrato-->
                            </div>
                            <!-- /form horizontal -->

                            <!-- data-tables -->
                            <div class="panel panel-flat">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarContrato" aria-expanded="false">
                                    <div class="panel-heading border3-darkPrimary">
                                        <h6 id='panel-datatables' class="panel-title" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarContrato">
                                            Lista Tipos Cadastrados
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoListarContrato" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="panel-body border3-primary no-padding">
                                        <div class="modernDataTable">
                                            <table id="tableContrato" class="table tasks-list table-lg dataTable no-footer">
                                                <thead>
                                                <tr>
                                                    <!--<th>Editar</th>-->
                                                    <th>Pessoa Jurídica</th>
                                                    <th>Descrição do Contrato</th>
                                                    <th>Observação</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!--
                                                <tr>
                                                    <td>0001</td>
                                                    <td>Computador</td>
                                                    <td>Descrição Descrição Descrição Descrição Descrição Descrição Descrição Descrição Descrição</td>
                                                    <td><i class="icon-checkmark4"></i></td>
                                                </tr>
                                                <tr>
                                                    <td>0002</td>
                                                    <td>Monitor</td>
                                                    <td>Descrição Descrição Descrição Descrição Descrição Descrição Descrição Descrição Descrição</td>
                                                    <td><i class="icon-cross2"></i></td>
                                                </tr>
                                                -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /data-tables-->
                        </div>
                        <!-- /panel-group-->

                    </div>
                    <!-- /content area -->

                </div><!-- /main content -->

            </div><!-- /page content -->

            <!-- Footer -->
            <div class="footer"> </div>
            <!-- /footer -->

        </div><!-- /page container -->
    </div>
</div>
</body>
</html>