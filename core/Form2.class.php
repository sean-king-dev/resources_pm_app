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

class Form2 {
	
	// PUBLIC VARIABLES
	public $errors = "";		// An array of any errors from processing the form.
	
	// PROTECTED VARIABLES
	protected $action;
	protected $method = "POST";
	protected $enctype;
	protected $on_sub;
	protected $add_proc;		// Any additional processing required.
	protected $req_symbol = "*";
	protected $req_class = "required";
	
	
	
	// PRIVATE VARIABLES
	
	public function __construct( $action="", $method="" ) {
		$action == "" ? $this->action = $_SERVER["REQUEST_URI"] : $this->action = $action;
		$method != "" ? $this->method = $method : NULL;
		// $this->setEncType();
	}

/* ************************************************************ */
/*                                                              */
/*                  BASIC FORM SETUP FUNCTIONS                  */
/*                                                              */
/* ************************************************************ */
	
	public function setMethod( $method ) {
		$this->method = $method;
	}

	public function setAction( $action ) {
		$this->action = $action;
	}














    public function processBasicForm( $table ) {
		$this->initForm( $table );
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
								case "file":
									$subkey = substr( $key, 4 );
									$location = $this->formData["fields"][$subkey]["process"]["location"];
									/*
									for( $i=1; $i <= count( $_FILES ); $i++ ) {
										move_uploaded_file( $_FILES[$key]["tmp_name"][$i], $_SERVER["DOCUMENT_ROOT"].$location.$_FILES[$key]["name"][$i] );
									}
									*/
									move_uploaded_file( $_FILES[$key]["tmp_name"]["1"], $_SERVER["DOCUMENT_ROOT"].$location.$_FILES[$key]["name"]["1"] );
									$new_value = $_FILES[$key]["name"]["1"];
									break;
								case "image":
									$subkey = substr( $key, 4 );
									$location = $this->formData["fields"][$subkey]["process"]["location"];
									move_uploaded_file( $_FILES[$key]["tmp_name"]["1"], $_SERVER["DOCUMENT_ROOT"].$location.$_FILES[$key]["name"]["1"] );
									$im = new CriterionImage( $_FILES[$key]["name"]["1"], $location );
									$im->processImage();
									$new_value = $_FILES[$key]["name"]["1"];
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
                mysql_query( $query );
                break;
            case "update":
                $i = 0;
                $keys = "";
                foreach ( $_POST as $key=>$value ) {
                    if ( substr( $key, 0, 4 ) == "frm_" ) {
                        $i == 0 ? $comma = "" : $comma = ", ";
                        $updates .= $comma."`".str_replace( "frm_", "", $key )."` = '".$value."'";
                        $i++;
                    }
                }
                $query = "UPDATE ".$_POST["table"]." SET ".$updates." WHERE ".$this->formData["primary"]." = ".$_POST["pval"]." LIMIT 1;";
				mysql_query( $query );
                break;
			case "delete":
				if ( $_POST["location"] != "" ) {
					$file = $_SERVER["DOCUMENT_ROOT"].$_POST["location"];
					file_exists( $file ) ? unlink( $file ) : NULL;
				}
                $query = "DELETE FROM ".$_POST["table"]." WHERE ".$this->formData["primary"]." = ".$_POST["pval"]." LIMIT 1;";
                mysql_query( $query );
                break;
			case "show":
                $query = "UPDATE ".$_POST["table"]." SET display = 'Y' WHERE ".$this->formData["primary"]." = ".$_POST["pval"]." LIMIT 1;";
                mysql_query( $query );
                break;
			case "hide":
                $query = "UPDATE ".$_POST["table"]." SET display = 'N' WHERE ".$this->formData["primary"]." = ".$_POST["pval"]." LIMIT 1;";
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

	// Base Functions
	
	protected function baseGenerateLabel( $label, $name, $req="N" ) {
		$req != "" ? $req = " <span class=\"".$this->req_class."\">".$this->req_symbol."</span>" : $req = "";
		$label = "<label for=\"frm_".$name."\" id=\"label_".$name."\">".$label.":".$req."</label>";
		return $label;
	}
	
	protected function baseGetInputText( $name, $value="" ) {
		$output = "<input type=\"text\" ".$this->inactive." name=\"frm_".$name."\" id=\"frm_".$name."\" value=\"".$value."\" />";
		return $output;
	}

	protected function baseGetInputFile( $name ) {
		$output = "<input type=\"file\" ".$this->inactive." name=\"frm_".$name."\" id=\"frm_".$name."\" value=\"\" />";
		return $output;
	}

	protected function baseGetInputHidden( $name, $value ) {
		$output = "<input name=\"".$name."\" type=\"hidden\" value=\"".$value."\">";
		return $output;
	}
	
	public function baseGetInputSelect( $name, $options, $current="", $blank="" ) {
		$output = "
			<select name=\"".$name."\" id=\"".$name."\">";
		$blank != "" ? $output .= "<option value=\"\">".$blank."</option>" : NULL;
		if ( is_array( $options ) ) {
			foreach( $options as $key=>$value ) {
				$current == $value ? $selected = " selected=\"selected\"" : $selected = "";
				$output .= "<option value=\"".$value."\"".$selected.">".$key."</option>";
			}
		}
		$output .= "
			</select>";
		return $output;
	}


	protected function initForm( $table ) {
		$this->formData = "";
        // Set the function name
        $$dbfunc = "setForm".$table;
        // Call the setting function
        $this->${$dbfunc}();
		!isset( $this->formData["primary"] ) ? $this->formData["primary"] = "ID" : NULL;
		!isset( $this->formData["table"] ) ? $this->formData["table"] = $table : NULL;
		!isset( $this->formData["fields"] ) ? $this->formData["fields"] = array( $table=>array() ) : NULL;
	}

	protected function getIsActive( $key ) {
		// This is set this way so that it acts as a single switch rather than always defaulting.
		$this->formData["fields"][$key]["general"]["act"] == "no" ? $this->inactive = "disabled=\"disabled\" " : NULL;
		$this->formData["fields"][$key]["general"]["act"] == "yes" ? $this->inactive = "" : NULL;
		return $dis;
	}

	protected function setInputInactive( $inactive = "N" ) {
		$inactive == "Y" ? $this->inactive = "disabled=\"disabled\"" : $this->inactive = "";
	}


	protected function generateLabel( $key, $suffix="" ) {
		$suffix != "" ? $suffix = " ".$suffix : NULL;
		$this->formData["fields"][$key]["general"]["req"] == "yes" ? $req = "Y" : $req = "N";
//		$this->formData["fields"][$key]["display"]["label"] != "" ? $label = $this->formData["fields"][$key]["display"]["label"].$suffix.":".$req : $label = "";
		$this->formData["fields"][$key]["display"]["label"] != "" ? $label = $this->formData["fields"][$key]["display"]["label"].$suffix : $label = "";
		// $label = "<label for=\"frm_".$key."\" id=\"label_".$key."\">".$label."</label>";
		$label = $this->baseGenerateLabel( $label, $key, $req );
		return $label;
	}
	
	protected function getInputText( $key, $data="" ) {
		$act = $this->getIsActive( $key );
//        $output = $this->generateLabel( $key )."
//				<input type=\"text\" ".$this->inactive." name=\"frm_".$key."\" id=\"frm_".$key."\" value=\"".$data."\" ".$act."/>";
		$output = $this->generateLabel( $key ).$this->baseGetInputText( $key, $data );
		return $output;
	}

	protected function getInputHidden( $key, $data="" ) {
		$act = $this->getIsActive( $key );
		$output = $this->baseGetInputHidden( $key, $data );
		return $output;
	}

	protected function getInputPassword( $key, $data="" ) {
		$act = $this->getIsActive( $key );
        $output = $this->generateLabel( $key )."
				<input type=\"password\" ".$this->inactive." name=\"frm_".$key."\" id=\"frm_".$key."\" value=\"\" ".$act."/>";
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

	protected function getInputDate( $key, $data="" ) {
		$act = $this->getIsActive( $key );
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
				".$this->generateLabel( $key )."
				<input type=\"hidden\" ".$this->inactive." name=\"frm_".$key."[]\" value=\"date\">
				<select ".$this->inactive." name=\"frm_".$key."[]\" id=\"frm_".$col["field"]."_day\" ".$act.">
		            ".$days."
	            </select>
				<select ".$this->inactive." name=\"frm_".$key."[]\" id=\"frm_".$col["field"]."_month\" ".$act.">
		            ".$months."
	            </select>
				<select ".$this->inactive." name=\"frm_".$key."[]\" id=\"frm_".$col["field"]."_year\" ".$act.">
		            ".$years."
	            </select>";
		return $output;
	}
	
	protected function getInputUrl( $key, $data="" ) {
		return $this->getInputText( $key, $data );
	}
	
	protected function getInputEmail( $key, $data="" ) {
		return $this->getInputText( $key, $data );
	}
	
	protected function getInputFile( $key, $data="" ) {
		$act = $this->getIsActive( $key );
		$output = "<input type=\"hidden\" ".$this->inactive." name=\"frm_".$key."[0]\" value=\"file\">";
		$output .= "
			".$this->generateLabel( $key )."
			<input type=\"file\" ".$this->inactive." name=\"frm_".$key."[1]\" id=\"frm_".$key."\" value=\"\" ".$act."/>".$current."";
		return $output;
	}
	
/*  THIS VERSION ALLOWS UPLOADING MULTIPLE FILES THOUGH TYING IT IN WITH MULTIPLE DESCRIPTIONS IS NOT YET CODED SO IT HAS BEEN COMMENTED OUT FOR NOW
	protected function getInputFile( $key, $data="" ) {
		$act = $this->getIsActive( $key );
		$output = "<input type=\"hidden\" ".$this->inactive." name=\"frm_".$key."[0]\" value=\"file\">";
		for( $i = 1; $i<=$this->formData["fields"][$key]["general"]["qty"]; $i++ ) {
			$data != "" ? $current = "<br />(".$data.")" : $current = "";
			$output .= "
					".( $this->formData["fields"][$key]["general"]["qty"] == 1 ? $this->generateLabel( $key ) : $this->generateLabel( $key, $i + 1 ) )."
					<input type=\"file\" ".$this->inactive." name=\"frm_".$key."[".$i."]\" id=\"frm_".$key."\" value=\"\" ".$act."/>".$current."";
		}
		return $output;
	}
*/

	protected function getInputImage( $key, $data="" ) {
		$act = $this->getIsActive( $key );
		$output = "<input type=\"hidden\" ".$this->inactive." name=\"frm_".$key."[0]\" value=\"image\">";
		$output .= "
			".$this->generateLabel( $key )."
			<input type=\"file\" ".$this->inactive." name=\"frm_".$key."[1]\" id=\"frm_".$key."\" value=\"\" ".$act."/>".$current."";
		return $output;
	}
	
	protected function getInputSelect( $key, $data="" ) {
		$act = $this->getIsActive( $key );
        if ( is_array( $this->formData["fields"][$key]["display"]["lookup"] ) ) {
            $lookup_field = $this->formData["fields"][$key]["display"]["lookup"]["field"];
            $query = "SELECT ID, ".$lookup_field." FROM ".$this->formData["fields"][$key]["display"]["lookup"]["table"]." ORDER BY ID;";
            $result = mysql_query( $query );
            while( $lookup = mysql_fetch_object( $result ) ) {
                $data == $lookup->ID ? $selected = " selected=\"selected\"" : $selected = "";
                $options .= "<option value=\"".$lookup->ID."\"".$selected.">".$lookup->$lookup_field."</option>";
            }
        }
        $output = "
	            ".$this->generateLabel( $key )."
				<select ".$this->inactive." name=\"frm_".$key."\" id=\"frm_".$key."\" ".$act.">
		            ".$options."
	            </select>";
		return $output;
	}

	protected function getInputTextarea( $key, $data="" ) {
		$act = $this->getIsActive( $key );
		$output = $this->generateLabel( $key )."
				<textarea ".$this->inactive." type=\"text\" name=\"frm_".$key."\" id=\"frm_".$keyHe."\" ".$act.">".$data."</textarea>";
		return $output;
	}

	protected function getInputRadio( $key, $data="" ) {
		$act = $this->getIsActive( $key );
		$output = "<span class=\"radio_label\">".$this->formData["fields"][$key]["display"]["label"].":</span>";
        if ( is_array( $this->formData["fields"][$key]["display"]["lookup"] ) ) {
			if ( isset( $this->formData["fields"][$key]["display"]["lookup"]["table"] ) ) {
				// The lookup data is a database table
	            $lookup_field  = $this->formData["fields"][$key];
				isset( $this->formData["fields"][$key]["display"]["lookup"]["primary"] ) ? $lu_primary = $this->formData["fields"][$key]["display"]["lookup"]["primary"] : $lu_primary = "ID";
	            $query = "SELECT ".$lu_primary.", ".$lookup_field." FROM ".$this->formData["fields"][$key]["display"]["lookup"]["table"].";";
	            $result = mysql_query( $query );
	            while( $lookup = mysql_fetch_object( $result ) ) {
	                $data == $lookup->$lu_primary ? $checked = " checked=\"checked\"" : $checked = "";
	                $output .= " <label for=\"frm_".$key."_".$lookup->$lu_primary."\" class=\"radio\">".$lookup->$lookup_field.":</label> <input type=\"radio\" name=\"frm_".$key."\" id=\"frm_".$key."_".$lookup->$lu_primary."\" value=\"".$lookup->$lu_primary."\"".$checked." ".$act."/>";
	            }
			} else {
				// The lookup info is the array
	            foreach( $this->formData["fields"][$key]["display"]["lookup"] as $lu_key=>$value ) {
	                $data == $value ? $checked = " checked=\"checked\"" : $checked = "";
	                $output .= " <label for=\"frm_".$key."_".$lu_key."_".$value."\" class=\"radio\">".$lu_key.":</label> <input type=\"radio\" ".$this->inactive." name=\"frm_".$key."\" id=\"frm_".$key."_".$lu_key."_".$value."\" value=\"".$value."\"".$checked." ".$act."/>";
				}
			}
        }
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
                $output .= " <label for=\"frm_".$col["field"]."_".$lookup->ID."\">".$lookup->$lookup_field.":</label> <input type=\"checkbox\" ".$this->inactive." name=\"frm_".$col["field"]."[]\" id=\"frm_".$col["field"]."_".$lookup->ID."\" value=\"".$lookup->ID."\"".$checked." ".$act."/>";
            }
        }
	}

