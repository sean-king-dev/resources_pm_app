<?php

// Changefirst Webpage Class

class kingsWebpage extends DynamicWebpage {


    public function setSiteTitle( $title="" ) {
        $title == "" ? $title = $this->_getSiteTitle() : NULL;
        parent::setSiteTitle( $title );
    }
    
    public function outputheader() {
        $p = $this->_getPageContent();
        return $p->parentID;
    }
    
    public function outputContent() {
        $p = $this->_getPageContent();
        switch($p->page_typeID) {
            case 3:// projects
            //home page
            switch($_GET["p"]){
              case 'view-project':
               $leftCol .="<div id=\"fullCol\">". $p->content.$this->doViewProject().'<div class="clear"></div>	</div>';
               break;
              case 'view-keywords':
               $leftCol .="<div id=\"fullCol\">". $p->content.$this->doProjectKeyWords().'<div class="clear"></div>	</div>';
               break;
              case 'view-tasks':
                if ($_GET["s"]=='task'){
					 $leftCol .="<div id=\"fullCol\">". $p->content.$this->doProjectViewEidTask(1).'<div class="clear"></div>	</div>';
                }else{
					if ($_GET["t"]=='new'){
					  $leftCol .="<div id=\"fullCol\">". $p->content.$this->doProjectViewEidTask(1).'<div class="clear"></div>	</div>';
					}else{
					  $leftCol .="<div id=\"fullCol\">". $p->content.$this->button('New Task','/view-tasks/'.$_GET['s'].'/new').$this->doProjectTask().'<div class="clear"></div>	</div>';
					}
                }
               break;
               case 'view-updates':
				if ($_GET["s"]=='update'){// this is a view/edit
					$leftCol .="<div id=\"fullCol\">". $p->content.$this->doProjectViewEidUpdate(0).'</div>';
                }else{
					if ($_GET["t"]=='new'){
						$leftCol .="<div id=\"fullCol\">". $p->content.$this->doProjectViewEidUpdate(1).'</div>';
					}else{
						$leftCol .="<div id=\"fullCol\">". $p->content.$this->button('New Update','/view-updates/'.$_GET['s'].'/new').$this->doProjectUpdates().'</div>';
					}
				}
			  break;
              case 'view-task':
               $leftCol .="<div id=\"fullCol\">". $p->content.$this->doProjectViewEidTask(1).'</div>';
               break;
              default:
                $leftCol .="<div id=\"fullCol\">". $p->content.$this->button('New Project','/new').$this->doProjects().'</div>';
              break;
            }
	    break;
	    case 4:// Tasks
            //home page
            $leftCol .="<div id=\"fullCol\">". $p->content.$this->button('New Task','/new/task').$this->doTasks().'</div>';
	    break;
	    case 5:// New
            //home page
			$leftCol .="<div id=\"fullCol\">". $p->content.$this->doNew().'</div>';
	    break;
		case 6:// CLIENT
            switch($_GET["p"]){
              case 'project-request-form':
               $leftCol .="<div id=\"fullCol\">". $p->content.$this->doProjectRequest().'</div>';
               break;
			case 'stationery-request-form':
               $leftCol .="<div id=\"fullCol\">". $p->content.$this->doStationaryRequest().'</div>';
               break;
			case 'project-requests':
					$leftCol .="<div id=\"fullCol\">". $p->content.$this->listProjectRequests().'</div>';
               break;
			default:
				$leftCol .="<div id=\"fullCol\">". $p->content.$this->doNew().'</div>';
			break;
			}	
	    break;
		
		case 7://profiles
			$leftCol .="<div id=\"fullCol\">". $p->content.$this->doProfile().'</div>';
		break;
	    
		case 8://Products
		     if($_GET["s"]){
			$leftCol .="<div id=\"fullCol\">". $p->content.$this->doEditProduct($_GET["s"]).'</div>';
		     }else{
			$leftCol .="<div id=\"fullCol\">". $p->content.$this->doProducts().'</div>';
		     }
		break;
	    
	    case 9://Products
		     if($_GET["s"]){
			$leftCol .="<div id=\"fullCol\">". $p->content.$this->doEditSupplier($_GET["s"]).'</div>';
		     }else{
			$leftCol .="<div id=\"fullCol\">". $p->content.$this->doSuppliers().'</div>';
		     }
		break;
		
		
	    default:
            //home page
	    $leftCol .="<div id=\"fullCol\">".$this->doSched().'</div>';
	    break;
        }
        
        switch($_GET["p"]){
            case "logout":
		$u = new kingsUser();
		$u->processLogout();
            break;
        }
        $leftCol.=$homelink.'</div>';

        $this->pageContent .=$leftCol.$rightCol;            
        return $this->pageContent;
    }
    
    public function doSuppliers(){
	$U = new kingsUser();
	$f = new Form();
	$P = new kingsProducts();
	is_numeric($_GET['s'])?	 $user = $U->returnUser($_GET['s']) :$user = $U->returnUser($_SESSION['user']['ID']);
	$sessionUser = $user;
	$display="display:none;";
	$display=" ";
	
	
	$button .='<div class="button" style="float: right;"><a href="/suppliers">+Suppliers</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/suppliers/new">+Add Supplier</a></div>';
	
	$button .='<div class="button" style="float: right;"><a href="/products">+Products</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/products/new">+Add Product</a></div>';
	
	$button .='<div class="button" style="float: right;"><a href="/profile">+Users</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/profile/new">+Add user</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/profile/new-client">+Add client</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/profile/personal">+Edit my profile</a></div>';
	$checked='Y';
	
	$form .=$button.'<h1>Suppliers</h1>';
	$form.='<table><thead><tr class="header"><td><h4>name</h4></td><td>Email</td></tr></thead><tbody>';
	$allsups = $P->getSuppliers();
	foreach($allsups as $sup){
	    $form.='<tr id="sup_'.$sup->id.'"><td>'.$sup->Name.'</td><td>'.$sup->email.'</td><td><a target="_self" href="/suppliers/'.$sup->id.'"><div class="button">+edit</div></a></td><td>'.$this->button('Delete','','deleteSupplier('.$sup->id.')').'</td></tr>';
	}
	$form.='</tbody></table>';
	 return $form;
    }
    
    public function doEditSupplier($sid){
	$f = new Form();
	$P = new kingsProducts();
	
	$supplier = $P->getSuppler($sid);

	$form .= $f->formStart('','','suppliers');
	$form .= '<table id="suppliers">';
	$form .= '<tr><td><label> Name  </label >'.$f->formText('name','',$supplier[0]->Name,'','required').'</td>';
	$form .= '<td><label> Email </label >'.$f->formText('email','',$supplier[0]->email,'','required').'</td>';
	$form .= '</tr>';

	$form .='</table>';
	$form.= $f->formHidden('supplier_id', $supplier[0]->id);
	$form.= $this->button2('+ Save Supplier','submitSupplier');
	
	$form .= $f->formEnd('','','new');
	return $form;
    }
    
