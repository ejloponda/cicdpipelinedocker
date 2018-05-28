<script>
	$(function() {
		$('#delete_calendar_form').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}
				$('#delete_calendar_form_wrapper').modal('hide');
				window.location.hash = "location_list";
             	reload_content("location_list");
			}, beforeSubmit: function(o) {
			}, dataType : "json"
		});
	});
</script>
<div class="modal-dialog" style="width:35%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h4 id="myModalLabel">Delete Type?</h4>
		</div>
		<div class="modal-body">
			<form id="delete_calendar_form" name="delete_calendar_form" method="post" action="<?php echo url('module_management/deleteCalendarType'); ?>">
				<input type="hidden" id="id" name="id" value="<?php echo $calendar_dropdown['id']; ?>">
				Are you sure you want to delete <b><?php echo $calendar_dropdown['value'] ?></b>?
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    <button class="btn btn-danger submit_button" onclick="$('#delete_calendar_form').submit();">Delete</button>
		</div>
	</div>
</div>

