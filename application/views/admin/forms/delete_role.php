<script>
	$(function() {
		$('#delete_user_role_form').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}

				$('#delete_user_form_wrapper').modal('hide');
				window.location.hash = "roles";
    			reload_content("roles");
    			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
				
			}, beforeSubmit: function(o) {

			},
			dataType : "json"

		});
	});
</script>

<div class="modal-dialog" style="width:35%; word-wrap:normal;">
	<div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">Delete Role? </h4>
	  </div>
	  <div class="modal-body">
	    <form id="delete_user_role_form" name="delete_user_role_form" method="post" action="<?php echo url('access_permissions/delete_user_role'); ?>">
			<input type="hidden" id="id" name="id" value="<?php echo $role['id']; ?>">
			Are you sure you want to delete <b><?php echo convert_word("{$role['user_type']}"); ?></b> in the database?<br/>This will be permanently deleted.
		</form>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button class="btn btn-danger submit_button" onclick="$('#delete_user_role_form').submit();">Delete</button>
	  </div>
	</div>
</div>
