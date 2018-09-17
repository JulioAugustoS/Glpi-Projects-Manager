<?php

include('../../../../inc/includes.php');
require_once('autoload.php');

define('GLPI_URL', 'http://localhost/glpi/front/');

//include('inc/consult.class.php');
//include('inc/home.class.php');

    $home = new Home();  
    $projetos = new Consult();

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Project Manager</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/alertify.min.css">
        <link rel="stylesheet" href="assets/css/themes/bootstrap.min.css">
    </head>
    <body>
        <section class="container-fluid paddBody">
            <?php include('includes/header.php'); ?>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#projetos" role="tab" aria-controls="projetos" aria-selected="true">Projetos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#chamados" role="tab" aria-controls="chamados" aria-selected="false">Chamados</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="projetos" role="tabpanel" aria-labelledby="home-tab">
                            <div style="margin-top: 30px !important;">
                                <div class="accordion col-md-12" id="projetos">
                                    <?php $projetos->Projetos(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="chamados" role="tabpanel" aria-labelledby="profile-tab">...</div>
                    </div>
                </div>
            </div>
        </section>
    </body>

    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/alertify.js"></script>
    <script src="assets/js/functions.js"></script>
</html>