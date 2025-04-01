<?php

/* ************************************************************ */
/*                                                              */
/*  Core Function Library                                       */
/*                                                              */
/*  Class: Events.class.php                                     */
/*                                                              */
/*  Author: Philip Cole                                         */
/*  Date:   10th July 2007                                      */
/*                                                              */
/*  This is the core events class                               */
/*                                                              */
/* ************************************************************ */
/*  Revision History                                            */
/*                                                              */
/*  2007-07-10: v0.1    Initial Creation                        */
/*                                                              */
/*                                                              */
/*                                                              */
/* ************************************************************ */

class Events {
    
    public function __contruct() {
    }
    
    function getEvents() {
    }
    
    // public function 
    
    public function outputCalendarMonth( $month="", $year="" ) {
        $month == "" ? $month = date( "m" ) : NULL;
        $year == "" ? $year = date( "Y" ) : NULL;
        
        $first = mktime( 0, 0, 1, $month, 1, $year );
        $last = strtotime( "-1 day", strtotime( "+1 month", $first ) );
        
        $first_date = date( "d m Y", $first );
        $first_day = date( "w l", $first );
        $last_date = date( "d m Y", $last );
        $last_day = date( "w l", $last );
        $day_array = array( "sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday" );
        for( $i = 0; $i <= 6; $i++ ) {
            print date( "l", strtotime( $day_array[$i] ) );
        }
        print $first_day." - ".$last_day."<br />";
        print $month." ".$year;
    }
    
}




?>