    public function doEditProduct($pid){
	$f = new Form();
	$P = new kingsProducts();
	$quantities = $P->getQuantities();
	$product = $P->getSingleProd($pid);
	$suppliers = $P->getSuppliers();
	foreach($quantities as $q){
	    $quants[$q->id]=$q->quantity;
	    if($product->quantitites){
		foreach($product->quantitites as $quan){
		    $currentQuantities[]=$quan->quantity_id;
		}
	    }
	    $checked='';
	    if($currentQuantities){
		if(in_array($q->id,$currentQuantities)){
		    $checked='Y';
		}
	    }
	       $checks.=$q->quantity.' '.$f->formCheckbox('quantities','',$q->id,$product->default_quantity_id,$checked);
	    
	}
	$supplyArray[0]='Select a Supplier';
	foreach($suppliers as $sup){
	    $supplyArray[$sup->id]=$sup->Name;
	}

	$form .= $f->formStart('','','product');
	$form .= '<table id="product">';
	$form .= $location.'<tr><td><label> Name  </label >'.$f->formText('name','',$product->name,'','required').'</td>';
	$form .= '<td><label> Default Quantity  </label >'.$f->formSelect('default_quantity', $quants,'',$product->default_quantity_id).'</td>';
	$form .= '<td><label> Optional Quantities</label >'.$checks.'</td>';
	'</tr>';
	
	$form .= '<tr id="prod_'.$product->id.'"><td><label> UK Supplier</label >'.$f->formSelect('UK', $supplyArray,'',$product->UK).'</td>';
	$form .= '<td><label> USA Supplier</label >'.$f->formSelect('USA', $supplyArray,'',$product->USA).'</td>';
	$form .= '<td><label> China Supplier</label >'.$f->formSelect('China', $supplyArray,'',$product->China).'</td>';
	$form .= '</tr>';
	 
	$form .='</table>';
	$form.= $f->formHidden('product_id', $product->id);
	$form.= $this->button2('+ Save Product','submitProduct');
	
	$form .= $f->formEnd('','','new');
	return $form;
    }
    
    public function doProducts(){
	$U = new kingsUser();
	$f = new Form();
	$P = new kingsProducts();
	is_numeric($_GET['s'])?	 $user = $U->returnUser($_GET['s']) :$user = $U->returnUser($_SESSION['user']['ID']);
	$sessionUser = $user;
	$display="display:none;";
	$display=" ";
	
	
	$button .='<div class="button" style="float: right;"><a href="/suppliers">+Suppliers</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/suppliers/new">+Add Supplier</a></div>';
	
	$button .='<div class="button" style="float: right;"><a href="/products">+Products</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/products/new">+Add Product</a></div>';
	
	$button .='<div class="button" style="float: right;"><a href="/profile">+Users</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/profile/new">+Add user</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/profile/new-client">+Add client</a></div>';
	$button .='<div class="button" style="float: right;"><a href="/profile/personal">+Edit my profile</a></div>';
	$checked='Y';
	
	$form .=$button.'<h1>Products</h1>';
	$form.='<table><thead><tr class="header"><td><h4>Product</h4></td><td>UK</td><td>USA</td><td>China</td></tr></thead><tbody>';
	$allProds = $P->getAllProducts();
	foreach($allProds as $prod){
	    $form.='<tr id="prod_'.$prod->id.'"><td>'.$prod->name.'</td><td>'.$P->getSupplName($prod->UK).'</td><td>'.$P->getSupplName($prod->USA).'</td><td>'.$P->getSupplName($prod->China).'</td><td><a target="_self" href="/products/'.$prod->id.'"><div class="button">+edit</div></a></td><td>'.$this->button('Delete','','deleteProduct('.$prod->id.')').'</td></tr>';
	}
	$form.='</tbody></table>';
	 return $form;
    }
    
    
	
