<script>
	$(function() {
		$('#delete_contact_person_form').ajaxForm({
			success:function(o) {
				$('#delete_contact_person_form_wrapper').modal('hide');
				contact_person_list("<?php echo $contact['patient_id']; ?>","false");
			}, beforeSubmit: function(o) {
			},
		});
	});
</script>
<div class="modal-dialog" style="width:35%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h4 id="myModalLabel">Delete Contact? </h4>
		</div>
		<div class="modal-body">
			<form id="delete_contact_person_form" name="delete_contact_person_form" method="post" action="<?php echo url('patient_management/delete_contact_person'); ?>">
				<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
		        <input type="hidden" id="edit_contact_patient_id" name="patient_id" value="<?php echo $contact['patient_id']; ?>">
				Are you sure you want to delete contact?
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    <button class="btn btn-danger submit_button" onclick="$('#delete_contact_person_form').submit();">Delete</button>
		</div>
	</div>
</div>

