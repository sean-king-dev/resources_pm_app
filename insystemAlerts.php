<?php
	session_start();
	require_once( $_SERVER["DOCUMENT_ROOT"]."/core/autoload.php" );
	require_once( $_SERVER["DOCUMENT_ROOT"]."/includes/kingsDB.inc.php" );
	
	$A = new kingsAlerts();
	$A->doAlerts();	
	$i=0;
	
	if($_POST['type']=='normal'){
			$inSystemAlerts_check = 	$A->alerts;
				foreach($inSystemAlerts_check as $alert){// test tehre is an alert for the currently logged in user
					if($_SESSION['user']['ID']==$alert[0]->user_id){
						$i++;
							$insystemAlerts.=
									'<li><a href="/view-'.$alert[5].'/'.$alert[2].'">'.$alert[3].' - Due: '.$alert[1].'</a> <a class="seen" onclick="seenit(\'seenalert'.$alert[6].'\')" href="#">seen</a><input  id="seenalert'.$alert[6].'" type="hidden" name="alert" value="'.$alert[6].'"></li>';
					}
				}
	}
			$A->doInsystemUpdate();
				foreach($A->updatesAlerts as $alert){// test there is an alert for the currently logged in user
				if($_SESSION['user']['ID']==$alert['user']->ID){
					$i++;
						$insystemAlerts.=	'<li><a style="display:inline-block;" href="/view-updates/update/'.$alert['update']->id.'">'.$alert['update']->detail.'</a> <a class="seen" onclick="seenit(\'seenalert'.$alert['update']->id.'\')" href="#">seen</a>  <input id="seenalert'.$alert['update']->id.'" type="hidden" name="update" value="'.$alert['update']->id.'"> </li> ';
				}
			}

if($i>0){
	echo json_encode($insystemAlerts);
}


?>