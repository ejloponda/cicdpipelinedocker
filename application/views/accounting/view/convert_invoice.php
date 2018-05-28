<script>
$(function(){
	reset_all();
	reset_all_topbars_menu();
	$('.billing_menu').addClass('hilited');
	$("#cm_list").hide();
	calculateTotalInvoice();
	$('.saveButton').tipsy({gravity: 's'});

	if($('.tipsy-inner')) {
		$('.tipsy-inner').remove();
	}
	var meds_count = "<?php echo count($order_meds) ?>";
	var others_count = "<?php echo count($order_others) ?>";
	$('#meds_datatable').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": ( (meds_count > 10) ? true : false),
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,     
        "bScrollCollapse": false
    });

	$('#others_datatable').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": ( (others_count > 10) ? true : false),
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,     
        "bScrollCollapse": false
    });

     $(".add_submit_btn").on('click', function(event) {
		//$.blockUI();
		$('#add_invoice_form').submit();
	});

    $(".return_to_main").on('click', function(){
    	window.location.hash = "view";
		reload_content("view");
    });

	$(".cm_add_modifier").on('click', function(){
		if($('.check').is(':checked')) { 
			addNewCM();
			$(".is_medicine").hide();
		}else{
			alert("Please select an option in Modifier Type");
		}
	});


	$("#add_invoice_form").validationEngine({scroll:false});
	$("#add_invoice_form").ajaxForm({
    success: function(o) {
  		if(o.is_successful) {
  			//send_notif(o.notif_message,o.notif_title,o.notif_type);
  			default_success_confirmation({message : o.message, alert_type: "alert-success"});
  			$.unblockUI();
  			window.location.href = "billing";
  		} else {
  			default_success_confirmation({message : o.message, alert_type: "alert-danger"});
  		}
		$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
    },
    beforeSubmit : function(evt){
	 	$.blockUI();
	 },
   		dataType : "json"
	});

	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	var start_date = $('#invoice_date').datepicker({
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
	  start_date.hide();
	  $('#due_date')[0].focus();
	}).data('datepicker');
	var expiration_date = $('#due_date').datepicker({
		format: 'yyyy-mm-dd',
	  onRender: function(date) {
	    return date.valueOf() <= start_date.date.valueOf() ? 'disabled' : '';
	  }
	}).on('changeDate', function(ev) {
	  expiration_date.hide();
	}).data('datepicker');
});
</script>
<style type="text/css">
	.medgross{
		float:right;
		padding-right:38px !important;
		color: #333333 !important;
		font-weight: bold !important;
		font-size: 20px !important;
		margin-top: -10px !important;
	} 
	.medgross span{
		color:#FF8208;
	}
	.dataTables_paginate{
		margin-right: 32px !important;
		margin-top: -20px !important;
		margin-bottom: 20px !important;
	}
