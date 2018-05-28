<script>
	$(function(){
		$('.tipsy-inner').remove();
		reset_all();
		reset_all_topbars_menu();
		$('.inventory_management_menu').addClass('hilited');

		$("#edit_returns_form").validationEngine({scroll:false});
		$("#edit_returns_form").ajaxForm({
	        success: function(o) {
	      		if(o.is_successful) {
	      			IS_ADD_INVENTORY_FORM_CHANGE = false;
	      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
	      		} else {
	      			default_success_confirmation({message : o.message, alert_type: "alert-danger"});
	      		}
	      		window.location.hash = "returns";
				reload_content("returns");
				$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
	        },
	        beforeSubmit: function(){
	        	$("#royalpreventive_opt").attr('disabled', false);
	        	$("#alist_opt").attr('disabled', false);
	        },
	        
	        dataType : "json"
	    });

	    $(".add_returns_trigger").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_INVENTORY_FORM_CHANGE = true;
			}
		});

		$("#medicine_list").on('change', function() {
			var med = $("#medicine_list").val();
			getMedicineDetails(med);
		});

		function getMedicineDetails(med){
			var med = parseInt(med);
			$.post(base_url + "inventory_management/getMedicineDetails", {med:med}, function(o){
				$("#generic_name").val(o.output['generic_name']);
				$("#dosage").val(o.output['dosage']);
				$("#dosage_type").val(o.output['dosage_type']);
				$("#quantity").val(o.output['quantity']);
				$("#quantity_type").val(o.output['quantity_type']);
				$("#returns_quantity_type").val(o.output['quantity_type']);
				if(o.output['stock'] == "Royal Preventive"){
					$("#royalpreventive_opt").attr('checked', true);
				}else{
					$("#alist_opt").attr('checked', true);
				}
				$("#purchase_date").val(o.output['purchase_date']);
				$("#expiration_date").val(o.output['expiry_date']);
			}, "json");	
		}

		if("<?php echo $inv['stock'] ?>" == "Royal Preventive"){
			$("#royalpreventive_opt").attr('checked', true);
		}else{
			$("#alist_opt").attr('checked', true);
		}
	});
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-inventory.png"></li>
			<li><h1>Edit Return Inventory</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript:void(0);" onClick="$('#edit_returns_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);"><img class="icon cancel_returns_form" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		<!-- <div class="id-number">ID Number: <input type="text" name="id"></div> -->
		<div class="clear"></div>
	</hgroup>
	<form action="<?php echo url("inventory_management/saveNewReturns") ?>" method="post" id="edit_returns_form"  style="width:100%;">
		<section id="left" style="border-right: dashed 1px #d3d3d3;">	
			<h1>Basic Information</h1>
				<input type="hidden" id="return_item_id" name="return_item_id" value="<?php echo $returns['id'] ?>">
						<ul id="form">
							<li>Medicine Name: <span>*</span></li>
							<li>
								<!-- <input type="text" name="medicine_name" class="textbox add_returns_trigger validate[required]"> -->
								<script>
									$(function() {
										var opts=$("#medicine_source").html(), opts2="<option></option>"+opts;
									    $("#medicine_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
									    $("#medicine_list").select2({allowClear: true});
									});
								</script>
								<select id="medicine_list" name="medicine_name" class="populate add_returns_trigger" style="width:280px;"></select>
								<select id="medicine_source" class="validate[required]" style="display:none">
								  <?php foreach($medicines as $key=>$value): ?>
								    <option value="<?php echo $value['id']; ?>" <?php echo ($returns['medicine_id'] == $value['id'] ? "selected" : "") ?>><?php echo $value['medicine_name'] . " " . $value['dosage'] . " mg" ?></option>
								  <?php endforeach; ?>
								</select>
							</li>				
						</ul>
					<section class="clear"></section>

						<ul id="form">
							<li>Generic Name: <span>*</span></li>
							<li><input type="text" name="generic_name" id="generic_name" class="textbox add_returns_trigger validate[required]" value="<?php echo $inv['generic_name'] ?>" readonly></li>
						</ul>
					
					<section class="clear"></section>
					

						<ul id="form">
							<li>Dosage: <span>*</span></li>
							<li>
								<input type="text" name="dosage" id="dosage" class="textbox add_returns_trigger validate[required,custom[number]]" style="width: 70px;" value="<?php echo $inv['dosage'] ?>" readonly>
								<input type="text" name="dosage_type" id="dosage_type" class="textbox add_returns_trigger validate[required]" style="width: 70px;" value="<?php $dosage = Dosage_Type::findById(array("id" => $inv['dosage_type'])); echo $dosage['abbreviation'] ?>" readonly>
							</li>
						</ul>	
					<section class="clear"></section>
		</section>
		<section id="right">
			<h1>Stock Information</h1>
						<ul id="form">
							<li>Quantity:</li>
							<li>
								<input type="text" name="quantity" id="quantity" class="textbox add_returns_trigger validate[required,custom[number]]" style="width: 70px;" value="<?php echo $returns['total_quantity'] ?>" readonly>
								<input type="text" name="quantity_type" id="quantity_type" class="textbox add_returns_trigger validate[required]" style="width: 70px;" value="<?php $quantity = Quantity_Type::findById(array("id" => $inv['quantity_type'])); echo $quantity['abbreviation'] ?>" readonly>
							</li>
						</ul>	
					
					<section class="clear"></section>

							<ul id="form">
								<li>From Stock:</li>
								<li>
									<input type="radio" name="stock" id="royalpreventive_opt" value="Royal Preventive" class="add_returns_trigger" disabled> <label for="r1"><span></span>Royal Preventive</label>
								<br>
									<input type="radio" name="stock" id="alist_opt" value="A-List" disabled> <label for="r2" class="add_returns_trigger"><span></span>A-List</label>
								</li>
							</ul>	
					
					<section class="clear"></section>

		</section>
		<div class="line03"></div>				
		<section id="left" style="border-right: dashed 1px #d3d3d3;">
			<h1>Miscellaneous Details</h1>
				<ul id="form">
					<li>Purchase Date</li>
					<li><input type="text" id="purchase_date" name="purchase_date" class="textbox add_returns_trigger" value="<?php echo $inv['purchase_date'] ?>" readonly></li>
				</ul>
			
			<section class="clear"></section>
			
				<ul id="form">
					<li>Expiration Date</li>
					<li><input type="text" id="expiration_date" name="expiration_date" class="textbox add_returns_trigger" value="<?php echo $inv['expiry_date'] ?>" readonly></li>
				</ul>
		</section>
		<section id="right">
			<h1>Returns Details</h1>
			<ul id="form">
				<li>Returned by: <span>*</span></li>
				<li>
					<script>
						// $(function() {
						// 	var opts=$("#user_source").html(), opts2="<option></option>"+opts;
						//     $("#user_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
						//     $("#user_list").select2({allowClear: true});
						// });
					</script>
					<!-- <select id="user_list" name="returned_by_name" class="populate add_returns_trigger" style="width:220px;"></select>
					<select id="user_source" class="validate[required]" style="display:none">
					  <?php foreach($patients as $key=>$value): ?>
					    <option value="<?php echo $value['id']; ?>"><?php echo $value['firstname'] . " " . $value['lastname']; ?></option>
					  <?php endforeach; ?>
					</select> -->
					<input type="text" name="returned_by_name" class="textbox validate[required] add_returns_trigger" autocomplete="on" style="width: 220px;" readonly value="<?php echo $returns['returned_by'] ?>">
					<input type="hidden" name="returned_by_id" class="textbox" autocomplete="on" readonly value="<?php echo $returns['returned_by_id'] ?>">
				</li>
			</ul>

			
		
		<section class="clear"></section>

			<ul id="form">
					<li>Returns Quantity: <span>*</span></li>
					<li>
						<input type="text" name="returns_quantity" class="textbox add_returns_trigger validate[required,custom[number]]" style="width: 70px;" value="<?php echo $returns['returned_quantity'] ?>">
						<input type="text" name="returns_quantity_type" id="returns_quantity_type" class="textbox add_returns_trigger validate[required]" style="width: 70px;" value="<?php $quantity = Quantity_Type::findById(array("id" => $inv['quantity_type'])); echo $quantity['abbreviation'] ?>" readonly>
					</li>
				</ul>	
			<section class="clear"></section>
		
			<ul id="form">
				<li>Reason for return: <span>*</span></li>
				<li><textarea type="text" name="returns_reason" class="validate[required] add_returns_trigger"><?php echo $returns['return_reasons'] ?></textarea></li>
			</ul>
						
		</section>
	</form>
		<section class="clear"></section>
	
		<section id="buttons">
			<button class="form_button" onClick="$('#edit_returns_form').submit();">Save & Continue</button>
			<button class="form_button cancel_returns_form">Cancel</button>
		</section>			
</section>
<section class="clear"></section>
</section>