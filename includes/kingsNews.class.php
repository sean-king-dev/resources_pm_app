<?php

// Kelta News Class

class kingsNews extends News {

/* ************************************************************************************ */

// Public Variables

// Protected Variables

// Private Variables


/* ************************************************************************************ */

// Public Methods

	public function outputNews($title, $pagecontent="") {
        $content = "<h1>".$title."</h1>";
        
		if($_GET["s"] != "") {
			$content .= $this->outputNewsItem($_GET["p"]);
		}else {
            $content .= $pagecontent;
            if($_GET["p"] != "news-and-events")
			$content .= $this->outputNewsSummaries();
			
		}
		return $content;
	}
    
    public function outputUpcomingEventBlock() {
        $events = $this->_getNewsSummaries(2, "E");
        
        if(is_array($events)) {
            foreach($events as $e) {
                $eventlist .=
                    "<div class=\"right-item\">
                        <div class=\"eventimage\">
                            <img src=\"/images/news/".$e->image."\" alt=\"".$e->imagealt."\" />
                        </div>
                        
                        <p><a href=\"/events/".urlencode($e->friendlyurl)."\">".$e->title."</a></p>
                        <p>".strip_tags(substr($e->content, 0, 50))."... <a href=\"/".$url."/".urlencode($e->friendlyurl)."\" title=\"".$e->title."\">Read more
                        <br class=\"clearall\" />
                    </div>";
                        
            }
        }
        
        $content = "
            <div class=\"rightbox\">
                <h2>Upcoming Events</h2>
                <p>See our upcoming events - ranging from wine tasting and seminars to training days.</p>
                ".$eventlist."
            </div>";
        
        return $content;
    }
    
    public function outputEventSurveyRightBlock() {
        
        $content = "
            <div id=\"surveybox\" class=\"rightboxwrap\">
                <h2>Attendee Survey</h2>
                <div class=\"scrollable\" id=\"question_scroll\">
                    <div class=\"items\">
                        <div class=\"scrollitem topad\">
                            How informative did you find the seminar?
                        </div>
                        <div class=\"scrollitem topad\">
                            Keeping you updated on progress
                        </div>
                        <div class=\"scrollitem\">
                            Providing you with quality candidates that match your requirements
                        </div>
                        <div class=\"scrollitem topad\">
                            What is your name
                        </div>
                    </div>
                </div>
                <div class=\"scrollable\" id=\"answer_scroll\">
                    <div class=\"items\">
                        <div class=\"scrollitem\">
                        &nbsp;
                        </div>
                        <div class=\"scrollitem\">
                            89% of our clients attending found the seminar informative to a good or excellent level
                        </div>
                        <div class=\"scrollitem\">
                        &nbsp;
                        </div>
                        <div class=\"scrollitem\">
                            87% of our clients attending found the seminar informative to a good or excellent level
                        </div>
                        <div class=\"scrollitem\">
                        &nbsp;
                        </div>
                        <div class=\"scrollitem\">
                            81% of our clients attending found the seminar informative to a good or excellent level
                        </div>
                        <div class=\"scrollitem\">
                        &nbsp;
                        </div>
                        <div class=\"scrollitem topad\">
                            100% of our clients thought it was excellent level
                        </div>
                    </div>
                </div>
                
                <div id=\"survey_man\"></div>
                <div id=\"swirlbox\"></div>
            </div>
        
        ";
        return $content;
    }
    
