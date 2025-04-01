<?php
// singleton instance

// kings Admin Class

class kingsAdmin extends kingsWebpage {
var $status="";

	public function __construct(){

        parent::__construct();
         // Check to see if any standard forms have been submitted.
$s = new kingsStock();
	switch( $_POST["page_action"]) {
	    case "AddStock":
		 $this->status = $s->_processAddStock();
		  
                break;
		
	     case "editStock":
		  $s->_processEditStock();

                break;
		}
		
	}

    public function outputAdminMenu() {
               
        $content = "
			<ul>
				<span class=\"left\"></span><li><a href=\"/admin/stock\" title=\"stock\">Latest Stock</a></li><span class=\"right\"></span>
				<span class=\"left\"></span><li><a href=\"/admin/stock/add\" title=\"stock\">Add Stock</a></li><span class=\"right\"></span>
				<span class=\"left\"></span><li><a href=\"/admin?lo=logout\" title=\"logout\">Logout</a></li><span class=\"right\"></span>
			
			</ul>
		";
    
        return $content;
    }

    public function outputAdminContent() {

        switch($_GET["p"]) {
		
		case "stock":
			$content = "<div id=\"leftCol\">".$this->outputAdminStock();
			break;
		default:
			$content = "<div id=\"leftCol\"><p>logged in as ".$_SESSION["user"]["email"]."</p>".$this->outputAdminStock();
		break;
        }

        return $content;
    }
	
	public function outputAdminStock() {
		$s = new kingsStock();
		switch($_GET["s"]) {
			case "edit":
				$content = $s->outputStockEdit()."</div>";
				break;
			case "add":
				$content = $s->addStock()."</div>";
				break;
			case "delete":
				$id = $_GET["t"];
				$content = $s->deleteStock($id).'<br/><br/>'. $s->outputStockList()."</div>";
				break;
			case "search":
			if($_POST["frm_keyword"]) {
					$content .= $s->outputAdminStockSearchResults();
				}
				$content .= "</div>";
				break;
			default:
				$content .= $this->status.$s->outputStockList()."</div>";
				break;
		}
		return $content;
	}



}

?>

