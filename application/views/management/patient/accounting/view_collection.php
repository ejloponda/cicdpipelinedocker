<script>
	$(function(){
		$('.cancel_receipt').on('click', function(){
			view_invoice_record("<?php echo $invoice['id'] ?>");
		});
	});
</script>

<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Collection : <?php echo $or['or_number'] ?></h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript:void(0);" class="cancel_receipt"><img class="icon actions" title="Back" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	<ul class="regimen-ID" >
		<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
		<li class="patient">
			<b style="font-size: 20px;"><span><?php echo $patient['patient_name'] ?></span></b>
			<br>Patient ID: <?php echo $patient['patient_code'] ?>
			<!-- <br> -->
			<input type="hidden" id="id" value="<?php echo $patient['id'] ?>">
		</li>
	</ul>
	
	<div class="line03"></div>

	<h3>Invoice ID: <?php echo $invoice['rpc_invoice_id'] ?></h3>
	<p>
		<b>Balance Details</b>
	</p>
	<ul id="form02">
		<li>Amount Due</li>
		<li>P <?php echo number_format( $invoice['total_net_sales_vat'], 2, '.', ',') ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Total Amount Paid</li>
		<li>P <?php echo number_format( $invoice['total_amount_paid'], 2, '.', ',') ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Remaining Balance</li>
		<li>P <?php echo number_format( $invoice['remaining_balance'], 2, '.', ',') ?></li>
	</ul>
	<div class="clear"></div>

	<div class="line03"></div>
	<div class="clear"></div>

	<ul id="form02">
		<li>Charged to</li>
		<li><?php echo $invoice['charge_to'] ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Relation to Patient</li>
		<li><?php echo $invoice['relation_to_patient'] ?></li>
	</ul>
	<div class="clear"></div>
	<div class="line03"></div>

	<p><b>Receipt Information</b></p>
		<ul id="form02">
			<li>OR Number</li>
			<li><?php echo $or['or_number'] ?></li>
		</ul>
		<div class="clear"></div>
		
		<ul id="form02">
			<li>Date of Receipt</li>
			<li><?php echo $or['date_receipt'] ?></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Notes</li>
			<li><?php echo $or['notes'] ?></li>
		</ul>
		<div class="clear"></div>

		<?php if(!empty($cash)) { ?> 
		<h4>Cash</h4>
		<table class="table" id="cash_table_form">
			<thead>
				<th>Amount Paid</th>
			</thead>
			<tbody>
				<?php foreach ($cash as $key => $value) { ?>
					<tr>
						<td>P <?php echo number_format($value['price'],2,'.',',') ?></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

		<?php if(!empty($credit)) { ?> 
		<h4>Credit</h4>
		<table class="table" id="credit_table_form">
			<thead>
				<th>Amount Paid</th>
			</thead>
			<tbody>
				<?php foreach ($credit as $key => $value) { ?>
					<tr>
						<td>P <?php echo number_format($value['price'],2,'.',',') ?></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

		<?php if(!empty($cheque)) { ?> 
		<h4>Cheque</h4>
		<table class="table" id="cheque_table_form">
			<thead>
				<th>Check Number</th>
				<th>Bank</th>
				<th>Cheque Date</th>
				<th>Amount Paid</th>
			</thead>
			<tbody>
				<?php foreach ($cheque as $key => $value) { ?>
					<tr>
						<td><?php echo $value['cheque_number'] ?></td>
						<td><?php echo $value['bank_name'] ?></td>
						<td><?php echo $value['cheque_date'] ?></td>
						<td>P <?php echo number_format($value['price'],2,'.',',') ?></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

		<?php if(!empty($cc)) { ?> 
		<h4>Credit Card</h4>
		<table class="table" id="creditcard_table_form">
			<thead>
				<th>Bank</th>
				<th>Card Type</th>
				<th>Amount Paid</th>
			</thead>
			<tbody>
				<?php foreach ($cc as $key => $value) { ?>
					<tr>
						<td><?php echo $value['bank_name'] ?></td>
						<td><?php echo $value['card_type'] ?></td>
						<td>P <?php echo number_format($value['price'],2,'.',',') ?></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

		<table class="table-total">
			<tr>
				<td>Total Payment</td>
				<td>P <?php echo number_format($or['amount_paid'],2,'.',',') ?></td>
			</tr>
		</table>
</section>