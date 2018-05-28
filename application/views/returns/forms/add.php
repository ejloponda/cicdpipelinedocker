<script>
	$(function(){
		reset_all();
		reset_all_topbars_menu();
		$('.inventory_management_menu').addClass('hilited');
		$('.returns_form').addClass('sub-hilited');

		$(".cancel_receipt").on('click', function(){
			loadPreviousForm();
		});

		$('.actions').tipsy({'gravity': 's'});

		$('#date_return').datepicker({
			format: 'yyyy-mm-dd'
		});

		$(".add_submit_btn").on('click', function(event) {
			//$.blockUI();
			$('#return_form').submit();
		});

		$("#return_form").validationEngine({scroll:false});
		$("#return_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      			viewReturns(o.returns_id);
      			$.unblockUI();
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}

			// alert(o.invoice_id);
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
         beforeSubmit : function(evt){
	         	$.blockUI();
	         },
        
       		dataType : "json"
    	});

    	$(".myCheckbox").on('click', function(){
    		var check 	= $(this).is(":checked");
    		var ctr 	= $(this).data('key');
    		if(check){
    			$('.myText_' + ctr).prop("disabled", false);
    		} else {
    			$('.myText_' + ctr).prop("disabled", true);
    		}
    	});

    	$(".cancel_convert_invoice").on('click', function(){
			window.location.hash = "returns";
			reload_content("returns");
		});

    	/*$(".medgross").hide();
		$("#date_return").live('change', function(){
			$(".medgross").show();
			var return_quantity = parseFloat($('#return_quantity').val());
			var cost_type		= $('#cost_type').val();
			var cost_modifier		= $('#cost_modifier').val();
			var price 			= $('#price').val();
			compute_cost_modifier(return_quantity, cost_type, price, cost_modifier);
		});

		function compute_cost_modifier(return_quantity, cost_type, price, cost_modifier){
			$.post(base_url + "returns_management/computeCostModifier",{return_quantity:return_quantity, cost_type:cost_type, price:price, cost_modifier:cost_modifier},function(o){
							$('.all_credit').val(o.all_credit);
						},"json");
		}*/
		
	});
</script>

<style type="text/css">
	.textbox{
		text-align: right;
	}
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
</style>

<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Returns</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#return_form').submit();"><img class="icon actions" title="Save"  src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li> 
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript:void(0);" class="cancel_receipt"><img class="icon actions" title="Back" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	<ul class="regimen-ID" >
		<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
		<li class="patient">
			<b style="font-size: 20px;"><span><?php echo $patient['patient_name'] ?></span></b>
			<br>Patient ID: <?php echo $patient['patient_code'] ?>
			<!-- <br> -->
		</li>
	</ul>
	
	<div class="line03"></div>
	<form id="return_form" method="post" action="<?php echo url('returns_management/saveReturns') ?>" style="width: 100%">
		<h3>Invoice ID: <?php echo $invoice['order_id'] ?></h3>
		<input type="hidden" value="<?php echo $invoice['id'] ?>" name="invoice_id">
		<input type="hidden" value="<?php echo $invoice['patient_id'] ?>" name="patient_id">

		<?php #debug_array($medicines) ?>
		<p><h3>Medicines</h3></p>
		<table class="table" id="medicine_tables">
			<thead>
				<th></th>
				<th>Medicine Name</th>
				<th>Dosage</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Total Price</th>
				<th>Cost Modifier</th>
			</thead>
			<tbody>
				<?php $ctr = 0 ?>
				<?php foreach ($medicines as $key => $value) { ?>
					<tr>
						<td><input type="checkbox" name="medicine[<?php echo $ctr ?>][id]" class="myCheckbox" data-key="<?php echo $ctr ?>" value="<?php echo $value['medicine_id'] ?>"> - <input type="text" id = "return_quantity"style="width: 50px;" class="textbox actions validate[custom[onlyNumberSp]] myText_<?php echo $ctr ?>" value="0" title="Quantity to Return" name="medicine[<?php echo $ctr ?>][quantity]" disabled></td>
						<td><?php echo $value['medicine_name'] ?></td>
						<td><?php echo $value['dosage'] . " " . $value['dosage_type'] ?></td>
						<td><?php echo $value['quantity'] ." ".($value['quantity'] == 1 ? (substr($value['quantity_type'], 0,-1)) : $value['quantity_type']) ?></td>
						<td>P <?php echo number_format($value['price'], 2, '.', ',') ?><input type="hidden" id="price" class="myText_<?php echo $ctr ?>" value="<?php echo $value['price'] ?>" name="medicine[<?php echo $ctr ?>][price]" disabled></td>
						<td>P <?php echo number_format($value['total_price'], 2, '.', ',')  ?></td>
						<td><input type="hidden" id="cost_type" class="myText_<?php echo $ctr ?>" value="<?php echo $value['cost_type'] ?>" name="medicine[<?php echo $ctr ?>][cost_type]" disabled> 
							<input type="hidden" id="cost_modifier" class="myText_<?php echo $ctr ?>" value="<?php echo $value['cost_modifier'] ?>" name="medicine[<?php echo $ctr ?>][cost_modifier]" disabled>
						 <?php echo ($value['cost_type'] == "%" ? $value['cost_modifier']." ".$value['cost_type'] : $value['cost_modifier']." ".$value['cost_type']);?></td>
						<td style = "display:none;"><input type="hidden" id="modify_due_to" class="myText_<?php echo $ctr ?>" value="<?php echo $value['modify_due_to'] ?>" name="medicine[<?php echo $ctr ?>][modify_due_to]" disabled> 
					</tr>
					<?php $ctr++ ?>	
				<?php } ?>
			</tbody>
		</table>

		<!-- <p class="medgross">TOTAL CREDIT:  &#8369; <input type="text" name="all_credit" id="all_credit" class="all_credit" style="border:0px;">  </span></p> -->
		<br>
		<ul id="form02">
			<li>Date of Return</li>
			<li><input type="text" id="date_return" name="date_return" class="validate[required]" style="width: 150px;"></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Return Slip No.</li>
			<li><input type="text" id="return_slip" name="return_slip" class="validate[required]" style="width: 150px;"></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Discounted Amount</li>
			<li><input type="text" id="discounted_amt" name="discounted_amt" value="0.00" style="width: 150px;"></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Reason of Return</li>
			<li><textarea name="reasons" class="validate[required]" style="width: 768px; max-width:768px; max-height:111px; height: 111px;"></textarea></li>
		</ul>
		<div class="clear"></div>
	</form>

	<section id="buttons">
		<button type="button" class="form_button add_submit_btn">Save</button>
		<button type="button" class="form_button cancel_convert_invoice">Cancel</button>
	</section>
</section>