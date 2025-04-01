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

class NewsReader {
	
	// PUBLIC VARIABLES
	public $news_headlines;					// Array of the news headlines
	
	// PROTECTED VARIABLES
	protected $num_headlines = 5;			// Number of Headlines / Headline Stories to display
	protected $headline_story_type = "p";	// How the headline story is stored
											// f is a separate field, p is the first paragraph
	protected $news_full = "news.php";		// Page where the full news stories can be read
	protected $news_query = "s";			// Variable for the query string
	protected $para_break = "p";			// What is used to break paragraphs
											// p is <p> tags, b is 2 <br> tags, l is /r/n line break
	protected $summary_limit = 20;


	// All $d_ variables relate to the data contained in the associated field
	// These should be standard across all default news stories - anything different will have
	// to be specifically set in the extension classes
	protected $d_newsID = "ID";
	protected $d_headline = "headline";
	protected $d_news_date = "news_date";
	protected $d_headline_story = "headline_story";
	protected $d_headline_image = "headline_image";
	protected $d_headline_imagealt = "headline_imagealt";
	protected $d_full_story = "full_story";
	protected $d_status = "statusID";
	
	
	// PRIVATE VARIABLES
	
	public function __contruct() {
	}

	public function getHeadlines() {
		// This should be in the DatabaseNewsReader, FileNewsReader, ArrayNewsReader, RSSNewsReader extension classes.
	}

	protected function getSummary( $full_story="", $limit="" ) {
		$full_story == "" ? $full_story = $this->full_story : NULL;
		$limit == "" ? $limit = $this->summary_limit : NULL;
	    $summary = "";
	    $words = 0;
	    $tok = strtok( $full_story, ' ' );
	    while( $tok ) {
	        $summary .= " ".$tok."";
	        $words++;
	         if( ( $words >= $limit ) && ( ( substr( $tok, -1 ) == '!') || ( substr( $tok, -1 ) == '.' ) ) )
	            break;
	        $tok = strtok( " " );
	    }
	    return ltrim( $summary );
	}


}




?>