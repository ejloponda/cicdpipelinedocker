<script>
	$(function(){
		$('.tipsy-inner').remove();

		reset_all();
		reset_all_topbars_menu();
		$('.menu_returns_inv').addClass('hidden');
		$('.inventory_management_menu').addClass('hilited');
		// $('.add_new_inventory_form').addClass('sub-hilited');
		$('.inventory_list_form').addClass('sub-hilited');

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

		// from stock
		// $("#royalpreventive_opt").attr('checked', true);
		/*$("#royalpreventive_opt").live('click',function(){
			$('#cost_to_purchase').attr('readonly', false);
		});

		$("#alist_opt").live('click',function(){
			$('#cost_to_purchase').val("0.00");
			$('#cost_to_purchase').attr('readonly', true);
		});*/

		var stock = "<?php echo $inv['stock'] ?>";
		var stock_price = "<?php echo $inv['stock_price'] ?>";
		/*if(stock == "Royal Preventive" || stock == 'RPP'){
			if(stock_price == '0.00'){
				$('#cost_to_purchase').val("");
			}
			
			$('#cost_to_purchase').attr('readonly', false);
		} else {
			$('#cost_to_purchase').val("0.00");
			$('#cost_to_purchase').attr('readonly', true);
		}*/

		$(".edit_submit_btn").on('click', function(event) {
			//$.blockUI();
			$('#add_new_inventory_form').submit();
		});

		$("#add_new_inventory_form").validationEngine({scroll:false});
		$("#add_new_inventory_form").ajaxForm({
	        success: function(o) {
	      		if(o.is_successful) {
	      			IS_ADD_INVENTORY_FORM_CHANGE = false;
	      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
	      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
	      			$.unblockUI();
	      		} else {
	      			default_success_confirmation({message : o.message, alert_type: "alert-danger"});
	      		}
	      		window.location.hash = "lists";
				reload_content("lists");
				$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
	        },
	        beforeSubmit : function(evt){
	         	$.blockUI();
	         },
	        
	        dataType : "json"
	    });

	    $(".add_inventory_trigger").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_INVENTORY_FORM_CHANGE = true;
			}
		});
	});
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-inventory.png"></li>
			<li><h1>Edit Inventory</h1></li>
		</ul>
		
		<div class="id-number">ID Number: <input type="text" name="item_id_view" id="item_id_view" value="<?php echo $inv['id'] ?>" readonly></div>
		<div class="clear"></div>
	</hgroup>
	<form action="<?php echo url("inventory_management/saveNewInventory") ?>" method="post" id="add_new_inventory_form"  style="width:100%;">
	<section id="left" style="border-right: dashed 1px #d3d3d3;">	
		<h1>Basic Information</h1>
		<input type="hidden" name="item_id" id="item_id" value="<?php echo $inv['id'] ?>">
					<ul id="form">
						<li>Product No: <span>*</span></li>
						<li><input type="text" name="product_no" class="textbox add_inventory_trigger validate[required]" value="<?php echo $inv['product_no'] ?>"></li>
					</ul>
				<section class="clear"></section>

					<ul id="form">
						<li>Medicine Name: <span>*</span></li>
						<li><input type="text" name="medicine_name" class="textbox add_inventory_trigger validate[required]" value="<?php echo $inv['medicine_name'] ?>"></li>
					</ul>
				<section class="clear"></section>

					<ul id="form">
						<li>Generic Name: <span>*</span></li>
						<li><input type="text" name="generic_name" class="textbox add_inventory_trigger validate[required]" value="<?php echo $inv['generic_name'] ?>"></li>
					</ul>
				
				<section class="clear"></section>
				
					<ul id="form">
						<li>Dosage: <span>*</span></li>
						<li>
							<input type="text" name="dosage" class="textbox add_inventory_trigger validate[required,custom[number]]" style="width: 127px;" value="<?php echo $inv['dosage'] ?>">
							<select name="dosage_type" id="dosage_type" class="select validate[required]">
								<?php foreach ($dosage as $key => $value) { ?>
									<option value="<?php echo $value['id'] ?>" <?php echo ($inv['dosage_type'] == $value['id'] ? "selected" : "") ?>><?php echo $value['abbreviation'] ?></option>
								<?php } ?>
							</select>
						</li>
					</ul>	
				<section class="clear"></section>

					<ul id="form">
						<li>Quantity Per Bottle: </li>
						<li>
							<input type="text" name="qty_per_bottle" class="textbox add_inventory_trigger validate[custom[number]]" style="width: 127px;" value="<?php echo $inv['qty_per_bottle'] ?>">
							<span> pcs</span>
						</li>
					</ul>	
					<section class="clear"></section>

				<ul id="form">
					<li>Medicine Status</li>
					<li>
						<li>
							<select name="status" id="status" class="select form_charges" style="width: 100px;">
								<option value="Active" <?php echo ($inv['status'] == "Active" ? "selected" : "") ?>>Active</option>
								<option value="Inactive" <?php echo ($inv['status'] == "Inactive" ? "selected" : "") ?>>Inactive</option>
							</select>
						</li>	
					</li>
				</ul>
	</section>
	<section id="right">
		<h1>Stock Information</h1>

				<ul id="form">
					<li>Product Cost:</li>
					<li>
						<input type="radio" name="stock" id="royalpreventive_opt" class="add_inventory_trigger" <?php echo ($inv['stock'] == "Royal Preventive" ? "checked" : "") ?> value="Royal Preventive"><label for="r1"><span></span>Royal Preventive</label>
					<br>
						<input type="radio" name="stock" id="alist_opt" class="add_inventory_trigger" value="A-List" <?php echo ($inv['stock'] == "A-List" ? "checked" : "") ?>> <label for="r2"><span></span>A-List</label>
					<br>
						<input type="radio" name="stock" id="rpp_opt" class="add_inventory_trigger" value="RPP" <?php echo ($inv['stock'] == "RPP" ? "checked" : "") ?>> <label for="r3"><span></span>Royal Preventive Pharma</label>
					</li>
				</ul>	
		
		<section class="clear"></section>
		
				<ul id="form">
					<li>Cost to Purchase</li>
					<li><input type="text" name="cost_to_purchase" id="cost_to_purchase" class="textbox validate[required,custom[number]]" style="width: 99px;" placeholder="Cost to Purchase" value="<?php echo $inv['stock_price'] ?>"></li>
				</ul>
		
		<section class="clear"></section>

	</section>
	<div class="line03"></div>				
	<section id="left" style="border-right: dashed 1px #d3d3d3;">
		
			<h1>Batch</h1>
				<strong>List of Batch</strong>
				<section class="clear"></section>
				<ul>
					<?php foreach ($batches as $key => $value) { ?>
						<li><a href="javascript: void(0);" onclick="javascript: batch_details(<?php echo $value['id'] ?>)"><?php echo $value['batch_no'] ?></a></li>
					<?php } ?>
				</ul> 

			<!-- <h1>Batch</h1>
					<ul id="form">
						<li>Batch No:</li>
						<li><input type="text" id="batch_number" name="batch_number" class="textbox" value="<?php echo trim($batch['batch_no']) ?>" readonly="readonly"></li>
					</ul>
				<section class="clear"></section>

					<ul id="form">
						<li>Quantity:</li>
						<li>
							<input type="text" name="quantity" id="quantity" class="textbox add_inventory_trigger validate[required,custom[number]]" style="width: 127px;" value="<?php echo $inv['remaining'] ?>" readonly>
							<select name="quantity_type" id="quantity_type" class="select validate[required]">
								<?php foreach ($quantity as $key => $value) { ?>
									<option value="<?php echo $value['id'] ?>" <?php echo ($inv['quantity_type'] == $value['id'] ? "selected" : "") ?>><?php echo $value['abbreviation'] ?></option>
								<?php } ?>
							</select>
						</li>
					</ul>	
				
				<section class="clear"></section>

					<ul id="form">
						<li>Purchase Date</li>
						<li><input type="text" id="purchase_date" name="purchase_date" class="textbox add_inventory_trigger" value="<?php echo $inv['purchase_date'] ?>"></li>
					</ul>
				
				<section class="clear"></section>
				

					<ul id="form">
						<li>Expiration Date</li>
						<li><input type="text" id="expiration_date" name="expiration_date" class="textbox add_inventory_trigger" value="<?php echo $inv['expiry_date'] ?>"></li>
					</ul> -->
		</section> 
		<section id="right">
			<h1>VAT RATE</h1>
			<section class="clear"></section>
			<h1>Selling Price per Medicine</h1>

				<ul id="form">
					<li>Selling Price:</li>
					<li><input type="text" id="price" name="price" class="textbox add_inventory_trigger prices validate[custom[number],required]" style="width: 127px;" value="<?php echo $inv['price'] ?>"></li>
				</ul>	
			<section class="clear"></section>
				<ul id="form">
					<li>NET of VAT</li>
					<li><input type="text" id="net_of_vat" name="net_of_vat" class="textbox add_inventory_trigger prices validate[custom[number]]" style="width: 127px; background-color: #e4e4e4;" readonly value=""></li>
				</ul>	
			
			<section class="clear"></section>
			
				<ul id="form">
					<li>VAT 12%</li>
					<li><input type="text" id="vat" name="vat" class="textbox add_inventory_trigger prices validate[custom[number]]" style="width: 127px; background-color: #e4e4e4;" readonly value=""></li>
				</ul>

			<section class="clear"></section>

			<h1>Cost of Sales</h1>
				<ul id="form">
					<li>Cost of Sales</li>
					<li><input type="text" id="cost_of_sales" name="cost_of_sales" class="textbox prices add_inventory_trigger validate[custom[number]]" style="width: 127px;" value="<?php echo $inv['cost_sales'] ?>"></li>
				</ul>	
			
			<section class="clear"></section>

				<ul id="form">
					<li>NET of VAT</li>
					<li><input type="text" id="cost_net_of_vat" name="cost_net_of_vat" class="textbox prices add_inventory_trigger validate[custom[number]]" style="width: 127px; background-color: #e4e4e4;" readonly value=""></li>
				</ul>	
			
			<section class="clear"></section>
			
				<ul id="form">
					<li>VAT 12%</li>
					<li><input type="text" id="cost_vat" name="cost_vat" class="textbox add_inventory_trigger prices validate[custom[number]]" style="width: 127px; background-color: #e4e4e4;" readonly value=""></li>
				</ul>	

		</section>
	</form>
		<section class="clear"></section>
	
		<section id="buttons">
			<button class="form_button edit_submit_btn">Save & Continue</button>
			<button class="form_button cancel_inventory_form">Cancel</button>
		</section>			