	public function doProfile(){
		$U = new kingsUser();
		$f = new Form();
		is_numeric($_GET['s'])?	 $user = $U->returnUser($_GET['s']) :$user = $U->returnUser($_SESSION['user']['ID']);
		$sessionUser = $user;
		$display="display:none;";

		if ($user[0]->user_type!=0 && $user[0]->client_id==0){// ADMINS
				$display=" ";
				
				$button .='<div class="button" style="float: right;"><a href="/suppliers">+Suppliers</a></div>';
			        $button .='<div class="button" style="float: right;"><a href="/suppliers/new">+Add Supplier</a></div>';
				
				$button .='<div class="button" style="float: right;"><a href="/products">+Products</a></div>';
				$button .='<div class="button" style="float: right;"><a href="/products/new">+Add Product</a></div>';
				
				$button .='<div class="button" style="float: right;"><a href="/profile/inactive">+Inactive users</a></div>';
				$button .='<div class="button" style="float: right;"><a href="/profile">+Users</a></div>';
				$button .='<div class="button" style="float: right;"><a href="/profile/new">+Add user</a></div>';
				$button .='<div class="button" style="float: right;"><a href="/profile/new-client">+Add client</a></div>';
				$button .='<div class="button" style="float: right;"><a href="/profile/personal">+Edit my profile</a></div>';
				$checked='Y';
				
		  }

		if($_SESSION['user']['type']==1){
		    $admin .= '<br><br><label>Make Admin</label>'.$f->formCheckbox('admin',$user->innitial,1,'admin',$checked,'block',$edit);
		}
		  

		if (!$_GET['s']  && $_GET['p']!='profile-client'){
			$users = $U->returnUser();
			$form.=$button.'<label>USERS</label><table>';
			foreach($users as $user){
			    if($sessionUser[0]->user_type==1 || $user->ID == $_SESSION['user']['ID']){
				$form.='<tr id="'.$user->ID.'"><td>'.$user->ID.'</td><td>'.$user->name.'</td><td>'.$user->email.'</td><td><a target="_self" href="/profile/'.$user->ID.'"><div class="button">+edit</div></a></td><td>'.$this->button('Delete','','deleteUser('.$user->ID.')').'</td></tr>';
			    }
			}
			$form.='</table>';
			
			$users = $U->outputClients();
			$form.='<label>CLIENTS</label><table>';
			foreach($users as $user){
			    if($sessionUser[0]->user_type==1 || $user->ID == $_SESSION['user']['ID']){
				$form.='<tr id="'.$user->ID.'"><td>'.$user->ID.'</td><td>'.$user->name.'</td><td>'.$user->email.'</td><td><a target="_self" href="/profile/'.$user->ID.'"><div class="button">+edit</div></a></td><td>'.$this->button('Delete','','deleteUser('.$user->ID.')').'</td></tr>';
			    }
			}
			$form.='</table>';
			
		}elseif ($_GET['s']=='inactive') {
			$users = $U->returnInactiveUser();
			$form.=$button.'<label>INACTIVE USERS</label><table>';
			foreach($users as $user){
			    if($sessionUser[0]->user_type==1 || $user->ID == $_SESSION['user']['ID']){
				$form.='<tr id="'.$user->ID.'"><td>'.$user->ID.'</td><td>'.$user->name.'</td><td>'.$user->email.'</td><td><a target="_self" href="/profile/'.$user->ID.'"><div class="button">+edit</div></a></td><td>'.$this->button('Delete','','deleteUser('.$user->ID.')').'</td></tr>';
			    }
			}
			$form.='</table>';
			
			$users = $U->outputInactiveClients();
			$form.='<label>INACTIVE CLIENTS</label><table>';
			foreach($users as $user){
			    if($sessionUser[0]->user_type==1 || $user->ID == $_SESSION['user']['ID']){
				$form.='<tr id="'.$user->ID.'"><td>'.$user->ID.'</td><td>'.$user->name.'</td><td>'.$user->email.'</td><td><a target="_self" href="/profile/'.$user->ID.'"><div class="button">+edit</div></a></td><td>'.$this->button('Delete','','deleteUser('.$user->ID.')').'</td></tr>';
			    }
			}
			$form.='</table>';
		}else{
		
	  $form .=$button.'<h1>PROFILE</h1>';
		if ($_GET['s']=='new'){
			unset($client[0]);
			unset($user[0]);
		}
	  if ($user[0]->client_id!=0 ){
	    
		$client = $U->getClient($user[0]->client_id);
		
		$results = mysql_query('select * from clients');
		while ($row = mysql_fetch_object($results)){
		    $locations[$row->id]=$row->location;
		    }
		    if( $_SESSION['user']['type']==1){
			$change='
			<style>
			    .buttonSmall{font-size:10px;}
			    .buttonSmall:hover{cursor:pointer; background:yellow;}
			</style>
			<span class="buttonSmall" onclick="$(\'#locaitonhidden\').fadeToggle();"> [change]</span>';
		    }
		$clientbits .='<tr><td><label>Billing Address:</label>';
		$clientbits.='<div id="bil">'.$client[0]->billing_address.'</div></td>';
		$clientbits .='<td><label>Delivery Address:</label>';
		$clientbits.='<div id="del">'.$client[0]->delivery_address.'</div></td></tr>';
		$location.='<tr><td colspan="2"><h2>'.$client[0]->location.$change.'</h2>
		<div id="locaitonhidden" style="display:none;">'.$f->formSelect('locations', $locations,'',$client[0]->id).'</div></td></tr>';
		
	  }else if ($_GET['s']=='new-client'){
			unset($client[0]);
			unset($user[0]);
			$cleintsSelect = '<tr><td colspan="2"><label>Select Client Location</label>'.$f->formSelect('client',$U->getAllClients()).'</td></tr>';

		}else{
		
		$member .='<tr><td><label>Initial:</label>';
		$member.=$f->formText('innitial','',$user[0]->innitial,'','required').$admin.'</td>';
		$member .='<td><label>Expertise:</label><p>Please slect from the list below</p>';
		//$member.=$f->formTextArea('expertise','',$user[0]->expertise,'','required');
		$expertiseArray = explode(',',str_replace(' ','',strtolower($user[0]->expertise)));
		$Items = new kingsItems(); 
		$area = $Items->projectAreaOptions();
			foreach($area as $a){
				if(in_array(strtolower($a),$expertiseArray)){
					$checked="Y";
				}else{
					$checked="N";
				}
			  $projectArea.=$f->formCheckbox('expertise',$a,$a,'expertise',$checked,'block required').'<br>';
			}
      $member .=$projectArea.'</td></tr>';
		
		
		
		}
		
		
	  $form .='<div id="loading" style="display:none;"></div>';
      $form .= $f->formStart('','','profile');
      $form .= $f->formFieldsetStart();
      $form .= '<table id="prod">';
	  $form .=$cleintsSelect;
	  $form .= $location.'<tr><td><label> Name  </label >'.$f->formText('name','',$user[0]->name,'','required').'</td>';
	  $form .= '<td><label> Email  </label >'.$f->formText('email', '',$user[0]->email,'','required email').'</td></tr>';
		$form .='<tr><td><label><input type="checkbox" name="active" value="1"';
		if($user[0]->active) $form.=' checked';
		$form.='> Active?</label>';
	  
	  $form .= '<tr><td colspan="2" onclick="$(\'#password\').slideToggle();" style="cursor:pointer;">Reset Password</td></tr>';
	  
	  $form .= '<tr id="password" style="'.$display.' background:#eee;">';
	  $form .= '<td><label>New Password:</label>'.$f->formPassword('password', '',$user[0]->password,'','required').'</td>';
	  $form .= '<td><label>Please re-enter new password:</label>'.$f->formPassword('confirm_password', '',$user[0]->password,'','required').'</td>';
	 // $form .='</tr>';
	  
	  $form .= $clientbits.$member.'</table>';
	  $form .= $f->formFieldsetEnd();
	  $form.= $f->formHidden('location', $P->location);
	  $form.= $f->formHidden('client_id', $user[0]->client_id);
	  $form.= $f->formHidden('user', $user[0]->ID);
	  $form.= $this->button2('+ Save Profile','submitProfile');
      $form .= $f->formEnd('','','new');
		}
	  
      return $form;

		
		
		
		
		
		
	}
	
    
	public function doSearch($type='project'){
	$Items = new kingsItems();
	$search='<div class="clear"></div>';
	$search.='<div style="border:solid 1px #ccc; border-bottom:none; padding:10px;">';
	$search.='<div id="searchdrop" style="cursor:pointer;">Filter</div><div style="float:right;position:relative;top:-13px">Show: <span style="background-color:#8DBDD8;" class="filter" id="l" onClick="doFilter(\'0\',\'l\')">Live</span> <span id="c"  class="filter" onClick="doFilter(\'1\',\'c\')">Completed</span>  <span id="o" class="filter" onClick="doFilter(\'2\',\'o\')">Ongoing</span> <span id="a" class="filter" onClick="doFilter(\'3\',\'a\')">ALL</span></div>';
	$search.='<div style="display:none;" id="search">';
	$search.='<form id="searchForm">';
	$search.='<div style="margin-top:8px; border-top:1px #ccc solid;margin-bottom:10px;"></div>';
	$search.='<label>Project Members</label>';
	$search.=$this->usersValues('','',1);
	$search.='<div style="border-top:1px #ccc solid;margin-bottom:10px;"></div>';
	$search.='<label>Client</label>';
	$filterclients=$this->listclientsforsearch();
	$search.=($filterclients);
	$search.='<div style="border-top:1px #ccc solid;margin-bottom:10px;margin-top:10px;"></div>';
	if($type != 'task'){
		$search.='<label>Keywords</label>';
		$search.=$this->doProjectKeyWords('',1);
	}
	$search.='<input type="hidden" id="show" name="show" value="2">';
	$search.='<input type="hidden" id="type" name="type" value="'.$type.'">';
	$search.='<input type="hidden" id="user" name="user" value="'.$_SESSION['user']['ID'].'">';
	$search.='<div style="border-top:1px #ccc solid;margin-bottom:10px;"></div>';
	$search.='<label>Search (Title Desc): </label>';
	$search.='<input type="text" id="searchString" name="searchString" onkeyup="doSearchKey();" style="width:400px">';
	$search.='</div></form></div>';
	return $search;
	}
	
