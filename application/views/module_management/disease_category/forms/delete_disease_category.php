<script>
	$(function() {
		$('#delete_disease_category_form').ajaxForm({
			success:function(o) {
				$('#delete_disease_category_form_wrapper').modal('hide');
				window.location.hash = "disease_category";
             	reload_content("disease_category");
			}, beforeSubmit: function(o) {
			},
		});
	});
</script>
<div class="modal-dialog" style="width:35%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h4 id="myModalLabel">Delete Disease Category?</h4>
		</div>
		<div class="modal-body">
			<form id="delete_disease_category_form" name="delete_disease_category_form" method="post" action="<?php echo url('module_management/delete_disease_category'); ?>">
				<input type="hidden" id="id" name="id" value="<?php echo $disease['id']; ?>">
				Are you sure you want to delete <?php echo $disease['disease_name'] ?>?
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    <button class="btn btn-danger submit_button" onclick="$('#delete_disease_category_form').submit();">Delete</button>
		</div>
	</div>
</div>

