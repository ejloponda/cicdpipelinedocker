<script>
	$(function() {
		$('#delete_doctors_form').ajaxForm({
			success:function(o) {
				$('#delete_doctors_form_wrapper').modal('hide');
				window.location.hash = "doctors_list";
             	reload_content("doctors_list");
             	default_success_confirmation({message : o.message, alert_type: "alert-success"});
             	$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
			}, beforeSubmit: function(o) {
			},dataType : "json"
		});
	});
</script>
<div class="modal-dialog" style="width:40%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h4 id="myModalLabel">Delete Doctor?</h4>
		</div>
		<div class="modal-body">
			<form id="delete_doctors_form" name="delete_doctors_form" method="post" action="<?php echo url('module_management/delete_doctors'); ?>">
				<input type="hidden" id="id" name="id" value="<?php echo $doctors['id']; ?>">
				Are you sure you want to delete <?php echo $doctors['full_name'] ?>?
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    <button class="btn btn-danger submit_button" onclick="$('#delete_doctors_form').submit();">Delete</button>
		</div>
	</div>
</div>

