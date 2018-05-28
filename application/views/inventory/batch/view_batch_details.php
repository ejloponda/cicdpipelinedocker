<div class="modal-dialog"> 
	<div class="modal-content">
		<div class="modal-header">
			<h3>Batch Details</h3>
		</div>
		<div class="modal-body">
			<form id="batch_details_form" name="batch_details_form" style="margin-left: 10%;">
				<ul id="form">
					<li><strong>Batch No:</strong></li>
					<li><input type="text" id="batch_number" name="batch_number" value="<?php echo trim($batch['batch_no']) ?>" style="border:0px;" readonly></li>					
				</ul>
				<section class="clear"></section>
				<ul id="form">
					<li><strong>Quantity:</strong></li>
					<li><input type="text" name="quantity" value="<?php echo $batch['quantity'] ." ". $qt['abbreviation'] ?>" style="width: 127px; border:0px" readonly></li>
				</ul>	
				<section class="clear"></section>
				<ul id="form">
					<li><strong>Purchase Date</strong></li>
					<li><input type="text" id="purchase_date" name="purchase_date" value="<?php echo $batch['purchase_date'] ?>" style="border:0px;" readonly></li>
				</ul>
				<section class="clear"></section>
				<ul id="form">
					<li><strong>Expiration Date</strong></li>
					<li><input type="text" id="expiration_date" name="expiration_date" value="<?php echo $batch['expiry_date']?>" style="border:0px;" readonly></li>
				</ul>
				<section class="clear"></section>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
			</div>
		</form>
	</div>
</div> 