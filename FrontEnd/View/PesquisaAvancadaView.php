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
    <!--<script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/pages/form_multiselect.js"></script>-->
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/notifications/jgrowl.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ui/ripple.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/tags/tagsinput.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/tags/tokenfield.min.js"></script>
    <!-- /theme JS files -->

    <script type="text/javascript" src="/cdn/Vendor/bootstrap-select-1.12.4/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/bootstrap-select-1.12.4/defaults-pt_BR.min.js"></script>

    <link href="/cdn/Assets/css/jSwitch.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/jubarteStyle.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/ModernTreeView.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/modernDataTable.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/moment.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/locale/pt-br.js"></script>

    <script type="text/javascript" src="/cdn/utils/utils.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernBlockUI.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModalAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/RESTClient.js"></script>
    <script type="text/javascript" src="/cdn/utils/LoaderAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernDataTable.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernTreeView.js"></script>

    <script type="text/javascript" src="ViewModel/Constants.js"></script>
    <script type="text/javascript" src="ViewModel/PesquisaAvancadaViewModel.js"></script>

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
                                        <h4><i class="icon-search4 position-left"></i> <span class="text-semibold">Pesquisa Avançada</span></h4>
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
                                <div class="row text-right" style="margin-right: 20px;">
                                    <a href="grid" class="btn bg-orange btn-labeled heading-btn legitRipple"><b><i class="icon-inbox"></i></b>Meus Atendimentos</a>
                                    <a href="minhasSolicitacoes" class="btn bg-blue btn-labeled heading-btn legitRipple"><b><i class="icon-task"></i></b>Minhas Solicitações</a>
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

                                <div class="panel panel-body">
                                    <div class="row">
                                        <!--<div class="col-md-4">
                                            <label><span class="text-semibold">Pesquisar</span></label>
                                            <div class="has-feedback has-feedback-left">
                                                <input type="search" class="form-control"
                                                       placeholder="Utilize ponto e vírgula(;) para separar buscas...">
                                                <div class="form-control-feedback">
                                                    <i class="icon-search4 text-size-base text-muted"></i>
                                                </div>
                                            </div>
                                        </div>-->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><span class="text-semibold">Prioridade</span></label>
                                                <div class="multi-select-full">
                                                    <select multiple name="prioridade">
                                                        <option value="0">Urgente</option>
                                                        <option value="1">Alta</option>
                                                        <option value="2">Normal</option>
                                                        <option value="3">Baixa</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><span class="text-semibold">Status</span></label>
                                                <div class="multi-select-full">
                                                    <select multiple data-selected-text-format="count > 3" name="status">
                                                        <option value="0">Aberto</option>
                                                        <option value="1">Em Andamento</option>
                                                        <option value="2">Pendente</option>
                                                        <option value="3">Concluído</option>
                                                        <option value="4">Cancelado</option>
                                                        <option value="5">Duplicado</option>
                                                        <option value="6">Sem Solução</option>
                                                        <option data-divider="true"></option>
                                                        <option value="encerrados">Encerrados</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><span class="text-semibold">Lotação (Criado por)</span></label>
                                                <input type="text" name="organograma" class="form-control cursor-pointer" placeholder="Selecione a lotação">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><span class="text-semibold">Setor (Responsável Atual)</span></label>
                                                <input type="text" name="setor" class="form-control cursor-pointer" placeholder="Selecione o setor">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!--<div class="col-md-8">
                                            <!-- Filtros Ativos --
                                            <div class="form-group mb-5">
                                                <label><span class="text-semibold">Filtros Ativos</span></label>
                                                <div class="bootstrap-tagsinput">
                                                    <span class="tag label bg-orange">2018
                                                        <span data-role="remove"></span>
                                                    </span>
                                                    <span class="tag label bg-blue">URGENTE
                                                        <span data-role="remove"></span>
                                                    </span>
                                                    <span class="tag label bg-indigo">Pendente
                                                        <span data-role="remove"></span>
                                                    </span>
                                                    <span class="tag label bg-orange">Tecnologia
                                                        <span data-role="remove"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            !-- /Filtros Ativos --
                                        </div>-->
                                        <div class="col-md-8">
                                            <label><span class="text-semibold">Pesquisar</span></label>
                                            <div class="form-group">
                                                <input type="text" name="pesquisa" class="form-control tokenfield" placeholder="Enter para pesquisar...">
                                            </div>

                                            <!--<div class="has-feedback has-feedback-left">

                                                <div class="form-control-feedback">
                                                    <i class="icon-search4 text-size-base text-muted"></i>
                                                </div>-->

                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><span class="text-semibold">Período - A partir de</span></label>
                                                <div class="has-feedback has-feedback-left">
                                                    <input type="date" class="form-control"
                                                           placeholder="00/00/0000">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-calendar2 text-size-base text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><span class="text-semibold">Período - Até</span></label>
                                                <div class="has-feedback has-feedback-left">
                                                    <input type="date" class="form-control"
                                                           placeholder="00/00/0000">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-calendar2 text-size-base text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Detached content -->
                                <div class="container-detached">
                                    <div class="content-detached">
                                        <div class="panel panel-body no-padding">
                                            <div class="modernDataTable">
                                                <table id="tabelaSolicitacao" class="table tasks-list table-lg dataTable no-footer">
                                                    <thead>
                                                    <tr style="height: 60px">
                                                        <th class="pl-20">Número</th>
                                                        <th>Descrição</th>
                                                        <th>Prioridade</th>
                                                        <th>Abertura</th>
                                                        <th>Fechamento</th>
                                                        <th>Status</th>
                                                        <th>Participantes</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div id="solicitacaoTabela">
                                    <div class="panel panel-white">
                                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="datatable-scroll-lg">
                                                <table class="table tasks-list table-lg dataTable no-footer" role="grid">
                                                    <thead>
                                                    <tr role="row">
                                                        <th aria-label="Número">Número</th>
                                                        <th aria-label="Descrição">Descrição</th>
                                                        <th aria-label="Prioridade">Prioridade</th>
                                                        <th aria-label="Criação">Criação</th>
                                                        <th aria-label="Última Atualização">Última Atualização</th>
                                                        <th aria-label="Status">Status</th>
                                                        <th aria-label="Participantes">Participantes</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr role="row" class="odd">
                                                        <td class="text-semibold text-danger sorting_1">
                                                            #2017.12041641114567
                                                        </td>
                                                        <td>
                                                            <div class="text-muted">Grumbled ripely eternal sniffed the when hello less much...</div>
                                                        </td>
                                                        <td>
                                                            <span class="label label-danger">URGENTE</span>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i class="icon-calendar2 position-left"></i></div>
                                                                <span>28/11/2017</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i class="icon-calendar2 position-left"></i></div>
                                                                <span>28/11/2017</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span>Pendente</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                             class="img-circle img-xs" alt=""></a>
                                                            <a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                             class="img-circle img-xs" alt=""></a>
                                                            <a href="#" class="text-default">&nbsp;<i class="icon-plus22"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="text-semibold text-primary sorting_1">
                                                            #2017.12041641114567
                                                        </td>
                                                        <td>
                                                            <div class="text-muted">Grumbled ripely eternal sniffed the when hello less much...</div>
                                                        </td>
                                                        <td>
                                                            <span class="label label-primary">NORMAL</span>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i class="icon-calendar2 position-left"></i></div>
                                                                <span>28/11/2017</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i class="icon-calendar2 position-left"></i></div>
                                                                <span>28/11/2017</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span>Pendente</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                             class="img-circle img-xs" alt=""></a>
                                                            <a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                             class="img-circle img-xs" alt=""></a>
                                                            <a href="#" class="text-default">&nbsp;<i class="icon-plus22"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="text-semibold text-info sorting_1">
                                                            #2017.12041641114567
                                                        </td>
                                                        <td>
                                                            <div class="text-muted">Grumbled ripely eternal sniffed the when hello less much...</div>
                                                        </td>
                                                        <td>
                                                            <span class="label label-info">ALTA</span>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i class="icon-calendar2 position-left"></i></div>
                                                                <span>28/11/2017</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i class="icon-calendar2 position-left"></i></div>
                                                                <span>28/11/2017</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span>Pendente</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                             class="img-circle img-xs" alt=""></a>
                                                            <a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                             class="img-circle img-xs" alt=""></a>
                                                            <a href="#" class="text-default">&nbsp;<i class="icon-plus22"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="text-semibold text-success sorting_1">
                                                            #2017.12041641114567
                                                        </td>
                                                        <td>
                                                            <div class="text-muted">Grumbled ripely eternal sniffed the when hello less much...</div>
                                                        </td>
                                                        <td>
                                                            <span class="label label-success">BAIXA</span>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i class="icon-calendar2 position-left"></i></div>
                                                                <span>28/11/2017</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i class="icon-calendar2 position-left"></i></div>
                                                                <span>28/11/2017</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span>Pendente</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                             class="img-circle img-xs" alt=""></a>
                                                            <a href="#"><img src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                             class="img-circle img-xs" alt=""></a>
                                                            <a href="#" class="text-default">&nbsp;<i class="icon-plus22"></i></a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="datatable-footer">
                                                <div class="dataTables_info">Exibindo 1 até 4 de 4 solicitações</div>
                                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                                    <a class="paginate_button previous disabled">←</a>
                                                    <span>
                                                        <a class="paginate_button current">1</a>
                                                    </span>
                                                    <a class="paginate_button next disabled">→</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>




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

<!-- Modal Default -->
<div id="defaultModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-large">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Selecione...</h6>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- Modal Default -->

</body>
</html>