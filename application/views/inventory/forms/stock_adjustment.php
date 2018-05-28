<script>
	$(function(){
		$('.tipsy-inner').remove();
		$('#add_button_quantity').tipsy({gravity: 's'});
		$('#minus_button_quantity').tipsy({gravity: 's'});
		$('#add_quantity').tipsy({gravity: 's'});
		reset_all();
		reset_all_topbars_menu();
		$('.inventory_management_menu').addClass('hilited');
		$('.stock_adjustment_form').addClass('sub-hilited');
		// $('.inventory_list_form').addClass('sub-hilited');

	});
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-inventory.png"></li>
			<li><h1>Edit Inventory</h1></li>
		</ul>
		
		<div class="id-number">ID Number: <input type="text" name="med_id" id="med_id" value="<?php echo $inv['id'] ?>" readonly></div>
		<div class="clear"></div>
	</hgroup>
	<section id="left" style="border-right: dashed 1px #d3d3d3;">	
		<input type="hidden" name="item_id" id="item_id" value="<?php echo $inv['id'] ?>">
		<script>
		$(function(){

			$('#batch').live('change', function(){
			var batch_id = $(this).val();
			if(batch_id){
				getBatchDetails(batch_id);
			}
		});
			var add 		= $("#add_button_quantity");
			var minus 		= $("#minus_button_quantity");
			var txtbox 		= $("#add_quantity");
			var quantity 	= $("#quantity");
			var quantity_type = $("#quantity_type");

			var item_id 	= $("#item_id").val();
			
			add.on("click", function(){
				var batch_value = $('#batch option:selected').val();
				var value 	= parseInt(txtbox.val()) + parseInt(quantity.val());
				var qt 	= quantity_type.val();
				var reasons = $("#reasons").val();

				if(reasons != "0"){
					
					$.post(base_url + "inventory_management/saveStockAdjustment", {value:value, item_id:item_id, reasons:reasons, qt:qt, batch_value:batch_value}, function(){
						quantity.val(value);
						txtbox.val(0);
						txtbox.focus();
						$("#reasons").select2('val', '0');
					});
					
				} else {
					alert("Please select your reason!");
				}
				
			});

			minus.on("click", function(){
				var batch_value = $('#batch option:selected').val();
				var value 		= parseInt(quantity.val()) - parseInt(txtbox.val());
				var reasons 	= $("#reasons").val();
				var qt 	= quantity_type.val();
				if(reasons  != "0"){
					
					$.post(base_url + "inventory_management/saveStockAdjustment", {value:value, item_id:item_id, reasons:reasons, qt:qt, batch_value:batch_value}, function(){
						quantity.val(value);
						txtbox.val(0);
						txtbox.focus();
						$("#reasons").select2('val', '0');
					});
					
				} else {
					alert("Please select your reason!");
				}
			});
		});
		</script>
		<h1>Stock Adjustments</h1> 

		<ul id="form">
			<li>Batch</li>
			<li>
				<script>
					var opts=$('#batch_list').html(), opts2='<option></option>'+opts;
				    $('#batch').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
				    $('#batch').select2({allowClear: true});
				</script>
				<select id='batch' name='batch_id' class='populate add_returns_trigger' style='width:280px;'></select>
				<select id='batch_list' name="batch_list" class='validate[required]' style='display:none'>
					<option value='0'>-Select Batch-</option>;
					<?php foreach($batches as $key=>$value): ?>
						<option value="<?php echo $value['id'] ?>"><?php echo $value['batch_no']?></option>
					<?php endforeach; ?>
				</select>
			</li>
		</ul>
		
		<section class="clear"></section>

		<ul id="form">
			<li>Add Stock:</li>
			<li>
				<input type="text" name="add_quantity" id="add_quantity" original-title="Enter Quantity Here" class="textbox validate[required,custom[number]]" style="width: 127px;" value="0">
				<?php if($im_sa['can_add'] || $im_sa['can_update']){ ?>
					<button type="button" class="btn btn-success" id="add_button_quantity" original-title="Add"><i class="glyphicon glyphicon-plus btn-xs" style="padding: 0px;"></i></button>
				<?php } ?>
				<?php if($im_sa['can_delete']){ ?>
					<button type="button" class="btn btn-danger" id="minus_button_quantity" original-title="Remove"><i class="glyphicon glyphicon-minus btn-xs" style="padding: 0px;"></i></button>
				<?php } ?>
			</li>
		</ul>

		<section class="clear"></section>

		<ul id="form">
			<li>Reason</li>
			<li>
				<script>
					var opts=$('#reasons_sources').html(), opts2='<option></option>'+opts;
				    $('#reasons').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
				    $('#reasons').select2({allowClear: true});
				</script>
				<select id='reasons' name='reasons' class='populate add_returns_trigger' style='width:280px;'></select>
				<select id='reasons_sources' class='validate[required]' style='display:none'>
					<option value='0'>-Select-</option>";
					<?php foreach($reasons as $key=>$value): ?>
						<option value="<?php echo $value['reason'] ?>"><?php echo $value['reason'] ?></option>
					<?php endforeach; ?>
				</select>
			</li>
		</ul>
	</section>
	<section id="right">
		<h1>Stock Information</h1>
		<ul id="form">
			<li>Quantity:</li>
			<li>
				<input type="text" name="quantity" id="quantity" class="quantity textbox add_inventory_trigger validate[required,custom[number]]" style="width: 127px;" value="" readonly>
				<input type="text" name="quantity_type" id="quantity_type" class="quantity_type textbox" style="width: 60px;" readonly>
			</li>
		</ul>	
		<section class="clear"></section>
	</section>				
</section>
<!-- <section class="clear"></section> -->
<!-- </section> -->