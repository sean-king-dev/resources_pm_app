<?php

/**
 * The core webpage class
 *
 * File with the core information about the Webpage class
 *
 * LICENSE: GPL v.3
 *
 * @copyright   2008 Demoncheese Designs / Preview Graphics
 * @license     http://www.demoncheese.co.uk/license/license.txt
 * @version     1.0
 * @link        http://www.demoncheese.co.uk/core/webpage.php
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
 * @link        http://www.demoncheese.co.uk/core/webpage.php
 * @since       File available since 1.0
 */
class Webpage {
    
    // Define Public Variables
    
    
    // Define Protected Variables
    protected $xmlDeclaration;
    protected $docType;
    protected $htmlOpenTag;
    protected $siteTitle;
    protected $site_title_suffix = " - Preview";

    protected $metaContentType;
    protected $metaLanguage;
    protected $metaDescription;
    protected $metaKeywords;
    protected $metaOwner;
    protected $metaAuthor;
    protected $metaCopyright;
    protected $metaCache;
    protected $metaTarget;
    protected $metaMS;
    protected $metaRobots;

    protected $favIcon;
    
    protected $htmlCloseTag = "</html>\n";

    
    protected $page;
    protected $subpage;
    protected $encoding;
    protected $language;
    
    protected $stylePath = "/style/";
    protected $attachedStyles;
    protected $includedStyles;
    
    protected $scriptPath = "/includes/";
    protected $attachedScripts;
    protected $includedScripts;
    
    protected $session_redir = "/";
    protected $baseURL;

    protected $debug = array();
   
    // Define Private Variables
    private $contentType;
    


// ----------------------------------------------------------------------------
// PUBLIC FUNCTIONS
// ----------------------------------------------------------------------------

    public function __construct() {
        $_SESSION["bugs"] = "";
    }


    public function attachScript( $script, $path="" ) {
        isset( $this->scriptPath ) && $path == "" ? $the_path = $this->scriptPath : $the_path = $path; 
        $this->attachedScripts[] = $the_path.$script;
    }

    // $cond is to allow additiona styles for things like IE6 fixes
    public function attachStyle( $styleSheet, $cond="" ) {
        $this->attachedStyles[] = array( "style"=>$styleSheet, "cond"=>$cond );
    }



    public function debug( $debug ) {
        $_SESSION["bugs"][] = $debug;
    }



    public function includeScript( $script ) {
        $this->includedScripts[] = $script;
    }

    public function includeStyle( $style ) {
        
    }




    public function outputAttachedScripts() {
        if ( is_array( $this->attachedScripts ) ) {
            foreach( $this->attachedScripts as $script ) {
                print "\t<script type=\"text/javascript\" src=\"".$script."\"></script>\n";
            }
            print "\n";
        }
    }
    
    public function outputAttachedStyles() {
        if ( is_array( $this->attachedStyles ) ) {
            foreach( $this->attachedStyles as $stylesheet ) {
                if ( $stylesheet["cond"] == "" ) {
                    print "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->stylePath.$stylesheet["style"]."\" />\n";
                } else {
                    switch( $stylesheet["cond"] ) {
                        case "ie":
                            strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? print "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->stylePath.$stylesheet["style"]."\" />\n" : NULL;
                        break;
                        case "ie6":
                            strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') ? print "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->stylePath.$stylesheet["style"]."\" />\n" : NULL;
                        break;
                    }
                }
            }
            print "\n";
        }
    }
    
    public function outputClose() {
        if ( $_SESSION["debug"] == "on" ) {
            print "
                <div id=\"debug\">
                <h1>Debug Information</h1>
                <h2>Session</h2>
                <pre>";
            print_r( $_SESSION );
            print "</pre>
                <h2>Post</h2>
                <pre>";
            print_r( $_POST );
            print "</pre>
                <h2>Get</h2>
                <pre>";
            print_r( $_GET );
            print "</pre>
                <h2>Files</h2>
                <pre>";
            print_r( $_FILES );
            print "</pre>
                </div>";
        }
        print $this->htmlCloseTag;
    }

    public function outputIncludedScripts() {
        if ( is_array( $this->includedScripts ) ) {
            foreach( $this->includedScripts as $script ) {
                print "\t<script language=\"javascript\" type=\"text/javascript\">".$script."</script>\n";
            }
            print "\n";
        }
    }
    