    public function doProjects(){
	$Items = new kingsItems();
	$search = $this->doSearch();   
	return $search.'<div id="results">'.$Items->outputProjects('','',1).'</div>';
    }
    
	
    public function doProjectUpdates($updates=''){
	$format = new kingsFormatter();	
      $f = new Form();
      $Items = new kingsItems();
      $_GET['s']=='task'?$id=$_GET['t']:$id=$_GET['s'];
      $updates==''?$updates=$Items->getUpdatesFromProjects($id):null;
      $taskUpdateArray='';
      if($updates){
        foreach($updates as $update){
			$updateUserArray='';
          foreach($update->users as $user){
              $updateUserArray.=$user->name.'<br>';
          }
		  $commentName='';
		  $commentArray='';
         if ($update->children){
            foreach($update->children as $comment){
                foreach($update->users as $user){
                $commentName='<div class="comment"><strong>'.$user->innitial.'</strong>';
                $commentArray.=$commentName.' ('.$comment->date.') <br>'.$format->afterSummary($comment->detail).'</div>';	
				}
			}
          }
          $updateArray.='<tr><td>'.$updateUserArray.'</td><td colspan="2" id="commenttext'.$update->id.'">'.$format->afterSummary($update->detail).$commentArray.'</td><td>'.$update->date.'</td><td>'.$this->button('View','/view-updates/update/'.$update->id,'').'</td><td>'.$this->button('Comment','','doComment('.$update->id.')').'</td></tr>';
          $updateArray.='<tr style=" display:none;" id="comment'.$update->id.'"><td colspan=5><form id="comment'.$update->id.'" class="commentform">Comment: <input style="width:500px;" type="text" id="text'.$update->id.'" name="text'.$update->id.'"></textarea><input type="hidden" name="users" value="'.$_SESSION['user']['ID'].'">
		  <input type="hidden" name="projectId" value="'.$_GET['s'].'"></form></td>';		  
		  }
      }
	  $_GET['p']!='view-updates'?$editbutton=$this->button('Edit','../view-updates/'.$id):null;
       return '<table class="sorter" style="border: 1px solid #666; margin: 5px;"><tr><td colspan ="6"><strong  style="float: left;">Updates</strong>'.$editbutton.'</td></tr>
      <tr><th>From</th><th colspan="2">Detail</th><th>Time and date</th><th></th><th></th></tr>
        '.$updateArray.'</table>'.$boom;
    }
    
    public function usersValues($projectUsers='', $autor='', $search=0,$type=0){
      $f= new Form();
      $U = new kingsUser();
      $users = $U->returnUser();
       foreach($users as $user){// ALL USERS								
        if ($projectUsers){
          foreach($projectUsers as $pUser){
              if ($pUser->ID == $user->ID){
              $checked='Y';
              }
          }
        }
		$search==0?$br='<br>':null;
          $UserArray2.= $f->formCheckbox('user',$user->innitial,$user->ID,'user',$checked,'block required',$edit).$br;
          $checked='N';
          }
      return $UserArray2;
    }
    
	public function clientVaues($pid){
		$U= new kingsUser();
		$f = new Form();
		if($_GET['p']=='new'){
		$cids =  $U->getclientFromUser($pid);
		}else{
		if(!is_numeric($pid)){
			$pid = $_GET['s'];
		}
		$cids =  $U->getClientFromProject($pid);
		}
		$cid = $cids[0]->ID;
		$allClients= $U->outputClients();
		foreach($allClients as $client){// ALL USERS
			$cid==$client->ID?$default='Y':$default='N';
			$cli[$client->ID]=array('id'=>$client->ID, 'value'=> $client->name,'default'=>$default);
		}
		return $f->formSelect2('client', $cli,'');
	}
	
	
	public function listclientsforsearch($pid){
		$U= new kingsUser();
		$f = new Form();
		if($_GET['p']=='new'){
		$cids =  $U->getclientFromUser($pid);
		}else{
		if(!is_numeric($pid)){
			$pid = $_GET['s'];
		}
		$cids =  $U->getClientFromProject($pid);
		}
		$cid = $cids[0]->ID;
		$allClients= $U->outputAllClients();
		foreach($allClients as $client){// ALL USERS
			$cid==$client->ID?$default='Y':$default='N';
			$cli[$client->ID]=array('id'=>$client->ID, 'value'=> $client->name,'default'=>$default);
		}
		return $f->formSelect2('clientsearch', $cli,'');
	}
	
	
    public function alertValues($alerts='',$type=0, $autor=''){
      $I= new kingsItems();
      $f = new Form();
      $type==0?$id = $_GET['s']: $id= $_GET['t'];

	  
   $id=='new'?$id='':null;
	  $id=='project'?$id='':null;
	  $id=='task'?$id='':null;
      $alerts==''?$alerts=$I->getAlerts($id,$type):null;
						//print_r($alerts);
      $allAllerts= $I->getAlerts();
						$custom='';
       foreach($allAllerts as $allAllert){// ALL USERS
         if ($alerts){
            foreach($alerts as $pAlerts){
                if ($pAlerts->alert_id == $allAllert->id){
																				if($pAlerts->alert_id==5){
																								$custom='<a href="#" class="alertsUpdate">Update date</a>';
																								$value=$pAlerts->date;
																				}
                $checked='Y';
                }
            }
        }
          $alertArray.= $f->formCheckbox('alerts',$allAllert->title,$allAllert->id,$allAllert->id,$checked,'block alerts').'<br>';
          $checked='N';
          }
										
										 $alertArray.= '<input type="date" style="display:block;" data-orig-type="date" value="'.$value.'"  name="custom_alert_date" class="customdate"><br>';
       return $alertArray;
    }
    
    public function doProjectKeyWords($projectkeyWords='', $search=0,$id='',$all=1){
	$i=0;
    $f = new Form();
	$Items = new kingsItems();
    $_GET['s']=='task'?$id=$_GET['t']:$id=$_GET['s'];
     $projectkeyWords==''?$projectkeyWords=$Items->outputKeywords($id):null;
      $AllkeyWords = $Items->outputKeywords(0,$all);	  
       foreach($AllkeyWords as $AllWords){// ALL Words
		if($projectkeyWords){
          foreach($projectkeyWords as $pWord){
              if ($pWord->Keyword_id == $AllWords->id && $search==0 ){
              $checked='Y';
              }
          }
	  	}
		$i==0?$rowStart="<tr>":$rowStart='';
		$i==5?$rowEnd="</tr>":$rowEnd='';
		if ($i<=4){
			$i=$i+1;
		}else{
			$i=0;
		}
		
		$col .=$rowStart.'<td>'.$f->formCheckbox('Keyword',$AllWords->name,$AllWords->id,'keywords',$checked,'block').'</td>'.$rowEnd;
		
          $wordArray.=$f->formCheckbox('Keyword',$AllWords->name,$AllWords->id,'keywords',$checked,'block');
          $checked='N';
          }
		 $search==0?$return= '<label>Check / uncheck to edit</label><table id="keywordstable" style="border: 1px solid #666; margin: 5px;">
		 
		 <input type="hidden" value ="'.$id.'" id="project_id" name="project_id">
		
		 '.$col.'</table>' :$return='<table style="border:none; width:880px;padding:0px;width:880px;">'.$col.'</table>';
      return 	$return;
   
    }
    
