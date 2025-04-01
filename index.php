<?php
session_start();
// each client should remember their session id for EXACTLY 1 day
session_set_cookie_params(86400);
require_once( $_SERVER["DOCUMENT_ROOT"]."/core/autoload.php" );
require_once( $_SERVER["DOCUMENT_ROOT"]."/includes/kingsDB.inc.php" );
error_reporting(0);

$w = new kingsWebpage();
$u = new kingsUser();


if ($_GET["p"]=='logout'){
	$u->processLogout();
header( "location: /");
}
if ($_POST['wp-submit'] == 'Log In'){
        $u->processLogin();
}

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
		

$version = '1.2'; 		
$w->setSiteTitle();
$w->setSiteMeta();
$w->setOwnerMeta( "Ben Smith - Preview (www.preview.co.uk)", "Preview" );
$w->attachStyle( "style.css?=$version" );
$w->attachStyle( "calender-style.css" );
$w->attachScript("jquery.js");
$w->attachScript("inline-edit.js");
$w->attachScript("jquery.validate.js");
$w->attachScript("jquery_table_sort.js");
$w->attachScript("jquery.form.js");

$w->attachScript("kings.js");

$w->setFavIcon( "./" );

$w->outputStandardHtmlHeaders();


if (!$u->checkLogin()){
?>
	<div id="wrapper">
		<div id="content">			<br>

			<div id="logo" style="float:none;"><a href="/"><img src="/images/KingsColleges_ProductionMasthead.png" alt="Kings Education 2025" /></a>
			<br><br>

			<table style="border-left:none; border-right:none;">
				<tr><td>
					<strong style="font-size:1.2em;">Welcome to the Central Marketing Project <br>Management System.</strong><br><br>
					<p>
						Here you can request:<br>
						<li>Stationery</li>
						<li>Print projects</li>
						<li>Web and digital media projects</li>
						<li>Photographic or video projects</li>
						<li>Branded goods</li>
					</p>
					<p><br>
						If you already have a user ID please log in opposite or, if not, <br>contact us to request a log in:<br>
						<a style="color:black;font-weight:bold;" href="mailto:design@kingseducation.com">design@kingseducation.com</a>
					</p>
				</td>
				<td>
					<?php $_GET['p']=='error'?print'<div style="text-align:center; margin-bottom:8px; color:red;" id="logerror">Incorrect username or password</div>':null;?>
					<div>
				<form method="post" action="/" id="loginform" name="loginform">
					<p>
						<label>Username<br>
						<input type="text" tabindex="10" size="20" value="<?php echo $_COOKIE['userName']; ?>" class="input" id="user_login" name="log"></label>
					</p>
					<p>
						<label>Password<br>
						<input type="password" tabindex="20" size="20" value="<?php echo $_COOKIE['userPass']; ?>" class="input" id="user_pass" name="pwd"></label>
					</p>
					<p class="forgetmenot"><label><input style="width:20px;"  type="checkbox" tabindex="90" value="forever" id="rememberme" name="rememberme"> Remember Me</label></p>
					<p class="submit">
						<input type="submit" tabindex="100" value="Log In" class="button-primary" id="wp-submit" name="wp-submit" style="background: none repeat scroll 0% 0% #56004E; font-size: 12px;">
						<input type="hidden" value="1" name="testcookie">
					</p>
				</form>
			</td></tr>
			</table><br>
		<img src="/images/KingsEducation_Logo.png" alt="Kings Education" />	<br>
		</div>

	<div class="clearall"></div>  
	</div>
	<div id="footer">
		<div id="footertext">
			
		</div>	
	</div >

</body>
<?php
}else{
?>
<body>
	
		<div id="inSystemAlerts"><a  href="#" class="close" style="float:left;">X</a><div class="content"></div></div>
	
<div id="wrapper">	
	<div id ="top">
		<?php $m= new kingsMenu();
		?>
		<div id="header">
			<div id="logo"><a href="/"><img src="/images/KingsColleges_ProductionMasthead.png" alt="Kings Education" /></a>
			</div>
			<div id="info">
				<p>
					Currently logged in as: <?php echo  $_SESSION["user"]["name"] ?>
					<?php
					
					if ($_SESSION["user"]["client_id"]>0 && $_SESSION["user"]["type"]==0){
						$profile ='/profile-client';
					}else{
						$profile ='/profile';
					}
					?>
					
					<?php
						if($_SESSION['user']['admin']==1){// admin user
									$allusers = $u->getAllnonAdminUsers();
									?>
									<form action="/" style="margin-top:10px;"><span style="margin-right:10px;">Switch user:</span>
													<select name="user-login" class="user-login">
													<?php foreach( $allusers as $user){?>
													<option email="<?=$user->email?>" password="<?php echo $user->password?>"><?php echo $user->name?></option>
															<?php }?>
												</select>
									</form>
			<?php }?>
					
					<div style="float:right;" class="button"><a href="/logout">+ Logout</a></div>  <div style="float:right;" class="button"><a href="<?php print $profile ?>">+ Settings</a></div>
			</div>
				<div id="navigation">
					<?php
					switch ($w->outputheader()){
						case 13:
							
						    if($_SESSION["user"]["client_id"] && $_SESSION["user"]["type"]==0){
								print 'Sorry you do not have permission to view these pages<br><br> <a href="/project-request-form">home</a><br><br>';
								die();
							}else{
								print $w->button('Back to all projects','/projects');
								print $w->projectTop().'<br>';
								print $m->outputSubMenu(13);
								if ($w->projectTop()=='<div class="clear"></div><h3><div>This task does not belong to a project</div></h3>'){
								print '<script type="text/javascript">
										hideNav();
									 </script>';
								}
							}
                        break;
						case 15:
                          print $m->outputSubMenu(15);
                        break;
				
						case 7:
						case 8:
								if($_SESSION["user"]["client_id"]){
								print $m->outputSubMenu(15);
								}else{
								print $m->outputSubMenu(13);		
								}
                        break;
								
                        default:
						    if($_SESSION["user"]["client_id"] && $_SESSION["user"]["type"]==0 && $_GET['s']!='personal'){
								print 'Sorry you do not have permission to view these pages<br><br> <a href="/project-request-form">home</a><br><br>';
								die();
							}else{
                          print $m->outputMenu();
							}
                        break;
						
					}
					?>

				</div>
			</div>
			
		</div>
			<div id="content">
			<?php print  $w->outputContent(); ?>
			<div class="clearall"></div>
		</div >
			
	</div>		
	
	     
	</div>
	<div id="footer">
		<div id="footertext">
				<img src="/images/KingsEducation_Logo.png" alt="Kings Education" />	<br><br><br>
		</div>	
	</div >

</body>
<?php
}
$w->outputClose();
?>