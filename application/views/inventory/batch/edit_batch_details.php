<style>
.datepicker{z-index:1151;}
</style>
<script>
$(function() {

	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	var purchase_date = $('#purchase_date').datepicker({
	format: 'yyyy-mm-dd',
	onRender: function(date) {
	return date.valueOf() < now.valueOf() ? 'disabled' : '';
	}
	}).on('changeDate', function(ev) {
	if (ev.date.valueOf() > expiration_date.date.valueOf()) {
	var newDate = new Date(ev.date)
	newDate.setDate(newDate.getDate() + 1);
	expiration_date.setValue(newDate);
	}
	purchase_date.hide();
	$('#expiration_date')[0].focus();
	}).data('datepicker');

	var expiration_date = $('#expiration_date').datepicker({
	format: 'yyyy-mm-dd',
	onRender: function(date) {
	return date.valueOf() <= purchase_date.date.valueOf() ? 'disabled' : '';
	}
	}).on('changeDate', function(ev) {
	expiration_date.hide();
	}).data('datepicker');

	$("#edit_batch_details_form").validationEngine({scroll:false});
	$("#edit_batch_details_form").ajaxForm({
		success: function(o) {
			IS_ADD_INVENTORY_FORM_CHANGE = true;
			$('#editBatch_modal_wrapper').modal("hide");
			batch_list(<?php echo $med['id'] ?>);
		},
		beforeSubmit: function(o) {
		}
	});
});

</script>
<div class="modal-dialog"> 
	<div class="modal-content">
		<div class="modal-header">
			<h3>Edit Batch Details</h3>
		</div>
		<div class="modal-body">
			<form id="edit_batch_details_form" style="margin-left: 10%;" method="post" action="<?php echo url('inventory_management/saveBatch'); ?>">
				<ul id="form">
					<li><strong>Batch No:</strong></li>
					<li><input type="text" id="batch_number" name="batch_number" value="<?php echo trim($batch['batch_no']) ?>" style="border:0px;" readonly></li>					
					 <input type="hidden" id="med_id" name="med_id" value="<?php echo $med['id'] ?>">
					 <input type="hidden" id="batch_id" name="batch_id" value="<?php echo $batch['id'] ?>">
				</ul>
				<section class="clear"></section>
				<!-- <ul id="form">
					<li><strong>Quantity:</strong></li>
					<li><input type="text" name="quantity" value="<?php echo $batch['quantity'] ?>" style="width: 127px;" readonly></li>
				</ul> -->

				<ul id="form">
					<li><strong>Quantity:</strong></li>
					<li>
						<input type="text" name="quantity" value="<?php echo $batch['quantity'] ?>" style="width: 127px;">
						<span value="<?php echo $qt['abbreviation']?>"><?php echo $qt['abbreviation']?></span>
						<!-- <select name="quantity_type" id="quantity_type" class="select validate[required]">
							<?php foreach ($quantity as $key => $value) { ?>
								<option value="<?php echo $value['id'] ?>"><?php echo $value['abbreviation'] ?></option>
							<?php } ?>
						</select> -->
					</li>
				</ul>		
				<section class="clear"></section>
		
				<ul id="form">
						<li><strong>Purchase Date</strong></li>
						<li><input type="text" id="purchase_date" name="purchase_date" class="textbox add_inventory_trigger validate[required]" value="<?php echo $batch['purchase_date'] ?>"></li>
					</ul>
					<section class="clear"></section>
					<ul id="form">
						<li><strong>Expiration Date</strong></li>
						<li><input type="text" id="expiration_date" name="expiration_date" class="textbox add_inventory_trigger validate[required]" value="<?php echo $batch['expiry_date']?>"></li>
					</ul>
					<section class="clear"></section>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
			<section id="buttons">
			<button class="form_button" onClick="$('#edit_batch_details_form').submit();">Save</button>
			</section>
		</div>
		
	</div>
</div> 