var url = 'http://suporte.passaromarron.com.br:86/glpi/plugins/projects/front/frame/';

function sincronizar(idProjeto){
    $("#sync" + idProjeto).addClass('fa-spin')
    $("#collapse" + idProjeto).load(location.href+" #collapse"+ idProjeto +">*", function(){
        $("#sync" + idProjeto).removeClass('fa-spin')
        alertify.set('notifier','position', 'top-center');
        alertify.notify('Projeto N° <b>' + idProjeto + '</b> sincronizado!');
    })
}

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

function fecharTarefa(id, idProjeto){

    alertify.confirm("Deseja realmente fechar a tarefa N° <b>"+ id +"</b>?", function (e) {
        if (e) {
            $.ajax({
                type: 'GET',
                url: url + 'functions/update.php?acao=fecharTarefa&id='+ id,
                dataType: 'json',
                success: function(res){
                    if(res){
                        if(res.error == 1){
                            alertify.error("Erro ao fechar a tarefa N° "+ id +"!");
                        }else{
                            $("#collapse" + idProjeto).load(location.href+" #collapse"+ idProjeto +">*","");
                        }
                    }
                },
                complete: function(){
                    alertify.success("Tarefa N° "+ id +" fechada com sucesso!");
                }
            });
        } else {
            alertify.error("Tarefa N° "+ id +" não foi fechada!");
        }
    });
    return false;

}

function reabrirTarefa(id, idProjeto){

    alertify.confirm("Deseja realmente reabrir a tarefa N° <b>"+ id +"</b>?", function (e) {
        if (e) {
            $.ajax({
                type: 'GET',
                url: url + 'functions/update.php?acao=reabrirTarefa&id='+ id,
                dataType: 'json',
                success: function(res){
                    if(res){
                        if(res.error == 1){
                            alertify.error("Erro ao reabrir a tarefa N° "+ id +"!");
                        }else{
                            $("#collapse" + idProjeto).load(location.href+" #collapse"+ idProjeto +">*","");
                        }
                    }
                },
                complete: function(){
                    alertify.success("Tarefa N° "+ id +" iniciada com sucesso!");
                }
            });
        } else {
            alertify.error("Tarefa N° "+ id +" não foi iniciada!");
        }
    });
    return false;

}

function mudarStatus(id, status){

    if(status != 4){
        statusAtual = convStatus(status)
        novoStatus  = convStatus(4)
    }else{
        statusAtual = convStatus(status)
        novoStatus  = convStatus(2)
    }

    alertify.confirm("Deseja realmente alterar o status do chamado <b>"+ id +"</b> de <b>"+ statusAtual +"</b> para <b>"+ novoStatus +"</b>?", function (e) {
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
                            $(".muda_status" + id).load(location.href+" .muda_status"+ id +">*","");
                        }
                    }
                },
                complete: function(){
                    alertify.success("Status alterado com sucesso!");
                }
            });
        } else {
            alertify.error("Status não foi alterado!");
        }
    });
    return false;
}