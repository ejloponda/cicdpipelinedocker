<script>
	$(function() {
		$('#delete_regimen_med_record').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
					getMedicineTable(o.regimen_id, o.version_id);
          			// default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}

				$('#delete_regimen_entry').modal('hide');
				
    			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
				
			}, beforeSubmit: function(o) {

			},
			dataType : "json"

		});
	});
</script>

<div class="modal-dialog" style="width:45%; word-wrap:normal;">
	<div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">Delete Medicine Record</h4>
	  </div>
	  <div class="modal-body">
	    <form id="delete_regimen_med_record" name="delete_regimen_med_record" method="post" action="<?php echo url('regimen_management/delete_entry'); ?>">
			<input type="hidden" id="id" name="reg_id" value="<?php echo $record['id']; ?>">
			Are you sure you want to delete this in the database? <br/>This will be permanently deleted.
		</form>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button class="btn btn-danger submit_button" onclick="$('#delete_regimen_med_record').submit();">Delete</button>
	  </div>
	</div>
</div>
