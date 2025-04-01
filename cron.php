<?php
session_start();
require_once( $_SERVER["DOCUMENT_ROOT"]."/core/autoload.php" );
require_once( $_SERVER["DOCUMENT_ROOT"]."/includes/kingsDB.inc.php" );

$A = new kingsAlerts();

$A->doAlerts();

?>