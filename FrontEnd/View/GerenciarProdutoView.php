<?php
/*
Nome : GerenciarTipoServico.php
Descricao : Tela com formulario e listagem dos Tipos de Serviços
Projeto : CIENTE
Data de Criacao : 04/05/2018
Data das ultimas modificacoes : 04/05/2018
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
    <script type="text/javascript" src="/cdn/utils/FormValidationAPI.js"></script>


    <!-- custom loading -->
    <script src="/cdn/utils/ModernBlockUI.js"></script>
    <script src="/cdn/utils/LoaderAPI.js"></script>

    <script type="text/javascript" src="ViewModel/Constants.js"></script>
    <script type="text/javascript" src="ViewModel/GerenciarProdutoViewModel.js"></script>


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
                                    <span class="text-semibold">Produtos</span>
                                </h4>

                                <ul class="breadcrumb position-right">

                                </ul>
                            </div>
                            <div class="heading-elements">
                                <a id='btn-cad' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoProduto" class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-file-plus"></i></b>Cadastrar</a>
                                <a id='btn-list' data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true" href="#blocoListarProdutos" class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-list"></i></b>Listar</a>
                            </div>
                        </div>
                    </div>
                    <!-- /page header -->

                    <!-- Content area -->
                    <div class="content">
                        <div class="panel-group accordion-sortable content-group-lg ui-sortable" id="accordion-controls">
                            <!-- Form horizontal -->
                            <div class="panel panel-flat">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoProduto" aria-expanded="false">
                                    <div class="panel-heading border3-darkOrange">
                                        <h6 id='panel-form' class="panel-title" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoProduto">
                                            Novo Produto
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoProduto" class="panel-collapse collapse in" aria-expanded="true" style="">
                                    <div class="panel-body border3-orange">

                                        <p class="content-group-lg">Tela para cadastramento de Produto</p>

                                        <form id='formProduto' class="form-horizontal" action="#" onSubmit='return false'>
                                            <!--<div id='formTipoServico' class="form-horizontal" >-->
                                            <fieldset class="content-group">

                                               <div class="form-group inputBlock">
                                                    <label class="control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Setor <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                        <select id="idSetor" name="idSetor" class="select" data-placeholder="Selecione o setor" required data-type='numeric'> <!--form-control-->
                                                            <option value=""/>
                                                        </select>
                                                    </div>
                                               </div>

                                               <div class="form-group inputBlock">
                                                    <label class="control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Produto <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <input type="text" id="nomeReduzido" name="nomeReduzido" class="form-control" placeholder="Nome do Produto" required data-type='string' />
                                                    </div>
                                                </div>

                                                <div class="form-group inputBlock">
                                                    <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Descrição <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <textarea id="descricao" name="descricao" rows="3" cols="5" class="form-control" placeholder="Descrição do Tipo de Serviço" required data-type='string' ></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group inputBlock">
                                                    <label class="control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Origem <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-11">
                                                        <select id="origem" name="origem" class="select" data-placeholder="Selecione" required data-type='string' > <!--form-control-->
                                                            <option value=""/>
                                                            <option value='P'>Próprio</option>
                                                            <option value='G'>Governo</option>
                                                            <option value='T'>Terceiro</option>
                                                            <option value='O'>Outros</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group inputBlock">
                                                    <label class="control-label control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Ativo <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                                        <div class="jSwitch jSwitchAjustes">
                                                            <label>
                                                                <input type="checkbox" id="ativo" name="ativo" checked="true" data-type='string' required />
                                                                <span class="jThumb"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </fieldset>

                                            <div class="text-right">
                                                <!--<button id='btnCadTipoServico' class="btn btn-primary btn-sm btn-labeled btn-labeled-right heading-btn">Cadastrar <b><i class="icon-arrow-right14"></i></b></button>-->
                                                <button id='btnCadProduto' class="btn btn-primary btn-sm btn-labeled btn-labeled-right heading-btn">Salvar <b><i class="icon-arrow-right14"></i></b></button>
                                            </div>
                                        </form>
                                        <!--</div> sem form-->
                                    </div>
                                </div><!--blocoCadTipoServico-->
                            </div>
                            <!-- /form horizontal -->

                            <!-- data-tables -->
                            <div class="panel panel-flat">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarProdutos" aria-expanded="false">
                                    <div class="panel-heading border3-darkPrimary">
                                        <h6 id='panel-datatables' class="panel-title" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoListarProdutos">
                                            Lista de Produtos
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoListarProdutos" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="panel-body border3-primary no-padding">
                                        <div class="modernDataTable">
                                            <table id="tableProduto" class="table tasks-list table-lg dataTable no-footer">
                                                <thead>
                                                <tr>
                                                    <!--<th>Editar</th>-->
                                                    <th>Setor</th>
                                                    <th>Produto</th>
                                                    <th>Descrição</th>
                                                    <th>Origem</th>
                                                    <th>Ativo</th>
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