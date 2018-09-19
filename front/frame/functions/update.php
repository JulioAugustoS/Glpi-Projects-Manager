<?php
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);*/

include ('../../../../../inc/includes.php');
include ('../inc/common.class.php');

global $DB;

$retorno = array();
$data    = date('Y-m-d H:i:s');

if($_GET['acao'] == 'mudarStatus'):

    $old_status = $_GET['status'];
    $id         = $_GET['id'];

    if($old_status == 2):
        $status = 4;
    else:
        $status = 2;
    endif;    

    $ticketStatus = new Common();
    $ticketStatus->updateTimelineStatus($id, $data, $old_status, $status);
    
    if($ticketStatus == true):
        $retorno['error'] = 0;
        $retorno['novoStatus'] = $status; 
    else:
        $retorno['error'] = 1;
    endif;  
    
endif;

if($_GET['acao'] == 'fecharTarefa'):

    $id = $_GET['id'];

    $sql = new Common();

    $ticketId = $sql->ticketsTasks($id);
    $result = $sql->consultOldStatus($ticketId);

    $execute = $sql->updateTimelineStatus($ticketId, $data, $result, 6);

    if($execute == true):
        
        $fecharTask = $sql->openCloseTask($id, 100);

        if($fecharTask == true):
            $retorno['error'] = 0;
        else:
            $retorno['error'] = 1;
        endif;

    else:
        echo 'Error';
    endif;

endif;    

if($_GET['acao'] == 'reabrirTarefa'):

    $id = $_GET['id'];

    $sql = new Common();

    $ticketId = $sql->ticketsTasks($id);
    $result = $sql->consultOldStatus($ticketId);

    $execute = $sql->updateTimelineStatus($ticketId, $data, $result, 2);

    if($execute == true):

        $abrirTask = $sql->openCloseTask($id, 0);

        if($abrirTask == true):
            $retorno['error'] = 0;
        else:
            $retorno['error'] = 1;
        endif;
    
    else:
        echo 'Error';
    endif;

endif;

die(json_encode($retorno));