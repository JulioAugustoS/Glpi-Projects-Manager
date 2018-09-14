<?php

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