    public function doProjectTask($tasks =''){
       $f = new Form();
	   $format = new kingsFormatter();
        $Items = new kingsItems();
        $_GET['s']=='task'?$id=$_GET['t']:$id=$_GET['s'];
        $tasks==''?$tasks=$Items->getTaskFromProjects($id):null;
      	$taskUserArray='';
        if ($tasks){
          foreach($tasks as $task){
			if ($task->users){
				$taskUserArray='';
			foreach($task->users as $user){
				$taskUserArray.=$user->innitial.'<br>';
			}
			$task->complete==1?$grey='style="color:#ccc;"':$grey='';
			$task->duedate=='0000-00-00'?$due='--':$due=$task->duedate;
			$task->project_id!=''?$taskid ='#'.$task->project_id.'&#151;'.$Items->getPFromT($task->project_id):$taskid='-';
			$taskArray.='<tr '.$grey.' ><td>'.$task->title.'</td><td><p>'.$format->afterSummary($task->description).'</p></td>
            <td>'.$Items->getmembersfromTask($task->id).'</td><td>'.$due.'</td><td>'.$this->button('view','/view-tasks/task/'.$task->id).'</td><td>'.$this->completedButton($task->id,$task->complete,'1').'</td></tr>';
			}
		  }
        }
		$_GET['p']!='view-tasks'?$editbutton=$this->button('Edit','../view-tasks/'.$id):null;
          return '<table class="sorter" style="border: 1px solid #666; margin: 5px;"><tr><td colspan ="6"><strong style="float: left;">Tasks</strong>'.$editbutton.'</td></tr>
		  <tr><th>Title</th><th>Description</th><th>Task members</th><th>Due date</th><th></th></tr>
            '.$taskArray.'</table>';
    }
    
    public function doTasks(){
	$search = $this->doSearch('task');
	$Items = new kingsItems();
	return $search.'<div id="results">'.$Items->outputTasks().'</div>';
    }
	
	public function completedButton($id, $completed, $type){
				if($completed==0){$comp0='active';}
				if($completed==1){$comp1='active';}
				if($completed==2){$comp2='active';}
		  $buttons.= '<div title="Mark as Live" id="'.$id.'" style="color:white;" class="button live compButton '.$comp0.' '.$id.'-0" onclick="doComplete('.$id.',0,'.$type.');">+ Mark as Live</div>';
				$buttons.=  '<div title="Mark as Complete" id="'.$id.'" style="color:white;" class="button complete compButton '.$comp1.' '.$id.'-1" onclick="doComplete('.$id.',1,'.$type.');">+ Mark as Complete</div>';
				$buttons.=  '<div title="Mark as Ongoing" id="'.$id.'" style="color:white;" class="button ongoing compButton '.$comp2.' '.$id.'-2" onclick="doComplete('.$id.',2,'.$type.');">+ Mark as Ongoing</div>';
				return $buttons;
	}
    
     public function button($input, $link, $onclick=""){
	$onclick!=''?$onclick='onclick="'.$onclick.';"':null;
	$link!=''? $link = $href='href="'.$link.'"':null;
	return '<a '.$href.' target=_self><div class="button" '.$onclick.'>+'.$input.'</div></a>';
    }
    
    public function button2($input, $onclick=""){
	$onclick!=''?$onclick='onclick="'.$onclick.'();"':null;
	return '<input type="button" class="button" '.$onclick.' value="'.$input.'">';
    }
    
    public function doProjectViewEidTask($type=0){
      $Items = new kingsItems();
      $_GET['s']=='task'?$id=$_GET['t']:$id=$_GET['s'];  
	  !$Items->returnView($id,1)?$Items->recordView($id,1):null;
			
      $values =array('title'=>'', 'updates'=>$this->doProjectUpdates(), 'task'=>$Items->getTaskFromTaskID($id), 'words'=>$this->doProjectKeyWords(),'alerts'=>$this->alertValues('',$type));
						$bits = $this->getBits();
      $form = $this->AddEditTemplate(1,'',$bits,$values);
      return $form;
    }
	
	
	public function listProjectRequests(){
	$Items = new kingsItems();
	return $Items->outputProjectRequests();
	}
	
	public function doStationaryRequest(){
	$P = new kingsProducts();
	$f = new Form();
      $form .='<div id="loading" style="display:none;"></div>';
      $form .= $f->formStart('','','stationary-request');
      $form .= $f->formFieldsetStart();
      $form .= '<table id="prod"><tr><td width="400px"><strong> Product  </strong >'.$f->formSelect('product',  $P->allprod,'','').'</td>';
	  $form .= '<td><strong> Quantity  </strong >'.$f->formSelect('quantity',  array('Please select'),'').'</td>';
	  $form .= '<td><input style="float:none;" type="button" class="button" onClick="add(1);" value="+ Add to cart"></td></tr></table>';
	  $form .= $f->formFieldsetEnd();
	  $form .= $f->formFieldsetStart();
	  $form .= '<table><tr><td><label>Comments and Delivery instructions</label>';
	  $form .= '<p>Unless specified here, delivery will be to your registered College address</p>';
	  $form .= $f->formTextArea('comments').'</td></tr></table>';
	  $form .= $f->formFieldsetEnd();
	  $form.= $f->formHidden('location', $P->location);
	  $form.= $f->formHidden('client_id', $_SESSION["user"]["client_id"]);
	  $form.= $f->formHidden('user', $_SESSION["user"]["ID"]);
	  $form.= $this->button2('+ Submit Request','submitrequest');
      $form .= $f->formEnd('','','new');
      return $form;

	}
	
