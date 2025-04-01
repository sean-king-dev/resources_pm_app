<?php
switch ($_GET['changed']){
    case 'location':
    $sql = 'SELECT *  FROM times where location_id='.$_GET['location'];
	    if ($sql){
		$result = mysql_query( $sql );
		while( $p = mysql_fetch_object( $result ) ) {

		$date =	strtotime("next ".$p->dayOfWeek);
		$eventDate = date("d/m/Y", $date);
		    
		$times[]=array($p->id,$eventDate.' - '. $p->time);
		}
	}
	break;
}

    // If this is an AJAX call, echo out a JSON object for Javascript  
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	  $json = array(
		      'time' =>  $times,
		      'date' => $eventDate,
		      'success' => TRUE,
		  );
		echo json_encode($json);
	// Else, just display the message on a new page  
	} else {  
		echo 'Page was submitted...';  
		echo $message;  
	}

?>