<?php

// Changefirst ???????? Class

class Form {

/* ************************************************************************************ */

// Public Variables

// Protected Variables

    protected $formAction = "";

// Private Variables


/* ************************************************************************************ */

// Public Methods

    public function __construct() {
        // parent::__construct();
    }

    public function formCheckbox( $name, $label="", $value="", $id="", $checked="N", $class="", $disabled="N" ) {
        $id == "" ? $id = $name."_".$value : NULL;
        $label != "" ? $label = $this->_getLabel( $id, $label,$class ) : NULL;
        $checked == "Y" ? $is_checked = " checked=\"checked\"" : $is_checked = "";
       // $disabled == "Y" ? $is_disabled = " disabled=\"disabled\"" : $is_disabled = " onchange=\"doCheckBox('".$id."','".$value."')\"";
        $disabled == "Y" ? $is_disabled = " disabled=\"disabled\"" : null;

        //$class != "" ? $class2 = "class\"".$class."\"" : $class2 = "";
        
        $content = "
            
            <input ".$is_disabled." type=\"checkbox\" name=\"frm_".$name."[]\" id=\"frm_".$id."\" value=\"".$value."\"".$is_checked." class=\"".$class." in\" />
            ".$label;
        return $content;
    }

    public function formEnd() {
        return "</form>";
    }

    public function formFieldsetEnd() {
        return "</fieldset>";
    }

    public function formFieldsetStart( $legend="", $class="" ) {
        $legend != "" ? $legend = "<legend>".$legend."</legend>" : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $content = "<fieldset".$class.">".$legend;
        return $content;
    }

    public function formFile( $name, $label="", $id="", $class="" ) {
        $id == "" ? $id = $name : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $label != "" ? $label = $this->_getLabel( $id, $label ) : NULL;
        $content = "
            ".$label."
            <input type=\"file\" name=\"frm_".$name."\" id=\"frm_".$id."\"".$class." />";
        return $content;
    }

    public function formHidden( $name, $value="", $id="" ) {
        $id == "" ? $id = $name : NULL;
        $content = "
            <input type=\"hidden\" name=\"frm_".$name."\" id=\"frm_".$id."\" value=\"".$value."\" />";
        return $content;
    }

    public function formPageAction( $value ) {
        $content = "
            <input type=\"hidden\" name=\"page_action\" value=\"".$value."\" />";
        return $content;
    }

    public function formPassword( $name, $label="", $value="", $id="", $class="", $focus="" ) {
        $id == "" ? $id = $name : NULL;
        $label != "" ? $label = $this->_getLabel( $id, $label ) : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $content = "
            ".$label."
            <input type=\"password\" name=\"frm_".$name."\" id=\"frm_".$id."\" value=\"".$value."\"".$class." onfocus=\"".$focus."\" />";
        return $content;
    }

    public function formRadio( $name, $values, $label="", $origid="", $class="" ) {
        $label != "" ? $label = $this->_getLabel( $name, $label ) : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $options = "";
        if ( is_array( $values ) ) {
            foreach( $values as $key=>$value ) {
                $origid == "" ? $id = $name."_".$key : $id = $origid;
                $_REQUEST["frm_".$name] == $value ? $checked = " checked=\"checked\"" : $checked = "";
                $radios .= 
                    $this->_getLabel( $id, $value, "inlinelabel" ) .
                    "<input type=\"radio\" name=\"frm_".$name."\" id=\"frm_".$id."\" value=\"".$key."\" class=\"radio\"".$checked." />";
            }
        }
        $content = "
            ".$label."
            ".$radios;
        return $content;
    }

    public function formRadioHidden( $name, $values, $label="", $origid="", $class="" ) {
        $label != "" ? $label = $this->_getLabel( $name, $label ) : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $options = "";
        if ( is_array( $values ) ) {
            foreach( $values as $key=>$value ) {
                $origid == "" ? $id = $name."_".$key : $id = $origid;
                $_REQUEST["frm_".$name] == $value ? $checked = " checked=\"checked\"" : $checked = "";
                $radios .= 
                    $this->_getLabel( $id, $value, "hiddenlabel" ) .
                    "<input type=\"radio\" name=\"frm_".$name."\" id=\"frm_".$id."\" value=\"".$key."\" class=\"radio\"".$checked." />";
            }
        }
        $content = "
            ".$label."
            ".$radios;
        return $content;
    }

    public function formSelect( $name, $values, $label="",$default="", $id="", $class="", $multi="", $size="3"  ) {     
        $id == "" ? $id = $name : NULL;
        $label != "" ? $label = $this->_getLabel( $id, $label ) : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $multi == "Y" ? $multi = " multiple=\"multiple\" size=\"".$size."\"" : NULL;
        $options = "";
        if ( is_array( $values ) ) {

            foreach( $values as $key=>$value ) {
                $key == $default ? $selected = " selected=\"selected\"" : $selected = "";
                $options .= "<option value=\"".$key."\"".$selected.">".$value."</option>";
            }
        }
        $content = "
            ".$label."
            <select name=\"frm_".$name."\" id=\"frm_".$id."\"".$class."".$multi.">
                ".$options."
            </select>";
        return $content;
    }
    
