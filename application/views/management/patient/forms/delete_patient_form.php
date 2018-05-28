<script>
	$(function() {
		$('#delete_user_form').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}

				$('#delete_patient').modal('hide');
				window.location.hash = "patient";
    			reload_content("patient");
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
		<h4 id="myModalLabel">Delete User? </h4>
	  </div>
	  <div class="modal-body">
	    <form id="delete_user_form" name="delete_user_form" method="post" action="<?php echo url('patient_management/delete_patient'); ?>">
			<input type="hidden" id="id" name="id" value="<?php echo $patient['id']; ?>">
			Are you sure you want to delete <b><?php echo convert_word("{$patient['firstname']} {$patient['lastname']}"); ?></b> in the database?<br/>This will be permanently deleted.
		</form>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button class="btn btn-danger submit_button" onclick="$('#delete_user_form').submit();">Delete</button>
	  </div>
	</div>
</div>
