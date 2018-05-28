<script>
$(function(){
	reset_all();
	reset_all_topbars_menu();
	$('.order_menu').addClass('hilited');
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
        "bFilter": true,
        "bInfo": false,     
        "bScrollCollapse": false
    });

	$('#others_datatable').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": ( (others_count > 10) ? true : false),
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,     
        "bScrollCollapse": false
    });

    $(".return_to_main").on('click', function(){
    	window.location.hash = "list";
		reload_content("list");
    });
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
			<li><h1>View Order : <?php echo $order['order_no'] ?></h1></li>
			<input type="hidden" name="order_id" id="order_id" class="textbox" value="<?php echo $order['id']?>">
			
		</ul>

		<ul id="controls">
			<li><span class="label <?php echo ($order['status'] == "Invoiced" ? "label-warning" : "label-info") ?>"><?php echo $order['status'] ?></span></li>
		</ul>
		<div class="clear"></div>
	</hgroup>

	<ul class="regimen-ID" >
		<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
		<li class="patient"><span><?php echo $patient['patient_name'] ?></span><br>
			Patient Code: <b><?php echo $patient['patient_code'] ?></b> 
		</li>
		<li style="margin: 47px 0px 0px -174px;">Age: <b> <?php echo $patient['age'] .'yrs. old'?></b></li>
	</ul>
	<ul id="filter-search" style="padding-right: 36px;" >
		<li><b>Date Generated:</b> <?php $date = new Datetime($order['date_created']); $date_generated = date_format($date, "M d, Y"); echo $date_generated; ?></li>
		<ul>
		<?php $doctor = Doctors::findById(array("id" => $order['doc_attending_id'] ))?>
			<li><b>Attending Doctor:</b> <?php echo $order['doc_attending_id'] == 0 ? "": $doctor['full_name']?> </li>
		</ul>
		
	</ul>
	<div class="clear"></div>

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
	<p class="medgross">Medicine Gross: <span>Php. <?php echo $meds_total_price ?></span></p>
		<br>
		<br>
		<br>
	<?php if($order['pharmacy'] != 'RPP'){?>
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

	<p class="medgross">Other Gross: <span>Php. <?php echo $others_total_price ?></span></p>
	
	<div class="clear"></div>
	<?php } ?>
	<div class="line02"></div>
		<ul id="notes">
			<li><b>Remarks:</b> </li>
			<li style="width: 540px"><?php echo $order['remarks'] ?></li>
		</ul>

	<div class="clear"></div>
	<div class="clear"></div>
	<section id="buttons" style="padding-right:16px;">
	<?php if($om_order['can_add']) { ?>
		<?php if($order['status'] != "Invoiced") { ?>
		<button class="form_button-green" onClick="javascript: create_invoice(<?php echo $order['id'] ?>)">Create Invoice</button>
		<?php } ?>
	<?php } ?>
		<button type="button" class="form_button-green" onClick="javascript: print_summary(<?php echo $order['id'] ?>);">Print Summary</button>
	<?php if($om_order['can_update']) { ?>
		<?php if($order['status'] != "Invoiced") { ?>
		<button class="form_button" onClick="javascript: edit_order(<?php echo $order['id'] ?>)">Edit</button>
		<?php } ?>
	<?php } ?>
	<?php if($om_order['can_delete']) { ?>
		<button class="form_button" onClick="javascript: delete_order(<?php echo $order['id'] ?>)">Delete</button>
	<?php } ?>
		<button class="form_button return_to_main">Back</button>
	</section>
</section>

<script>
	var order_id = $("#order_id").val();
	function print_summary(order_id){
			window.open(base_url+'download/generate_summary/'+order_id+'/',"_blank");
		}
</script>