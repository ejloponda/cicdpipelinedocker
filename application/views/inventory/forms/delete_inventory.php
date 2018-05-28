<script>
	$(function() {
		$('#delete_item_inventory').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-danger"});
          		}

				$('#delete_inventory_item').modal('hide');
				window.location.hash = "lists";
				reload_content("lists");
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
		<h4 id="myModalLabel">Delete Item</h4>
	  </div>
	  <div class="modal-body">
	    <form id="delete_item_inventory" name="delete_item_inventory" method="post" action="<?php echo url('inventory_management/deleteItemInventory'); ?>">
			<input type="hidden" id="id" name="item_id" value="<?php echo $inv['id']; ?>">
			Are you sure you want to delete <b><?php echo $inv['medicine_name']; ?> - <?php echo $inv['generic_name']; ?></b> in the database? <br/>This will be permanently deleted.
		</form>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button class="btn btn-danger submit_button" onclick="$('#delete_item_inventory').submit();">Delete</button>
	  </div>
	</div>
</div>
