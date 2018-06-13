<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ciente - Jubarte - Prefeitura Municipal de Rio das Ostras - RJ</title>

    <!-- ESTILO LIMITLESS -->
    <link href="/cdn/Assets/fonts/material-icons/material-icons.css" rel="stylesheet">
    <link href="/cdn/Assets/fonts/roboto/roboto.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/core.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/components.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Vendor/limitless/material/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /ESTILO LIMITLESS -->

    <!-- JS CORE LIMITLESS -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/jquery_ui/widgets.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/uploaders/dropzone.min.js"></script>
    <!-- /JS CORE LIMITLESS -->

    <script type="text/javascript" src="/cdn/Vendor/bootstrap-select-1.12.4/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/bootstrap-select-1.12.4/defaults-pt_BR.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/app.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/notifications/jgrowl.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ui/ripple.min.js"></script>

    <!-- Editor de Texto -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ckeditor/ckeditorCustom/ckeditor.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ckeditor/ckeditorCustom/adapters/jquery.js"></script>

    <!-- /theme JS files -->
    <link href="/cdn/Assets/css/jSwitch.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/jubarteStyle.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/ModernTreeView.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/modernDataTable.css" rel="stylesheet" type="text/css"/>

    <!-- DEPENDECIAS DA VIEW MODEL -->
    <script type="text/javascript" src="/cdn/utils/utils.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernBlockUI.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModalAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/RESTClient.js"></script>
    <script type="text/javascript" src="/cdn/utils/LoaderAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/JubarteAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernTreeView.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernDataTable.js"></script>

    <!-- MOMENT -->
    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/moment.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment-busines-time/mbt.js"></script>

    <!-- VIEW MODEL -->
    <script type="text/javascript" src="ViewModel/Constants.js"></script>
    <script type="text/javascript" src="ViewModel/NovaSolicitacaoViewModel.js"></script>
    <script>
        $(function () {
            var app = new NovaSolicitacaoVM();
            app.init();
        });
    </script>
    <style>
        button.dropdown-toggle > span.filter-option > i {
            display: none;
        }
    </style>

</head>

<body class="sidebar-xs">

