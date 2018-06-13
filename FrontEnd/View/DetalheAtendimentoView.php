<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ciente - Jubarte - Prefeitura Municipal de Rio das Ostras - RJ</title>

    <!-- Global stylesheets -->
    <link href="//fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
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
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/uploaders/dropzone.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/bootstrap-select-1.12.4/bootstrap-select.min.js"></script>

    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/uniform.min.js"></script>
    <!--    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/switchery.min.js"></script>-->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/app.js"></script>
    <!--    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/pages/form_checkboxes_radios.js"></script>-->
    <!--    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/pages/tasks_grid.js"></script>-->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ui/ripple.min.js"></script>

    <!-- Editor de Texto -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ckeditor/ckeditorCustom/ckeditor.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ckeditor/ckeditorCustom/adapters/jquery.js"></script>
    <!--<script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ckeditor/samples/js/sample.js"></script>-->

    <!-- /theme JS files -->
    <link href="/cdn/Assets/css/jSwitch.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/jCheckBox.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/jubarteStyle.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/ModernTreeView.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/modernDataTable.css" rel="stylesheet" type="text/css"/>


    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/moment.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/locale/pt-br.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment-busines-time/mbt.js"></script>

    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/notifications/jgrowl.min.js"></script>
    <script type="text/javascript" src="/cdn/utils/utils.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernBlockUI.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModalAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/JubarteAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/RESTClient.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernDataTable.js"></script>
    <script type="text/javascript" src="/cdn/utils/LoaderAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/customLoading.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernDataGrid.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernTreeView.js"></script>

    <script type="text/javascript" src="ViewModel/Constants.js"></script>
    <script type="text/javascript" src="ViewModel/NovaSolicitacaoViewModel.js"></script>
    <script type="text/javascript" src="ViewModel/DetalheAtendimentoViewModel.js"></script>
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
                        <h4><i class="icon-grid position-left"></i> <span class="text-semibold">Atendimento</span></h4>

                        <ul class="breadcrumb position-right">
                            <li><a href="index.html">Ciente</a></li>
                            <li><a href="GerenciarAtendimentosView.php">Meus Atendimentos</a></li>
                            <li class="active">Atendimento</li>
                        </ul>
                    </div>
                    <div class="heading-elements">
                        <a href="grid" class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-task"></i></b>Meus Atendimentos</a>
                        <a href="minhasSolicitacoes" class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-grid"></i></b>Minhas Solicitações</a>
                    </div>
                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">

                <!-- Detailed task -->
                <div class="row">
                    <div class="col-md-8 col-lg-8 col-xl-9">

                        <!-- Task overview -->
                        <div class="panel panel-flat border3-grey" id="solicitacaoPanel">
                            <div class="panel-heading">
                                <h5 class="panel-title" id="solicitacao-numero">#2017.04131629426573</h5>
                                <div class="heading-elements">
                                    <a href="#" class="btn bg-primary btn-sm btn-labeled btn-labeled-right heading-btn" id="btnPegarSolicitacao">
                                        Pegar Atendimento <b><i class="icon-drawer-in"></i></b>
                                    </a>
                                </div>
                            </div>

                            <div class="panel-body">
                                <h6 class="text-semibold">Descrição</h6>
                                <p class="content-group" id="solicitacao-descricao"></p>

                                <div class="table-responsive content-group">
                                    <table class="table table-framed hoverTable" id="solicitacao-atendimentos-table">
                                        <thead>
                                        <tr>
                                            <th style="width: 20px;">#</th>
                                            <th>Setor</th>
                                            <th>Responsável</th>
                                            <th>Encaminhado</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!--<div class="panel-footer">
                                <div class="heading-elements">
                                    <ul class="list-inline list-inline-condensed heading-text pull-right">
                                        <li><a href="#" class="text-default"><i class="icon-compose"></i></a></li>
                                        <li><a href="#" class="text-default"><i class="icon-trash"></i></a></li>
                                    </ul>
                                </div>
                            </div>-->
                        </div>
                        <!-- /task overview -->

                        <!-- Comments -->
                        <div class="panel panel-flat border3-grey">
                            <div class="panel-heading">
                                <h5 class="panel-title text-semiold"><i class="icon-bubbles4 position-left"></i> Histórico</h5>
                            </div>

                            <div class="panel-body">

                                <ul class="media-list content-group-lg stack-media-on-mobile" id="historico-container">
                                    <li>Não existem comentários para este atendimento</li>
                                </ul>

                            </div>
                        </div>

                        <div class="panel-group accordion-sortable content-group-lg ui-sortable" id="accordion-controls">
                            <div class="panel panel-white">
                                <a id='panel-form' data-toggle="collapse" data-parent="#accordion-controls" href="#blocoComentario" aria-expanded="true">
                                    <div class="panel-heading border3-darkOrange">
                                        <h6 class="panel-title">
                                            <i class="icon-pencil7 position-left"></i> Seu comentário
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoComentario" class="panel-collapse collapse in" aria-expanded="true" style="">
                                    <div class="panel-body border3-orange">

                                        <fieldset class="content-group">

                                            <div class="content-group">
                                                <textarea id="editor" class="editor"></textarea>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select id="comboEquipe" class="select selectpicker">
                                                    </select>
                                                </div>

                                                <div class="col-md-8 text-right">
                                                    <a href="#" class="btn bg-orange btn-labeled heading-btn legitRipple btn-comment" id="btn-comment">
                                                        <b><i class="icon-plus22"></i></b>
                                                        <span>Adicionar comentário</span>
                                                    </a>
                                                    <a href="#blocoTransferir" id="btn-transfer" class="btn bg-blue btn-labeled heading-btn legitRipple btn-tranfer" data-toggle="collapse" data-parent="#accordion-controls" aria-expanded="true">
                                                        <b><i class="icon-tree7"></i></b>
                                                        <span>Transferir</span>
                                                    </a>
                                                </div>
                                            </div>

                                        </fieldset>

                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-white">
                                <a id='panel-datatables' class="collapsed" data-toggle="collapse" data-parent="#accordion-controls" href="#blocoTransferir" aria-expanded="false">
                                    <div class="panel-heading border3-darkPrimary">
                                        <h6 class="panel-title">
                                            <i class="icon-pencil7 position-left"></i> Transferir para outro setor
                                        </h6>
                                    </div>
                                </a>
                                <div id="blocoTransferir" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="panel-body border3-primary">

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
                                                        <select name="tipoServico" required class="form-control" title="Selecione o tipo de serviço">
                                                            <option value="opt1">Selecione</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label correcaoAlturaLabel col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">Serviço <span class="text-danger">*</span></label>
                                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 ">
                                                        <select name="servico" required class="form-control" title="Selecione o serviço"></select>
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
                                                        <select name="product" required class="form-control" title="Selecione o produto"></select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- end products -->


                                            <!--<div class="form-group">
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
                                                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-9 ">
                                                        <input type="text" class="form-control" placeholder="Digite e-mail">
                                                    </div>
                                                </div>
                                            </div>-->

                                            <div class="content-group">
                                                <textarea id="editor-message-transfer" class="editor"></textarea>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3 col-lg-offset-2 col-lg-3 col-xl-offset-5 col-xl-2">
                                                    <div class="jCheckBox" style="top: 8px;">
                                                        <input type="checkbox" id="checkbox-transferir-manter">
                                                        <label for="checkbox-transferir-manter">Transferir e Manter</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-lg-3 col-xl-2 text-right">
                                                    <select name="cmb-status-transferir" id="cmb-status-transferir" class="select selectpicker" data-placeholder="Status" tabindex="-1" aria-hidden="true"></select>
                                                </div>
                                                <div class="col-md-5 col-lg-4 col-xl-3 text-right">
                                                    <a href="#" class="btn bg-blue btn-labeled heading-btn legitRipple btn-tranfer" id="btn-concluir-transferencia">
                                                        <b><i class="icon-tree7"></i></b><span>Concluir Transferência</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </fieldset>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- /comments -->

                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-3">

                        <!-- Timer -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">
                                    <i class="icon-watch position-left"></i>
                                    Tempo da Solicitação
                                </h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel-body">
                                <ul class="timer mb-10">
                                    <li>
                                        <div id="solicitacao-horas">00</div>
                                        <span>horas úteis</span>
                                    </li>
                                    <li class="dots">:</li>
                                    <li>
                                        <div id="solicitacao-minutos">00</div>
                                        <span>minutos</span>
                                    </li>
                                    <li class="dots">:</li>
                                    <li>
                                        <div id="solicitacao-segundos">00</div>
                                        <span>segundos</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /timer -->


                        <!-- Task details -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-files-empty position-left"></i> Detalhes da Solicitação</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <table class="table table-borderless table-xs content-group-sm" style="border-top:0;">
                                <tbody>
                                <tr>
                                    <td class="tdLeft"><i class="icon-file-check position-left"></i> Status:</td>
                                    <td class="tdRight text-right" id="detalhes-solicitacao-status">
                                        <select id="comboStatus" name="cmb-status" class="select selectpicker" data-placeholder="Status" tabindex="-1" aria-hidden="true">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tdLeft"><i class="icon-alarm-add position-left"></i> Atualização:</td>
                                    <td class="tdRight text-right" id="detalhes-soliciticao-atualizacao">18 de Dezembro de 2017</td>
                                </tr>
                                <tr>
                                    <td class="tdLeft"><i class="icon-file-plus position-left"></i> Responsável:</td>
                                    <td class="tdRight text-right" id="detalhes-solicitacao-responsavel">
                                        <a href="#">Leonardo Calheiros/COTINF</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tdLeft"><i class="icon-warning position-left"></i> Prioridade:</td>
                                    <td class="tdRight text-right itemPrioridade">
                                        <!-- cardItemSelectPrioridade -->

                                        <select name="select-prioridade" id="cmb-prioridade" data-style="bg-primary" data-width="100%" disabled="true">
                                            <option data-content="<i class='icon-circle-small text-danger'></i> Urgente" value="0"></option>
                                            <option data-content="<i class='icon-circle-small text-info'></i> Alta" value="1"></option>
                                            <option data-content="<i class='icon-circle-small text-primary'></i> Normal" selected value="2"></option>
                                            <option data-content="<i class='icon-circle-small text-success'></i> Baixa" value="3"></option>
                                        </select>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="tdLeft"><i class="icon-alarm-check position-left"></i> Criação:</td>
                                    <td class="tdRight text-right" id="detalhes-solicitacao-criacao">01 de Novembro de 2017</td>
                                </tr>
                                <tr>
                                    <td class="tdLeft"><i class="icon-user position-left"></i> Solicitante:</td>
                                    <td class="tdRight text-right" id="detalhes-solicitacao-solicitante"><a href="#">Alex Amorin/COTINF</a></td>
                                </tr>
                                <tr>
                                    <td class="tdLeft"><i class="icon-users2 position-left"></i> Secundário:</td>
                                    <td class="tdRight text-right" id="detalhes-solicitacao-solicitante-sec"><a href="#">José Neto/COTINF</a></td>
                                </tr>
                                <tr>
                                    <td class="tdLeft"><i class="icon-database4 position-left"></i> Produto:</td>
                                    <td class="tdRight text-right" id="detalhes-solicitacao-produto"><a href="#">Jubarte</a></td>
                                </tr>
                                <tr>
                                    <td class="tdLeft"><i class="icon-display position-left"></i> Equipamento:</td>
                                    <td class="tdRight text-right" id="detalhes-solicitacao-equipamento"><a href="#">110533</a></td>
                                </tr>
                                </tbody>
                            </table>

                            <!--<div class="panel-footer panel-footer-condensed">
                                <div class="heading-elements">
                                    <ul class="list-inline list-inline-condensed heading-text pull-right">
                                        <li><a href="#" class="text-default"><i class="icon-pencil7"></i></a></li>
                                        <li><a href="#" class="text-default"><i class="icon-bin"></i></a></li>
                                    </ul>
                                </div>
                            </div>-->
                        </div>
                        <!-- /task details -->


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


                        <!-- Assigned users -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title"><i class="icon-users position-left"></i> Equipe</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel-body">
                                <ul class="media-list" id="listaUsuarios">
                                    <li class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img src="../../cdn/Vendor/limitless/material/images/placeholder.jpg" class="img-sm img-circle" alt="">
                                                <span class="badge bg-warning-400 media-badge">6</span>
                                            </a>
                                        </div>

                                        <div class="media-body media-middle text-semibold">
                                            Alex Amorin
                                            <div class="media-annotation">Chefe de Equipe</div>
                                        </div>

                                        <div class="media-right media-middle">
                                            <ul class="icons-list text-nowrap">
                                                <li>
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-more2"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a href="#"><i class="icon-comment-discussion pull-right"></i> Iniciar chat</a></li>
                                                        <li><a href="#"><i class="icon-phone2 pull-right"></i> Fazer Chamada</a></li>
                                                        <li><a href="#"><i class="icon-mail5 pull-right"></i> Enviar e-mail</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="#"><i class="icon-statistics pull-right"></i> Estatísticas</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img src="../../cdn/Vendor/limitless/material/images/placeholder.jpg" class="img-sm img-circle" alt="">
                                                <span class="badge bg-warning-400 media-badge">9</span>
                                            </a>
                                        </div>

                                        <div class="media-body media-middle text-semibold">
                                            Isaque Neves
                                            <div class="media-annotation">Programador</div>
                                        </div>

                                        <div class="media-right media-middle">
                                            <ul class="icons-list text-nowrap">
                                                <li>
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-more2"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a href="#"><i class="icon-comment-discussion pull-right"></i> Iniciar chat</a></li>
                                                        <li><a href="#"><i class="icon-phone2 pull-right"></i> Fazer Chamada</a></li>
                                                        <li><a href="#"><i class="icon-mail5 pull-right"></i> Enviar e-mail</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="#"><i class="icon-statistics pull-right"></i> Estatísticas</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="media">
                                        <div class="media-left">
                                            <a href="#"><img src="../../cdn/Vendor/limitless/material/images/placeholder.jpg" class="img-sm img-circle" alt=""></a>
                                        </div>

                                        <div class="media-body media-middle text-semibold">
                                            José Amaro Neto
                                            <div class="media-annotation">Programador</div>
                                        </div>

                                        <div class="media-right media-middle">
                                            <ul class="icons-list text-nowrap">
                                                <li>
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-more2"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a href="#"><i class="icon-comment-discussion pull-right"></i> Iniciar chat</a></li>
                                                        <li><a href="#"><i class="icon-phone2 pull-right"></i> Fazer Chamada</a></li>
                                                        <li><a href="#"><i class="icon-mail5 pull-right"></i> Enviar e-mail</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="#"><i class="icon-statistics pull-right"></i> Estatísticas</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="media">
                                        <div class="media-left">
                                            <a href="#"><img src="../../cdn/Vendor/limitless/material/images/placeholder.jpg" class="img-sm img-circle" alt=""></a>
                                        </div>

                                        <div class="media-body media-middle text-semibold">
                                            Leonardo Calheiros
                                            <div class="media-annotation">Web Designer</div>
                                        </div>

                                        <div class="media-right media-middle">
                                            <ul class="icons-list text-nowrap">
                                                <li>
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-more2"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a href="#"><i class="icon-comment-discussion pull-right"></i> Iniciar chat</a></li>
                                                        <li><a href="#"><i class="icon-phone2 pull-right"></i> Fazer Chamada</a></li>
                                                        <li><a href="#"><i class="icon-mail5 pull-right"></i> Enviar e-mail</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="#"><i class="icon-statistics pull-right"></i> Estatísticas</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="media">
                                        <div class="media-left">
                                            <a href="#"><img src="../../cdn/Vendor/limitless/material/images/placeholder.jpg" class="img-sm img-circle" alt=""></a>
                                        </div>

                                        <div class="media-body media-middle text-semibold">
                                            Marcos Queiroz
                                            <div class="media-annotation">Analista de Sistemas</div>
                                        </div>

                                        <div class="media-right media-middle">
                                            <ul class="icons-list text-nowrap">
                                                <li>
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-more2"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a href="#"><i class="icon-comment-discussion pull-right"></i> Iniciar chat</a></li>
                                                        <li><a href="#"><i class="icon-phone2 pull-right"></i> Fazer Chamada</a></li>
                                                        <li><a href="#"><i class="icon-mail5 pull-right"></i> Enviar e-mail</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="#"><i class="icon-statistics pull-right"></i> Estatísticas</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /assigned users -->

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


