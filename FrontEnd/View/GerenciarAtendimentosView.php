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
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/libraries/jquery_ui/widgets.min.js"></script>

    <script type="text/javascript" src="/cdn/Vendor/bootstrap-select-1.12.4/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/bootstrap-select-1.12.4/defaults-pt_BR.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/core/app.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/notifications/jgrowl.min.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ui/ripple.min.js"></script>
    <!-- /theme JS files -->

    <!-- Editor de Texto -->
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ckeditor/ckeditorCustom/ckeditor.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/limitless/material/js/plugins/ckeditor/ckeditorCustom/adapters/jquery.js"></script>
    <!-- /Editor de Texto -->


    <link href="/cdn/Assets/css/jSwitch.css" rel="stylesheet" type="text/css">
    <link href="/cdn/Assets/css/jubarteStyle.css" rel="stylesheet" type="text/css">
    <link href="Assets/css/GerenciarAtendimentosView.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/moment.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment/2.19.1/locale/pt-br.js"></script>
    <script type="text/javascript" src="/cdn/Vendor/moment-busines-time/mbt.js"></script>

    <script type="text/javascript" src="/cdn/utils/utils.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernBlockUI.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModalAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/LoaderAPI.js"></script>
    <script type="text/javascript" src="/cdn/utils/RESTClient.js"></script>
    <script type="text/javascript" src="/cdn/utils/ModernDataGrid.js"></script>

    <script type="text/javascript" src="ViewModel/Constants.js"></script>
    <script type="text/javascript" src="ViewModel/GerenciarAtendimentosViewModel.js"></script>
    <script type="text/javascript">

        // Internet Explorer 8 or lower
        function redirect(url) {
            var ua = navigator.userAgent.toLowerCase(), isIE = ua.indexOf('msie') !== -1, version = parseInt(ua.substr(4, 2), 10);

            // Internet Explorer 8 and lower
            if (isIE && version < 9) {
                var link = document.createElement('a');
                link.href = url;
                document.body.appendChild(link);
                link.click();
            }

            // All other browsers can use the standard window.location.href (they don't lose HTTP_REFERER like Internet Explorer 8 & lower does)
            else {
                window.location.href = url;
            }
        }
    </script>
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

                    <!-- Page header ->
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">
                                <h4><i class="icon-grid position-left"></i> <span class="text-semibold">Meus Atendimentos</span>
                                </h4>
                                <!--<form action="#">
                                    <div class="has-feedback has-feedback-left">
                                        <input type="search" class="form-control" placeholder="Pesquisar...">
                                        <div class="form-control-feedback">
                                            <i class="icon-search4 text-size-base text-muted"></i>
                                        </div>
                                    </div>
                                </form>->

                                <ul class="breadcrumb position-right">

                                </ul>
                            </div>

                            <div class="heading-elements">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm">
                                            <select id="selectFiltroSetorUsuario" class="select form-control" tabindex="-1" aria-hidden="true">
                                                <option selected>CONTIF</option>
                                                <option>SEMAD</option>
                                                <option>SEMED</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <!--<a href="#" class="btn bg-orange btn-labeled heading-btn"><b><i class="icon-inbox"></i></b>
                                    Criar Comunicação
                                </a>->
                                            <a href="#" class="btn bg-blue btn-labeled heading-btn"><b><i class="icon-task"></i></b>
                                                Criar Solicitação
                                            </a>
                                            <a href="#" class="btn bg-indigo btn-icon heading-btn sidebar-detached-hide legitRipple"><!-- sidebar-control hidden-xs  ->
                                                <b>
                                                    <i class="icon-menu"></i>
                                                </b>
                                            </a>
                                            <!--<a href="#" class="btn btn-default btn-icon heading-btn"><i class="icon-gear"></i></a>->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /page header -->

                    <!-- Page header -->
                    <div class="customPageHeader">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="row ">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                                        <h4><i class="icon-grid position-left"></i> <span class="text-semibold">Meus Atendimentos</span></h4>
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
                                    <select name="selectFiltroSetor" class="select-init form-control" tabindex="-1" aria-hidden="true"></select>

                                    <button href="#" class="btn bg-blue btn-labeled heading-btn" name="btnCriar"><b><i class="icon-task"></i></b>
                                        Criar Solicitação
                                    </button>
                                    <button href="#" class="btn bg-indigo btn-labeled heading-btn sidebar-detached-hide legitRipple"><!-- sidebar-control hidden-xs  -->
                                        <b>
                                            <i class="icon-menu"></i>
                                        </b>Ações
                                    </button>
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

                                <!-- Tasks options -->
                                <div class="navbar navbar-default navbar-xs navbar-component">
                                    <ul class="nav navbar-nav no-border visible-xs-block">
                                        <li><a class="text-center collapsed" data-toggle="collapse"
                                               data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
                                    </ul>

                                    <div class="navbar-collapse collapse row navFilter" id="navbar-filter">
                                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="icon-sort-amount-asc"></i>
                                                </div>
                                                <select class="select-init" name="selectOrder">
                                                    <option value="dataAbertura asc" selected>Antigos</option>
                                                    <option value="dataAbertura desc">Recentes</option>
                                                    <option value="status asc">Status</option>
                                                    <option value="prioridade asc">Prioridade</option>
                                                    <option value="nomeResponsavel asc">Responsável</option>
                                                    <option value="nomeOrganograma asc">Lotação</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="icon-filter3"></i>
                                                </div>
                                                <select class="select-init" name="selectStatus">
                                                    <option value="acessivel" selected>Todos Status</option>
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
                                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="icon-filter4"></i>
                                                </div>
                                                <select class="select-init" name="selectPrioridade">
                                                    <option value="null" selected>Todas prioridades</option>
                                                    <option value="0">Urgente</option>
                                                    <option value="1">Alta</option>
                                                    <option value="2">Normal</option>
                                                    <option value="3">Baixa</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 text-center jSwitchPaddingRight">
                                            <div class="jSwitch">
                                                <label>
                                                    Próprias
                                                    <input type="checkbox" name="chkProprios">
                                                    <span class="jThumb jThumbColor"></span>
                                                    Setor
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 text-center jSwitchPaddingRight">
                                            <div class="jSwitch">
                                                <label>
                                                    Lista
                                                    <input type="checkbox" id="checkBoxModoExibicao" checked>
                                                    <span class="jThumb jThumbColor"></span>
                                                    Grade
                                                </label>
                                                <div class="reloadAtendimentos" title="Reiniciar formulário">
                                                    <i id="icoReset" class="icon-reload-alt text-muted"></i>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <!-- /tasks options -->

                                <!-- tasks grid -->
                                <div id="solicitacaoGrade"></div>
                                <!-- /tasks grid -->

                                <!-- tasks table -->
                                <div id="solicitacaoTabela" style="display:none">
                                    <!-- Task manager table -->
                                    <div class="panel panel-white">

                                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="datatable-header">
                                                <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                                    <label><span>Filter:</span> <input type="search" class=""
                                                                                       placeholder="Type to filter..."
                                                                                       aria-controls="DataTables_Table_0"></label>
                                                </div>
                                                <div class="dataTables_length" id="DataTables_Table_0_length">
                                                    <label><span>Show:</span> <select name="DataTables_Table_0_length"
                                                                                      aria-controls="DataTables_Table_0"
                                                                                      class="select2-hidden-accessible"
                                                                                      tabindex="-1" aria-hidden="true">
                                                            <option value="15">15</option>
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="75">75</option>
                                                            <option value="100">100</option>
                                                        </select><span
                                                                class="select2 select2-container select2-container--default"
                                                                dir="ltr" style="width: auto;"><span
                                                                    class="selection"><span
                                                                        class="select2-selection select2-selection--single"
                                                                        role="combobox" aria-haspopup="true"
                                                                        aria-expanded="false" tabindex="0"
                                                                        aria-labelledby="select2-DataTables_Table_0_length-er-container"><span
                                                                            class="select2-selection__rendered"
                                                                            id="select2-DataTables_Table_0_length-er-container"
                                                                            title="25">25</span><span
                                                                            class="select2-selection__arrow"
                                                                            role="presentation"><b
                                                                                role="presentation"></b></span></span></span><span
                                                                    class="dropdown-wrapper" aria-hidden="true"></span></span></label>
                                                </div>
                                            </div>
                                            <div class="datatable-scroll-lg">
                                                <table class="table tasks-list table-lg dataTable no-footer"
                                                       id="DataTables_Table_0" role="grid"
                                                       aria-describedby="DataTables_Table_0_info">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting_desc" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            aria-sort="descending"
                                                            aria-label="Número: activate to sort column ascending"
                                                            style="width: 20px;">Número
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            aria-label="Descrição: activate to sort column ascending"
                                                            style="width: 40%;">Descrição
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            aria-label="Prioridade: activate to sort column ascending"
                                                            style="width: 10%;">Prioridade
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            aria-label="Última Atualização: activate to sort column ascending"
                                                            style="width: 15%;">Última Atualização
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            aria-label="Status: activate to sort column ascending"
                                                            style="width: 15%;">Status
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            aria-label="Participantes: activate to sort column ascending"
                                                            style="width: 15%;">Participantes
                                                        </th>
                                                        <th class="text-center text-muted sorting_disabled"
                                                            style="width: 100px;" rowspan="1" colspan="1" aria-label="
                                                "><i class="icon-checkmark3"></i>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr class="active border-double">
                                                        <td colspan="8" class="text-semibold">Hoje</td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="text-semibold text-danger sorting_1">
                                                            #2017.12041641114567
                                                        </td>

                                                        <td>
                                                            <div class="text-muted">Grumbled ripely eternal sniffed the
                                                                when
                                                                hello less much...
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="#" class="label label-danger dropdown-toggle"
                                                                   data-toggle="dropdown">Urgente
                                                                    <span class="caret"></span></a>
                                                                <ul class="dropdown-menu dropdown-menu-right active">
                                                                    <li class="active"><a href="#"><span
                                                                                    class="status-mark position-left bg-danger"></span>
                                                                            Urgente</a></li>
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-info"></span>
                                                                            Alta</a></li>
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-primary"></span>
                                                                            Normal</a></li>
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-success"></span>
                                                                            Baixa</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i
                                                                            class="icon-calendar2 position-left"></i>
                                                                </div>
                                                                <input type="text"
                                                                       class="form-control datepicker hasDatepicker"
                                                                       value="28/11/2017" id="dp1512650305802">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select name="status"
                                                                    class="select select2-hidden-accessible"
                                                                    data-placeholder="Select status" tabindex="-1"
                                                                    aria-hidden="true">
                                                                <option value="open">Aberto</option>
                                                                <option value="hold">Em Andamento</option>
                                                                <option value="resolved" selected="selected">Pendente
                                                                </option>
                                                                <option value="dublicate">Concluído</option>
                                                                <option value="invalid">Cancelado</option>
                                                                <option value="wontfix">Duplicado</option>
                                                                <option value="closed">Sem Solução</option>
                                                            </select><span
                                                                    class="select2 select2-container select2-container--default"
                                                                    dir="ltr" style="width: 150px;"><span
                                                                        class="selection"><span
                                                                            class="select2-selection select2-selection--single"
                                                                            role="combobox" aria-haspopup="true"
                                                                            aria-expanded="false" tabindex="0"
                                                                            aria-labelledby="select2-status-v0-container"><span
                                                                                class="select2-selection__rendered"
                                                                                id="select2-status-v0-container"
                                                                                title="Pendente">Pendente</span><span
                                                                                class="select2-selection__arrow"
                                                                                role="presentation"><b
                                                                                    role="presentation"></b></span></span></span><span
                                                                        class="dropdown-wrapper"
                                                                        aria-hidden="true"></span></span>
                                                        </td>
                                                        <td>
                                                            <a href="#"><img
                                                                        src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                        class="img-circle img-xs" alt=""></a>
                                                            <a href="#"><img
                                                                        src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                        class="img-circle img-xs" alt=""></a>
                                                            <a href="#" class="text-default">&nbsp;<i
                                                                        class="icon-plus22"></i></a>
                                                        </td>
                                                        <td class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="dropdown">
                                                                    <a href="#" class="dropdown-toggle"
                                                                       data-toggle="dropdown"><i class="icon-menu9"></i></a>
                                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                                        <li><a href="#"><i class="icon-alarm-add"></i>
                                                                                Check in</a>
                                                                        </li>
                                                                        <li><a href="#"><i class="icon-attachment"></i>
                                                                                Anexar
                                                                                screenshot</a>
                                                                        </li>
                                                                        <li><a href="#"><i class="icon-rotate-ccw2"></i>
                                                                                Encaminhar</a></li>
                                                                        <li class="divider"></li>
                                                                        <li><a href="#"><i class="icon-pencil7"></i>
                                                                                Editar
                                                                                Solicitação</a></li>
                                                                        <li><a href="#"><i class="icon-cross2"></i>
                                                                                Remover</a>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="text-semibold text-primary sorting_1">
                                                            #2017.12041641114567
                                                        </td>

                                                        <td>
                                                            <div class="text-muted">Grumbled ripely eternal sniffed the
                                                                when
                                                                hello less much...
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="#" class="label label-primary dropdown-toggle"
                                                                   data-toggle="dropdown">Normal
                                                                    <span class="caret"></span></a>
                                                                <ul class="dropdown-menu dropdown-menu-right active">
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-danger"></span>
                                                                            Urgente</a></li>
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-info"></span>
                                                                            Alta</a></li>
                                                                    <li class="active"><a href="#"><span
                                                                                    class="status-mark position-left bg-primary"></span>
                                                                            Normal</a></li>
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-success"></span>
                                                                            Baixa</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i
                                                                            class="icon-calendar2 position-left"></i>
                                                                </div>
                                                                <input type="text"
                                                                       class="form-control datepicker hasDatepicker"
                                                                       value="28/11/2017" id="dp1512650305803">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select name="status"
                                                                    class="select select2-hidden-accessible"
                                                                    data-placeholder="Select status" tabindex="-1"
                                                                    aria-hidden="true">
                                                                <option value="open">Aberto</option>
                                                                <option value="hold">Em Andamento</option>
                                                                <option value="resolved" selected="selected">Pendente
                                                                </option>
                                                                <option value="dublicate">Concluído</option>
                                                                <option value="invalid">Cancelado</option>
                                                                <option value="wontfix">Duplicado</option>
                                                                <option value="closed">Sem Solução</option>
                                                            </select><span
                                                                    class="select2 select2-container select2-container--default"
                                                                    dir="ltr" style="width: 150px;"><span
                                                                        class="selection"><span
                                                                            class="select2-selection select2-selection--single"
                                                                            role="combobox" aria-haspopup="true"
                                                                            aria-expanded="false" tabindex="0"
                                                                            aria-labelledby="select2-status-4s-container"><span
                                                                                class="select2-selection__rendered"
                                                                                id="select2-status-4s-container"
                                                                                title="Pendente">Pendente</span><span
                                                                                class="select2-selection__arrow"
                                                                                role="presentation"><b
                                                                                    role="presentation"></b></span></span></span><span
                                                                        class="dropdown-wrapper"
                                                                        aria-hidden="true"></span></span>
                                                        </td>
                                                        <td>
                                                            <a href="#"><img
                                                                        src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                        class="img-circle img-xs" alt=""></a>
                                                            <a href="#"><img
                                                                        src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                        class="img-circle img-xs" alt=""></a>
                                                            <a href="#" class="text-default">&nbsp;<i
                                                                        class="icon-plus22"></i></a>
                                                        </td>
                                                        <td class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="dropdown">
                                                                    <a href="#" class="dropdown-toggle"
                                                                       data-toggle="dropdown"><i class="icon-menu9"></i></a>
                                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                                        <li><a href="#"><i class="icon-alarm-add"></i>
                                                                                Check in</a>
                                                                        </li>
                                                                        <li><a href="#"><i class="icon-attachment"></i>
                                                                                Anexar
                                                                                screenshot</a>
                                                                        </li>
                                                                        <li><a href="#"><i class="icon-rotate-ccw2"></i>
                                                                                Encaminhar</a></li>
                                                                        <li class="divider"></li>
                                                                        <li><a href="#"><i class="icon-pencil7"></i>
                                                                                Editar
                                                                                Solicitação</a></li>
                                                                        <li><a href="#"><i class="icon-cross2"></i>
                                                                                Remover</a>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr class="active border-double">
                                                        <td colspan="8" class="text-semibold">Ontem</td>
                                                    </tr>
                                                    <tr role="row" class="odd">
                                                        <td class="text-semibold text-info sorting_1">
                                                            #2017.12041641114567
                                                        </td>

                                                        <td>
                                                            <div class="text-muted">Grumbled ripely eternal sniffed the
                                                                when
                                                                hello less much...
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group dropup">
                                                                <a href="#" class="label label-info dropdown-toggle"
                                                                   data-toggle="dropdown">Alta
                                                                    <span class="caret"></span></a>
                                                                <ul class="dropdown-menu dropdown-menu-right active">
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-danger"></span>
                                                                            Urgente</a></li>
                                                                    <li class="active"><a href="#"><span
                                                                                    class="status-mark position-left bg-info"></span>
                                                                            Alta</a></li>
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-primary"></span>
                                                                            Normal</a></li>
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-success"></span>
                                                                            Baixa</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i
                                                                            class="icon-calendar2 position-left"></i>
                                                                </div>
                                                                <input type="text"
                                                                       class="form-control datepicker hasDatepicker"
                                                                       value="28/11/2017" id="dp1512650305804">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select name="status"
                                                                    class="select select2-hidden-accessible"
                                                                    data-placeholder="Select status" tabindex="-1"
                                                                    aria-hidden="true">
                                                                <option value="open">Aberto</option>
                                                                <option value="hold">Em Andamento</option>
                                                                <option value="resolved" selected="selected">Pendente
                                                                </option>
                                                                <option value="dublicate">Concluído</option>
                                                                <option value="invalid">Cancelado</option>
                                                                <option value="wontfix">Duplicado</option>
                                                                <option value="closed">Sem Solução</option>
                                                            </select><span
                                                                    class="select2 select2-container select2-container--default"
                                                                    dir="ltr" style="width: 150px;"><span
                                                                        class="selection"><span
                                                                            class="select2-selection select2-selection--single"
                                                                            role="combobox" aria-haspopup="true"
                                                                            aria-expanded="false" tabindex="0"
                                                                            aria-labelledby="select2-status-5t-container"><span
                                                                                class="select2-selection__rendered"
                                                                                id="select2-status-5t-container"
                                                                                title="Pendente">Pendente</span><span
                                                                                class="select2-selection__arrow"
                                                                                role="presentation"><b
                                                                                    role="presentation"></b></span></span></span><span
                                                                        class="dropdown-wrapper"
                                                                        aria-hidden="true"></span></span>
                                                        </td>
                                                        <td>
                                                            <a href="#"><img
                                                                        src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                        class="img-circle img-xs" alt=""></a>
                                                            <a href="#"><img
                                                                        src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                        class="img-circle img-xs" alt=""></a>
                                                            <a href="#" class="text-default">&nbsp;<i
                                                                        class="icon-plus22"></i></a>
                                                        </td>
                                                        <td class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="dropdown dropup">
                                                                    <a href="#" class="dropdown-toggle"
                                                                       data-toggle="dropdown"><i class="icon-menu9"></i></a>
                                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                                        <li><a href="#"><i class="icon-alarm-add"></i>
                                                                                Check in</a>
                                                                        </li>
                                                                        <li><a href="#"><i class="icon-attachment"></i>
                                                                                Anexar
                                                                                screenshot</a>
                                                                        </li>
                                                                        <li><a href="#"><i class="icon-rotate-ccw2"></i>
                                                                                Encaminhar</a></li>
                                                                        <li class="divider"></li>
                                                                        <li><a href="#"><i class="icon-pencil7"></i>
                                                                                Editar
                                                                                Solicitação</a></li>
                                                                        <li><a href="#"><i class="icon-cross2"></i>
                                                                                Remover</a>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="text-semibold text-success sorting_1">
                                                            #2017.12041641114567
                                                        </td>

                                                        <td>
                                                            <div class="text-muted">Grumbled ripely eternal sniffed the
                                                                when
                                                                hello less much...
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group dropup">
                                                                <a href="#" class="label label-success dropdown-toggle"
                                                                   data-toggle="dropdown">Baixa
                                                                    <span class="caret"></span></a>
                                                                <ul class="dropdown-menu dropdown-menu-right active">
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-danger"></span>
                                                                            Urgente</a></li>
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-info"></span>
                                                                            Alta</a></li>
                                                                    <li><a href="#"><span
                                                                                    class="status-mark position-left bg-primary"></span>
                                                                            Normal</a></li>
                                                                    <li class="active"><a href="#"><span
                                                                                    class="status-mark position-left bg-success"></span>
                                                                            Baixa</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-transparent">
                                                                <div class="input-group-addon"><i
                                                                            class="icon-calendar2 position-left"></i>
                                                                </div>
                                                                <input type="text"
                                                                       class="form-control datepicker hasDatepicker"
                                                                       value="28/11/2017" id="dp1512650305805">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select name="status"
                                                                    class="select select2-hidden-accessible"
                                                                    data-placeholder="Select status" tabindex="-1"
                                                                    aria-hidden="true">
                                                                <option value="open">Aberto</option>
                                                                <option value="hold">Em Andamento</option>
                                                                <option value="resolved" selected="selected">Pendente
                                                                </option>
                                                                <option value="dublicate">Concluído</option>
                                                                <option value="invalid">Cancelado</option>
                                                                <option value="wontfix">Duplicado</option>
                                                                <option value="closed">Sem Solução</option>
                                                            </select><span
                                                                    class="select2 select2-container select2-container--default"
                                                                    dir="ltr" style="width: 150px;"><span
                                                                        class="selection"><span
                                                                            class="select2-selection select2-selection--single"
                                                                            role="combobox" aria-haspopup="true"
                                                                            aria-expanded="false" tabindex="0"
                                                                            aria-labelledby="select2-status-1z-container"><span
                                                                                class="select2-selection__rendered"
                                                                                id="select2-status-1z-container"
                                                                                title="Pendente">Pendente</span><span
                                                                                class="select2-selection__arrow"
                                                                                role="presentation"><b
                                                                                    role="presentation"></b></span></span></span><span
                                                                        class="dropdown-wrapper"
                                                                        aria-hidden="true"></span></span>
                                                        </td>
                                                        <td>
                                                            <a href="#"><img
                                                                        src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                        class="img-circle img-xs" alt=""></a>
                                                            <a href="#"><img
                                                                        src="/cdn/Vendor/limitless/material/images/placeholder.jpg"
                                                                        class="img-circle img-xs" alt=""></a>
                                                            <a href="#" class="text-default">&nbsp;<i
                                                                        class="icon-plus22"></i></a>
                                                        </td>
                                                        <td class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="dropdown dropup">
                                                                    <a href="#" class="dropdown-toggle"
                                                                       data-toggle="dropdown"><i class="icon-menu9"></i></a>
                                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                                        <li><a href="#"><i class="icon-alarm-add"></i>
                                                                                Check in</a>
                                                                        </li>
                                                                        <li><a href="#"><i class="icon-attachment"></i>
                                                                                Anexar
                                                                                screenshot</a>
                                                                        </li>
                                                                        <li><a href="#"><i class="icon-rotate-ccw2"></i>
                                                                                Encaminhar</a></li>
                                                                        <li class="divider"></li>
                                                                        <li><a href="#"><i class="icon-pencil7"></i>
                                                                                Editar
                                                                                Solicitação</a></li>
                                                                        <li><a href="#"><i class="icon-cross2"></i>
                                                                                Remover</a>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="datatable-footer">
                                                <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                                     aria-live="polite">Showing 1 to 4 of 4 entries
                                                </div>
                                                <div class="dataTables_paginate paging_simple_numbers"
                                                     id="DataTables_Table_0_paginate"><a
                                                            class="paginate_button previous disabled"
                                                            aria-controls="DataTables_Table_0" data-dt-idx="0"
                                                            tabindex="0" id="DataTables_Table_0_previous">←</a><span><a
                                                                class="paginate_button current"
                                                                aria-controls="DataTables_Table_0" data-dt-idx="1"
                                                                tabindex="0">1</a></span><a
                                                            class="paginate_button next disabled"
                                                            aria-controls="DataTables_Table_0" data-dt-idx="2"
                                                            tabindex="0" id="DataTables_Table_0_next">→</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /task manager table -->
                                </div>
                                <!-- /tasks table -->

                            </div>
                        </div>
                        <!-- /detached content -->


                        <!-- Detached sidebar -->
                        <div class="sidebar-detached">
                            <div class="sidebar sidebar-default">
                                <div class="sidebar-content">

                                    <!-- Search task -->
                                    <div class="sidebar-category">
                                        <div class="category-title">
                                            <span>Pesquisar Solicitação</span>
                                            <ul class="icons-list">
                                                <li><a href="#" data-action="collapse"></a></li>
                                            </ul>
                                        </div>

                                        <div class="category-content">
                                            <div action="#">
                                                <div class="has-feedback has-feedback-left">
                                                    <input name="inputSearch" type="search" class="form-control"
                                                           placeholder="Tecle Enter para pesquisar...">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-search4 text-size-base text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /search task -->


                                    <!-- Action buttons / BOTÕES ->
                                    <div class="sidebar-category">
                                        <div class="category-title">
                                            <span>Botões</span>
                                            <ul class="icons-list">
                                                <li><a href="#" data-action="collapse"></a></li>
                                            </ul>
                                        </div>

                                        <div class="category-content">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <button class="btn bg-teal-400 btn-block btn-float btn-float-lg"
                                                            type="button"><i class="icon-git-branch"></i>
                                                        <span>Sistemas</span></button>
                                                    <button class="btn bg-purple-300 btn-block btn-float btn-float-lg"
                                                            type="button"><i class="icon-mail-read"></i>
                                                        <span>Entrada</span></button>
                                                </div>

                                                <div class="col-xs-6">
                                                    <button class="btn bg-warning-400 btn-block btn-float btn-float-lg"
                                                            type="button"><i class="icon-stats-bars"></i>
                                                        <span>Ouvidoria</span></button>
                                                    <button class="btn bg-blue btn-block btn-float btn-float-lg"
                                                            type="button"><i class="icon-people"></i> <span>Usuários</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /action buttons -->


                                    <!-- Task navigation -->
                                    <div class="sidebar-category">
                                        <div class="category-title">
                                            <span>Informações</span>
                                            <ul class="icons-list">
                                                <li><a href="#" data-action="collapse"></a></li>
                                            </ul>
                                        </div>

                                        <div class="category-content no-padding">
                                            <ul class="navigation navigation-alt navigation-accordion">
                                                <li><a href="#">
                                                        <i class="icon-file-plus"></i> Atendimentos Ativos
                                                        <span class="badge badge-default" id="badgeAtendAtivos"></span>
                                                    </a></li>
                                                <!--<li><a href="#">
                                                    <i class="icon-file-plus"></i> Atendimentos Vencendo
                                                    <span class="badge bg-orange-400 text-muted" id="badgeAtendVencendo">XX</span>
                                                </a></li>
                                                <li><a href="#">
                                                    <i class="icon-file-plus"></i> Atendimentos Vencidos
                                                    <span class="badge badge-danger" id="badgeAtendVencido">XX</span>
                                                </a></li>-->


                                                <!--<li class="navigation-header">Ações</li>
                                                <li><a href="#"><i class="icon-googleplus5"></i> Criar Solicitação</a>
                                                </li>
                                                <li><a href="#"><i class="icon-compose"></i> Editar Solicitação</a></li>
                                                <li><a href="#"><i class="icon-portfolio"></i> Criar Projeto</a></li>
                                                <li class="navigation-header">Tarefas</li>
                                                <li><a href="#"><i class="icon-files-empty"></i> Todas as
                                                        Solicitações</a>
                                                </li>
                                                <li><a href="#"><i class="icon-file-plus"></i> Tarefas Ativas <span
                                                                class="badge badge-default">28</span></a></li>
                                                <li><a href="#"><i class="icon-file-check"></i> Tarefas Fechadas</a>
                                                </li>
                                                <li class="navigation-header">Usuários</li>
                                                <li><a href="#"><i class="icon-user-plus"></i> Adicionar Usuários <span
                                                                class="label label-success">94 online</span></a></li>
                                                <li><a href="#"><i class="icon-collaboration"></i> Criar Equipe</a></li>
                                                <li class="navigation-divider"></li>
                                                <li><a href="#"><i class="icon-reading"></i> Assigned to me <span
                                                                class="badge badge-info">86</span></a></li>
                                                <li><a href="#"><i class="icon-make-group"></i> Assigned to my team <span
                                                                class="badge badge-info">47</span></a></li>
                                                <li><a href="#"><i class="icon-cog3"></i> Configurações</a></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /task navigation -->


                                    <!-- Assigned users -->
                                    <div class="sidebar-category">
                                        <div class="category-title">
                                            <span>Equipe</span>
                                            <ul class="icons-list">
                                                <li><a href="#" data-action="collapse"></a></li>
                                            </ul>
                                        </div>

                                        <div class="category-content">
                                            <ul class="media-list" id="listaUsuarios"></ul>
                                        </div>
                                    </div>
                                    <!-- /assigned users -->


                                    <!-- Revisions ->
                                    <div class="sidebar-category">
                                        <div class="category-title">
                                            <span>Revisions</span>
                                            <ul class="icons-list">
                                                <li><a href="#" data-action="collapse"></a></li>
                                            </ul>
                                        </div>

                                        <div class="category-content">
                                            <ul class="media-list">
                                                <li class="media">
                                                    <div class="media-left">
                                                        <a href="#" class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-pull-request"></i></a>
                                                    </div>

                                                    <div class="media-body">
                                                        Drop the IE <a href="#">specific hacks</a> for temporal inputs
                                                        <div class="media-annotation">4 minutes ago</div>
                                                    </div>
                                                </li>

                                                <li class="media">
                                                    <div class="media-left">
                                                        <a href="#" class="btn border-warning text-warning btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-commit"></i></a>
                                                    </div>

                                                    <div class="media-body">
                                                        Add full font overrides for popovers and tooltips
                                                        <div class="media-annotation">36 minutes ago</div>
                                                    </div>
                                                </li>

                                                <li class="media">
                                                    <div class="media-left">
                                                        <a href="#" class="btn border-info text-info btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-branch"></i></a>
                                                    </div>

                                                    <div class="media-body">
                                                        <a href="#">Chris Arney</a> created a new <span class="text-semibold">Design</span> branch
                                                        <div class="media-annotation">2 hours ago</div>
                                                    </div>
                                                </li>

                                                <li class="media">
                                                    <div class="media-left">
                                                        <a href="#" class="btn border-success text-success btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-merge"></i></a>
                                                    </div>

                                                    <div class="media-body">
                                                        <a href="#">Eugene Kopyov</a> merged <span class="text-semibold">Master</span> and <span class="text-semibold">Dev</span> branches
                                                        <div class="media-annotation">Dec 18, 18:36</div>
                                                    </div>
                                                </li>

                                                <li class="media">
                                                    <div class="media-left">
                                                        <a href="#" class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm"><i class="icon-git-pull-request"></i></a>
                                                    </div>

                                                    <div class="media-body">
                                                        Have Carousel ignore keyboard events
                                                        <div class="media-annotation">Dec 12, 05:46</div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /revisions -->


                                    <!-- Completeness stats -->
                                    <div class="sidebar-category">
                                        <div class="category-title">
                                            <span>Atendimentos por Prioridade</span>
                                            <ul class="icons-list">
                                                <li><a href="#" data-action="collapse"></a></li>
                                            </ul>
                                        </div>

                                        <div class="category-content">
                                            <ul class="progress-list estatisticasPrioridades">
                                                <li data-key="0">
                                                    <label>Urgente <span class="atendimentos"></span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar progress-bar-danger">
                                                            <span class="sr-only">10% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li data-key="1">
                                                    <label>Alta <span class="atendimentos"></span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar progress-bar-info">
                                                            <span class="sr-only">30% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li data-key="2">
                                                    <label>Normal <span class="atendimentos"></span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar progress-bar-primary">
                                                            <span class="sr-only">50% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li data-key="3">
                                                    <label>Baixa <span class="atendimentos"></span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar progress-bar-success">
                                                            <span class="sr-only">10% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /completeness stats -->

                                    <!-- Completeness stats -->
                                    <div class="sidebar-category">
                                        <div class="category-title">
                                            <span>Atendimentos por Status</span>
                                            <ul class="icons-list">
                                                <li><a href="#" data-action="collapse"></a></li>
                                            </ul>
                                        </div>

                                        <div class="category-content">
                                            <ul class="progress-list estatisticasStatus">
                                                <li data-key="0">
                                                    <label>Aberto <span class="atendimentos">20%</span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar bg-indigo"
                                                             style="width: 20%">
                                                            <span class="sr-only">20% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li data-key="1">
                                                    <label>Em Andamento <span class="atendimentos">35%</span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar bg-indigo"
                                                             style="width: 35%">
                                                            <span class="sr-only">35% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li data-key="2">
                                                    <label>Pendente <span class="atendimentos">15%</span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar bg-indigo"
                                                             style="width: 15%">
                                                            <span class="sr-only">15% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <!--<li>
                                                    <label>Concluído <span>23%</span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar progress-bar-primary"
                                                             style="width: 23%">
                                                            <span class="sr-only">23% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Cancelado <span>5%</span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar progress-bar-primary"
                                                             style="width: 5%">
                                                            <span class="sr-only">5% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Duplicado <span>1%</span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar progress-bar-primary"
                                                             style="width: 1%">
                                                            <span class="sr-only">1% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Sem Solução <span>1%</span></label>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar progress-bar-primary"
                                                             style="width: 1%">
                                                            <span class="sr-only">1% Complete</span>
                                                        </div>
                                                    </div>
                                                </li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /completeness stats -->

                                </div>
                            </div>
                        </div>
                        <!-- /detached sidebar -->

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