    protected function outputInputType( $key, $data="" ) {
		$funcname = "getInput".ucwords( $this->formData["fields"][$key]["general"]["type"] );
		if ( $funcname == "getInputHidden" ) {
		$this->form .= $this->$funcname( $key, $this->formData["fields"][$key]["display"]["default"] );
		} else {
		$this->form .= "
			<div id=\"div_".$key."\">
				".$this->$funcname( $key, $data )."
			</div>";
		}
	}


	// Function to populate the object properties from their defaults if they aren't already explicitly set
	protected function setFormDefaults( $key ) {
		!isset( $this->formData["fields"][$key]["general"]["type"] ) 	? $this->formData["fields"][$key]["general"]["type"] = "text" : NULL;
		!isset( $this->formData["fields"][$key]["general"]["req"] ) 	? $this->formData["fields"][$key]["general"]["req"] = "no" : NULL;
		// !isset( $this->formData["fields"][$key]["general"]["act"] ) 	? $this->formData["fields"][$key]["general"]["act"] = "yes" : NULL;
		!isset( $this->formData["fields"][$key]["general"]["qty"] ) 	? $this->formData["fields"][$key]["general"]["qty"] = 1 : NULL;
		!isset( $this->formData["fields"][$key]["display"]["label"] ) 	? $this->formData["fields"][$key]["display"]["label"] = ucwords( str_replace( "_", " ", $key ) ) : NULL;
		!isset( $this->formData["fields"][$key]["process"]["urlOK"] ) 	? $this->formData["fields"][$key]["process"]["urlOK"] = "no" : NULL;
	}

