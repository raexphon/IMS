<div id="productModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="product_form">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Add Item Product</h4>
				</div><!-- Close Modal Header -->
				<div class="modal-body">
					<div class="row" style="margin:0px;">
						<div class="form-group" style="float:left; width:30%;">								
							<label>*Enter Item Code</label>
							<input type="text" name="item_code" id="item_code" class="form-control" style="text-transform: uppercase;" maxlength="20" onclick="this.select()" required />								
						</div>											
						<div class="form-group" style="float:right; width:50%;">
							<label>Enter Description</label>
							<textarea name="item_desc" id="item_desc"  rows="3" maxlength="255" onclick="this.select()" style="text-transform: uppercase;" class="form-control"/></textarea>
						</div>
					</div>
					<div class="row" style="margin:0px;">
						<div class="form-group" style="float:left; width:30%;">
							<label>Inactive</label></br>								
							<input name="item_active" id="item_active" type="checkbox" value="N" style="margin-left:5px;"></br>
						</div>
						<div class="form-group" style="float:right; width:50%;">
							<label>Quantity</label>								
							<input name="qty" id="qty" class="form-control" onclick="this.select()" required type="text" value="1" />
						</div>
					</div>
					<div class="row" style="margin:0px;">
						<div class="form-group" style="float:left; width:50%;">
							<label>*Product Line</label>							
							<div class="input-group">
								<input  name="type" class="form-control" id="src_prodline" placeholder="Select product line..." onclick="this.select()" required/>
								<?php
								//Fill product line 
								fill_prodline($connect);								
								?>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="addpline">Add</button>
								</span>
							</div>
						</div>
						<div class="form-group" style="float:right; width:40%;">
							<label>*Price Code</label>							
							<div class="input-group">
								<input  name="type" class="form-control" id="src_pricecode"  onclick="this.select()" placeholder="Select price code..." />
								<?php
								//Fill product line 
								fill_pricecode($connect);								
								?>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="addpricecode">Add</button>
								</span>
							</div>
						</div>														
					</div>
					<div class="row" style="margin:0px;">							
						<div class="form-group" style="float:left; width:20%">
							<label>Tax</label>									
							<div class="input-group">									
								<span class="input-group-addon">
									<input name="item_tax" id="item_tax" type="checkbox"value="TX" checked>
								</span>									
								<input name="item_taxvalue" id="item_taxvalue" class="form-control col-xs-2"  onclick="this.select()" required type="text" value="8.875">									
							</div>
						</div>
						<div class="form-group" style="float:right; width:30%;">
							<label>*Unit of Measurement</label></br>							
							<input  name="type" value="EACH" class="form-control" id="uom"  onclick="this.select()" required/>
							<?php
							//Fill product line 
							fill_uom($connect);								
							?>								
						</div>							
					</div>
					<div class="row" style="margin:0px;">
						<div class="form-group" style="float:left; width:35%">
							<label>Standard Cost</label>
							<div class="input-group">
								<span class="input-group-addon">$</span>								
								<input name="item_cost" id="item_cost" class="form-control" onclick="this.select()" type="text" value="0.00">
							</div>
						</div>
						<div class="form-group"  style="float:right; width:35%">
							<label style="float:right;">Last receipt</label>
							<input name="item_lrec" id="item_lrec" class="form-control" type="text" style="float:right">								
						</div>
					</div>
					<div class="row" style="margin:0px;">
						<div class="form-group" style="float:left; width:35%">
							<label>Standard Price</label>
							<div class="input-group">
								<span class="input-group-addon">$</span>								
								<input name="item_standardprice" id="item_standardprice" class="form-control" onclick="this.select()" type="text" value="0.00">
							</div>
						</div>
						<div class="form-group" style="float:right; width:35%">
							<label style="float:right;">Retail Price</label>
							<div class="input-group">
								<span class="input-group-addon">$</span>								
								<input name="item_retailprice" id="item_retailprice" class="form-control" onclick="this.select()" type="text" value="0.00" style="float:right; min-width:10px;">
							</div>
						</div>						
					</div>
					<hr />
					<div class="form-group">
						<div class="row" style="position:relative; left:0; width: 100%; text-align: center; margin:10px 0;">
							<label>Item Pricing Maintenance</label>
						</div>
						<div class="row" style="margin:0px;">
							<div class="form-group" style="float:left;">									
								<input type="button" name="insertplvl" id="insertplvl" class="btn btn-info" value="Insert Price Level" style="margin-bottom:5px;"/>
								<input type="button" name="removeplvl" id="removeplvl" class="btn btn-info" value="Remove Price Level" style="margin-bottom:5px;"/>
							</div>
							<div class="form-group" style="float:right;">																		
								<input  class="form-control" id="price_method"  onclick="this.select()" value="CM"/>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 table-responsive">
								<table id="item_pricelvl" class="table table-bordered table-striped" style="width:100%;">
									<thead>
										<tr>												
											<th>Price Level</th>
											<th>Pricing Method</th>									
											<th>From Quantity</th>
											<th>To Quantity</th>									
											<th>Markup Amount %</th>									
											<th>Unit Price</th>
											<th><input type="checkbox" class="checkall" /></th>												
										</tr>
									</thead>																	
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="product_id" id="product_id" />
					<input type="hidden" name="product_code" id="product_code" />
					<input type="hidden" name="btn_action" id="btn_action" />
					<input type="submit" name="add_item" id="add_item" class="btn btn-info" value="Add" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div><!-- Close Modal Footer -->
			</div><!-- Close Modal Content -->
		</form><!-- Close Form -->
	</div><!-- Close Modal-Dialog -->
</div><!-- Close Product Modal -->