</style>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-order.png"></li>
			<li><h1>Create Invoice for Order No: <?php echo $order['order_no'] ?></h1></li>
		</ul>

		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#add_invoice_form').submit();"><img class="icon saveButton" title="Save"  src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript:void(0);" class="return_to_main"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	<form id="add_invoice_form" method="post" action="<?php echo url('account_billing/save_invoice') ?>" style="width: 100%;">
		<input type="hidden" value="<?php echo $order['id'] ?>" name="order_id">
		<input type="hidden" value="<?php echo $patient['id'] ?>" name="patient_id">
		<ul class="regimen-ID" >
			<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
			<li class="patient"><span><?php echo $patient['patient_name'] ?></span><br>
				Patient Code: <b><?php echo $patient['patient_code'] ?></b> 
			</li>
		</ul>
		<ul id="filter-search" style="padding-right: 36px;">
			<li><b>Date Generated:</b> <?php $date = new Datetime($order['date_created']); $date_generated = date_format($date, "M d, Y"); echo $date_generated; ?></li>
		</ul>
		<div class="clear"></div>
		<div class="line03"></div>
		<p><h1>Medicine Details</h1></p>
		<!-- MEDS TABLE -->
		<table id="meds_datatable" class="datatable table">
			<thead>
				<th style="text-align:center;">Medicine Name</th>
				<th style="text-align:center;">Quantity</th>
				<th style="text-align:center;">Price</th>
				<th style="text-align:center;">Total Price</th>
			</thead>
			<tbody>
			<?php foreach($order_meds as $key => $value){ ?>
				<tr>
					<td><?php echo $value['medicine_name'] ?> / <?php echo $value['dosage'] ?></td>
					<td><?php echo $value['quantity'] ?></td>
					<td>Php. <?php echo number_format( (float) $value['price'], 2, '.', ',') ?></td>
					<td>Php. <?php echo number_format( (float) $value['total_price'], 2, '.', ','); ?></td>
				</tr>
			<?php } ?>

			</tbody>

		</table>
		<p class="medgross">Total Regimen Gross: <span>Php. <?php echo number_format($meds_total_price,2,'.',',') ?></span></p>
		<input type="hidden" id="rpc_meds_total_input" value="<?php echo $meds_total_price ?>">
		<div class="clear"></div>
		<?php $vat = ($meds_total_price / 1.12) * 0.12 ?>
		<p class="medgross">Regimen VAT: <span>Php. <?php echo number_format($vat,2,'.',',') ?></span></p>
		<input type="hidden" id="rpc_meds_vat_input" value="<?php echo $vat ?>">
		<div class="clear"></div>
		<?php $net_of_vat = ($meds_total_price / 1.12) ?>
		<p class="medgross">Total NET of VAT: <span>Php. <?php echo number_format($net_of_vat,2,'.',',') ?> </span></p>
		<input type="hidden" id="regimen_cost_input" value="<?php echo $net_of_vat ?>">
		<div class="clear"></div>
		<br>
		<div class="line03"></div>
		<?php if($order['pharmacy'] != 'RPP'){?>
		<p><h1>Other Charges Details</h1></p>
		<!-- OTHERS TABLE -->
		<table id="others_datatable" class="datatable table">
			<thead>
				<th style="text-align:center;">Description</th>
				<th style="text-align:center;">Quantity</th>
				<th style="text-align:center;">Price</th>
				<th style="text-align:center;">Total Price</th>
			</thead>
			<tbody>
			<?php foreach($order_others as $key => $value){ ?>
				<tr>
					<td><?php echo $value['description'] ?></td>
					<td><?php echo $value['quantity'] ?></td>
					<td>Php. <?php echo number_format( (float) $value['cost'], 2, '.', ',') ?></td>
					<td>Php. <?php echo number_format( (float) $value['total_cost'], 2, '.', ','); ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>

		<p class="medgross">Other Charges Gross: <span>Php. <?php echo number_format($others_total_price,2,'.',',') ?></span></p>
		<input type="hidden" id="total_o_charges" value="<?php echo $others_total_price ?>">
		<div class="clear"></div>
		<?php $vat = ($others_total_price / 1.12) * 0.12 ?>
		<p class="medgross">Other Charges VAT: <span>Php. <?php echo number_format($vat,2,'.',',') ?></span></p>
		<input type="hidden" id="total_o_charges_vat" value="<?php echo $vat ?>">
		<div class="clear"></div>
		<?php $net_of_vat = ($others_total_price / 1.12) ?>
		<p class="medgross">Other Charges NET of VAT: <span>Php. <?php echo number_format($net_of_vat,2,'.',',') ?> </span></p>
		<input type="hidden" id="summary_total_o_charges" value="<?php echo $net_of_vat ?>">
		<div class="clear"></div>
		<div class="line03"></div>
		<?php } ?>
		<p><h1>Cost Modifiers</h1></p>
		<ul id="form02">
			<li>Applies to</li>
			<li>
				<select id="apply" style="width: 200px;" data-id="">
					
					<optgroup label="Medicines">
						<option data-price="<?php echo $meds_total_price ?>" value="All Medicines" data-type="1">All Medicines</option>
						<?php foreach ($order_meds as $key => $value) { ?>
							<option data-price="<?php echo (float) $value['total_price'] ?>" value="<?php echo $value['medicine_name'] ?>" data-type="<?php echo $value['id'] ?>"><?php echo $value['medicine_name'] ?></option>
						<?php } ?>
					</optgroup>
					<optgroup label="Other Charges" class="other_charges_select">
						<option data-price="<?php echo $others_total_price ?>" value="All Other Charges" data-type="0">All Other Charges</option>
						<?php foreach($order_others as $key => $value){ ?>
							<option data-price="<?php echo $value['total_cost'] ?>" value="<?php echo $value['description'] ?>" data-type="<?php echo $value['id'] ?>"><?php echo $value['description'] ?></option>
						<?php } ?>
					</optgroup>  
				</select>
			</li>
		</ul>

		<div class="clear"></div>

		<ul id="form02">
			<li>Modifier Type</li>
			<li>
				<input type="radio" name="modifier_type" value="Mark up" class="check"><label for="r1"><span></span>Mark up</label>
				<input type="radio" name="modifier_type" value="Discount" class="check"> <label for="r2"><span></span>Discount</label>
			</li>
		</ul>

		<div class="clear"></div>

		<ul id="form02">
			<li>Modifier Due To</li>
			<li>
				<select id="modify_due_to" style="width: 200px;">
					<?php foreach($cost_modifier as $key => $value){ ?>
						<option value="<?php echo $value['cost_modifier'] ?>" ><?php echo $value['cost_modifier'] ?></option>
					<?php } ?>
					<!-- <option value="Senior Discount">Senior Discount</option>
					<option value="Bottle Discount">Bottle Discount</option>
					<option value="Employee Discount">Employee Discount</option>
					<option value="Special Discount">Special Discount</option>
					<option value="RPC Marketing">RPC Marketing</option> -->
				</select>
				<!-- <input type="text" id="modify_due_to" class="modifier_inputs"> -->
			</li>
		</ul>

		<div class="clear"></div>
		
		<ul id="form02">
			<li>Cost</li>
			<li>
				<select id="cost_type" style="width: 60px;">
					<option value="%">%</option>
					<option value="php">&#8369;</option>
				</select>
				<input type="text" id="cost_modifier" style="width: 120px;" class="modifier_inputs">
			</li>
			<li><button type="button" class="invoice-modifier-add cm_add_modifier">+ Add Modifier</button></li>
		</ul>

		<div class="clear"></div>

		<table class="table" id="cm_list">
			<th>Applies to</th>
			<th>Modifier Type</th>
			<th>Modify Due To</th>
			<th>Cost</th>
			<th>Total Cost</th>
			<th>Actions</th>
		</table>
		<p class="medgross">Total Cost Modifiers: <span>Php. <span class="total_cm"><?php echo number_format(0,2,'.',',') ?></span></span></p>
		<input type="hidden" id="total_cm" value="0">
		<br>
		<div class="clear"></div>
		<div class="line03"></div>
		<p><h1>Invoice Details</h1></p>
		<ul id="form02">
			<li>RPC Invoice No.</li>
			<li>
				<input type="text" name="invoice_number" class="textbox validate[required]" value="<?php echo $invoice_number ?>">
			</li>
		</ul>
		<div class="clear"></div>
		<ul id="form02">
			<li>Invoice Date</li>
			<li>
				<input id="invoice_date" name="invoice_date" class="textbox validate[required]">
			</li>
		</ul>
		<div class="clear"></div>
		<!-- <ul id="form02">
			<li>Due Date</li>
			<li>
				<input id="due_date" name="due_date" class="textbox validate[required]">
			</li>
		</ul>
		<div class="clear"></div> -->
		<ul id="form02">
			<li>Payment Terms</li>
			<li>
				<select name="payment_terms" class="select00">
					<option value="COD">Cash On Delivery</option>
					<option value="7">7 days</option>
					<option value="15">15 days</option>
					<option value="15">30 days</option>
				</select>
			</li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Charged to</li>
			<li><input type="text" name="charged_to" class="textbox"></li>
		</ul>

		<div class="clear"></div>
		
		<ul id="form02">
			<li>Relation to Patient</li>
			<li><input type="text" name="relation_to_patient" class="textbox"></li>
		</ul>
		<div class="clear"></div>
		<div class="line03"></div>
		<p><h1>Invoice Summary</h1></p>
		<table class="table-total02">
			<tr>
				<td>Regimen Cost</td>
				<td><input name="total_regimen_cost" id="total_regimen_cost" type="hidden" value="<?php echo $meds_total_price ?>">Php. <?php echo number_format($meds_total_price,2,'.',',') ?></td>
			</tr>
			<tr>
				<td>Other Charges</td>
				<td><input name="total_other_charges" id="total_other_charges" type="hidden" value="<?php echo $others_total_price ?>"><span class="other_charges">Php. <?php echo number_format($others_total_price,2,'.',',') ?></span></td>
			</tr>
			<tr>
				<td>Cost Modifiers</td>
				<td><input name="total_cost_modifier" id="total_cost_modifier" type="hidden" value="0">Php. <span id="cost_modifiers">0.00</span></td>
			</tr>
			<tr>
				<td style="background: #FFE8AE;">TOTAL INVOICE AMOUNT</td><!-- WITH VAT -->
				<td><input name="total_net_sales_vat" id="total_net_sales_vat" type="hidden">Php. <span id="total_invoice_amount">0.00</span></td>
			</tr>
			<tr>
				<td>VAT</td>
				<td><input name="net_sales_vat" id="net_sales_vat" type="hidden">Php. <span id="invoice_amount_vat">0.00</span></td>
			</tr>
			<tr>
				<td style="color: #fff; background-color: #f4814b;">INVOICE NET OF VAT</td> <!-- WITHOUT VAT -->
				<td><input name="net_sales" id="net_sales" type="hidden">Php. <span id="invoice_amount">0.00</span></td>
			</tr>
		</table>
	</form>
	<section id="buttons" style="padding-right:16px;">
	
		<button class="form_button-green add_submit_btn">Save</button>
		<button class="form_button return_to_main">Cancel</button>
	</section>
</section>