    public function outputEventTestRightBlock() {
        $content = "
        <div id=\"eventtestbox\" class=\"rightboxwrap\">
            <h2>Event Testimonials</h2>
            <div id=\"test_man\">
                <div class=\"scrollable sml\" id=\"test_scroll\">
                    <div class=\"items\">
                        <div class=\"scrollitem\">
                            &quot;Good to hear from fellow HR professionals as well as legal expert.&quot;<br /> <strong>Head of PR, RSPCA</strong>
                        </div>
                        <div class=\"scrollitem\">
                            &quot;Excellent timing re. new disciplinary procedures. Helped to clarify the changes.&quot;<br /> <strong>HR Advisor AIG Life</strong>
                        </div>
                        <div class=\"scrollitem\">
                            &quot;Nice central location, speakers were both very informative clear and concise style. &quot;<br /> <strong>HR Advisor, Family Investments</strong>
                        </div>
                        <div class=\"scrollitem\">
                            &quot;Very interesting and good subject matter. Speakers excellent.&quot;<br /> <strong>Senior HR Business Partner, Family Investments</strong>
                        </div>
                        <div class=\"scrollitem\">
                            &quot;Very informative and easy to understand presentation.&quot;<br /> <strong>HR Manager, Mott Mac Donald</strong>
                        </div>
                        <div class=\"scrollitem\">
                            &quot;I found the seminar very informative and practical, and look forward to other Kelta HR seminars now.&quot;<br /> <strong>HR Manager, West Control Solutions</strong>
                        </div>
                        <div class=\"scrollitem\">
                            &quot;Thank you - it's a great opportunity to keep my skills up to date and learn about changes in legislation.&quot;<br /> <strong>HR manager, Relentless Software</strong>
                        </div>
                        
                        <div class=\"scrollitem\">
                            &quot;Excellent in all respects - thank you.&quot;<br /> <strong>nterim HR Manager, Care UK, Sussex Orthopaedic Treatment Centre</strong>
                        </div>
                        <div class=\"scrollitem\">
                            &quot;Enjoyable presentation, kept attention throughout.&quot;<br /> <strong>HHR / Payroll Administrator, Compucredituk Ltd</strong>
                        </div>
                        <div class=\"scrollitem\">
                            &quot;Thanks so much for this and for the seminar this morning. I found it very informative and it raised some good points that I will feed back to my HR Manager.&quot;<br /> <strong>Human Resources Advisor, Insurecom Limited</strong>
                        </div>
                        <div class=\"scrollitem\">
                            &quot;Please do more of these. &quot;<br /> <strong>R Manager, Brighton Dome and Festival Ltd</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div id=\"swirlbox\"></div>
        </div>";
            
        return $content;
    }
    
    public function outputEventRightBlock() {
        $content = "
            <div class=\"rightbox_banner\" id=\"banner_green\">                
                <h3>Register for Future Events</h3>
                <p>Get notified of new and upcoming events.</p>
            </div>
            
            <div class=\"rightbox_banner\" id=\"banner_pink\">                
                <h3>Ask the speaker a question</h3>
                <p>Attending an event? <a href=\"/contact/6\">Ask a question</a></p>
            </div>
            
            <div class=\"rightbox_banner\" id=\"banner_green\">                
                <h3>Submit event feedback</h3>
                <p>Attend an event? <a href=\"/contact/7\">Share you thoughts</a></p>
            </div>
        ";
        
        return $content;
    }
	
	public function outputNewsHome($limit="") {
        
        $content = "<div class=\"rightboxwrap\"><h2>Latest News and Events</h2>";
        
		$news = $this->_getNewsSummaries($limit);
		
		if(is_array($news)) {
			foreach($news as $n) {
				switch($n->type) {
					case "N":
						$url = "news";
                        $icon = "<div class=\"newsicon\"></div>";
						break;
					case "E":
						$url = "events";
                        $icon = "";
						break;
				}
				
				$content .= "
				<div class=\"news-item\">
					<h3>".$icon."<a href=\"/".$url."/".urlencode($n->friendlyurl)."\" title=\"".$n->title."\">".$n->title." - ".date("d M y", strtotime($n->article_date))."</a></h3>
					<p>".strip_tags(substr($n->content, 0, 230))."... <a href=\"/".$url."/".urlencode($n->friendlyurl)."\" title=\"".$n->title."\">Read more</a></p>
				</div>";
			}
		}
		$content .= "</div>";
		return $content;
	}
    
    public function outputNewsRssFeed() {
         $content = "
         <div class=\"rightboxwrap\">
         <h2>Industry News</h2>
         <p>Today's top stories from People Management, Personnel Today and ACAS:  </p>
         <p><img src=\"/images/Peoplemanagment.png\" alt=\"people management\" />&nbsp;&nbsp;
         <img src=\"/images/Personneltoday.png\" alt=\"personal today\" />&nbsp;&nbsp;
         <img src=\"/images/acas.png\" alt=\"acas\" /></p>
            <div id=\"rssfeeditem\"><center><img src=\"/images/loading.gif\" alt=\"loading\" /></center></div>
            </div>";
            
            return $content;
    }

