<?php

include ('../../../../../inc/includes.php');

if($_SESSION["glpiactiveprofile"]["interface"] == "central"){
    Html::header("Project Manager", $_SERVER['PHP_SELF'], "plugins", "pluginprojectsprojects");   
}

global $DB;

$sel = "SELECT a.* , a.tempo_atualizado , 
        SEC_TO_TIME( fu_verficaferiados( a.ultimo_status, current_date() ) ) tempo_feriado_finalsemana , 
        SEC_TO_TIME(time_to_sec(a.tempo_atualizado) - fu_verficaferiados(a.ultimo_status, current_date())) horas_efetivas
        FROM vw_timesticket a WHERE tickets_id = 23183
";

//$sel = "SELECT * FROM vw_timesticket";

$sql = $DB->query($sel) or die('Erro');

print_r($sql);

Html::footer();
