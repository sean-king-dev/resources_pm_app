<?php

/* ************************************************************ */
/*                                                              */
/*  Core Function Library                                       */
/*                                                              */
/*  Class: User.class.php                                       */
/*                                                              */
/*  Author: Philip Cole                                         */
/*  Date:   10th July 2007                                      */
/*                                                              */
/*  This is the core user class                                 */
/*                                                              */
/* ************************************************************ */
/*  Revision History                                            */
/*                                                              */
/*  2007-07-10: v0.1    Initial Creation                        */
/*                                                              */
/*                                                              */
/*                                                              */
/* ************************************************************ */

class User {
	
	// PUBLIC VARIABLES
	public $display_name;
	public $loggedIn = "N";
	public $errors;
	
	// PROTECTED VARIABLES
	protected $encPassword = "md5";
	protected $userID;
	protected $username;
	
	protected $logout_redir = "/";
	
	// Names to be used for the session variables.
	// protected $userID_session = "userID";
	// protected $username_session = "username";

	// PRIVATE VARIABLES
	
	public function __construct() {
	}

/*
 	public function login( $username, $password ) {
		$enc_password = $this->encPassword( $password );
		$query = "SELECT ID, personname FROM users WHERE username = '".$username."' and password = '".$enc_password."' LIMIT 1;";
		$user = mysql_fetch_object( mysql_query( $query ) );
		if ( $user->ID > 0 ) {
			$this->userID = $user->ID;
			$_SESSION["userID"] = $this->userID;
			$this->username = $username;
			$_SESSION["username"] = $this->username;
			$_SESSION["personname"] = $user->personname;
			$this->loggedIn = "Y";
			$_SESSION["loggedIn"] == "Y";
		}
	}
*/

	protected function encPassword( $password ) {
		switch( $this->encPassword ) {
			case "md5":
				$enc_password = md5( $password );
			break;
			case "sha1":
				$enc_password = sha1( $password );
			break;
			case "":
				$enc_password = $password;
			break;
		}
		return $enc_password;
	}
	
	public function isLoggedIn() {
		isset( $_SESSION["userID"] ) && isset( $_SESSION["username"] ) ? $this->loggedIn = "Y" : NULL;
	}
	
	public function setPassword( $old_pass, $new_pass ) {
		$query = "SELECT ID FROM users WHERE password = ";
		$query = "UPDATE users SET password = ".$old_pass."";
	}
	
	
	
	
	
	public function logout() {
		session_unset();
		session_destroy();
		header( "Location: http://".$_SERVER["HTTP_HOST"]."" );
	}

}




?>