</section>
<section class="clear"></section>
</section>

<script>
	$(function(){
		computePrice();
		computeCOS();
		$("#price").on('change', function(){
			computePrice();
		});

		$("#cost_of_sales").on('change', function(){
			computeCOS();
		});

		$('.prices').on('change',function() {
			var value = $(this).val();
			var formatted = number_format(value,2,'.','');
			$(this).val(formatted);
		});
	});

	function computePrice(){
		var price = parseFloat($("#price").val().replace(/,/g,''));
		var net_of_vat = (price / 1.12);
		var vat = net_of_vat * (12/100);

		$("#net_of_vat").val(net_of_vat.toFixed(2));
		$("#vat").val(vat.toFixed(2));
	}

	function computeCOS(){
		var cost_of_sales = parseFloat($("#cost_of_sales").val().replace(/,/g,''));
		var net_of_vat = (cost_of_sales / 1.12);
		var vat = net_of_vat * (12 / 100);

		$("#cost_net_of_vat").val(net_of_vat.toFixed(2));
		$("#cost_vat").val(vat.toFixed(2));
	}

	function batch_details(id){
		$.post(base_url + "inventory_management/edit_batch_details", {id:id}, function(o){
			$('#editBatch_modal_wrapper').html(o);
			$('#editBatch_modal_wrapper').modal("show");
		})

	}
</script>