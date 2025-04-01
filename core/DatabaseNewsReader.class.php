<?php

/* ************************************************************ */
/*                                                              */
/*  Core Function Library                                       */
/*                                                              */
/*  Class: NewsReader.class.php                                 */
/*                                                              */
/*  Author: Philip Cole                                         */
/*  Date:   10th July 2007                                      */
/*                                                              */
/*  This is the core newsreader class                           */
/* 	This is designed to facilitate the easy display of news		*/
/*	stories based on predefined settings - these can be easily	*/
/* 	overriden with an extension class if required				*/
/*                                                              */
/* ************************************************************ */
/*  Revision History                                            */
/*                                                              */
/*  2007-07-10: v0.1    Initial Creation                        */
/*                                                              */
/*                                                              */
/*                                                              */
/* ************************************************************ */

class DatabaseNewsReader extends NewsReader {
	
	// PUBLIC VARIABLES
	
	// PROTECTED VARIABLES
	protected $news_data = "news";		// Default data to use for news stories
	protected $view_statusID = 1;

	// Default source information variables - these will be the fields in the database for the
	// default system but should be set for the relevant input source when required.
	// All $f_ variables relate to a source field for data
	protected $f_newsID = "ID";
	protected $f_headline = "headline";
	protected $f_news_date = "news_date";
	protected $f_headline_story = "headline_story";
	protected $f_headline_image = "headline_image";
	protected $f_headline_imagealt = "headline_imagealt";
	protected $f_full_story = "full_story";
	protected $f_status = "status_codeID";
	
	
	// PRIVATE VARIABLES
	
	public function __contruct() {
		parent::__contruct;
	}


	public function getHeadlines( $num="", $filter="DEFAULT" ) {
		$num != "" ? $this->num_headlines = $num : NULL;
		$this->headline_story_type == "f" ? $headline_story = $this->f_headline_story : $headline_story = $this->f_full_story;
		if ( $filter == "DEFAULT" )
			$sql_filter = "WHERE ".$this->f_status." = ".$this->view_statusID;
		elseif ( $filter != "" )
			$sql_filter = "WHERE ".$filter;
		else
			$sql_filter = "";
		$query = "
			SELECT
				".$this->f_newsID.",
				".$this->f_headline.",
				".$this->f_news_date.",
				".$headline_story." AS ".$this->f_headline_story.",
				".$this->f_headline_image.",
				".$this->f_headline_imagealt.",
				".$this->f_full_story.",
				".$this->f_status."
			FROM
				".$this->news_data."
			".$sql_filter."
			ORDER BY
				".$this->f_news_date." DESC
			LIMIT ".$this->num_headlines.";";
		$result = mysql_query( $query );
		while( $story = mysql_fetch_array( $result ) ) {
			// If the headline story is the first para of the full story then we need to extract it.
			$this->headline_story_type == "p" ?	$headline_story = $this->getSummary( $story[$this->f_headline_story] ) : $headline_story = $story[$this->f_headline_story];
			$this->news_headlines[] = array( $this->d_newsID=>$story[$this->f_newsID], $this->d_headline=>$story[$this->f_headline], $this->d_news_date=>$story[$this->f_news_date], $this->d_headline_story=>$headline_story, $this->d_headline_image=>$story[$this->f_headline_image], $this->d_headline_imagealt=>$story[$this->f_headline_imagealt], $this->d_status=>$story[$this->f_status] );
		}
		
	}
	
}




?>