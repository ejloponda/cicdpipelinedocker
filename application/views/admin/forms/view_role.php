<div class="modal-dialog" style="width:35%; word-wrap:normal;">
	<div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel"><?php echo "{$role['role_name']}" ?></h4>
	  </div>
	  <div class="modal-body">
	  	<div style="">
		    <section id="check-boxes">
					<strong>Manage Accounts</strong>
					<ul class="choices">
						<li><input type="checkbox" class="add_user_roles_form" name="add_user" <?php echo ($role['add_user'] == "on" ? "checked" : ""); ?> disabled>Add User Account</li>
						<li><input type="checkbox" class="add_user_roles_form" name="edit_user" <?php echo ($role['edit_user'] == "on" ? "checked" : ""); ?> disabled>Edit User Account</li>
						<li><input type="checkbox" class="add_user_roles_form" name="delete_user" <?php echo ($role['delete_user'] == "on" ? "checked" : ""); ?> disabled>Delete User Account</li>
						<br></br>
						<li><input type="checkbox" class="add_user_roles_form" name="view_access_permission" <?php echo ($role['view_access'] == "on" ? "checked" : ""); ?> disabled>View Access Permissions</li>
					</ul>
					<section class="clear"></section>
				</section>

				<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>
			
				<section id="check-boxes">
					<strong>User Roles</strong>
					<ul class="choices">
						<li><input type="checkbox" class="add_user_roles_form" name="add_roles" <?php echo ($role['add_roles'] == "on" ? "checked" : ""); ?> disabled>Add Roles</li>
						<li><input type="checkbox" class="add_user_roles_form" name="edit_roles" <?php echo ($role['edit_roles'] == "on" ? "checked" : ""); ?> disabled>Edit Roles</li>
						<li><input type="checkbox" class="add_user_roles_form" name="del_roles" <?php echo ($role['del_roles'] == "on" ? "checked" : ""); ?> disabled>Delete Roles</li>
						<li><input type="checkbox" class="add_user_roles_form" name="view_roles" <?php echo ($role['view_roles'] == "on" ? "checked" : ""); ?> disabled>View Roles</li>
					</ul>
					<section class="clear"></section>
				</section>

				<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>

				<section id="check-boxes">
					<strong>Patient Management Module</strong>
					<ul class="choices">
						<li><input type="checkbox" class="add_user_roles_form" name="add_patient" <?php echo ($role['add_patient'] == "on" ? "checked" : ""); ?> disabled>Add Patient</li>
						<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="edit_patient" <?php echo ($role['edit_patient'] == "on" ? "checked" : ""); ?> disabled>Edit Patient</li>
						<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="del_patient" <?php echo ($role['del_patient'] == "on" ? "checked" : ""); ?> disabled>Delete Patient</li>
						<br></br>
						<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="view_pat_information" <?php echo ($role['view_pat_information'] == "on" ? "checked" : ""); ?> disabled>View Patient Information</li>
						<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="view_pat_med_history" <?php echo ($role['view_pat_med_history'] == "on" ? "checked" : ""); ?> disabled>View Patient Medical History</li>
						<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="view_pat_reg_history" <?php echo ($role['view_pat_reg_history'] == "on" ? "checked" : ""); ?> disabled>View Patient Regimen History</li>
						<li><input type="checkbox" class="add_user_roles_form"class="add_user_roles_form" name="view_billing_accounting" <?php echo ($role['view_billing_accounting'] == "on" ? "checked" : ""); ?> disabled>View Billing & Accounting History</li>
					</ul>
					<section class="clear"></section>
				</section>

				<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>

				<section id="check-boxes">
					<strong>Regimen Management Module</strong>
					<ul class="choices">
						<li><input type="checkbox" class="add_user_roles_form" name="add_regimen" <?php echo ($role['add_regimen'] == "on" ? "checked" : ""); ?> disabled>Add Regimen</li>
						<li><input type="checkbox" class="add_user_roles_form" name="edit_regimen" <?php echo ($role['edit_regimen'] == "on" ? "checked" : ""); ?> disabled>Edit Regimen</li>
						<li><input type="checkbox" class="add_user_roles_form" name="view_regimen" <?php echo ($role['view_regimen'] == "on" ? "checked" : ""); ?> disabled>View Regimen</li>
						<li><input type="checkbox" class="add_user_roles_form" name="del_regimen" <?php echo ($role['del_regimen'] == "on" ? "checked" : ""); ?> disabled>Delete Regimen</li>
						<br></br>
						<li><input type="checkbox" class="add_user_roles_form" name="convert_regimen" <?php echo ($role['convert_regimen'] == "on" ? "checked" : ""); ?> disabled>Convert to Regimen List</li>
						<li><input type="checkbox" class="add_user_roles_form" name="convert_invoice_reg" <?php echo ($role['convert_invoice_reg'] == "on" ? "checked" : ""); ?> disabled>Convert to Invoice</li>
						<li><input type="checkbox" class="add_user_roles_form" name="view_returns_reg" <?php echo ($role['view_returns_reg'] == "on" ? "checked" : ""); ?> disabled>View Returns</li>
					</ul>
					<section class="clear"></section>
				</section>

				<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>
				
				<section id="check-boxes">
					<strong>Inventory Management Module</strong>
					<ul class="choices">
						<li><input type="checkbox" class="add_user_roles_form" name="add_inventory" <?php echo ($role['add_inventory'] == "on" ? "checked" : ""); ?> disabled>Add Inventory</li>
						<li><input type="checkbox" class="add_user_roles_form" name="edit_inventory" <?php echo ($role['edit_inventory'] == "on" ? "checked" : ""); ?> disabled>Edit Inventory</li>
						<li><input type="checkbox" class="add_user_roles_form" name="view_inventory" <?php echo ($role['view_inventory'] == "on" ? "checked" : ""); ?> disabled>View Inventory</li>
						<li><input type="checkbox" class="add_user_roles_form" name="del_inventory" <?php echo ($role['del_inventory'] == "on" ? "checked" : ""); ?> disabled>Delete Inventory</li>
						<br></br>
						<li><input type="checkbox" class="add_user_roles_form" name="add_returns_inv" <?php echo ($role['add_returns_inv'] == "on" ? "checked" : ""); ?> disabled>Add Returns</li>
						<li><input type="checkbox" class="add_user_roles_form" name="edit_returns_inv" <?php echo ($role['edit_returns_inv'] == "on" ? "checked" : ""); ?> disabled>Edit Returns</li>
						<li><input type="checkbox" class="add_user_roles_form" name="view_returns_inv" <?php echo ($role['view_returns_inv'] == "on" ? "checked" : ""); ?> disabled>View Returns</li>
					</ul>
				</section>

				<div style="margin-bottom: 20px; margin-top: 1px; border-bottom: 1px solid #b7b7b7;"></div>

				<section id="check-boxes">
					<strong>Module Management</strong>
					<ul class="choices">
						<li><input type="checkbox" class="add_user_roles_form" name="add_module" <?php echo ($role['add_module'] == "on" ? "checked" : ""); ?> disabled>Add Module</li>
						<li><input type="checkbox" class="add_user_roles_form" name="edit_module" <?php echo ($role['edit_module'] == "on" ? "checked" : ""); ?> disabled>Edit Module</li>
						<li><input type="checkbox" class="add_user_roles_form" name="del_module" <?php echo ($role['del_module'] == "on" ? "checked" : ""); ?> disabled>Delete Module</li>
						<li><input type="checkbox" class="add_user_roles_form" name="view_module" <?php echo ($role['view_module'] == "on" ? "checked" : ""); ?> disabled>View Module</li>
					</ul>
					<section class="clear"></section>
				</section>

			</div>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>
</div>