<!-- Page container -->
<div class="page-container containerInsideIframe">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->
            <div class="page-header">
                <div class="page-header-content">
                    <div class="page-title">
                        <h4><i class="icon-grid position-left"></i> <span class="text-semibold">Nova Solicitação</span></h4>

                        <ul class="breadcrumb position-right">

                        </ul>
                    </div>
                    <div class="heading-elements">
                        <a href="#" class="btn bg-orange btn-labeled heading-btn legitRipple btn-solicitar"><b><i class="icon-file-plus"></i></b>Cadastrar Solicitação</a>
                    </div>
                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">

                <!-- Detailed task -->
                <div class="row">
                    <div class="col-md-9 col-lg-9">
                        <!-- Solicitação -->
                        <div class="panel panel-flat border3-grey" id="panelSolicitante">
                            <div class="panel-body">
                                <h6 class="text-semibold">Solicitante</h6>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Lotação <span class="text-danger">*</span></label>
                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                <input name="lotacao" readonly required type="text" class="form-control cursor-pointer" placeholder="Selecione a lotação">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Solicitante <span class="text-danger">*</span></label>
                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                <input name="solicitante" readonly required type="text" class="form-control cursor-pointer" placeholder="Selecione o usuário solicitante">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Acompanhamento</label>
                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                <input name="solicitanteSec" readonly type="text" class="form-control cursor-pointer" placeholder="Selecione o usuário que irá acompanhar">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Equipamento</label>
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-2">
                                                <input type="text" class="form-control" placeholder="Buscar Equipamento">
                                            </div>
                                            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-8" style="margin-top: 0;">
                                                <select name="equipamento" class="form-control">
                                                    <option value="">Selecione Equipamento</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Operador <span class="text-danger">*</span></label>
                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                <input name="operador" disabled required type="text" class="form-control cursor-pointer text-muted">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <h6 class="text-semibold">Descrição</h6>
                                <div class="content-group">
                                    <textarea name="editorDescricao" required id="editor"></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2"><strong>Observações</strong></label>
                                        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                            <textarea name="observacao" rows="3" cols="5" class="form-control" placeholder="Se necessário, utilize essa área para alguma observação"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Solicitação -->

                        <!-- Descrição da Solicitação ->
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <h6 class="text-semibold">Descrição</h6>
                                <div class="content-group">
                                    <textarea name="editorDescricao" required id="editor"></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2"><strong>Observações</strong></label>
                                        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                            <textarea name="observacao" rows="3" cols="5" class="form-control" placeholder="Se necessário, utilize essa área para alguma observação"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Descrição da Solicitação -->

                        <!-- Atendimento -->
                        <div class="panel panel-flat border3-grey" id="panelAtendimento">
                            <div class="panel-body">
                                <h6 class="text-semibold">Atendimento</h6>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Setor <span class="text-danger">*</span></label>
                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                <input name="setor" readonly required type="text" class="form-control cursor-pointer" placeholder="Selecione o setor da solicitação">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Tipo de serviço <span class="text-danger">*</span></label>
                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                <select name="tipoServico" required class="form-control" data-live-search="true" title="Selecione o tipo de serviço">
                                                    <option value="opt1">Selecione</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Serviço <span class="text-danger">*</span></label>
                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                <select name="servico" required class="form-control" data-live-search="true" title="Selecione o serviço"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- products -->

                                    <div class="form-group hide" id="product-row">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                                Produto <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                <select name="product" required class="form-control" data-live-search="true" title="Selecione o produto"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- end products -->


                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Cópia para e-mail</label>
                                            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">
                                                <div class="jSwitch correcaoAlturaLabel" style="margin-left: -20px;">
                                                    <label>
                                                        <input type="checkbox" id="checkBoxModoExibicao">
                                                        <span class="jThumb"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-9">
                                                <input type="text" class="form-control" placeholder="Digite e-mail">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <!-- Atendimento -->

                        <!-- Anexar Arquivos --
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <h6 class="text-semibold">Anexar Arquivos</h6>
                                <div class="table-responsive content-group">
                                    <form action="#" class="dropzone dz-clickable" id="dropzone_multiple">
                                        <div class="dz-default dz-message">
                                        <span>Arraste os arquivos ou CLIQUE<br>para upload
                                        </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /Anexar Arquivos -->

                        <div class="text-right">
                            <a href="#" class="btn bg-orange btn-labeled heading-btn legitRipple btn-solicitar mb-15"><b>
                                    <i class="icon-file-plus"></i></b>Cadastrar Solicitação</a>
                        </div>

                    </div>

                    <div class="col-md-3 col-lg-3">

                        <!-- Solicitações por lotação -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-collaboration position-left"></i> Por Lotação</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <ul class="media-list" id="solicitacoes-por-lotacao">
                                    <li class="outrosChamados text-center text-uppercase text-semibold text-muted">Nenhuma solicitação localizada</li>
                                </ul>
                            </div>
                        </div>
                        <!-- /Solicitações por lotação -->

                        <!-- Solicitações por Usuário -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-person position-left"></i> Por Usuário</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <ul class="media-list" id="solicitacoes-por-solicitante">
                                    <li class="outrosChamados text-center text-uppercase text-semibold text-muted">Nenhuma solicitação localizada</li>
                                    <!--
                                    <li class="outrosChamados" data-popup="popover" data-placement="left" title="" data-trigger="hover" data-content="01. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI." data-original-title="#2017.04131629426573">
                                        01. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI.
                                    </li>
                                    <li class="outrosChamados" data-popup="popover" data-placement="left" title="" data-trigger="hover" data-content="02. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI." data-original-title="#2017.04131629426573">
                                        02. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI.
                                    </li>
                                    <li class="outrosChamados" data-popup="popover" data-placement="left" title="" data-trigger="hover" data-content="03. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI." data-original-title="#2017.04131629426573">
                                        03. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI.
                                    </li>
                                    -->
                                </ul>
                            </div>
                        </div>
                        <!-- /Solicitações por usuário -->

                        <!-- Solicitações por Serviço -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-files-empty position-left"></i> Por Serviço</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <ul class="media-list" id="solicitacoes-por-servico">
                                    <li class="outrosChamados text-center text-uppercase text-semibold text-muted">Nenhuma solicitação localizada</li>
                                    <!--
                                    <li class="outrosChamados" data-popup="popover" data-placement="left" title="" data-trigger="hover" data-content="01. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI." data-original-title="#2017.04131629426573">
                                        01. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI.
                                    </li>
                                    <li class="outrosChamados" data-popup="popover" data-placement="left" title="" data-trigger="hover" data-content="02. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI." data-original-title="#2017.04131629426573">
                                        02. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI.
                                    </li>
                                    <li class="outrosChamados" data-popup="popover" data-placement="left" title="" data-trigger="hover" data-content="03. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI." data-original-title="#2017.04131629426573">
                                        03. Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI.
                                    </li>
                                    -->
                                </ul>
                            </div>
                        </div>
                        <!-- /Solicitações por Serviço -->

                        <!-- Prioridade -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-price-tag3 position-left"></i> Prioridade</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding-bottom:15px">
                                        <select name="select-prioridade" data-style="bg-primary" data-width="100%" >
                                            <option data-content="<i class='icon-circle-small text-danger'></i> Urgente" value="0"></option>
                                            <option data-content="<i class='icon-circle-small text-info'></i> Alta" value="1"></option>
                                            <option data-content="<i class='icon-circle-small text-primary'></i> Normal" selected value="2"></option>
                                            <option data-content="<i class='icon-circle-small text-success'></i> Baixa" value="3"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Prioridade -->


                        <!-- Timer -->
                        <div class="panel panel-flat" id="solicitacao-conclusao">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-watch position-left"></i> Previsão</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel-body media-body">
                                <ul class="timer">
                                    <li>
                                        11 <span>dia</span>
                                    </li>
                                    <li class="dots">/</li>
                                    <li>
                                        03 <span>mês</span>
                                    </li>
                                    <li class="dots">/</li>
                                    <li>
                                        17 <span class="mb-20">ano</span>
                                    </li>
                                    <li style="font-size: 25px;">
                                        09:11<span class="mt-15">horário</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /timer -->




                        <!-- Attached files -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-link position-left"></i> Arquivos Anexados</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel-body">
                                <ul class="media-list" id="list-files">
                                </ul>

                                <div> <p>&nbsp;</p></div>

                                <ul class="media-list" id="upload-files">
                                    <li class="media text-right">

                                        <!-- Anexar Arquivos -->
                                        <div class="table-responsive content-group">
                                            <form action="#" class="dz-clickable" id="dropzoneMultiple">
                                                <div class="dz-default dz-message">
                                                    <span>Arraste os arquivos ou CLIQUE<br>para upload</span>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /Anexar Arquivos -->
                                        <!--<a class="btn bg-grey btn-labeled heading-btn legitRipple"><b><i class="icon-file-plus"></i></b>Adicionar Arquivo</a>-->
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /attached files -->






                    </div>
                </div>
                <!-- /detailed task -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

    <!-- Footer -->
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="footer text-muted"></div>
        </div>
    </div>
    <!-- /footer -->

</div>
<!-- /page container -->

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
<div id="modalPessoaFisica" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-large">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Selecione</h6>
            </div>
            <div class="modal-body">
                <!-- MODAL BODY -->
                <div class="modernDataTable">
                    <table id="tableListaPessoa" class="table tasks-list table-lg dataTable no-footer">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Nascimento</th>
                            <th>Sexo</th>
                            <th>CPF</th>
                            <th>RG</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- MODAL BODY -->
            </div>
        </div>
    </div>
</div>
<!-- /Modais -->

</body>
</html>
