<?php
error_reporting(0);

require_once( $_SERVER["DOCUMENT_ROOT"]."/core/autoload.php" );
require_once( $_SERVER["DOCUMENT_ROOT"]."/includes/kingsDB.inc.php" );

if($_GET['mydate']!=''){
		$a = strptime($_GET['mydate'],'%d/%m/%y');
		$timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
		$newDate = date("Y-m-d",$timestamp);
}else{
		$newDate='';
}

if($_GET['custom_alert_date']!=''){
		$a = strptime($_GET['custom_alert_date'],'%d/%m/%y');
		$timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
		$_GET['custom_alert_date'] = date("Y-m-d",$timestamp);
}else{
		$_GET['custom_alert_date']='0000-00-00';
}

switch($_GET['frm_type']){
	
	case 'delete_file':
		if ($_GET['fileid']){// is project Delete
		      mysql_query('delete from files where id = '.$_GET['fileid']);
		}
		break;
	case 'project_request':
		$profocus = serialize($_GET['frm_pro_focus']);
		$location = serialize($_GET['frm_location']);
		$area = serialize($_GET['frm_pro_area']);
		$Items = new kingsItems();
		
		$sql='INSERT INTO project_requests VALUES ("","'.mysql_real_escape_string($_GET['frm_title']).'","'.mysql_real_escape_string($_GET['frm_objective']).'","'.mysql_real_escape_string($_GET['frm_case']).'","'
		.$_GET['frm_tech'].'","'.mysql_real_escape_string($area).'","'.mysql_real_escape_string($profocus).'","'.mysql_real_escape_string($location).'","'.$newDate.'","'.date("Y-m-d",time()).'","'.$_GET['frm_created_by'].'")';
		$result = mysql_query( $sql );
		$alerts = new kingsAlerts($_GET['frm_pro_area']);
		$userids= $alerts->doAreas();
		//$request_id = mysql_insert_id();
		
		////$sql = 'INSERT INTO requeste_projects_map VALUES ("","'.$request_id.'","'.$projectId.'")';
		//$result = mysql_query( $sql );
		
		
		foreach($userids as $uid){// Send email to Members
				$body= array("[project_requst]",mysql_insert_id());
				$mail = new kingsMail($uid[1], 'New project request', $body);
				$mail->send();
		}
	
	break;
    
	case 'delete_project':
		
		if ($_GET['itemid']){// is project Delete
				mysql_query('delete from projects where id = '.$_GET['itemid']);

		}
	break;

	case 'deleteProduct':
		
		if ($_GET['id']){// is project Delete
				mysql_query('delete from products where id = '.$_GET['id']);
				mysql_query('delete from product_quantity_map where product_id = '.$_GET['id']);

		}
	break;

	case 'deleteSupplier':
		
		if ($_GET['id']){// is project Delete
				mysql_query('delete from suppliers where id = '.$_GET['id']);

		}
	break;

	
	case 'project':
		if ($_GET['id']){// is an update
				if($_GET['changed']=='frm_users' || $_GET['changed']=='frm_alerts' || $_GET['changed']=='frm_client' ){
						switch($_GET['changed']){
								case 'frm_users':
										$table = 'item_user_map';
										$valfield = 'user_id ';
								break;
								case 'frm_alerts':
										 $table = 'alerts_item_map';
											$valfield = 'alert_id ';
											$datetest =' and date ="'.$_GET['custom_alert_date'].'"';
											$customDateInsert =',"'.$_GET['custom_alert_date'].'",0';
											if($_GET['val']==6){
												$_GET['val']=5;
												$doinsert=1;
											}
								break;
								case 'frm_client':
										$table = 'item_client_map';
										$valfield = 'client_id ';
										$sql= 'delete from '.$table.' where type=0 and item_id='.$_GET['id'];
								$result = mysql_query( $sql );
								break;
						
						}
										$sql= 'select * from '.$table.' where type=0 and item_id='.$_GET['id'].' and '.$valfield.' ='.$_GET['val'];
										$result = mysql_query( $sql );
										$row = mysql_fetch_object($result);
						if ($row) {
								$sql= 'delete from '.$table.' where type=0 and item_id='.$_GET['id'].' and '.$valfield.' ='.$_GET['val'];
								$result = mysql_query( $sql );
								if($doinsert){
										$sql='INSERT INTO '.$table.' VALUES ("",'.$_GET['id'].','.$_GET['val'].',0'.$customDateInsert.')';
										$result = mysql_query( $sql );
								}								
						}else{
										$sql='INSERT INTO '.$table.' VALUES ("",'.$_GET['id'].','.$_GET['val'].',0'.$customDateInsert.')';
										$result = mysql_query( $sql );
						}
						
				
				}else if ($_GET['changed']=='frm_duedate'){
								$a = strptime($_GET['val'],'%d/%m/%y');
								$timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
								$val = date("Y-m-d",$timestamp);
								$sql= 'UPDATE projects SET '.str_replace('frm_','',$_GET['changed']).'="'.$val.'" WHERE id='.$_GET['id'];
						//print $sql;
						$result = mysql_query( $sql );
				}else if(	$_GET['changed']=='frm_description'){
						$sql= 'UPDATE projects SET	description="'.$_GET['val'].'" WHERE id='.$_GET['id'];
						$result = mysql_query( $sql );
				
				}else if(	$_GET['changed']=='frm_brief2'){
						$sql= 'UPDATE projects SET	brief="'.$_GET['val'].'" WHERE id='.$_GET['id'];
						$result = mysql_query( $sql );
				
				}else if(	$_GET['changed']=='frm_title'){
						$sql= 'UPDATE projects SET	title="'.$_GET['val'].'" WHERE id='.$_GET['id'];
						$result = mysql_query( $sql );
						
				}else{// keywords update
						
						
							$sql= 'select * from catagory_keywords_map where item_id='.$_GET['id'].
								' and Keyword_id ='.$_GET['val'];
						$result = mysql_query( $sql );
						$row = mysql_fetch_object($result);
						if ($row) {
								$sql= 'delete from catagory_keywords_map where item_id='.$_GET['id'].
										' and Keyword_id ='.$_GET['val'];
								$result = mysql_query( $sql );
						}else{
								$sql='INSERT INTO catagory_keywords_map VALUES ("",'.$_GET['val'].','.$_GET['id'].')';
								$result = mysql_query( $sql );
						}
						
						
				}
				
		}else{

				$sql = 'INSERT INTO projects
						VALUES ("","'.$_GET['frm_title'].'", "'.mysql_real_escape_string($_GET['frm_Brief']).'", "'.$newDate.'","",0, "'.date("Y-m-d",time()).'", '.$_GET['frm_created_by'].', "'.$_GET['frm_completed'].'", "'.mysql_real_escape_string($_GET['frm_brief2']).'")';
						//print $sql;
						$result = mysql_query( $sql );
						$projectId=mysql_insert_id();
						$Items = new kingsItems();
						!$Items->returnView($projectId,0,$_GET['frm_created_by'])?$Items->recordView($projectId,0,$_GET['frm_created_by']):null;
						$goback = true;
						
				if ($_GET['frm_Keyword']){
						foreach($_GET['frm_Keyword'] as $word){
						$sql = 'INSERT INTO catagory_keywords_map VALUES ("",'.$word.','.$projectId.')';
						$result = mysql_query( $sql );
						}
				}
				if ($_GET['frm_user']){
						foreach($_GET['frm_user'] as $user){
								$sql = 'INSERT INTO item_user_map VALUES ("",'.$projectId.','.$user.',0)';
								$result = mysql_query( $sql );		
						}
				}
				if ($_GET['frm_alerts']){
						foreach($_GET['frm_alerts'] as $alerts){
								//if($_GET['custom_alert_date'] && $alerts==5){$date=$_GET['custom_alert_date']}else{$date='0000-00-00'}
								$sql = 'INSERT INTO alerts_item_map VALUES ("",'.$projectId.','.$alerts.',0,"'.$_GET['custom_alert_date'].'",0)';
								$result = mysql_query( $sql );		
						}
				}
						$sql = 'INSERT INTO item_client_map VALUES ("",'.$projectId.','.$_GET['frm_client'] .',0)';
						mysql_query( $sql );
				
						$I = new kingsItems();
						!$I->returnView($projectId,0,$_GET['frm_created_by'])?$I->recordView($projectId,0,$_GET['frm_created_by']):null;
	
				if( $_GET['frm_request_id']){ // if project came from a client request
						
						$sql = 'INSERT INTO requeste_projects_map
						VALUES ("",'.$_GET['frm_request_id'].','.$projectId.')';
						//print $sql;
						$result = mysql_query( $sql );
					
					
					// Send email to clients
					$Users =  new kingsUser();
					$client = $Users->returnUserfromClient($_GET['frm_requestBy']);
					$body= array("[project_accepted]",$projectId,$_GET['frm_title'], $newDate);
					$mail = new kingsMail($client[0]->email, 'Project #'.$projectId.': '.$_GET['frm_title'], $body);
					$mail->send();
				}
		
		}
	
	break;
    
	case 'task':
		if ($_GET['frm_action']=='edit'){// update
				
				$sql= 'UPDATE tasks SET	title="'.$_GET['frm_title'].'",
				description ="'.mysql_real_escape_string ($_GET['frm_Brief']).'", brief2 ="'.mysql_real_escape_string ($_GET['frm_brief2']).'",	duedate="'.$newDate.'" WHERE id='.$_GET['frm_id'];
				$result = mysql_query( $sql );
				$goback=true;

				// remove users		
				$sql = 'delete from item_user_map where item_id='.$_GET['frm_id'];
				$result = mysql_query( $sql );
				//update users
				if ($_GET['frm_user']){ 
						foreach($_GET['frm_user'] as $user){
								$sql = 'INSERT INTO item_user_map VALUES ("",'.$_GET['frm_id'].','.$user.',1)';
								$result = mysql_query( $sql );
						}
				}
				// remove alerts		
				$sql = 'delete from alerts_item_map where item_id='.$_GET['frm_id'];
				$result = mysql_query( $sql );
				//update alerts
				if ($_GET['frm_alerts']){ 
						foreach($_GET['frm_alerts'] as $alerts){
								$sql = 'INSERT INTO alerts_item_map VALUES ("","'.$_GET['frm_id'].'","'.$alerts.'","1","'.$_GET['custom_alert_date'].'",0)';
								$result = mysql_query( $sql );
						}
				}
				
		}else{// insert Task
		if($_GET['frm_projectIdAssign']){
				
				$pid=$_GET['frm_projectIdAssign']; // new task with project assign drop down
		}else{
				$pid = 	$_GET['frm_projectId']; // new task already viweing a project
		}
				$sql= "insert into tasks
				value('','".$_GET['frm_title']."','".mysql_real_escape_string($_GET['frm_Brief'])."','".$newDate."','','".$pid."','".date("Y-m-d",time())."','". $_GET['frm_completed']."','". $_GET['frm_created_by']."','".mysql_real_escape_string($_GET['frm_brief2'])."'  )";  
				$goback=true;
				$result = mysql_query( $sql );
				$taskId=mysql_insert_id();
				$goback=true;
				//update users
				if ($_GET['frm_user']){ 
						foreach($_GET['frm_user'] as $user){
								$sql = 'INSERT INTO item_user_map VALUES ("",'.$taskId.','.$user.',1)';
								$result = mysql_query( $sql );
						}
				}
				if ($_GET['frm_alerts']){ 
						//update alerts
						foreach($_GET['frm_alerts'] as $alerts){
								$sql = 'INSERT INTO alerts_item_map VALUES ("","'.$taskId.'","'.$alerts.'","1","'.$_GET['custom_alert_date'].'",0)';
								$result = mysql_query( $sql );
						}
				}
				$I = new kingsItems();
				!$I->returnView($taskId,1,$_GET['frm_created_by'])?$I->recordView($taskId,1,$_GET['frm_created_by']):null;
		}
	
	break;

		case 'delete':// delete a task
				$sql='DELETE FROM tasks WHERE id = '.$_GET['id'];
				//print $sql;
				$result = mysql_query( $sql );
				
		break;

		case 'deleteRequest':// delete a task
				$sql='DELETE FROM project_requests WHERE id = '.$_GET['id'];
				//print $sql;
				$result = mysql_query( $sql );
		break;

		case 'deleteUser':// delete a task
				$sql='DELETE FROM users WHERE ID = '.$_GET['id'];
				//print $sql;
				$result = mysql_query( $sql );
		break;

case 'update':
		if ($_GET['frm_action']=='edit'){// update
				$sql= 'UPDATE updates SET
				detail  ="'.$_GET['frm_update'].'"
				WHERE id='.$_GET['frm_id'];
				$result = mysql_query( $sql );
				$goback=true;
		
		}else{// insert update
				$goback=true;
				$sql= "insert into updates
				value('','".$_GET['frm_update']."','".$_GET['frm_projectId']."','".date("Y-m-d",time())."',0,'".$_GET['frm_origin_user']."','".$_GET['frm_completed']."','0','".serialize($_GET['frm_user'])."' )";
				$result = mysql_query( $sql );
		}
		
		$ALERTS = new kingsAlerts();
		$ALERTS->doUpdate($_GET['frm_update'],$_GET['frm_user'],$_GET['frm_projectId'] );
	
	break;
case 'comment':
		if ($_GET['frm_action']=='edit'){// update
				$sql= 'UPDATE tasks SET	title="'.$_GET['frm_title'].'",
				description ="'.mysql_real_escape_string($_GET['frm_Brief']).'",	duedate="'.$originalDate.'"
				WHERE id='.$_GET['frm_id'];
				$result = mysql_query( $sql );

				// remove users		
				$sql = 'delete from item_user_map where item_id='.$_GET['frm_id'];
				$result = mysql_query( $sql );
				//update users
				if ($_GET['frm_user']){ 
						foreach($_GET['frm_user'] as $user){
								$sql = 'INSERT INTO item_user_map VALUES ("",'.$_GET['frm_id'].','.$user.',1)';
								$result = mysql_query( $sql );
						}
				}
				// remove alerts		
				$sql = 'delete from alerts_item_map where item_id='.$_GET['frm_id'];
				$result = mysql_query( $sql );
				//update alerts
				if ($_GET['frm_alerts']){ 
						foreach($_GET['frm_alerts'] as $alerts){
								$sql = 'INSERT INTO alerts_item_map VALUES ("","'.$_GET['frm_id'].'","'.$alerts.'","1","'.$_GET['custom_alert_date'].',0)';
								$result = mysql_query( $sql );
						}
				}
				
		}else{// insert comment
				$testfield = 'text'.$_GET['updateId'];
				$sql= "insert into updates
				value('','".$_GET[$testfield]."','".$_GET['projectId']."','".date("Y-m-d",time())."','".$_GET['updateId']."','".$_GET['users']."','".$_GET['frm_completed']."','0','')";
				$result = mysql_query( $sql );
				$sql = 'select * from users where ID='.$_GET['users'];
				$result = mysql_query( $sql );
				while( $x = mysql_fetch_object( $result ) ) {
				$userName = $x->name;
             }
		}
	break;

		case 'complete':
				$_GET['type']==0?$table = 'projects': $table='tasks';
				$sql = 'update '.$table;
				$sql .= ' set complete ="'.$_GET['val'].'"';
				$sql .= ' where id = '.$_GET['itemid'];
				$result = mysql_query( $sql );
		break;

		case 'product_select':
				$sql = 'select quantities.id, quantities.quantity
				from quantities
				left join product_quantity_map on quantities.id =  product_quantity_map.quantity_id
				where product_quantity_map.product_id='.$_GET['product'];
				$result = mysql_query( $sql );
				while( $x = mysql_fetch_object( $result ) ) {
				$options .='<option value="'.$x->id.'">'.$x->quantity.'</option>';
             }
		break;

		case 'staionary':/// Send supliers email about order
				$products = new kingsProducts();
				foreach ($_GET['prod'] as $p){
						$item = explode(',',$p);
							$product =$item[0];
							$value = $item[1];
								$sql = 'select * from products where id='.$product;
								$result = mysql_query( $sql );
								while( $x = mysql_fetch_object( $result ) ) {

								$options =array($x->name,$value );
								$supplier = $x->$_GET['frm_location'];
								$email = $products->selectSupplierEmail($supplier);
								$basket[$email][]=$options;
							 }
						}
				$body[0]='[stationary]';
				$UserClass= new kingsuser();
				$client = $UserClass->getClient($_GET['frm_client_id']);
				$billingContact =$client[0]->billing_contact;
				$billingEmail =$client[0]->billing_email;
				$billingaddress =$client[0]->billing_address;
				$delivery_address=$client[0]->delivery_address;
				$body['billing'].=$billingContact.'<br>'.$billingEmail.'<br>'.str_replace(',','<br>', $billingaddress);
				$body['delivery']=$_SESSION["user"]["name"].'<br> '.$delivery_address;
				$body['comment']=$_GET['frm_comments'];
				foreach ($basket as $key => $value){
						foreach($value as $item){
								$body['itembody'].=$item[0].' - '.$item[1].'<br>';
						}
						
						// send to statinery company
						$mail = new kingsMail($key, 'Kings Colleges materials order '.date("Y-m-d",time()), $body);
						$mail->send();
						
						// Send to james as well
						$mail = new kingsMail('james.allwright@kingscolleges.com', 'Kings Colleges materials order '.date("Y-m-d",time()), $body);
						$mail->send();
						
						// Send to ben as well
						$mail = new kingsMail('contact@bensmithportfolio.com', 'Kings Colleges materials order '.date("Y-m-d",time()), $body);
						$mail->send();
						
						//send confirmation to client
						$body[0]='[confirm_stationary]';
						$mail = new kingsMail($_SESSION["user"]["email"], 'Request confirmation', $body);
						$mail->send();
						
						$body['itembody']='';
				}
				
		
		break;

		case 'profile':
				if($_GET['frm_user']!=''){ // is an update
						if($_GET['frm_expertise'] ){
								$exp = implode(',',$_GET['frm_expertise']);
						}
								if(!isset($_GET['active'])) $_GET['active']=0;
								
								$sql= 'UPDATE users set	email="'.$_GET['frm_email'].'",
								innitial ="'.$_GET['frm_innitial'].'",	active ='.$_GET['active'].',	name="'.$_GET['frm_name'].'",
								expertise ="'.$exp.'", password ="'.$_GET['frm_password'].'",	user_type="'.$_GET['frm_admin'][0].'"
								WHERE ID='.$_GET['frm_user'];
								
								//PRINT $sql;
								$result = mysql_query( $sql );
						if($_GET['frm_locations']!=''){
								$sql= 'UPDATE users set	client_id="'.$_GET['frm_locations'].'"
								WHERE ID='.$_GET['frm_user'];
								$result = mysql_query( $sql );
								
								$sql='select billing_address,delivery_address from clients where id = '.$_GET['frm_locations'];
								$res=mysql_query($sql);
								while ($row = mysql_fetch_object($res)){
										$billing = $row->billing_address;
										$delivery = $row->delivery_address;
								}
						}		
								
								
								
				}else{// is a new one
						
						if($_GET['frm_client']!=''){
								$frmclientid=$_GET['frm_client'];
								$user_type='0';
						}
						
						if($_GET['frm_admin'][0]!=''){
								$user_type='1';
						}
						
						$sql = 'INSERT INTO users VALUES
						("","'.$_GET['frm_password'].'","'.$_GET['frm_name'].'","'.$_GET['frm_email'].'","'.$user_type.'","'.$frmclientid.'","'.$_GET['frm_innitial'].'","'.$_GET['frm_expertise'].'", "'.$_GET['active'].'")';
						
// 						print $sql;
						$result = mysql_query( $sql );
				}
		break;

		
		case 'suppliers':
				if($_GET['frm_supplier_id']!=''){ // is an update
		
						$sql= 'UPDATE suppliers set Name="'.$_GET['frm_name'].'",
						email="'.$_GET['frm_email'].'"
						where id="'.$_GET['frm_supplier_id'].'"';
						$result = mysql_query( $sql );

				}else{// is a new one
						
						$sql= 'insert into  suppliers set Name="'.$_GET['frm_name'].'",
						email="'.$_GET['frm_email'].'"';
						$result = mysql_query( $sql );
						$new_sup_id = mysql_insert_id();
						
				}
		break;
		
		case 'product':
				if($_GET['frm_product_id']!=''){ // is an update
		
						$sql= 'UPDATE products set name="'.$_GET['frm_name'].'",
						default_quantity_id="'.$_GET['frm_default_quantity'].'",
						UK="'.$_GET['frm_UK'].'",
						USA="'.$_GET['frm_USA'].'",
						China="'.$_GET['frm_china'].'"
						where id="'.$_GET['frm_product_id'].'"';
						
						$result = mysql_query( $sql );
						
						$sql='delete from product_quantity_map where product_id='.$_GET['frm_product_id'];
						$result = mysql_query( $sql );
						
						foreach($_GET['frm_quantities'] as $quant){
							$sql='insert into  product_quantity_map set product_id='.$_GET['frm_product_id'].', quantity_id='.$quant;	
							$result = mysql_query( $sql );
						}
						
						
						
						
				
				}else{// is a new one
						
						$sql= 'insert into  products set name="'.$_GET['frm_name'].'",
						default_quantity_id="'.$_GET['frm_default_quantity'].'",
						UK="'.$_GET['frm_UK'].'",
						USA="'.$_GET['frm_USA'].'",
						China="'.$_GET['frm_china'].'"';
						$result = mysql_query( $sql );
						$new_prod_id = mysql_insert_id();
						
						foreach($_GET['frm_quantities'] as $quant){
							$sql='insert into  product_quantity_map set product_id='.$new_prod_id.', quantity_id='.$quant;	
							$result = mysql_query( $sql );
						}
				}
		break;
		
}


switch($_POST['frm_type']){
		
		case 'update':
					$sql='update updates set seen = 1 where id ='.$_POST['val'];	
							$result = mysql_query( $sql );
				break;
		
		case 'alert':
								$sql='update alerts_item_map set seen = 1 where id ='.$_POST['val'];	
							$result = mysql_query( $sql );
				break;
		
}




    // If this is an AJAX call, echo out a JSON object for Javascript  
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	  $json = array(
		      'text' =>  $_GET[$testfield],
		      'date' => date("Y-m-d",time()),
			  'user' => $userName,
		           'success' => TRUE,
			  'options' => $options,
			  'goback' =>$goback,
			  'billing' =>$billing,
			  'delivery' =>$delivery,
			  'prod_id' =>$new_prod_id,
			  'sup_id' =>$new_sup_id
		  );
		echo json_encode($json);
	// Else, just display the message on a new page  
	} else {  
		echo 'Page was submitted...';  
		echo $message;  
	}

?>