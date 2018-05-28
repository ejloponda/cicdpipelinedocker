<script>
	$(function(){
		$('.cancel_view').on('click', function(){
			view_invoice_list();
		});
		var status = "<?php echo $invoice['status'] ?>";
		$("#status").html(status);
		var payment_terms = "<?php echo ($invoice['payment_terms'] == 'COD' ? 'Cash On Delivery' : $invoice['payment_terms'] . ' Days') ?>";
		$("#payment_terms").html(payment_terms);
	});

</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Invoice (<?php echo $invoice['invoice_internal'] ?>)</h1></li>
		</ul>
		
		<!-- <ul id="controls">
			<?php if($invoice['status'] == "New" OR $invoice['status'] == "Pending"){  ?>
			<li><a href="javascript: void(0);" onclick="javascript: voidInvoice(<?php echo $invoice['id'] ?>)"><i class="glyphicon glyphicon-warning-sign editButton" style="font-size: 18px;" title="Void Invoice"></i></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<?php } ?>
			<?php if($invoice['status'] == "New" OR $invoice['status'] == "Pending"){ ?>
			<li><a href="javascript: void(0);" onclick=""><img class="icon editButton" title="Edit"  src="<?php echo BASE_FOLDER; ?>themes/images/icon_edit.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<?php } ?>
			<li><a href="javascript:void(0);" class="cancel_view"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul> -->
		<ul id="controls">
				<?php 
					$now 		= time(); // or your date as well
				    $your_date 	= strtotime($invoice['invoice_date']);
				    $datediff 	= $now - $your_date;
					$expiry 	= floor($datediff/(60*60*24));

					$datetime1 = new DateTime($invoice['invoice_date']);
					$datetime2 = new DateTime($today_date);
					$interval = $datetime1->diff($datetime2);
					$difference = $interval->format('%a');
				?>
				<?php if($accounting['can_add'] || $accounting['can_update'] || $accounting['can_delete']) { ?>
				<?php if ($difference <= 0) {?>
					<?php if($invoice['status'] == "New" OR $invoice['status'] == "Pending"){ ?>
					<li><a href="javascript: void(0);" onclick="javascript: voidInvoice(<?php echo $invoice['id'] ?>)"><i class="glyphicon glyphicon-warning-sign editButton" style="font-size: 18px;" title="Void Invoice"></i></a></li>
					<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
					<?php } ?>
				<?php } ?>

				<?php if ($difference <= 0) {?>
					<?php if($expiry >= 0){ ?>
						<?php if($invoice['status'] == "New" OR $invoice['status'] == "Pending"){ ?>
						<li><a href="javascript: void(0);" onClick="editInvoice(<?php echo $invoice['id'] ?>)"><img class="icon editButton" title="Edit"  src="<?php echo BASE_FOLDER; ?>themes/images/icon_edit.png"></a></li>
						<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				<?php } ?>
				<li><a href="javascript:void(0);" class="cancel_view"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
			</ul>
			
		
		<div class="clear"></div>
	</hgroup>

	<ul class="regimen-ID" >
		<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
		<li class="patient">
			<b style="font-size: 20px;"><span><?php echo $patient['patient_name'] ?></span></b>
			<br>Patient ID: <?php echo $patient['patient_code'] ?>
			<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
		</li>
	</ul>
	
	<div style="float: right;margin: 20px 36px 0 0;">
		<div id="form02">Status:&nbsp;
			<span id="status"></span>
		</div>
	</div>

	<div class="clear" style="padding-bottom: -10px;"></div>
	
	<div class="line03"></div>

	<input type="hidden" name="patient_id" value="<?php echo $patient['id'] ?>">
	<p><h1>RPC Invoice Details</h1></p>
	<div style="float:right;margin: 0px 36px 0 0;">
		<span style="color: #96a960;">Invoice Date</span> <?php echo $invoice['invoice_date'] ?>
		&nbsp;&nbsp;&nbsp;
		<?php 
			$due = date('Y/m/d', strtotime($invoice['date_claimed']));
			$real_duedate = date('Y-m-d',strtotime($due . "+15 days"));
		?>
		<span style="color: #96a960;">Due Date</span> <?php echo $invoice['date_claimed'] != 0 ? $real_duedate : '0000-00-00' ;?>
	</div>
	<div style="float:left">
		
		<span style="color: #96a960;">Payment Terms:</span> 
		<span id="payment_terms"></span>
	</div>
	
	<div class="clear"></div>
	
	<?php $rpc_meds_total = 0 ?>	
	<?php $rpc_counter = 0 ?>	
	<?php if($rpc_meds) { ?>
	<table class="table-invoice" style="width:100%;">
		<thead>
			<th>LIST OF RPC MEDS</th>
			<th style="padding-right: 20px;">Quantity</th>
			<th style="padding-right: 20px;">Cost/item</th>
			<th style="padding-right: 20px;width:22%;">Cost</th>
		</thead>
		<tbody style="min-height:150px;">
		<?php foreach ($rpc_meds as $key => $value) { ?>
			<tr>
				<td><?php echo $value['medicine_name'] . " (" . $value['dosage'] . " " . $value['dosage_type'] . ")" ?></td>
				<td><?php echo $value['quantity'] ?></td>
				<td><?php echo "&#8369; " . number_format($value['price'], 2, '.', ',') ?></td>
				<td><?php echo "&#8369; " . number_format($value['total_price'], 2, '.', ',') ?></td>
			</tr>
			<?php $rpc_counter++ ?>
			<?php $rpc_meds_total = $rpc_meds_total + $value['total_price'] ?>
		<?php } ?>
		</tbody>	
	</table>
	<br>
	<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
			<tr>
				<td style="width: 173px; padding: 0;">TOTAL REGIMEN GROSS:</td>
				<td style="padding-right: 45px;">
					&#8369; <?php echo number_format($rpc_meds_total, 2, '.', ',') ?>
					<input type="hidden" id="rpc_meds_total_input" value="<?php echo $rpc_meds_total ?>">
				</td>
			</tr>
	</table>

	<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
			<tr>
				<td style="width: 173px; padding: 0;">VAT:</td>
				<td style="padding-right: 45px;">
					<?php $vat = ($rpc_meds_total / 1.12) * 0.12 ?>
					&#8369; <?php echo number_format( $vat, 2, '.', ',') ?>
					<input type="hidden" id="rpc_meds_vat_input" value="<?php echo $vat ?>">
				</td>
			</tr>
	</table>
	<?php } ?>

	<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
		<tr>
			<td style="width:330px;">TOTAL NET OF VAT:</td>
			<td style="padding-right: 45px;">
				&#8369; <?php echo number_format($rpc_meds_total / 1.12, 2, '.', ',') ?>
				<input type="hidden" id="regimen_cost_input" value="<?php echo $rpc_meds_total / 1.12 ?>">
			</td>
		</tr>
	</table>
	<div class="clear"></div>
	<div class="line03"></div>

	<div class="clear"></div>

	<ul id="form02">
		<li>Charged to</li>
		<li><b><?php echo $invoice['charge_to'] ?></b></li>
	</ul>

	<div class="clear"></div>
	
	<ul id="form02">
		<li>Relation to Patient</li>
		<li><b><?php echo $invoice['relation_to_patient'] ?></b></li>
	</ul>
	<div class="clear"></div>
	<div class="line03"></div>
	
	<?php if(!empty($other_charges)){ ?>
	<p><h1>Other Charges</h1></p>
	<table class="table" id="other_charges_list">
		<thead>
			<th>Description</th>
			<th>Quantity</th>
			<th>Cost per Item</th>
			<th>Total Cost</th>
		</thead>
		<tbody>
			<?php $rpc_other_charges = 0 ?>
			<?php foreach ($other_charges as $key => $value) { 
				$othercharges = Other_Charges::findById(array("id" =>$value['description_id']));		
			?>
			<tr>
				<td><?php echo $othercharges['r_centers'] ?></td>
				<td><?php echo $value['quantity'] ?></td>
				<td>&#8369; <?php echo number_format($value['cost_per_item'], 2, '.', ',') ?></td>
				<td>&#8369; <?php echo number_format($value['cost'], 2, '.', ',') ?></td>
			</tr>
			<?php $rpc_other_charges += $value['cost'] ?>
			<?php } ?>
		</tbody>
	</table>
	<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
			<tr>
				<td style="width: 300px; padding: 0;">OTHER CHARGES GROSS:</td>
				<td style="padding-right: 45px;">
					<span class="other_charges_gross">&#8369; <?php echo number_format($rpc_other_charges, 2, '.', ',') ?></span>
					<input type="hidden" id="total_o_charges" value="<?php echo $rpc_other_charges ?>">
				</td>
			</tr>
	</table>

	<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
			<tr>
				<td style="width: 300px; padding: 0;">OTHER CHARGES VAT:</td>
				<td style="padding-right: 45px;">
					<?php $oc_vat = ($rpc_other_charges / 1.12) * 0.12 ?>
					<span class="other_charges_vat">&#8369; <?php echo number_format($oc_vat, 2, '.', ',') ?></span>
					<input type="hidden" id="total_o_charges_vat" value="<?php echo $oc_vat ?>">
				</td>
			</tr>
	</table>

	<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
			<tr>
				<td style="width: 330px;">OTHER CHARGES NET OF VAT:</td>
				<td style="padding-right: 45px;">
					<span class="summary_total_other_charges">&#8369; <?php echo number_format($rpc_other_charges / 1.12, 2, '.', ',') ?></span>
					<input type="hidden" id="summary_total_o_charges" value="<?php echo $rpc_other_charges / 1.12 ?>">
				</td>
			</tr>
	</table>
	<br>
	<DIV class="clear"></DIV>
	<div class="line03"></div>
	<?php } ?>

	<?php if(!empty($cost_modifier)){ ?>
	<p><h1>Cost Modifiers</h1></p>
	<table class="table" id="cm_list">
		<thead>	
			<th>Applies to</th>
			<th>Modifier Type</th>
			<th>Modify Due To</th>
			<th>Cost</th>
			<th>Total Cost</th>
		</thead>
		<tbody>
			<?php $rpc_cost_mod = 0 ?>
			<?php foreach ($cost_modifier as $key => $value) { ?>
			<tr>
				<td><?php echo $value['applies_to'] ?></td>
				<td><?php echo $value['modifier_type'] ?></td>
				<td><?php echo $value['modify_due_to'] ?></td>
				<td><?php echo $value['cost_modifier'] . " " . $value['cost_type'] ?></td>
				<td>&#8369; <?php echo number_format($value['total_cost'], 2, '.', ',') ?></td>
			</tr>
			<?php $rpc_cost_mod += $value['total_cost'] ?>
			<?php } ?>
		</tbody>

	</table>

	<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
		<tr>
			<td style="width: 330px;">TOTAL COST MODIFIERS</td>
			<td style="padding-right: 45px;">
				<span class="total_cm">&#8369; <?php echo number_format($rpc_cost_mod, 2, '.', ',') ?></span>
				<input type="hidden" id="total_cm" value="<?php echo $rpc_cost_mod ?>">
			</td>
		</tr> 
	</table>
	<br>
	<div class="clear"></div>
	<div class="line03"></div>
	<?php } ?>
			<p><h1>Invoice Summary</h1></p>
			<table class="table-total02">
				<tr>
					<td>Regimen Cost</td>
					<td><input name="total_regimen_cost" id="total_regimen_cost" type="hidden" value="<?php echo $invoice['total_regimen_cost'] ?>">&#8369; <?php echo number_format($invoice['total_regimen_cost'],2,'.',',') ?></td>
				</tr>
				<tr>
					<td>Other Charges</td>
					<td><input name="total_other_charges" id="total_other_charges" type="hidden" value="<?php echo $invoice['total_other_charges'] ?>">&#8369; <?php echo number_format($invoice['total_other_charges'],2,'.',',') ?></td>
				</tr>
				<tr>
					<td>Cost Modifiers</td>
					<td><input name="total_cost_modifier" id="total_cost_modifier" type="hidden" value="<?php echo $invoice['cost_modifier'] ?>">&#8369; <?php echo number_format($invoice['cost_modifier'],2,'.',',') ?></td>
				</tr>
				<tr>
					<td style="background: #FFE8AE;">INVOICE NET OF VAT</td>
					<td><input name="net_sales" id="net_sales" type="hidden" value="<?php echo $invoice['net_sales'] ?>">&#8369; <?php echo number_format($invoice['net_sales'],2,'.',',') ?></td>
				</tr>
				<tr>
					<td>VAT</td>
					<td><input name="net_sales_vat" id="net_sales_vat" type="hidden" value="<?php echo $invoice['net_sales_vat'] ?>">&#8369; <?php echo number_format($invoice['net_sales_vat'],2,'.',',') ?></td>
				</tr>
				<tr>
					<td style="color: #fff; background-color: #f4814b;">TOTAL INVOICE AMOUNT</td>
					<td><input name="total_net_sales_vat" id="total_net_sales_vat" type="hidden" value="<?php echo $invoice['total_net_sales_vat'] ?>">&#8369; <?php echo number_format($invoice['total_net_sales_vat'],2,'.',',') ?></td>
				</tr>
			</table>
		<div class="clear"></div>
		<div class="line03"></div>
		<p><h1>Balance Details</h1></p>
		<ul id="form02">
			<li>Amount Due</li>
			<li><?php echo ($invoice['status'] == "Void") ? "Void" : "P " . number_format($invoice['total_net_sales_vat'], 2, '.', ',') ?></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Total Amount Paid</li>
			<li>P <?php echo number_format($invoice['total_amount_paid'], 2, '.', ',') ?></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Remaining Balance</li>
			<li><?php echo ($invoice['status'] == "Void") ? "Void" : "P " . number_format($invoice['remaining_balance'], 2, '.', ',') ?></li>
		</ul>
		<div class="clear"></div>
		<?php if($collections) { ?>
		<h4>Collections</h4>
		<table class="table" id="cash_table_form">
			<thead>
				<th>OR Number</th>
				<th>Amount Paid</th>
				<th>Date of Receipt</th>
			</thead>
			<tbody>
				<?php foreach ($collections as $key => $value) { ?>
					<tr>
						<td><a href="javascript: void(0);" onclick="javascript: viewCollection(<?php echo $value['id'] ?>)"><?php echo $value['or_number'] ?></a></td>
						<td>P <?php echo number_format($value['amount_paid'],2,'.',',') ?></td>
						<td><?php echo $value['date_receipt'] ?></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<div class="clear"></div>
		<?php } ?>
		<?php if($returns) { ?>
		<h4>Returns</h4>
		<table class="table" id="cash_table_form">
			<thead>
				<th>Invoice No.</th>
				<th>Credit</th>
				<th>Date of Returns</th>
			</thead>
			<tbody>
				<?php foreach ($returns as $key => $value) { ?>
					<tr>
						<td><a href="javascript: void(0);" onclick="javascript: viewReturns(<?php echo $value['id'] ?>)"><?php echo $invoice['invoice_internal'] ?></a></td>
						<td>P <?php echo number_format($value['credit'],2,'.',',') ?></td>
						<td><?php echo $value['date_return'] ?></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<div class="clear"></div>
		<?php } ?>
	<section id="buttons">
		<?php if($accounting['can_add'] || $accounting['can_update'] || $accounting['can_delete']) { ?>
		<?php if ($difference <= 0) {?>
			<?php if($expiry >= 0){ ?>
				<?php if($invoice['status'] == "New" OR $invoice['status'] == "Pending"){ ?>
				<button type="button" class="form_button" onClick="editInvoice(<?php echo $invoice['id'] ?>)">Edit Invoice</button>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		<?php } ?>
		<button type="button" class="form_button" onclick="javascript: print_invoice(<?php echo $invoice['id'] ?>)">Print Invoice</button>
		<button type="button" class="form_button cancel_view">Cancel</button>
	</section>
</section>

<script>
	function print_invoice(invoice_id){
		window.open(base_url+'download/generate_pdf/'+invoice_id,"_blank");
	}

	function editInvoice(invoice_id){
			$('#main_wrapper_management').html(default_ajax_loader);
			$.post(base_url+"account_billing/editInvoice", {invoice_id:invoice_id}, function(o){
				$('#main_wrapper_management').html(o);
			});
		}
</script>