	public function outputNewsItem($type="") {
        
        if($type != "") {
			$backurl = $type;
		}
        
		$n = $this->_getNewsItem(urldecode($_GET["s"]));
        
        $n->image != "" ? $image = "<div class=\"eventimage\"><img src=\"/images/news/".$n->image."\" width=\"170px\" alt=\"".$n->imagealt."\" /></div>" : $image = "";
        
		$content .= "
        <div class=\"item-head".$_GET["t"]."\">&nbsp;</div>
            <div class=\"item".$_GET["t"]."\">
			<h2>".$n->title."</h2>
			<p class=\"newsdate\">".date("d-M-Y", strtotime($n->article_date))."</p>"
			.$image;
            if($n->type == "E") {
                
                $content .= "
                        <div class=\"eventdetails\">
                            <dl>
                                <dt>Event Date:</dt><dd>".date("d-M-Y", strtotime($n->article_date))."</dd>
                                <dt>Speakers:</dt><dd>".$n->speakers."</dd>
                                <dt>Location:</dt><dd>".$n->location."</dd>
                                
                            </dl>
                            <br class=\"clearall\" /><br />
                            <p><a href=\"#eventbooking\">Request your place</a></p>
                        </div>
                        <br class=\"clearall\" /><br />
                        ";
                        
                $content .= "";
            }
            
            $content .=
            $n->content;
			
		if($n->type == "E") {
			$f = new Form();
			
            $content .= "
					<h2>Request your place </h2>";
            
			if($_POST["page_action"] == "event_booking") {
				$this->_processEventBooking();
				$content .= "
					<p>Thank you for your requesting your place(s) we will confirm by email.</p>";
			}else {
				$content .=
					"<a name=\"eventbooking\"></a>
                    <p>Please register your early interest in these popular events. Due to high demand we will operate on a first come, first served basis. </p>" .
					$f->formStart("post", "", "bookingform", "rightcontentbox") .
					"<div class=\"formrow\">".$f->formText("name", "Name: *", "", "name", "required")."</div>" .
					"<div class=\"formrow\">".$f->formText("email", "Email: *", "", "email", "required")."</div>" .
                    "<div class=\"formrow\">".$f->formText("position", "Position: *", "", "position", "required")."</div>" .
					"<div class=\"formrow\">".$f->formText("company", "Company: *", "", "", "required")."</div>" .
                    "<div class=\"formrow\"><strong>First Delegate</strong> - <a class=\"eventdup\">Same as above</a></div>" .
					"<div class=\"formrow\">".$f->formText("d1_name", "Name: *", "", "d1_name", "required")."</div>" .
                    "<div class=\"formrow\">".$f->formText("d1_email", "Email: *", "", "d1_email", "required")."</div>" .
                    "<div class=\"formrow\">".$f->formText("d1_position", "Position: *", "", "d1_position", "required")."</div>" .
                    "<div class=\"formrow\"><strong>Second Delegate (optional)</strong></div>" .
					"<div class=\"formrow\">".$f->formText("d2_name", "Name:")."</div>" .
                    "<div class=\"formrow\">".$f->formText("d2_email", "Email:")."</div>" .
                    "<div class=\"formrow\">".$f->formText("d2_position", "Position:")."</div>" .
					"<div class=\"formrow\">".$f->formSubmit("Request now")."</div>" .
					$f->formPageAction("event_booking") .
					$f->formHidden("eventID", $n->ID) .
					$f->formEnd();
			}
			
			
			
		}
        
        $content .= "<p class=\"newsdate\"><a href=\"/".$backurl."\" title=\"click to go back to news listing\">-<- back</a></p>
            <br class=\"clearall\" />
        </div>
        <div class=\"item-foot".$_GET["t"]."\">&nbsp;</div>";
		return $content;
	}
	
