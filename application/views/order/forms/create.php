<script>
	$(function(){
		CKEDITOR.replace( 'remarks',{removePlugins: 'toolbar,elementspath',width:['810px'],height:['130px'], resize_enabled:false});

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
	      			window.location.hash = "list";
					reload_content("list");
					$.unblockUI();
	      		} else {
	      			default_success_confirmation({message : o.message, alert_type: "alert-danger"});
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

		$(".add_submit_btn").on('click', function(event) {
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

		$(document).ready(function() {  
			var id = $(this).data("id");
			var confirm = new jBox('Confirm', {
			content: '<h3>Select pharmacy.</h3>',
			confirmButton: 'Royal Pharmacy',
			cancelButton: 'Royal Preventive',
			addClass: "button-wrapper-jbox",
			closeOnClick: false,
			closeButton: false,
			confirm: function(){
				var choice = 'RPP';
				$.post(base_url + "orders_management/getMedicineList",{choice:choice},function(o){
						$(".medicine_wrapper").html(o);
				});
			},
			cancel: function(){
				var choice = 'Royal Preventive';
				$.post(base_url + "orders_management/getMedicineList",{choice:choice},function(o){
						$(".medicine_wrapper").html(o);
				});
			},
			animation: {open: 'tada', close: 'pulse'}
			});
			confirm.open();		
		});
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
			<li><h1>New Order</h1></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
		<form action="<?php echo url("orders_management/saveOrder"); ?>" method="post" id="add_new_order_form" style="width:100%;">
			<ul class="regimen-ID" >
				<li><img class="photoID" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png"></li>
				<li class="patient">
					<script>
						$(function() {
							var opts=$("#user_source").html(), opts2="<option></option>"+opts;
						    $("#user_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
						    $("#user_list").select2({allowClear: true});
						});
					</script>
					<select id="user_list" name="patient_id" class="populate new_order_form" style="width:300px;"></select>
					<select id="user_source" style="display:none">
						<option value="0">Select Patient</option>
					  <?php foreach($patients as $key=>$value): ?>
					    <option value="<?php echo $value['id']; ?>"><?php echo $value['lastname'] . ", " . $value['firstname']; ?></option>
					  <?php endforeach; ?>
					</select>
					<br>Patient Code: <b id="patient_code"></b> </li>
				
					<li class="attending-doctor" style="padding:0px; margin-left:170px; color:gray;">Attending Doctor
					<script>				
						var opts=$('#attending_doctor_source').html(), opts2='<option></option>'+opts;
					    $('#attending_doctor').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
					    $('#attending_doctor').select2({allowClear: true});


					</script>
							<select id='attending_doctor' name='attending_doctor' class='populate add_returns_trigger' style='width:280px;'></select>
							<select id='attending_doctor_source' style='display:none'><option value='0'>-Select-</option>";
								<?php foreach($doctors as $key=>$value): ?>
									<option value="<?php echo $value['id'] ?>" ><?php echo $value['full_name'] ?></option>
								<?php endforeach; ?>
							</select>
					</li>
					<li class="estimated-date" style="padding:5px; margin-left:165px; color:gray;">Estimated End Date 
					<input type="text" id="estimated_date" name="estimated_date" class="textbox new_order_form" style="width:100px; color:black;">
					</li>
					
			</ul>
			<credit class="credit_style" style="margin-top: 12px; display: inline-block;">Credit: <b id="credit" style="color: black;"></b></credit>
			<ul style = "margin-top: 7px !important">
				<li><span>Age:</span> <span id="age"></span>	</li>
			</ul>

			<div class="clear"></div>
			
			<div id="left" style="text-align: center;" class="left-col">
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
			<div class="left-col" id="right" style="margin: 30px 0px 30px 0px !important;width: 46%;">
				<h1 class="bg">Medicine Tray</h1>
				<div class="box">
					<div id="medicine_list_wrapper_loader" style="display: none;"></div>
					<div id="medicine_list_wrapper"></div>
				</div>
			</div>

			<div class="clear"></div>
			<div id="left" class="left-col othr_chrgs" style="text-align: center;">
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
				<select class="myTipsy new_order_form select-med" title="Description" id="o_description" style="width:324px;"></select>
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
				<span class="span-wrapper">
				<span class="p">Php&nbsp; </span><input type="text" title="Cost" id="o_cost" style="width:107px;" class="other_charges_main  new_order_form textbox myTipsy" placeholder="0.00">
				</span>
				&nbsp; 
				<span class="span-wrapper">
				<span class="p">Quantity&nbsp; </span><input type="text" id="o_quantity" title="Quantity" style="width: 100px;" class="other_charges_main new_order_form myTipsy textbox" placeholder="0">
				</span>
				<br><br>
				<button type="button" id="add_medicine_id" class="invoice-modifier-add add_other_charges">+ Add Modifier</button>
				<br><br>
				<br><br>
				<div id="other_charges_price_wrapper" style="display: inline;" class="price">
					<span style="margin-right:10px;">CHARGES GROSS:</span>
					<span style="margin-right:95px;">Php <span id="total_other_charges_price" class="pricecolor">0.00</span></span>
				</div>
			</div>
			<div id="right" class="right-col othr_chrgs" style="margin: 30px 0px 30px 0px !important;width: 46%;">
				<h1 class="bg">Other Charges Tray</h1>
				<div class="box">
					<div id="other_charges_list"></div>
				</div>
			</div>
			<div class="clear"></div>	
			<div class="line02"></div>					
			<div>
				<ul id="notes">
					<li>Remarks</li>
					<li><textarea class="add_regimen_general_form" id="remarks" name="remarks"></textarea></li>
				</ul>
			</div>
			<div class="clear"></div>		

			<section class="clear"></section>	
			<section id="buttons">
				<button class="form_button add_submit_btn" type="button">Save & Continue</button>
				<button class="form_button cancel_order" type="button">Cancel</button>
			</section>	
</form>
</section>
<section class="clear"></section>
</section>