    public function doProjectRequest(){
		$Items = new kingsItems();
		$type==0? $ItemIndex='project':$ItemIndex='task';
      $f = new Form();
      $form .='<div id="loading" style="display:none;"></div>';
      $form .= $f->formStart('','','project-request');
      $form .= $f->formFieldsetStart();
      $form .='<table style="border:none;"><tr><td>';
      $form .= $f->formText('title', 'Title',$values[$ItemIndex]->title,'','required');
	  
	  $objlabel='<span style="font-weight:normal; font-size:0.8em;">Give a detailed description of what is required, who the audience are and in which region it is going to be seen. Please state if this is based on a previous project produced by the CMU, providing the project number<span>';
      $form .= $f->formTextArea('objective', 'Objective overview and requirement<br>'.$objlabel,$values[$ItemIndex]->description,'','required');
	  
	  $caselabel='<span style="font-weight:normal; font-size:0.8em;">Give a brief outline as to the business value for this project<span>';
      $form .= $f->formTextArea('case', 'business case<br>'.$caselabel,$values[$ItemIndex]->description,'','required');
	  
	  $techlabel='<span style="font-weight:normal; font-size:0.8em;">&mdash;Specify final required format, size and ratio where relevant.<br>&mdash;If it is to be translated, please include further details here.<br>&mdash;Include delivery details and any contact information of relevant third parties<span>';
      $form .= $f->formTextArea('tech', 'technical specification, language and delivery<br>'.$techlabel,$values[$ItemIndex]->description,'','required');
	  
	  
      if ($values[$ItemIndex]->created_date){
        $form .='<br><br><strong>Created:</strong>'.$values[$ItemIndex]->created_date;
      }
      $form .='</td><td class="input" width="150px">';
      $form .='<div class="title">Project Area</div>';
	  //////////////////////////////////////////
	  $area = $Items->projectAreaOptions();
	  foreach($area as $a){
		$projectArea.=$f->formCheckbox('pro_area',$a,$a,'pro_area',$checked,'block required').'<br>';
	  }
      $form .=$projectArea;
	  $form.='<div style="margin-top:10px;border-bottom:dotted 1px #666;"></div>';
	  //////////////////////////////////////////
	  $focus = $Items->projectFocusOptions();
	  $form .='<div class="title">Project focus</div>';
	  foreach($focus as $foc){
		$projectFocus.=$f->formCheckbox('pro_focus',$foc,$foc,'focus',$checked,'block required').'<br>';
	  }
      $form .=$projectFocus;
	  $form.='<div style="margin-top:10px;border-bottom:dotted 1px #666;"></div>';
	  //////////////////////////////////////////
	  $location = $Items->projectLocationFocusOptions();
	  $form .='<div class="title">Location focus</div>';
	  foreach($location as $l){
		$locationFocus.=$f->formCheckbox('location',$l,$l,'location',$checked,'block required').'<br>';
	  }
      $form .=$locationFocus;
	  /////////////////////////////////////////
	  
      $form .='</td><td class="input">';
      $form .='<div class="title">Delivery Date</div>';
      $form .= '<div id="calendar"><input class="date required" type="date" name="mydate" value="'.$values[$ItemIndex]->duedate.'" /></div><div id="theday"></div>';
      $form .= '</tr></table>';
      $form .= $f->formFieldsetEnd();
      if ($type==0){
        $form .= $f->formFieldsetStart();
        $form .= $item['wordArray'];
        $form .= $f->formFieldsetEnd();
      }
      $form.= $f->formHidden('id',$values[$ItemIndex]->id,'id');
      $form.= $f->formHidden('type','project_request','type');
	  $form.= $f->formHidden('created_by',$_SESSION['user']['ID'],'created_by');
    
      $form.= $this->button2('Submit request','submitProject');
      $form.= $this->button2('Clear','reset');
      $form .= $f->formEnd('','','new');
      return $form;
    }
	
	
      public function doProjectViewEidUpdate($values=1){
		$Items = new kingsItems();
		$values!=1? $update = $Items->getUpdate($_GET['t']):null;
		$f = new Form();
		$form .='<div id="loading" style="display:none;"></div>';
		$form .= $f->formStart('','','new','content');
		$form .= $f->formFieldsetStart();
		$form .='<table style="border:none;"><tr>';
		$form .= '<td>'.$f->formTextArea('update', 'update',$update->detail).'</td>';      
		$form .='<td class="input">Send Alert to:<br>';
		$form .=$this->usersValues().'</td>';
		$form .='</tr></table>';
		$form .= $f->formFieldsetEnd();
		if ($this->doSaveButtons($update->user_id)  || $_GET['t']=='new'){
			$form.= $this->button2('Save','submitProject');
		}
		$form.= $this->button2('Cancel','back');
		$form.= $f->formHidden('id',$update->id,'id');
		$form.= $f->formHidden('type','update','type');
		$form.= $f->formHidden('origin_user',$_SESSION['user']['ID'],'user');
      if ($_GET['t']=='new'){
          $form.= $f->formHidden('projectId',$_GET['s'],'projectId');
          $form.= $f->formHidden('action','new','action');
      }else{
        $form.= $f->formHidden('action','edit','action');
      }
      $form .= $f->formEnd('','','new');
	  
      return $form;
    }
	
	public function doSaveButtons($author){
		if($author==$_SESSION['user']['ID'] || $_SESSION["user"]["type"]==1){
			$return = true;
		}else{
			$return = false;
		}
		return $return;
	}

    public function AddEditTemplate($type=0, $form="", $item="", $values=""){
	 $f = new Form();		
	 $type==0? $ItemIndex='project':$ItemIndex='task';
	 if ($_GET['s']=='project'){
		$ItemIndex='project';
	 }
	 if ($_GET['s']=='task'){
		$ItemIndex='task';
	 }
	  if(is_numeric($_GET['t']) && $_GET['p']=='new' ){// turn request into Project
		$req= '/'.$_GET['t'];
		$Item = new kingsItems();
		$itemIndex='request';
		$values[$ItemIndex] = $Item->getProjectrequest($_GET['t']);
		$request= $f->formHidden('request_id',$values[$ItemIndex]->id,'request_id');
		$clients = '<label for="frm_title">client</label>'.$this->clientVaues($values['project']->created_by);
	  }
		  if ($ItemIndex=='project'){
			if ($clients==''){
				$clients .= $f->formSelect('client', $item['clientVal'],'client');
			}
		  }else{
			if($_GET['s']=='task' ){
			$Item = new kingsItems();
			$projects = $Item->getProjectNames();
			$assigntoPro .= '<br><br>'.$f->formSelect('projectIdAssign', $projects,'Assign to Project');
			}
		  }
      $form .='<div id="loading" style="display:none;"></div>';
      $form .= $f->formStart('','','new','content');
      $form .= $f->formFieldsetStart();
      $form .='<table style="border:none;">';
	  if ($_GET['p']=='new'){
		if ($_GET['s']=='project'){
			$greypro = 'style="color:grey;"';
		}else{
			$greyTask = 'style="color:grey;"';
		}
	  $form.= '<tr><td colspan="4"><label>TYPE</label> <a href="/new/project'.$req.'" '.$greyTask.'>Project</a> | <a href="/new/task'.$req.'" '.$greypro.'>Task</a>';
	  $form.= $assigntoPro.'</td></tr>';
	}

      $form .= '<tr><td>'.$f->formText('title', 'Title',$values[$ItemIndex]->title,'','required','3');
      $form .= $clients.$request;
      $form .= $f->formTextArea('Brief', 'Summary',stripslashes($values[$ItemIndex]->description),'','required');
      $form .= $f->formTextArea('brief2', 'Brief',stripslashes($values[$ItemIndex]->brief2),'');
      if ($values[$ItemIndex]->created_date){
        $form .='<br><br><strong>Created:</strong>'.$values[$ItemIndex]->created_date;
      }
      $form .='</td><td class="input">';
      $form .='<div class="title">Members</div>';
      $form .=$this->usersValues($values[$ItemIndex]->users);
      $form .='</td><td class="input">';
      $form .='<div class="title">Delivery Date</div>';
      $form .= '<div id="calendar"><input class="date" type="date" name="mydate" value="'.$values[$ItemIndex]->duedate.'" /></div><div id="theday"></div>';
      $form .='</td><td class="input">';
      $form .='<div class="title '.$ItemIndex.'" id="alerts">Alerts<br>';
      $form .=$values['alerts'];
      $form .='</div>';
     
      $form .='</td></tr></table>';
      $form .= $f->formFieldsetEnd();

      if ($ItemIndex=='project'){
        $form .= $f->formFieldsetStart();
        $form .='<table id="fullview" style="border: 1px solid #666; margin: 5px;"><tr><td><strong><span style="float:left;">Keywords</span></strong></td></tr><tr><td>';
	$form .=$this->doProjectKeyWords();
	$form.='</td></tr></table>';
        $form .= $f->formFieldsetEnd();
      }
      $form.= $f->formHidden('id',$values[$ItemIndex]->id,'id');
      $form.= $f->formHidden('type',$ItemIndex,'type');
      if ($_GET['t']=='new' || $_GET['p']=='new'){
          $form.= $f->formHidden('projectId',$_GET['s'],'projectId');
          $form.= $f->formHidden('action','new','action');
		  $form.= $f->formHidden('requestBy',$values[$ItemIndex]->requestBy);
		  $form.= $f->formHidden('created_by',$_SESSION['user']['ID'],'created_by');
      }else{
        $form.= $f->formHidden('action','edit','action');
      }
    
      $form.= $this->button2('Save','submitProject');
	  if ($this->doSaveButtons($values[$ItemIndex]->created_by) ){
		$form.= $this->button('Delete','','deleteTask('.$values[$ItemIndex]->id.')');
	  }
  
      $form.= $this->button2('Cancel','back');
      $form .= $f->formEnd('','','new');
      return $form;
    }
    
    
    public function doNew($type=0){
      $bits = $this->getBits();
      $form = $this->AddEditTemplate(0,'',$bits, array('alerts'=>$this->alertValues() ));
      return $form;
      
    }
      