<!-- default modal -->

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


<!-- end modal -->
<template id="modal-custom">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label control-label col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">Comentário</label>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <textarea name="mensagem" rows="3" cols="5" class="form-control" placeholder="Digite o seu comentário"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="template-list-files">
    <li class="media">
        <div class="media-left media-middle">
            <i class="icon-file-word icon-2x text-muted icon"></i>
        </div>

        <div class="media-body">
            <span class="file-name"></span>
            <ul class="list-inline list-inline-separate list-inline-condensed text-size-mini text-muted">
                <li class="file-size"></li>
                <li>
                    Adicionado por <a href="#" ><span class="file-by"></span></a>
                </li>
            </ul>
        </div>
        <div class="media-right media-middle">
            <ul class="icons-list">
                <li class="li-remove-file">
                </li>
            </ul>
        </div>
    </li>
</template>

<template id="template-historico">
    <li class="media">
        <div class="media-left">
            <a href="#">
                <img src="/cdn/Vendor/limitless/material/images/placeholder.jpg" class="img-circle img-sm historico-u-imagem" alt="">
            </a>
        </div>
        <div class="media-body">
            <div class="media-heading">
                <a href="#" class="text-semibold historico-u-nome"></a>
                <span class="media-annotation dotted historico-horario"></span>
            </div>
            <p class="historico-mensagem"></p>
        </div>
    </li>
</template>

<template id="media-usuario">
    <li class="media">
        <a href="#" class="media-left">
            <img class="img-sm img-circle data-avatar" alt="">
            <span class="badge bg-warning-400 media-badge data-badge"></span>
        </a>
        <div class="media-body">
            <a href="#" class="media-heading text-semibold data-nome"></a>
            <span class="text-size-mini text-muted display-block data-cargo"></span>
        </div>
        <div class="media-right media-middle">
            <span class="status-mark bg-success"></span>
        </div>
    </li>
</template>


</body>
</html>
