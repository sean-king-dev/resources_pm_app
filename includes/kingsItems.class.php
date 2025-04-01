<?php

// kings stock Class

class kingsItems {
    
    protected $ItemsArray;      // Main array holding the items
    // Private Variables

    public function __contruct() {
    }
    
  public function outputItems() {
   $wp = new kingsWebpage();
    $shed .='<table>';
    $shed.='<table class="list"><tr><td>Type</td><td>Title</td><td>Description</td><td>Due date</td><td style="width: 150px;"></td></tr>';
    $this->outputProjects(' and projects.complete = 0');
    $this->outputTasks('',' and tasks.complete = 0', ' item_user_map.user_id='.$_SESSION['user']['ID']);
    function cmp($a, $b){
      if (strtotime($a[4]) == strtotime($b[4])) {
        return 0;
      }
        return (strtotime($a[4]) < strtotime($b[4])) ? -1 : 1;
    }
   $a = $this->ItemsArray;
   if($a){
 
     usort($a, "cmp");
     foreach ($a as $p){
      $englishDate ='';
      $p[4]<=date('Y-m-d',time())?$class[0]='overdue':$class[0]='';
      $p[0]=='Task'?$type=1:$type=0;
      $this->returnView($p[1],$type)?$class[1]=' seen':$class[1]=' unseen';
      $this->complete=='0'?$class[2]=' incomplete':$class[2]=' complete';
      $calsses = 'class="'.$class[0].' '.$class[1].' '.$class[2].'"';
      $completeButton = $wp->completedButton($p[1],$p->complete,$type);
      if ($p[0]=='Task'){
       $viewButton=$wp->button('view','/view-tasks/task/'.$p[1]);
      }else{
      $viewButton = $wp->button('view','/view-project/'.$p[1]);
      }

      $englishDate = date("d/m/Y", strtotime($p[4]));
      $englishDate=='30/11/-0001'?$englishDate='-':null;
      $p[0]!='Task'? $title = $p[1].'<br>'.$p[2]:$title=$p[2];

      if($p[4] < date('Y-m-d',time()) && date('W',strtotime($p[4])) <= date('W',time()) || date('Y',strtotime($p[4])) < date('Y',time())  ){//overdue
       $group[0]['OVERDUE'][]='<tr '.$calsses.'><td>'.$p[0].'</td><td>'.$title.'</td><td><p>'.$p[3].'</p></td><td style="color:red;">'.$englishDate.'</td><td>'.$viewButton.'</td><td style="width: 150px;">'.$completeButton.'</td></tr>';
      
      }elseif(date('Y-m-d',strtotime($p[4])) == date('Y-m-d',time())){//Today
       $group[1]['TODAY'][]='<tr '.$calsses.'><td>'.$p[0].'</td><td>'.$title.'</td><td><p>'.$p[3].'</p></td><td>'.$englishDate.'</td><td>'.$viewButton.'</td><td style="width: 150px;">'.$completeButton.'</td></tr>';

      }elseif(date('W',strtotime($p[4])) == date('W',time())){//week
       $group[2]['WEEK'][]='<tr '.$calsses.' ><td>'.$p[0].'</td><td>'.$title.'</td><td><p>'.$p[3].'</p></td><td>'.$englishDate.'</td><td>'.$viewButton.'</td><td style="width: 150px;">'.$completeButton.'</td></tr>';

      }elseif($p[4] > date('Y-m-d',time())){//future
       $group[3]['FUTURE'][]='<tr '.$calsses.'><td>'.$p[0].'</td><td>'.$title.'</td><td><p>'.$p[3].'</p></td><td>'.$englishDate.'</td><td>'.$viewButton.'</td><td style="width: 150px;">'.$completeButton.'</td></tr>';
     
     }elseif($englishDate=='-'){//no due date
       $group[4]['NO DUE DATE'][]='<tr '.$calsses.' ><td>'.$p[0].'</td><td>'.$title.'</td><td><p>'.$p[3].'</p></td><td>'.$englishDate.'</td><td>'.$viewButton.'</td><td style="width: 150px;">'.$completeButton.'</td></tr>';
     }
    }
   }
   if($group){
    ksort($group);
    foreach($group as $g){
      foreach ($g as $key => $value){
       $shed.='<tr><td colspan="6" style="background:#eee;">'.$key.'</td></tr>';
       foreach ($value as $row){
        $shed.=$row;
       }
      }
    }
  }
   
    $shed .='</table>';
   return $shed;
   }
   
