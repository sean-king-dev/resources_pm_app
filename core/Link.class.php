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
class Link {
	
	public function __construct() {
	}
	
// -----------------------------------------------------------------------------

/**
 *  Summarises a passage of text
 *
 *  @param String 	$text 	The text to be summarised
 *  @param Int 		$length The length of the summary (it will not break a work)
 */
    public function outputLink( $url, $title, $text, $rel="", $other="" ) {
        $url = " href=\"".$url."\"";
        $title = " title=\"".$title."\"";
        $text
        
        $link = "<a hre
    }
	
// -----------------------------------------------------------------------------

}




?>