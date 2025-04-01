
<?PHP
require_once( $_SERVER["DOCUMENT_ROOT"]."/core/autoload.php" );
require_once( $_SERVER["DOCUMENT_ROOT"]."/includes/kingsDB.inc.php" );


//$kw[38]='887';
$kw[93]='920';







 //do new keywords

   foreach($kw as $excelId=>$wrong_id){
    print '<h3>'.$wrong_id.'</h3>';
 //   mysql_query('update Keywords set sort='.$sort.' where name ="'.$word.'"');
    //print 'update alerts_item_map set item_id='.$excelId.' where item_id ="'.$wrong_id.'"<br>';
    //print 'update item_client_map set item_id='.$excelId.' where item_id ="'.$wrong_id.'"<br>';
    //print 'update item_user_map set item_id='.$excelId.' where item_id ="'.$wrong_id.'"<br>';
    //print 'update item_user_views set item_id='.$excelId.' where item_id ="'.$wrong_id.'"<br>';
    //print 'update projects set id='.$excelId.' where id ="'.$wrong_id.'"<br>';
    //
    //
    //
     //mysql_query('update alerts_item_map set item_id='.$excelId.' where item_id ="'.$wrong_id.'"');
     // mysql_query('update item_client_map set item_id='.$excelId.' where item_id ="'.$wrong_id.'"');
     // mysql_query('update item_user_map set item_id='.$excelId.' where item_id ="'.$wrong_id.'"');
     // mysql_query('update item_user_views set item_id='.$excelId.' where item_id ="'.$wrong_id.'"');
     // mysql_query('update projects set id='.$excelId.' where id ="'.$wrong_id.'"');

   } 


//
//$Items = new kingsItems();
////  mysql_query( $sql='DELETE FROM projects' );
////  mysql_query( $sql='DELETE FROM updates' );
////  mysql_query( $sql='DELETE FROM item_client_map' );
////  mysql_query( $sql='DELETE FROM item_user_map' );
////  mysql_query( $sql='DELETE FROM item_user_views' ); 
//
//
//$file_handle = fopen("docs/Current-jobs.csv", "r");
//
//while (!feof($file_handle) ) {  
//  $line_of_text = fgetcsv($file_handle, 1024);
//   // each row or record
//
//      $createdbyArray = explode('/',$line_of_text[7]);
//      $createdById = $createdbyArray[0];
//      $line_of_text[5]!=''? $startDate = date('Y-d-m',returnTimestamp($line_of_text[5])):$startDate='0000-00-00';
//      $line_of_text[6]!=''? $endDate = date('Y-d-m',returnTimestamp($line_of_text[6])):$endDate='0000-00-00';
//
//      //insert a project
//      $table = 'projects';
//      $columns = 'id, title, description, duedate, type, created, created_by, complete';
//      $values = '"'.$line_of_text[0].'","'.$line_of_text[2].'","'.mysql_real_escape_string($line_of_text[3]).'","'.
//      $startDate.'",0,"'. $endDate.'",'.getUser($createdById).',0';
// //     $insert_id = insert($table,$columns,$values);
//      
//      //get correct user id
//      //insert into item user map
//       foreach ($createdbyArray as $abbr){
//        $table = 'item_user_map';
//        $columns ='item_id, user_id, type';
//        $values = $line_of_text[0].','.getUser($abbr).',0';
////       insert($table,$columns,$values);  
//      }
//      
//      //insert into client item map
//      foreach (explode('/',$line_of_text[4]) as $abbr){
//        $table = 'item_client_map';
//        $columns ='item_id, user_id, type';
//        $values = $line_of_text[0].','.getUser($abbr).',0';
// //       insert($table,$columns,$values);  
//      }
//      
//      //insert into views
//        $table = 'item_user_views';
//        $columns ='item_id, user_id, type';
//        $values = $line_of_text[0].','.getUser($createdById).',0';
// //       insert($table,$columns,$values);  
//      
//      //insert into updates
//      if ($line_of_text[8] !=''){ 
//        $table = 'updates';
//        $columns ='detail,project_id,date,user_id';
//        $values = '"'.mysql_real_escape_string($line_of_text[8]).'","'.$line_of_text[0].'","'.date('Y-d-m').'",'.getUser($createdById);
////        insert($table,$columns,$values);  
//      }
//  print  "<BR>";
//  $userId ='';
//}
//
//function insert($table, $columns, $values){
//  $sql ='INSERT INTO '.$table.' ('.$columns.')
//        VALUES ("'.$values.'")';
//        print '<br>';
//        print $sql;
//  $result = mysql_query( $sql );      
//}
//
//
//function returnTimestamp($date){
//      $format = '%d/%m/%Y';
//      $a = strptime($date, $format);
//      $timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
//      return $timestamp;
//}
//
//function getUser($userletters){
//  $sql='select id from users where innitial="'.$userletters.'"';
//  $result = mysql_query( $sql );
//  while( $p = mysql_fetch_object( $result ) ) {
//        $id = $p->id;
//  }
//  return $id; 
//}
//
//
//fclose($file_handle);
//
//
//
//function dokeywords(){
//  $words = array(  'Kings',
//    'Prime',
//    'Print',
//    'Web',
//    'Mobile devices',
//    'Video',
//    'Keynote/Ppt',
//    'Brochure',
//    'Flyer',
//    'Wall Art',
//    'Poster',
//    'Testimonial',
//    'Advertisement',
//    'Promotion',
//    'Banner',
//    'Signage',
//    
//    'English Language',
//    'Academic',
//    'Summer',
//    
//    'UK',
//    'USA',
//    'Boston',
//    'Los Angeles',
//    'Bournemouth',
//    'London',
//    'Oxford',
//    
//    '2010',
//    '2011',
//    '2012',
//    '2013',
//    '2014');
//  
//  return $words;
//}

?>



Job number,Brand,Project title,Project description,Client,Start date,Delivery date,Actioned by,Comments,PPI job reference,