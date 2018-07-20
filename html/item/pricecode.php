<!-- PRICE CODE Product Modal -->
<div id="ModalPCode" class="modal fade">		
	<div class="modal-dialog">
		<form method="post" id="pcode_form">
			<div class="modal-content">					
				<div class="modal-header">
					<!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
					<h4 class="modal-title"><i class="fa fa-plus"></i> Add New Price Code</h4>
				</div><!-- Close Modal Header -->
				<div class="modal-body">
					<div class="form-group">
						<label>*Price Code</label>
						<input type="text" name="pcode_code" id="pcode_code" class="form-control" style="text-transform: uppercase;" maxlength="20" onclick="this.select()" required />
					</div><!-- Close Formal Group -->
					<div class="form-group">
						<label>Price Description</label>
						<input type="text" name="pcode_desc" id="pcode_desc"  style="text-transform: uppercase;" onclick="this.select()" class="form-control"/>
					</div><!-- Close Formal Group -->
					<div class="form-group">							
						<div class="row" style="margin:0px;">
							<div class="form-group" style="float:left;">									
								<input type="button" name="inspcode" id="inspcode" class="btn btn-info" value="Insert New Price Level" style="margin-bottom:5px;"/>
								<input type="button" name="delpcode" id="delpcode" class="btn btn-info" value="Remove Price Level" style="margin-bottom:5px;"/>																		
							</div>
							<div class="form-group" style="float:right;">
								<input  class="form-control" id="price_method1"  onclick="this.select()" value="CM" required/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 table-responsive">
							<table id="pcode_lvl" class="table table-bordered table-striped" >
								<thead>
									<tr>												
										<th>Price Level</th>
										<th>Pricing Method</th>									
										<th>From Quantity</th>
										<th>To Quantity</th>									
										<th>Markup Amount</th>									
										<th>Unit Price</th>
										<th><input type="checkbox" class="checkall" id="chkpcode" name="chkpcode"/></th>												
									</tr>
								</thead>																	
							</table>
						</div>
					</div>
				</div><!-- Close Modal Body -->
				<div class="modal-footer">
					<input type="hidden" name="product_id" id="product_id" />
					<input type="hidden" name="btn_action" id="btn_action" />
					<input type="submit" name="new_pcode" id="new_pcode" class="btn btn-info" value="Add" />
					<!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
				</div>
			</div><!-- Close Modal Content -->
		</form><!-- Close Form -->
	</div><!-- Close Modal Header -->
</div><!-- Close Product Modal -->	