<template id="cardAtendimento">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
        <div class="cardItem panel border-left-lg"><!-- border-left-primary -->
            <!-- cardBody -->
            <div class="cardBody panel-body ">
                <!-- cardItemRow -->
                <div class="cardRow row">
                    <!-- cardItemTextBox -->
                    <div class="cardItemTextBox col-md-8 ">
                        <!-- cardItemTitle -->
                        <h6 class="cardItemTitle numeroSolicitacao no-margin-top"><!-- text-primary -->
                            #<span class="data-ano"></span>.<span class="data-numero"></span>
                        </h6>
                        <!-- cardItenDescription -->
                        <p class="data-descricao cardItenDescription"></p>
                        <!-- Assinatura -->
                        <div class="minH">
                            <div class="row">
                                <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 text-bold">Solicitante:&nbsp;</div>
                                <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9 data-solicitante"></div>
                            </div>
                            <div class="row mb-15">
                                <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 text-bold">Lotação:&nbsp;</div>
                                <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9 data-setor"></div>
                            </div>
                        </div>
                        <!-- cardItemThumbnailUser -->
                        <div class="cardItemThumbnailUser"></div>
                    </div>
                    <!-- cardItemSideListBox -->
                    <div class="cardItemSideListBox col-md-4 ">
                        <ul class="list task-details" style="list-style: none;">
                            <li class="itemPrioridade">
                                <!-- cardItemSelectPrioridade -->
                                <label class="cardItemLabelPrioridade text-bold"><i class="icon-warning"></i> Prioridade:&nbsp</label>
                                <select name="select-prioridade" class="select-template cardItemSelectPrioridade data-prioridade" data-style="" data-width="100%" data-container="body" disabled>
                                    <option value="0" data-class="danger" data-content="<i class='icon-circle-small text-danger'></i> Urgente"></option>
                                    <option value="1" data-class="info" data-content="<i class='icon-circle-small text-info'></i> Alta"></option>
                                    <option value="2" data-class="primary" data-content="<i class='icon-circle-small text-primary'></i> Normal"></option>
                                    <option value="3" data-class="success" data-content="<i class='icon-circle-small text-success'></i> Baixa"></option>
                                </select>
                                <!-- /cardItemSelectPrioridade -->
                            </li>
                            <li>
                                <!-- cardItemLabelOpen -->
                                <label class="cardItemLabelOpen text-bold"><i class="icon-alarm-add"></i> Encaminhado:&nbsp;</label>
                                <!-- cardItemSpanOpen -->
                                <span class="data-encaminhamento cardItemSpanOpen"></span>
                            </li>
                            <li>
                                <!-- cardItemLabelStatus -->
                                <label class="cardItemLabelStatus text-bold"><i class="icon-alarm"></i> Vencimento:&nbsp;</label>
                                <!-- cardItemSpanStatus -->
                                <span class="data-vencimento cardItemSpanStatus"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /cardBody -->
            <!-- cardFooter -->
            <div class="cardFooter panel-footer panel-footer-condensed">
                <div class="heading-elements pr-10">
                    <div class="col-md-8">
                        <div class="heading-text ml-10">
                            <!-- cardItemSpanDue -->
                            <!--Prazo:<span class="cardItemSpanDue text-semibold">43 horas </span>-->

                            <ul class="list-inline list-inline-condensed">
                                <!-- cardItemSelectStatus -->
                                <li>
                                    <!--<span class="gerenciaAtendimentoIconeUsuario">
                                        <i class="icon-users2"></i>
                                    </span>-->
                                    <select name="cmb-responsavel" data-hidden="true" class="data-responsavel cardItemSelectResponsavel select-template" title="Responsável...">
                                        <!--<option></option>-->
                                    </select>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-inline list-inline-condensed heading-text pull-right">
                            <!-- cardItemSelectStatus -->
                            <li>
                                <select name="cmb-status" class="data-status cardItemSelectStatus select-template" title="Status...">
                                    <option value="0" disabled>Aberto</option>
                                    <option value="1">Em Andamento</option>
                                    <option value="2">Pendente</option>
                                    <option value="3">Concluído</option>
                                    <option value="4">Cancelado</option>
                                    <option value="5">Duplicado</option>
                                    <option value="6">Sem Solução</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /cardFooter -->
        </div>
    </div>
</template>
<template id="divisor">
    <div class="text-center content-group text-muted content-divider">
        <span class="data-divisor"></span>
    </div>
</template>
<template id="media-usuario">
    <li class="media cursor-pointer" name="card-usuario">
        <a class="media-left">
            <img class="img-sm img-circle data-avatar" alt="">
            <span class="badge bg-warning-400 media-badge data-badge"></span>
        </a>
        <div class="media-body">
            <a class="media-heading text-semibold data-nome"></a>
            <span class="text-size-mini text-muted display-block data-cargo"></span>
        </div>
        <div class="media-right media-middle">
            <span class="status-mark bg-success"></span>
        </div>
    </li>
</template>
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
</body>
</html>