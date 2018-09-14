<?php

/*
   ------------------------------------------------------------------------
   Projects
   Copyright (C) 2018 by the Projects Development Team.

   https://github.com/JulioAugustoS/projects
   ------------------------------------------------------------------------

   LICENSE

   This file is part of TimelineTicket project.

   TimelineTicket plugin is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   TimelineTicket plugin is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with TimelineTicket plugin. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Projects plugin
   @copyright Copyright (c) 2018 Projects team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://github.com/pluginsGLPI/timelineticket
   @since     2018

   ------------------------------------------------------------------------
 */

define("PLUGIN_PROJECTS_VERSION", "1.0.0");

if(!defined("PLUGIN_PROJECTS_DIR")){
    define("PLUGIN_PROJECTS_DIR", GLPI_ROOT . "/plugins/projects");
}

function plugin_version_projects(){

    return [
        'name'                  => _n("Project Manager", "Projects Manager", 2, "projects"),
        'minGlpiVersion'        => '9.3.0',
        'version'               => PLUGIN_PROJECTS_VERSION,
        'homepage'              => 'https://github.com/JulioAugustoS/projectsManager',
        'license'               => 'AGPLv3+',
        'author'                => 'Julio Augusto'
    ];

}

function plugin_init_projects(){

    global $PLUGIN_HOOKS, $CFG_GLPI;

    $PLUGIN_HOOKS['csrf_compliant']['projects'] = true;

    // Adiciona Autoload for vendor
    include_once(PLUGIN_PROJECTS_DIR . "/vendor/autoload.php");

    // Display a menu entry ?
    $_SESSION["glpi_plugin_projects_profile"]['projects'] = 'w';
    if(isset($_SESSION["glpi_plugin_projects_profile"])) {
        $PLUGIN_HOOKS['menu_toadd']['projects'] = ['plugins' => 'PluginProjectsProjects',
                                                    'tools'   => 'PluginProjectsProjects'];

        $PLUGIN_HOOKS["helpdesk_menu_entry"]['projects'] = true;
    }

    // Config page
    if(Session::haveRight('config', UPDATE)){
        $PLUGIN_HOOKS['config_page']['projects'] = 'front/config.php';
    }

    // Change profile
    $PLUGIN_HOOKS['change_profile']['projects'] = 'plugin_change_profile_projects';

    $PLUGIN_HOOKS['planning_types'][] = 'PluginProjectsProjects';

    // Add specific files to add to the header : javascript or css
    //$PLUGIN_HOOKS['add_javascript']['example'] = 'example.js';
    //$PLUGIN_HOOKS['add_css']['example']        = 'example.css';

    // CSRF compliance : All actions must be done via POST and forms closed by Html::closeForm();
    $PLUGIN_HOOKS['csrf_compliant']['projects'] = true;
}

function plugin_projects_check_prerequisites(){

    if(version_compare(GLPI_VERSION, '9.3', 'lt')
        || version_compare(GLPI_VERSION, '9.4', 'ge')){
        echo 'This plugin requires GLPI >= 9.3';
        return false;
    }
    return true;
}

function plugin_projects_check_config($verbose = false){
    
    if(true){
        return true;
    }

    if($verbose){
        echo __('Installed / not configured', 'projects');
    }
    return false;

}