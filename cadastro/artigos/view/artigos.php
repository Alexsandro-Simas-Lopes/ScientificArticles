<?php
    // Inicialize a sessão
    session_start();
    // Verifique se o usuário está logado, caso contrário, redirecione para a página de login
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: ../../../public/home.php "); // Se não estiver logado volta para o Catalogo
        exit;
    }
    
    require_once("../../../parametro/configDB/connectDB.php");

?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>ScientificArticles | Articles</title>

        <link href="../../../components/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../../components/font-awesome/css/font-awesome.css" rel="stylesheet">

        <link href="../../../components/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
        <link href="../../../components/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

        <link href="../../../components/css/animate.css" rel="stylesheet">
        <link href="../../../components/css/style.css" rel="stylesheet">

    </head>

    <body>
        <div id="wrapper">
            <nav class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav metismenu" id="side-menu">
                        <li class="nav-header">
                            <div class="dropdown profile-element"> <span>
                                <img alt="image" class="img-circle" src="../../../components/img/LOGO.png" style="max-width: 70%;" />
                                </span>
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">Sientific Articles</strong>
                                </span> <span class="text-muted text-xs block">User <b class="caret"></b></span> </span> </a>
                                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                    <!-- <li><a href="../autenticacao/view/reset-password.php">Alterar senha</a></li>
                                    <li class="divider"></li> -->
                                    <li><a href="../../../autenticacao/view/logout.php">Logout</a></li>
                                </ul>
                            </div>
                            <div class="logo-element">
                                <img alt="image" class="img-circle" src="../../../components/img/LOGO.png" style="max-width: 70%;" />
                            </div>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-table"></i> <span class="nav-label">My Articles</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a href="http://localhost/Scientific_articles/cadastro/artigos/view/artigos.php">Articles</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-graduation-cap"></i> <span class="nav-label">Scientific Aticles</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a target="_blank" href="http://localhost/Scientific_articles/public/home.php">Web Site</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-coffee"></i> <span class="nav-label">WebSite</span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a target="_blank" href="http://alexsimas.netlify.app">My Portfolio</a></li>
                            </ul>
                        </li>
                    </ul>

                </div>
            </nav>

            <div id="page-wrapper" class="gray-bg dashbard-1">
                <div class="row border-bottom">
                    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                        <div class="navbar-header">
                            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                        </div>
                        <ul class="nav navbar-top-links navbar-right">
                            <!-- <li>
                                <span class="m-r-sm text-muted welcome-message">Welcome to INSPINIA+ Admin Theme.</span>
                            </li> -->
                            <li>
                                <a href="../../../autenticacao/view/logout.php">
                                    <i class="fa fa-sign-out"></i> Log out
                                </a>
                            </li>
                        </ul>

                    </nav>
                </div>
                <div class="row wrapper border-bottom white-bg page-heading">
                    <div class="col-lg-10">
                        <h2>Scientific Articles</h2>
                        <ol class="breadcrumb">
                            <li>
                                <a>Tabela</a>
                            </li>
                            <li class="active">
                                <strong>Articles</strong>
                            </li>
                        </ol>
                    </div>
                    <div class="col-lg-2">

                    </div>
                </div>

                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content language-json">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="text-left">
                                                <button type="button" disabled="" class="btn btn-sm btn-primary" style="cursor: default !important; height: 30.0px; margin-bottom: 0px; margin-right: 0px; border-bottom-right-radius: 0; border-top-right-radius: 0; float: left; opacity:100%; background-color:rgb(38 79 159 / 100%)!important; border-color: rgb(38 79 159 / 100%)!important;">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                                <input id="search_artigo" type="search" class="form-control input-sm" placeholder="Buscar por nome do produto (Min: 3 caracteres)" minlength="3" aria-controls="table_artigo" onchange="search_datagrid_artigos(this.value)" value="" style="width: 50%; float: left; margin-right: 5px;">
                                                <button type="button" class="btn btn-sm btn-default" style="height: 30.0px; margin-bottom: 0px; border-radius: 3px; background: white !important; float: left;" title="Recarregar" onclick="reset_busca_artigos()">
                                                    <i class="fa fa-refresh" aria-hidden="false"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="text-right">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" onclick="add_artigo()" style="border:none !important;"><i class="fa fa-plus" aria-hidden="true"></i> Novo artigo</button>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="footable table table-striped toggle-arrow-tiny default breakpoint footable-loaded table-bordered table-hover dataTables-example" id="table_artigo" page='1'>
                                        <thead>
                                            <tr>
                                                <th data-i18n="" style="cursor: pointer; width: 2vw;" onclick="set_order_table_artigo('id')">
                                                    <div class="th_separator">
                                                        <span>ID</span>
                                                        <i class="bi bi-arrow-down-up" id="id_ordericon" target="default" order="id" name="orders_table"></i>
                                                    </div>
                                                </th>
                                                <th data-i18n="" style="cursor: pointer;" onclick="set_order_table_artigo('titulo')">
                                                    <div class="th_separator">
                                                        <span>Title</span>
                                                        <i class="bi bi-arrow-down-up" id="titulo_ordericon" target="default" order="titulo" name="orders_table"></i>
                                                    </div>
                                                </th>
                                                <th data-i18n="" style="cursor: pointer;" onclick="set_order_table_artigo('resumo')">
                                                    <div class="th_separator">
                                                        <span>Summary</span>
                                                        <i class="bi bi-arrow-down-up" id="resumo_ordericon" target="default" order="resumo" name="orders_table"></i>
                                                    </div>
                                                </th>
                                                <th data-i18n="" style="cursor: pointer;" onclick="set_order_table_artigo('conteudo')">
                                                    <div class="th_separator">
                                                        <span>Content</span>
                                                        <i class="bi bi-arrow-down-up" id="conteudo_ordericon" target="default" order="conteudo" name="orders_table"></i>
                                                    </div>
                                                </th>
                                                <th data-i18n="" style="cursor: pointer;" onclick="set_order_table_artigo('status')">
                                                    <div class="th_separator">
                                                        <span>Status</span>
                                                        <i class="bi bi-arrow-down-up" id="status_ordericon" target="default" order="status" name="orders_table"></i>
                                                    </div>
                                                </th>
                                                <th data-i18n="" style="cursor: pointer; width: 15vw;">
                                                    <center>Ações</center>
                                                </th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td colspan="5"><span id="find_indicator"></span></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="dataTables_info" id="table_artigo_info" role="status" aria-live="polite">Mostrando <span id="cont_inicio_paginacao">0</span> a <span id="cont_fim_paginacao">0</span> de <span id="cont_total_paginacao">0</span> entradas </div>
                                        </div>
                                        <div class="col-sm-7" style="display: flex; justify-content: flex-end; align-items: center;">
                                            <div style="margin-right: 12px; display: flex; justify-content: flex-end; align-items: center;">
                                                <span>Mostrar</span>&nbsp;
                                                <select id="table_artigo_length" class="form-control" style="width: 70px" onchange="restart_lenght_table_artigo()">
                                                    <option value="10" selected>10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>&nbsp;
                                                <span>entradas</span>
                                            </div>
                                            <div class="dataTables_paginate paging_simple_numbers" id="table_artigo_paginate">
                                                <ul class="pagination" id="paginacao_table_artigo" style="margin: 0">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="footer">
                    <div class="pull-right">
                    Scientific Articles<strong>ADM</strong>.
                    </div>
                    <div>
                        <strong>Copyright</strong> 2025 &copy; | Designed by Alexsandro Simas Lopes
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal" id="modal_window" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow-y: hidden !important;">
            <div class="modal-dialog modal-lg modal-to-window">
                <div class="modal-content modal-to-window-content modal-content animated bounceInUp">...</div>
            </div>
        </div>

        <div class="modal" id="modal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">...</div>
            </div>
        </div>

        <!-- Mainly scripts -->
        <script src="../../../components/js/jquery-3.1.1.min.js"></script>
        <script src="../../../components/js/bootstrap.min.js"></script>
        <script src="../../../components/js/plugins/metisMenu/jquery.metisMenu.js"></script>
        <script src="../../../components/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

        <script src="../../../components/js/plugins/dataTables/datatables.min.js"></script>
        <script src="../../../components/js/plugins/sweetalert/sweetalert.min.js"></script>

        <!-- Custom and plugin javascript -->
        <script src="../../../components/js/inspinia.js"></script>
        <script src="../../../components/js/plugins/pace/pace.min.js"></script>

        <!-- Page-Level Scripts -->
        <script src="../../artigos/ajax/artigos.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('titulo_ordericon').click();
            });

            $(document).on('click', '.collapse-link', function() {
                var ibox = $(this).closest('div.ibox');
                var button = $(this).find('i');
                var content = ibox.children('.ibox-content');
                content.slideToggle(200);
                button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
                ibox.toggleClass('').toggleClass('border-bottom');
                setTimeout(function() {
                    ibox.resize();
                    ibox.find('[id^=map-]').resize();
                }, 50);
            });

            $(document).on('click', '.close-link', function() {
                var content = $(this).closest('div.ibox');
                content.remove();
            });
        </script>

    </body>

</html>