    public function getBits($type=0){
	$f = new Form();
	$U = new kingsUser();
	$clients = $U->outputClients();
	foreach ($clients as $client){
	    $clientVal[$client->ID]=$client->name;
	}
	
	$users = $U->returnUser();
	foreach ($users as $user){
	    $UserArray.=$f->formCheckbox('user',$user->innitial,$user->ID,'user','','block').'<br>';
	}
	$I = new kingsItems();
	$words = $I->outputKeywords();
	foreach ($words as $word){
	    $wordArray.=$f->formCheckbox('Keyword',$word->name,$word->id,'keyword','','block');
	}
    $I = new kingsItems();
	$alerts = $I->getAlerts();
	foreach ($alerts as $alert){
	    $alertArray.=$f->formCheckbox('alert',$alert->title,$alert->id,'alert','','block');
	}
    
	return $item = array('clientVal'=>$clientVal,'UserArray'=>$UserArray,'wordArray'=>$wordArray,'alerts'=>$alertArray, );
    }
    
    public function projectTop(){
    $I = new kingsItems();
	$U = new KingsUser();
    if ($_GET['s']=='task'){
      $task = $I->getTaskFromTaskID($_GET['t']);
      $pid = $task->project_id;	  
    }elseif($_GET['s']=='update'){
	  $update = $I->getUpdate($_GET['t']);
      $pid = $update->project_id;
	}else{
      $pid = $_GET['s'];
    }
	if ($pid !=0){
		$project = $I->getProject($pid);
		//if ($this->doSaveButtons($project->created_by, )){
			$class='class="edit"';
			$edit='  [edit]';
		//}
		
		$creator = $U->returnUser($project->created_by);
	
		$clientbox='<div style="display:none;" id="client">'.$this->clientVaues($pid).'</div>';
		if ($project->complete ==0){
			$status ='Live';
		}else if ($project->complete ==1){
			$status ='Completed';
		}else if ($project->complete ==2){
			$status ='Ongoing';
		}
		$output.='<div class="clear"></div>';
		$output.='<h3><div>#'.$project->id.'</div></h3>';
		$output.='<h3 id="title"'.$class.' ><div>'.$project->title.'</div></h3><br>';
		$output.='<div><table> <tr><td><strong>Status: </strong></td><td><span id="status"> '.$status.'</span></td><td><div>'.$this->completedButton($project->id,$project->complete,'0').'</div></td></tr></table></div>';
		$output.='<div><strong>Client: </strong>'.$clientbox.'<span id="clientPrint">'.$project->client[0]->name.'</span><span id="editclient" class="editcheck" onclick="reveal(\'#client\',this)"">'.$edit.'</span></div>';
		$project->created=='0000-00-00'?$created=' Date unknown':$created=date("d/m/Y", strtotime($project->created));
		$output.='<div><strong>Created: </strong><span>'.$created.' &#151; by '.$creator[0]->name.'</span></div>';
		
		}else{
			$output.='<div class="clear"></div><h3><div>This task does not belong to a project</div></h3>';

		}
		return $output;
    }
    
	
	
	
    public function doViewProject(){
	$pid= $_GET['s'];
	$I = new kingsItems();
	$project = $I->getProject($pid);
	
	!$I->returnView($pid,0)?$I->recordView($pid,0):null;
    
	if ($this->doSaveButtons($project->created_by)){
		$edit='<br>[edit]';
	}
	foreach($project->users as $name){
		if ($this->doSaveButtons($name->ID)){
		$edit='<br>[edit]';
	}
	$users.=$name->innitial.', ';
	}
	$users = substr($users, 0, -2);

	
	if ($project->alerts){
		foreach($project->alerts as $name){
		$alerts.=$name->title.'<br> ';
		}
	}
	
	if ($project->words){
	    foreach($project->words as $word){
		$wordArray.=$word->name.', ';
	    }
		$wordArray = substr($wordArray, 0, -2);
	}
	
	
	
	
	$taskArray = $this->doProjectTask($project->tasks);
	$updateArray =$this->doProjectUpdates($project->updates);
	$UserArray2= $this->usersValues($project->users,$project->created_by);
    $alertArray= $this->alertValues($project->alerts,0,$project->created_by);
	$output .='<input id="project_id" type="hidden" value="'.$project->id.'">';
	$output .='<table style="border: 1px solid #666; margin: 5px;" ><tr><td>';
	$output .= '<strong>Summary</strong><br><p id="description" class="editable-area" style="width:450px;">'.$project->description.'</p>';
	$output .= '<strong>Brief</strong><br><p id="brief2" class="editable-area" style="width:450px;">'.str_replace('\n','<br>', $project->brief).'</p>';
	$output .='</td><td class="input" style="width:80px;">';
	$output .='<div class="title">Members</div>';
	$output .= '<div id="usersPrint">'.$users.'</div>';
	$output .= '<div style="display:none;" id="users">'.$UserArray2.'</div><br>';
	$output .='</td><td class="input">';
	$output .='<div class="title">Delivery Date</div>';
		if   ($project->duedate=='0000-00-00'){
		   $style=' style=" display:none;"';
		   $init =date('d/m/Y');
		}else{
			$init=$project->duedate;
		}
	$output .= '<div id="calendar"><input id="calPrint" style="display:none;" class="date" type="text" name="mydate" value="'.$init.'" /></div><div id="theday" '.$style.'></div>';
	$output .='<br></td><td class="input">';
	$output .='<div class="title">Alerts</div>';
    //ALERTS
	$output .= '<div id="alertsPrint">'.$alerts.'</div>';
	$output .= '<div style="display:none;" id="alerts">'.$alertArray.'</div>';
	$output .='</td></tr>';
	
	$output .='<tr><td><span id="editbrief" class="editcheck" onclick="reveal(\'#description\',this)""></span></td><td><span class="editcheck" onclick="reveal(\'#users\',this)"">'.$edit.'</span></td><td><span class="editcheck" onclick="reveal(\'#cal\',this)"">'.$edit.'</span></td><td><span class="editcheck" onclick="reveal(\'#alerts\',this)"">'.$edit.'</span></td></tr>';
	
	
	$output .='</table>';
	//KEYWORDS
	$output .='<table id="fullview" style="border: 1px solid #666; margin: 5px;"><tr><td><strong><span style="float:left;">Keywords</span></strong> '.$this->button('Edit','../view-keywords/'.$project->id).'</td></tr><tr><td>';
	$wordArray!=''? $output .=$this->doProjectKeyWords('','',$project->id):null;
	$output.='</td></tr></table>';
		
	
	//Tasks
	
    $output .=$taskArray;
	//Updates
	$output .=$updateArray;
	
	//Uploads
	$output .='<table style="border: 1px solid #666; margin: 5px;"><tr><td><strong><span style="float:left;">Uploads</span></strong></td></tr><tr><td>';
	
	
	$output.=  '
	
	 <div class="progress">
			    <ul id="file-status">'.$project->files.'</ul>        
			</div>
	<br><br>
	<div onclick="$(this).next().slideToggle();" class="button" style="color:white;" >+ add File</div>
	
	
		    <form  id="fileForm" style="display:none;" action="/file.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="pid" value="'.$project->id.'">
			
			<table style="width:50%;">
			    <tr><td>Custom Name:</td><td><input type="text" name="txtname"></td></tr>
			    <tr><td>File:</td><td><input type="file" name="myfile"></td></tr>
			    <tr><td>&nbsp;</td><td><input class="button" type="submit" value="Upload"></td></tr>
			</table>
			
		    </form>';
		
	$output .='</td><tr></table>';	    
	$output.= '<a target="_self"><div onclick="doProjectDelete('.$project->id.');" class="button">- Delete Project</div></a>';
    
	return 	$output;
    }
    
    
    public function doSched(){
	$Items = new kingsItems();
	print_r($this->button('New Project','/new').$this->button('New Task','/new/task').$Items->outputItems());
    }


  
    
