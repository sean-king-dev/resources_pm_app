<?php

/* ************************************************************ */
/*                                                              */
/*  Core Function Library                                       */
/*                                                              */
/*  Class: Form.class.php                                       */
/*                                                              */
/*  Author: Philip Cole                                         */
/*  Date:   9th July 2007                                       */
/*                                                              */
/*  This is the core form class                                 */
/*                                                              */
/* ************************************************************ */
/*  Revision History                                            */
/*                                                              */
/*  2007-07-09: v0.1    Initial Creation                        */
/*                                                              */
/*                                                              */
/*                                                              */
/* ************************************************************ */

class Form {
	
	// PUBLIC VARIABLES
	public $errors = "";		// An array of any errors from processing the form.
	
	// PROTECTED VARIABLES
	protected $action;
	protected $method = "POST";
	protected $enc_type;
	protected $on_sub;
	protected $add_proc;		// Any additional processing required.
	
	
	
	// PRIVATE VARIABLES
	
	public function __construct( $action="", $method="" ) {
		$action == "" ? $this->action = $_SERVER["PHP_SELF"] : $this->action = $action;
		$method != "" ? $this->method = $method : NULL;
	}

    public function processBasicForm() {
        switch( $_POST["page_action"] ) {
            case "add":
                $i = 0;
                $keys = "";
                foreach ( $_POST as $key=>$value ) {
                    if ( substr( $key, 0, 4 ) == "frm_" ) {
                        $i == 0 ? $comma = "" : $comma = ", ";
                        $keys .= $comma."`".str_replace( "frm_", "", $key )."`";
						if ( is_array( $value ) ) {
							switch( $value[0] ) {
								case "date":
									$new_value = $value[3]."-".$value[2]."-".$value[1];
									break;
								case "file";
									break;
							}
						} else {
							$new_value = $value;
						}
                        $values .= $comma."'".$new_value."'";
                        $i++;
                    }
                }
                $query = "INSERT INTO ".$_POST["table"]." ( ".$keys." ) VALUES ( ".$values." );";
                mysql_query( $query ) or die( mysql_error()."<br /><br />".print_r( $_POST ) );
                break;
            case "edit":
                $i = 0;
                $keys = "";
                foreach ( $_POST as $key=>$value ) {
                    if ( substr( $key, 0, 4 ) == "frm_" ) {
                        $i == 0 ? $comma = "" : $comma = ", ";
                        $updates .= $comma."`".str_replace( "frm_", "", $key )."` = '".$value."'";
                        $i++;
                    }
                }
                $query = "UPDATE ".$_POST["table"]." SET ".$updates." WHERE ".$_POST["pcol"]." = ".$_POST["pval"]." LIMIT 1;";
                mysql_query( $query );
                break;
            case "delete":
                $query = "DELETE FROM ".$_POST["table"]." WHERE ".$_POST["pcol"]." = ".$_POST["pval"]." LIMIT 1;";
                mysql_query( $query );
                break;
            default:
                print "Err - there's no page action - something has gone a bit screwey!";
                break;
        }
    }

/* ************************************************************ */
/*                                                              */
/*                  FORM PROCESSING FUNCTIONS                   */
/*                                                              */
/* ************************************************************ */

	protected function getProcessRulesText() {
		
	}

	protected function getProcessRulesPassword() {
		
	}

	protected function getProcessRulesEmail() {
		
	}

	protected function getProcessRulesUrl() {
		
	}

	protected function getProcessRulesDate() {
		
	}

	protected function getProcessRulesFile() {
		
	}

	protected function getProcessRulesTextarea() {
		
	}

	protected function getProcessRulesSelect() {
		
	}

	protected function getProcessRulesRadio() {
		
	}

	protected function getProcessRulesCheckbox() {
		
	}









/* ************************************************************ */
/*                                                              */
/*                   FORM CREATION FUNCTIONS                    */
/*                                                              */
/* ************************************************************ */


	protected function getIsActive( $act ) {
		$act == "no" ? $dis = "disabled=\"disabled\" " : $dis = "";
		return $dis;
	}

	protected function generateLabel( $col, $suffix="" ) {
		!isset( $col["label"] ) ? $label = ucwords( str_replace( "_", " ", $col["field"] ) ) : $label = $col["label"];
		$suffix != "" ? $suffix = " ".$suffix : NULL;
		$label != "" ? $label = $label.$suffix.":" : NULL;
		$label = "<label for=\"frm_".$col["field"]."\" id=\"label_".$col["field"]."\">".$label."</label>";
		return $label;
	}

