<script>
	$(function(){

	    reset_all();
		reset_all_topbars_menu();
		$('.billing_menu').addClass('hilited');
		$('.account_billing_menu').addClass('hidden');
		$('.account_billing_add_invoice_menu').removeClass('hidden');
		$('.rpc_form_invoice').addClass('sub-hilited');
		$("#other_charges_list").hide();
		$("#cm_list").hide();

		$("#add_invoice_form").validationEngine({scroll:false});
		$("#add_invoice_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			send_notif(o.notif_message,o.notif_title,o.notif_type);
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}

      		sessionStorage.setItem("invoice_id", o.invoice_id);
      		window.location.hash = "invoice-Alist";
			reload_content("invoice-Alist");

			// alert(o.invoice_id);
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
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
	})
</script>
	<section class="area">
		<form id="add_invoice_form" method="post" action="<?php echo url('account_billing/save_invoice') ?>" style="width: 100%;">
			<hgroup id="area-header">
				<ul class="page-title">
					<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
					<li><h1>New Invoice</h1></li>
				</ul>
				
				<ul id="controls">
					<li><a href="javascript: void(0);" onclick="$('#add_invoice_form').submit();"><img class="icon saveButton" title="Save & Continue"  src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
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

			<div style="float: right;margin: 20px 36px 0 0;">
				<div id="form02">Status&nbsp;
					<select name="status">
						<option value="New">New</option>
						<!-- <option value="Pending">Pending</option>
						<option value="Partial">Partial</option>
						<option value="Paid">Paid</option>
						<option value="Void">Void</option> -->
					</select>
				</div>
			</div>

			<div class="clear" style="padding-bottom: -10px;"></div>
			
			<div class="line03"></div>
		
			<input type="hidden" name="patient_id" value="<?php echo $patient['id'] ?>">
			<input type="hidden" name="regimen_id" id="regimen_id">
			<input type="hidden" name="version_id" id="version_id">
			<!-- <section id="left" style="border-right: dashed 1px #d3d3d3;margin:0"> -->
			<p><h1>RPC Invoice Details</h1></p>
			<div style="float:right;margin: 0px 36px 0 0;">
				<span style="color: #96a960;">Invoice Date</span> <input id="invoice_date" name="invoice_date" class="textbox validate[required]">
				&nbsp;&nbsp;&nbsp;
				<span style="color: #96a960;">Due Date</span> <input id="due_date" name="due_date" class="textbox validate[required]">
			</div>
			<div style="float:left">
				<span style="color: #96a960;">RPC Invoice ID.</span> <input type="hidden" name="invoice_id" class="textbox validate[required]" value="<?php echo $rpc_invoice_id ?>"><?php echo $rpc_invoice_id ?>
				&nbsp;&nbsp;&nbsp;
				<span style="color: #96a960;">RPC Invoice No.</span> <input type="text" name="invoice_number" class="textbox validate[required]" value="<?php echo $rpc_invoice_number ?>"></p>
				<span style="color: #96a960;">Payment Terms</span> 
				<select name="payment_terms" class="select00">
					<option value="COD">Cash On Delivery</option>
					<option value="7">7 days</option>
					<option value="15">15 days</option>
					<option value="15">30 days</option>
				</select>
			</div>
			
			<div class="clear"></div>
			
			<?php $rpc_meds_total = 0 ?>	
			<?php $rpc_counter = 0 ?>	
			<table class="table-invoice" style="width:100%;">
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
						<td><input type="hidden" name="rpc_meds[<?php echo $rpc_counter ?>][quantity]" value="<?php echo $value['quantity'] ?>"><?php echo $value['quantity'] ?></td>
						<td><input type="hidden" name="rpc_meds[<?php echo $rpc_counter ?>][price]" value="<?php echo $value['price'] ?>"><?php echo "&#8369; " . number_format($value['price'], 2, '.', ',') ?></td>
						<td><input type="hidden" name="rpc_meds[<?php echo $rpc_counter ?>][total_price]" id="rpc_per_med_total_price_<?php echo $rpc_counter ?>" value="<?php echo $value['total_price']?>"><?php echo "&#8369; " . number_format($value['total_price'], 2, '.', ',') ?></td>
					</tr>
					<?php $rpc_counter++ ?>
					<?php $rpc_meds_total = $rpc_meds_total + $value['total_price'] ?>
				<?php } ?>
				</tbody>	
			</table>
			<br>
			<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
					<tr>
						<td style="width: 173px; padding: 0;">TOTAL REGIMEN GROSS:</td>
						<td style="padding-right: 45px;">
							&#8369; <?php echo number_format($rpc_meds_total, 2, '.', ',') ?>
							<input type="hidden" id="rpc_meds_total_input" value="<?php echo $rpc_meds_total ?>">
						</td>
					</tr>
			</table>
