<div class="modal-dialog"> 
	<script>
		$(function(){
			$("#add_notes_form").ajaxForm({
		        success: function(o) {
		      		if(o.is_successful) {
		      			$("#add_notes_form_wrapper").modal("hide");
		      			openViewNotes(o.patient_id);
		      		}
		        },
		        
		        dataType : "json"
		    });

		    $(".select_btn").on('click', function(){
				$("#add_notes_form").submit();
		    });
		});
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<h3>Add Notes</h3>
		</div>
		<div class="modal-body">
		<form id="add_notes_form" method="post" action="<?php echo url('patient_management/savePatientNotes') ?>" style="width: 100%;">
		<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
		<ul id="form02">
				<li>Notes</li>
				<li><textarea name="notes" style="max-width:363px; width:363px; max-height: 87px; height: 87px;"></textarea></li>
			</ul>
				<div class="clear"></div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger select_btn">Save</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		</div>
	</div>
</div>