<script>
	$(function() {
		$('.tipsy-inner').remove();
		$("#edit_quantity_form").validationEngine({scroll:false});
		$('#edit_quantity_form').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}
				$('#edit_quantity_form_wrapper').modal('hide');
				window.location.hash = "quantity_list";
             	reload_content("quantity_list");
			}, beforeSubmit: function(o) {
			}, dataType : "json"
		});
	});
</script>
<div class="modal-dialog" style="width:50%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h4 id="myModalLabel">Edit Quantity Type?</h4>
		</div>
		<div class="modal-body">
			<form id="edit_quantity_form" name="edit_quantity_form" method="post" action="<?php echo url('module_management/add_quantity_type'); ?>">
				<input type="hidden" id="id" name="id" value="<?php echo $quantity['id']; ?>">
				<ul id="form02">
					<li>Quantity Type Name<span>*</span></li>
					<li><input type="text" id="quantity_type" name="quantity_type" class="textbox validate[required] form_new_quantity_type" style="margin: 0px;" value="<?php echo $quantity['quantity_type']; ?>"></li>
				</ul>

				<section class="clear"></section>

					<ul id="form02">
						<li>Abbreviation<span>*</span></li>
						<li><input type="text" id="abbreviation" name="abbreviation" class="textbox validate[required] form_new_quantity_type" style="margin: 0px;" value="<?php echo $quantity['abbreviation']; ?>"></li>
					</ul>

				<section class="clear"></section>
					<ul id="form02">
						<li>Status</li>
						<li>
							<select name="status" id="status" class="select form_new_quantity_type" style="width: 100px;">
								<option value="Active" <?php echo ($quantity['status'] == "Active" ? "selected" : "") ?>>Active</option>
								<option value="Inactive" <?php echo ($quantity['status'] == "Inactive" ? "selected" : "") ?>>Inactive</option>
							</select>
						</li>
					</ul>
					
				<section class="clear"></section>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    <button class="btn btn-primary submit_button" onclick="$('#edit_quantity_form').submit();">Update</button>
		</div>
	</div>
</div>