 public function outputProjectRequests(){
  $user= new kingsUser();
  $wp =  new kingsWebpage();
  $sql='select * from project_requests order by created desc';
         $results = mysql_query( $sql );
         $projects.='<table class="list"><tr><td>Client</td><td>Brief</td><td>Requested Date</td><td>Due date</td><td>Type</td><td></td><td style="width: 150px;"></td></tr>';
            while( $p = mysql_fetch_object( $results ) ) {
             if($this->isProjectyet($p->id)){
            // $viewButton = $wp->button('Go to  Project',$this->isProjectyet($p->id));
             }else{
              $viewButton = $wp->button('view Request','/new/project/'.$p->id);
             
             !$this->returnView($p->id,4)?$this->recordView($p->id,4):null;
             $client = $user->getclientFromUser($p->created_by);
             $type = $this->deserial($p->project_area);
             $englishDate = date("d/m/Y", strtotime($p->due_date));
             $brief=stripcslashes($p->title).'<br>'.stripcslashes($p->overview).'<br>'.stripcslashes($p->business_case).'<br>'.stripcslashes($p->tech_spec);
                $projects .= '<tr id="req'.$p->id.'"><td>'.$client[0]->name.'</td><td>'.$brief.'</td><td><p>'.date("d/m/Y", strtotime($p->created)).'</p></td><td>'.$englishDate.'</td><td>'.$type.'</td><td>'.$viewButton.'</td><td><div onclick="deleteRequest('.$p->id.');" class="button">+Delete</div></td></tr>';
            }
            }
        $projects .='</table>';
        return $projects;
 }
 
 public function isProjectyet($rid){
   $sql='select * from requeste_projects_map where request_id='.$rid;
   $results = mysql_query( $sql );
   while( $p = mysql_fetch_object( $results ) ) {
    $link = '/view-project/'.$p->project_id;
   }
   return $link;
 }
 
  public function doRequestExl(){
    $sql='select * from project_requests order by due_date asc';
    $results = mysql_query( $sql );
    while( $p = mysql_fetch_object( $results ) ) {
        $sql2='select * from requeste_projects_map where request_id='.$p->id;
        $results2 = mysql_query( $sql2 );
        if (!mysql_num_rows($results2)){
            $i=$i+1;
            $this->returnView($p->id,4)?$a=$a+1:null;
            }
        }
       
        
    $i==$a?$return=true:$return==false;
    return $return;
 }
 
