<?php

/**
 * The core formatter class
 *
 * File with the core information about the Formatter class
 *
 * LICENSE: GPL v.3
 *
 * @copyright   2012 Matchbox
 * @license     http://www.Matchboxmedia.co.uk/license/license.txt
 * @version     1.0
 * @link        http://www.Matchboxmedia.co.uk/core/formatter.class.php
 * @since       File available since 1.0
 */
error_reporting(0);

class kingsAlerts{
				var $allAreas = '';
    var $area = '';
				var $alerts = array();
				var $updatesAlerts = array();


 function kingsAlerts($area=''){
		$Items = new kingsItems();
		$this->allAreas 	= $Items->projectAreaOptions();
  $this->area 		= $area;
 }
	
	function doAreas(){
		$users= new kingsUser;
		$members = $users->returnUser();
				foreach ($members as $member){
					$y=0;
					$expertArray = explode(",",$member->expertise);
					foreach ($expertArray as $areaItem){
						foreach ($this->area as $a){
							if ($areaItem == $a){
							$y=1;
							break;
							}
						}
					}
					$y==1?$email[] = array($member->ID,$member->email, $member-name):null;
				}
		return $email;
	}
	
function doAlerts(){

		$ITEMS = new kingsItems();
		$sql='select  * from projects where duedate >= "'.date("Y-m-d",time()).'" and complete =0';
		$results = mysql_query( $sql );
		while( $p = mysql_fetch_object( $results ) ) {
				$alerts = $ITEMS->getAlerts($p->id, 0);
				if($alerts){
						foreach($alerts as $alert){
								$this->alertType($alert->alert_id,$p->duedate, $p->id,$p->title,$alert->date,'project',$alert);
						}
				}
		}
		$sql='select  * from tasks where duedate >="'.date("Y-m-d",time()).'" and complete =0';
		$results = mysql_query( $sql );
		while( $p = mysql_fetch_object( $results ) ) {
				$alerts = $ITEMS->getAlerts($p->id, 1);
				if($alerts){
						foreach($alerts as $alert){
						$this->alertType($alert->alert_id,$p->duedate, $p->id,$p->title,$alert->date,'task',$alert);
						}
				}
		}
}
	
	function doUpdate($body,$users,$project_id){
		if ($users){
		foreach($users as $user){
					$sql = 'select * from users where id = '.$user;
					$results = mysql_query( $sql );
					while( $user = mysql_fetch_object( $results ) ) {
						$this->alerts[]=array($user,$date,$project_id,$title,$customDate,'updates' );
						$MAIL = new kingsMail($user->email, 'Update', array('[ItemUpdate]',$body));
						$MAIL->send();
				}
			}
		}				
	}
	
	
		function doInsystemUpdate(){
					$sql = 'select * from updates where seen = 0';
					$results = mysql_query( $sql );
					while( $update = mysql_fetch_object( $results ) ) {
						$user_ids = unserialize($update->send_to_users);
						foreach($user_ids as $user_id){
								$sql3 = 'select * from users where id = '.$user_id;
								$results3 = mysql_query( $sql3 );
								while( $user = mysql_fetch_object( $results3 ) ) {
										$this->updatesAlerts[]=array('user'=>$user,'update'=>$update);
								}
						}
				}
	}
	
	function alertType($alert_id,$date,$item_id,$title,$customDate,$contentType,$alert){
		$USER = new kingsUser();
		switch ($alert_id){
			case 1:// 1 month
				if  ($date == date("Y-m-d",strtotime("next Month"))){
					$users = $USER->returnUserfromItem($item_id);
					foreach ($users as $user){
						if($alert->seen!=1){
								$this->alerts[]=array($user,$date,$item_id,$title,$customDate,$contentType,$alert->id  );
						}
						$MAIL = new kingsMail($user->email, 'Alert', array('[ItemAlert]',' In 1 month',$title));
						$MAIL->send();
					}
				}
			break;
				
			case 2:// 2 weeks
				if( $date ==  date("Y-m-d",strtotime("2 Weeks"))){
				$users = $USER->returnUserfromItem($item_id);
					foreach ($users as $user){
						if($alert->seen!=1){
								$this->alerts[]=array($user,$date,$item_id,$title,$customDate,$contentType,$alert->id  );
						}
						$MAIL = new kingsMail($user->email, 'Alert', array('[ItemAlert]',' In 2 weeks',$title));
						$MAIL->send();
					}
			}
			break;
		
			case 3:// 1 day
				if ($date == date("Y-m-d",strtotime("1 Day"))){
					$users = $USER->returnUserfromItem($item_id);
					foreach ($users as $user){
						if($alert->seen!=1){
							$this->alerts[]=array($user,$date,$item_id,$title,$customDate,$contentType,$alert->id  );
						}
						$MAIL = new kingsMail($user->email, 'Alert', array('[ItemAlert]',' Tomorrow',$title));
						$MAIL->send();
					}
				}
			break;
		
			case 4:// delivery
				if ($date == date("Y-m-d",time())){
					$users = $USER->returnUserfromItem($item_id);
					foreach ($users as $user){
						if($alert->seen!=1){
								$this->alerts[]=array($user,$date,$item_id,$title,$customDate,$contentType,$alert->id );
						}
						$MAIL = new kingsMail($user->email, 'Alert', array('[ItemAlert]',' Today',$title));
						$MAIL->send();
					}
				}
			break;

		case 5:// Custom
				if ($customDate == date("Y-m-d",time())){
					$users = $USER->returnUserfromItem($item_id);
					foreach ($users as $user){
						if($alert->seen!=1){
								$this->alerts[]=array($user,$date,$item_id,$title,$customDate,$contentType,$alert->id );
						}
						$MAIL = new kingsMail($user->email, 'Alert', array('[ItemAlert]',' '.$customDate,$title));
						$MAIL->send();
					}
				}
			break;
		}	
	}
	
	
}
	






?>