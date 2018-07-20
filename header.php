<?php
//header.php
?>
<div id="imsrow" class="FabioHeaderRow hidden-xs imsrow">
	<div class="hidden-xs col-sm-5" style="float: left;">
		<div><a href="." title="IMS - Inventory Management System"><img src="images/image1.png" alt=""></a></div>
	</div>
	<div class="col-sm-7" style="height: 30px;">
		  <div class="imsrow pull-right" style="padding-top: 8px; padding-bottom: 4px;"><strong><span style="font-family:arial;font-size:13px;color:white;text-transform:normal">IMS - Inventory Management System&nbsp;&nbsp;</span></strong></div><br>
	</div>
	<?php if (isset($_SESSION["uname"])) { ?>
	<div class="col-sm-7" style="height: 20px;">
		 <div class="pull-right" id="msUserName" style="padding-top: 8px; padding-bottom: 4px;">
			<font color="white">User Name: <strong><?php echo $_SESSION["uname"]; ?></strong> | 
				<ul class="nav navbar-nav navbar-right" >
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-top:0px !important; color:#FFF;">Logout</a>
						<ul class="dropdown-menu">
							<li><a class="glyphicon glyphicon-user"href="profile.php"> Profile</a></li>
							<li><a class="glyphicon glyphicon-log-out" href="logout.php"> Logout</a></li>
						</ul>
					</li>
				</ul>
				<!--<ul class="nav navbar-nav navbar-right">						
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Logout</a>
						<ul class="dropdown-menu">
							<li><a class="glyphicon glyphicon-user"href="profile.php"> Profile</a></li>
							<li><a class="glyphicon glyphicon-log-out" href="logout.php"> Logout</a></li>
						</ul>
					</li>
				</ul>-->
			</font>
		</div><br>
	</div>
	<?php }else{ ?>
	<div class="col-sm-7" style="height: 20px;">
		 <div class="HeaderRow pull-right" id="msUserName" style="padding-top: 8px; padding-bottom: 4px;">
			<font color="white">User Name: <strong>Guest</strong> | 
				<ul class="nav navbar-nav navbar-right">						
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> Logout</a>
						<ul class="dropdown-menu">
							<li><a class="glyphicon glyphicon-user"href="profile.php"> Profile</a></li>
							<li><a class="glyphicon glyphicon-log-out" href="logout.php"> Logout</a></li>
						</ul>
					</li>
				</ul>
			</font>
		</div><br>
	</div>
	<?php } ?>
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
				<li id="mmi_dashboard_php"><a href="dashboard.php">Dashboard</a></li>
				<li id="mmi_a_stock_items" class="dropdown active"><a class="ewMenuLink" href="a_stock_itemslist.php?cmd=resetall"><span class="icon-arrow-right"></span></a><a class="ewDropdown" href="a_stock_itemslist.php?cmd=resetall">Stock Items<span class="icon-arrow-down"></span></a><ul class="dropdown-menu" role="menu">
				<li id="mmi_a_stock_categories"><a href="a_stock_categorieslist.php">Stock Categories</a></li>
				<li id="mmi_a_unit_of_measurement"><a href="a_unit_of_measurementlist.php">Unit of Measurement</a></li>
				</ul>
				</li>
				<li id="mmi_a_suppliers"><a href="a_supplierslist.php">Suppliers</a></li>
				<li id="mmi_a_purchases"><a href="a_purchaseslist.php?cmd=resetall">Purchases</a></li>
				<li id="mmi_a_customers"><a href="a_customerslist.php">Customers</a></li>
				<li id="mmi_a_sales"><a href="a_saleslist.php?cmd=resetall">Sales</a></li>
				<li id="mmci_Outstandings" class="dropdown"><a class="ewDropdown" href="#">Outstandings<span class="icon-arrow-down"></span></a><ul class="dropdown-menu" role="menu">
				<li id="mmi_view_purchases_outstandings"><a href="view_purchases_outstandingslist.php">Purchases Outstandings</a></li>
				<li id="mmi_view_sales_outstandings"><a href="view_sales_outstandingslist.php">Sales Outstandings</a></li>
				<li id="mmi_a_payment_transactions"><a href="a_payment_transactionslist.php?cmd=resetall">Payment Transactions</a></li>
				</ul>
				</li>				
				</ul>
				<li id="mmi_logout"><a href="logout.php"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
				</ul>
<!-- End Main Menu -->
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
</div>
	