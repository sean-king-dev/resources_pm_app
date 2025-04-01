<?php
session_start();
	
	if ( isset( $_COOKIE["user"] ) && !isset( $_SESSION["user"] ) ) {
		$_SESSION["user"] = $_COOKIE["user"];
		header( "location: /" );
	}
	
	
require_once( $_SERVER["DOCUMENT_ROOT"]."/core/autoload.php" );
require_once( $_SERVER["DOCUMENT_ROOT"]."/includes/kingsDB.inc.php" );

$u = new kingsUser();

if ( $_POST["page_action"] == "Login" ) {
    $u->processLogin();
}


$_GET["lo"] == "logout" ? $u->processLogout() : NULL;


$w = new kingsWebpage();
$a = new kingsAdmin();
$s= new kingsStock();

 

//$w->setDefaults();
if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 6' ) && ! strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera' ) )
        print "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
            <html lang=\"en\">
        ";
    else
        print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
            <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
        ";
		
$w->setSiteTitle("Admin");

$w->setOwnerMeta( "Ben Smith - Preview (www.preview.co.uk)", "Preview" );

$w->attachStyle( "style.css" );
$w->attachScript("jquery.tools.js");
$w->attachScript("jquery.validate.js");
$w->attachScript("kings.js");
$w->attachScript("/tinymce/jscripts/tiny_mce/tiny_mce.js", "/admin");

$w->setFavIcon( "./" );

$w->includeScript("
	tinyMCE.init({
		mode : 'textareas',
		theme : 'advanced',
		theme_advanced_buttons1 : 'bullist,bold,undo,redo,link,unlink',
	theme_advanced_buttons2 : '',
	theme_advanced_buttons3 : '',
	valid_elements : 'ul,li,a[href|target|title],p,br,strong/b'});
");


$w->outputStandardHtmlHeaders();
if ( $u->checkLogin() === false ) {
	
	
	?>

<body>
	<div id ="top">
		  <?php $m= new kingsMenu(); ?>
	   
		
		<div id="header">
			<div id="logo"><a href="/"><img src="/images/logo.jpg" alt="Steven kings Machinery" /></a></div>
			<div id="contact"><a id href="/"></a>+44 (0) 1444 245414<div id="email">info@stevenkingsmachinery.co.uk</div></div>
			<div></div>
			<div id="navigation">
			<?php print $m->outputMenu(); ?>
			</div>
			<?php print  $w->outputheader(); ?>
			
			<?php //print $m->outputSubMenu(15); ?>
			</div>
			
		</div>	
		</div>	
	</div>
	<div id="wrapper">
	     
	   
		
		<div id="content">

			   <?php
			   print "
			   
			   <h1>LOGIN</h1>
			   <p>Please enter your user name and password</p>
			   
            <form id=\"login\" action=\"\" method=\"post\">
                <label for=\"useremail\"><abbr title=\"Please enter your email address\">Email:</abbr></label>
                <input type=\"text\" name=\"useremail\" id=\"useremail\" value=\"\" />
                <label for=\"password\"><abbr title=\"Please enter your password\">Password:</abbr></label>
                <input type=\"password\" name=\"password\" id=\"password\" value=\"\" />
                <label for=\"remember\"><abbr title=\"Remember your info so you don't have to login again\">Save?</abbr></label>
                <input type=\"checkbox\" name=\"remember\" id=\"remember\" value=\"on\" />
                <input type=\"submit\" class=\"submit\" name=\"page_action\" value=\"Login\" />
            </form>";
       ?>
		</div >
		     
		
		
	<div class="clearall"></div>
	     
	</div>
	<div id="footer">
		<div id="footertext">
			Site Map   |   Contact us   |   Terms & conditions   |   Legal   |   Privacy Policy   |   Accessibility
		</div>	
	</div >

</body>

<?php
    } else {
	?>



<body>
	<div id ="top">
		  <?php $m= new kingsMenu(); ?>
		<div id="header">
			<div id="logo"><a href="/"><img src="/images/logo.jpg" alt="Steven kings Machinery" /></a></div>
			<div id="contact"><a id href="/"></a>+44 (0) 1444 245414<div id="email">info@stevenkingsmachinery.co.uk</div></div>
			<div></div>
			<div id="navigation">
			<?php print $a->outputAdminMenu(); ?>
			</div>
			<?php print  $w->outputheader(); ?>
			
			<?php //print $m->outputSubMenu(15); ?>
			</div>
			
		</div>	
		
		
	</div>
	<div id="wrapper">
	     
	   
		
		<div id="content">

			<?php print $a->outputAdminContent();?>
		</div >
		     
		
		
	<div class="clearall"></div>
	     
	</div>
	<div id="footer">
		<div id="footertext">
			Site Map   |   Contact us   |   Terms & conditions   |   Legal   |   Privacy Policy   |   Accessibility
		</div>	
	</div >

</body>
<?php
}
?>


<?php
$w->outputClose();
    
?>