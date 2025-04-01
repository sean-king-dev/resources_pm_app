<?php

/* ************************************************************ */
/*                                                              */
/*  Intranet User                                               */
/*                                                              */
/*  Class: IntranetUser.class.php                               */
/*                                                              */
/*  Author: Philip Cole                                         */
/*  Date:   8th September 2008                                  */
/*                                                              */
/*                                                              */
/* ************************************************************ */
/*  Revision History                                            */
/*                                                              */
/*  2008-09-08: v0.1    Initial Creation                        */
/*                                                              */
/*                                                              */
/*                                                              */
/* ************************************************************ */

class kingsUser {
    
    // Protected Variables

    public function __construct() {
    }
    
    protected function _getModules( $type="main" ) {
        $type == "sub" ? $moduletype = "sub_modules" : $moduletype = "main_modules";
        $query = "SELECT ".$moduletype." AS modules FROM users WHERE ID = ".$_SESSION["user"]["ID"]." LIMIT 1;";
        $modules = mysql_fetch_object( mysql_query( $query ) );
        $module_data = explode( ":", $modules->modules );
        foreach( $module_data as $md ) {
            $core = explode( "-", $md );
            $mod_array_data = explode( ",", $core[2] );
            $mod_array = "";
            foreach( $mod_array_data as $mda ) {
                $query = "SELECT module_code AS title FROM modules WHERE ID = ".$mda." LIMIT 1;";
                $m = mysql_fetch_object( mysql_query( $query ) );
                $mod_array[] = $m->title;
            }
            $mods[] = array( "title"=>$core[0], "code"=>$core[1], "modules"=>$mod_array );
        }
        return $mods;
    }

    public function checkLogin(){
        if ( $_SESSION["user"]['ID'] == "" )
            return false;
        else
            return true;
    }
    
    public function processLogin() {
        $query = "SELECT ID, email, name, user_type, client_id FROM users WHERE email = '".$_POST["log"]."' AND password = '".$_POST["pwd"]."' LIMIT 1;";
        $user = mysql_fetch_object( mysql_query( $query ) );

	    if ( $user->ID > 0) {
		$_SESSION["user"]["ID"] = $user->ID;
		$_SESSION["user"]["email"] = $user->email;
		$_SESSION["user"]["name"] = $user->name;
		$_SESSION["user"]["client_id"] = $user->client_id;
			    $_SESSION["user"]["type"] = $user->user_type;
							
				if($_SESSION["user"]["type"]==1){
								$_SESSION['user']['admin']=1;
				}
		if ( $_POST["rememberme"] == "forever" ) {
		    setcookie( "userName", $user->name);
		    setcookie( "userPass", $_POST["log"]);
		}
		
	    if ($_SESSION["user"]["client_id"] && $_SESSION["user"]["type"]==0){
			     header( "location: /project-request-form" );
		    }else{
			    header( "location: /" );
		    }
    
	    } else {
		header( "location: /error" );
	    }
	
      // print_r($_COOKIE);
    }
    
    public function processLogout() {
        if ( isset( $_COOKIE["userName"] ) ) {
            //setcookie( "user[username]", "", time() - 3600, "/", "intranet.preview.co.uk" );
            setcookie( "userName", "", time() - 3600, "/", "http://resource.kingseducation.com/" );
            setcookie( "userPass", "", time() - 3600, "/", "http://resource.kingseducation.com/" );
        }
        session_unset();
        session_destroy();
        
    }

    public function returnMainModules() {
        $mods = $this->_getModules();
        return $mods;
    }
    
    public function returnSubModules() {
        $mods = $this->_getModules( "sub" );
        return $mods;
    }
				
				public function getAllnonAdminUsers(){
								$sql='select * from users order by user_type desc , name asc ';
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
              $users[] = $p;
            }
        return $users;
								
				}
    
    public function returnUser($uid=""){
       $uid==''? $sql='select * from users where client_id=0 or user_type=1': $sql='select * from users where ID='.$uid;
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
              $users[] = $p;
            }
        return $users;
    }
    
    public function returnInactiveUser($uid=""){
       $uid==''? $sql='select * from users where active=0 and (client_id=0 or user_type=1)': $sql='select * from users where active=0 and ID='.$uid;
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
              $users[] = $p;
            }
        return $users;
    }
    
    public function returnUserfromClient($cid=""){
       $sql='select * from users where ID='.$cid .' order by name';
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
              $users[] = $p;
            }
        return $users;
    }
    
    
    public function getClient($cid=""){
       $sql='select * from clients where id='.$cid;       
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
              $clients[] = $p;
            }
        return $clients;
    }
	
	public function getAllClients(){
       $sql='select * from clients where active=1 order by location';       
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
              $clients[$p->id] = $p->location;
            }
        return $clients;
    }
    
    public function getclientFromUser($cid=""){
       $sql='select * from users where id='.$cid ;
         $results = mysql_query( $sql );
		 if ($results){
            while( $p = mysql_fetch_object( $results ) ) {
              $users[] = $p;
            }
		 }
		 return $users;
    }
    
      public function getClientFromProject($pid){
         $sql='SELECT DISTINCT (
              item_client_map.user_id
              ), users . *
              FROM item_client_map
              LEFT JOIN users ON item_client_map.user_id = users.ID
              WHERE item_client_map.item_id ='.$pid;
         $results = mysql_query( $sql );
		 if ($results){
            while( $p = mysql_fetch_object( $results ) ) {
              $clients[] = $p;
            }
		 }
        return $clients;
    }
    
     public function outputClients() {
         $sql='select * from users where client_id > 0 and active=1 order by name';
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
              $clients[] = $p;
            }
            
        return $clients;
    }
    
     public function outputAllClients() {
         $sql='select * from users where client_id > 0 order by name';
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
              $clients[] = $p;
            }
            
        return $clients;
    }
    
     public function outputInactiveClients() {
         $sql='select * from users where client_id > 0 and active=0 order by name';
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
              $clients[] = $p;
            }
            
        return $clients;
    }
    
    
    public function returnUserfromItem($pid,$type=0){
        $sql='SELECT DISTINCT (
              item_user_map.user_id
              ), users . *, type
              FROM item_user_map
              LEFT JOIN users ON item_user_map.user_id = users.ID
              WHERE item_user_map.item_id ='.$pid;
         $results = mysql_query( $sql );
            while( $p = mysql_fetch_object( $results ) ) {
																if($type && $p->type==1 || !$type){
																				$users[] = $p;
																}
            }
        return $users;
    }
    public function isClient($cid=''){        
        $cid=$_SESSION['user']['client_id'];
        if($cid){
            return $cid;
        }else{
            return false;
        }
    }
    
}


?>
