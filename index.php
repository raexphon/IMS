<?php
//index.php
include('phpfunc/database_connection.php');
include('phpfunc/function.php');
?>
<!DOCTYPE html>
<html>
	<?php 
	error_reporting(E_ALL); 
	ini_set('display_errors', 1);

	include('head.php');
	?>
	<body>	
<?php	
	echo "<div class='layout'>";	
	if(!isset($_SESSION["active"])|| $_SESSION["active"]=='N'){	//Login in the db
		include('login.php');		
	}else{		
		include('header.php');//Header
		echo '<div id="contentTable" class="contentTable" style="min-height: 655px; height:100%; clear:left;">';
		echo '<div id="contentRow">';
		include('menu.php');//Menu
		include ('breadcrumbs.php');
		echo "<div id='contentColumn' class='contentColumn'>";
		include('toolbar.php');
		if (!isset($_GET["r"]) || !$_GET["r"]){//Check what page open 
			include('dashboard.php');	
		}else{
			switch (base64url_decode($_GET["r"])) {
				case "dashbrd":
					include('dashboard.php');
					break;
				case "itemmntc":
					include('item.php');
					break;
				case "pline":
					include('prodline.php');
					break;
			}
		}		
	}
	echo "</div>";
	include('footer.php');
	echo "</div>";
?>	
	</body>
</html>