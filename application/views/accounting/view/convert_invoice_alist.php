<script>
	$(function(){

	    reset_all();
		reset_all_topbars_menu();
		$('.billing_menu').addClass('hilited');
		$('.account_billing_menu').addClass('hidden');
		$('.account_billing_add_invoice_menu').removeClass('hidden');
		$('.alist_form_invoice').addClass('sub-hilited');
		$("#alist_other_charges_list").hide();
		$("#alist_cm_list").hide();

		$("#add_invoice_form").validationEngine({scroll:false});
		$("#add_invoice_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			send_notif(o.notif_message,o.notif_title,o.notif_type);
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
      		window.location.hash = "sales";
			reload_content("sales");

			// alert(o.invoice_id);
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        
       		dataType : "json"
    	});

		var invoice_id = sessionStorage.getItem("invoice_id");

		$("#invoice_id").val(invoice_id);

    	var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		var start_date = $('#invoice_date').datepicker({
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
		  start_date.hide();
		  $('#due_date')[0].focus();
		}).data('datepicker');
		var expiration_date = $('#due_date').datepicker({
			format: 'yyyy-mm-dd',
		  onRender: function(date) {
		    return date.valueOf() <= start_date.date.valueOf() ? 'disabled' : '';
		  }
		}).on('changeDate', function(ev) {
		  expiration_date.hide();
		}).data('datepicker');

		$('.saveButton').tipsy({gravity: 's'});
		$('.alertUser').tipsy({gravity: 's'});
	})
</script>
	<section class="area">
		<form id="add_invoice_form" method="post" action="<?php echo url('account_billing/save_invoice_alist') ?>" style="width: 100%;">
			<hgroup id="area-header">
				<ul class="page-title">
					<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
					<li><h1>New Invoice</h1></li>
				</ul>
				
				<ul id="controls">
					<li><a href="javascript: void(0);" onclick="$('#add_invoice_form').submit();"><img class="icon saveButton" title="Save Invoice"  src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
					<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
					<li><a href="javascript:void(0);" class="cancel_convert_invoice"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
				</ul>
				
				<div class="clear"></div>
			</hgroup>
		
			<ul class="regimen-ID" >
				<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
				<li class="patient">
					<b style="font-size: 20px;"><span><?php echo $patient['patient_name'] ?></span></b>
					<br>Patient ID: <?php echo $patient['patient_code'] ?><br>Regimen ID: <a href="javascript: void(0);" class="regimen_open"><?php echo $regimen['regimen_number'] ?></a> <?php echo ($version_id ? " Version Name: " .$version['version_name'] : "")  ?>
				</li>
			</ul>

			<div class="clear" style="padding-bottom: -10px;"></div>
			
			<div class="line03"></div>
		
			<input type="hidden" name="patient_id" value="<?php echo $patient['id'] ?>">
			<input type="hidden" name="invoice_id" id="invoice_id">
			<!-- <input type="hidden" name="invoice_id" id="invoice_id" value="9"> -->
			<input type="hidden" name="regimen_id" id="regimen_id">
			<input type="hidden" name="version_id" id="version_id">
			<p><h1>A-List Invoice Details</h1></p>
			<div style="float:left">
				<span style="color: #96a960;">A-List Invoice ID.</span> <input type="text" name="alist_invoice_id" class="textbox validate[required]" value="">
				&nbsp;&nbsp;&nbsp;
				<span style="color: #96a960;">A-List Invoice No.</span> <input type="text" name="alist_invoice_number" class="textbox validate[required]" value=""><p></p>
			</div>
			<div class="clear"></div>
			<?php $alist_total = 0 ?>
			<?php $counter = 0 ?>
			<table class="table-invoice" style="width:100%;">
				<th>LIST OF A-LIST MEDS</th>
				<th style="padding-right: 20px;">Quantity</th>
				<th style="padding-right: 20px;">Cost/item</th>
				<th style="padding-right: 20px;width:22%;">Cost</th>
				<?php foreach ($alist_meds as $key => $value) { ?>
					<tr>
						<td><input type="hidden" name="alist_med[<?php echo $counter ?>][id]" value="<?php echo $value['medicine_id'] ?>"><?php echo $value['medicine_name'] . " (" . $value['dosage'] . " " . $value['dosage_type'] . ")" ?></td>
						<td><input type="hidden" style="width: 50px;" class="textbox" onchange="calcualateAlistMedicine(<?php echo $counter ?>)" name="alist_med[<?php echo $counter ?>][quantity]" id="alist_quantity_<?php echo $counter ?>" value="<?php echo $value['quantity'] ?>"><?php echo $value['quantity'] ?></td>
						<td>&#8369; <input type="text" style="width: 50px;" onchange="calcualateAlistMedicine(<?php echo $counter ?>)" title="Kindly note, price change will be reflected in inventory." class="textbox alertUser" name="alist_med[<?php echo $counter ?>][price]" id="alist_price_<?php echo $counter ?>" value="<?php echo $value['price'] ?>"></td>
						<td>&#8369; <input type="hidden" id="alist_total_price_input_<?php echo $counter ?>" name="alist_med[<?php echo $counter ?>][total_price]" value="<?php echo $value['total_price'] ?>"><span id="alist_total_price_<?php echo $counter ?>"><?php echo number_format($value['total_price'],2,'.',',') ?></span></td>
					</tr>
					<?php $counter++; ?>
					<?php $alist_total = $alist_total + $value['total_price'] ?>
				<?php } ?>
			</table>
			<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
					<tr>
						<td style="width: 173px; padding: 0;">TOTAL A-LIST GROSS:</td>
						<td style="padding-right: 45px;">
							&#8369; <span id="alist_meds_total_span"><?php echo number_format($alist_total, 2, '.', ',') ?></span>
							<input type="hidden" id="alist_meds_total_input" value="<?php echo $alist_total ?>">
						</td>
					</tr>
			</table>
