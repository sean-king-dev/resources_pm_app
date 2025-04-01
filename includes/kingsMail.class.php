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

class kingsMail{
	var $to = null;
    var $from = 'design@kingseducation.com';
    var $subject = null;
    var $body = null;
    var $headers = null;

    function kingsMail($to,$subject,$body){
        $this->to      = $to;
        $this->from    = 'design@kingseducation.com';
        $this->subject = $subject;
        $this->body    = $this->getBodies($body);
    }
	function getBodies($body=''){		
		switch ($body[0]){
			
			case '[confirm_stationary]':
				$body = 'Thank you,<br> Your stationery request has been sent '.date("Y-m-d",time()).'<br><br>
				Here is a copy of your request:<br><br>'
				.stripslashes($body['itembody']).' <br><br>Notes: <br>
				'.stripslashes($body['comment']).' <br><br>';
			
			break;
			
			case '[project_requst]':
				$body = 'You have a new Project request:  <br><br>
						http://production.kingscolleges.com/new/project/'.$body[1];
			break;
		
			case '[project_accepted]':
				$USER= new kingsUser();
				$members = $USER->returnUserfromItem($body[1]);
				if ($members){
					foreach($members as $member){
						$membersNames.=$member->name." - ". $member->email." <br>";
					}
					$deliverdate=date("d/m/Y", strtotime($body[3]));
					
					$body = "Your project ".$body[2]." has been allocated project number ".$body[1].".<br>
								The project will be handled by: <br><br>
								".$membersNames."<br><br>
								
								Project delivery date: <br><br>
								".$deliverdate." <br><br>
								
								Please contact the team member(s) above with any questions, additions or amendments to the project, referencing the project number in all communications.\n
								";
				}
			break;
			
			case '[ItemAlert]':
				$body = $body[2].' is due:'.$body[1];
			break;
			
			case '[ItemUpdate]':
				$body = 'A new update has been posted: <br><br>'.$body[1];
			break;
		
			case '[stationary]':
				$body = '
					You have received an order for: <br>
					'.$body['itembody'].' <br><br>
					From: <br>
					'.$body['delivery'].' <br><br>
					
					Please check the notes below, and unless otherwise instructed print and dispatch to the above address. <br>
					
					Please send the invoice to the following person:<br>
					<br>'.stripslashes($body['billing']).'<br>
					
					<br>Notes: <br>
					'.stripslashes($body['comment']).' <br><br>
					
					Please contact design@kingseducation.com with any problems.';
			break;
		
		
		
		
		
		
		
		


		
		}
		return $body;
	}
	
	function send($to=""){
	}

    function send_REMOVE($to=""){
      $this->addHeader('From: '.$this->from."\r\n");
        $this->addHeader('Reply-To: '.$this->from."\r\n");
        $this->addHeader('Return-Path: '.$this->from."\r\n");
        $this->addHeader('X-mailer: KingsColleges 1.0'."\r\n");
		$this->addHeader('Content-Type: text/html; charset=utf-8'."\r\n");
		$this->addHeader('Content-Transfer-Encoding: 8bit'."\r\n");
		$this->style();
		//print $this->to.' '.$this->subject.' '.$this->body.'<br>';
		//if ($this->to != 'ben.smith@matchboxmedia.co.uk'){
		//	$this->to = 'ben.smith@matchboxmedia.co.uk';
		//	}

	    mail($this->to,$this->subject,'<body><div id="container"><h1>Kings Colleges</h1>
			<h3>CENTRAL MARKETING PROJECT MANAGEMENT SYSTEM</h3>'.$this->body.'</div><div id="logo"></div></body></html>',$this->headers);
		
    }

    function addHeader($header){
        $this->headers .= $header;
    }

	function style(){
	 $this->headers .=	'
		<html>
		  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<style type="text/css">
				body{
				background:f4f4f4;
				}				
				#sig{
				font-family: Tahoma,Verdana,Arial,Helvetica,sans-serif;
				padding:25px; float:left;
				background-color:#339966;
				
				}
				#container{
				-moz-border-radius:6px 6px 6px 6px;
				background-color:#FFFFFF;
				border:1pt solid #CCCCCC;
				margin:0 auto;
				padding:10px;
				text-align:left;
				width:500px;
				}
				
				h1 {
				color:#56004E;
				font-size:1.6em;
				font-weight:bold;
				line-height:1;
				}
				h3{
				font-size:1.1em;
				font-weight:bold;
				margin-bottom: 15px;
				}
				
				#logo{
				background:url("http://production.kingscolleges.com/images/logo.gif") no-repeat scroll 0 0 transparent;
				height:100px;
				width:300px;
				margin:0;
				}
				
				#footer{
				font-size:0.8em;
				text-align:center;
				}
				
				a:link {
				color:blue;;
				text-decoration:none;
				}
				a:visited {
				color:#FFAA22;
				text-decoration:none;
				}
				
				a:active {
				color:#FFAA22;
				text-decoration:none;
				}
				
				a:hover {
				color:#002233;
				text-decoration:underline;
				}
				
				.style6 {font-size: 12px}
				-->
			</style>
		</head>';
	}

}




?>