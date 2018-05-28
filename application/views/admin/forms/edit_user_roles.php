<script>
	$(function() {
		$(".tipsy").hide();
		$("#form-user-roles").validationEngine({scroll:false});
		$("#form-user-roles").ajaxForm({
            success: function(o) {
          		if(o.is_successful) {
          			IS_ADD_USER_ROLES_CHANGE = false;
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
				IS_ADD_USER_ROLES_CHANGE = true;
			}
		});
		
	 });
</script>
<section class="area">
		<hgroup id="area-header">
			<ul class="page-title">
				<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-role-type.png"></li>
				<li><h1>Add Role Types</h1></li>
			</ul>
			
			<ul id="controls">
				<li><img class="icon" onClick="$('#form-user-roles').submit();" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></li>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
				<li class="firm_admin_roles"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></li>
			</ul>
			<div class="clear"></div>
		</hgroup>
		<form id="form-user-roles" method="post" action="<?php echo url('admin/addNewRoles'); ?>">
			<section id="role-input">
				Select Access Permissions <span>*</span><br/>
				<input type="text" name="role_name" class="textbox validate[required] add_user_roles_form" value="<?php echo $role['role_name'] ?>" placeholder="Input Role Name Here">
				<input type="hidden" name="id" value="<?php echo $role['id'] ?>">
			</section>
			<br/>
			<section id="check-boxes">
				<strong>Manage Accounts</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_user" <?php echo ($role['add_user'] == "on" ? "checked" : ""); ?>>Add User Account</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_user" <?php echo ($role['edit_user'] == "on" ? "checked" : ""); ?>>Edit User Account</li>
					<li><input type="checkbox" class="add_user_roles_form" name="delete_user" <?php echo ($role['delete_user'] == "on" ? "checked" : ""); ?>>Delete User Account</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_user" <?php echo ($role['view_user'] == "on" ? "checked" : ""); ?>>View User Account</li>
					<br></br>
					<li><input type="checkbox" class="add_user_roles_form" name="view_access_permission" <?php echo ($role['view_access'] == "on" ? "checked" : ""); ?>>View Access Permissions</li>
				</ul>
				<section class="clear"></section>
			</section>
			
			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>
			
			<section id="check-boxes">
				<strong>User Roles</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_roles" <?php echo ($role['add_roles'] == "on" ? "checked" : ""); ?>>Add User Roles</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_roles" <?php echo ($role['edit_roles'] == "on" ? "checked" : ""); ?>>Edit User Roles</li>
					<li><input type="checkbox" class="add_user_roles_form" name="del_roles" <?php echo ($role['del_roles'] == "on" ? "checked" : ""); ?>>Delete User Roles</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_roles" <?php echo ($role['view_roles'] == "on" ? "checked" : ""); ?>>View User Roles</li>
				</ul>
				<section class="clear"></section>
			</section>

			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>

			<section id="check-boxes">
				<strong>Patient Management Module</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_patient" <?php echo ($role['add_patient'] == "on" ? "checked" : ""); ?>>Add Patient</li>
					<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="edit_patient" <?php echo ($role['edit_patient'] == "on" ? "checked" : ""); ?>>Edit Patient</li>
					<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="del_patient" <?php echo ($role['del_patient'] == "on" ? "checked" : ""); ?>>Delete Patient</li>
					<br></br>
					<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="view_pat_information" <?php echo ($role['view_pat_information'] == "on" ? "checked" : ""); ?>>View Patient Information</li>
					<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="view_pat_med_history" <?php echo ($role['view_pat_med_history'] == "on" ? "checked" : ""); ?>>View Patient Medical History</li>
					<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="view_pat_reg_history" <?php echo ($role['view_pat_reg_history'] == "on" ? "checked" : ""); ?>>View Patient Regimen History</li>
					<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="view_billing_accounting" <?php echo ($role['view_billing_accounting'] == "on" ? "checked" : ""); ?>>View Billing & Accounting History</li>
				</ul>
				<section class="clear"></section>
			</section>

			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>

			<section id="check-boxes">
				<strong>Regimen Management Module</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_regimen" <?php echo ($role['add_regimen'] == "on" ? "checked" : ""); ?>>Add Regimen</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_regimen" <?php echo ($role['edit_regimen'] == "on" ? "checked" : ""); ?>>Edit Regimen</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_regimen" <?php echo ($role['view_regimen'] == "on" ? "checked" : ""); ?>>View Regimen</li>
					<li><input type="checkbox" class="add_user_roles_form" name="del_regimen" <?php echo ($role['del_regimen'] == "on" ? "checked" : ""); ?>>Delete Regimen</li>
					<br></br>
					<li><input type="checkbox" class="add_user_roles_form" name="convert_regimen" <?php echo ($role['convert_regimen'] == "on" ? "checked" : ""); ?>>Convert to Regimen List</li>
					<li><input type="checkbox" class="add_user_roles_form" name="convert_invoice_reg" <?php echo ($role['convert_invoice_reg'] == "on" ? "checked" : ""); ?>>Convert to Invoice</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_returns_reg" <?php echo ($role['view_returns_reg'] == "on" ? "checked" : ""); ?>>View Returns</li>
				</ul>
				<section class="clear"></section>
			</section>

			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>
			
			<section id="check-boxes">
				<strong>Inventory Management Module</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_inventory" <?php echo ($role['add_inventory'] == "on" ? "checked" : ""); ?>>Add Inventory</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_inventory" <?php echo ($role['edit_inventory'] == "on" ? "checked" : ""); ?>>Edit Inventory</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_inventory" <?php echo ($role['view_inventory'] == "on" ? "checked" : ""); ?>>View Inventory</li>
					<li><input type="checkbox" class="add_user_roles_form" name="del_inventory" <?php echo ($role['del_inventory'] == "on" ? "checked" : ""); ?>>Delete Inventory</li>
					<br></br>
					<li><input type="checkbox" class="add_user_roles_form" name="add_returns_inv" <?php echo ($role['add_returns_inv'] == "on" ? "checked" : ""); ?>>Add Returns</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_returns_inv" <?php echo ($role['edit_returns_inv'] == "on" ? "checked" : ""); ?>>Edit Returns</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_returns_inv" <?php echo ($role['view_returns_inv'] == "on" ? "checked" : ""); ?>>View Returns</li>
				</ul>
				<section class="clear"></section>
			</section>

			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>

			<section id="check-boxes">
				<strong>Module Management</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_module" <?php echo ($role['add_module'] == "on" ? "checked" : ""); ?>>Add Module</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_module" <?php echo ($role['edit_module'] == "on" ? "checked" : ""); ?>>Edit Module</li>
					<li><input type="checkbox" class="add_user_roles_form" name="del_module" <?php echo ($role['del_module'] == "on" ? "checked" : ""); ?>>Delete Module</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_module" <?php echo ($role['view_module'] == "on" ? "checked" : ""); ?>>View Module</li>
				</ul>
				<section class="clear"></section>
			</section>
			<br>
			</form>
			<section id="buttons">
				<button class="form_button" onClick="$('#form-user-roles').submit();">Save</button>
				<button class="form_button firm_admin_roles">Cancel</button>
			</section>			
		</section>
	
<section class="clear"></section>