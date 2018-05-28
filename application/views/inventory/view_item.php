<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-inventory.png"></li>
			<li><h1>View Inventory</h1></li>
		</ul>
		
		<ul id="controls">
			<?php #if($mu_default['can_update']){?>
			<li><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_edit.png" onclick="javascript: edit_item_inv(<?php echo $med['id'] ?>);"></li>
			<?php #} ?>
			<?php #if($mu_default['can_update'] && $mu_default['can_delete']){?>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<?php #} ?>
			<?php #if($mu_default['can_delete']){?>
			<li><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_trash.png" onclick="javascript: delete_item_inv(<?php echo $med['id'] ?>);"></li>
			<?php #} ?>
		</ul>
		<div class="clear"></div>
	</hgroup>
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
		<input type="hidden" name="product_number" id="product_number" value="<?php echo $med['product_no']; ?>">
		<input type="hidden" name="med_id" id="med_id" value="<?php echo $med['id']; ?>">
		
				<h1>Basic Information</h1><br/>
						<strong>Product #: </strong> 
						<?php echo $med['product_no']; ?>
				<section class="clear"></section>
						<strong>Medicine Name: </strong> 
						<?php echo $med['medicine_name']; ?>
				<section class="clear"></section>
						<strong>Generic Name: </strong> 
						<?php echo $med['generic_name']; ?>
				<section class="clear"></section>
						<strong>Dosage: </strong> 
						<?php $dosage = Dosage_Type::findById(array("id" => $med['dosage_type'])); ?>
						<?php echo $med['dosage'] . " " . $dosage['abbreviation']; ?>	
				<section class="clear"></section>
						<strong>Quantity Per Bottle: </strong>
						<?php echo $med['qty_per_bottle'] ." pcs"; ?>
				<section class="clear"></section>
						
		</section>	
		
		<section id="right">
			<h1>Stock Information</h1><br/>
					
					<strong>From Stock: </strong> 
					<?php echo ($med['stock'] == "Royal Preventive" ? $med['stock'] . " / Php " . number_format($med['stock_price'],2) : $med['stock']); ?>
			<section class="clear"></section>
					<strong>Price: </strong> 
					<?php echo "Php " . number_format($med['price'],2); ?>

			<section class="clear"></section>
		</section>	
	
		<div class="line02"></div>
		<div class="clear"></div>
		
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
			<!-- <h1>Miscellaneous Details</h1><br/> -->
			<h1>Batch</h1>
			<div id="batch_list_wrapper"></div>
		</section>
		<section id="right">
			<h1>Stock Adjustment</h1>
			<div class="stock-history" style="overflow-y: auto; height: 200px; width: 445px;">
				<ul>
				<?php foreach ($stock_history as $key => $value) { ?>
					<?php $user = User::findById(array("id" => $value['created_by'])) ?>
					<li>Reason: <?php echo $value['reason'] ?><br/>Quantity: <?php echo $value['quantity'] ?><br/>By: <?php echo $user['firstname'] . " " . $user['lastname'] ?><br/>Time: <?php echo Tool::humanTiming(strtotime($value['created_at'])) ?> ago<br/>---<br/></li>
				<?php } ?>
				</ul>
			</div>
		</section>

</section>	
<section class="clear"></section>


<script>
	$(function(){
		batch_list(<?php echo $med['id'] ?>);
	});
	function addBatch(){
		/*var item_id = parseInt(item_id);*/
		var prod_no = $('#product_number').val();
		var med_id  = $('#med_id').val();
		/*var x = prod_no;
		var y = med_id;*/

		$.post(base_url + "inventory_management/addBatchModal", {prod_no:prod_no, med_id:med_id}, function(o){

			$('#addBatch_modal_wrapper').html(o);
			$('#addBatch_modal_wrapper').modal("show");
		})
	}

	function batch_details(id){
		$.post(base_url + "inventory_management/batch_details", {id:id}, function(o){
			$('#viewBatch_modal_wrapper').html(o);
			$('#viewBatch_modal_wrapper').modal("show");
		})
	}
</script>