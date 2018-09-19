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

class Consult extends Functions {

	function Projetos(){

		global $DB, $CFG_GLPI;

        $select = "SELECT 
					id 					AS IdProjeto,	
					name 				AS NomeProjeto, 
					priority			AS PrioridadeProjeto,
					plan_start_date 	AS DataInicio,
					plan_end_date 		AS DataFinal,
					content				AS Conteudo,
					percent_done    	AS Complete
					FROM glpi.glpi_projects	
					WHERE is_deleted = 0
					/*GROUP BY id*/
				";
		$sql = $DB->query($select) or die(''. __('Erro ao retornar os projetos') .'');

			while($result = $DB->fetch_assoc($sql)):

				$consultTarefa = "SELECT
									a.id 			AS id, 
									a.name 			AS titulo,
									a.content 		AS descricao, 
									a.projects_id	AS id_projeto, 
									a.percent_done  AS PercentComplete,
									b.tickets_id	AS id_ticket
									FROM glpi_projecttasks a
									LEFT JOIN glpi_projecttasks_tickets b
										ON (b.projecttasks_id = a.id)
									WHERE a.projects_id = ".$result['IdProjeto']."
								";
				$sqlTarefa = $DB->query($consultTarefa) or die(''. __('Erro ao retornar a tarefa!') .'');

				// Duração planejada
				$dp = "SELECT sec_to_time(sum(planned_duration)) AS tempo_planejado
						FROM glpi_projecttasks
						WHERE projects_id = ".$result['IdProjeto']."
					";
				$sqldp = $DB->query($dp);
				$resultdp = $DB->fetch_assoc($sqldp);

				if(empty($resultdp['tempo_planejado'])):
					$resultdp['tempo_planejado'] = '00:00:00';
				endif;	

				$DataInicio = Functions::conv_data_hora($result['DataInicio']);
				$DataFinal  = Functions::conv_data_hora($result['DataFinal']);
				if($DataInicio == '' && $DataFinal == ''):
					$DataInicio = __('Não Informado');
					$DataFinal  = __('Não Informado');
				else:
					$DataInicio = $DataInicio;
					$DataFinal  = $DataFinal;
				endif;	

				echo '<div class="card">';
				echo '<div class="card-header" id="heading'.$result['IdProjeto'].'">';
				echo '<h5 class="mb-0">';
				echo '<button class="btn btn-link" type="button"
						data-toggle="collapse" 
						data-target="#collapse'.$result['IdProjeto'].'" 
						aria-expanded="true" 
						aria-controls="collapse'.$result['IdProjeto'].'">'.$result['NomeProjeto'].'</button>';
				echo '<i id="sync'.$result['IdProjeto'].'" style="float:right !important;font-size:14px;margin-top:11px !important;margin-left:10px;cursor:pointer;" class="fas fa-sync-alt" title="Sincronizar" onclick="sincronizar('. $result['IdProjeto'] .')"></i>';
				echo '<a target="blank" href="'.GLPI_URL.'project.form.php?id='.$result['IdProjeto'].'" style="float:right !important;font-size:14px;margin-top:10px !important;">' . __('Ir para o projeto') .'</a>';
				echo '<p style="float:right;font-size:14px;color:#666;margin-top:10px;margin-right:50px;">';
				echo '<span style="margin-right:10px;padding-right:10px;border-right:1px solid #CCC;"><b>'. __('Data Início:') .'</b> '. $DataInicio .'</span>';
				echo '<span style="margin-right:10px;padding-right:10px;border-right:1px solid #CCC;"><b>'. __('Data Final:') .'</b> '. $DataFinal .'</span>';
				echo '<span style="margin-right:10px;"><b>'. __('Horas Planejadas:') .' </b> '. $resultdp['tempo_planejado'] .'</span></p>';
				echo '</h5></div>';
				echo '<div id="collapse'.$result['IdProjeto'].'" class="collapse"
						aria-labelledby="heading'.$result['IdProjeto'].'" 
						data-parent="#projetos"><div class="card-body">';

				$progressBar = "SELECT COUNT(a.id) * (100 / b.totalgeral) AS percent 
									FROM glpi_projecttasks a, 
									(
										SELECT COUNT(id) AS totalgeral FROM glpi_projecttasks WHERE projects_id = ".$result['IdProjeto']."
									) b
									WHERE projects_id = ".$result['IdProjeto']." AND percent_done != 100";
				$sqlProgress = $DB->query($progressBar);
				$listD = $DB->fetch_assoc($sqlProgress);

					$minProgress = 0;
					$maxProgress = 100;

					if(empty($listD['percent']) || $listD['percent'] == 99.9999):
						$porcent = $minProgress;
					else:
						$porcent = $maxProgress - $listD['percent']; 
					endif;

				echo '<div class=""><div class="card-body">';
				echo '<b>'. __('Progresso do Projeto:') .'</b> '.$porcent.'%<br>';

				echo '<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="'.$porcent.'" aria-valuemin="'.$minProgress.'" aria-valuemax="'.$maxProgress.'" style="width: '.$porcent.'%;color:#000;font-weight:bold;padding-left:5px;">'.$porcent.'%</div>
					</div><br>';
				echo '<b>'. __('Descrição do projeto:') .' </b> '.$result['Conteudo'].'';
				echo '</div></div>';
				
				if($DB->numrows($sqlTarefa) < 1):
					echo '<div class="alert alert-primary" role="alert">
							'. __('Não existem tarefas vinculadas a esse projeto!') .'
				  		  </div>';
				else:
					echo '<div style="margin-left:20px;"><b>'. __('Tarefas do projeto:'). '</b></div><hr>';
					echo '<div style="margin-top: 30px !important;">';
					echo '<div class="accordion col-md-12" id="tarefaPri">';

					while($listTarefa = $DB->fetch_assoc($sqlTarefa)):

						if($listTarefa['id_ticket'] == ''):
							$idChamado = '';
						else: 
							$idChamado = $listTarefa['id_ticket'];
						endif;

						$tempoTrabalhado = "SELECT a.* , a.tempo_atualizado , 
									SEC_TO_TIME( fu_verficaferiados( a.ultimo_status, current_date() ) ) tempo_feriado_finalsemana , 
									SEC_TO_TIME(time_to_sec(a.tempo_atualizado) - fu_verficaferiados(a.ultimo_status, current_date())) horas_efetivas
									FROM vw_timesticket a WHERE tickets_id = '$idChamado'
								";

						$sqlTt = $DB->query($tempoTrabalhado) or die(''. __('Erro ao retornar o tempo') .'');
						$resultTt = $DB->fetch_assoc($sqlTt);	
						
							if($listTarefa['id_ticket'] == '' && empty($resultTt['horas_efetivas']) || empty($resultTt['atribuido'])):
								$resultTt['horas_efetivas'] = '00:00:00';
								$resultTt['atribuido'] = '00:00:00';
							endif;	

						$consultaTicketTask = "SELECT *
												FROM `glpi_projecttasks_tickets`
												WHERE projecttasks_id = ".$listTarefa['id']."
												LIMIT 1
											";
						$sqlTicketTask = $DB->query($consultaTicketTask) or die(''. __('Erro ao buscar os chamados da Task') .'');
						$returnTicketTask = $DB->fetch_assoc($sqlTicketTask);

						echo '<div class="card">';
						echo '<div class="card-header" id="tarefas'.$listTarefa['id'].'">';
						echo '<h5 class="mb-0">';
						if($listTarefa['PercentComplete'] != 100):
							echo '<button class="btn btn-link" type="button"
									data-toggle="collapse" 
									data-target="#tf'.$listTarefa['id'].'" 
									aria-expanded="true" 
									aria-controls="tf'.$listTarefa['id'].'">ID: '.Functions::modificaId($listTarefa['id']).' - '.$listTarefa['titulo'].'</button>';
							echo '<p style="float:right;font-size:14px;color:#666;margin-top:10px;">';
							echo '<span style="margin-right:10px;padding-right:10px;border-right:1px solid #CCC;"><b>'. __('Total Trabalhado:') .' </b> '. $resultTt['atribuido'] .'</span>';
							echo '<span style="margin-right:10px;padding-right:10px;border-right:1px solid #CCC;"><b>'. __('Horas Trabalhadas (Atual):') .' </b>'. $resultTt['horas_efetivas'] .'</span>';
							echo '<span style=""><a style="vertical-align:none!important;padding:0!important;line-height:0!important;" class="btn btn-link" onclick="fecharTarefa('.$listTarefa['id'].', '.$result['IdProjeto'].')">'. __('Finalizar Tarefa') .'</a></span>';
							echo '</p>';
							echo '</h5></div>';
						else:
							echo '<button class="btn btn-link" style="text-decoration:line-through;color:#666;cursor:none;">ID: '.Functions::modificaId($listTarefa['id']).' - '.$listTarefa['titulo'].'</button>';
							echo '<p style="float:right;font-size:14px;color:#666;margin-top:10px;">';
							echo '<span style="margin-right:10px;padding-right:10px;border-right:1px solid #CCC;"><b>'. __('Total Trabalhado:') .' </b> '. $resultTt['atribuido'] .'</span>';
							echo '<span style=""><a style="vertical-align:none!important;padding:0!important;line-height:0!important;" class="btn btn-link" onclick="reabrirTarefa('.$listTarefa['id'].', '.$result['IdProjeto'].')">'. __('Reabrir Tarefa') .' </a></span>';
							echo '</p>';
							echo '</h5></div>';
						endif;
						
						echo '<div class="collapse" id="tf'.$listTarefa['id'].'"
								aria-labelledby="tarefas'.$listTarefa['id'].'" 
								data-parent="#tarefaPri"><div class="card-body">';
						
						if($DB->numrows($sqlTicketTask) < 1):
							echo '<div class="alert alert-primary" role="alert">'. __('Não existem chamados vinculados a esta tarefa!') .'</div>';
						else:	

							$consultaTickets = "SELECT 
												a.id 					AS Id,
												a.name					AS Titulo,
												b.name			 		AS Categoria,
												c.firstname			 	AS NomeRequerente,
												c.realname				AS SobRequerente,
												a.status				AS Status,
												a.priority				AS Prioridade
											FROM glpi_tickets a
											
											LEFT JOIN glpi_itilcategories b
												ON (b.id = a.itilcategories_id)
												
											LEFT JOIN glpi_users c 
												ON (c.id = a.users_id_recipient)
											
											WHERE a.id = ".$returnTicketTask['tickets_id']."
										"; 
							$sqlTickets = $DB->query($consultaTickets) or die(''. __('Erro ao buscar os chamados') .'');
							
							echo '<div><b>'. __('Chamado da tarefa:'). '</b></div>';
							echo '<table class="table table-striped"><thead>';
							echo '<tr>
									<th scope="col">'. __('ID') .'</th>
									<th scope="col">'. __('Título') .'</th>
									<th scope="col">'. __('Categoria') .'</th>
									<th scope="col">'. __('Requerente') .'</th>
									<th scope="col">'. __('Status') .'</th>
									<th scope="col">'. __('Prioridade') .'</th>	
								  </tr></thead><tbody>';
							while($listTickets = $DB->fetch_assoc($sqlTickets)):
								echo '<tr>
										<td><a target="blank" href="'.GLPI_URL.'ticket.form.php?id='.$listTickets['Id'].'">'.$listTickets['Id'].'</a></td>
										<td>'.$listTickets['Titulo'].'</td>
										<td>'.$listTickets['Categoria'].'</td>
										<td>'.$listTickets['NomeRequerente'].' '.$listTickets['SobRequerente'].'</td>
										<td class="muda_status'.$listTickets['Id'].'"><button class="btn btn-link" onclick="mudarStatus('.$listTickets['Id'].', '.$listTickets['Status'].')">'.Functions::conv_status($listTickets['Status']).'</button></td>
										<td>'. Functions::conv_prioridade($listTickets['Prioridade']) .'</td>
									  </tr>';
								echo '</tbody></table>';
							endwhile;	
							
						endif;	
						
						echo '</div></div></div>';

					endwhile;

					echo '</div></div>';

				endif;

				echo '</div></div></div>';

				// Modal
				echo '<div class="modal fade notify" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-sm">
						<div class="modal-content" id="contentModal">
							
						</div>
						</div>
					  </div>';

			endwhile;	

	} 
	

}