    public function formSelect2( $name, $values, $label="", $id="", $class="", $multi="", $size="3" ) {
        $id == "" ? $id = $name : NULL;
        $label != "" ? $label = $this->_getLabel( $id, $label ) : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $multi == "Y" ? $multi = " multiple=\"multiple\" size=\"".$size."\"" : NULL;
        $options = "";
	    if($name=='clientsearch') $options .= "<option value=''>All</option>";
        if ( is_array( $values ) ) {
            foreach( $values as $key=>$value ) {    
                $value["default"] == "Y" ? $selected = " selected=\"selected\"" : $selected = "";
                $options .= "<option value=\"".$value["id"]."\"".$selected.">".$value["value"]."</option>";
            }
        }
        $content = "
            ".$label."
            <select name=\"frm_".$name."\" id=\"frm_".$id."\"".$class."".$multi.">
                ".$options."
            </select>";
        return $content;
    }
    
    public function formSelectMulti( $name, $values, $label="", $id="", $class="", $size="3" ) {
        $id == "" ? $id = $name : NULL;
        $label != "" ? $label = $this->_getLabel( $id, $label ) : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $options = "";
        if ( is_array( $values ) ) {
            foreach( $values as $key=>$value ) {
                $value["default"] == "Y" ? $selected = " selected=\"selected\"" : $selected = "";
                $options .= "<option value=\"".$key."\"".$selected.">".$value["value"]."</option>";
            }
        }
        $content = "
            ".$label."
            <select name=\"frm_".$name."[]\" id=\"frm_".$id."\"".$class." multiple=\"multiple\" size=\"".$size."\">
                ".$options."
            </select>";
        return $content;
    }
    
    public function formStart( $method="", $multi="N", $id="", $class="" ) {
        $method == "" ? $method = "post" : $method = $method;
        $this->formAction == "" ? $action = $_SERVER["REQUEST_URI"] : $action = $this->formAction;
        $multi == "Y" ? $enctype = " enctype=\"multipart/form-data\"" : $enctype = "";
        
        $id != "" ? $id = "id=\"".$id."\"" : $id = "";
        
        $content = "<form action=\"".$action."\" method=\"".$method."\"".$enctype." ".$id." class=\"".$class."\">";
        return $content;
    }

    public function formSubmit( $value="Submit", $class="submit" ) {
        $content = "
            <input type=\"submit\" class=\"".$class."\" value=\"".$value."\" />";
        return $content;
    }
    
    public function formSubmitAction( $value="Submit", $class="submit" ) {
        $content = "
            <input type=\"submit\" class=\"".$class."\" name=\"page_action\" value=\"".$value."\" />";
        return $content;
    }
    
    public function formText( $name, $label="", $value="", $id="", $class="", $focus="" ) {
        $id == "" ? $id = $name : NULL;
        $label != "" ? $label = $this->_getLabel( $id, $label ) : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $content = "
            ".$label."
            <input type=\"text\" name=\"frm_".$name."\" id=\"frm_".$id."\" value=\"".$value."\"".$class." onfocus=\"".$focus."\" />";
        return $content;
    }

    public function formTextArea( $name, $label="", $value="", $id="", $class="", $focus="" ) {
        $id == "" ? $id = $name : NULL;
        $label != "" ? $label = $this->_getLabel( $id, $label ) : NULL;
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        $content = "
            ".$label."
            <textarea name=\"frm_".$name."\" id=\"frm_".$id."\"".$class." onfocus=\"".$focus."\">".$value."</textarea>";
        return $content;
    }

    public function formTextAreaMCE( $name, $label="", $value="", $id="", $class="" ) {
        $id == "" ? $id = $name : NULL;
        $label != "" ? $label = $this->_getLabel( $id, $label ) : NULL;
        $class != "" ? $class = " class=\"mceEditor ".$class."\"" : $class = "class=\"mceEditor\"";
        $content = "
            ".$label."
            <textarea name=\"frm_".$name."\" id=\"frm_".$id."\"".$class.">".$value."</textarea>";
        return $content;
    }

    public function returnLabel( $id, $label, $class="" ) {
        $label = $this->_getLabel( $id, $label, $class );
        return $label;
    }

    public function setFormAction( $action ) {
        $this->formAction = $action;
    }

// Protected Methods
    
    protected function _getLabel( $id, $label, $class="" ) {
        $class != "" ? $class = " class=\"".$class."\"" : NULL;
        return "<label for=\"frm_".$id."\"".$class.">".$label."</label>";
    }


// Private Methods



}

?>