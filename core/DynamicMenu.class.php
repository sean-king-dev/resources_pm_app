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

class DynamicMenu extends Menu {
    
    public function __contruct() {
        parent::__contruct();
    }
    
/* *****************************************************************************************************
*
*       MENU methods
*
***************************************************************************************************** */

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function getMenu( $id=0, $lvl=1 ) {
        // Menu
        $GLOBALS["admin"] == "Y" ? $page_type = "A" : $page_type = "N";
        $query = "SELECT ID, parentID, friendly_url, page_title FROM pages WHERE parentID = '".$id."' AND page_type = '".$page_type."' ORDER BY display_order, ID;";
        $result = mysql_query( $query );
        while( $m = mysql_fetch_object( $result ) ) {
            $m->ID == $this->pageID ? $current = "Y" : $current = "N";
            $m->ID == $this->parentID ? $parent = "Y" : $parent = "N";
            if ( $m->parentID != 0 ) {
                $grandparentID = $this->checkGrandParent( $m->parentID );
                $grandparentID > 0 ? $this->pageMenu[$grandparentID]["grandparent"] = "Y" : NULL;
            }
            $menu[$m->ID] = array( "ID"=>$m->ID, "parentID"=>$m->parentID, "url"=>$m->friendly_url, "title"=>$m->page_title, "current"=>$current, "parent"=>$parent, "grandparent"=>"N" );
        }
        $this->pageLevel = $lvl;
        return $menu;
    }

// ----------------------------------------------------------------------------

    public function overrideMenu( $menu ) {
        $this->pageMenu = $menu;
    }

// ----------------------------------------------------------------------------

    public function returnMenu( $parentID ) {
        $menu = $this->getMenu( $parentID );
        return $menu;
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
        $query = "SELECT parentID FROM pages WHERE ID = '".$parentID."' LIMIT 1";
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
        $this->pageMenu == "" ? $this->setMenu() : NULL;
        // print $this->genMenuSubmenuList();
    }
    

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */

    protected function getParentChild( $id ) {
        // Set the current level to be zero.
        $current_level = 0;
        
        // Get the info on the page that is currently selected
        $query = "SELECT parentID, title FROM pages WHERE ID = '".$id."' LIMIT 1;";
        $result = mysql_fetch_object( mysql_query( $query ) );

        // Put the information in the temporary array at the current level
        $temp_array[$current_level] = array( "ID"=>$id, "parentID"=>$result->parentID, "title"=>$result->title, "level"=>$curlevel );

        // Set the parentID to be used to loop through the higher level pages
        $parentID = $result->parentID;
    
        while( $parentID != 0 ) {
            // Subtract one from the new level (as we are going backwards
            $current_level--;
            
            // Get the page's parent
            $query = "SELECT ID, parentID, title FROM pages WHERE ID = '".$parentID."' LIMIT 1;";
            $result = mysql_fetch_object( mysql_query( $query ) );
        
            // Add the parent to the new level
            $temp_array[$current_level] = array( "ID"=>$result->ID, "parentID"=>$result->parentID, "title"=>$result->title, "level"=>$current_level );

            // Set the parentID to run through the loop again.
            $parentID = $result->parentID;
        }
        
        // Calculate the number of levels we have added
        $levels = count( $temp_array );
        
        // Sort the array as it will probably have negative index numbers
        asort( $temp_array );
    
        // Loop through the array and reset the index to start at 0
        foreach ( $temp_array as $ta ) {
            $level = $ta["level"] = $ta["level"] + $levels;
            $pc_array[$level] = $ta;
        }
    
        // Check to see if there is a child item to list
        $query = "SELECT ID, parentID, title FROM pages WHERE parentID = '".$id."' LIMIT 1;";
        $result = mysql_fetch_object( mysql_query( $query ) );
        $level += 1;
        if ( $result->ID != "" ) {
            $pc_array[] = array( "ID"=>$result->ID, "parentID"=>$result->parentID, "title"=>$result->title, "level"=>$level );
        }
        
        return $pc_array;
    }
    
    public function returnParentChild( $id ) {
        $pc = $this->getParentChild( $id );
        return $pc;
    }
   
}




?>