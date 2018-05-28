<script>
	$(function(){
		reset_all();
		reset_all_topbars_menu();
		$('.inventory_management_menu').addClass('hilited');
		$('.returns_form').addClass('sub-hilited');

		$(".cancel_receipt").on('click', function(){
			loadPreviousForm();
		});

		$('.actions').tipsy({'gravity': 's'})
	});
</script>

<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Returns</h1></li>
		</ul>
		
		<ul id="controls">
			<?php if($rets['can_update']){ ?>
				<?php if($returns['status'] == "Pending"){ ?>
				<li><a href="javascript:void(0);" onclick="javascript: AcceptDeclineReturns(<?php echo $returns['id'] ?>)"><i class="glyphicon glyphicon-bell actions" style="font-size: 18px;" title="Accept / Decline"></i></a></li> 
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
				<?php } ?>
			<?php } ?>
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
		</li>
	</ul>
	
	<div class="line03"></div>

	<h3>Invoice ID: <?php echo $invoice['invoice_num'] ?></h3>
	
	<p><h3>Medicines</h3></p>
	<table class="table" id="medicine_tables">
		<thead>
			<th>Medicine Name</th>
			<th>Dosage</th>
			<th>Quantity</th>
			<th>Price</th>
			<th>Cost Modifier</th>
		</thead>
		<tbody>
			<?php foreach ($medicines as $key => $value) { ?>
				<tr>
					<td><?php echo $value['medicine_name'] ?></td>
					<td><?php echo $value['dosage'] . " " . $value['dosage_type'] ?></td>
					<td><?php echo $value['quantity'] . " " . ($value['quantity'] == 1 ? (substr($value['quantity_type'], 0,-1)) : $value['quantity_type']) ?></td>
					<td>P <?php echo number_format($value['price'], 2, '.', ',') ?><input type="hidden" id="price" class="myText_<?php echo $ctr ?>" value="<?php echo $value['price'] ?>" name="medicine[<?php echo $ctr ?>][price]" disabled></td>
					<td><input type="hidden" id="cost_type" class="myText_<?php echo $ctr ?>" value="<?php echo $value['cost_type'] ?>" name="medicine[<?php echo $ctr ?>][cost_type]" disabled> 
							<input type="hidden" id="cost_modifier" class="myText_<?php echo $ctr ?>" value="<?php echo $value['cost_modifier'] ?>" name="medicine[<?php echo $ctr ?>][cost_modifier]" disabled>
						 <?php echo ($value['cost_type'] == "%" ? $value['cost_modifier']." ".$value['cost_type'] : $value['cost_modifier']." ".$value['cost_type']);?></td>
						<td style = "display:none;"><input type="hidden" id="modify_due_to" class="myText_<?php echo $ctr ?>" value="<?php echo $value['modify_due_to'] ?>" name="medicine[<?php echo $ctr ?>][modify_due_to]" disabled> 
				</tr>	
			<?php } ?>
		</tbody>
	</table>

	<ul id="form02">
		<li>Date of Return</li>
		<li><?php echo $returns['date_return'] ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Return Slip No.</li>
		<li><?php echo $returns['return_slip_no'] ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Discounted Amount</li>
		<li>PHP <?php echo $returns['discounted_amt'] ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Credit</li>
		<li>PHP <?php echo $returns['credit'] ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Reason of Return</li>
		<li><?php echo $returns['reason_of_return'] ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Remarks</li>
		<li><?php echo $returns['remarks'] ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Status</li>
		<li><?php echo $returns['status'] ?></li>
	</ul>
	<div class="clear"></div>
</section>