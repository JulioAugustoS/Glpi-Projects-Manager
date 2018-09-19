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

include ('functions.class.php');

class Common extends Functions {

    function updateTimelineStatus($id, $data, $old_status, $new_status){

        global $DB;

        $delay = $this->geraDelayTickets($old_status, $id, $data);
        $this->updateTicketStatus($new_status, $id);

        $state = "INSERT INTO glpi_plugin_timelineticket_states
                  SET tickets_id = '$id', 
                    date = '$data', 
                    old_status = '$old_status', 
                    new_status = '$new_status',
                    delay = '$delay' 
                ";
        $insert = $DB->query($state);

        if($insert):
            return true;
        else: 
            return false;
        endif;        

    }

    function geraDelayTickets($old_status, $id, $data){

        global $DB;

        if(empty($old_status)):
            $delay = 0;
        else:
            $query = "SELECT MAX(date) AS datedebut
                        FROM glpi_plugin_timelineticket_states
                        WHERE tickets_id = '$id'
                    ";    
            $result = $DB->query($query);
            $datedebut = '';
            if($result && $DB->numrows($result)):
                $datedebut = $DB->result($result, 0, 'datedebut');
            endif;
            $datefin = $data;
            
            if(!$datedebut):
                $delay = 0;
            else:
                $delay = strtotime($datefin) - strtotime($datedebut);
            endif;
        endif;

        return $delay;

    }

    function updateTicketStatus($new_status, $id){

        global $DB;

        $updateTicket = "UPDATE glpi_tickets 
                         SET status = '$new_status' 
                         WHERE id = '$id'
                        ";
        $sqlTicket = $DB->query($updateTicket);
        
    }

    function ticketsTasks($idTask){

        global $DB;

        $sql = "SELECT * FROM glpi_projecttasks_tickets WHERE projecttasks_id = '$idTask'";
        $result = $DB->query($sql);
        $list = $DB->fetch_assoc($result);

        return $list['tickets_id'];

    }

    function consultOldStatus($idTicket){

        global $DB;

        $consult = "SELECT new_status FROM glpi_plugin_timelineticket_states WHERE tickets_id = '$idTicket' ORDER BY date DESC LIMIT 1";
        $result = $DB->query($consult);
        $list = $DB->fetch_assoc($result);

        return $list['new_status'];

    }

    function openCloseTask($idTask, $percent){

        global $DB;

        $openClose = "UPDATE glpi_projecttasks SET percent_done = '$percent' WHERE id = '$idTask'";
        $sqlOpenClose = $DB->query($openClose);

        if($sqlOpenClose):
            return true;
        else:
            return false;
        endif;    

    }

}