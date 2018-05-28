<script>
	$(function() {
		$('#delete_record').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
					//send_notif(o.notif_message,o.notif_title,o.notif_type);
          			default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}
          		window.location.hash = "list";
				reload_content("list");
				$('#delete_order_record').modal('hide');
				$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
			}, beforeSubmit: function(o) {

			},
			dataType : "json"
		});
	});
</script>

<div class="modal-dialog" style="height:45% margin-top:-30% word-wrap:normal;">
	<div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">Delete Order Record</h4>
	  </div>
	  <div class="modal-body">
	  	<?php if($allowed == "allowed"){ ?>
	    <form id="delete_record" name="delete_regimen_record" method="post" action="<?php echo url('orders_management/deleteOrdersRecord'); ?>">
			<!-- <input type="hidden" id="id" name="patient_id" value="<?php echo $patient['id']; ?>"> -->
			<input type="hidden" id="order_id" name="order_id" value="<?php echo $order['id']; ?>">
			Are you sure you want to delete <b><?php echo $patient['patient_name'] ?></b> in the database? This will be permanently deleted.
		</form>
		<?php } else { ?>
			<?php echo $message ?>
		<?php } ?>
	  </div>
	  	<?php if($allowed == "allowed"){ ?>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button class="btn btn-danger submit_button" onclick="$('#delete_record').submit();">Delete</button>
	  </div>
	  <?php } else { ?>
	  	 <div class="modal-footer">
			 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		 </div>
		<?php } ?>
	</div>
</div>
