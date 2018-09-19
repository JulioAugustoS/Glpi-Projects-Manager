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

function autoload($Class){
    $P = ['inc'];
    $Incluido = null;

    foreach ($P as $Pasta) {
        if(!$Incluido && file_exists(__DIR__ . "\\{$Pasta}\\{$Class}.class.php") && !is_dir(__DIR__ . "\\{$Pasta}\\{$Class}.class.php")):
            include_once __DIR__ . "\\{$Pasta}\\{$Class}.class.php";
            $Incluido = true;
        else:
            trigger_error("Erro ao incluir: " . __DIR__ . "\\{$Pasta}\\{$Class}.class.php");
            die;
        endif;
    }
}

spl_autoload_register("autoload");