 public function deserial($array='') {
  $normalArray = unserialize($array);
  if ($normalArray){
   foreach ($normalArray as $item){
    $returnString .= $item.', ';
   }
  }
  return $returnString;
 }
 

 
 public function outputProjects($where='',$user='',$all='',$order=0,$limit=FALSE,$join='') {
   $format = new kingsFormatter();
   $USER = new kingsUser();
   $wp = new kingsWebpage();
   $user==''?$user = ' item_user_map.user_id = '.$_SESSION["user"]["ID"]:null;
   if ($where){
      
      if($order){

        $order =$order;
      }else{
        
        if($_GET['search']){
                  $order = ' order by id asc';         
              }else{
                   $order = ' order by duedate asc';
              }
      }
        
        $sqlcount = 'select count( distinct(projects.id))from projects 
            left join item_user_map on projects.id = item_user_map.item_id
            left join item_client_map on projects.id = item_client_map.item_id
            left join catagory_keywords_map on projects.id = catagory_keywords_map.item_id
            where item_user_map.type="0" and '.$user.' '.$where.'
            ';
            
                $total = mysql_query( $sqlcount );
                $total2 = mysql_fetch_array($total);
                $totalResults=$total2[0];
                $pages = ceil($totalResults/50);
                $i=0;

        while($i < $pages){
            if($i>0){
                $pageText.='<li class="pager"><a href="#" class="pages" id="pages'.$i.'" page="'.$i.'" onClick="doPage('.$i.')">'.$i.'</a></li>';
            }
            $i++;
        }
            
        $sql='select distinct(projects.id), projects.* from projects 
            left join item_user_map on projects.id = item_user_map.item_id
            left join item_client_map on projects.id = item_client_map.item_id
            left join catagory_keywords_map on projects.id = catagory_keywords_map.item_id
            where item_user_map.type="0" and '.$user.' '.$where.'
            '.$order.$limit['sql'];
            
   }else{
    
    $sqlcount = 'select count( distinct(projects.id)) from projects 
            left join item_user_map on projects.id = item_user_map.item_id
            where item_user_map.type="0" and projects.complete=0
           ';
    $total = mysql_query( $sqlcount );
    $total2 = mysql_fetch_array($total);
    $totalResults=$total2[0];
    $pages = round($totalResults/50);
    $i=0;
        while($i < $pages){
            if($i>0){
                $pageText.='<li class="pager"><a href="#" class="pages" id="pages'.$i.'" page="'.$i.'" onClick="doPage('.$i.')">'.$i.'</a></li>';
            }
        $i++;
        }
        
         $sql='select distinct(projects.id), projects.* from projects 
            left join item_user_map on projects.id = item_user_map.item_id
            left join item_client_map on projects.id = item_client_map.item_id
            where item_user_map.type="0" and projects.complete=0
            order by id asc '.$limit['sql'];
            
            
   }
   //if(!$limit['currentPage']){$limit['currentPage']=1;}

   if(ceil($totalResults) < 50){$pageTotal=$totalResults;}else{$pageTotal=(round($limit['currentPage']*50) + 50); }
   
    $pageDiv='<div class="pagdiv"><p style="padding:10px;">Showing '.$limit['currentPage']*50 .' - '.$pageTotal.' of '.$totalResults.'</p><ul>'.$pageText.'</ul>';

         $results = mysql_query( $sql );
         $projects.='<table id="sorter" class="list tablesorter">
         <thead><tr><th>Number</th><th>Title</th><th>Description</th><th>Client</th><th>Members</th><th class="{sorter: \'shortDate\'}">Due date</th><td></td></tr></thead>
         <tbody>';
            while( $p = mysql_fetch_object( $results ) ) {
             $this->returnView($p->id,0,$_SESSION["user"]["ID"])?$class=' class="seen"':$class=' class="unseen"';
             
             $client = $USER->getClientFromProject($p->id);
             $clientContact = $client[0]->name;
             $member = $USER->returnUserfromItem($p->id);
             $members='';
             foreach($member as $name){
              $members.=$name->innitial.', ';
             }
             $members = substr($members, 0, -2);
                $completeButton = $wp->completedButton($p->id,$p->complete,'0');
                $viewButton = $wp->button('view','/view-project/'.$p->id);
                
                $p->duedate!='0000-00-00'?$englishDate = date("d/m/Y", strtotime($p->duedate)):$englishDate='-';
                $projects .= '<tr '.$class.' ><td>'.$p->id.'</td><td>'.$p->title.'</td><td><p>'.$format->afterSummary($p->description).'</p></td><td>'.$clientContact.'</td><td>'.$members.'</td><td>'.$englishDate.'</td><td>'.$viewButton.'</td><td style="width: 150px;">'.$completeButton.'</td></tr>';
                $this->ItemsArray[]=array("Project",$p->id,$p->title,$format->afterSummary($p->description),$p->duedate);
            }
           
       
        $projects .=$pageDiv.'</tbody></table>'.$pageDiv;
        return $projects;
    }
    
