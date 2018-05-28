<script>
	$(function(){
		getMedicineList();
		CKEDITOR.replace( 'edit_remarks',{removePlugins: 'toolbar,elementspath',width:['810px'],height:['130px'], resize_enabled:false});
		
		$(".myTipsy").tipsy({gravity: 's'});
/*		$('#estimated_date').datepicker({
			format: 'yyyy-mm-dd'
   		 });*/
		$("#add_new_order_form").validationEngine({scroll:false});
		$("#add_new_order_form").ajaxForm({
	        success: function(o) {
	      		if(o.is_successful) {
	      			IS_ADD_ORDER_FORM = false;
	      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
	      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
	      			loadView(o.order_id);
	      			$.unblockUI();
	      		} else {
	      			default_success_confirmation({message : o.message, alert_type: "alert-danger"});
	      			$.unblockUI();
	      		}
				$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
	        },
	        beforeSubmit : function(evt){
	        	$.blockUI();
	        	var patient_id = $("#user_list").val();
	        	var attending_doctor = $("#attending_doctor").val();
	        	if(patient_id == 0)
	        	{ 
	        		alert("Warning: No Patient Selected.");
	        		$.unblockUI();
	        		return false;
	        	} else if (attending_doctor == 0){
	        		alert("Warning: No Attending Doctor Selected.");
	        		$.unblockUI();
	        		return false;
	        	} else {
	        		var bol = true;
	        		$(".medicine_quantity_class").each(function(){
	        			var med = $(this).val();
	        			// alert();
		        		if(med == 0){
		        			alert("Warning: Your Medicine has 0 Quantity.");
		        			bol = false;
		        		}
		        	});
		        	return bol;
	        	}

	        },
	        dataType : "json"
	    });

	    $(".edit_submit_btn").on('click', function(event) {
			//$.blockUI();
			for ( instance in CKEDITOR.instances ) {
       		 CKEDITOR.instances[instance].updateElement();
   			}
			$('#add_new_order_form').submit();
		});

	    $(".new_order_form").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_ORDER_FORM = true;
			}
		});

		$(".cancel_order").on('click', function(){
			window.location.hash = "list";
			reload_content("list");
		});

		reset_all();
		reset_all_topbars_menu();
		getTotalPriceMedicine();
		updateTotalCharges();
		$('.order_menu').addClass('hilited');


		$("#medicine_list_wrapper_loader").html(default_ajax_loader);
		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}


		$(".medicine_quantity_class").live('change', function(){
			var main_index = $(this).data("ctr");
			var medicine_quantity 	= $.trim($("#medicine_quantity_"+main_index).val());
			var medicine_price 		= $.trim($("#medicine_price_"+main_index).val());
			var total = medicine_quantity * medicine_price;
			$("#medicine_total_price_"+main_index).val(total.toFixed(2));
			$("#span_medicine_total_price_"+main_index).html(addCommas(total.toFixed(2)));
			getTotalPriceMedicine();
		});

		$(".add_other_charges").on('click', function(){
			addOtherCharges();
		});

		$('#user_list').live('change', function(){
			var patient_id = $(this).val();
			if(patient_id){
				getPatientDetails(patient_id);
			}
		});

		function getMedicineList(){		
			var choice =" <?php echo $order['pharmacy']?>";
			$.post(base_url + "orders_management/getMedicineList",{choice:choice},function(o){
				$(".medicine_wrapper").html(o);
			});
		}
	});