    public function outputSitemap() {
        $menu = "<ul>";
            
        $query = "
            SELECT
                ID,
                friendly_url,
                page_title
            FROM
                pages 
            WHERE
                parentID = 0 
            OR 
                parentID = 999 
            ORDER BY
                display_order;
        ";
        $result = mysql_query( $query );
        while( $m = mysql_fetch_object( $result ) ) {
            
            $m->friendly_url == "" ? $m->friendly_url = "/" : $m->friendly_url = $m->friendly_url;
                   
            $menu .= "<li><a href=\"".$m->friendly_url."\" title=\"".$m->page_title."\">".$m->page_title."</a>";
            
            $querys = "
            SELECT 
                ID,
                friendly_url,
                page_title
            FROM
                pages
            WHERE
                parentID = ".$m->ID." 
            ORDER BY
                display_order;";
                
            $results = mysql_query( $querys );
            $num = mysql_num_rows($results);
            if ($num > 0) {
                
                while( $sm = mysql_fetch_object( $results ) ) {
                    $subitem .= "<li><a href=\"".$sm->friendly_url."\" title=\"".$sm->page_title."\">".$sm->page_title."</a></li>";
                }
                $menu .= "<ul>".$subitem."</ul></li>";   
            }else {
                
                if($m->friendly_url == "what-we-do") {
                    
                    $query3 = "SELECT ID, title FROM work_type ORDER BY ID;";
                    $result3 = mysql_query( $query3 );
                    $i = 1;
                    while ($worktype = mysql_fetch_object($result3)) {
                        $worktype->title = str_replace("<br />", "", $worktype->title);
                        
                        $workmenu .= "<li>".$worktype->title."<ul>";
                        $query2 = "SELECT ID, client FROM work WHERE type = '".$worktype->ID."' AND status = 'Y' ORDER BY display_order ASC;";
                        $result2 = mysql_query( $query2 );
                        while ($items = mysql_fetch_object($result2)) {
                            $clienturl = str_replace(".", "_", $items->client);
                            $workmenu .= "<li><a href=\"/".$m->friendly_url."/".urlencode($clienturl)."\" title=\"view our ".$items->client." work\">".$items->client."</a></li>";
                        }
                        $workmenu .= "</ul></li>";
                    }
                    
                    $menu .= "<ul>".$workmenu."</ul>";
                }
                
                $menu .= "</li>";
            }
            $subitem = "";
        }
        

        
        $menu .= "</ul>";

        $content = $menu;
        
        return $content;
    }
    
    
    // ------------------  PRIVATE FUNCTIONS -------------- //
    
    protected function _getPageContent() {
        $query = "SELECT ID, page_title, parentID, page_typeID, browser_title, meta_description, meta_keywords, title, content FROM pages WHERE ID = '".$this->pageID."' LIMIT 1;";
        $p = mysql_fetch_object( mysql_query( $query ) );
        
        // Set Header Information
        //$this->setSiteTitle( $p->browser_title );
        //$this->setMetaDescription( $p->meta_description );
        //$this->setMetaKeywords( $p->meta_keywords );
        
        // Set Main Content
        //$this->pageContent .= $p->content;
        
        return $p;
    }
    
    public function setSiteMeta() {
        $m = $this->_getPageContent();
        $this->setMetaKeywords( $m->meta_keywords );
        $this->setMetaDescription( $m->meta_description );
    }

    
    protected function _getSiteTitle() {
        $this->pageID == "" ? $this->pageID = 2 : $this->pageID = $this->pageID;
        $query = "
            SELECT
                browser_title
            FROM
                pages
            WHERE
                ID = ".$this->pageID."
            LIMIT 1;
        ";
        $t = mysql_fetch_object( mysql_query( $query ) );
        return $t->browser_title;
    }
    
   
    public function outputLatestNews() {

         $news = $this->_getNewsItems(1, 3);
            if(is_array($news)) {
            foreach($news as $n) {
                $intro = Formatter::summarise($n["content"],80);
                $content .= "
                <div class=\"news_list_item item-".$pagecol."\">
                    <h3><a href=\"news/".urlencode($n["title"])."\" title=\"read more of ".$n["title"]."\">".$n["title"]."</a></h3> 
                    ".$intro."
                </div>
                ";
            }
        }
       
        return " <h2>Latest News</h2>".$content;
    }
    
}

?>