<script>
	$(function(){

		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}
		
	    reset_all();
		reset_all_topbars_menu();
		$('.billing_menu').addClass('hilited');
		$("#other_charges_list").hide();
		$("#cm_list").hide();

	})
</script>
	<section class="area">
		<hgroup id="area-header">
			<ul class="page-title">
				<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
				<li><h1>Invoice</h1></li>
			</ul>
			
			<ul id="controls">
				<li><a href="javascript: void(0);" onclick="$('#add_invoice_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
				<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
				<li><a href="javascript:void(0);" class="cancel_convert_invoice"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
			</ul>
			
			<div class="clear"></div>
		</hgroup>
	
		<ul class="regimen-ID" >
			<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/header-account.png') ?>"></li>
			<li class="patient">
				<b style="font-size: 20px;"><span><?php echo $patient['patient_name'] ?></span></b>
				<br>Patient ID: <?php echo $patient['patient_code'] ?><br>Order Number: <?php echo $order['order_no'] ?>
			</li>
		</ul>

		<div style="float: right;margin: 20px 36px 0 0;">
			Invoice Date&nbsp;<input id="invoice_date" name="invoice_date" class="textbox validate[required]">&nbsp;
			Due Date&nbsp;<input id="due_date" name="due_date" class="textbox validate[required]">&nbsp;
			<div id="form02">Status&nbsp;
				<select name="status">
					<option value="New">New</option>
					<option value="Pending">Pending</option>
					<option value="Partial">Partial</option>
					<option value="Paid">Paid</option>
				</select>
			</div>
		</div>

		<div class="clear" style="padding-bottom: -10px;"></div>
		
		<div class="line03"></div>
	
		<input type="hidden" name="patient_id" value="<?php echo $patient['id'] ?>">
		<input type="hidden" name="regimen_id" id="regimen_id">
		<input type="hidden" name="version_id" id="version_id">
		<section id="left" style="border-right: dashed 1px #d3d3d3;margin:0">
			<p><h1>RPC Invoice Details</h1><br><br>
			<span style="color: #96a960;">RPC Invoice ID.</span> <input type="text" name="invoice_id" class="textbox validate[required]" value="<?php echo $rpc_invoice_id ?>">
			<span style="color: #96a960;">RPC Invoice No.</span> <input type="text" name="invoice_number" class="textbox validate[required]" value="<?php echo $rpc_invoice_number ?>"></p>
			<br/>
			<?php $rpc_meds_total = 0 ?>	
			<?php $rpc_counter = 0 ?>	
			<?php if($rpc_meds) { ?>
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
						<td style="width: 173px; padding: 0;">TOTAL RPC MEDS:</td>
						<td style="padding-right: 45px;">
							&#8369; <?php echo number_format($rpc_meds_total, 2, '.', ',') ?>
							<input type="hidden" id="rpc_meds_total_input" value="<?php echo $rpc_meds_total ?>">
						</td>
					</tr>
			</table>
			<table class="regimen-cost" style="font: normal 14px arial;margin:0">	
					<tr>
						<td style="width: 173px; padding: 0;">TOTAL RPC VAT:</td>
						<td style="padding-right: 45px;">
							<?php $vat = $rpc_meds_total - ($rpc_meds_total / 1.12); ?>
							&#8369; <?php echo number_format( $vat, 2, '.', ',') ?>
							<input type="hidden" id="rpc_meds_vat_input" value="<?php echo $vat ?>">
						</td>
					</tr>
			</table>
			<?php } ?>

			<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
				<tr>
					<td style="width:330px;">REGIMEN COST SUMMARY:</td>
					<td style="padding-right: 45px;">
						&#8369; <?php echo number_format($rpc_meds_total + $vat, 2, '.', ',') ?>
						<input type="hidden" id="regimen_cost_input" value="<?php echo $rpc_meds_total + $vat ?>">
					</td>
				</tr>
			</table>
		</section>
		<section id="right" style="width: 48%;margin:0 0 0 10px;">
			<p><h1>A-List Invoice Details</h1><br><br>
			<span style="color: #96a960;">A-List Invoice ID.</span> <input type="text" name="alist_invoice_id" class="textbox validate[required]" value="">
			<span style="color: #96a960;">A-List Invoice No.</span> <input type="text" name="alist_invoice_number" class="textbox validate[required]" value=""></p>
			<br/>
			<?php $alist_total = 0 ?>
			<?php $counter = 0 ?>
			<?php if($alist_meds) { ?>
			<table class="table-invoice" style="width:100%;">
				<th>LIST OF A-LIST MEDS</th>
				<th style="padding-right: 20px;">Quantity</th>
				<th style="padding-right: 20px;">Cost/item</th>
				<th style="padding-right: 20px;width:22%;">Cost</th>
				<?php foreach ($alist_meds as $key => $value) { ?>
					<tr>
						<td><input type="hidden" name="alist_med[<?php echo $counter ?>][id]" value="<?php echo $value['medicine_id'] ?>"><?php echo $value['medicine_name'] . " (" . $value['dosage'] . " " . $value['dosage_type'] . ")" ?></td>
						<td><input type="hidden" style="width: 50px;" class="textbox" onchange="calcualateAlistMedicine(<?php echo $counter ?>)" name="alist_med[<?php echo $counter ?>][quantity]" id="alist_quantity_<?php echo $counter ?>" value="<?php echo $value['quantity'] ?>"><?php echo $value['quantity'] ?></td>
						<td>&#8369; <input type="text" style="width: 50px;" onchange="calcualateAlistMedicine(<?php echo $counter ?>)" class="textbox" name="alist_med[<?php echo $counter ?>][price]" id="alist_price_<?php echo $counter ?>" value="<?php echo $value['price'] ?>"></td>
						<td>&#8369; <input type="hidden" id="alist_total_price_input_<?php echo $counter ?>" name="alist_med[<?php echo $counter ?>][total_price]" value="<?php echo $value['total_price'] ?>"><span id="alist_total_price_<?php echo $counter ?>"><?php echo $value['total_price'] ?></span></td>
					</tr>
					<?php $counter++; ?>
					<?php $alist_total = $alist_total + $value['total_price'] ?>
				<?php } ?>
			</table>
			
			<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
					<tr>
						<td>TOTAL A-LIST MEDS:</td>
						<td style="padding-right: 45px;">
							&#8369; <span id="alist_total_span"><?php echo number_format($alist_total, 2, '.', ',') ?></span>
							<input type="hidden" id="alist_total_input" value="<?php echo $alist_total ?>">
						</td>
					</tr>
			</table>
			<?php } ?>
		</section>
		
		
		<div class="line03"></div>
		<div class="clear"></div>

		<ul id="form02">
			<li>Charged to</li>
			<li><input type="text" name="charged_to" class="textbox validate[required]"></li>
		</ul>

		<div class="clear"></div>
		
		<ul id="form02">
			<li>Relation to Patient</li>
			<li><input type="text" name="relation_to_patient" class="textbox validate[required]"></li>
		</ul>
				
		<div class="line03"></div>
		<div class="clear"></div>

		<p><h1>Other Charges</h1></p>
		<ul id="form02">
			<li>Description</li>
			<li>
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
				<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
						<tr>
							<td style="width: 330px;">Summary Total Other Charges:</td>
							<td style="padding-right: 45px;">
								<span class="summary_total_other_charges">&#8369; 0.00</span>
								<input type="hidden" id="summary_total_o_charges" value="0">
							</td>
						</tr>
				</table>

				<table>	
						<tr>
							<td style="width: 173px; padding: 0;">Total Other Charges:</td>
							<td style="padding-right: 45px;">
								

								<span class="other_charges">&#8369; 0.00</span>
								<input type="hidden" id="total_o_charges" value="0">
							</td>
						</tr>
				</table>

				<table>	
						<tr>
							<td style="width: 173px; padding: 0;">Total Other Charges VAT:</td>
							<td style="padding-right: 45px;">
								<span class="other_charges_vat">&#8369; 0.00</span>
								<input type="hidden" id="total_o_charges_vat" value="0">
							</td>
						</tr>
				</table>
				<br>
				<div class="line03"></div>
				<p><h1>Cost Modifiers</h1></p>
				<ul id="form02">
					<li>Applies to</li>
					<li>
						<select id="apply" data-id="">
							<option value="All Medicines">All Medicines</option>
							<?php foreach ($rpc_meds as $key => $value) { ?>
								<option value="<?php echo $value['medicine_name'] ?>" data-id="<?php echo $value['medicine_id'] ?>" data-key="<?php echo $key ?>"><?php echo $value['medicine_name'] ?></option>
							<?php } ?>
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
					</table>

					<table class="regimen-cost" style="font: normal 18px arial; margin: 12px 0 0 0;">	
						<tr>
							<td style="width: 330px;">Total Cost Modifiers</td>
							<td style="padding-right: 45px;">
								<span class="total_cm">&#8369; 0.00</span>
								<input type="hidden" id="total_cm" value="0">
							</td>
						</tr> 
					</table>
					<br>

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
						<td>Invoice Amount</td>
						<td><input name="net_sales" id="net_sales" type="hidden"><span id="invoice_amount">&#8369; 0.00</span></td>
					</tr>
					<tr>
						<td>Invoice Amount VAT</td>
						<td><input name="net_sales_vat" id="net_sales_vat" type="hidden"><span id="invoice_amount_vat">&#8369; 0.00</span></td>
					</tr>
					<tr>
						<td style="color: #fff; background-color: #f4814b;">Total Invoice Amount</td>
						<td><input name="total_net_sales_vat" id="total_net_sales_vat" type="hidden"><span id="total_invoice_amount"></span></td>
					</tr>
				</table>
				<section id="buttons">
					<button type="button" class="form_button-green">Export to Excel</button>
					<button type="button" class="form_button">Edit Payment</button>
					<button type="button" class="form_button cancel_convert_invoice">Cancel</button>
				</section>
	<script>
		$(function(){

			calculateOverallTotal();
			var regimen_id = sessionStorage.getItem("regimen_id");
			var version_id = sessionStorage.getItem("version_id");

			$("#regimen_id").val(regimen_id);
			$("#version_id").val(version_id);
			
			sessionStorage.removeItem("regimen_id");
			sessionStorage.removeItem("version_id");

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

			check();
		});

		function calculateTotalAlistMedicine(){
			var count = "<?php echo $counter ?>";
			var total = 0;
			for (var i = 0; i < count; i++) {
				total = total + parseFloat($("#alist_total_price_input_" + i).val());
			};
			$("#alist_total_input").val(total.toFixed(2));
			$("#alist_total_span").html(addCommas(total.toFixed(2)));
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