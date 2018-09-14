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

class PluginProjectsConfig extends CommonDBTM {

    static protected $notable = true;

    function getTabNameForItem(CommonGLPI $item, $withtemplate = 0){

        if(!$withtemplate){
            if($item->getType() == 'Config'){
                return __('Projects plugin');
            }
        }
        return '';

    }

    function showFormProjects(){

        global $CFG_GLPI;

        if(!Session::haveRight("config", UPDATE)){
            return false;
        }

    }

    static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0){

        if($item->getType() == 'Config'){
            $config = new self();
            $config->showFormProjects();
        }

    }

}