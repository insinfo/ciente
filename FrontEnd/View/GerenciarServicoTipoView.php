<?php
/*
Nome : GerenciarTipoServico.php
Descricao : Tela com formulario e listagem dos Tipos de Serviços
Projeto : CIENTE
Data de Criacao : 02/01/2018
Data das ultimas modificacoes : 11/01/2018
Versao : 2.0
Autor : José Amaro da Costa Neto (jcosta@riodasostras.rj.gov.br)
Ultimo Modificador : José Amaro da Costa Neto (jcosta@riodasostras.rj.gov.br)
*/
?>
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
    <script type="text/javascript" src="ViewModel/GerenciarServicoTipoViewModel.js"></script>


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
                                    <span class="text-semibold">Tipo de Serviço</span>
                                </h4>

                                <ul class="breadcrumb position-right">

                                </ul>
                            </div>
                            <div class="heading-elements">
                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoTipoServico" class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-file-plus"></i></b>Cadastrar</a>
                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoListarTipoServico" class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-list"></i></b>Listar</a>
                            </div>
                        </div>
                    </div>
                    <!-- /page header -->

                    <!-- Content area -->
                    <div class="content">
                        <div class="panel-group accordion-sortable content-group-lg ui-sortable" id="accordion-controls">
                            <!-- Form horizontal -->
                            <div class="panel panel-flat">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoTipoServico" aria-expanded="false">
                                    <div class="panel-heading border3-darkOrange">
                                        <h6 id='panel-form' class="panel-title" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoTipoServico">
                                            Novo Tipo de Serviço
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoTipoServico" class="panel-collapse collapse in" aria-expanded="true" style="">
                                    <div class="panel-body border3-orange">

                                        <p class="content-group-lg">Tela para cadastramento de Tipo de Serviço</p>

                                        <form id='formTipoServico' class="form-horizontal" action="#" onSubmit='return false'>
                                            <!--<div id='formTipoServico' class="form-horizontal" >-->
                                            <fieldset class="content-group">
                                               <div class="form-group">
                                                    <label class="control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Setor <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <select id='idSetor' name="idSetor" class="select form-control" data-placeholder="Selecione...">
                                                            <option value="" />
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Nome do serviço <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <input type="text" id="nome" name="nome" class="form-control" placeholder="Nome do Serviço"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Descrição <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <textarea id="descricao" name="descricao" rows="3" cols="5" class="form-control" placeholder="Descrição do Tipo de Serviço"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Atendimento inicial <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <input type="text" id="prazoAtendimentoInicial" name="prazoAtendimentoInicial" class="form-control mskInteger" placeholder="Prazo em horas úteis" onkeypress="number(event)" min="0"/>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Ativo <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <div class="jSwitch jSwitchAjustes">
                                                            <label>
                                                                <input type="checkbox" id="ativo" name="ativo" checked="true"/>
                                                                <span class="jThumb"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <div class="text-right">
                                                <!--<button id='btnCadTipoServico' class="btn btn-primary btn-sm btn-labeled btn-labeled-right heading-btn">Cadastrar <b><i class="icon-arrow-right14"></i></b></button>-->
                                                <button id='btnCadTipoServico' class="btn btn-primary btn-sm btn-labeled btn-labeled-right heading-btn">Salvar <b><i class="icon-arrow-right14"></i></b></button>
                                            </div>
                                        </form>
                                        <!--</div> sem form-->
                                    </div>
                                </div><!--blocoCadTipoServico-->
                            </div>
                            <!-- /form horizontal -->

                            <!-- data-tables -->
                            <div class="panel panel-flat">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarTipoServico" aria-expanded="false">
                                    <div class="panel-heading border3-darkPrimary">
                                        <h6 id='panel-datatables' class="panel-title" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarTipoServico">
                                            Lista Tipos de Serviços
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoListarTipoServico" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="panel-body border3-primary no-padding">
                                        <div class="modernDataTable">
                                            <table id="tableTipoServico" class="table tasks-list table-lg dataTable no-footer">
                                                <thead>
                                                <tr>
                                                    <!--<th>Editar</th>-->
                                                    <th>Tipo de Serviço</th>
                                                    <th>Setor</th>
                                                    <th>Descrição</th>
                                                    <th>Prazo Atendimento</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div> <!--fim data-tables-->
                        </div> <!-- panel-group-->

                    </div><!-- /content area -->

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
<script>
    /*$("#setor").autocomplete("../Assets/setores.php", {
        width: 500,
        scrollHeight: 180,
        selectFirst: true
    });*/
</script>