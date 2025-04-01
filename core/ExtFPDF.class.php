<?php
/*******************************************************************************
*
*  FPDF Extension Class 
*
*  This class extends the standard FPDF Class to include handling elements like
*  bullets and numbering etc.
*
*******************************************************************************/

class ExtFPDF extends FPDF
{
 
    // Set  A Default Font Size.
    var $size = 10;
 
    // Modified Version of the Tutorial Code for the HTML parser on the FPDF
    // website.
    var $B = 0;
    var $I = 0;
    var $U = 0;
    var $HREF = '';
    var $ul_pad = 10;

    function WriteHeading( $heading, $size="20", $align="L", $style="B", $font="") {
        $this->SetFont( $font, $style, $size );
        $this->MultiCell( 0, ( $size / 2 ), $heading, 0, $align );
        $this->Ln( $this->size / 2 );
        $this->SetFontNormal();
    }
 
    function SetFontNormal() {
        $this->SetFont( '', '', $this->size );
    }
    
    function WritePara( $text, $single="N", $size="" ) {
        $size == "" ? $size = $this->size : $size = $size;
        $this->SetFontSize( $size );
        //$this->MultiCell( 0, ( $size / 2 ), $text );
        $this->ParseHTML( $text );
        if ( $single == "N" ) {
            $this->Ln( $this->size / 2 );
            $this->Ln( $this->size / 2 );
        }
    }

    function ParseHTML ( $text ) {
        //HTML parser
        $html = str_replace( "\n", ' ', $text );
        $a = preg_split( '/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
        foreach( $a as $i=>$e ) {
            if( $i % 2 == 0) {
                //Text
                if( $this->HREF ) {
                    $this->PutLink( $this->HREF, $e);
                } else {
                    $this->Write( ( $this->size / 2 ), $e);
                }
            } else {
                //Tag
                if($e{0}=='/') {
                    $this->CloseTag( strtoupper( substr( $e, 1) ) );
                } else {
                    //Extract attributes
                    $a2 = explode( ' ', $e );
                    $tag = strtoupper( array_shift( $a2 ) );
                    $attr = array();
                    foreach( $a2 as $v ) {
                        if( ereg('^([^=]*)=["\']?([^"\']*)["\']?$', $v, $a3) ) {
                           $attr[strtoupper($a3[1])] = $a3[2];
                        }
                    }
                $this->OpenTag( $tag, $attr );
                }
            }
        }
    }
    

/* Lists Functions */

    function WriteUlPara( $text, $single="N", $size="" ) {
        $size == "" ? $size = $this->size : $size = $size;
        $this->SetFontSize( $size );
        //$this->MultiCell( 0, ( $size / 2 ), $text );
        $this->ParseUlHTML( $text );
        if ( $single == "N" ) {
            $this->Ln( $this->size / 2 );
            $this->Ln( $this->size / 2 );
        }
    }

    function ParseUlHTML ( $text ) {
        //HTML parser
        $html = str_replace( "\n", ' ', $text );
        $a = preg_split( '/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
        foreach( $a as $i=>$e ) {
            if( $i % 2 == 0) {
                //Text
                if( $this->HREF ) {
                    $this->PutLink( $this->HREF, $e);
                } else {
                    $this->UlWrite( ( $this->size / 2 ), $e);
                }
            } else {
                //Tag
                if($e{0}=='/') {
                    $this->CloseTag( strtoupper( substr( $e, 1) ) );
                } else {
                    //Extract attributes
                    $a2 = explode( ' ', $e );
                    $tag = strtoupper( array_shift( $a2 ) );
                    $attr = array();
                    foreach( $a2 as $v ) {
                        if( ereg('^([^=]*)=["\']?([^"\']*)["\']?$', $v, $a3) ) {
                           $attr[strtoupper($a3[1])] = $a3[2];
                        }
                    }
                    $this->OpenTag( $tag, $attr );
                }
            }
        }
    }
    function WriteUL( $options, $single="N" ) {
        if ( is_array( $options ) ) {
            foreach ( $options as $option ) {
                // $this->SetX( $indent );
                $this->WriteUlPara( "•        ".$option, $single );
            }
        }
    }
    
    function WriteOL( $options, $single="N" ) {
        if ( is_array( $options ) ) {
            $i = 1;
            foreach ( $options as $option ) {
                // $this->SetX( $indent );
                $this->WriteUlPara( $i.".      ".$option, $single );
                $i++;
            }
        }
    }
    
    function UlWrite($h,$txt,$link='') {
        //Output text in flowing mode
        $cw=&$this->CurrentFont['cw'];
        $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb) {
            //Get next character
            $c=$s{$i};
            if($c=="\n") {
                //Explicit line break
                $this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                if($nl==1) {
                    $this->x=$this->lMargin + $$this->ul_pad;
                    $w=$this->w-$this->rMargin-$this->x;
                    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
                }
                $nl++;
                continue;
            }
            if($c==' ')
            	$sep=$i;
            $l+=$cw[$c];
            if($l>$wmax) {
                //Automatic line break
                if($sep==-1) {
                    if($this->x>$this->lMargin) {
                        //Move to next line
                        $this->x=$this->lMargin + $this->ul_pad;
                        $this->y+=$h;
                        $w=$this->w-$this->rMargin-$this->x;
                        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
                        $i++;
                        $nl++;
                        continue;
                    }
                    if($i==$j)
                    	$i++;
                    $this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
                } else {
                    $this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                if($nl==1) {
                    $this->x=$this->lMargin + $this->ul_pad;
                    $w=$this->w-$this->rMargin-$this->x;
                    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
                }
                $nl++;
            }
            else
            	$i++;
        }
        //Last chunk
        if($i!=$j)
        	$this->Cell($l/1000*$this->FontSize,$h,substr($s,$j),0,0,'',0,$link);
    }


// Modified Version of the Tutorial Code for the HTML parser on the FPDF
    // website (and all functions below).
    function OpenTag( $tag, $attr ) {
        //Opening tag
        if ( $tag=='B' or $tag=='I' or $tag=='U' )
            $this->SetStyle( $tag, true );
        if( $tag=='A' )
            $this->HREF=$attr['HREF'];
        if( $tag=='BR' )
            $this->Ln( ( $this->size / 2 ) );
    }

    function CloseTag($tag) {
        //Closing tag
        if( $tag=='B' or $tag=='I' or $tag=='U' )
            $this->SetStyle( $tag, false );
        if( $tag=='A' )
            $this->HREF='';
    }

    function SetStyle( $tag,$enable ) {
        //Modify style and select corresponding font
        $this->$tag+=( $enable ? 1 : -1 );
        $style='';
        foreach( array('B','I','U') as $s )
            if( $this->$s > 0 )
                $style.=$s;
        $enable ? $this->SetFont( '', $style ) : $this->SetFont( '', '' );
        // $this->SetFont( '', $style );
    }

    function PutLink( $URL, $txt ) {
        //Put a hyperlink
        $this->SetTextColor( 0, 0, 255);
        $this->SetStyle( 'U', true);
        $this->Write( ( $this->size / 2 ), $txt, $URL );
        $this->SetStyle( 'U', false );
        $this->SetTextColor( 0 );
    }
}

?>