	protected function getInputText( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
        $output = "
			<div id=\"div_".$col["field"]."\">
				".$this->generateLabel( $col )."
				<input type=\"text\" name=\"frm_".$col["field"]."\" id=\"frm_".$col["field"]."\" value=\"".$data."\" ".$act."/>
			</div>";
		return $output;
	}

	protected function getInputPassword( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
        $output = "
			<div id=\"div_".$col["field"]."\">
				".$this->generateLabel( $col )."
				<input type=\"password\" name=\"frm_".$col["field"]."\" id=\"frm_".$col["field"]."\" value=\"".$data."\" ".$act."/>
			</div>";
		return $output;
	}

	protected function calculateInputDate ( $date ) {
		switch( substr( $date, 0, 1 ) ) {
			case "+":
				$new_date = date( "Y" ) + substr( $date, 1 );
				break;
			case "-":
				$new_date = date( "Y" ) - substr( $date, 1 );
				break;
			default:
				$new_date = $date;
				break;
		}
		return $new_date;
	}

	protected function getInputDate( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
		isset( $col["start"] ) ? $start = $this->calculateInputDate( $col["start"] ) : $start = date( "Y" ) - 5;
		isset( $col["end"] ) ? $end = $this->calculateInputDate( $col["end"] ) : $end = date( "Y" );
		$days = "";
		$data == "" ? $def_day = date( "j" ) : $def_day = $data;
		$data == "" ? $def_month = date( "n" ) : $def_month = $data;
		$data == "" ? $def_year = date( "Y" ) : $def_year = $data;
		$months = "";
		$years = "";
		for( $i=1; $i<=31; $i++ ) {
            $i == $def_day ? $selected = " selected=\"selected\"" : $selected = "";
			$days .= "<option value=\"".$i."\"".$selected.">".$i."</option>";
		}
		for( $i=1; $i<=12; $i++ ) {
            $i == $def_month ? $selected = " selected=\"selected\"" : $selected = "";
			$months .= "<option value=\"".$i."\"".$selected.">".$i."</option>";
		}
		for( $i=$end; $i>=$start; $i-- ) {
            $i == $def_year ? $selected = " selected=\"selected\"" : $selected = "";
			$years .= "<option value=\"".$i."\"".$selected.">".$i."</option>";
		}
		$output = "
			<div id=\"div_".$col["field"]."\">
				".$this->generateLabel( $col )."
				<input type=\"hidden\" name=\"frm_".$col["field"]."[]\" value=\"date\">
				<select name=\"frm_".$col["field"]."[]\" id=\"frm_".$col["field"]."_day\" ".$act.">
		            ".$days."
	            </select>
				<select name=\"frm_".$col["field"]."[]\" id=\"frm_".$col["field"]."_month\" ".$act.">
		            ".$months."
	            </select>
				<select name=\"frm_".$col["field"]."[]\" id=\"frm_".$col["field"]."_year\" ".$act.">
		            ".$years."
	            </select>
			</div>";
		return $output;
	}
	
	protected function getInputUrl( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
        $output = "
			<div id=\"div_".$col["field"]."\">
				".$this->generateLabel( $col )."
				<input type=\"text\" name=\"frm_".$col["field"]."\" id=\"frm_".$col["field"]."\" value=\"".$data."\" ".$act."/>
			</div>";
		return $output;
	}
	
	protected function getInputEmail( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
        $output = "
			<div id=\"div_".$col["field"]."\">
				".$this->generateLabel( $col )."
				<input type=\"text\" name=\"frm_".$col["field"]."\" id=\"frm_".$col["field"]."\" value=\"".$data."\" ".$act."/>
			</div>";
		return $output;
	}
	
	protected function getInputFile( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
		$col["qty"] == "" ? $qty = 1 : $qty = $col["qty"];
		$output = "";
		for( $i = 1; $i<$qty; $i++ ) {
			$output .= "
				<div id=\"div_".$col["field"]."\">
					".( $qty == 1 ? $this->generateLabel( $col ) : $this->generateLabel( $col, $i + 1 ) )."
					<input type=\"hidden\" name=\"frm_".$col["field"]."[]\" value=\"\">
					<input type=\"file\" name=\"frm_".$col["field"]."[".$i."]\" id=\"frm_".$col["field"]."\" value=\"".$data."\" ".$act."/>
				</div>";
		}
		return $output;
	}

