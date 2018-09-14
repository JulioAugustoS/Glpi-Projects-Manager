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

class Functions {

    function conv_data($data) {
        if($data != "") {
            $source = $data;
            $date = new DateTime($source);

            switch ($_SESSION['glpidate_format']) {
            case "0": $dataf = $date->format('Y-m-d'); break;
            case "1": $dataf = $date->format('d-m-Y'); break;
            case "2": $dataf = $date->format('m-d-Y'); break;
        }

            //return $date->format('d-m-Y');}
            return $dataf;}
        else {
            return "";
        }
    }


    function conv_data_hora($data) {
        if($data != "") {
            $source = $data;
            $date = new DateTime($source);

            switch ($_SESSION['glpidate_format']) {
                case "0": $dataf = $date->format('Y-m-d H:i'); break;
                case "1": $dataf = $date->format('d/m/Y H:i'); break;
                case "2": $dataf = $date->format('m-d-Y H:i'); break;
            }

            return $dataf;
        }
        else {
            return "";
        }
    }


    function calculaDatas($data) {

        $data_inicial = conv_data($data);
        $data_final = date('d-m-Y');

        // Calcula a diferença em segundos entre as datas
        $diferenca = strtotime($data_final) - strtotime($data_inicial);

        //Calcula a diferença em dias
        $dias = floor($diferenca / (60 * 60 * 24));

        return $dias;

    }


    function time_ext($solvedate)
    {

    $time = $solvedate; // time duration in seconds

    if ($time == 0){
            return '';
        }

        $days = floor($time / (60 * 60 * 24));
        $time -= $days * (60 * 60 * 24);

        $hours = floor($time / (60 * 60));
        $time -= $hours * (60 * 60);

        $minutes = floor($time / 60);
        $time -= $minutes * 60;

        $seconds = floor($time);
        $time -= $seconds;

        $return = "{$days}d {$hours}h {$minutes}m {$seconds}s"; // 1d 6h 50m 31s

        return $return;
    }


    function time_hrs($time)
    {

    if ($time == 0){
            return '';
        }

    // $days = floor($time / 86400); // 60*60*24
    // $time -= $days * 86400;

        $hours = floor($time / (60 * 60));
        $time -= $hours * (60 * 60);

        $minutes = floor($time / 60);
        $time -= $minutes * 60;

        $seconds = floor($time);
        $time -= $seconds;

        $return = "{$hours}h{$minutes}m" /*{$seconds}s"*/; // 1d 6h 50m 31s

        return $return;
    }


    function time_hrs2($time)
    {

    if ($time == 0){
            return '';
        }

    // $days = floor($time / 86400); // 60*60*24
    // $time -= $days * 86400;

        $hours = floor($time / (60 * 60));
        $time -= $hours * (60 * 60);

        $minutes = floor($time / 60);
        $time -= $minutes * 60;

        $seconds = floor($time);
        $time -= $seconds;

        $return = $hours; // 1d 6h 50m 31s

        return $return;
    }



    function dropdown( $name, array $options, $selected=null )
    {
        /*** begin the select ***/
        $dropdown = '<select id="sel1" style="width: 300px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

        $selected = $selected;
        /*** loop over the options ***/
        foreach( $options as $key=>$option )
        {
            /*** assign a selected value ***/
            $select = $selected==$key ? ' selected' : null;

            /*** add each option to the dropdown ***/
            $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
        }

        /*** close the select ***/
        $dropdown .= '</select>'."\n";

        /*** and return the completed dropdown ***/
        return $dropdown;
    }


    function dropdown2( $name, array $options, $selected=null )
    {
        /*** begin the select ***/
        $dropdown = '<select style="width: 300px; height: 27px;" autofocus name="'.$name.'" id="'.$name.'">'."\n";

        $selected = $selected;
        /*** loop over the options ***/
        foreach( $options as $key=>$option )
        {
            /*** assign a selected value ***/
            $select = $selected==$key ? ' selected' : null;
            /*** add each option to the dropdown ***/
            $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
        }
        /*** close the select ***/
        $dropdown .= '</select>'."\n";

        /*** and return the completed dropdown ***/
        return $dropdown;
    }

    //segundos para h:m:s
    /*
    $segundos = 15058084;
    //$converter = date('H:i:s',mktime(0,0,$segundos,15,03,2013));//Converter os segundos em no formato mm:ss
    $converter = date('H:i:s',mktime(0,0,$segundos));//Converter os segundos em no formato mm:ss
    echo $converter;//no exemplo ira retornar 02:15
    */
  
}    