<!--  class="regimen-cost" style="font: normal 18px arial;margin:0" -->
			<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
					<tr>
						<td style="width: 173px; padding: 0;">VAT:</td>
						<td style="padding-right: 45px;">
							<?php $vat = ($alist_total / 1.12) * 0.12 ?>
							&#8369; <span id="alist_meds_vat_span"><?php echo number_format( $vat, 2, '.', ',') ?></span>
							<input type="hidden" id="alist_meds_vat_input" value="<?php echo $vat ?>">
						</td>
					</tr>
			</table>

			<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
					<tr>
						<td>TOTAL NET OF VAT:</td>
						<td style="padding-right: 45px;">
							&#8369; <span id="alist_total_span"><?php echo number_format($alist_total / 1.12, 2, '.', ',') ?></span>
							<input type="hidden" id="alist_total_input" value="<?php echo $alist_total / 1.12 ?>">
						</td>
					</tr>
			</table>
			<!-- </section> -->
			
			<div class="clear"></div>
			<div class="line03"></div>
			<!-- <div class="clear"></div> -->

			<p><h1>Other Charges</h1></p>
			<ul id="form02">
				<li>Description</li>
				<li>
					<!-- <textarea style="max-width: 280px;" id="o_description" class="other_charges_main"></textarea> -->
					<select id="alist_o_description" style="width: 200px;">
						<?php foreach ($other_charges as $key => $value) { ?>
							<option value="<?php echo $value['r_centers'] ?>"><?php echo $value['r_centers'] ?></option>
						<?php } ?>
						
					</select>
				</li>
			</ul>

			<div class="clear"></div>
			

			<ul id="form02">
				<li>Cost</li>
				<li>&#8369; <input type="text" id="alist_o_cost" style="width:107px;" class="alist_other_charges_main" placeholder="0.00"></li>
				
			</ul>
			<div class="clear"></div>

			<ul id="form02">
				<li>Quantity</li>
				<li><input type="text" id="alist_o_quantity" style="width: 120px;" class="alist_other_charges_main" placeholder="0"></li>
				<li><button type="button" class="invoice-modifier-add alist_add_other_charges">+ Add Modifier</button></li>
			</ul>
			
			<div class="clear"></div>
			
			<table class="table" id="alist_other_charges_list">
				<th>Description</th>
				<th>Quantity</th>
				<th>Cost per Item</th>
				<th>Total Cost</th>
				<th>Actions</th>
					
			</table>
					<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
							<tr>
								<td style="width: 173px; padding: 0;">Total Other Charges:</td>
								<td style="padding-right: 45px;">
									

									<span class="alist_other_charges">&#8369; 0.00</span>
									<input type="hidden" id="alist_total_o_charges" value="0">
								</td>
							</tr>
					</table>

					<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
							<tr>
								<td style="width: 173px; padding: 0;">Total Other Charges VAT:</td>
								<td style="padding-right: 45px;">
									<span class="alist_other_charges_vat">&#8369; 0.00</span>
									<input type="hidden" id="alist_total_o_charges_vat" value="0">
								</td>
							</tr>
					</table>

					<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
							<tr>
								<td style="width: 330px;">Summary Total Other Charges:</td>
								<td style="padding-right: 45px;">
									<span class="alist_summary_total_other_charges">&#8369; 0.00</span>
									<input type="hidden" id="alist_summary_total_o_charges" value="0">
								</td>
							</tr>
					</table>
					<br>
					<div class="clear"></div>
					<div class="line03"></div>
					<p><h1>Cost Modifiers</h1></p>
					<ul id="form02">
						<li>Applies to</li>
						<li>
							<select id="alist_apply" style="width: 200px;" data-id="">
								
								<optgroup label="A-List Meds">
									<option data-type="A-List" value="All Medicines">All Medicines</option>
									<?php foreach ($alist_meds as $key => $value) { ?>
										<option data-type="A-List" value="<?php echo $value['medicine_name'] ?>" data-id="<?php echo $value['medicine_id'] ?>" data-key="<?php echo $key ?>"><?php echo $value['medicine_name'] ?></option>
									<?php } ?>
								</optgroup>
							</select>
						</li>
					</ul>

					<div class="clear"></div>

					<ul id="form02">
						<li>Modifier Type</li>
						<li>
							<input type="radio" name="alist_modifier_type" value="Mark up"><label for="r1"><span></span>Mark up</label>
							<input type="radio" name="alist_modifier_type" value="Discount"> <label for="r2"><span></span>Discount</label>
						</li>
					</ul>

					<div class="clear"></div>

					<ul id="form02">
						<li>Modifier Due To</li>
						<li>
							<input type="text" id="alist_modify_due_to" class="alist_modifier_inputs">
						</li>
					</ul>

					<div class="clear"></div>
					
					<ul id="form02">
						<li>Cost</li>
						<li>
							<select id="alist_cost_type" style="width: 60px;">
								<option value="%">%</option>
								<option value="php">&#8369;</option>
							</select>
							<input type="text" id="alist_cost_modifier" style="width: 120px;" class="alist_modifier_inputs">
						</li>
						<li><button type="button" class="invoice-modifier-add alist_cm_add_modifier">+ Add Modifier</button></li>
					</ul>

					<div class="clear"></div>

					<table class="table" id="alist_cm_list">
						<th>Applies to</th>
						<th>Modifier Type</th>
						<th>Modify Due To</th>
						<th>Cost</th>
						<th>Total Cost</th>
						<th>Actions</th>
					</table>

					<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
						<tr>
							<td style="width: 330px;">Total Cost Modifiers</td>
							<td style="padding-right: 45px;">
								<span class="alist_total_cm">&#8369; 0.00</span>
								<input type="hidden" id="alist_total_cm" value="0">
							</td>
						</tr> 
					</table>
					<br>
					<div class="clear"></div>
				<div class="line03"></div>
					<p><h1>Invoice Summary</h1></p>
					<table class="table-total02">
						<tr>
							<td>A-List Cost</td>
							<td><input name="total_alist_cost" id="total_alist_cost" type="hidden" value="0"><span id="alist_cost"></span></td>
						</tr>
						<tr>
							<td>Other Charges</td>
							<td><input name="total_alist_other_charges" id="total_alist_other_charges" value="0" type="hidden"><span class="alist_other_charges_span">&#8369; 0.00</span></td>
						</tr>
						<tr>
							<td>Cost Modifiers</td>
							<td><input name="total_alist_cost_modifier" id="total_alist_cost_modifier" value="0" type="hidden"><span id="alist_cost_modifiers">&#8369; 0.00</span></td>
						</tr>
						<tr>
							<td style="background: #FFE8AE;">TOTAL INVOICE AMOUNT</td>
							<td><input name="alist_total_net_sales_vat" id="alist_total_net_sales_vat" value="0" type="hidden"><span id="alist_total_invoice_amount"></span></td>
						</tr>
						<tr>
							<td>Invoice Amount VAT</td>
							<td><input name="alist_net_sales_vat" id="alist_net_sales_vat" type="hidden" value="0"><span id="alist_invoice_amount_vat">&#8369; 0.00</span></td>
						</tr>
						<tr>
							<td style="color: #fff; background-color: #f4814b;">INVOICE NET OF VAT</td>
							<td><input name="alist_net_sales" id="alist_net_sales" type="hidden" value="0"><span id="alist_invoice_amount">&#8369; 0.00</span></td>
						</tr>
					</table>
				</form>
				<section id="buttons">
					<button type="button" class="form_button" onClick="$('#add_invoice_form').submit();">Save Invoice</button>
					<button type="button" class="form_button cancel_convert_invoice">Cancel</button>
				</section>
	<script>
		$(function(){

			calculateOverallTotalALIST();
			var regimen_id = sessionStorage.getItem("regimen_id");
			var version_id = sessionStorage.getItem("version_id");

			$("#regimen_id").val(regimen_id);
			$("#version_id").val(version_id);
			
			/*sessionStorage.removeItem("regimen_id");
			sessionStorage.removeItem("version_id");*/

			$("#alist_total_o_charges").on('change', function(){
				calculateOverallTotalALIST();
				
			});

			$(".alist_add_other_charges").on('click', function(){
				addNewOtherChargesALIST();
			});

			$(".alist_cm_add_modifier").on('click', function(){
				addNewCMALIST();
			});

			$(".cancel_convert_invoice").on('click', function(){
				var regimen_id = $("#regimen_id").val();
				var version_id = $("#version_id").val();
				sessionStorage.setItem("regimen_id", regimen_id);
				sessionStorage.setItem("version_id", version_id);
				
				window.location.href="regimen";
			});

			$(".regimen_open").on('click', function(){
				var regimen_id = $("#regimen_id").val();
				var version_id = $("#version_id").val();
				loadRegimenPopUP(regimen_id, version_id);
			});

			check();
		});

		function calculateTotalAlistMedicine(){
			var count = "<?php echo $counter ?>";
			var total = 0;
			for (var i = 0; i < count; i++) {
				total = total + parseFloat($("#alist_total_price_input_" + i).val());
			};
			var net = (total / 1.12);
			var vat = net * 0.12;

			$("#alist_meds_total_span").html(addCommas(total.toFixed(2)));
			$("#alist_meds_total_input").val(total.toFixed(2));

			$("#alist_meds_vat_span").html(addCommas(vat.toFixed(2)));
			$("#alist_meds_vat_input").val(vat.toFixed(2));

			$("#alist_total_span").html(addCommas(net.toFixed(2)));
			$("#alist_total_input").val(net.toFixed(2));

			calculateOverallTotalALIST();
		}

		function check(){
			var regimen_id = $("#regimen_id").val();
			if(regimen_id == 0 || regimen_id == null){
				window.location="regimen";
			}
		}
	</script>
	</section>						
</section>


<div class="modal fade" id="view_regimen_modal_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>