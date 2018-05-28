<script>
	$(function() {
		$('#delete_module_scope_form').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}

				$('#delete_user_form_wrapper').modal('hide');
				window.location.hash = "permissions";
    			reload_content("permissions");
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
		<h4 id="myModalLabel">Delete Access Permission</h4>
	  </div>
	  <div class="modal-body">
	    <form id="delete_module_scope_form" name="delete_module_scope_form" method="post" action="<?php echo url('admin/ExecuteDeleteModuleScope'); ?>">
			<input type="hidden" id="id" name="module_id" value="<?php echo $module['id']; ?>">
			Are you sure you want to delete <b>Scope: <?php echo $module['scope']; ?> - Module: <?php echo $module['module']; ?></b> in the database? <br/>This will be permanently deleted.
		</form>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button class="btn btn-danger submit_button" onclick="$('#delete_module_scope_form').submit();">Delete</button>
	  </div>
	</div>
</div>