  public function getProject($id){
   $USER = new kingsUser();
    $sql='select  projects.*
           from projects where projects.id ='.$id;
         $results = mysql_query( $sql );
          while( $p = mysql_fetch_object( $results ) ) {
              $project = $p;
            }
            $project->words=$this->outputKeywords($id);
            $project->client=$USER->getClientFromProject($id);
            $project->users=$USER->returnUserfromItem($id);
            $project->tasks=$this->getTaskFromProjects($id);
            $project->updates=$this->getUpdatesFromProjects($id);
            $project->alerts=$this->getAlerts($id);
            $project->files=$this->getProjectFiles($id);
        return $project;
    }
    
   public function getProjectNames(){
   $USER = new kingsUser();
    $sql='select  * from projects order by id desc';
     $project[0] ='None';
         $results = mysql_query( $sql );
          while( $p = mysql_fetch_object( $results ) ) {
              $project[$p->id] = '#'.$p->id.'-'.$p->title;
            }
        return $project;
    }
    
    
   public function getProjectrequest($id){
   $USER = new kingsUser();
    $sql='select  * from project_requests where id ='.$id;
         $results = mysql_query( $sql );
          while( $p = mysql_fetch_object( $results ) ) {
             // $p->client=$USER->getclientFromUser($p->created_by);
             $projectfocus = unserialize($p->product_focus);
             $area = unserialize($p->location_focus);
             
             if($projectfocus){
                foreach ($projectfocus as $pf){
                   $pojectf .="\n- ".$pf;
                }
             }
             if($area){
                foreach ($area as $af){
                   $areaf .="\n- ".$af;
                }
            } 
              $p->description= "Overview\n".$p->overview."\n\nBusiness case \n".$p->business_case."\n\nTech spec \n".$p->tech_spec;
              $p->description.= "\n\nProject focus:".$pojectf."\n\nLocation focus:".$areaf;
              $p->duedate=$p-due_date;
              $p->requestBy=$p->created_by;
              $project = $p;
            }
            
        return $project;
    }
    
   public function getTaskFromProjects($pid){
    $USER = new kingsUser();
    $sql='select * from tasks where project_id ='.$pid;
         $results = mysql_query( $sql );
          while( $p = mysql_fetch_object( $results ) ) {
              $p->users=$USER->returnUserfromItem($p->id);
              $tasks[] = $p;
              
            }
        return $tasks;
    }
        
    public function getTaskFromTaskID($tid){
      $USER = new kingsUser();
    $sql='select * from tasks where id ='.$tid;
         $results = mysql_query( $sql );
          while( $p = mysql_fetch_object( $results ) ) {
                  $p->users=$USER->returnUserfromItem($p->id,1);
                  $tasks= $p;
            }
        return $tasks;
    }
    
    public function getUpdatesFromProjects($pid){
    $USER = new kingsUser();
    $sql='select * from updates where project_id ='.$pid. ' and parent_update_id = 0';
    $results = mysql_query( $sql );
    while( $p = mysql_fetch_object( $results ) ) {
      $sql2='select * from updates where parent_update_id='.$p->id;
      $results2 = mysql_query( $sql2 );
      while( $x = mysql_fetch_object( $results2 ) ) {
         $x->users=$USER->returnUser($x->user_id);
         $children[] = $x;
      }
      $p->users=$USER->returnUser($p->user_id);
      $p->children=$children;
      unset($children);
      $updates[] = $p;       
    }
        return $updates;
    }
    
