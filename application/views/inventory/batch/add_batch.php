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

	/*$("#royalpreventive_opt").attr('checked', true);
		$("#royalpreventive_opt").live('click',function(){
			$('#cost_to_purchase').attr('readonly', false);
		});

		$("#alist_opt").live('click',function(){
			$('#cost_to_purchase').val("");
			$('#cost_to_purchase').attr('readonly', true);
		});*/

	$("#add_batch_form").validationEngine({scroll:false});
	$("#add_batch_form").ajaxForm({
	success: function(o) {
	  $('#addBatch_modal_wrapper').modal("hide");
	  batch_list(<?php echo $med_id ?>);
	},
	beforeSubmit: function(o) {
	}
	});
});
</script>
<div class="modal-dialog"> 
	<div class="modal-content">
		<div class="modal-header">
			<h3>Add Batch</h3>
		</div>
		<div class="modal-body">
			<form id="add_batch_form" method="post" action="<?php echo url('inventory_management/saveBatch'); ?>">
				<ul id="form">
					<li>Batch No:</li>
					<li><input type="text" id="batch_number" name="batch_number" value="<?php echo $batchcode ?>" readonly="readonly"></li>
					<input type="hidden" id="med_id" name="med_id" value="<?php echo $med_id ?>">					
				</ul>
				<section class="clear"></section>
				<ul id="form">
					<li>Quantity:</li>
					<li>
						<input type="text" name="quantity" class="textbox add_inventory_trigger validate[required,custom[number]]" style="width: 127px;">	
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
					<li>Purchase Date</li>
					<li><input type="text" id="purchase_date" name="purchase_date" class="textbox add_inventory_trigger validate[required]"></li>
				</ul>
				<section class="clear"></section>
				<ul id="form">
					<li>Expiration Date</li>
					<li><input type="text" id="expiration_date" name="expiration_date" class="textbox add_inventory_trigger validate[required]"></li>
				</ul>
				<section class="clear"></section>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
			<section id="buttons">
			<button class="form_button" onclick="$('#add_batch_form').submit();">Save</button>
			</section>
		</div>
		
	</div>
</div> 
