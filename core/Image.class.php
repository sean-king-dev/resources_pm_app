<?php

/* ************************************************************ */
/*                                                              */
/*  Core Function Library                                       */
/*                                                              */
/*  Class: Image.class.php                                      */
/*                                                              */
/*  Author: Philip Cole                                         */
/*  Date:   2nd July 2007                                       */
/*                                                              */
/*  This is the core image class                                */
/*                                                              */
/* ************************************************************ */
/*  Revision History                                            */
/*                                                              */
/*  2007-07-02: v0.1    Initial Creation                        */
/*                                                              */
/*                                                              */
/*                                                              */
/* ************************************************************ */

class Image {

    // Public Varibles
    
    
    // Protected Variables
    protected $image;
    protected $imagePath = "/images/";
    protected $imageBasePath;
    protected $altText;
    protected $height;
    protected $width;
    
    protected $if;              // Input File image reference
    protected $of;              // Output file image reference
    
    protected $rWidth = 1;      // Width Aspect Ratio
    protected $rHeight = 1;     // Height Apsect Ratio
    
    protected $maxHW;           // Maximum height of width of an image
    
    protected $nHeight;         // New image height - often calculated
    protected $nWidth;          // New image width - often calculated
    
    protected $imgStyle;
    protected $imgClass;
    protected $imgID;
    protected $IDImages = true;
    
    protected $watermarkImage;
    protected $watermarkPosition;
    protected $watermarkComplete = false;
    
    protected $copyrightText;
    protected $copyrightFont;
    
    protected $doScale           = "N";
    protected $doWatermark       = "N";
    protected $doCopyright       = "N";
    
    protected $mimeType;
    protected $imgType;
    
    protected $prefix;
    
    protected $quality = 90;
    
    
    // Private Variables
    

    // The construct function takes an image and sets the initial variables.
    // It can also take a blank entry to allow for the creation of a new image
    public function __construct( $image="" ) {
        if ( $image != "" ) {
            $this->image = $image;
        }
        $this->setBasePath();
    }

    protected function initImage() {
        $img_data = getimagesize( $this->imageBasePath.$this->image );
        $this->width = $img_data[0];
        $this->height = $img_data[1];
        $this->nWidth = $this->width;
        $this->nHeight = $this->height;
        $this->setAspectRatio();
        $this->imgType = $this->getImageType( $img_data[2] );
        $this->mimeType = $img_data["mime"];
/*
            $this->image = $this->imageBasePath.$image;
            $this->altText = $alt;
            if ( $this->IDImages == true ) {
                $img_data = explode( ".", $image );
                $this->imgID = "img_".$img_data[0];
            }
*/
    }

    protected function setBasePath( $path="" ) {
        if ( $path != "" )
            $this->imageBasePath = $path;
        elseif ( $GLOBALS["loc_images"] != "" )
            $this->imageBasePath = $GLOBALS["loc_images"];
        else
            $this->imageBasePath = $_SERVER["DOCUMENT_ROOT"];
    }

    public function getBasePath() {
        $this->setBasePath();
        return $this->imageBasePath;
    }

    public function overrideBasePath( $path ) {
        $this->imageBasePath = $path;
    }

    public function setImage( $image ) {
        $this->image = $image;
        $this->initImage();
    }

    protected function setAspectRatio() {
        // The largest aspect should always be set to 1
        if ( $this->width > $this->height ) {
            $this->rHeight = $this->height / $this->width;
            $this->maxHW = $this->width;
        } elseif ( $this->height > $this->width ) {
            $this->rWidth = $this->width / $this->height;
            $this->maxHW = $this->height;
        }
    }

    public function setMaxHW( $max="" ) {
        $max != "" ? $this->maxHW = $max : NULL;
    }
    
    public function setHW( $width, $height ) {
        $this->nWidth = $width;
        $this->nHeight = $height;
    }

    protected function setNewImageSize() {
        $this->maxHW > 0 ? $this->setNewImageSizeMax() : $this->setNewImageSizeHW();
    }

