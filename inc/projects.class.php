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

if(!defined('GLPI_ROOT')){
    die("Sorry. You can't access directly to this file");
} 

class PluginProjectsProjects extends CommonDBTM {

	static function getTypeName($nb = 0){
		return 'Projects Manager';
	}

	static function canCreate(){

		if(isset($_SESSION["glpi_plugin_projects_profile"])){
			return ($_SESSION["glpi_plugin_projects_profile"]['projects'] == 'w');
		}

		return false;

	}

	static function canView(){

		if(isset($_SESSION["glpi_plugin_projects_profile"])){
			return ($_SESSION["glpi_plugin_projects_profile"]['projects'] == 'w'
			|| $_SESSION["glpi_plugin_projects_profile"]['projects'] == 'r');
		}

		return false;

	}

	/**
	 * @see CommonGLPI::getMenuName()
	 */
	static function getMenuName(){
		return __('Projects Manager');
	}

	/**
	 * Get an history entry message
	 * 
	 * @param $data Array from glpi_logs table
	 * @since GLPI version 9.3
	 * @return string 
	 */
	static function getHistoryEntry($data){

		switch($data['linked_action'] - Log::HISTORY_PLUGIN){
			case 0:
				return __('History from plugin project manager', 'projects');
		}

		return '';

	}

}