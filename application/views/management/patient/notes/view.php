<center>
<div style="overflow-y: auto; height: 200px;">
	<ul>
	<?php foreach ($patient_notes as $key => $value) { ?>
		<?php $user = User::findById(array("id" => $value['created_by'])) ?>

		<div class="view-notes-wrapper">
			<br/><b>By: </b><?php echo $user['firstname'] . " " . $user['lastname'] ?>
			<br/><b>Time: </b><?php echo Tool::humanTiming(strtotime($value['date_created'])) ?> ago
			<br/>
			<div class="notes-content"><span>Notes:</span> <b><?php echo $value['notes'] ?></b></div>
			<div class="notes-btn-hldr">
			<a href="javascript: void(0);" onClick="javascript: edit_notes(<?php echo $value['id'];?>);" class="edit_user table_icon custom-edit-btn" original-title="Edit"><i class="glyphicon glyphicon-edit"></i>Edit</a> |
			<a href="javascript: void(0);" onClick="javascript: delete_notes(<?php echo $value['id']?>);" class="delete_user table_icon custom-delete-btn" original-title="Delete"><i class="glyphicon glyphicon-trash"></i>Delete</a>
			</div>
			<hr class="custom-hr">
		</div>
	<?php } ?>
	</ul>
</div>
</center>

<script>
	function delete_notes(id){

		var confirm = new jBox('Confirm', {
		content: '<h3>Are you sure you want to delete note?</h3>',
		confirmButton: 'Yes',
		cancelButton: 'No',
		confirm: function(){
			$.post(base_url+"patient_management/deleteNotes", {id:id}, function(){
				viewNotes("<?php echo $patient['id'] ?>");
				edit_Notes("<?php echo $patient['id'] ?>");
			});	
		},
		cancel: function(){
			//$("#medicine_claim").attr('checked', false);
		},
		animation: {open: 'tada', close: 'pulse'}
		});
		confirm.open();
	}

	/*function edit_notes(id,notes){
		$.post(base_url + "patient_management/editNotes", {id:id}, function(o){
					$("#edit_notes_form_wrapper").html(o);
				});
	}*/
</script>