<script>
$(function() {
	$('.tipsy-inner').remove();
	$("#add_user_role_form").ajaxForm({
		success: function(o) {
		  
		  $('#add_user_access_form').modal('hide');
		  reload_content("#roles");
		},
		beforeSubmit: function(o) {
		}
	});

});
</script>
<div class="modal-dialog" style="width:50%; word-wrap:normal;">
	<div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel"><img src="<?php echo BASE_FOLDER; ?>themes/images/header-role-type.png"> Add User Roles</h4>
	  </div>
	  <div class="modal-body">
	  	<form action="<?php echo url('access_permissions/saveRoleTypeForm') ?>" id="add_user_role_form" method="post" style="width: 100%;">
	  		<section id="role-input">
		  		Access Permissions <span>*</span> <br/>
				<input type="text" name="role_name" class="textbox validate[required] add_user_roles_form" placeholder="Input Role Name Here">
			</section>
			<br/>
			<section id="form">
				Status <br/>
				<select name="status" class="select add_user_roles_form" style="width: 150px;">
					<option value="1">Enabled</option>
					<option value="0">Disabled</option>
				</select>
			</section>
	  	</form>
	  </div>
	  <div class="modal-footer">
	  	<section id="buttons">
			<button class="form_button" onClick="$('#add_user_role_form').submit();">Save</button>
		</section>	
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>
</div>