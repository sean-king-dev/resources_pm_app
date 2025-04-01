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
 * @link        http://www.demoncheese.co.uk/core/news.class.php
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
 * @link        http://www.demoncheese.co.uk/core/news.class.php
 * @since       File available since 1.0
 */
class News {
	
	protected $newsID;
	protected $headlines;
	protected $summaries;
	protected $story;

	protected $coreTable 	= "news";
	protected $lookupTable 	= "news";

	private $numHeadlines 	= 10;
	private $numSummaries 	= 5;
	private $currentPos 	= 0;
	private $summarySize	= 200;
	
	
	private $displayField 	= "status";
	private $displayStatus	= 1;
	private $displayBy		= "article_date DESC";


/**
 *  Convert standard line breaks into paragraphs
 *
 *   @param
 *   @throws
 *   @returns
 */
	public function __construct() {
	}
	
// -----------------------------------------------------------------------------
// OVERRIDE FUNCTIONS	

	final public function overrideNumHeadlines( $num ) {
		$this->numHeadlines = $num;
	}

	final public function overrideNumSummaries( $num ) {
		$this->numSummaries = $num;
	}

	final public function overrideDisplayField( $field ) {
		$this->displayField = $field;
	}

	final public function overrideDisplayStatus( $status ) {
		$this->displayStatus = $status;
	}
	
	final public function overrideDisplayBy( $by ) {
		$this->displayBy = $by;
	}
	
	final public function overrideSummarySize( $size ) {
		$this->summarySize = $size;
	}
	
	final public function overrideCoreTable( $table ) {
		$this->coreTable = $table;
	}
	
	final public function overrideLookupTable( $table ) {
		$this->lookupTable = $table;
	}
	
	final public function setCurrentPos( $pos ) {
		$this->currentPos = $pos;
	}
	
	final protected function clearHeadlines() {
		$this->headlines = "";
	}
	
	final protected function clearSummaries() {
		$this->summaries = "";
	}
	
// -----------------------------------------------------------------------------
	
	

	
	protected function getHeadlines() {
		$this->clearHeadlines();
		$query = "SELECT ID, title, article_date FROM ".$this->coreTable." WHERE ".$this->displayField." = '".$this->displayStatus."' AND article_date <= NOW() ORDER BY ".$this->displayBy." LIMIT ".$this->currentPos.", ".$this->numHeadlines.";";
		$result = mysql_query( $query );
		while( $h = mysql_fetch_assoc( $result ) ) {
			$this->headlines[] = $h;
		}
	}
	
// -----------------------------------------------------------------------------
	
	public function returnHeadlines() {
		$this->getHeadlines();
		return $this->headlines;
	}
	
// -----------------------------------------------------------------------------
	
	protected function getSummaries() {
		$this->clearSummaries();
		$query = "SELECT ID, title, article_date, content FROM ".$this->coreTable." WHERE ".$this->displayField." = '".$this->displayStatus."' AND article_date <= NOW() ORDER BY article_date DESC LIMIT ".$this->currentPos.", ".$this->numSummaries.";";
		$result = mysql_query( $query );
		while( $s = mysql_fetch_object( $result ) ) {
			$content = Formatter::summarise( strip_tags( $s->content ), $this->summarySize );
			$this->summaries[] = array( "ID"=>$s->ID, "title"=>$s->title, "article_date"=>$s->article_date, "content"=>$content );
		}
	}
	
// -----------------------------------------------------------------------------
	
	public function returnSummaries() {
		$this->getSummaries();
		return $this->summaries;
	}
	
// -----------------------------------------------------------------------------
	
	protected function getRelatedHeadlines( $parent, $parentID ) {
		$this->clearHeadlines();
		$query = "SELECT map.ID, core.title, core.article_date FROM ".$parent."_".$this->lookupTable."_map map INNER JOIN ".$this->coreTable." core ON core.ID = map.".$this->lookupTable."ID WHERE map.".$parent."ID = ".$parentID." AND core.".$this->displayField."='".$this->displayStatus."' ORDER BY map.display_order, core.article_date DESC;";
		$result = mysql_query( $query );
		while( $h = mysql_fetch_assoc( $result ) ) {
			$this->headlines[] = $h;
		}
	}

// -----------------------------------------------------------------------------
	
	public function returnRelatedHeadlines( $parent, $parentID ) {
		$this->getRelatedHeadlines( $parent, $parentID );
		return $this->headlines;
	}
	
// -----------------------------------------------------------------------------
	
	protected function getRelatedSummaries( $parent, $parentID ) {
		$this->clearSummaries();
		$query = "SELECT map.ID, core.title, core.article_date, core.content FROM ".$parent."_".$this->lookupTable."_map map INNER JOIN ".$this->coreTable." core ON core.ID = map.".$this->lookupTable."ID WHERE map.".$parent."ID = ".$parentID." AND core.".$this->displayField."='".$this->displayStatus."' ORDER BY map.display_order, core.article_date DESC;";
		$result = mysql_query( $query );
		while( $s = mysql_fetch_object( $result ) ) {
			$content = Formatter::summarise( $s->content, $this->summarySize );
			$this->summaries[] = array( "ID"=>$s->ID, "title"=>$s->title, "article_date"=>$s->article_date, "content"=>$content );
		}
	}

// -----------------------------------------------------------------------------
	
	public function returnRelatedSummaries( $parent, $parentID ) {
		$this->getRelatedSummaries( $parent, $parentID );
		return $this->summaries;
	}

}




?>