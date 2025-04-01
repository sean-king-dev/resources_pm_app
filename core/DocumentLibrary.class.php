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
    protected $documentLibraryID;       // Array of all the current document information
    protected $libraryDocuments;        // Array of documents in the library
    // Private Variables
    
    public function __construct() {
    }

// ----------------------------------------------------------------------------

    public function returnDocumentLibraryID() {
        return $this->documentLibraryID;
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function getLibraryDocuments( $id ) {
        $query = "SELECT ID, filename, description, filetype, filesize FROM documents WHERE libraryID = ".$id." LIMIT 1;";
        $result = mysql_query( $query );
        while( $doc = mysql_fetch_assoc( $result ) ) {
            $libraryDocuments[] = $doc;
        }
        return $libraryDocuments;
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    protected function setLibraryDocuments() {
        $this->libraryDocuments = $this->getLibraryDocuments( $this->documentLibraryID );
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function overrideLibraryDocuments( $id ) {
        $this->libraryDocuments = $this->getLibraryDocuments( $id );
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

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function outputSelectList( $current="", $name="" ) {
        !isset( $this->libraryDocuments ) ? $this->setLibraryDocuments() : NULL;
        $name == "" ? $name = "DL_documents" : NULL;
        if ( is_array( $this->libraryDocuments ) ) {
            foreach( $this->libraryDocuments as $docs ) {
                $options[] = array( $docs["ID"]=>$docs["filename"] );
            }
        }
        $output = Form2::baseGetInputSelect( $name, $options, $current, "Please select" );
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
    public function a() {
        
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function b() {
        
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function c() {
        
    }

// ----------------------------------------------------------------------------

/**
 *  Description
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function d() {
        
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