    public function outputMetaData() {
        $this->outputStandardMeta();
        $this->outputOwnerMeta();
        // $this->outputOtherMeta();
    }

    public function outputOwnerMeta() {
        isset( $this->metaOwner ) ? print $this->metaOwner : NULL;
        isset( $this->metaAuthor ) ? print $this->metaAuthor : NULL;
        isset( $this->metaCopyright ) ? print $this->metaCopyright : NULL;
            print "\n";
    }
    
    
// General method for outputting all the required components of the standard html headers
    public function outputStandardHtmlHeaders( $close="Y" ) {
        isset( $this->xmlDeclaration ) ? print $this->xmlDeclaration : NULL;
        print $this->docType;
        print $this->htmlOpenTag;
        print "<head>\n";
        print $this->siteTitle;
            print "\n";
    
        print $this->baseURL."\n\n";

        $this->outputMetaData();

        // Include FavIcon link
        isset( $this->favIcon ) ? print $this->favIcon : NULL;
        
        $this->outputAttachedStyles();
        $this->outputAttachedScripts();
        $this->outputIncludedScripts();

        $close == "Y" ? print "</head>\n" : NULL;
    }
    
    public function outputStandardMeta() {
        isset( $this->metaContentType ) ? print $this->metaContentType : NULL;
        isset( $this->metaLanguage ) ? print $this->metaLanguage : NULL;
        isset( $this->metaDescription ) ? print $this->metaDescription : NULL;
        isset( $this->metaKeywords ) ? print $this->metaKeywords : NULL;
        print "\n";
    }
    







    public function overrideBaseURL( $url ) {
        $this->_setBaseURL( $url );
    }
    
    public function overrideContentType( $type ) {
        $this->_setContentType( $type );
    }

    public function overrideDocType( $format, $type ) {
        $this->_setDocType( $format, $type );
    }
   
    public function overrideEncoding( $enc ) {
        $this->_setEncoding( $enc );
    }

    public function overrideLanguage( $lang ) {
        $this->_setLanguage( $lang );
    }

    public function overrideStylePath( $path ) {
        $this->stylePath = $path;
    }




/**
 *  Pseudo function to set the basic defaults for the encoding, language and doctypes
 *  Calls setEncoding, setLanguage and setDocType methods
 */
    public function setDefaults() {
        $this->_setEncoding();
        $this->_setLanguage();
        $this->_setDocType();
        $this->_setBaseURL();
    }
    
    public function setMetaAuthor( $author ) {
        if ( $author  != "" )
            $this->metaAuthor = "\t<meta name=\"Author\" content=\"".$author."\" />\n";
    }
    
    public function setMetaCache( $opt="no-cache" ) {
        $this->metaCache = "\t<meta http-equiv=\"Cache-Control\" content=\"no-cache\" />\n";
    }

    public function setMetaCopyright( $copy, $year="" ) {
        $year == "" ? $year = date( "Y" ) : NULL;
        $this->metaCopyright = "\t<meta name=\"Copyright\" content=\"".$year." ".$copy." \" />\n";
    }

    public function setMetaDescription( $desc ) {
        if ( $desc != "" )
            $this->metaDescription = "\t<meta name=\"Description\" content=\"".$desc."\" />\n";
    }

    public function setMetaKeywords( $keywords ) {
        if ( $keywords != "" )
            $this->metaKeywords = "\t<meta name=\"Keywords\" content=\"".$keywords."\" />\n";
    }

    public function setMetaOwner( $owner ) {
        if ( $owner != "" )
            $this->metaOwner = "\t<meta name=\"Owner\" content=\"".$owner."\" />\n";
    }

    public function setMetaTarget( $target="_top") {
        $this->metaTarget = "\t<meta http-equiv=\"Window-target\" content=\"".$target."\" />\n";
    }
    
    public function setMetaMS() {
        $this->metaMS = "\t<meta name=\"MSSmartTagsPreventParsing\" content=\"TRUE\" />\n";
    }

    public function setFavIcon( $path="/" ) {
        $this->favIcon = "\t<link rel=\"shortcut icon\" href=\"".$path."favicon.ico\" />\n";
    }

    public function setOwnerMeta( $author, $owner="", $copy="", $year="" ) {
        $this->setMetaAuthor( $author );
        $owner == "" ? $this->setMetaOwner( $author ) : $this->setMetaOwner( $owner );
        if ( $copy == "" ) {
            if ( $owner == "" )
                $this->setMetaCopyright( $author );
            else
                $this->setMetaCopyright( $owner );
        } else 
            $this->setMetaCopyright( $copy );
    }
    
