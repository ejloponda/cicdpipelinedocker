<script>
	$(function(){
		$(".add_submit_btn").on('click', function(event) {
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

		reset_all();
		reset_all_topbars_menu();
		$('.inventory_management_menu').addClass('hilited');

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
	/*$("#royalpreventive_opt").attr('checked', true);
		$("#royalpreventive_opt").live('click',function(){
			$('#cost_to_purchase').val("");
			$('#cost_to_purchase').attr('readonly', false);
		});

		$("#alist_opt").live('click',function(){
			$('#cost_to_purchase').val("0.00");
			$('#cost_to_purchase').attr('readonly', true);
		});

		$("#rpp_opt").live('click',function(){
			$('#cost_to_purchase').val("");
			$('#cost_to_purchase').attr('readonly', false);
		});*/
	});

	function getBatchNo(){
		var product_no = $("#product_no").val();
		if(product_no !=''){
			var x = product_no;
			/*$.post(base_url + 'inventory_management/generateBatchCode',{x:x},function(o) {*/
				$("#batch_number_view").html(x+"."+"001");
				$("#batch_number").val(x+"."+"001");
			/*});*/
		}
	}
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-inventory.png"></li>
			<li><h1>Add New Inventory</h1></li>
		</ul>
		
		<!-- <div class="id-number">ID Number: <input type="text" name="item_id_view" value="0<?php echo $inv['id'] ?>"></div> -->
		<div class="clear"></div>
	</hgroup>
		<form action="<?php echo url("inventory_management/saveNewInventory"); ?>" method="post" id="add_new_inventory_form" style="width:100%;">
			<section id="left" style="border-right: dashed 1px #d3d3d3;">	
				<h1>Basic Information</h1>
							<ul id="form">
								<li>Product No: <span>*</span></li>
								<li><input type="text" name="product_no" id="product_no" class="textbox add_inventory_trigger validate[required]" onchange="javascript: getBatchNo();"></li>
							</ul>
						<section class="clear"></section>

							<ul id="form">
								<li>Medicine Name: <span>*</span></li>
								<li><input type="text" name="medicine_name" class="textbox add_inventory_trigger validate[required]"></li>
							</ul>
						<section class="clear"></section>

							<ul id="form">
								<li>Generic Name: <span>*</span></li>
								<li><input type="text" name="generic_name" class="textbox add_inventory_trigger validate[required]"></li>
							</ul>
						
						<section class="clear"></section>
							<ul id="form">
								<li>Dosage: <span>*</span></li>
								<li>
									<input type="text" name="dosage" class="textbox add_inventory_trigger validate[required,custom[number]]" style="width: 127px;">
									<select name="dosage_type" id="dosage_type" class="select validate[required]">
										<?php foreach ($dosage as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['abbreviation'] ?></option>
										<?php } ?>
									</select>
								</li>
							</ul>	
						<section class="clear"></section>
							<ul id="form">
								<li>Quantity Per Bottle: </li>
								<li>
									<input type="text" name="qty_per_bottle" class="textbox add_inventory_trigger validate[custom[number]]" style="width: 127px;">
								</li>
							</ul>	
						<section class="clear"></section>
						<ul id="form">
							<li>Medicine Status</li>
							<li>
								<select name="status" id="status" class="select" style="width: 100px;">
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
								</select>
							</li>
						</ul>
						<section class="clear"></section>

			</section>

			<section id="right">
				<h1>Stock Information</h1>
						<section class="clear"></section>

								<ul id="form">
									<li>Product Cost:</li>
									<li>
										<input type="radio" name="stock" id="royalpreventive_opt" value="Royal Preventive" class="add_inventory_trigger"><label for="r1"><span></span>Royal Preventive</label>									
									<br>
										<input type="radio" name="stock" id="alist_opt" value="A-List"> <label for="r2" class="add_inventory_trigger"><span></span>A-List</label>
									<br>
										<input type="radio" name="stock" id="rpp_opt" value="RPP"> <label for="r3" class="add_inventory_trigger"><span></span>Royal Preventive Pharma</label>
									</li>
								</ul>	

						<section class="clear"></section>
								<ul id="form">
									<li>Cost to Purchase: </li>
									<li><input type="text" name="cost_to_purchase" id="cost_to_purchase" class="textbox add_inventory_trigger validate[required,custom[number]]" style="width: 99px;" placeholder="Cost to Purchase"> </li>
								</ul>
						
						<section class="clear"></section>
						

							<ul id="form">
								<li>Selling Price:</li>
								<li><input type="text" name="price" class="textbox add_inventory_trigger validate[required,custom[number]]" style="width: 127px;"></li>
							</ul>	
						
						<section class="clear"></section>
			</section>

		<div class="line03"></div>			
			<section id="left" style="border-right: dashed 1px #d3d3d3;">
			<div>
				<h1>Batch</h1>
					<input type="hidden" id="main_med_id" name="main_med_id" value="">
					<ul id="form">
						<li>Batch No:</li>
						<li><input type="hidden" id="batch_number" name="batch_number" class="textbox"  value="1" readonly="readonly">
							<span id="batch_number_view"><?php echo $data;?></span></li>
					</ul>
					<section class="clear"></section>
					<ul id="form">
								<li>Quantity:</li>
								<li>
									<input type="text" name="quantity" class="textbox add_inventory_trigger validate[required,custom[number]]" style="width: 127px;">
									<select name="quantity_type" id="quantity_type" class="select validate[required]">
										<?php foreach ($quantity as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>"><?php echo $value['abbreviation'] ?></option>
										<?php } ?>
									</select>
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
			</div>
				
			</section>
		<section class="clear"></section>
	
		<section id="buttons">
			<button class="form_button add_submit_btn" type="button">Save & Continue</button>
			<button class="form_button cancel_inventory_form" type="button">Cancel</button>
		</section>	
</form>
</section>
<!-- <section class="clear"></section> -->
</section>

<div>