    // Sets the output size so that the maximum length is equal to maxHW
    protected function setNewImageSizeMax() {
        $this->nHeight = $this->maxHW * $this->rHeight;
        $this->nWidth = $this->maxHW * $this->rWidth;
    }

    // Sets the output height and width to be the same size as the input sizes
    protected function setNewImageSizeHW() {
        $this->nHeight = $this->height;
        $this->nWidth = $this->width;
    }

    public function resizeImageWithThumbManual( $size, $thumbsize, $prefix ) {
        $this->setMaxHW( $thumbsize );
        $this->resizeImage( $prefix );
        $this->setMaxHW( $size );
        $this->resizeImage();
    }
    
    public function resampleImage() {
        $this->createFrom();
        $this->doScale == "Y" ? $this->setNewImageSize() : NULL;
        $this->of = ImageCreateTrueColor( $this->nWidth, $this->nHeight );
        $this->doScale == "Y" ? ImageCopyResampled( $this->of, $this->if, 0, 0, 0, 0, $this->nWidth, $this->nHeight, $this->width, $this->height ) : $this->of = $this->if;
        $this->doWatermark == "Y" ? $this->addWatermarkImage() : NULL;
        $this->doCopyright == "Y" ? $this->addCopyright() : NULL;
        $this->saveImage();
        $this->cleanUp();
    }

    public function overrideDoScale( $opt ) {
        $this->doScale = $opt;
    }

    public function overrideDoWatermark( $opt ) {
        $this->doWatermark = $opt;
    }

    public function overrideDoCopyright( $opt ) {
        $this->doCopyright = $opt;
    }

    public function resizeImage( $prefix="", $thumb="N" ) {
        $this->prefix = $prefix;
        $this->createFrom();
        $this->setNewImageSize();
        // Create the new image
        $this->of = ImageCreateTrueColor( $this->nWidth, $this->nHeight );
        if ( $thumb == "Y" && $this->imgType == "png" ) {
            imagealphablending($this->of, false);
            $colorTransparent = imagecolorallocatealpha($this->of, 15, 254, 21, 127);
            imagefill($this->of, 0, 0, $colorTransparent);
            imagesavealpha($this->of, true);
        }
        ImageCopyResampled( $this->of, $this->if, 0, 0, 0, 0, $this->nWidth, $this->nHeight, $this->width, $this->height );
        $this->saveImage();
        $this->cleanUp();
    }

    public function watermarkImageOnly() {
        $this->createFrom();
        // Create the new image
        $this->of = ImageCreateTrueColor( $this->nWidth, $this->nHeight );
        $this->of = $this->if;
        $this->addWatermarkImage();
        $this->saveImage();
        $this->cleanUp();
    }

    public function copyrightImageOnly() {
        $this->createFrom();
        // Create the new image
        $this->of = ImageCreateTrueColor( $this->nWidth, $this->nHeight );
        $this->of = $this->if;
        $this->addCopyright();
        $this->saveImage();
        $this->cleanUp();
    }

    public function overrideCopyrightText( $text ) {
        $this->copyrightText = $text;
    }

    public function overrideCopyrightFont( $font ) {
        $this->copyrightFont = $font;
    }

    protected function addCopyright() {
        // Create a black image 20px higher than the photo
        $im_black = ImageCreateTrueColor( $this->nWidth, ( $this->nHeight + 20 ) );
        $im_black_black = ImageColorAllocate( $im_black, 0, 0, 0 );
        
        // Merge the photo with the black image
        ImageCopy( $im_black, $this->of, 0, 0, 0, 0, $this->nWidth, $this->nHeight );

        // Create the text on the image
        $im_text_grey = ImageColorAllocate( $im_black, 153, 153 ,153 );
        ImageTTFText( $im_black, 11, 0, 5, $this->nHeight + 15, $im_text_grey, $_SERVER["DOCUMENT_ROOT"]."/fonts/".$this->copyrightFont, $this->copyrightText );
        $this->of = $im_black;
    }


