<script>
	$(function() {
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
				Select Access Permissions <span>*</span> <br/>
				<input type="text" name="role_name" class="textbox validate[required] add_user_roles_form" placeholder="Input Role Name Here">
			</section>
			<br/>
			<section id="check-boxes">
				<strong>Manage Accounts</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_user">Add User Account</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_user">Edit User Account</li>
					<li><input type="checkbox" class="add_user_roles_form" name="delete_user">Delete User Account</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_user">View User Account</li>
					<br></br>
					<li><input type="checkbox" class="add_user_roles_form" name="view_access_permission">View Access Permissions</li>
				</ul>
				<section class="clear"></section>
			</section>

			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>
			
			<section id="check-boxes">
				<strong>User Roles</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_roles">Add User Roles</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_roles">Edit User Roles</li>
					<li><input type="checkbox" class="add_user_roles_form" name="del_roles">Delete User Roles</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_roles">View User Roles</li>
				</ul>
				<section class="clear"></section>
			</section>

			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>

			<section id="check-boxes">
				<strong>Patient Management Module</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_patient">Add Patient</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_patient">Edit Patient</li>
					<li><input type="checkbox" class="add_user_roles_form" name="del_patient">Delete Patient</li>
					<br></br>
					<li><input type="checkbox" class="add_user_roles_form" name="view_pat_information">View Patient Information</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_pat_med_history">View Patient Medical History</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_pat_reg_history">View Patient Regimen History</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_billing_accounting">View Billing & Accounting History</li>
				</ul>
				<section class="clear"></section>
			</section>

			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>

			<section id="check-boxes">
				<strong>Regimen Management Module</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_regimen">Add Regimen</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_regimen">Edit Regimen</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_regimen">View Regimen</li>
					<li><input type="checkbox" class="add_user_roles_form" name="del_regimen">Delete Regimen</li>
					<br></br>
					<li><input type="checkbox" class="add_user_roles_form" name="convert_regimen">Convert to Regimen List</li>
					<li><input type="checkbox" class="add_user_roles_form" name="convert_invoice_reg">Convert to Invoice</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_returns_reg">View Returns</li>
				</ul>
				<section class="clear"></section>
			</section>

			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>
			
			<section id="check-boxes">
				<strong>Inventory Management Module</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_inventory">Add Inventory</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_inventory">Edit Inventory</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_inventory">View Inventory</li>
					<li><input type="checkbox" class="add_user_roles_form" name="del_inventory">Delete Inventory</li>
					<br></br>
					<li><input type="checkbox" class="add_user_roles_form" name="add_returns_inv">Add Returns</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_returns_inv">Edit Returns</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_returns_inv">View Returns</li>
				</ul>
				<section class="clear"></section>
			</section>

			<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>

			<section id="check-boxes">
				<strong>Module Management</strong>
				<ul class="choices">
					<li><input type="checkbox" class="add_user_roles_form" name="add_module">Add Module</li>
					<li><input type="checkbox" class="add_user_roles_form" name="edit_module">Edit Module</li>
					<li><input type="checkbox" class="add_user_roles_form" name="del_module">Delete Module</li>
					<li><input type="checkbox" class="add_user_roles_form" name="view_module">View Module</li>
				</ul>
				<section class="clear"></section>
			</section>

			</form>
			<br>
			<section id="buttons">
				<button class="form_button" onClick="$('#form-user-roles').submit();">Save</button>
				<button class="form_button firm_admin_roles">Cancel</button>
			</section>			
		</section>
	
<!-- <section class="clear"></section> -->