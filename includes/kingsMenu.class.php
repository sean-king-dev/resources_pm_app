<?php

// Changefirst CFMenu Class

class kingsMenu extends Menu {

/* ************************************************************************************ */

// Public Variables

// Protected Variables

// Private Variables


/* ************************************************************************************ */

// Public Methods

    public function __construct() {
        parent::__contruct();
        $this->setPageIDs();
    }

    public function outputMenu() {
        $content =
            $this->_generateMenu();
        return $content;
    }

    public function outputSubMenu( $parentID=0 ) {	
    	if ( $parentID == 0 )
            $this->parentID > 0 ? $ID = $this->parentID : $ID = $this->pageID;
        else
        $ID = $parentID;
    	$content = $this->_generateMenu( $ID );
		$I=new kingsItems();
        if ($_GET['s']=='task'){
			$task = $I->getTaskFromTaskID($_GET['t']);
			$pid = $task->project_id;
		}elseif($_GET['s']=='update') {
			$update = $I->getUpdate($_GET['t']);
			$pid = $update->project_id;
		}else {
			$pid=$_GET['s'];
        }
        $content = str_replace('view-keywords', 'view-keywords/'.$pid, $content);
        $content = str_replace('view-project', 'view-project/'.$pid, $content);
        $content = str_replace('view-tasks', 'view-tasks/'.$pid, $content);
        $content = str_replace('view-updates', 'view-updates/'.$pid, $content);
        return $content;
    }

    public function returnPageID() {
        return $this->pageID;
    }

    public function returnParentID() {
        return $this->parentID;
    }

    public function setPageIDs() {
        $query = "
            SELECT
                ID,
                parentID
            FROM
                pages
            WHERE
                friendly_url = '".$_GET["p"]."'
            LIMIT 1;
        ";
        $p = mysql_fetch_object( mysql_query( $query ) );
        $this->pageID = $p->ID;
        $this->parentID = $p->parentID;
    }

// Protected Methods

       protected function _generateMenu( $parentID=0, $mode="" ) {
        $page = $this->_getPageIDs();
        $menu = $this->_getMenu( $parentID );
        if ( is_array( $menu ) ) {
            $content = "
                <ul>
            ";
            foreach( $menu as $m ) {
				$url = "/";	
                if ( $_GET["p"] == $m["friendly_url"] ) {
                    if ( $mode == "edit" ) {
                        $f = new CFForm();
						
                        if ($m["page_title"]!='Profile'){
						$content .=
                            "<li class=\"current\">".$f->formText( "page_title", "", $m["page_title"] )."</li>";
						}
			  //  $this->outputSubMenu();
                    }
                    else {
						if ($m['ID'] !=24  && $m['ID'] !=29 && $m['ID'] !=34 && $m['ID'] !=35 && $m['ID'] !=36){//dont show profile and new pages
							$content .=
								"<li class=\"current\"><a href=\"".$url.$m["friendly_url"]."\" title=\"\" class=\"current\"><span>".$m["page_title"]."</span></a>" . $extra .
                            "</li>";
						}
                    }
                } else {
                    if ( $page->parentID == $m["ID"] ) {
	                    $content .= "<li class=\"current\"><a href=\"".$url.$m["friendly_url"]."\" title=\"\" class=\"current current2\"><span>".$m["page_title"]."</span></a>".$extra;
                        $sub = $this->_getMenu( $page->parentID );
                        if ( is_array( $sub ) ) {
                            $content .= "<ul>";
                            foreach( $sub as $s ) {
                                if ( $_GET["p"] == $s["friendly_url"] ) {
                                    if ( $mode == "edit" ) {
                                        $f = new CFForm();
                                        $content .=
                                            "<li>".$f->formText( "page_title", "", $s["page_title"] )."</li>";
                                    }
                                    else {
					$_GET["p"] == $s["friendly_url"] ? $checked = "class=\"current\"" : $checked = "";
                                        $content .=
                                            "<li><a href=\"/".$s["friendly_url"]."\" title=\"\" ".$checked."><span>".$s["page_title"]."</span></a></li>";
                                    }
                                } else {
                                    $content .= "<li><a href=\"/".$s["friendly_url"]."\" title=\"\"><span>".$s["page_title"]."</span></a></li>";
                                }
                            }
                            $content .= "</ul>";
                        }
                    } else {
	
						if ($m['ID'] ==15){//is a project request page;
							$ITEM = new kingsItems();
							if (!$ITEM->doRequestExl()){
								$m["page_title"]= $m["page_title"].' <span style="color:red">!</span>';
							}

						}
						if ($m['ID'] !=24  && $m['ID'] !=29 && $m['ID'] !=34 && $m['ID'] !=35 && $m['ID'] !=36){//dont show profile and new pages
							$content .= "<li><a href=\"".$url.$m["friendly_url"]."\" title=\"\"><span>".$m["page_title"]."</span></a>".$extra;
						}
					}
                    $content .= "</li>";
                }
            }
            $content .= "
                </ul>
            ";
        }
		
        return $content;
    }

    protected function _getMenu( $parent=0 ) {
        $query = "
            SELECT
                ID,
                friendly_url,
                page_title
            FROM
                pages
            WHERE
                parentID = '".$parent."'
            ORDER BY
                display_order;
        ";
        $result = mysql_query( $query );
        while( $m = mysql_fetch_assoc( $result ) ) {
            $menu[] = $m;
        }	
        return $menu;
    }

    protected function _getPageIDs() {
        $query = "
            SELECT
                ID,
                parentID
            FROM
                pages
            WHERE
                friendly_url = '".$_GET["p"]."'
            LIMIT 1;
        ";
        $p = mysql_fetch_object( mysql_query( $query ) );
        return $p;
    }

// Private Methods



}

?>