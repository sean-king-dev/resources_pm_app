<?php

ini_set( "include_path", ".:".$_SERVER["DOCUMENT_ROOT"]."/core:".$_SERVER["DOCUMENT_ROOT"]."/includes" );

function __autoload( $class ) {
    if ( stristr( $class, "zend" ) ) {
        $zend_class = explode( "_", $class );
        $zclass = "";
        $i = 1;
        foreach( $zend_class as $zc ) {
            $i == 1 ? $prefix = "" : $prefix = "/";
            $zclass .= $prefix.$zc;
            $i++;
        }
        require_once( $zclass.".php" );
    } else {
        require_once( $class.".class.php" );
    }
}

?>