	public function outputNewsSummaries() {
		
		$_GET["p"] == "events" ? $type = "E" : $type = "";
		$_GET["p"] == "news" ? $type = "N" : $type = $type;
        
        $_GET["p"] == "news" ? $icon = "<div class=\"newsicon\"></div>" : $icon ="";
				
		$news = $this->_getNewsSummaries("",$type);
    
        if(is_array($news)){
            $i = 1;
            $nm= 1;
            $content .= "
            <div class=\"panes\">
                <div class=\"newtab\">";
                
            foreach($news as $n) {
                if($i == 5) {
                    $content .= "</div><div class=\"newtab\">";
                    $i = 1;
                    $nm++;
                }
                if($i == 1) {
                    $menutabs .= "<li><a id=\"page".$nm."\" href=\"#page".$nm."\">".$nm."</a></li>";
                }
                
				switch($n->type) {
					case "N":
						$url = "news";
                        $extran = "<p class=\"newsdate\">".date("d-M-Y", strtotime($n->article_date))."</p>";
						break;
					case "E":
						$url = "events";
                        $extra = "
                        <div class=\"eventdetails\">
                            <dl>
                                <dt>Event Date:</dt><dd>".date("d-M-Y", strtotime($n->article_date))."</dd>
                                <dt>Speakers:</dt><dd>".$n->speakers."</dd>
                                <dt>Location:</dt><dd>".$n->location."</dd>
                            </dl>
                        </div>
                        <br class=\"clearall\" /><br />";
						break;
				}
                
                //$n->image != "" ? $image = "<div class=\"newsimage\"><img src=\"/images/news/".$n->image."\" alt=\"".$n->imagealt."\" /></div>" : $image = "";
                $even_odd = ( '-odd' != $even_odd ) ? '-odd' : '-even';
                if($n->image != "") {
                    $storyimage = "<img src=\"/images/news/".$n->image."\" alt=\"".$n->imagealt."\" width=\"170px\" />";
                }else {
                    $storyimage = "";
                }
                $content .= "
                <div class=\"item-head".$even_odd."\">&nbsp;</div>
                <div class=\"item".$even_odd."\">
                ".$icon."
                    <h2><a href=\"/".$url."/".urlencode($n->friendlyurl)."/".$even_odd."\" title=\"".$n->title."\">".$n->title."</a></h2>
                    
					".$extran."
                    <div class=\"eventimage\">
                        ".$storyimage."
                    </div>
                    ".$extra."
                    ".Formatter::firstPara( $n->content) .
                    "<p class=\"newsdate\"><a href=\"/".$url."/".urlencode($n->friendlyurl)."/".$even_odd."\">Read more ->-</a></p>
                    <br class=\"clearall\" />
                </div>
                <div class=\"item-foot".$even_odd."\">&nbsp;</div>";
                
                $i++;
            }
            $content .= "</div></div>";
        }
        if($tnum > 5) {
            $content .= "<ul class=\"jobnav tabs3\">".$menutabs."</ul><div class=\"floatleft\">(total of ".$tnum.") <a class=\"prev\"><< prev</a> | <a class=\"next\">next >></a></div>";
        }
        
        
		return $content;
	}
    
    public function outputPastEvents() {
        if($_GET["s"] != "") {
			$content .= $this->outputPastEventsItem();
		}else {

			$content .= $this->outputPastEventsSummaries();
			
		}
		return $content;
    }
    
    public function outputPastEventsSummaries() {
        $events = $this->_getPastEvents();
        
        if(is_array($events)) {
            foreach($events as $e) {
                
                $even_odd = ( '-odd' != $even_odd ) ? '-odd' : '-even';
                
                if($e->image != "") {
                    $storyimage = "<img src=\"/images/news/".$e->image."\" alt=\"".$e->imagealt."\" />";
                }else {
                    $storyimage = "";
                }
                
                $content .= "
                <div class=\"item-head".$even_odd."\">&nbsp;</div>
                <div class=\"item".$even_odd."\">
                    <h2><a href=\"/".$_GET["p"]."/".urlencode($e->friendlyurl)."/".$even_odd."\">".$e->title."</a></h2>
                    <div class=\"eventimage\">
                        ".$storyimage."
                    </div>
                    <div class=\"eventdetails\">
                        <dl>
                            <dt>Event Date:</dt><dd>".date("d M y", strtotime($e->event_date))."</dd>
                            <dt>Speakers:</dt><dd>".$e->speakers."</dd>
                            <dt>Location:</dt><dd>".$e->location."</dd>
                            <dt>Attendees:</dt><dd>".$e->attendees."</dd>
                        </dl>
                    </div>
                    <br class=\"clearall\" /><br />
                    ".Formatter::summarise($e->synopsis, 200).
                    "<a href=\"/".$_GET["p"]."/".urlencode($e->friendlyurl)."/".$even_odd."\">read more</a></p><br />
                </div>
                <div class=\"item-foot".$even_odd."\">&nbsp;</div>";
            }
        }
        
        return $content;
    }
    
