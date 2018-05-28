<script>
	$(function(){

	    reset_all();
		reset_all_topbars_menu();
		//$("#cm_list").hide();
		$('.billing_menu').addClass('hilited');
		$('.account_billing_menu').addClass('hidden');
		$('.account_billing_add_invoice_menu').removeClass('hidden');
		$('.rpc_form_invoice').addClass('sub-hilited');

		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}
		var meds_count = "<?php echo count($rpc_meds) ?>";
		
		$('#table_invoice').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": ( (meds_count > 10) ? true : false),
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,     
        "bScrollCollapse": false
    	});

    	$('#other-charges-list').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": ( (meds_count > 10) ? true : false),
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,     
        "bScrollCollapse": false
   		});

   		$('#cm_list').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": ( (meds_count > 10) ? true : false),
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,     
        "bScrollCollapse": false
   		});

		$("#add_invoice_form").validationEngine({scroll:false});
		$("#add_invoice_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
      			IS_EDIT_ACCOUNTING_BILLING_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      			$.unblockUI();
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}

      		window.location.hash = "sales";
			reload_content("sales");

			// alert(o.invoice_id);
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        beforeSubmit : function(evt){
         	$.blockUI();
         },
        
       		dataType : "json"
    	});


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

		var other_charges_data = <?php echo json_encode($other_charges_data) ?>;

		$.each(other_charges_data, function(index, value){
			otherCounter2++;
			addToDropDownCM(value.description,"other_charges_cost_data_" + value.id);
		});

		var cost_modifier_data = <?php echo json_encode($cost_modifier_data) ?>;

		$.each(cost_modifier_data, function(index, value){
			CMCounter2++;
		});

	})
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
		<form id="add_invoice_form" method="post" action="<?php echo url('account_billing/edit_invoice_process') ?>" style="width: 100%;">
			<hgroup id="area-header">
				<ul class="page-title">
					<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
					<li><h1>Edit Invoice</h1></li>
				</ul>
				
				<ul id="controls">
					<li><a href="javascript: void(0);" onclick="$('#add_invoice_form').submit();"><img class="icon saveButton" title="Save & Continue"  src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
					<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
					<li><a href="javascript:void(0);" class="cancel_convert_invoice"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
				</ul>
				
				<div class="clear"></div>
			</hgroup>
		
			<ul class="regimen-ID" >
				<li><img class="photoID" style="margin-top: 17px !important;" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
				<li class="patient">
					<b style="font-size: 20px;"><span><?php echo $patient['patient_name'] ?></span></b>
					<br>Patient ID: <?php echo $patient['patient_code'] ?>
					<br>Order No: <?php echo $order['order_no'] ?>
					<br>Doctor Assigned: 
					<script>
						var opts=$('#doctor_assigned_source').html(), opts2='<option></option>'+opts;
					    $('#doctor_assigned').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
					    $('#doctor_assigned').select2({allowClear: true});
					</script>
					<select id='doctor_assigned' name='doctor_assigned' class='populate add_returns_trigger' style='width:220px;'></select>
					<select id='doctor_assigned_source' class='validate[required]' style='display:none'><option value='0'>-Select-</option>";
						<?php foreach($doctor as $key=>$value): ?>
							<option value="<?php echo $value['id'] ?>" <?php echo ($order['doc_attending_id'] == $value['id'] ? "selected" : "") ?>><?php echo $value['full_name'] ?></option>
						<?php endforeach; ?>
					</select>
					<!-- <br>Regimen ID: <a href="javascript: void(0);" class="regimen_open"><?php echo $regimen['regimen_number'] ?></a> <?php echo ($version_id ? " Version Name: " .$version['version_name'] : "")  ?> -->
				</li>
			</ul>

			<div style="float: right;margin: 20px 36px 0 0;">
				<div id="form02">Status&nbsp;
					<select name="status">
						<option value="<?php echo $invoice['status'];?>"><?php echo $invoice['status'];?></option>
					</select>
				</div>
			</div>
			<div class="clear" style="padding-bottom: -10px;"></div>
			<div class="line03"></div>
		
			<input type="hidden" name="id" value="<?php echo $invoice['id'] ?>">
			<input type="hidden" name="patient_id" value="<?php echo $invoice['patient_id'] ?>">
			
			
			
			<?php $rpc_meds_total = 0 ?>	
			<?php $rpc_counter = 0 ?>	
			
			<p><h1>Medicine Details</h1></p>
			<table class="datatable table" id="table_invoice" >
				<thead>
					<th>LIST OF RPC MEDS</th>
					<th style="padding-right: 20px;">Quantity</th>
					<th style="padding-right: 20px;">Cost/item</th>
					<th style="padding-right: 20px;width:22%;">Cost</th>
				</thead>
				<tbody style="min-height:150px;">
				<?php foreach ($rpc_meds as $key => $value) { ?>
					<tr>
						<td><input type="hidden" name="rpc_meds[<?php echo $rpc_counter ?>][medicine_id]" value="<?php echo $value['medicine_id'] ?>"><?php echo $value['medicine_name'] . " (" . $value['dosage'] . " " . $value['dosage_type'] . ")" ?></td>
						<td><input type="hidden" name="rpc_meds[<?php echo $rpc_counter ?>][quantity]" value="<?php echo $value['quantity'] ?>"><?php echo $value['quantity'] ." ". $value['quantity_type'] ?></td>
						<td><input type="hidden" name="rpc_meds[<?php echo $rpc_counter ?>][price]" value="<?php echo $value['price'] ?>"><?php echo "&#8369; " . number_format($value['price'], 2, '.', ',') ?></td>
						<td><input type="hidden" name="rpc_meds[<?php echo $rpc_counter ?>][total_price]" id="rpc_per_med_total_price_<?php echo $rpc_counter ?>" value="<?php echo $value['total_price']?>"><?php echo "&#8369; " . number_format($value['total_price'], 2, '.', ',') ?></td>
					</tr>
					<?php $rpc_counter++ ?>
					<?php $rpc_meds_total = $rpc_meds_total + $value['total_price'] ?>
				<?php } ?>
				</tbody>	
			</table>
			<p class="medgross">Total Regimen Gross: <span> &#8369; <?php echo number_format($rpc_meds_total, 2, '.', ',') ?></span></p>
			<input type="hidden" id="rpc_meds_total_input" value="<?php echo $rpc_meds_total ?>">
			<div class="clear"></div>
			<p class="medgross">VAT: <span> &#8369; <?php echo number_format( $vat, 2, '.', ',') ?></span></p>
			<input type="hidden" id="rpc_meds_vat_input" value="<?php echo $vat ?>">
			<div class="clear"></div>
			<p class="medgross">TOTAL NET OF VAT: <span> &#8369; <?php echo number_format($rpc_meds_total / 1.12, 2, '.', ',') ?></span></p>
			<input type="hidden" id="regimen_cost_input" value="<?php echo $rpc_meds_total / 1.12 ?>">
			<div class="clear"></div>
			
			
			<div class="line03"></div>
			<?php 
				$now 		= time(); // or your date as well
			    $your_date 	= strtotime($invoice['invoice_date']);
			    $datediff 	= $now - $your_date;
				$expiry 	= floor($datediff/(60*60*24));

				$datetime1 = new DateTime($invoice['invoice_date']);
				$datetime2 = new DateTime($today_date);
				$interval = $datetime1->diff($datetime2);
				$difference = $interval->format('%a');
			?>
			
			<p><h1>Other Charges</h1></p>
			<table class="table" id="other-charges-list">
				<thead>
					<th>Description</th>
					<th>Quantity</th>
					<th>Cost per Item</th>
					<th>Total Cost</th>
					<!-- <th></th> -->
				</thead>
				<tbody>
					<?php $rpc_other_charges = 0 ?>

					<?php foreach ($other_charges_data as $key => $value) { ?>
					<?php $description = Other_Charges::findById(array("id" =>$value['description_id']));?>
					<tr id="old_other_charges_row_<?php echo $value['id'] ?>">
						<td><?php echo  $description['r_centers'] ?></td>
						<td><?php echo $value['quantity'] ?></td>
						<td>&#8369; <?php echo number_format($value['cost_per_item'], 2, '.', ',') ?></td>
						<td><input type="hidden" value="<?php echo $value['cost'] ?>" id="other_charges_cost_data_<?php echo $value['id'] ?>">&#8369; <?php echo number_format($value['cost'], 2, '.', ',') ?></td>
					</tr>
					<?php $rpc_other_charges += $value['cost'] ?>
					<?php } ?>
				</tbody>
			</table>
			<p class="medgross">OTHER CHARGES GROSS: <span> &#8369; <?php echo number_format($rpc_other_charges, 2, '.', ',') ?></span></p>
			<input type="hidden" id="total_o_charges" value="<?php echo $rpc_other_charges ?>">
			<div class="clear"></div>
			<p class="medgross">OTHER CHARGES VAT: 
			<?php $oc_vat = ($rpc_other_charges / 1.12) * 0.12 ?>
			<span> &#8369; <?php echo number_format($oc_vat, 2, '.', ',') ?></span></p>
			<input type="hidden" id="total_o_charges_vat" value="<?php echo $oc_vat ?>">
			<div class="clear"></div>
			<p class="medgross">OTHER CHARGES NET OF VAT: <span> &#8369; <?php echo number_format($rpc_other_charges / 1.12, 2, '.', ',') ?></span></p>
			<input type="hidden" id="summary_total_o_charges" value="<?php echo $rpc_other_charges / 1.12 ?>">

			<div class="clear"></div>
			<div class="line03"></div>
			
			<!-- Cost Modifier -->
			
			<p><h1>Cost Modifiers</h1></p>
	<?php if ($difference <= 0) {?>
		<?php if($expiry >= 0){ ?>	
			<ul id="form02">
				<li>Applies to</li>
				<li>
					<select id="apply2" style="width: 200px;" data-id="">
						<optgroup label="RPC Meds">
							<option data-type="RPC" value="All Medicines" data-type="1">All Medicines</option>
							<?php foreach ($rpc_meds as $key => $value) { ?>
								<option data-type="RPC" value="<?php echo $value['medicine_name'] ?>" data-id="<?php echo $value['medicine_id'] ?>" data-key="<?php echo $key ?>"><?php echo $value['medicine_name'] ?></option>
							<?php } ?>
						</optgroup>
						<optgroup label="Other Charges" class="other_charges_select">
							<option data-price="<?php echo $others_total_price ?>" value="All Other Charges" data-type="2">All Other Charges</option>
							<?php foreach($order_others as $key => $value){ ?>
							<?php $description = Other_Charges::findById(array("id" =>$value['desc_id']));?>
								<option data-price="<?php echo $value['total_cost'] ?>" data-id="<?php echo $description['id'] ?>" value="<?php echo $description['r_centers']?>"><?php echo $description['r_centers'] ?></option>
							<?php } ?>
						</optgroup> 
					</select>
				</li>
			</ul>

			<div class="clear"></div>

			<ul id="form02">
				<li>Modifier Type</li>
				<li>
					<input type="radio" name="modifier_type" value="Mark up"><label for="r1"><span></span>Mark up</label>
					<input type="radio" name="modifier_type" value="Discount"> <label for="r2"><span></span>Discount</label>
				</li>
			</ul>

			<div class="clear"></div>

			<ul id="form02">
				<li>Modifier Due To</li>
				<li>
					<select id="modify_due_to" style="width: 200px;">
						<?php foreach($cost_modifier as $key => $value){ ?>
							<option value="<?php echo $value['cost_modifier'] ?>" ><?php echo $value['cost_modifier'] ?></option>
						<?php } ?>
						<!-- <option value="Senior Discount">Senior Discount</option>
						<option value="Bottle Discount">Bottle Discount</option>
						<option value="Employee Discount">Employee Discount</option>
						<option value="Special Discount">Special Discount</option>
						<option value="RPC Marketing">RPC Marketing</option> -->
					</select>
					<!-- <input type="text" id="modify_due_to" class="modifier_inputs"> -->
				</li>
			</ul>

			<div class="clear"></div>
			
			<ul id="form02">
				<li>Cost</li>
				<li>
					<select id="cost_type" style="width: 60px;">
						<option value="%">%</option>
						<option value="php">&#8369;</option>
					</select>
					<input type="text" id="cost_modifier" style="width: 120px;" class="modifier_inputs">
				</li>
				<li><button type="button" class="invoice-modifier-add cm_add_modifier">+ Add Modifier</button></li>
			</ul>
		<?php } ?>
					<?php } ?>
			<div class="clear"></div>

			<table class="table" id="cm_list">
				<thead>	
					<th>Applies to</th>
					<th>Modifier Type</th>
					<th>Modify Due To</th>
					<th>Cost</th>
					<th>Total Cost</th>
					<th></th>
				</thead>
				<tbody>
					<?php $rpc_cost_mod = 0 ?>
					<?php foreach ($cost_modifier_data as $key => $value) { ?>
					<tr id="old_cm_row_<?php echo $value['id'] ?>">
						<td><?php echo $value['applies_to'] ?></td>
						<td><?php echo $value['modifier_type'] ?></td>
						<td><?php echo $value['modify_due_to'] ?></td>
						<td><?php echo $value['cost_modifier'] . " " . $value['cost_type'] ?></td>
						<td>&#8369; <?php echo number_format($value['total_cost'], 2, '.', ',') ?> <input type="hidden" value="<?php echo $value['total_cost'] ?>" id="old_cm_cost_<?php echo $value['id'] ?>"></td>
						<td><a href="javascript: void(0);" onclick="javascript: deleteCMRow(<?php echo $value['id'] ?>)"><img src="<?php echo BASE_FOLDER ?>themes/images/doc_delete.png"></a></td>
					</tr>
					<?php $rpc_cost_mod += $value['total_cost'] ?>
					<?php } ?>
				</tbody>

			</table>
			<p class="medgross">TOTAL COST MODIFIERS <span class="total_cm" > &#8369; <?php echo number_format($rpc_cost_mod, 2, '.', ',') ?></span></p>
			<input type="hidden" id="total_cm" value="<?php echo $rpc_cost_mod ?>">
			<div class="clear"></div>

			<div class="line03"></div>
			<p><h1>RPC Invoice Details</h1></p>
			<ul id="form02">
				<li>RPC Invoice No.</li>
				<li><input type="text" name="invoice_number" class="textbox validate[required]" value="<?php echo $invoice['invoice_num'] ?>"></li>
			</ul>
			<div class="clear"></div>

			<ul id="form02">
				<li>Invoice Date</li>
				<li><input id="invoice_date" name="invoice_date" class="textbox validate[required]" value="<?php echo $invoice['invoice_date'] ?>"></li>
			</ul>
			<div class="clear"></div>

