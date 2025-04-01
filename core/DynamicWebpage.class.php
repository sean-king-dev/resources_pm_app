<?php

/**
 * Dynamic extension to the webpage class
 *
 * File with the core information about the Dynamic extension to the
 * Webpage class
 *
 * LICENSE: GPL v.3
 *
 * @copyright   2008 Demoncheese Designs / Preview Graphics
 * @license     http://www.demoncheese.co.uk/license/license.txt
 * @version     1.0
 * @link        http://www.demoncheese.co.uk/core/DynamicWebpage.class.php
 * @since       File available since 1.0
 */

 
/**
 * This allows quicker dynamic creation of webpages
 *
 * Allows easy creation of dynamic database based websites
 *
 * @author      Philip Cole
 * @copyright   2008 Demoncheese Designs / Preview Graphics
 * @license     http://www.demoncheese.co.uk/license/license.txt
 * @version     1.0
 * @link        http://www.demoncheese.co.uk/core/DynamicWebpage.class.php
 * @since       File available since 1.0
 */
class DynamicWebpage extends Webpage {
    
    // Define New Variables
    // Core Variables
    protected $pageID;              // The current Page's ID value
    protected $pageTypeID;          // The type of page - page, news, case_study, team etc.
    protected $pageUrl;             // The current Page's url - gathered from the entered URL
    protected $parentID;            // The current Page's parent ID
    protected $pageLevel;           // The current Page's level - 1 -top, 2 - sub, 3 - subsub

    // Page Variables
    protected $pageTitle;           // The title for the page - H1 element
    protected $pageContent;         // The combined page content area for the page.

    protected $pageMenu;            // The top level menu items
    protected $pageSubMenu;         // The sublevel menu items
    protected $pageSubSubMenu;      // The third level menu items
    
    // Breadcrumb variables
    protected $breadcrumbHome       = "<a href=\"/home\" title=\"Back to the home page\">Home</a>";
    protected $breadcrumbPage;
    protected $breadcrumbSubPage;
    protected $breadcrumbSubSubPage;
    protected $breadcrumbDivider    = " :: ";

    protected $pageErrors;          // Any error messages for the page (Array)
    protected $pageMessages;        // Any non error messages for the page (Array)

    // URL Settings
    protected $pathHomePage         = "";       // The friendly URL of the home page
    protected $pathAdmin            = "admin/";     // The path to the administration area
    protected $pathAdminPage        = "admin";      // The friendly URL of the admin section
    protected $pathPrimary          = "p";          // The primary GET variable
    protected $pathSecondary        = "s";          // The secondary GET variable
    protected $pathTertiary         = "t";          // The tertiary GET variable

// ----------------------------------------------------------------------------
// PUBLIC FUNCTIONS
// ----------------------------------------------------------------------------



    public function __construct() {
        parent::__construct();
        $this->_initPage();
    }



    public function outputContent() {
        $this->pageContent == "" ? $this->_getPageContent() : NULL;
        print "
            <div id=\"content\">
                ".$this->pageContent."
            </div>";
    }

    public function outputFooter() {
        // This should be custom set in the client extension class
    }

    public function outputHeader() {
        // This should be custom set in the client extension class
    }
    
    public function outputPageTitle( $id="", $class="" ) {
        $id != "" ? $id = "id=\"".$id."/" : NULL;
        $class != "" ? $class = "class=\"".$class."/" : NULL;
        return "<h1>".$this->pageTitle."</h1>";
    }




    public function overridePageTitle( $title ) {
        $this->pageTitle = $title;
    }




    public function returnPageID() {
        return $this->pageID;
    }

    public function returnPageUrl() {
        return $this->pageUrl;
    }

    public function returnParentID() {
        return $this->parentID;
    }
    
    public function returnPageTitle() {
        return $this->pageTitle;
    }
    


// ----------------------------------------------------------------------------
// PROTECTED FUNCTIONS
// ----------------------------------------------------------------------------