    public function outputBasicAddForm( $intable ) {
		return $this->outputMultiAddForm( $intable );
	}


    public function outputMultiAddForm( $table ) {
		$this->initForm( $table );
        $this->form = "
            <form action=\"".$this->action."\" method=\"".$this->method."\" enctype=\"".$this->enctype."\">
                <input type=\"hidden\" name=\"page_action\" value=\"add\">
                <input type=\"hidden\" name=\"table\" value=\"".$table."\">
        ";
        foreach( $this->formData["fields"] as $key=>$value ) {
			$this->setFormDefaults( $key );
			$this->outputInputType( $key );
        }
        $this->form .= "
                <input type=\"submit\" class=\"submit\" value=\" Add \" id=\"submit_".$func."\">
            </form>
        ";
		return $this->form;
		/*
        if ( $table["additional"] != "" ) {
            foreach( $table["additional"] as $additional ) {
                $this->outputBasicAddForm( $additional );
            }
        }
		*/
        
    }


	public function outputBasicEditForm( $table, $primary ) {
		$this->initForm( $table );
		$this->outputFormHeader();
		$this->form = "
			<input type=\"hidden\" name=\"page_action\" value=\"update\">
            <input type=\"hidden\" name=\"table\" value=\"".$table."\">
            <input type=\"hidden\" name=\"pval\" value=\"".$primary."\">
		";
		$query = "SELECT * FROM ".$this->formData["table"]." WHERE ".$this->formData["primary"]." = '".$primary."' LIMIT 1;";
		$data = mysql_fetch_object( mysql_query( $query ) );
		
        foreach( $this->formData["fields"] as $key=>$value ) {
			$this->setFormDefaults( $key );
			$this->outputInputType( $key, $data->$key );
        }
        $this->form .= "
                <input type=\"submit\" value=\" Update \" id=\"submit_".$func."\">
            </form>";
		$this->initForm( $table );
		
		$this->outputFormHeader();
		$this->form .= "
			<input type=\"hidden\" name=\"page_action\" value=\"delete\">
            <input type=\"hidden\" name=\"table\" value=\"".$table."\">
            <input type=\"hidden\" name=\"pval\" value=\"".$primary."\">
		";
		$query = "SELECT * FROM ".$this->formData["table"]." WHERE ".$this->formData["primary"]." = '".$primary."' LIMIT 1;";
		$data = mysql_fetch_object( mysql_query( $query ) );
		
		$this->setInputInactive( "Y" );
        foreach( $this->formData["fields"] as $key=>$value ) {
			$this->setFormDefaults( $key );
			$this->outputInputType( $key, $data->$key );
        }
		$this->setInputInactive();
        $this->form .= "
                <input type=\"submit\" class=\"submit\" value=\" Delete \" id=\"submit_".$func."\">
            </form>
			
        ";
		return $this->form;
		
	}

/*
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
*/



