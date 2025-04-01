<?php

/**
 * The core file class
 *
 * File with the core information about the File class
 *
 * LICENSE: GPL v.3
 *
 * @copyright   2008 Demoncheese Designs / Preview Graphics
 * @license     http://www.demoncheese.co.uk/license/license.txt
 * @version     1.0
 * @link        http://www.demoncheese.co.uk/core/File.class.php
 * @since       File available since 1.0
 */

 
/**
 * This creates output of valid (x)html standards
 *
 * Allows easy creation of valid (x)html code using a variety of
 * functions assiting in best practise principals.
 *
 * @author      Philip Cole
 * @copyright   2008 Demoncheese Designs / Preview Graphics
 * @license     http://www.demoncheese.co.uk/license/license.txt
 * @version     1.0
 * @link        http://www.demoncheese.co.uk/core/File.class.php
 * @since       File available since 1.0
 */

class File {

    // Public Variables
    
    // Protected Variables
    protected $filename;
    protected $filepath = "/";
    protected $overwrite = "N";
    protected $destFilename;
    protected $destFilepath;
    
    // Private Variables
    
/**
 *  Sets the page encoding for the webpage object
 *  
 *  @param String $enc The encoding type for the page default is "UTF-8"
 */
    public function __construct( $name, $path="" ) {
        $this->filename = $name;
        $path == "" ? $this->filepath = $path : NULL;
    }
    
/**
 *  Sets the page encoding for the webpage object
 *  
 *  @param String $enc The encoding type for the page default is "UTF-8"
 */
    public function moveFile( $dest, $name="" ) {
        $this->destFilepath = $dest;
        $name != "" ? $this->destFilename = $name : $this->destFilename = $this->filename;
        if ( $this->overwrite == "Y" ) {
            rename( $_SERVER["DOCUMENT_ROOT"].$this->filepath.$this->filename, $_SERVER["DOCUMENT_ROOT"].$dest.$newFilename );
        } else {
            $fileExists == FALSE;
            do {
                
            } while( $fileExists === FALSE );
        }
    }

/**
 *  Sets the page encoding for the webpage object
 *  
 *  @param String $enc The encoding type for the page default is "UTF-8"
 */
    public function overrideOverwrite( $overwrite ) {
        $overwrite == "Y" ? $this->overwrite = "Y" : $this->overwrite = "N";
    }

/**
 *  Sets the page encoding for the webpage object
 *  
 *  @param String $enc The encoding type for the page default is "UTF-8"
 */
    public function getUniqueFilename() {
        
    }


}


?>