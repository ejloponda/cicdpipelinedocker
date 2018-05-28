<script>
	$(function() {
		$('#delete_dosage_form').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}
				$('#delete_dosage_form_wrapper').modal('hide');
				window.location.hash = "dosage_list";
             	reload_content("dosage_list");
			}, beforeSubmit: function(o) {
			}, dataType : "json"
		});
	});
</script>
<div class="modal-dialog" style="width:35%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h4 id="myModalLabel">Delete Dosage Type?</h4>
		</div>
		<div class="modal-body">
			<form id="delete_dosage_form" name="delete_dosage_form" method="post" action="<?php echo url('module_management/deleteDosageType'); ?>">
				<input type="hidden" id="id" name="id" value="<?php echo $dosage['id']; ?>">
				Are you sure you want to delete <b><?php echo $dosage['dosage_type'] ?></b>?
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    <button class="btn btn-danger submit_button" onclick="$('#delete_dosage_form').submit();">Delete</button>
		</div>
	</div>
</div>