    public function getUpdate($id){
    $sql='select * from updates where id ='.$id;
         $results = mysql_query( $sql );
          while( $p = mysql_fetch_object( $results ) ) {
              $updates = $p;
            }
        return $updates;
    }
    
    
    public function getAlerts($pid='',$type=0){
      if ($pid!=''){
      $and = ' and alerts_item_map.type='.$type;
      $sql='select * from alerts left join alerts_item_map on alerts.id = alerts_item_map.alert_id where alerts_item_map.item_id ='.$pid.$and ;      
      }else{
      $sql='select * from alerts';
      }
        $results = mysql_query( $sql );
          while( $x = mysql_fetch_object( $results ) ) {
            $alert[] = $x;
          }
          return $alert;
    }
    
    public function getProjectFiles($pid=''){

      $sql='select * from files where pid='.$pid;      
      
        $results = mysql_query( $sql );
          while( $x = mysql_fetch_object( $results ) ) {
            $files.='<li id="file'.$x->id.'"><a target="_blank" href="/uploads/'.$x->URL.'">'.$x->name.'</a> | <span class="link" onclick="deleteFile('.$x->id.')">delete</span></li>';
          }
          return $files;
    }
    
    public function getPFromT($tid){
     $sql='select title from projects where id ='.$tid;
     $results = mysql_query( $sql );
      while( $p = mysql_fetch_object( $results ) ) {
             $title = $p->title;
            }
      return $title;
    }
    
    public function getmembersfromTask($tid){
     $sql='select distinct(users.ID), name, innitial from users
     left join item_user_map on users.id = item_user_map.user_id
     where item_user_map.item_id='.$tid.' and (users.client_id = 0 or users.client_id = 7)'; 
     $results = mysql_query( $sql );
     if ($results){

      while( $p = mysql_fetch_object( $results ) ) {
             $name .= $p->innitial.', ';
            }
     }
      return substr($name, 0, -1);
    }
    
 
  public function outputTasks($tid="", $where='',$user='',$order="") {
   $wp= new kingsWebpage();
   $format = new kingsFormatter();
   $user==''?$user=$_SESSION["user"]["ID"]:null;
    if($where!=''){
        
     $sql='select distinct(tasks.id) as tid, tasks.* from tasks 
             left join item_user_map on tasks.id = item_user_map.item_id
             where item_user_map.type="1"  and '.$user.' '.$where.'
             order by duedate asc';

          $results = mysql_query( $sql );
          $items.='<table id="sorter" class="list tablesorter"><thead> <tr><th>Title</th><th>Description</th><th>Project</th><th>Task members</th><th>Due date</th></tr></thead><tbody>';
        if ($results){
             while( $p = mysql_fetch_object( $results ) ) {
              $completeButton = $wp->completedButton($p->id,$p->complete,'1');
              $viewButton = $wp->button('view','/view-tasks/task/'.$p->id);
              $p->duedate!='0000-00-00'?$englishDate = date("d/m/Y", strtotime($p->duedate)):$englishDate='-';
              
              	if ($p>project_id!='' && $p->project_id!='0'){
				$taskpid ='#'.$p->project_id.'&#151;'.$this->getPFromT($p->project_id);
			}else{
				$taskpid='-';
			}
                 $items .= '<tr><td>'.$p->title.'</td><td><p>'.$format->afterSummary($p->description).'</p></td><td>'.$taskpid.'</td>
                 <td>'.$this->getmembersfromTask($p->tid).'</td><td>'.$englishDate.'</td><td>'.$viewButton.'</td><td style="width: 150px;">'.$completeButton.'</td></tr>';
                 $this->ItemsArray[]=array("Task",$p->id,$p->title,$format->afterSummary($p->description),$p->duedate );
             }
        }
         $items .='</tbody></table>';
    }else{
     
     if ($tid==""){
          $sql='select distinct(tasks.id) as tid, tasks.* from tasks 
             left join item_user_map on tasks.id = item_user_map.item_id
             where  item_user_map.type="1" and tasks.complete=0
             order by duedate asc';
   
          $results = mysql_query( $sql );
          $items.='<table id="sorter" class="list tablesorter"><thead> <tr><th>Title</th><th>Description</th><th width="100px;">Project</th><th>Task members</th><th>Due date</th><td></td></tr></thead><tbody>';
             while( $p = mysql_fetch_object( $results ) ) {
              $completeButton = $wp->completedButton($p->id,$p->complete,'1');
              $viewButton = $wp->button('view','/view-tasks/task/'.$p->id);
              $p->duedate!='0000-00-00'?$englishDate = date("d/m/Y", strtotime($p->duedate)):$englishDate='-';
             
              $this->getPFromT($p->project_id)!=''?$Project_id_disp ='#'.$p->project_id.'&#151;'.$this->getPFromT($p->project_id):$Project_id_disp='&#151;';
              
                $items .= '<tr><td>'.$p->title.'</td><td><p>'.$format->afterSummary($p->description).'</p></td><td>'.$Project_id_disp.'</td>
                 <td>'.$this->getmembersfromTask($p->tid).'</td><td>'.$englishDate.'</td><td>'.$viewButton.'</td><td style="width: 150px;">'.$completeButton.'</td></tr>';
                 $this->ItemsArray[]=array("Task",$p->id,$p->title,$format->afterSummary($p->description),$p->duedate);
             }
         $items .='</tbody></table>';
     }else{
           $sql='select * from tasks 
             left join item_user_map on tasks.id = item_user_map.item_id
             where item_user_map.type="1" and tasks.id = '.$tid.'
             order by duedate asc';
             //   print $sql;
             
          $results = mysql_query( $sql );
          while( $p = mysql_fetch_object( $results ) ) {
                 $items =$p;
             }
     }
    }
        return $items;
    }
    
