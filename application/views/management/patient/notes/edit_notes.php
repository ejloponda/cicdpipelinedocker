<div class="modal-dialog"> 
	<script>
	var patient_id = $('#patient_id').val();
		$(function(){
			$("#edit_notes_form").ajaxForm({
		        success: function(o) {
		      		if(o.is_successful) {
		      			$("#edit_notes_form_wrapper").modal("hide");
		
		      			viewNotes(patient_id);
		      		}
		        },
		        
		        dataType : "json"
		    });

		    $(".select_btn").on('click', function(){
				$("#edit_notes_form").submit();
				$("#edit_notes_form_wrapper").modal("hide");
				viewNotes(patient_id);
		    });
		});
	</script>
	<div class="modal-content jBox-content custom-modal-content">
		<div class="modal-body custom-modal-body">
		<form id="edit_notes_form" method="post" action="<?php echo url('patient_management/savePatientNotes') ?>" style="width: 100%;">
		<input type="hidden" name="patient_id" id="patient_id" value="<?php echo $patient_notes['patient_id'] ?>">
		<ul id="form02" class="custom-form">
				<li>Notes</li>
				<li><textarea name="edit_notes" style="max-width:363px; width:363px; max-height: 87px; height: 87px;" value = ""><?php echo $patient_notes['notes'];?></textarea></li>
				<input type="hidden" name="notes_id" id="notes_id"value="<?php echo $patient_notes['id'] ?>">
			</ul>
				<div class="clear"></div>
			</form>
		</div>
		<div class="modal-footer jBox-Confirm-footer custom-modal-footer">
			<button type="button" class="btn btn-default jBox-Confirm-button select_btn jBox-Confirm-button-submit" style="border-color: #5FC04C;">Save</button>
			<button type="button" class="btn btn-default jBox-Confirm-button jBox-Confirm-button-cancel" data-dismiss="modal">Cancel</button>
		</div>


	</div>
</div>