    protected function _getImages( $type ) {
        $query = "SELECT pim.ID as pimID, pim.pageID AS pageID, i.ID as imageID, i.filename, i.alt_text, pim.image_url, pim.image_url_desc FROM page_image_map pim INNER JOIN images i ON i.ID = pim.imageID WHERE pim.pageID = ".$this->pageID." AND i.image_type = '".$type."' ORDER BY pim.display_order;";
        $result = mysql_query( $query );
        while( $i = mysql_fetch_object( $result ) ) {
            if( stripos( $i->image_url, "://" ) > 0 ) {
                $url = $i->image_url;
                $url_desc = $i->image_url_desc;
            } elseif( $i->image_url != "" ) {
                $query = "SELECT page_title, friendly_url FROM pages WHERE ID = ".$i->image_url." LIMIT 1;";
                $p = mysql_fetch_object( mysql_query( $query ) );
                $url = $p->friendly_url;
                $url_desc = $p->page_title;
            }
            $images[] = array( "filename"=>$i->filename, "alt"=>$i->alt_text, "url"=>$url, "url_desc"=>$url_desc, "pimID"=>$i->pimID, "pageID"=>$i->pageID, "imageID"=>$i->imageID );
        }
        return $images;
    }
 
 
 
    protected function _getPageContent() {
        $query = "SELECT page_title, browser_title, meta_description, meta_keywords, title, content, editable FROM pages WHERE ID = '".$this->pageID."' LIMIT 1;";
        $p = mysql_fetch_object( mysql_query( $query ) );
        
        // Set Header Information
        $this->setSiteTitle( $p->browser_title.SITE_TITLE_SUFFIX );
        $this->setMetaDescription( $p->meta_description );
        $this->setMetaKeywords( $p->meta_keywords );
        
        // Set Page Title
        $this->setPageTitle( $p->title );
        $this->pageContent = $this->outputPageTitle();
        
        // Set Main Content
        //$this->pageContent .= $p->content;
        if ( $p->editable != "X" && $this->isAdmin() ) {
            if ( $_GET["s"] == "edit" ) {
                $this->pageContent .= "
                    <form action=\"".$this->pageUrl."\" method=\"POST\">
                        <input type=\"hidden\" name=\"page_action\" value=\"update_contents\">
                        <textarea name=\"contents\">
                            ".$p->content."
                        </textarea>
                        <input type=\"submit\" class=\"submit\" value=\"Save Changes\">
                    </form>
                ";
            } else {
                $this->pageContent .= $p->content."<p><a href=\"".$this->pageUrl."/edit\" title=\"Edit this page\">[edit]</a></p>";
            }
        } else {
            $this->pageContent .= $p->content;
        }
    }
    
    protected function _getPageLayout() {
        $query = "SELECT pl.layout FROM pages p LEFT JOIN page_layouts pl ON p.page_layoutID = pl.ID WHERE p.ID = '".$this->pageID."' LIMIT 1;";
        $p = mysql_fetch_object( mysql_query( $query ) );
        return $p->layout;
    }

    protected function _getPageTitle() {
        $query = "SELECT page_title FROM pages WHERE ID = ".$this->pageID." LIMIT 1;";
        $p = mysql_fetch_object( mysql_query( $query ) );
        $this->pageTitle = $p->page_title;
    }

/**
 *  Gets the current page from the path that the user has entered.
 *  This uses a .htacess file to correctly navigate around the site though the
 *  settings of the 
 */
    protected function _setPage() {
        // Check to see if is admin first
        if ( strstr( $_SERVER["REQUEST_URI"], $this->pathAdmin ) && $_GET[$this->pathPrimary] == "" ) {
            $path = $this->pathAdminPage;
        } elseif ( $_GET[$this->pathPrimary] == "" ) {
            $path = $this->pathHomePage;
        } else {
            $path = $_GET[$this->pathPrimary];
        }
        $this->pageUrl = $path;
    }

    protected function _setPageIDs() {
        $query = "SELECT ID, parentID, page_typeID FROM pages WHERE friendly_url = '".$this->pageUrl."' LIMIT 1;";

        $p = mysql_fetch_object( mysql_query( $query ) );
        
        $this->pageID = $p->ID;
        $this->parentID = $p->parentID;
        $this->pageTypeID = $p->page_typeID;
        $this->editable = $p->editable;
    }


