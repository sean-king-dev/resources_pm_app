<?php

/* ************************************************************ */
/*                                                              */
/*  Core Function Library                                       */
/*                                                              */
/*  Class: Menu.class.php                                       */
/*                                                              */
/*  Author: Philip Cole                                         */
/*  Date:   28th June 2007                                      */
/*                                                              */
/*  This is the core menu class                                 */
/*                                                              */
/* ************************************************************ */
/*  Revision History                                            */
/*                                                              */
/*  2007-06-28: v0.1    Initial Creation                        */
/*                                                              */
/*                                                              */
/*                                                              */
/* ************************************************************ */

class Menu {
    
    // Public Variables
    
    // Protected Variables
    protected $menu;            // Main array holding the Menu
    protected $breadcrumb;      // Main array holding the Breadcrumb
    
    // Private Variables
    
    
    
    public function __contruct() {
    }
    
/* *****************************************************************************************************
*
*       MENU methods
*
***************************************************************************************************** */


    public function overrideMenu( $menu ) {
        $this->pageMenu = $menu;
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *  input format is array( page=>url, subpages=>array( page=>'url', title=>'title', subpages=>array( page=>url ) ) );
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function createPageList( $list, $page, $full="Y" ) {
        print "<br />";
        $pid = 1;
        $i = 0;
        $parent = "N";
        $subparent = "N";
        $grandparent = "N";
        foreach( $list as $p ) {
            $sid = 0;
            $isp = false;
            if ( is_array( $p["subpages"] ) ) {
                $parent = "Y";
                $sid = $pid + 1;
                foreach( $p["subpages"] as $s ) {
                    $tid = 0;
                    $iss = false;
                    if ( is_array( $s["subpages"] ) ) {
                        $tid = $sid;
                        $subparent = "Y";
                        $grandparent = "Y";
                        $k = 1;
                        foreach( $s["subpages"] as $t ) {
                            $tid++;
                            $t["page"] == $page ? $current = "Y" : $current = "N";
                            $subsubmenu[] = array( "ID"=>$tid, "parentID"=>$sid, "url"=>$t["page"], "title"=>$t["title"], "current"=>$current, "parent"=>"N", "grandparent"=>"N" );
$i++;
print "Output ".$i.": LEVEL 3 --- pid -> ".$pid." --- sid -> ".$sid." --- <b>TID -> ".$tid."</b> --- ".$t["page"]."<br />";

                        }
                    }
                    
                    $s["page"] == $page ? $current = "Y" : $current = "N";
                    $submenu[] = array( "ID"=>$sid, "parentID"=>$pid, "url"=>$s["page"], "title"=>$s["title"], "current"=>$current, "parent"=>$subparent, "grandparent"=>"N" );
$i++;

print "Output ".$i.": LEVEL 2 --- pid -> ".$pid." --- <b>SID -> ".$sid."</b> --- tid -> ".$tid." --- ".$s["page"]."<br />";
                    $tid == 0 ? $sid++ : $sid = $tid + 1;
                }
            }
            $p["page"] == $page ? $current = "Y" : $current = "N";
            
                $menu[] = array( "ID"=>$pid, "parentID"=>"0", "url"=>$p["page"], "title"=>$p["title"], "current"=>$current, "parent"=>$parent, "grandparent"=>$grandparent );
                $i++;
            
print "Output ".$i.": LEVEL 1 --- <b>PID -> ".$pid."</b> --- sid -> ".$sid." --- tid -> ".$tid." --- ".$p["page"]."<br />";
            if( $tid != 0 ) {
                $pid = $tid + 1;
                $tid = 0;
                $sid = 0;
            } elseif( $sid != 0 ) {
                $pid = $sid + 1;
                $sid = 0;
            } else {
                $pid++;
            }
        }

        $the_menu = array( "menu"=>$menu, "submenu"=>$submenu, "subsubmenu"=>$subsubmenu );
        
        return $the_menu;
    }


// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function setMenu() {
        $this->pageMenu == "" ? $this->getMenu() : NULL;
        $grandparentID = $this->checkGrandParent( $this->parentID );
        if ( $grandparentID == 0 ) {
            if ( $this->parentID == 0 ) {
                $this->pageSubMenu = $this->getMenu( $this->pageID, "1" );
            } else {
                $this->pageSubMenu = $this->getMenu( $this->parentID, "2" );
                $this->pageSubSubMenu = $this->getMenu( $this->pageID, "2" );
            }
        } else {
            $this->pageSubMenu = $this->getMenu( $grandparentID, "3" );
            $this->pageSubSubMenu = $this->getMenu( $this->parentID, "3" );
        }
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function isSubSubMenu( $id ) {
        $query = "SELECT parentID FROM pages WHERE ID = ".$this->pageID." LIMIT 1;";
        $parID = mysql_result( mysql_query( $query ), 0 );
        $parID == $id ? $is = true : $is = false;
        return $is;
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function checkGrandParent( $parentID ) {
        $query = "SELECT parentID FROM pages WHERE ID = ".$parentID." LIMIT 1";
        $grandparentID = mysql_fetch_object( mysql_query( $query ) );
        return $grandparentID->parentID;
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function genMenuSubmenuList() {
        $i = 1;
        $j = 1;
        $output = "
            <div id=\"menu\">
            <ul id=\"MM_main\">";
        foreach( $this->pageMenu as $m ) {
            $output .= "
                ".( $i != 1 ? "<div class=\"notfirst\">&nbsp;</div>" : "" )."
                <li class=\"\">
                <div class=\"".( $m["current"] == "Y" ? "current " : "" )."\">
                    <a href=\"/".$m["url"]."\" title=\"View the ".$m["title"]." section\">".$m["title"]."</a>
                </div>";
            if ( $m["current"] == "Y" || $m["parent"] == "Y" || $m["grandparent"] == "Y" ) {
                if ( is_array( $this->pageSubMenu ) ) {
                $output .= "
                    <div class=\"notfirst_sub\">&nbsp;</div>
                    <ul id=\"MM_sub\">";
                    foreach( $this->pageSubMenu as $sm ) {
                        // Check for third level pages - keep highlighted if on a third level subpage
                        if ( $m["current"] != "Y" && $sm["current"] != "Y" ) {
                            $this->isSubSubMenu( $sm["ID"] ) ? $sm["current"] = "Y" : NULL;
                        }
                        $output .= "
                            ".( $j != 1 ? "<div class=\"notfirst\">&nbsp;</div>" : "" )." 
                            <div class=\"".( $sm["current"] == "Y" ? "current " : "" )."\">
                            <li class=\"\"><a href=\"/".$sm["url"]."\" title=\"View the ".$sm["title"]." section\">".$sm["title"]."</a></div></li>";
                        $j++;
                    }
                $output .= "</ul>";
                }
            }
            $output .= "</li>";
            $i++;
        }
        $output .= "</ul>
            </div>";
        return $output;
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function getBreadcrumb() {
        $this->pageMenu == "" ? $this->setMenu() : NULL;
        if ( $this->pageUrl != "home" ) {
            foreach ( $this->pageMenu as $m ) {
                $m["current"] == "Y" ? $this->breadcrumbPage = " / ".$m["title"] : NULL;
                if ( $m["parent"] == "Y" || $m["grandparent"] == "Y" ) {
                    $this->breadcrumbPage = " / <a href=\"/".$m["url"]."\" title=\"Back to the Criterion Partnership ".$m["title"]." page\">".$m["title"]."</a>";
                    foreach ( $this->pageSubMenu as $sm ) {
                        $sm["current"] == "Y" ? $this->breadcrumbSubPage = " / ".$sm["title"] : NULL;
                        if ( $sm["parent"] == "Y" ) {
                            $this->breadcrumbSubPage = " / <a href=\"/".$sm["url"]."\" title=\"Back to the Criterion Partnership ".$sm["title"]." page\">".$sm["title"]."</a>";
                            foreach ( $this->pageSubSubMenu as $ssm ) {
                            $ssm["current"] == "Y" ? $this->breadcrumbSubSubPage = " / ".$ssm["title"] : NULL;
                            }
                        }
                    }
                }
            }
        }
    }
    
// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function setBreadCrumbHome( $bc ) {
        $this->breadcrumbHome = $bc;
    }
    
// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function setBreadCrumbPage( $bc ) {
        $this->breadcrumbPage = $bc;
    }
    
// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function setBreadCrumbSubPage( $bc ) {
        $this->breadcrumbSubPage = $bc;
    }
    
// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function setBreadCrumbSubSubPage( $bc ) {
        $this->breadcrumbSubSubPage = $bc;
    }
    
// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function outputBreadCrumb() {
        $this->breadcrumbPage == "" ? $this->getBreadcrumb() : NULL;
        if ( $this->pageUrl != "home" ) {
            print "
            <div id=\"breadcrumb\">
            <span>
                ".$this->breadcrumbHome."
                ".$this->breadcrumbPage."
                ".$this->breadcrumbSubPage."
                ".$this->breadcrumbSubSubPage."
            </span>
            </div>";
        } else {
            print "<div id=\"nobreadcrumb\">&nbsp;</div>";
        }
        
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function outputMenu() {
        // $this->pageMenu == "" ? $this->setMenu() : NULL;
        print $this->genMenuSubmenuList();
    }
    
   
}




?>