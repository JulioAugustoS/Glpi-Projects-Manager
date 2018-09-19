<?php

/*
   ------------------------------------------------------------------------
   Projects
   Copyright (C) 2018 by the Projects Manager Development Team.

   https://github.com/JulioAugustoS/projectsmanager
   ------------------------------------------------------------------------

   LICENSE

   This file is part of ProjectsManager project.

   ProjectsManager plugin is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   ProjectsManager plugin is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with ProjectsManager plugin. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Projects Manager plugin
   @copyright Copyright (c) 2018 Projects Manager team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://github.com/JulioAugustoS/projectsmanager
   @since     2018

   ------------------------------------------------------------------------
 */

include('../../../../inc/includes.php');
include('includes/config.inc.php');
require_once('autoload.php');

    $home = new Home();  
    $projetos = new Consult();

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title><?php __('Project Manager'); ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/alertify.min.css">
        <link rel="stylesheet" href="assets/css/themes/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/all.min.css">
    </head>
    <body>
        <section class="container-fluid paddBody">
            <?php include('includes/header.php'); ?>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#projetos" role="tab" aria-controls="projetos" aria-selected="true"><?php echo __('Projetos'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#chamados" role="tab" aria-controls="chamados" aria-selected="false"><?php echo __('Chamados'); ?></a>
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
    <script src="assets/js/all.min.js"></script>
</html>