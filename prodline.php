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
						<h3 class="panel-title">Product Line List</h3>
					</div>
				
					<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
						<button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>						
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table id="pline_data" class="table table-bordered table-striped" style="width:100%;">
							<thead>
								<tr>
									<th></th>
									<th>ProductLine Code</th>
									<th>ProductLine Description</th>
									<th>Price Code</th>																									
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
	
<!-- INCLUDE PRODLINE VIEW -->
<?php include('html/prodline/prodline_view.php'); ?>

<!-- INCLUDE PRODLINE UPDATE -->
<?php include('html/prodline/prodline_detail.php'); ?>

<!-- OPEN MSGBOX Modal -->
<?php include('html/msgbox.php'); ?>

<!-- Load Item Javascript-->
<script src="js/custom/prodline.js"></script>
<script>
/* Calling the javascript file */
$(document).ready(function() {	
	pline_set();	
});

</script>