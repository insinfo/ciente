<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jubarte - Prefeitura Municipal de Rio das Ostras - RJ</title>

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
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/jquery_ui/widgets.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/selects/select2.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/app.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/notifications/jgrowl.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ui/ripple.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/jquery.mask/1.14.11/jquery.mask.min.js"></script>
    <!-- /theme JS files -->

    <link href="/cdn/Assets/css/jSwitch.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/jubarteStyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/cdn/Assets/css/modernDataTable.css"/>

    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/moment.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/locale/pt-br.js"></script>

    <script type="text/javascript" src="/cdn/utils/utils.js"></script>
    <script type="text/javascript" src="/cdn/utils/jubarte.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModalAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernBlockUI.js"></script>
    <script type="text/javascript" src="/cdn/utils/LoaderAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/RESTClient.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernDataTable.js"></script>
    <script type="text/javascript" src="ViewModel/Constants.js"></script>

    <script type="text/javascript" src="ViewModel/GerenciarServicosViewModel.js"></script>

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
                            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                <div class="row ">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                                        <h4><i class="icon-grid position-left"></i> <span class="text-semibold">Serviços</span></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <ul class="breadcrumb position-right"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 mt-15">
                                <div class="row text-right nbb" style="margin-right: 20px;">
                                    <select id="selectFiltroSetorUsuario" class="select form-control" tabindex="-1" aria-hidden="true"></select>
                                    <button id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoCadServico" class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-file-plus"></i></b>Cadastrar</button>
                                    <button id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoListarServicos" class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-list"></i></b>Listar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /page header -->

                    <!-- Page header BKP ->
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">
                                <h4>
                                    <i class="icon-grid position-left"></i>
                                    <span class="text-semibold">Serviços</span>
                                </h4>

                                <ul class="breadcrumb position-right">

                                </ul>
                            </div>
                            <div class="heading-elements">
                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoCadServico" class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-file-plus"></i></b>Cadastrar</a>
                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoListarServicos" class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-list"></i></b>Listar</a>
                            </div>
                        </div>
                    </div>
                    <!-- /page header BKP -->

                    <!-- Content area -->
                    <div class="content">
                        <div class="panel-group accordion-sortable content-group-lg ui-sortable" id="accordion-controls">
                            <div class="panel panel-white" id="panelFormulario"><!-- painel 01 -->
                                <a id="linkOpenForm" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoCadServico" aria-expanded="true">
                                    <div class="panel-heading border3-darkOrange">
                                        <h6 class="panel-title">
                                            Cadastro de Serviço
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoCadServico" class="panel-collapse collapse in" aria-expanded="true" style="">
                                    <div class="panel-body border3-orange">

                                        <!-- Form horizontal -->
                                        <p class="content-group-lg">Tela para cadastramento de serviços do setor.</p>

                                        <div class="form-horizontal" id="formCadServico">
                                            <fieldset class="content-group">

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Contrato</label>
                                                    <div class="col-sm-10">
                                                        <select id="selectIdContrato" name="idContrato" class="form-control" data-placeholder="Selecione o contrato">
                                                            <option value="null">Nenhum contrato</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Tipo de Serviço <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <select name="idTipoServico" class="form-control" data-placeholder="Selecione o tipo de serviço" required>
                                                            <option value=""/>
                                                            <!--<option value="null">Todos os Sistemas</option>-->
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Código de referência</label>
                                                    <div class="col-sm-10">
                                                        <input name="codigoRef" type="text" class="form-control" placeholder="Código de referência">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Nome <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input name="nome" maxlength="64" type="text" class="form-control" placeholder="Nome do serviço" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Descrição <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <textarea name="descricao" class="form-control" placeholder="Descrição do serviço" required></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Prazo urgência <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input name="prazoUrgente" maxlength="4" type="text" class="form-control mskInteger" placeholder="Prazo em horas úteis" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Prazo alta <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input name="prazoAlta" maxlength="4" type="text" class="form-control mskInteger" placeholder="Prazo em horas úteis" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Prazo normal <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input name="prazoNormal" maxlength="4" type="text" class="form-control mskInteger" placeholder="Prazo em horas úteis" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Prazo baixa <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input name="prazoBaixa" maxlength="4" type="text" class="form-control mskInteger" placeholder="Prazo em horas úteis" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Ordem</label>
                                                    <div class="col-sm-10">
                                                        <input name="ordem" maxlength="4" type="text" class="form-control mskInteger" placeholder="Ordem de exibição">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Produto requerido</label>
                                                    <div class="col-sm-10">
                                                        <div class="jSwitch jSwitchAjustes">
                                                            <label>
                                                                <input name="inProduto" type="checkbox" checked >
                                                                <span class="jThumb"></span>
                                                            </label>
                                                        </div>
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
                                        <!-- /form horizontal -->

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-white" id="tableServicos"><!-- painel 02 -->
                                <a id="btnOpenListaServicos" class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarServicos" aria-expanded="false">
                                    <div class="panel-heading border3-darkPrimary">
                                        <h6 class="panel-title">
                                            Lista de Serviços
                                    </div>
                                </a>
                                <div id="blocoListarServicos" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="panel-body border3-primary no-padding">

                                        <!-- ModernDataTable -->
                                        <div class="modernDataTable">
                                            <table id="tableListServicos" class="">
                                                <thead>
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Referência</th>
                                                    <th>Nome</th>
                                                    <th>Urgência</th>
                                                    <th>Alta</th>
                                                    <th>Normal</th>
                                                    <th>Baixa</th>
                                                    <th>Produto</th>
                                                    <th>Ativo</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div><!-- Fim ModernDataTable -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /content area -->

                </div><!-- /main content -->

            </div><!-- /page content -->

            <!-- Footer -->
            <div class="footer text-muted"> </div>
            <!-- /footer -->

        </div><!-- /page container -->
    </div>
</div>
</body>
</html>