</script>
<style>
.textbox{ text-align: right !important;}
.sub_table{margin:0 !important; padding: 0 !important;}
#s2id_user_list .select2-choice .select2-chosen{margin-top: 3px;}
</style>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-order.png"></li>
			<li><h1>Edit Order</h1></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
		<form action="<?php echo url("orders_management/saveOrder"); ?>" method="post" id="add_new_order_form" style="width:100%;">
			<input type="hidden" name="order_id" value="<?php echo $order['id'] ?>">
			<input type="hidden" name="patient_id" value="<?php echo $patient['id'] ?>">
			<ul class="regimen-ID" >
				<li><img class="photoID" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png"></li>
				<li class="patient"><span><?php echo $patient['patient_name'] ?></span><br>
					Patient Code: <b><?php echo $patient['patient_code'] ?></b>
				</li>
				<li style="margin: 47px 0px 0px -174px;">Age: <b> <?php echo $patient['age'] .'yrs. old'?></b></li>
			</ul>
			<ul id="filter-search" style="padding-right: 70px;">
				<li><b>Date Generated:</b> <?php $date = new Datetime($order['date_created']); $date_generated = date_format($date, "M d, Y"); echo $date_generated; ?></li>
				<br/>
				<li style="color:gray; padding-top: 3px;"><b>Estimated End Date</b>
					<input type="text" id="estimated_date" name="estimated_date" class="textbox new_order_form" style="width:70px; color:black;" value = "<?php echo $order['estimated_date']?>">
				</li>
				<li> <b style="font-size: 12px;">Credit: </b> <b style="color: black;"><?php echo $patient['credit'] ?></b></li>
				<ul>
				<li style="padding-top: 4px;"> <b>Attending Doctor </b>
					<script>
						var opts=$('#attending_doctor_source').html(), opts2='<option></option>'+opts;
					    $('#attending_doctor').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
					    $('#attending_doctor').select2({allowClear: true});
					</script>
					<select id='attending_doctor' name='attending_doctor' class='populate add_returns_trigger' style='width:200px;'></select>
					<select id='attending_doctor_source' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";
						<?php foreach($doctors as $key=>$value): ?>
							<option value="<?php echo $value['id'] ?>" <?php echo ($order['doc_attending_id'] == $value['id'] ? "selected" : "") ?>><?php echo $value['full_name'] ?></option>
						<?php endforeach; ?>
					</select>
				</li>
				</ul>
			</ul>
			<ul class="regimen-ID">
				
			</ul>
			<div class="clear"></div>
			
			<div id="left" style="text-align: center;">
				<h1 class="bg">Medicine</h1>
				<br>
				<br>
				<br>
				<br>
				<br>
				<div class="medicine_wrapper"> </div>
				
				<button type="button" id="add_medicine_id" class="invoice-modifier-add" onclick='javascript: addMedicine()'>+ Add Medicine</button>
				<br><br>
				<br><br>
				<div id="regimen_gross_wrapper" style="display: inline-flex;" class="price">
					<span style="margin-right:10px;">REGIMEN GROSS:</span>
					<span style="margin-right:95px;">Php <span id="total_medicine_price" class="pricecolor">0.00</span></span>
				</div>
			</div>
			<div id="right" style="margin: 30px 0px 30px 0px !important;width: 46%;">
				<h1 class="bg">Medicine Tray</h1>
				<div class="box">
					<?php $main_index 	= 20; ?>
					<?php foreach ($order_meds as $key => $value) { ?>
					<?php
						
						$medicine_name 	= $value['medicine_name'];
						$medicine_id 	= $value['medicine_id'];
						$qty 			= $value['quantity'];

						$div_wrapper 			= "other_charge_row_{$main_index}";

						$field_id 				= "medicine[{$main_index}][id]";
						$field_medicine_id 		= "medicine[{$main_index}][medicine_id]";
						$field_quantity 		= "medicine[{$main_index}][quantity]";
						$field_price 			= "medicine[{$main_index}][price]";
						$field_total_price		= "medicine[{$main_index}][total_price]";

						$field_quantity_id 			= "medicine_quantity_{$main_index}";
						$field_quantity_class 		= "medicine_quantity_class";
						$field_price_id 			= "medicine_price_{$main_index}";
						$field_total_price_id 		= "medicine_total_price_{$main_index}";
						$field_total_price_class 	= "medicine_total_price_class";
						$span_total_price 			= "span_medicine_total_price_{$main_index}";
					?>
						<div class='minibox <?php echo $div_wrapper ?>'>
							<span id='button-box' onclick='javascript: removeMedicineDB("<?php echo $div_wrapper ?>", <?php echo $value['id'] ?>)'>X</span> 
							<span id='med01'>
								<span class='title'><input type='hidden' id='old_data_id' name='<?php echo $field_id ?>' value='<?php echo $value['id'] ?>'><input type='hidden' name='<?php echo $field_medicine_id ?>' value='<?php echo $medicine_id ?>'><?php echo $medicine_name ?> / <?php echo $value['dosage'] ?> <?php echo $dosage['abbreviation'] ?></span>
								<br><br>&nbsp;&nbsp;&nbsp;
								<span>Quantity: <input type='text' data-ctr='<?php echo $main_index ?>' name='<?php echo $field_quantity ?>' id='<?php echo $field_quantity_id ?>' class='validate[required, custom[onlyNumberSp]] textbox <?php echo $field_quantity_class ?>' style='width: 30px; text-align: center !important;' value='<?php echo $qty ?>'> / <input type='hidden' class='textbox' name='<?php echo $field_price ?>' id='<?php echo $field_price_id ?>' value='<?php echo $value['price'] ?>'> P <?php echo $value['price'] ?></span>
								<span class='price'>Php&nbsp;
									<span class='pricecolor'><input type='hidden' class='textbox <?php echo $field_total_price_class ?>' name='<?php echo $field_total_price ?>' id='<?php echo $field_total_price_id ?>' value='<?php echo $value['total_price'] ?>'><span id='<?php echo $span_total_price ?>'><?php echo number_format($value['total_price'],2,'.',',') ?></span></span>
								</span>
							</span>
						</div>
						<?php $main_index++ ?>
					<?php } ?>
					<div id="medicine_list_wrapper_loader" style="display: none;"></div>
					<div id="medicine_list_wrapper"></div>
				</div>
			</div>

			<div class="clear"></div>
			<?php if($order['pharmacy'] != 'RPP'){?>
			<div id="left" style="text-align: center;">
				<h1 class="bg">Other Charges</h1>
				<br><br><br>
				<br><br>
				<script>				
					var opts=$('#o_description1').html(), opts2='<option></option>'+opts;
				    $('#o_description').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
				    $('#o_description').select2({allowClear: true});

				    $("#o_description").live('change', function(e) {
						var price = $(this).find(':selected').attr('data-price');
						$('#o_cost').val(price);
					});
				</script>
				<select class="myTipsy new_order_form" title="Description" id="o_description" style="width:324px;"></select>
				<select id='o_description1' style='display:none'><option value='0'>-Select Other Charges-</option>";
					<?php foreach ($other_charges as $key => $value) { ?>
					<option value="<?php echo $value['r_centers'] ?>" data-id="<?php echo $value['id'] ?>" data-price="<?php echo $value['price'] ?>"><?php echo $value['r_centers'] ?></option>
					<?php } ?>
				</select> 
				<!-- <select class="select01 myTipsy new_order_form" title="Description" id="o_description" style="width:324px;">
					<?php foreach ($other_charges as $key => $value) { ?>
						<option value="<?php echo $value['r_centers'] ?>" data-id="<?php echo $value['id'] ?>"><?php echo $value['r_centers'] ?></option>
					<?php } ?>
				</select> -->
				<br><br>
				<span class="p">Php&nbsp; </span><input type="text" title="Cost" id="o_cost" style="width:107px;" class="other_charges_main  new_order_form textbox myTipsy" placeholder="0.00">
				&nbsp; 
				<span class="p">Quantity&nbsp; </span><input type="text" id="o_quantity" title="Quantity" style="width: 100px;" class="other_charges_main new_order_form myTipsy textbox" placeholder="0">
				<br><br>
				<button type="button" id="add_medicine_id" class="invoice-modifier-add add_other_charges">+ Add Modifier</button>
				<br><br>
				<br><br>
				<div id="other_charges_price_wrapper" style="display: inline;" class="price">
					<span style="margin-right:10px;">CHARGES GROSS:</span>
					<span style="margin-right:95px;">Php <span id="total_other_charges_price" class="pricecolor">0.00</span></span>
				</div>
			</div>
			<div id="right" style="margin: 30px 0px 30px 0px !important;width: 46%;">
				<h1 class="bg">Other Charges Tray</h1>
				<div class="box">
					<?php $others_index = 20; ?>
					<?php #debug_array($order_others) ?>
					<?php foreach ($order_others as $key => $value) { ?>
					<?php
						
						$description_id	= $value['description_id'];
						$description	= $value['description'];
						$qty 			= $value['quantity'];

						$div_wrapper 			= "others_wrapper_{$others_index}";

						$field_id 				= "others[{$others_index}][id]";
						$field_description_id 	= "others[{$others_index}][description_id]";
						$field_quantity 		= "others[{$others_index}][quantity]";
						$field_price 			= "others[{$others_index}][cost]";
						$field_total_price		= "others[{$others_index}][total_cost]";

						$field_quantity_id 			= "others_quantity_{$others_index}";
						$field_quantity_class 		= "others_quantity_class";
						$field_price_id 			= "others_price_{$others_index}";
						$field_total_price_id 		= "others_total_price_{$others_index}";
						$field_total_price_class 	= "other_charges_cost_class";
						$span_total_price 			= "span_others_total_price_{$others_index}";
					?>
						<div class='minibox <?php echo $div_wrapper ?>'>
						<span id='button-box' onclick='javascript: removeMedicine("<?php echo $div_wrapper ?>", <?php echo $value['id'] ?>)'>X</span> 
							<span id='med01'>
								<span class='title'><input type='hidden' name='<?php echo $field_id ?>' value='<?php echo $value['id'] ?>'><input type='hidden' name='<?php echo $field_description_id ?>' value='<?php echo $description_id ?>'><?php echo $description ?></span>
								<br><br>&nbsp;&nbsp;&nbsp;
								<span>Quantity: <input type='hidden' data-ctr='<?php echo $others_index ?>' name='<?php echo $field_quantity ?>' id='<?php echo $field_quantity_id ?>' class='validate[required, custom[onlyNumberSp]] textbox <?php echo $field_quantity_class ?>' style='width: 30px; text-align: center !important;' value='<?php echo $qty ?>'><?php echo $qty ?> / <input type='hidden' class='textbox' name='<?php echo $field_price ?>' id='<?php echo $field_price_id ?>' value='<?php echo $value['cost'] ?>'> P <?php echo number_format($value['cost'],2,'.',',') ?></span>
								<span class='price'>Php&nbsp;
									<span class='pricecolor'><input type='hidden' class='textbox <?php echo $field_total_price_class ?>' name='<?php echo $field_total_price ?>' id='<?php echo $field_total_price_id ?>' value='<?php echo $value['total_cost'] ?>'><span id='<?php echo $span_total_price ?>'><?php echo number_format($value['total_cost'],2,'.',',') ?></span></span>
								</span>
							</span>
						</div>
						<?php $others_index++ ?>
					<?php } ?>
					<div id="other_charges_list"></div>
				</div>
			</div>
			<div class="clear"></div>
			<?php } ?>		
			<div class="line02"></div>	

			<div>
				<ul id="notes">
					<li>Remarks</li>
					<li><textarea class="add_regimen_general_form" id="edit_remarks" name="edit_remarks"><?php echo $order['remarks']?></textarea></li>
				</ul>
			</div>
			<div class="clear"></div>		
			<section class="clear"></section>	
			<section id="buttons">
				<button class="form_button edit_submit_btn" type="button">Save & Continue</button>
				<button class="form_button cancel_order" type="button">Cancel</button>
			</section>	
</form>
</section>
<section class="clear"></section>
</section>