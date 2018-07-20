<!-- PRODUCT LINE Product Modal -->
<div id="msgboxModal" class="modal fade">		
	<div class="modal-dialog">
		<form method="post" id="msgbox_form">
			<div class="modal-content">					
				<div class="modal-header">
					<!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
					<h4 class="modal-title"><i class="fa fa-plus"></i></h4>
				</div><!-- Close Modal Header -->
				<div class="modal-body">
					<div class="form-group">
						<div class="msglbl"></div>
						<!--CODE JAVASCRIPT FOR DINAMICALLY TEST-->
					</div><!-- Close Formal Group -->					
				</div><!-- Close Modal Body -->
				<div class="modal-footer">
					<input type="hidden" name="product_code" id="product_code" />
					<input type="hidden" name="product_id" id="product_id" />
					<input type="hidden" name="btn_action" id="btn_action" />
					<input type="submit" name="yes" id="yes" class="btn btn-info" value="Yes" />					
					<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>					
				</div>
			</div><!-- Close Modal Content -->
		</form><!-- Close Form -->
	</div><!-- Close Modal Header -->
</div><!-- Close Product Modal -->	