	protected function processForm() {
		foreach( $_POST as $key=>$value ) {
			$key_data = explode( "|", $key );
			if ( $value == "" && $key_data[1] == "req" )
				$this->errors[] = $key_data[0]." required";
			
        }
	}

	



	// Stock Image Upload Forms
	public function outputImageUpload() {
		$this->setEncType( "data" );
		$this->outputFormHeader();
		print "
			".$this->baseGetInputHidden( "page_action", "upload_image" )."
			".$this->baseGetInputFile( "image" )."
			".$this->baseGetInputText( "image_alt", "Description" );
		$this->outputLibrarySelect();
		$this->outputFormFooter( "upload" );
	}
	
	public function processImageUpload() {
		$i = new Image();
		$path = $i->getBasePath();
		move_uploaded_file( $_FILES["frm_image"]["tmp_name"], $path."".$_FILES["frm_image"]["name"] );
		$i->setImage( $_FILES["frm_image"]["name"] );
		$i->resizeImageWithThumbManual( "300", "150", "thumb_" );
		$this->storeImageInfo();
		
	}

	protected function outputLibrarySelect() {
		print $this->baseGetInputHidden( "libraryID", "0" );
	}
	
	protected function storeImageInfo() {
		$query = "INSERT INTO images ( libraryID, filename, alt_text, filetype, filesize ) VALUES ( ".$_POST["libraryID"].", '".$_FILES["frm_image"]["name"]."', '".$_POST["frm_image_alt"]."', '".$_FILES["frm_image"]["type"]."', '".$_FILES["frm_image"]["size"]."' );";
		mysql_query( $query ) or die( "Err - nah<br /><br />".mysql_error() );
	}

/*	public function setImageLocation( $location ) {
		$this->imageLocation = $location;
	}
	
	protected function getImageLocation( $location="" ) {
		if ( $location != "" )
			$location = $location;
		elseif( $GLOBALS["loc_images"] != "" )
			$location = $GLOBALS["loc_images"];
		else
	}
*/



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
	
	
	