    public function outputPastEventsItem() {
        $e = $this->_getPastEvent($_GET["s"]);
        
        if($e->image != "") {
            $storyimage = "<img src=\"/images/news/".$e->image."\" alt=\"".$e->imagealt."\" />";
        }else {
            $storyimage = "";
        }
        
        $content =
            "
            <div class=\"item-head".$_GET["t"]."\">&nbsp;</div>
            <div class=\"item".$_GET["t"]."\">
                <h2>".$e->title."</h2>
                <div class=\"eventimage\">
                    ".$storyimage."
                </div>
                <div class=\"eventdetails\">
                    <dl>
                        <dt>Event Date:</dt> <dd>".date("d M y", strtotime($e->event_date))."</dd>
                        <dt>Speakers:</dt> <dd>".$e->speakers."</dd>
                        <dt>Location:</dt> <dd>".$e->location."</dd>
                        <dt>Attendees:</dt> <dd>".$e->attendees."</dd>
                    </dl>
                </div>
                <br class=\"clearall\" /><br />
                ".$e->synopsis."
                <br />
                <h3>Quotes from attendees:</h3>
                <div class=\"box-head-pink\">&nbsp;</div>
                <div class=\"box-pink\">
                    <em>".$e->quotes."</em>
                </div>
                <p><a href=\"/".$_GET["p"]."\">Back to Past Events</a></p>
                <br class=\"clearall\" />
            </div>
            <div class=\"item-foot".$_GET["t"]."\">&nbsp;</div>";
            
        return $content;
    }

   

// Private Methods
    public function _getPastEvent($title) {
        $query = "SELECT ID, friendlyurl, title, event_date, speakers, location, attendees, synopsis, quotes, image, imagealt FROM pastevents WHERE friendlyurl = '".urldecode($title)."';";
        $event = mysql_fetch_object( mysql_query( $query ) );
	
		return $event;
    }

    public function _getPastEvents() {
        $query = "SELECT ID, friendlyurl, title, event_date, speakers, location, attendees, synopsis, quotes, image, imagealt FROM pastevents ORDER BY event_date DESC;";
        $result = mysql_query( $query );
		while ($items = mysql_fetch_object($result)) {
			$events[] = $items;
		}
		
		return $events;
    }

	private function _getNewsItem($title) {
		//Get news item based on title
		$query = "SELECT ID, friendlyurl, type, title, content, image, imagealt, article_date, speakers, location, synopsis FROM news WHERE friendlyurl = '".urldecode($title)."' LIMIT 1;";
		$news = mysql_fetch_object( mysql_query( $query ) );
		
		return $news;
	}
	
	private function _getNewsSummaries($total="", $type="") {
		
		$type != "" ? $typeextra = " AND type = '".$type."' " : $typeextra = "";
		
		$total != "" ? $totalextra = " LIMIT ".$total : $totalextra = "";
		
		$query = "SELECT type, friendlyurl, title, content, image, imagealt, article_date, speakers, location, synopsis FROM news WHERE status = 'Y' ".$typeextra." ORDER BY article_date DESC ".$totalextra.";";
		
		$result = mysql_query( $query );
		while ($items = mysql_fetch_object($result)) {
			$news[] = $items;
		}
		
		return $news;
	}
	