	protected function getInputSelect( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
        if ( $col["lookup"] != "" ) {
            $lookup_field = $col["lookup"]["field"];
            $query = "SELECT ID, ".$lookup_field." FROM ".$col["lookup"]["table"]." ORDER BY ID;";
            $result = mysql_query( $query );
            while( $lookup = mysql_fetch_object( $result ) ) {
                $data == $lookup->ID ? $selected = " selected=\"selected\"" : $selected = "";
                $options .= "<option value=\"".$lookup->ID."\"".$selected.">".$lookup->$lookup_field."</option>";
            }
        }
        $output = "
			<div id=\"div_".$col["field"]."\">
	            ".$this->generateLabel( $col )."
				<select name=\"frm_".$col["field"]."\" id=\"frm_".$col["field"]."\" ".$act.">
		            ".$options."
	            </select>
			</div>";
		return $output;
	}

	protected function getInputTextarea( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
		$output = "
			<div id=\"div_".$col["field"]."\">
				".$this->generateLabel( $col )."
				<textarea type=\"text\" name=\"frm_".$col["field"]."\" id=\"frm_".$col["field"]."\" ".$act.">".$data."</textarea>
			</div>";
		return $output;
	}

	protected function getInputRadio( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
		$output = "<div id=\"div_".$col["field"]."\"><span class=\"radio_label\">".$col["label"].":</span>";
        if ( $col["lookup"] != "" ) {
            $lookup_field  = $col["lookup"]["field"];
            $query = "SELECT ID, ".$lookup_field." FROM ".$col["lookup"]["table"].";";
            $result = mysql_query( $query );
            while( $lookup = mysql_fetch_object( $result ) ) {
                $data == $lookup->ID ? $checked = " checked=\"checked\"" : $checked = "";
                $output .= " <label for=\"frm_".$col["field"]."_".$lookup->ID."\" class=\"radio\">".$lookup->$lookup_field.":</label> <input type=\"radio\" name=\"frm_".$col["field"]."\" id=\"frm_".$col["field"]."_".$lookup->ID."\" value=\"".$lookup->ID."\"".$checked." ".$act."/>";
            }
        } elseif ( $col["data"] != "" ) {
            foreach( $col["data"] as $key=>$value ) {
                $data == $value ? $checked = " checked=\"checked\"" : $checked = "";
                $output .= " <label for=\"frm_".$col["field"]."_".$key."_".$value."\" class=\"radio\">".$key.":</label> <input type=\"radio\" name=\"frm_".$col["field"]."\" id=\"frm_".$col["field"]."_".$key."_".$value."\" value=\"".$value."\"".$checked." ".$act."/>";
            }
        }
		$output .= "</div>";
		return $output;
	}

	protected function getInputCheckbox( $col, $data="" ) {
		$act = $this->getIsActive( $col["act"] );
	    $output = $col["label"].":";
        if ( $col["lookup"] != "" ) {
            $lookup_field  = $col["lookup"]["field"];
            $query = "SELECT ID, ".$lookup_field." FROM ".$col["lookup"]["table"].";";
            $result = mysql_query( $query );
            while( $lookup = mysql_fetch_object( $result ) ) {
                $data == $lookup->ID ? $checked = " checked=\"checked\"" : $checked = "";
                $output .= " <label for=\"frm_".$col["field"]."_".$lookup->ID."\">".$lookup->$lookup_field.":</label> <input type=\"checkbox\" name=\"frm_".$col["field"]."[]\" id=\"frm_".$col["field"]."_".$lookup->ID."\" value=\"".$lookup->ID."\"".$checked." ".$act."/>";
            }
        }
	}

    protected function outputInputType( $col, $data="" ) {
		$col["type"] == "" ? $col["type"] = "text" : NULL;
		$funcname = "getInput".ucwords( $col["type"] );
		print $this->$funcname( $col, $data );
	}

/*
    public function outputBasicAddForm( $intable ) {
        // Set the function name
        $$dbfunc = "setDB".ucwords( $intable );
        // Call the setting function
        $this->${$dbfunc}();
        // Set the data set from above
        $$db = "db_".$intable;
        // The $this->${$db} references the entered table
        $table = $this->${$db};
        print "
            <form action=\"".$this->action."\" method=\"".$this->method."\">
                <input type=\"hidden\" name=\"page_action\" value=\"add\">
                <input type=\"hidden\" name=\"table\" value=\"".$intable."\">
        ";
        foreach( $table["updates"] as $col ) {
            $this->outputInputType( $col );
        }
        print "
                <input type=\"submit\" value=\" Add \">
            </form>
        ";
        if ( $table["additional"] != "" ) {
            foreach( $table["additional"] as $additional ) {
                $this->outputBasicAddForm( $additional );
            }
        }
    }
*/

