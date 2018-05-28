<script>
	$(function() {
		$('#delete_cost_modifier_form').ajaxForm({
			success:function(o) {
				$('#delete_cost_modifier_form_wrapper').modal('hide');
				window.location.hash = "cost_modifier";
             	reload_content("cost_modifier");
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
		    <h4 id="myModalLabel">Delete Other Charges?</h4>
		</div>
		<div class="modal-body">
			<form id="delete_cost_modifier_form" method="post" action="<?php echo url('module_management/delete_cost_modifier'); ?>">
				<input type="hidden" id="oc_id" name="oc_id" value="<?php echo $oc['id']; ?>">
				Are you sure you want to delete "<?php echo $oc['cost_modifier'] ?>"?
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    <button class="btn btn-danger submit_button" onclick="$('#delete_cost_modifier_form').submit();">Delete</button>
		</div>
	</div>
</div>

