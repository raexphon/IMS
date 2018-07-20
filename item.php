<?php
if(!isset($_SESSION["active"])|| $_SESSION["active"]=='N'){	//Login in the db
	include('login.php');		
}
?>

<span id='alert_action'></span>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
						<h3 class="panel-title">Item Maintenance List</h3>
					</div>
				
					<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
						<button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>						
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table id="item_data" class="table table-bordered table-striped" style="width:100%;">
							<thead>
								<tr>
									<th></th>
									<th>Item Code</th>
									<th>Item Description</th>
									<th>Product Line</th>									
									<th>Purchase Standard Cost</th>
									<th>Standard Price</th>
									<th>Last Receipt</th>									
									<th>Status</th>									
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</thead>																	
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
<!-- OPEN WINDOW ITEM DETAILS -->
<?php include('html/item/item_detail.php'); ?>

<!-- OPEN WINDOW ITEM DETAILS MODE VIEW -->
<?php include('html/item/item_detail_view.php'); ?>

<!-- OPEN WINDOW ITEM DETAILS MODE UPDATE -->
<?php include('html/item/item_update.php'); ?>

<!-- OPEN WINDOW PRODUCT LINE -->
<?php include('html/item/prodline.php'); ?>

<!-- OPEN WINDOW PRICE CODE -->
<?php include('html/item/pricecode.php'); ?>
	
<!-- OPEN MSGBOX Modal -->
<?php include('html/msgbox.php'); ?>

<!-- Load Item Javascript-->
<script src="js/custom/item.js"></script>
<script>
/* Calling the javascript file */
$(document).ready(function() {
		item_set();
});

</script>