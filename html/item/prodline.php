<!-- PRODUCT LINE Product Modal -->
<div id="productModalPLine" class="modal fade">		
	<div class="modal-dialog">
		<form method="post" id="pline_form">
			<div class="modal-content">					
				<div class="modal-header">
					<!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
					<h4 class="modal-title"><i class="fa fa-plus"></i> Add New Product Line</h4>
				</div><!-- Close Modal Header -->
				<div class="modal-body">
					<div class="form-group">
						<label>*Enter Item Code</label>
						<input type="text" name="pline_code" id="pline_code" class="form-control" style="text-transform: uppercase;" maxlength="20" onclick="this.select()" required />
					</div><!-- Close Formal Group -->
					<div class="form-group">
						<label>Enter Description</label>
						<input type="text" name="pline_desc" id="pline_desc"  style="text-transform: uppercase;" onclick="this.select()" class="form-control"/>
					</div><!-- Close Formal Group -->
					<div class="form-group">
						<label>*Price Code</label>							
						<div class="input-group">
							<input  name="type" value="" class="form-control" id="src_pricecode1"  onclick="this.select()" placeholder="Select price code..." required/>
							<?php
							//Fill price code
							fill_pricecode($connect);								
							?>
						</div><!-- Close Input Box -->
					</div><!-- Close Form Group -->
				</div><!-- Close Modal Body -->
				<div class="modal-footer">
					<input type="hidden" name="product_id" id="product_id" />
					<input type="hidden" name="btn_action" id="btn_action" />
					<input type="submit" name="new_pline" id="new_pline" class="btn btn-info" value="Add" />
					<!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
				</div>
			</div><!-- Close Modal Content -->
		</form><!-- Close Form -->
	</div><!-- Close Modal Header -->
</div><!-- Close Product Modal -->	