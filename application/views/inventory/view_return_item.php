<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-inventory.png"></li>
			<li><h1>View Return Item</h1></li>
		</ul>
		
		<ul id="controls">
			<?php #if($mu_default['can_update']){?>
			<li><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_edit.png" onclick="javascript: edit_return_item(<?php echo $returns['id'] ?>);"></li>
			<?php #} ?>
			<?php #if($mu_default['can_update'] && $mu_default['can_delete']){?>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<?php #} ?>
			<?php #if($mu_default['can_delete']){?>
			<li><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_trash.png" onclick="javascript: delete_return_item(<?php echo $returns['id'] ?>);"></li>
			<?php #} ?>
		</ul>
		<div class="clear"></div>
	</hgroup>
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
				<h1>Basic Information</h1><br/>
						<strong>Medicine Name:</strong> 
						<?php echo $returns['medicine_name']; ?>
				<section class="clear"></section>
						<strong>Generic Name: </strong> 
						<?php echo $returns['generic_name']; ?>
				<section class="clear"></section>
						<strong>Dosage: </strong> 
						<?php echo $inv['dosage'] . " mg"; ?>
	
				<section class="clear"></section>
						
		</section>	
		
		<section id="right">
				<h1>Stock Information</h1><br/>
						
				<strong>Total Quantity</strong> 
						<?php echo $returns['total_quantity'] . " pcs"; ?>
				<section class="clear"></section>
						<strong>From Stock: </strong> 
						<?php echo $returns['stock']; ?>
				<section class="clear"></section>
						<strong>Price: </strong> 
						<?php echo "Php " . $returns['price']; ?>
	
				<section class="clear"></section>
		</section>	
	
		<div class="line02"></div>
		
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
				<h1>Miscellaneous Details</h1><br/>

				<strong>Purchase Date:</strong> 
						<?php echo $inv['purchase_date'] ?>
				<section class="clear"></section>

				<strong>Expiration Date:</strong> 
						<?php echo $inv['expiry_date'] ?>
				<section class="clear"></section>

		</section>

		<section id="right">
				<h1>Return Details</h1><br/>
						
				<strong>Returned By:</strong> 
				<?php echo $returns['returned_by'] ?>
				<section class="clear"></section>

				<strong>Returned Quantity / Total Price: </strong> 
				<?php echo $returns['returned_quantity'] . " pcs / Php " . $returns['total_price']; ?>
				<section class="clear"></section>

				<strong>Return Reason: </strong> 
				<?php echo wordwrap($returns['return_reasons'],70,"<br/>\n") ?>
				<section class="clear"></section>
		</section>	

</section>	
<section class="clear"></section>


