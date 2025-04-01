<?php
error_reporting(0);
require_once( $_SERVER["DOCUMENT_ROOT"]."/core/autoload.php" );
require_once( $_SERVER["DOCUMENT_ROOT"]."/includes/kingsDB.inc.php" );

$Items = new kingsItems();

switch($_GET['search']){
		
		case 'project':
				if ($_GET['frm_user'] ){
						$total = count($_GET['frm_user']);
						$where .=' and item_user_map.user_id in ( ';
						$i=1;
						foreach ($_GET['frm_user'] as $user){
								$where .=$user;
								if($total==$i ){
									$where .=')';	
								}else{
									$where .=', ';	
								}
								$i=$i+1;
						}
				}

				if ($_GET['frm_clientsearch'] ){
						$where .=' and item_client_map.user_id = '.$_GET['frm_clientsearch'];
				}
				
				
				if ($_GET['frm_Keyword'] ){
						$total = count($_GET['frm_Keyword']);
						$i=1;
						foreach ($_GET['frm_Keyword'] as $word){
								$where.='and projects.id in(
										select distinct(projects.id) from projects 
											    left join item_user_map on projects.id = item_user_map.item_id
											    left join catagory_keywords_map on projects.id = catagory_keywords_map.item_id
											    where item_user_map.type="0"   
										and catagory_keywords_map.Keyword_id ="'.$word.'" 
										 )';
								$i=$i+1;
						}
						
						
				}
				
				
				if ($_GET['page'] ){
						if( $_GET['page']=='undefined'){$_GET['page']=0;}
						$limit=array('currentPage'=>$_GET['page'],'sql'=>' limit '.$_GET['page']*50 . ', '.round((($_GET['page']*50)+50)));
				}
				
				if ($_GET['show']!='' ){
						if ($_GET['show']=='2'){
								$where .=' and (projects.complete = 2)';	
						}elseif($_GET['show']=='3'){
									$where .=' and 1';	
						}else{
								$where .=' and projects.complete = '.$_GET['show'].' ';
								$order = ' order by id desc';
						}
				}
				
				if ($_GET['searchString']){
						$where .=' AND (';
						$where .='  projects.id like "%'.$_GET['searchString'].'%"';
						$where .=' OR projects.title like "%'.$_GET['searchString'].'%"';
						$where .=' OR projects.description like "%'.$_GET['searchString'].'%" )';	
				}

	
	//echo $where;
				$projects = $Items->outputProjects($where,$_GET['user'],'',$order,$limit);
				
		break;
		
		case 'task':
				if ($_GET['frm_user'] ){
						$total = count($_GET['frm_user']);
						$where .=' and item_user_map.user_id in ( ';
						$i=1;
						foreach ($_GET['frm_user'] as $user){
								$where .=$user;
								if($total==$i ){
									$where .=')';	
								}else{
									$where .=', ';	
								}
								$i=$i+1;
						}
				}
				
				if ($_GET['show']!='' ){
						if ($_GET['show']=='2'){
								$where .=' and (tasks.complete = 0 OR  tasks.complete = 1)';	
						}else{
								$where .=' and tasks.complete = '.$_GET['show'].' ';
								$order = ' order by id desc';
						}
				}
				
				if ($_GET['searchString']){
						$where .=' AND (';
						$where .='  tasks.id like "%'.$_GET['searchString'].'%"';
						$where .=' OR tasks.title like "%'.$_GET['searchString'].'%"';
						$where .=' OR tasks.description like "%'.$_GET['searchString'].'%" )';
				}
				//print $order;
				$projects = $Items->outputTasks('',$where,$_GET['user'],'');
		break;

}





    // If this is an AJAX call, echo out a JSON object for Javascript  
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	  $json = array(
		      'projects' =>  $projects,
		      'date' => date("Y-m-d",time()),
			  'user' => $userName,
		      'success' => TRUE,
		  );
		echo json_encode($json);
	// Else, just display the message on a new page  
	} else {  
		echo 'Page was submitted...';  
		echo $message;  
	}

?>