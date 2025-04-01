	
<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/core/autoload.php" );
require_once( $_SERVER["DOCUMENT_ROOT"]."/includes/kingsDB.inc.php" );
/*
*Code By Abhishek R. Kaushik
* Downloaded from http://devzone.co.in
*/
$upload_dir = "uploads/";
if (isset($_POST["pid"])) {

    if (isset($_FILES["myfile"])) {
        if ($_FILES["myfile"]["error"] > 0) {
            echo "Error: " . $_FILES["file"]["error"] . "<br>";
        } else {
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_dir . $_FILES["myfile"]["name"]);
                //echo "Uploaded File :" . $_FILES["myfile"]["name"];
            
            $sql='delete FROM files where pid='.$_POST["pid"].' and name = "'.$_FILES['myfile']['name'].'"';
            $results = mysql_query( $sql );
         
              
            $sql = 'INSERT INTO files VALUES ("","'.$_POST['txtname'].'","'.$_POST['pid'].'","'.$_FILES['myfile']['name'].'")';
            $result = mysql_query( $sql );
            
            $sql = 'select * from files where pid='.$_POST["pid"];
            $result = mysql_query( $sql );

            if($result){
                while( $res = mysql_fetch_object( $result ) ) {
                    $ROWS.= '<li id="file'.$res->id.'"><a href="/uploads/'.$_FILES['myfile']['name'].'">'.$res->name.'</a> | <span class="link" onclick="deleteFile('.$res->id.')">delete</span></li>';
                  }
                  ECHO $ROWS;
        }
        //print_r($_POST);
        //print_r($_FILES);
        }
    }
}else{
    echo 'You must save the project before uploading files';
}

?>