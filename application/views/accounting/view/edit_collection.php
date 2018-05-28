<script>
	$(function(){
		reset_all();
		reset_all_topbars_menu();
		HideAllPayment();
		$('.billing_menu').addClass('hilited');
		$('.account_billing_menu').addClass('hidden');
		$('.account_billing_add_invoice_menu').removeClass('hidden');
		$('.rpc_form_invoice').addClass('sub-hilited');

		$(".saveButton").tipsy({gravity: 's'});
		$(".actions").tipsy({gravity: 's'});
		
		$('#date_receipt').datepicker({
			format: 'yyyy-mm-dd'
		});
		
		$('#cheque_date').datepicker({
			format: 'yyyy-mm-dd'
		});

		var payment = $(".mode_payment:checked").val();
		ShowHidePayment(payment);
		$(".mode_payment").on('click', function(){
			if($(this).is(":checked")){
				var mode_payment = $(this).val();
				ShowHidePayment(mode_payment);
			}
		});

		$('.figure').on('change',function() {
			var value = $(this).val();
			var formatted = number_format(value,2,'.',',');
			$(this).val(formatted);
		});
		
		$('.figure').val("0.00");
		$('#total_payment_span').html("0.00");
		$("#cash_wrapper").hide();
		$("#cheque_wrapper").hide();
		$("#creditcard_wrapper").hide();
		$("#credit_wrapper").hide();

		$(".cancel_receipt").on('click', function(){
			loadPreviousForm();
		});

		$("#edit_payment_form").validationEngine({scroll:false});
		$("#edit_payment_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      			$.unblockUI();
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      			$.unblockUI();
      		}
      		window.location.hash = "collections";
			reload_content("collections");

			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        beforeSubmit : function(evt){
         	$.blockUI();
        },
        
       		dataType : "json"
    	});
	});

	function ShowHidePayment(mode_payment){
		switch(mode_payment){
			case 'cash':
				HideAllPayment();
				$(".cash_form").show();
				break;
			case 'cheque':
				HideAllPayment();
				$(".cheque_form").show();
				break;
			case 'creditcard':
				HideAllPayment();
				$(".credit_card_form").show();
				break;
			case 'credit':
				HideAllPayment();
				$(".credit_form").show();
				break;

		}
	}

	function HideAllPayment(){
		$(".cheque_form").hide();
		$(".cash_form").hide();
		$(".credit_card_form").hide();
		$(".credit_form").hide();
	}
</script>

