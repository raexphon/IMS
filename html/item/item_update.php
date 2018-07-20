<div id="productModalUpdate" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="product_form_update">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i>Item Product</h4>
				</div><!-- Close Modal Header -->
				<div class="modal-body">
					<div id="itemupdate"></div>
				</div><!-- Close Modal Body-->
				<div class="modal-footer">
					<input type="hidden" name="product_id" id="product_id" />
					<input type="hidden" name="btn_action" id="btn_action" />
					<input type="submit" name="update_item" id="update_item" class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div><!-- Close Modal Footer -->
			</div><!-- Close Modal Content -->
		</form><!-- Close Form -->
	</div><!-- Close Modal-Dialog -->
</div><!-- Close Product Modal -->