    public function outputBasicAddForm( $intable ) {
		$this->outputMultiAddForm( $intable, $intable );
	}


    public function outputMultiAddForm( $intable, $func ) {
        // Set the function name
        $$dbfunc = "setDB".$func;
        // Call the setting function
        $this->${$dbfunc}();
        // Set the data set from above
        $$db = "db_".$intable;
        // The $this->${$db} references the entered table
        $table = $this->${$db};
        print "
            <form action=\"".$this->action."\" method=\"".$this->method."\">
                <input type=\"hidden\" name=\"page_action\" value=\"add\">
                <input type=\"hidden\" name=\"table\" value=\"".$intable."\">
        ";
        foreach( $table["updates"] as $col ) {
            $this->outputInputType( $col );
        }
        print "
                <input type=\"submit\" value=\" Add \" id=\"submit_".$func."\">
            </form>
        ";
        if ( $table["additional"] != "" ) {
            foreach( $table["additional"] as $additional ) {
                $this->outputBasicAddForm( $additional );
            }
        }
        
    }


    public function outputBasicEditForm( $intable, $primary ) {
        // Set the function name
        $$dbfunc = "setDB".ucwords( $intable );
        // Call the setting function
        $this->${$dbfunc}();
        // Set the data set from above
        $$db = "db_".$intable;
        // The $this->${$db} references the entered table
        $table = $this->${$db};
        // Get the current data
        $query = "SELECT * FROM ".$intable." WHERE ".$table["primary"]." = ".$primary." LIMIT 1;";
        $data = mysql_fetch_object( mysql_query( $query ) );
        
        // Loop through the updates to create the output fields
        print "
            <form action=\"".$this->action."\" method=\"".$this->method."\">
                <input type=\"hidden\" name=\"page_action\" value=\"edit\">
                <input type=\"hidden\" name=\"table\" value=\"".$intable."\">
                <input type=\"hidden\" name=\"pcol\" value=\"".$table["primary"]."\">
                <input type=\"hidden\" name=\"pval\" value=\"".$primary."\">
        ";
        foreach( $table["updates"] as $col ) {
            $field = $col["field"];
            $this->outputInputType( $col, $data->$field );
        }
        print "
                <input type=\"submit\" value=\" Update \">
            </form>
        ";
    }




	protected function processForm() {
		foreach( $_POST as $key=>$value ) {
			$key_data = explode( "|", $key );
			if ( $value == "" && $key_data[1] == "req" )
				$this->errors[] = $key_data[0]." required";
			
        }
	}









	// FUNCTIONS BELOW ALL NEED PROPERLY DOING





	public function setEncType( $enctype="" ) {
		// Allow shorthand version of encoding type otherwise will set it to the literal value
		switch ( strtolower( $enctype ) ) {
			case "data":
				$this->enctype = "multipart/form-data";
			break;
			case "":
				$this->enctype = "application/x-www-form-urlencoded";
			break;
		}
		if ( $this->enctype == "" && $enctype != "" ) {
			$this->enctype = $enctype;
		}
		
	}
	
	public function setOnSubmit( $on_sub="" ) {
		$on_sub != "" ? $this->on_sub = $on_sub : NULL;
	}

	public function setAddProc( $add="" ) {
		$add != "" ? $this->add_proc = $add : $this->add_proc;
	}

	public function setInputText( ) {
	}
	
	public function setTextArea( ) {
	}
	
	
	
	
	
	
	
	
	
	
	
	public function outputFormHeader() {
		$this->enctype == "" ? $enctype = "" : $enctype = "enctype=\"".$this->enctype."\"";
		$this->on_sub == "" ? $on_sub = "" : $on_sub = " onsubmit=\"".$this->on_sub."\"";
		print "<form action=\"".$this->action."\" method=\"".$this->method."\" ".$enc_type." ".$on_sub.">";
	}

//	public function outputInputText() {
//		print "<label for=\"".$XXXXXXX."\">".$XXXXXXXX."</label> <input type=\"text\" name=\"".$XXXXXXXX."\" id=\"".$XXXXXXXX."\" value=\"".$XXXXXXXX."\"";
//	}


}




?>