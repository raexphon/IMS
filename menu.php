<?php 
// MENU
?>
<!--<script src="js/custom.js"></script>-->	
<?php $getvar= (isset($_GET['r'])) ? $_GET['r'] : ""; ?>
<div id="menuColumn" class="hidden-xs menuColumn">
	<div id="imsMenu">	
		<ul id="RootMenu" class="dropdown-menu dropdown-menu-right">			
			<li class="fabio <?php echo acv($getvar, "dashbrd", $hsh="y") ?>"><a id="home" href="index.php?r=<?php echo base64url_encode("dashbrd"); ?>" onClick="breadcrumb(this)">Dashboard</a></li>			
			<li class="ims dropdown-submenu dropdown-menu-right <?php echo acv($getvar, "itemmntc", $hsh="y") ?>">			
				<a id="item" href="index.php?r=<?php echo base64url_encode("itemmntc"); ?>" onClick="breadcrumb(this)">Item Maintenance</a>
				<ul class="dropdown-menu">					
					<li><a href="index.php?r=<?php echo base64url_encode("pline"); ?>">Product Line</a></li>
					<li class="divider"></li>
					<li><a href="#">Pricing Maintenance</a></li>					
				</ul>
			</li>
			<li class="divider"></li>
			<li class="ims"><a href="#">Vendor</a></li>		
			<li class="ims"><a href="#">Purchase</a></li>
			<li class="divider"></li>
			<li class="ims"><a href="#">Customer</a></li>		
			<li class="ims"><a href="#">Sales </a></li>
			<li class="divider"></li>
			<li class="ims dropdown-submenu dropdown-menu-right">
				<a href="#">Outstanding</a>
				<ul class="dropdown-menu">
					<li><a href="#">Purchases Outstanding</a></li>
					<li class="divider"></li>
					<li><a href="#">Sales Outstanding</a></li>				
				</ul>
			</li>
			<li class="divider"></li>
		</ul>	
	</div>
</div>

<nav id="ewMobileMenu" role="navigation" class="navbar navbar-default visible-xs hidden-print">
	<div class="container-fluid"><!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button data-target="#ewMenu" data-toggle="collapse" class="navbar-toggle" type="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="javascript:void(0);">IMS - Inventory Management System</a>
							</div>
		<div id="ewMenu" class="collapse navbar-collapse" style="height: auto;"><!-- Begin Main Menu -->
			<!-- Begin Main Menu -->
			<ul id="MobileMenu" class="nav navbar-nav">
				<li id="mmi_dashboard_php" class="active"><a href="dashboard.php">Dashboard</a></li>
				<li id="mmi_a_stock_items" class="dropdown"><a class="ewMenuLink" href="a_stock_itemslist.php?cmd=resetall"><span class="icon-arrow-right"></span></a><a class="ewDropdown" href="a_stock_itemslist.php?cmd=resetall">Stock Items<span class="icon-arrow-down"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li id="mmi_a_stock_categories"><a href="a_stock_categorieslist.php">Stock Categories</a></li>
						<li id="mmi_a_unit_of_measurement"><a href="a_unit_of_measurementlist.php">Unit of Measurement</a></li>
					</ul>
				</li>
				<li id="mmi_a_suppliers"><a href="a_supplierslist.php">Suppliers</a></li>
				<li id="mmi_a_purchases"><a href="a_purchaseslist.php?cmd=resetall">Purchases</a></li>
				<li id="mmi_a_customers"><a href="a_customerslist.php">Customers</a></li>
				<li id="mmi_a_sales"><a href="a_saleslist.php?cmd=resetall">Sales</a></li>
				<li id="mmci_Outstandings" class="dropdown"><a class="ewDropdown" href="#">Outstandings<span class="icon-arrow-down"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li id="mmi_view_purchases_outstandings"><a href="view_purchases_outstandingslist.php">Purchases Outstandings</a></li>
						<li id="mmi_view_sales_outstandings"><a href="view_sales_outstandingslist.php">Sales Outstandings</a></li>
						<li id="mmi_a_payment_transactions"><a href="a_payment_transactionslist.php?cmd=resetall">Payment Transactions</a></li>
					</ul>
				</li>
			</ul>
			<li id="mmi_logout"><a href="logout.php"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
			<!-- End Main Menu -->
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>

<script>
// Add active class to the current button (highlight it)
var header = document.getElementById("RootMenu");
var btns = header.getElementsByClassName("ims");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {	
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}
</script>