<!--  class="regimen-cost" style="font: normal 18px arial;margin:0" -->
			<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
					<tr>
						<td style="width: 173px; padding: 0;">VAT:</td>
						<td style="padding-right: 45px;">
							<?php $vat = ($rpc_meds_total / 1.12) * 0.12 ?>
							&#8369; <?php echo number_format( $vat, 2, '.', ',') ?>
							<input type="hidden" id="rpc_meds_vat_input" value="<?php echo $vat ?>">
						</td>
					</tr>
			</table>

			<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
				<tr>
					<td style="width:330px;">TOTAL NET OF VAT:</td>
					<td style="padding-right: 45px;">
						&#8369; <?php echo number_format($rpc_meds_total / 1.12, 2, '.', ',') ?>
						<input type="hidden" id="regimen_cost_input" value="<?php echo $rpc_meds_total / 1.12 ?>">
					</td>
				</tr>
			</table>
			<div class="clear"></div>
			<div class="line03"></div>

			<div class="clear"></div>

			<ul id="form02">
				<li>Charged to</li>
				<li><input type="text" name="charged_to" class="textbox"></li>
			</ul>

			<div class="clear"></div>
			
			<ul id="form02">
				<li>Relation to Patient</li>
				<li><input type="text" name="relation_to_patient" class="textbox"></li>
			</ul>
					
			<div class="line03"></div>
			<!-- <div class="clear"></div> -->

			<p><h1>Other Charges</h1></p>
			<ul id="form02">
				<li>Description</li>
				<li>
					<!-- <textarea style="max-width: 280px;" id="o_description" class="other_charges_main"></textarea> -->
					<select id="o_description" style="width: 200px;">
						<?php foreach ($other_charges as $key => $value) { ?>
							<option value="<?php echo $value['r_centers'] ?>"><?php echo $value['r_centers'] ?></option>
						<?php } ?>
						
					</select>
				</li>
			</ul>

			<div class="clear"></div>
			

			<ul id="form02">
				<li>Cost</li>
				<li>&#8369; <input type="text" id="o_cost" style="width:107px;" class="other_charges_main" placeholder="0.00"></li>
				
			</ul>
			<div class="clear"></div>

			<ul id="form02">
				<li>Quantity</li>
				<li><input type="text" id="o_quantity" style="width: 120px;" class="other_charges_main" placeholder="0"></li>
				<li><button type="button" class="invoice-modifier-add add_other_charges">+ Add Modifier</button></li>
			</ul>
			
			<div class="clear"></div>
			
			<table class="table" id="other_charges_list">
				<th>Description</th>
				<th>Quantity</th>
				<th>Cost per Item</th>
				<th>Total Cost</th>
				<th>Actions</th>
					
			</table>
			<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
					<tr>
						<td style="width: 300px; padding: 0;">OTHER CHARGES GROSS:</td>
						<td style="padding-right: 45px;">
							

							<span class="other_charges_gross">&#8369; 0.00</span>
							<input type="hidden" id="total_o_charges" value="0">
						</td>
					</tr>
			</table>

			<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
					<tr>
						<td style="width: 300px; padding: 0;">OTHER CHARGES VAT:</td>
						<td style="padding-right: 45px;">
							<span class="other_charges_vat">&#8369; 0.00</span>
							<input type="hidden" id="total_o_charges_vat" value="0">
						</td>
					</tr>
			</table>

			<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
					<tr>
						<td style="width: 330px;">OTHER CHARGES NET OF VAT:</td>
						<td style="padding-right: 45px;">
							<span class="summary_total_other_charges">&#8369; 0.00</span>
							<input type="hidden" id="summary_total_o_charges" value="0">
						</td>
					</tr>
			</table>
			<br>
			<DIV class="clear"></DIV>
			<div class="line03"></div>
			<p><h1>Cost Modifiers</h1></p>
			<ul id="form02">
				<li>Applies to</li>
				<li>
					<select id="apply" style="width: 200px;" data-id="">
						
						<optgroup label="RPC Meds">
							<option data-type="RPC" value="All Medicines">All Medicines</option>
							<?php foreach ($rpc_meds as $key => $value) { ?>
								<option data-type="RPC" value="<?php echo $value['medicine_name'] ?>" data-id="<?php echo $value['medicine_id'] ?>" data-key="<?php echo $key ?>"><?php echo $value['medicine_name'] ?></option>
							<?php } ?>
						</optgroup>
						<!-- <optgroup label="Other Charges" class="other_charges_select">
							<option value="All Other Charges">All Other Charges</option>
							<?php #foreach ($other_charges as $key => $value) { ?>
								<option value="<?php #echo $value['r_centers'] ?>"><?php# echo $value['r_centers'] ?></option>
							<?php #} ?>
						</optgroup> -->
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
					<input type="text" id="modify_due_to" class="modifier_inputs">
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

			<div class="clear"></div>

			<table class="table" id="cm_list">
					<th>Applies to</th>
					<th>Modifier Type</th>
					<th>Modify Due To</th>
					<th>Cost</th>
					<th>Total Cost</th>
					<th>Actions</th>
					
					<!-- <tr>
						<td>All Medicines</td>
						<td>Discount</td>
						<td>Senior Citizen Card</td>
						<td>&#8369; 10.00</td>
						<td>&#8369; 10.00</td>
						<td>
							<ul class="actions">
								<li><img src="<?php echo BASE_FOLDER; ?>themes/images/edit.png" onClick="window.location.href='#';" title="Edit File"></li>
								<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
								<li><img src="<?php echo BASE_FOLDER; ?>themes/images/doc_delete.png" onClick="window.location.href='#';" title="Cancel"></li>
							</ul>
						</td>
					</tr> -->
				</table>

				<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
					<tr>
						<td style="width: 330px;">TOTAL COST MODIFIERS</td>
						<td style="padding-right: 45px;">
							<span class="total_cm">&#8369; 0.00</span>
							<input type="hidden" id="total_cm" value="0">
						</td>
					</tr> 
				</table>
				<br>
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