    // Options are: all | none | index | noindex | follow | nofollow
    public function setRobots( $option="all" ) {
        $this->metaRobots = "\t<meta name=\"Robots\" content=\"".$option."\" />\n";
    }

    public function setSiteTitle( $title ) {
        if( $title != "" )
            $this->siteTitle = "\t<title>".$title.$this->site_title_suffix."</title>\n";
    }
    
    public function setStandardMeta( $desc="", $keywords="" ) {
        $this->_setMetaContentType();
        $this->_setMetaLanguage();
        $this->setMetaDescription( $desc );
        $this->setMetaKeywords( $keywords );
    }





    
    public function validateSession() {
        !isset( $_SESSION["username"] ) || !isset( $_SESSION["userID"] ) ? header( "location: ".$this->session_redir ) : NULL;
    }



// ----------------------------------------------------------------------------
// PROTECTED FUNCTIONS
// ----------------------------------------------------------------------------



/**
 *  Sets the BaseURL for the site to be the domain it is in
 */
    protected function _setBaseURL( $url="host" ) {
        switch( $url ) {
            case "host":
                $this->baseURL = "\t<base href=\"http://".$_SERVER["HTTP_HOST"]."\" />";
                break;
            case "":
                $this->baseURL = "";
                break;
            default:
                $this->baseURL = "\t<base href=\"http://".$url."\" />";
                break;
        }
    }
    
/**
 *  Sets the content type for the document
 *  
 *   @param string $type The content type for the page
 */
    protected function _setContentType( $type ) {
        $this->contentType = $type;
    }

/**
 *  Sets the doctype for the page.  Creates the correct opening tags and content type.
 *  Requires that setLanguage and setEncoding are set first - will call the set function if not
 *
 *   @param string $format The format of the page (html / xhtml) 
 *   @param string $type The format of the doctype (strict, transitional, frameset)
 */
    protected function _setDocType( $format="x", $type="s" ) {
        !isset( $this->encoding ) ? $this->_setEncoding() : NULL;
        !isset( $this->language ) ? $this->_setLanguage() : NULL;
        switch( $format ) {
            // HTML 4.01
            default:
            case "h":
                switch( $type ) {
                    // Strict
                    case "s":
                        $this->docType = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n";
                    break;
                    // Transitional
                    default:
                    case "t":
                        $this->docType = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
                    break;
                    // Frameset
                    case "f":
                        $this->docType = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">\n";
                    break;
                }
                $this->htmlOpenTag = "<html lang=\"".$this->language."\">\n";
                $this->_setContentType( "text/html" );
            break;
            
            // XHTML 1.0
            case "x":
                switch( $type ) {
                    // Strict
                    case "s":
                        $this->docType = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
                    break;
                    // Transitional
                    case "t":
                        $this->docType = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
                    break;
                    // 
                    case "f":
                        $this->docType = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">\n";
                    break;
                }
                $this->xmlDeclaration = "";
                $this->htmlOpenTag = "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"".$this->language."\" lang=\"".$this->language."\">\n";
                $this->_setContentType( "application/xhtml+xml" );
            break;
        }
    }


/**
 *  Sets the page encoding for the webpage object
 *  
 *  @param String $enc The encoding type for the page default is "UTF-8"
 */
    protected function _setEncoding( $enc="UTF-8" ) {
        $this->encoding = $enc;
    }

/**
 *  Sets the language for the webpage
 *
 *  @param String $lang The language for the webpage - default is "en"
 */
    protected function _setLanguage( $lang="en" ) {
        $this->language = $lang;
    }

/**
 *  Sets the meta content type tag based on the encoding type
 */
    protected function _setMetaContentType() {
        !isset( $this->encoding ) ? $this->setEncoding() : NULL;
        $this->metaContentType = "\t<meta http-equiv=\"Content-Type\" content=\"".$this->contentType."\; charset=".$this->encoding."\" />\n";
    }

   
/**
 *  Sets the meta content-language tag based on the language set
 */
    protected function _setMetaLanguage() {
        $this->metaLanguage = "\t<meta http-equiv=\"Content-Language\" content=\"".$this->language."\" />\n";
    }


// ----------------------------------------------------------------------------
// PRIVATE FUNCTIONS
// ----------------------------------------------------------------------------



}




?>