	// createNew - creates a new image based on the sizes given
	// if no size is set it will take the default for the class;
	protected function createNew( $h="", $w="", $truecolour="Y" ) {
		if ( $truecolour == "Y" ) {
			$this->if = ImageCreateTrueColor( $this->nWidth, $this->nHeight );
		} else {
			NULL;
		}
	}
	
	// createFrom - takes a file and creates an image reference from it
	protected function createFrom() {
		if ( ! isset( $this->image ) ) {
			return false;
		} else {
			switch( $this->imgType ) {
				case "jpg":
				case "jpeg":
					$this->if = ImageCreateFromJPEG( $this->imageBasePath.$this->image );
				break;
			
				case "png":
					$this->if = ImageCreateFromPNG( $this->imageBasePath.$this->image );
				break;
			
				case "gif":
					$this->if = ImageCreateFromGIF( $this->imageBasePath.$this->image );
				break;
				// Can add new cases later if needed
			}
		}
	}

    protected function addWatermarkImage() {
        $watermark = imagecreatefrompng( $_SERVER["DOCUMENT_ROOT"]."images/".$this->watermarkImage );  
        $watermark_width = imagesx($watermark);  
        $watermark_height = imagesy($watermark);
        switch( substr( $this->watermarkPosition, 0, 1 ) ) {
            case "t":
                $dest_y = 0;  
                break;
            case "m":
                $dest_y = ( $this->nHeight - $watermark_height ) / 2;  
                break;
            case "b":
                $dest_y = $this->nHeight - $watermark_height;  
                break;
        }
        switch( substr( $this->watermarkPosition, 1, 1 ) ) {
            case "l":
                $dest_x = 0;  
                break;
            case "m":
                $dest_x = ( $this->nWidth - $watermark_width ) / 2;  
                break;
            case "r":
                $dest_x = $this->nWidth - $watermark_width;  
                break;
        }
        imagecopy($this->of, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);  
    }


    protected function getImageType( $type ) {
        $types = array( 'gif', 'jpg', 'png', 'swf', 'psd', 'bmp', 'tiff(intel byte order)', 'tiff(motorola byte order)', 'jpc', 'jp2', 'jpx', 'jbc', 'swc', 'iff', 'wbmp', 'xbm');
        return $types[$type - 1];
    }

    public function overrideWatermarkImage( $image ) {
        $this->watermarkImage = $image;
    }
    
    public function overrideWatermarkPosition( $pos ) {
        $this->watermarkPosition = $pos;
    }
    
    protected function saveImage() {
		switch( $this->imgType ) {
			case "jpg":
			case "jpeg":
				ImageJPEG( $this->of, $this->imageBasePath.$this->prefix.$this->image, $this->quality );
			break;
		
			case "png":
				ImagePNG( $this->of, $this->imageBasePath.$this->prefix.$this->image );
			break;
		
			case "gif":
				ImageGIF( $this->of, $this->imageBasePath.$this->prefix.$this->image );
			break;
			// Can add new cases later if needed
		}
    }
   
    protected function cleanUp() {
        imagedestroy( $this->if );
        // imagedestroy( $this->of );
    }
   
   
    public function overrideQuality( $quality ) {
        $this->quality = $quality;
    }
   
    
    
    
    function setStyle( $style ) {
        $this->imgStyle = $style;
    }
    
    function setClass( $class ) {
        $this->imgClass = $class;
    }
    
    function setID( $id ) {
        $this->imgID = $id;
    }
    
    function setCustomScript( $script ) {
        $this->customScript = $script;
    }
    
    
    
    
    function outputImage() {
        // Check to see if there are any additional settings for the image.
        isset( $this->imgStyle ) ? $style = "style=\"".$this->imgStyle."\"" : NULL;
        isset( $this->imgClass ) ? $class = "class=\"".$this->imgClass."\"" : NULL;
        isset( $this->imgID ) ? $id = "id=\"".$this->imgID."\"" : NULL;
        
        print "<img src=\"".$this->image."\" alt=\"".$this->altText."\" height=\"".$this->height."\" width=\"".$this->width."\" ".$style." ".$class." ".$id.">";
    }

}


?>