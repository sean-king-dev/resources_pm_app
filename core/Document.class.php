<?php

/* ************************************************************ */
/*                                                              */
/*  Core Function Library                                       */
/*                                                              */
/*  Class: Document.class.php                                   */
/*                                                              */
/*  Author: Philip Cole                                         */
/*  Date:   4th April 2008                                      */
/*                                                              */
/*  This is the core document class                             */
/*                                                              */
/* ************************************************************ */
/*  Revision History                                            */
/*                                                              */
/*  2008-04-04: v0.1    Initial Creation                        */
/*                                                              */
/*                                                              */
/*                                                              */
/* ************************************************************ */

class Document {

    // Public Variables
    
    // Protected Variables
    protected $documentID;      // The current document ID
    protected $document;        // Array of all the current document information
    
    // Private Variables
    
    public function __construct( $id ) {
        $this->documentID = $id;
    }

// ----------------------------------------------------------------------------

    public function returnDocumentID() {
        return $this->documentID;
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function getDocumentData() {
        $query = "SELECT libraryID, filename, description, filetype, filesize FROM documents WHERE ID = ".$this->documentID." LIMIT 1;";
        $document = mysql_fetch_assoc( mysql_query( $query ) );
        return $document;
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function getDocumentTags() {
        $query = "SELECT ";
    }





/*
    // Public Varibles
    public $libraryTitle = "";
    
    // Protected Variables
    protected $images;
    protected $libraryID = 0;
    protected $libraries; 
    protected $canUpload = "Y";
    
    // Private Variables
    

    // The construct function takes an image and sets the initial variables.
    // It can also take a blank entry to allow for the creation of a new image
    public function __construct() {
    }
    
    // Set Functions
    public function setImageLibrary( $library ) {
        $this->libraryID = $library;
        $query = "SELECT library, path FROM image_libraries WHERE ID = ".$library." LIMIT 1;";
        $lib = mysql_fetch_object( mysql_query( $query ) );
        $lib->library == "" ? $this->libraryTitle = "All Images" : $this->libraryTitle = $lib->library;
        $this->libraryPath = $lib->path;
    }
    
    
    public function displayOpts() {
        print "The image location is: ".$GLOBALS["loc_images"];
    }
    
    
    // Get Functions
    protected function getImages() {
        $this->libraryID == 0 ? $query = "SELECT ID, filename, alt_text, filetype, filesize FROM images ORDER BY filename;" : $query = "SELECT ID, filename, alt_text, filetype, filesize FROM images WHERE libraryID = ".$this->libraryID." ORDER BY filename;";
        print $query;
        $result = mysql_query( $query );
        while( $i = mysql_fetch_object( $result ) ) {
            $images[] = array( "ID"=>$i->ID, "filename"=>$i->filename, "altText"=>$i->alt_text, "filetype"=>$i->filetype, "filesize"=>$i->filesize );
        }
        $this->images = $images;
    }
    
    protected function getLibraries() {
        $query = "SELECT ID, library FROM image_libraries ORDER BY display_order;";
        $result = mysql_query( $query );
        while( $l = mysql_fetch_object( $result ) ) {
            $libraries[] = array( "ID"=>$l->ID, "library"=>$l->library );
        }
        $this->libraries = $libraries;
    }
    
    protected function getImageOptions() {
        // Pseudo Method to run a number of other methods
    }
    
    
    // Display Functions
    
    
    
    // Output Functions
    public function outputImageLibrary() {
        $this->getLibraries();
        $_GET["p"] >= 1 ? $this->setImageLibrary( $_GET["p"] ) : $this->setImageLibrary( "0" );
        $this->getImages();
        print "
            <div id=\"IL_imageLibrary\">
                <h2 id=\"IL_imageLibraryTitle\">".$this->libraryTitle."</h2>
                <div id=\"IL_imageWindow\">";
        
        if ( is_array( $this->images ) ) {
            foreach( $this->images as $image ) {
                print "
                    <div id=\"IL_imageGroup\">
                        <div id=\"IL_image\">
                            <img src=\"".$GLOBALS["loc_images"].$this->libraryPath."/".$image["filename"]."\" alt=\"".$image["altText"]."\" width=\"100\" />
                        </div>
                        <div id=\"IL_imageAlt\">
                            <input type=\"checkbox\" name=\"frm_images[]\" value=\"".$this->images["ID"]."\" /> ".$image["altText"]."
                        </div>
                    </div>";
            }
        } else {
            print "
                    <p>No images uploaded</p>";
        }
        print "
                </div>";
        is_array( $this->libraries ) ? $this->outputLibrarySelect() : NULL;
        $this->canUpload == "Y" ? $this->outputUploadImage() : NULL;
        print "
            </div>
        ";
    }

    protected function outputLibrarySelect() {
        print "
                <div id=\"IL_selectLibrary\">
                <h3>Library:</h3>
                <ul>
                    <li><a href=\"/admin/0\" title=\"All Images\">All Images</a></li>";
        foreach ( $this->libraries as $lib ) {
            print "
                    <li><a href=\"/admin/".$lib["ID"]."\" title=\"".$lib["library"]."\">".$lib["library"]."</a></li>";
        }
        print "
                </ul>
                </div>";
    }
    
    protected function outputUploadImage() {
        print "
                <div id=\"IL_addImage\">
                ";
                $f = new Form2();
                $f->outputImageUpload();
        print "
                </div>";
    }
    
*/  
}


?>