<?php
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);*/

include('../../../../../inc/includes.php');

global $DB;

$retorno = array();

if($_GET['acao'] == 'mudarStatus'):

    $status = $_GET['status'];
    $id     = $_GET['id'];

    if($status == 2):
        $status = 4;
    else:
        $status = 2;
    endif;    

    $update = "UPDATE glpi_tickets SET status = '$status' WHERE id = '$id'";
    $sql = $DB->query($update) or die('Erro ao fazer update');

    if($sql):
        $retorno['error'] = 0;
        $retorno['novoStatus'] = $status; 
    else:
        $retorno['error'] = 1;
    endif;    

endif;

die(json_encode($retorno));