	public function outputEditOptions( $table ) {
		$this->initForm( $table );
		!isset( $this->formData["select"] ) ? $this->formData["select"] = array( key( $this->formData["fields"] ) ) : NULL;
        print "
            <form action=\"".$this->action."\" method=\"".$this->method."\">
                <input type=\"hidden\" name=\"page_action\" value=\"edit\">
                <input type=\"hidden\" name=\"table\" value=\"".$table."\">
				<select name=\"primary\" id=\"frm_".$this->formData["select"][0]."\" ".$act.">
        ";
		$i = 0;
		$select = "";
		foreach ( $this->formData["select"] as $s_items ) {
			$i == 0 ? $comma = "" : $comma = ", ";
			$select .= $comma.$s_items;
			$i++;
		}
        $query = "SELECT ".$this->formData["primary"].", ".$select." FROM ".$table." ORDER BY ".$select.";";
		$result = mysql_query( $query );
		while( $e_opt = mysql_fetch_array( $result ) ) {
			$options .= "<option value=\"".$e_opt[0]."\">";
			for( $j=1; $j<=count( $this->formData["select"] ); $j++ ) {
				$j == 1 ? $comma = "" : $comma = ", ";
				$options .= $comma.$e_opt[$j];
			}
			$options .= "</option>";
		}
		print $options;
        print "
				</select>
                <input type=\"submit\" value=\" Edit \" id=\"submit_".$func."\">
            </form>
        ";
	}
	
	
	
	
	
	
	
	
	
	
	public function outputFormHeader() {
		$this->enctype == "" ? $enctype = "" : $enctype = "enctype=\"".$this->enctype."\"";
		$this->on_sub == "" ? $on_sub = "" : $on_sub = " onsubmit=\"".$this->on_sub."\"";
		print "<form action=\"".$this->action."\" method=\"".$this->method."\" ".$enctype." ".$on_sub.">";
	}

//	public function outputInputText() {
//		print "<label for=\"".$XXXXXXX."\">".$XXXXXXXX."</label> <input type=\"text\" name=\"".$XXXXXXXX."\" id=\"".$XXXXXXXX."\" value=\"".$XXXXXXXX."\"";
//	}

	
	public function outputFormFooter( $button=" Submit " ) {
        print "
                <input type=\"submit\" value=\"".$button."\" id=\"submit_".$func."\">
            </form>
        ";
	}
	


}




?>