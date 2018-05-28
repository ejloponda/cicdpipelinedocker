<script>
$(function(){
	if($('.tipsy-inner')) {
		$('.tipsy-inner').remove();
	}

	$("#form_user_role").validationEngine({scroll:false});
	$("#form_user_role").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_ACCESS_PERMISSION_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      			window.location.hash = "roles";
      			reload_content("roles");
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        dataType : "json"
    });

    $(".add_user_roles_form").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_ACCESS_PERMISSION_FORM_CHANGE = true;
			}
	});

});
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-role-type.png"></li>
			<li><h1>Update Role Types</h1></li>
		</ul>
		
		<ul id="controls">
			<li><img class="icon" onClick="$('#form_user_role').submit();" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li class="firm_admin_roles"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></li>
		</ul>
		<div class="clear"></div>
	</hgroup>

	<h3>Update User Access</h3>
	<form method="post" id="form_user_role" action="<?php echo url('access_permissions/update_roles'); ?>" style="width:100%;">
	<section id="role-input">
		<input type="hidden" name="user_type_id" value="<?php echo $user_type['id'] ?>">
		<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
		<input type="text" name="user_type" readonly="readonly" class="textbox validate[required]" value="<?php echo $user_type['user_type'] ?>">
	</section>
	<br/>
	<table class="datatable table">
		<thead>
			<th style="text-align:center !important;" width="15%">Scope</th>
			<th style="text-align:center !important;" width="15%">Module</th>
			<th style="text-align:center !important;" width="15%">Can Add</th>
			<th style="text-align:center !important;" width="15%">Can Edit</th>
			<th style="text-align:center !important;" width="15%">Can Delete</th>
			<th style="text-align:center !important;" width="15%">Can View</th>
		</thead>
		<tbody>
			<?php $counter = 0; ?>
			<?php foreach($user_module_list as $key=>$value): ?>
				<?php $roles = RPC_User_Access_Permission::findPermissionByUserTypeID(array("user_type_id" => $user_type['id'], "user_module_id" => $value['id'])) ?>
				<input type="hidden" name="option[<?php echo $counter ?>][permission_id]" value="<?php echo $roles['id'] ?>">
				<input type="hidden" name="option[<?php echo $counter ?>][module_id]" value="<?php echo $value['id'] ?>">
					<tr>
						<td width="15%"><?php echo $value['scope']; ?></td>
						<td width="15%"><?php echo $value['module']; ?></td>
						<td align="middle" width="15%"> <input name="option[<?php echo $counter ?>][add_box]" class="add_user_roles_form" type="checkbox" <?php echo ($roles['can_add'] == 1 ? "checked" : "") ?>></td>
						<td align="middle" width="15%"> <input name="option[<?php echo $counter ?>][edit_box]" class="add_user_roles_form" type="checkbox" <?php echo ($roles['can_update'] == 1 ? "checked" : "") ?>></td>
						<td align="middle" width="15%"> <input name="option[<?php echo $counter ?>][delete_box]" class="add_user_roles_form" type="checkbox" <?php echo ($roles['can_delete'] == 1 ? "checked" : "") ?>></td>
						<td align="middle" width="15%"> <input name="option[<?php echo $counter ?>][view_box]" class="add_user_roles_form" type="checkbox" <?php echo ($roles['can_view'] == 1 ? "checked" : "") ?>></td>
					</tr>
				<?php $counter++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	</form>
	<br/><br/>

	<section id="buttons">
			<button class="form_button" onClick="$('#form_user_role').submit();">Save</button>
			<button class="form_button firm_admin_roles">Cancel</button>
		</section>			
</section>
	
<section class="clear"></section>