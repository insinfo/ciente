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
    <script type="text/javascript" src="ViewModel/GerenciarEquipamentoMovimentacaoViewModel.js"></script>

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
                                    <span class="text-semibold">Modelo de Equipamento</span>
                                </h4>

                                <ul class="breadcrumb position-right">

                                </ul>
                            </div>
                            <div class="heading-elements">
                                <select id='idSetoresUsuario' name='idSetoresUsuario' _class="select form-control" class="btn bg-purple btn-labeled heading-btn legitRipple">
                                </select>

                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" href="#blocoCadastrarModeloEquipamento" aria-expanded="true" class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-file-plus"></i></b>Cadastrar</a>
                                <a id='panel-datatables' data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarModeloEquipamento" aria-expanded="false" class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-list"></i></b>Listar</a>
                            </div>
                        </div>
                    </div>
                    <!-- /page header -->

                    <!-- Content area -->
                    <div class="content">

                        <div class="panel-group accordion-sortable content-group-lg ui-sortable" id="accordion-controls">
                            <div class="panel panel-white">
                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" href="#blocoCadastrarModeloEquipamento" aria-expanded="true">
                                    <div class="panel-heading border3-darkOrange">
                                        <h6 class="panel-title">
                                            Cadastrar Modelo de Equipamento
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoCadastrarModeloEquipamento" class="panel-collapse collapse in" aria-expanded="true" style="">
                                    <div class="panel-body border3-orange">

                                        <!-- Form horizontal -->
                                        <div>
                                            <div class="panel-body">
                                                <p class="content-group-lg">Tela para cadastrar todos os modelos de equipamentos por setor.</p>

                                                <form id='formEquipamentoModelo' class="form-horizontal" action="#" onSubmit='return false'>
                                                    <div class="form-horizontal">
                                                        <fieldset class="content-group">

                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Tipo de Equipamento</label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <select id="idTipoEquipamento" name="idTipoEquipamento" class="select" data-placeholder="Selecione o tipo de equipamento"> <!--form-control-->
                                                                        <!--<option disabled selected>Selecione</option>-->
                                                                        <option value=""/>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!--
                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Nome</label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <input id="nome" name="nome" type="text" class="form-control" placeholder="Digite o modelo do equipamento">
                                                                </div>
                                                            </div>
                                                            -->

                                                            <div class="form-group">
                                                            <label class="control-label col-sm-2">Data início </label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <input name="data" type="text" class="form-control mskData" placeholder="Data da movimentação">
                                                                    <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                                                </div>
                                                            </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Motivo</label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <textarea id="motivo" name="motivo" rows="3" cols="5" class="form-control" placeholder="Motivo"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Observação</label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <textarea id="observacao" name="observacao" rows="3" cols="5" class="form-control" placeholder="Observação"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Ativo</label>
                                                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                                    <div class="jSwitch jSwitchAjustes">
                                                                        <label>
                                                                            <input id="ativo" type="checkbox" checked>
                                                                            <span class="jThumb"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </fieldset>


                                                        <div class="text-right">
                                                            <button id="btnCadEquipamentoModelo" class="btn btn-primary btn-sm btn-labeled btn-labeled-right heading-btn">Salvar <b><i class="icon-arrow-right14"></i></b></button>
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
                                <a id='panel-datatables' class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarModeloEquipamento" aria-expanded="false">
                                    <div class="panel-heading border3-darkPrimary">
                                        <h6 class="panel-title">
                                            Lista Tipos Cadastrados
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoListarModeloEquipamento" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="panel-body border3-primary no-padding">
                                        <div class="modernDataTable">
                                            <table id="tableEquipamentoModelo" class="table tasks-list table-lg dataTable no-footer">
                                                <thead>
                                                <tr>
                                                    <th>Setor</th>
                                                    <th>Nome</th>
                                                    <th>Descrição</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
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