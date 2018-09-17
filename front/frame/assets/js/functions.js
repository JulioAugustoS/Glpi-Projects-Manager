var url = 'http://localhost/glpi/plugins/projects/front/frame/';

function convStatus(status){
    switch (status) {
        case 1:
            status = 'Novo' 
            break;
        case 2:
            status = 'Processando' 
            break;
        case 3:
            status = 'Processando Planejado' 
            break;
        case 4:
            status = 'Pendente' 
            break;
        case 5:
            status = 'Solucionado' 
            break;    
        default:
            status = 'Fechado' 
            break;
    }

    return status
}

function mudarStatus(id, status){

    if(status != 4){
        statusAtual = convStatus(status)
        novoStatus  = convStatus(4)
    }else{
        statusAtual = convStatus(status)
        novoStatus  = convStatus(2)
    }

    alertify.confirm("Deseja realmente alterar o status do chamado "+ id +" de <b>"+ statusAtual +"</b> para <b>"+ novoStatus +"</b>?", function (e) {
        if (e) {
            $.ajax({
                type: 'GET',
                url: url + 'functions/update.php?acao=mudarStatus&status='+ status +'&id='+ id,
                dataType: 'json',
                success: function(res){
                    if(res){
                        if(res.error == 1){
                            alertify.error("Erro ao alterar o status do chamado!");
                        }else{
                            $(".muda_status").load(location.href+" .muda_status>*","");
                        }
                    }
                },
                complete: function(){
                    alertify.success("Status alterado com sucesso!");
                }
            });
            //alertify.success("Status alterado com sucesso!");
        } else {
            alertify.error("Status não foi alterado!");
        }
    });
    return false;
}