	private function _processEventBooking() {
		//get events details
		$query = "
			SELECT
				title, article_date, location
			FROM
				news
			WHERE
				ID = ".$_POST["frm_eventID"]."
			LIMIT 1;";
		$event = mysql_fetch_object(mysql_query($query));
		
		$mail = new Zend_Mail();
        
        $body =
        "Dear " . $_POST["frm_name"] .
        "\n\nThank you for requesting your place(s) at our event. We will be in contact to confirm your reservation(s) shortly. " .
        "\n\nAs our events are extremely popular, your booking at this stage is only a request to attend. Your booking will only be confirmed when you receive an email confirming your place(s)." .
        "\n\nEvent Details:" .
		"\n\nEvent name: ".$event->title.
		"\nEvent Date: ".$event->article_date .
        "\nLocation: ".$event->location .
        "\n\nYour Details:".
        "\nName: ".$_POST["frm_name"] .
        "\nEmail: ".$_POST["frm_email"] .
        "\nPosition: ".$_POST["frm_position"] .
        "\nCompany: ".$_POST["frm_company"] .
        "\n\nDelegate One" .
        "\n\nName: ".$_POST["frm_d1_name"] .
        "\nEmail: ".$_POST["frm_d1_email"] .
        "\nPosition: ".$_POST["frm_d1_position"];
        if($_POST["frm_d2_name"] != "") {
        $body .= "\n\nDelegate Two" .
            "\n\nName: ".$_POST["frm_d2_name"] .
            "\nEmail: ".$_POST["frm_d2_email"] .
            "\nPosition: ".$_POST["frm_d2_position"];
            
        $bodyhtmlextra = "
            <p>Delegate Two</p>
            <p>Name: ".$_POST["frm_d2_name"] ."<br>
            Email: ".$_POST["frm_d2_email"] ."<br>
            Position: ".$_POST["frm_d2_position"]."</p>";
        }
        $body .=
        "\n\nKelta HR is a specialist HR and L&D Recruitment Consultancy operating across the Sussex and East Surrey region. We have successfully placed many candidates locally across organisations varying from SMEÕs to large multinationals. " .
        "\n\nWe have an experienced team of recruiters who truly understand HR. We keep ourselves informed with market knowledge, HR issues and employment law updates. Should you wish to speak with one of our team, please feel free to contact us at anytime for an informal chat about our services. " .
        "\n\nKind regards, " .
         "\n\nKelta HR" .
        "\n\nT: 01273 832 144" .
        "\nE: info@kelta-hr.com" .
        "\nW: www.kelta-hr.com";
        
        $bodyhtml = "
            <p>Dear " . $_POST["frm_name"] ."</p>
            <p>Thank you for requesting your place(s) at our event. We will be in contact to confirm your reservation(s) shortly. </p>
            <p>As our events are extremely popular, your booking at this stage is only a request to attend. Your booking will only be confirmed when you receive an email confirming your place(s).</p>
            <p>Event Details:</p>
		<p>Event name: ".$event->title."<br>
		Event Date: ".$event->article_date ."<br>
        Location: ".$event->location ."</p>
        <p>Your Details:</p>
        <p>Name: ".$_POST["frm_name"] ."<br>
        Email: ".$_POST["frm_email"] ."<br>
        Position: ".$_POST["frm_position"] ."<br>
        Company: ".$_POST["frm_company"] ."</p>
        <p>Delegate One</p>
        <p>Name: ".$_POST["frm_d1_name"] ."<br>
        Email: ".$_POST["frm_d1_email"] ."<br>
        Position: ".$_POST["frm_d1_position"]."</p>".$bodyhtmlextra."
        <p>Kelta HR is a specialist HR and L&D Recruitment Consultancy operating across the Sussex and East Surrey region. We have successfully placed many candidates locally across organisations varying from SMEÕs to large multinationals. </p>
        <p>We have an experienced team of recruiters who truly understand HR. We keep ourselves informed with market knowledge, HR issues and employment law updates. Should you wish to speak with one of our team, please feel free to contact us at anytime for an informal chat about our services. </p>";
        
        
        $bodyhtml = KeltaWebpage::setHTMLEmail($bodyhtml);
        
        //$mail->addTo( "rob@preview.co.uk" );
        $mail->addTo( "info@kelta-hr.com" );
        $mail->addTo( $_POST["frm_email"] );
        $mail->setSubject( "Kelta HR Event Booking Request" );
        $mail->setFrom( "info@kelta-hr.com", "Kelta HR" );
        $mail->setBodyText( $body );
        $mail->setBodyHtml( $bodyhtml );
        $mail->send();
		
		
	}

}

?>