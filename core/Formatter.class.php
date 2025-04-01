<?php

/**
 * The core formatter class
 *
 * File with the core information about the Formatter class
 *
 * LICENSE: GPL v.3
 *
 * @copyright   2008 Demoncheese Designs / Preview Graphics
 * @license     http://www.demoncheese.co.uk/license/license.txt
 * @version     1.0
 * @link        http://www.demoncheese.co.uk/core/formatter.class.php
 * @since       File available since 1.0
 */

 
/**
 * This formats text in certain ways
 *
 * Allows easy paragraphing of text, summaries etc.
 * These methods are designed to be called externally.
 *
 * @author      Philip Cole
 * @copyright   2008 Demoncheese Designs / Preview Graphics
 * @license     http://www.demoncheese.co.uk/license/license.txt
 * @version     1.0
 * @link        http://www.demoncheese.co.uk/core/formatter.class.php
 * @since       File available since 1.0
 */
class Formatter {
	
	public function __construct() {
	}
	
// -----------------------------------------------------------------------------

/**
 *  Summarises a passage of text
 *
 *  @param String 	$text 	The text to be summarised
 *  @param Int 		$length The length of the summary (it will not break a work)
 */
    public function summarise( $text, $length="150" ) {
        $sum = $text;
        if ( strlen( $text ) > $length ) {
            $sum = substr( $text, 0, $length );
			$ssum = strrpos( $sum, " " );
			$sum = substr( $sum, 0, $ssum );
            $sum = $sum."...";
        }
        return $sum;
    }
	
// -----------------------------------------------------------------------------
	
/**
 *  Convert standard line breaks into paragraphs
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function paraText( $text, $others="" ) {
        $others == "" ? $ptag = "<p>" : $ptag = "<p ".$others.">";
        $text = nl2br( $text );
        $text = str_replace( "<br>", "<br />", $text );
        $text = str_replace( "<BR>", "<br />", $text );
        $text = str_replace( "<br />", "<br />", $text );
        $text = str_replace( "<br /><br />", "</p>".$ptag, $text );
        $text = $ptag."\r\n\t".$text."\r\n</p>";
        return $text;
    }

// -----------------------------------------------------------------------------
	
/**
 *  Get the first paragraph
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function firstPara( $text ) {
        $tdata = explode( "</p>", $text );
        $text = $tdata[0]."</p>";
        return $text;
    }

// -----------------------------------------------------------------------------
	
/**
 *  Highlighter
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function highlight( $text, $word ) {
		$keywords = preg_replace('/\s\s+/', ' ', strip_tags(trim($word))); // filter
		$text = str_ireplace( $keywords, "<span class='highlight'>".$keywords."</span>", $text );
        return $text;
    }

// -----------------------------------------------------------------------------
	
/**
 *  Standardise text - convert &'s etc
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function standardise( $text ) {
        $text = str_replace( " & ", " &amp; ", $text );
        // $text = str_replace( "&amp;amp;", "&amp;", $text );
        return $text;
    }

// -----------------------------------------------------------------------------
	
/**
 *  Format MySQL Date (YYYY-MM-DD or YYYY-MM-DD HH:MM:SS)
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function formatMySQLDate( $date, $format="" ) {
        $date = explode( " ", $date );
		if ( $date[1] != "" ) {
			$hours = substr( $date[1], 0, 2 );
			$mins = substr( $date[1], 3, 2  );
			$secs = substr( $date[1], 6, 2  );
		} else {
			$hours = 0;
			$mins = 0;
			$secs = 1;
		}
		$the_date = explode( "-", $date[0] );
		$datetime = mktime( $hour, $mins, $secs, $the_date[1], $the_date[2], $the_date[0] );
		switch( $format ){
			default:
				$formatted_date = date( "d/m/Y", $datetime );
				break;
		}
        return $formatted_date;
    }

// -----------------------------------------------------------------------------
	
/**
 * Return human readable sizes
 *
 * @param       int    $size        Size
 * @param       int    $unit        The maximum unit
 * @param       int    $retstring   The return string format
 * @param       int    $si          Whether to use SI prefixes
 */
	public function size_readable($size, $unit = null, $retstring = null, $si = true) {
		// Units
		if ($si === true) {
			$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
			$mod   = 1000;
		} else {
			$sizes = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
			$mod   = 1024;
		}
		$ii = count($sizes) - 1;
	 
		// Max unit
		$unit = array_search((string) $unit, $sizes);
		if ($unit === null || $unit === false) {
			$unit = $ii;
		}
	 
		// Return string
		if ($retstring === null) {
			$retstring = '%01.2f %s';
		}
	 
		// Loop
		$i = 0;
		while ($unit != $i && $size >= 1024 && $i < $ii) {
			$size /= $mod;
			$i++;
		}
	 
		return sprintf($retstring, $size, $sizes[$i]);
	}

// -----------------------------------------------------------------------------
	
/**
 *  Format A Filetype
 *
 *   @param
 *   @throws
 *   @returns
 */
    public function formatFileType( $format ) {
		$_SESSION["locale"] == "" ?  $lang = $_SESSION["language"] : $lang = $_SESSION["language"]."_".$_SESSION["locale"];
        $query = "
			SELECT
				icon,
				application_".$lang." AS application
			FROM
				filetypes
			WHERE
				filetype = '".$format."'
			LIMIT 1;
		";
		$f = mysql_fetch_object( mysql_query( $query ) );
		if ( $f->icon == "" ) {
			$icon = "unknown";
			$application = "Unknown";
		} else {
			$icon = $f->icon;
			$application = $f->application;
		}
		$icon = "<img src=\"/images/filetypes/".$icon.".png\" title=\"".$application."\">";
		return $icon;
    }


}




?>