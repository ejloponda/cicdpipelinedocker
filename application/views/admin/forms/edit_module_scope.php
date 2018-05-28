<script>
$(function() {
	$('.tipsy-inner').remove();
	$("#add_module_scope_form").ajaxForm({
		success: function(o) {
			if(o.is_successful) {
				default_success_confirmation({message : o.message, alert_type: "alert-success"});
			} else {
				default_success_confirmation({message : o.message, alert_type: "alert-error"});
			}
			$('#form_module_scope_modal').modal('hide');
			window.location.hash = "permissions";
			reload_content("permissions");
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
		},
		beforeSubmit: function(o) {
		},
		dataType : "json"
	});

});
</script>
<div class="modal-dialog" style="width:50%; word-wrap:normal;">
	<div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel"><img src="<?php echo BASE_FOLDER; ?>themes/images/header-lock.png"> Update Scope & Module</h4>
	  </div>
	  <div class="modal-body">
	  	<form action="<?php echo url('admin/saveModuleScope') ?>" id="add_module_scope_form" method="post" style="width: 100%;">
	  		<input type="hidden" name="module_id" value="<?php echo $module['id'] ?>">
	  		<section id="role-input">
		  		Scope <span>*</span> <br/>
				<input type="text" name="scope" class="textbox validate[required]" placeholder="Scope" value="<?php echo $module['scope'] ?>">
			</section>
			<br />
			<section id="role-input">
		  		Module <span>*</span> <br/>
				<input type="text" name="module" class="textbox validate[required]" placeholder="Module" value="<?php echo $module['module'] ?>">
			</section>
			<br/>
			<section id="form">
				Status <br/>
				<select name="status" class="select" style="width: 150px;">
					<option value="1" <?php echo ($module['status'] == 1 ? "selected" : "") ?>>Enabled</option>
					<option value="0" <?php echo ($module['status'] == 0 ? "selected" : "") ?>>Disabled</option>
				</select>
			</section>
	  	</form>
	  </div>
	  <div class="modal-footer">
	  	<section id="buttons">
			<button class="form_button" onClick="$('#add_module_scope_form').submit();">Save</button>
		</section>	
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>
</div>