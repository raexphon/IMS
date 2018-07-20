<div id="productModalDetail" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="product_form">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Product Line Update</h4>
				</div><!-- Close Modal Header -->
				<div class="modal-body">
					<div class="row" style="margin:0px;">
						<div class="form-group" style="float:left; width:30%;">								
							<label>*Product Line Code</label>
							<input type="text" name="pline_code" id="pline_code" class="form-control" style="text-transform: uppercase;" maxlength="20" onclick="this.select()" required />								
						</div>											
						<div class="form-group" style="float:right; width:50%;">
							<label>Product Line Description</label>
							<textarea name="pline_desc" id="pline_desc"  rows="3" maxlength="255" onclick="this.select()" style="text-transform: uppercase;" class="form-control"/></textarea>
						</div>
					</div>
					<div class="row" style="margin:0px;">
						<div class="form-group" style="float:left; width:50%;">
							<label>Price Level Code</label></br>															
							<div class="input-group">
								<input  name="type" value="" class="form-control" id="pline_pricecode"  onclick="this.select()" placeholder="Select price code..." required />
								<?php
								//Fill price code
								fill_pricecode($connect);								
								?>
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" id="addpricecode">Add</button>
							</span>
						</div><!-- Close Input Box -->
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