    public function outputKeywords($pid='',$all=0) {
         $all?$whereSort='':$whereSort=' where sort<99 ';
         $pid==''?$sql='select * from Keywords '.$whereSort.' order by name ASC':$sql='select * from Keywords left join catagory_keywords_map on Keywords.id = catagory_keywords_map.Keyword_id where catagory_keywords_map.item_id ='.$pid;
         $results = mysql_query( $sql );
         if ($results){
            while( $p = mysql_fetch_object( $results ) ) {
              $words[] = $p;
            }
         }
        return $words;
    }
    
    public function recordView($id='', $type='0', $user='') {
        $user==''?$user=$_SESSION['user']['ID']:null;
             $sql='INSERT INTO item_user_views VALUES ("",'.$id.','.$user.','.$type.')';
             $results = mysql_query( $sql );
    }
    
    public function returnView($id='', $type=0, $user=''){
      $user==''? $user= $_SESSION['user']['ID']:null;
      $return = false;
      $sql='select * from item_user_views where item_id='.$id.' and type='.$type.' and user_id='.$user;
      $results = mysql_query( $sql );
       if ($results){
            while( $p = mysql_fetch_object( $results ) ) {
              $return = true;
            }
        }
      return $return;
    }
    
      public function projectAreaOptions(){
      
      $area[]='Advertising';
      $area[]='Clothing';
      $area[]='Design';
      $area[]='Display';
      $area[]='Document';
      $area[]='Photography';
      $area[]='Presentation';
      $area[]='Promotion';
      $area[]='Stationery';
      $area[]='Video';
      $area[]='Web';
      $area[]='Other';
      return $area;
    }
      public function projectFocusOptions(){
      $area[]='ALL';
      $area[]='English Courses';
      $area[]='UK Academic';
      $area[]='USA Academic';
      $area[]='Summer courses';
      $area[]='Adult';
      $area[]='Junior';
      $area[]='Other';
      return $area;
    }
    
    public function projectLocationFocusOptions(){
      $area[]='ALL';
      $area[]='Boston';
      $area[]='Los Angeles';
      $area[]='Bournemouth';
      $area[]='london';
      $area[]='Oxford';
      $area[]='Summer Locations';
      $area[]='Other';
      return $area;
    }  
    
}        
?>
