<div class="modal-dialog" style="width:75%; word-wrap:normal;" id="add-user-role-modal">
	<div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">View Roles</h4>
	  </div>
	  <div class="modal-body">
	  	<section id="role-input">
			<h1><?php echo $user_type['user_type'] ?></h1>
		</section>
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
							<td align="middle" width="15%"> <input name="option[<?php echo $counter ?>][add_box]" class="add_user_roles_form" type="checkbox" <?php echo ($roles['can_add'] == 1 ? "checked" : "") ?> disabled></td>
							<td align="middle" width="15%"> <input name="option[<?php echo $counter ?>][edit_box]" class="add_user_roles_form" type="checkbox" <?php echo ($roles['can_update'] == 1 ? "checked" : "") ?> disabled></td>
							<td align="middle" width="15%"> <input name="option[<?php echo $counter ?>][delete_box]" class="add_user_roles_form" type="checkbox" <?php echo ($roles['can_delete'] == 1 ? "checked" : "") ?> disabled></td>
							<td align="middle" width="15%"> <input name="option[<?php echo $counter ?>][view_box]" class="add_user_roles_form" type="checkbox" <?php echo ($roles['can_view'] == 1 ? "checked" : "") ?> disabled></td>
						</tr>
					<?php $counter++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>
</div>