<!-- 			<ul id="form02">
				<li>Due Date</li>
				<li><input id="due_date" name="due_date" class="textbox validate[required]" value="<?php echo $invoice['due_date'] ?>"></li>
			</ul>
			<div class="clear"></div> -->

			<ul id="form02">
				<li>Payment Terms</li>
				<li> 
					<select name="payment_terms" class="select00">
						<option value="COD">Cash On Delivery</option>
						<option value="7">7 days</option>
						<option value="15">15 days</option>
						<option value="15">30 days</option>
					</select>
				</li>
			</ul>
			<div class="clear"></div>
			<ul id="form02">
				<li>Charged to</li>
				<li><input type="text" name="charged_to" class="textbox" value="<?php echo $invoice['charge_to'] ?>"></li>
			</ul>

			<div class="clear"></div>
			
			<ul id="form02">
				<li>Relation to Patient</li>
				<li><input type="text" name="relation_to_patient" class="textbox" value="<?php echo $invoice['relation_to_patient'] ?>"></li>
			</ul>
			<div class="clear"></div>		
			<div class="line03"></div>
			
			

			<p><h1>Invoice Summary</h1></p>
			<table class="table-total02">
				<tr>
					<td>Regimen Cost</td>
					<td><input name="total_regimen_cost" id="total_regimen_cost" type="hidden"><span id="regimen_cost"></span></td>
				</tr>
				<tr>
					<td>Other Charges</td>
					<td><input name="total_other_charges" id="total_other_charges" type="hidden"><span class="other_charges">&#8369; 0.00</span></td>
				</tr>
				<tr>
					<td>Cost Modifiers</td>
					<td><input name="total_cost_modifier" id="total_cost_modifier" type="hidden"><span id="cost_modifiers">&#8369; 0.00</span></td>
				</tr>
				<tr>
					<td style="background: #FFE8AE;">TOTAL INVOICE AMOUNT</td><!-- WITH VAT -->
					<td><input name="total_net_sales_vat" id="total_net_sales_vat" type="hidden"><span id="total_invoice_amount">&#8369; 0.00</span></td>
				</tr>
				<tr>
					<td>VAT</td>
					<td><input name="net_sales_vat" id="net_sales_vat" type="hidden"><span id="invoice_amount_vat">&#8369; 0.00</span></td>
				</tr>
				<tr>
					<td style="color: #fff; background-color: #f4814b;">INVOICE NET OF VAT</td> <!-- WITHOUT VAT -->
					<td><input name="net_sales" id="net_sales" type="hidden"><span id="invoice_amount">&#8369; 0.00</span></td>
				</tr>
			</table>
		</form>
		<section id="buttons">
			<button type="button" class="form_button" onClick="$('#add_invoice_form').submit();">Save and Continue</button>
			<button type="button" class="form_button cancel_convert_invoice">Cancel</button>
		</section>
	<script>
		$(function(){

			calculateOverallTotal();
			var regimen_id = sessionStorage.getItem("regimen_id");
			var version_id = sessionStorage.getItem("version_id");

			$("#regimen_id").val(regimen_id);
			$("#version_id").val(version_id);
			
			/*sessionStorage.removeItem("regimen_id");
			sessionStorage.removeItem("version_id");*/

			$("#total_o_charges").on('change', function(){
				calculateOverallTotal();
				
			});

			$(".add_other_charges").on('click', function(){
				addNewOtherCharges();
			});

			$(".cm_add_modifier").on('click', function(){
				addNewCM();
				$(".is_medicine").hide();
			});

			$(".cancel_convert_invoice").on('click', function(){
				loadPreviousForm();
				/*if(IS_EDIT_ACCOUNTING_BILLING_CHANGE){
					alert("You have deleted information from database. Please press Save to continue.");
				}*/
			});
		});

	</script>
	</section>						
</section>


<div class="modal fade" id="view_regimen_modal_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>