    protected function _setPageTitle( $title ) {
        if ( $title != "" )
            $this->pageTitle = $title;
    }

    
   
// ----------------------------------------------------------------------------
// PRIVATE FUNCTIONS
// ----------------------------------------------------------------------------



    private function _initPage() {
        $this->_setPage();
        $this->_setPageIDs();
    }

























// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------


//              DO  NOT  USE  FUNCTIONS  BELOW  !!!


// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

    public function IGNORE_ANY_FUNCTIONS_BELOW_HERE() {
        
    }

    protected function _setPageLayout() {
        $this->pageLayout = $this->getPageLayout();
    }


/**
 *  This is separated out from the main initPage function so that content can
 *  be set before initialising the information
 */
    public function initPageContent() {
        $this->setMenu();
        $this->setPageContent();
    }

    protected function _getTertiaryPages() {
        if ( is_array( $this->pageSubSubMenu ) ) {
            foreach( $this->pageSubSubMenu as $ssm ) {
                $this->pageContent .= "<h3>".$ssm["title"]."</h3><p><a href=\"".$ssm["url"]."\" title=\"More information about ".$ssm["title"]."\">More information about ".$ssm["title"]."</a></p>";
            }
        }
    }

    protected function _getMenu( $id=0, $lvl=1 ) {
        // Menu
        $GLOBALS["admin"] == "Y" ? $page_type = "A" : $page_type = "N";
        $query = "SELECT ID, parentID, friendly_url, page_title FROM pages WHERE parentID = ".$id." AND page_type = '".$page_type."' ORDER BY ID;";
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

    protected function _setMenu() {
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

    protected function _isSubSubMenu( $id ) {
        $query = "SELECT parentID FROM pages WHERE ID = ".$this->pageID." LIMIT 1;";
        $parID = mysql_result( mysql_query( $query ), 0 );
        $parID == $id ? $is = true : $is = false;
        return $is;
    }

    protected function _checkGrandParent( $parentID ) {
        $query = "SELECT parentID FROM pages WHERE ID = ".$parentID." LIMIT 1";
        $grandparentID = mysql_fetch_object( mysql_query( $query ) );
        return $grandparentID->parentID;
    }

    protected function _genMenuSubmenuList() {
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

    protected function _getBreadcrumb() {
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
    
    protected function _setBreadCrumbHome( $bc ) {
        $this->breadcrumbHome = $bc;
    }
    
    protected function _setBreadCrumbPage( $bc ) {
        $this->breadcrumbPage = $bc;
    }
    
    protected function _setBreadCrumbSubPage( $bc ) {
        $this->breadcrumbSubPage = $bc;
    }
    
    protected function _setBreadCrumbSubSubPage( $bc ) {
        $this->breadcrumbSubSubPage = $bc;
    }
    
    // Pseudo method to call the correct method for the page
    protected function _setPageContent() {
        switch( $this->pageTypeID ) {
            case "1":
                break;
            case "2":
                break;
            case "3":
                break;
            case "4":
                break;
        }
    }

    public function outputMenu() {
        print $this->pageMenu;
    }


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

    public function encodeURL( $url ) {
        $url = urlencode( $url );
        return $url;
    }
    
    public function decodeURL( $url ) {
        $url = urldecode( $url );
        return $url;
    }

    public function isAdmin() {
        if ( $_SESSION["admin"] == sha1( $this->adminCode ) )
            return true;
        else
            return false;
    }

    public function setPageErrors( $errors ) {
        $this->pageErrors[] = $errors;
    }

    public function overrideMenu( $menu ) {
        $this->pageMenu = $menu;
    }
    public function returnPageLayout() {
        $this->pageLayout == "" ? $this->setPageLayout() : NULL;
        return $this->pageLayout;
    }
    
    public function outputPageLayout() {
        $this->pageLayout == "" ? $this->setPageLayout() : NULL;
        require_once( $_SERVER["DOCUMENT_ROOT"]."layouts/page/".$this->pageLayout.".layout.php" );
    }



}


?>