<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Edit Collection</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#edit_payment_form').submit();"><img class="icon saveButton" title="Save"  src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript:void(0);" class="cancel_receipt"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	<ul class="regimen-ID" >
		<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
		<li class="patient">
			<b style="font-size: 20px;"><span><?php echo $patient['patient_name'] ?></span></b>
			<br>Patient ID: <?php echo $patient['patient_code'] ?>
			<!-- <br>Regimen ID: <a href="javascript: void(0);" class="regimen_open"><?php echo $regimen['regimen_number'] ?></a> <?php echo ($version_id ? " Version Name: " .$version['version_name'] : "")  ?> -->
			<!-- <br> -->
		</li>
	</ul>
	
	<div class="line03"></div>

	<h3>Invoice ID: <?php echo $invoice['invoice_num'] ?></h3>

	<section id="left" style="border-right: dashed 1px #d3d3d3;">
		<b>Balance Details</b>
		<ul id="form02">
			<li>Amount Due</li>
			<li>P <?php echo number_format( $invoice['total_net_sales_vat'], 2, '.', ',') ?></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Total Amount Paid</li>
			<li>P <?php echo number_format( $invoice['total_amount_paid'], 2, '.', ',') ?></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Remaining Balance</li>
			<li>P <?php echo number_format( $invoice['remaining_balance'], 2, '.', ',') ?></li>
			<div class="clear"></div>
		</ul>

		<?php if($invoice['overpayment'] != '0.00'){?>
		<ul id="form02">
			<li>Overpayment Amount</li>
			<li>P <?php echo number_format($invoice['overpayment'], 2, '.', ',') ?></li>
		</ul>
		<div class="clear"></div>
		<?php } ?>
	</section>

	<section id="right">
		<b>Credit Details</b>
		<ul id="form02">
			<li style="width: 90px;">Credits</li>
			<li><?php echo $patient['credit'] ?></li>
		</ul>
	</section>
	<div class="clear"></div>

	<div class="line03"></div>
	<div class="clear"></div>

	<ul id="form02">
		<li>Charged to</li>
		<li><?php echo $invoice['charge_to'] ?></li>
	</ul>
	<div class="clear"></div>

	<ul id="form02">
		<li>Relation to Patient</li>
		<li><?php echo $invoice['relation_to_patient'] ?></li>
	</ul>
	<div class="clear"></div>
	<div class="line03"></div>

	<p><b>Receipt Information</b></p>
	<form style="width: 100%;" action="<?php echo url('account_billing/save_collections') ?>" method="post" id="edit_payment_form">
		<input type="hidden" name="or_id" value="<?php echo $or_id ?>">
		<ul id="form02">
			<li>OR Number</li>
			<li><input type="text" id="or_number" name="or_number" class="validate[required] textbox" value="<?php echo $collections['or_number']; ?>"></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Date of Receipt</li>
			<li><input type="text" id="date_receipt" name="date_receipt" class="validate[required] textbox" value="<?php echo $collections['date_receipt']?>"></li>
			<input type="hidden" name="invoice_id" value="<?php echo $invoice['id'] ?>">
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Amount</li>
			<li><input type="text" name="amount" id="amount" value="<?php echo $collections['amount_paid'];?>" readonly></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02" style="margin-top: -8px!important ;">
			<li>Notes</li>
			<li><textarea name="edit_notes" id="edit_notes" style="max-width:280px; width:280px; max-height: 87px; height: 87px; border: 1px solid #cccccc !important; margin: 10px 0px !important; border-radius: 0px !important; resize: none !important; padding: 10px !important;"><?php echo $collections['notes'];?></textarea></li>
		</ul>
		<div class="clear"></div>

		<?php if(!empty($cash)) { ?> 
		<!-- <h4>Cash</h4> -->
		<table class="table collections-th" id="cash_table_form">
			<thead>
				<th>Cash</th>
				<th>
				<!-- <span class="glyphicon glyphicon-remove-circle close_btn" onclick="removePayment()" value ="<?php echo $or_id ?>">
				<input type="hidden" id="btn_close" name="btn_close" value="<?php echo $or_id ?>"></input>
				</span> -->
				</th> 
				<th class="th-action">Action</th>
			</thead>
			<tbody>
				<?php foreach ($cash as $key => $value) { ?>
					<tr>
						<td style="font-weight: bold;" >Amount Paid</td>
						<td>P <?php echo number_format($value['price'],2,'.',',') ?>
						<!-- P <input type="text" name="cash_amount_paid" id="cash_amount_paid" value="<?php echo number_format($value['price'],2,'.',',') ?>"> --></td>
						<td><a href="javascript: void(0);" onclick="javascript: change_mode_of_payment(<?php echo $value['id']?>, <?php echo $value['or_id']?>, <?php echo 1;?>)">Change mode of payment</a></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

		<?php if(!empty($credit)) { ?> 
		<h4>Credit</h4>
		<table class="table collections-th" id="credit_table_form">
			<thead>
				<th>Credit</th>
				<th></th> 
				<th class="th-action">Action</th> 
			</thead>
			<tbody>
				<?php foreach ($credit as $key => $value) { ?>
					<tr>
						<td style="font-weight: bold;" >Amount Paid</td>
						<td>P <?php echo number_format($value['price'],2,'.',',') ?></td>
						<td><a href="javascript: void(0);" onclick="javascript: change_mode_of_payment(<?php echo $value['id']?>, <?php echo $value['or_id']?>, <?php echo 2;?>)">Change mode of payment</a></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

		<?php if(!empty($cheque)) { ?> 
		<!-- <h4>Cheque</h4> -->
		<table class="table collections-th" id="cheque_table_form">
			<thead>
				<th>Cheque</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th class="th-action">Action</th> 
			</thead>
			<tbody>
				<?php foreach ($cheque as $key => $value) { ?>
					<tr>
						<td style="font-weight: bold;" >Check Number</td>
						<td><?php echo $value['cheque_number'] ?></td>
						<td style="font-weight: bold;" >Bank</td>
						<td><?php echo $value['bank_name'] ?></td>
						<td style="font-weight: bold;" >Cheque Date</td>
						<td><?php echo $value['cheque_date'] ?></td>
						<td style="font-weight: bold;" >Amount Paid</td>
						<td>P <?php echo number_format($value['price'],2,'.',',') ?></td>
						<td><a href="javascript: void(0);" onclick="javascript: change_mode_of_payment(<?php echo $value['id']?>, <?php echo $value['or_id']?>, <?php echo 3;?>)">Change mode of payment</a></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

		<?php if(!empty($cc)) { ?> 
		<!-- <h4>Credit Card</h4> -->
		<table class="table collections-th" id="creditcard_table_form">
			<thead>
				<th>Credit Card</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th> 
				<th class="th-action">Action</th> 
			</thead>
			<tbody>
				<?php foreach ($cc as $key => $value) { ?>
					<tr><td style="font-weight: bold;" >Bank</td>
						<td><?php echo $value['bank_name'] ?></td>
						<td style="font-weight: bold;" >Card Type</td>
						<td><?php echo $value['card_type'] ?></td>
						<td style="font-weight: bold;" >Amount Paid</td>
						<td>P <?php echo number_format($value['price'],2,'.',',') ?></td>
						<td><a href="javascript: void(0);" onclick="javascript: change_mode_of_payment(<?php echo $value['id']?>, <?php echo $value['or_id']?>, <?php echo 4;?>)">Change mode of payment</a></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

		<?php if(!empty($taxwithheld)) { ?> 
		<h4>Tax Withheld</h4>
		<table class="table collections-th" id="taxwithheld_table_form">
			<thead>
				<th>Tax Withheld</th>
				<th></th> 
				<th class="th-action">Action</th> 
			</thead>
			<tbody>
				<?php foreach ($taxwithheld as $key => $value) { ?>
					<tr>
						<td style="font-weight: bold;" >Amount Paid</td>
						<td>P <?php echo number_format($value['price'],2,'.',',') ?></td>
						<td><a href="javascript: void(0);" onclick="javascript: change_mode_of_payment(<?php echo $value['id']?>, <?php echo $value['or_id']?>, <?php echo 5;?>)">Change mode of payment</a></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>
	</form>

	<section id="buttons">
		<button type="button" class="form_button" onClick="$('#edit_payment_form').submit();">Save Payment</button>
		<button type="button" class="form_button cancel_receipt">Cancel</button>
	</section>
	</section>
<script>
	function change_mode_of_payment(id,or_id,mode_payment){
		$.post(base_url + "account_billing/change_mode_of_payment", {id:id,or_id:or_id,mode_payment:mode_payment}, function(o){
			$('#change_mode_of_payment').html(o);
			$('#change_mode_of_payment').modal("show");
		});
	
	}
</script>
