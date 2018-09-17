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

//require_once('functions.class.php');

class Consult extends Functions {
/*
	function Times($idTicket){

		global $DB, $CFG_GLPI;

		$selectTimes = "SELECT 
							a.*, coalesce( timediff( current_timestamp(), a.ultimo_status ), time('00:00') ) tempo,
							p.name, p.comment

							FROM (

								SELECT a.tickets_id, max(a.date) date_a, sec_to_time( SUM(a.delay) ) atribuido, b.date_b ultimo_status
									FROM glpi_plugin_timelineticket_states a

									LEFT JOIN (

										SELECT b.tickets_id, max(b.date) as date_b
											FROM glpi_plugin_timelineticket_states b
										WHERE b.new_status = 2
										AND b.date >= ( 
											
											SELECT max(date) FROM glpi_plugin_timelineticket_states x  
											WHERE x.tickets_id = b.tickets_id
										
										)	
										GROUP BY b.tickets_id

									) b ON b.tickets_id = a.tickets_id
								
								WHERE 1 = 1
								AND a.old_status = 2
								AND a.tickets_id = $idTicket
								GROUP BY a.tickets_id

							) a

							LEFT JOIN glpi_projecttasks_tickets ptt ON ptt.tickets_id = a.tickets_id
							LEFT JOIN glpi_projecttasks pt ON pt.id = ptt.projecttasks_id
							LEFT JOIN glpi_projects p ON p.id = pt.projects_id
						";
		$sqlTimes = $DB->query($selectTimes) or die('Erro ao retornar a tarefa!');
		//$Time = $DB->fetch_assoc($sqlTimes);
		
		return $sqlTimes;

	}
*/

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
		$sql = $DB->query($select) or die('Erro');

			while($result = $DB->fetch_assoc($sql)):

				$consultTarefa = "SELECT * 
									FROM  `glpi_projecttasks`
									WHERE projects_id = ".$result['IdProjeto']."
								";
				$sqlTarefa = $DB->query($consultTarefa) or die('Erro ao retornar a tarefa!');

				$DataInicio = Functions::conv_data_hora($result['DataInicio']);
				$DataFinal  = Functions::conv_data_hora($result['DataFinal']);
				if($DataInicio == '' && $DataFinal == ''):
					$DataInicio = 'Não Informado';
					$DataFinal  = 'Não Informado';
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
				echo '<a target="blank" href="'.GLPI_URL.'project.form.php?id='.$result['IdProjeto'].'" style="float:right !important;font-size:14px;margin-top:10px !important;">Ir para o projeto</a>';
				echo '<p style="float:right;font-size:14px;color:#666;margin-top:10px;margin-right:50px;">
						<span style="margin-right:10px;"><b>Data Início:</b> '. $DataInicio .'</span>
						<span style="margin-right:10px;"><b>Data Final:</b> '. $DataFinal .'</span>
						<span style="margin-right:10px;"><b>Tempo Trabalhado: </b> </span>
					</p>';
				echo '</h5></div>';
				echo '<div id="collapse'.$result['IdProjeto'].'" class="collapse"
						aria-labelledby="heading'.$result['IdProjeto'].'" 
						data-parent="#projetos"><div class="card-body">';

				echo '<div class=""><div class="card-body">';
				echo ' 
					<b>Progresso do Projeto:</b> '.$result['Complete'].'%<br>
					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="'.$result['Complete'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$result['Complete'].'%;color:#000;font-weight:bold;padding-left:5px;">'.$result['Complete'].'%</div>
					</div><br>
					<b>Descrição: </b> '.$result['Conteudo'].'		
				';
				echo '</div></div>';
				
				if($DB->numrows($sqlTarefa) < 1):
					echo '<div class="alert alert-primary" role="alert">
							Não existem tarefas vinculadas a esse projeto!
				  		  </div>';
				else:

					echo '<div style="margin-top: 30px !important;">';
					echo '<div class="accordion col-md-12" id="tarefaPri">';

					while($listTarefa = $DB->fetch_assoc($sqlTarefa)):

						$consultaTicketTask = "SELECT *
												FROM `glpi_projecttasks_tickets`
												WHERE projecttasks_id = ".$listTarefa['id']."
											";
						$sqlTicketTask = $DB->query($consultaTicketTask) or die('Erro ao buscar os chamados da Task');
						$returnTicketTask = $DB->fetch_assoc($sqlTicketTask);
						
						echo '<div class="card">';
						echo '<div class="card-header" id="tarefas'.$listTarefa['id'].'">';
						echo '<h5 class="mb-0">';
						echo '<button class="btn btn-link" type="button"
								data-toggle="collapse" 
								data-target="#tf'.$listTarefa['id'].'" 
								aria-expanded="true" 
								aria-controls="tf'.$listTarefa['id'].'">'.$listTarefa['name'].'</button></h5></div>';
						echo '<div class="collapse" id="tf'.$listTarefa['id'].'"
								aria-labelledby="tarefas'.$listTarefa['id'].'" 
								data-parent="#tarefaPri"><div class="card-body">';
						
						if($DB->numrows($sqlTicketTask) < 1):
							echo '<div class="alert alert-primary" role="alert">
									Não existem chamados vinculados a esta tarefa!
								  </div>';
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
							$sqlTickets = $DB->query($consultaTickets) or die('Erro ao buscar os chamados');

							echo '<table class="table table-striped"><thead>';
							echo '<tr>
									<th scope="col">ID</th>
									<th scope="col">Título</th>
									<th scope="col">Categoria</th>
									<th scope="col">Requerente</th>
									<th scope="col">Status</th>
									<th scope="col">Prioridade</th>	
								  </tr></thead><tbody>';
							while($listTickets = $DB->fetch_assoc($sqlTickets)):
								echo '<tr>
										<td>'.$listTickets['Id'].'</td>
										<td>'.$listTickets['Titulo'].'</td>
										<td>'.$listTickets['Categoria'].'</td>
										<td>'.$listTickets['NomeRequerente'].' '.$listTickets['SobRequerente'].'</td>
										<td class="muda_status"><button class="btn btn-link" onclick="mudarStatus('.$listTickets['Id'].', '.$listTickets['Status'].')">'.Functions::conv_status($listTickets['Status']).'</button></td>
									  </tr>';
								echo '</tbody></table>';
							endwhile;	
							
						endif;	

						echo '</div></div></div>';

					endwhile;

					echo '</div></div>';

				endif;

				echo '</div></div></div>';

			endwhile;	

	} 
	

}