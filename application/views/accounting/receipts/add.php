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
		$("#taxwithheld_wrapper").hide();

		$(".cancel_receipt").on('click', function(){
			loadPreviousForm();
		});

		$("#add_payment_form").validationEngine({scroll:false});
		$("#add_payment_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_RECEIPT_BILLING_CHANGE = false;
      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      			$.unblockUI();
      			
      			window.location.hash = "collections";
				reload_content("collections");
      		} else {
      			//default_success_confirmation({message : o.message, alert_type: "alert-error"});
      			alert(o.notice);
      			$.unblockUI();
      		}
      		

			// alert(o.invoice_id);
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
			case 'tax_withheld':
				HideAllPayment();
				$(".taxwithheld_form").show();
				break;
		}
	}

	function HideAllPayment(){
		$(".cheque_form").hide();
		$(".cash_form").hide();
		$(".credit_card_form").hide();
		$(".credit_form").hide();
		$(".taxwithheld_form").hide();
	}
</script>

<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Add New Receipt</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#add_payment_form').submit();"><img class="icon saveButton" title="Save"  src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
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
			<!--<br>Regimen ID: <a href="javascript: void(0);" class="regimen_open"><?php echo $regimen['regimen_number'] ?></a> <?php echo ($version_id ? " Version Name: " .$version['version_name'] : "")  ?>-->
			<!-- <br> -->
		</li>
	</ul>
	
	<div class="line03"></div>

	<h3>Invoice ID: <?php echo $invoice['invoice_num'] ?></h3>

	<section id="left" style="border-right: dashed 1px #d3d3d3;">
		<b>Balance Details</b>
		<ul id="form02">
			<li>Amount Due</li>
			<li>P <?php echo number_format( $invoice['total_net_sales_vat'], 2, '.', ',') ?><input type="hidden" value="<?php echo $invoice['total_net_sales_vat'] ?>" id="total_amount_due"></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Total Amount Paid</li>
			<li>P <?php echo number_format( $invoice['total_amount_paid'], 2, '.', ',') ?><input type="hidden" value="<?php echo $invoice['total_amount_paid'] ?>" id="total_amount_paid"></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Remaining Balance</li>
			<li>P <span id="remaining_balance" style="color:#555;"></span><input type="hidden" name="remaining_balance_val" id="remaining_balance_val"></li>							
		</ul>
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
	<form style="width: 100%;" action="<?php echo url('account_billing/save_receipt') ?>" method="post" id="add_payment_form">
		<ul id="form02">
			<li>OR Number</li>
			<li><input type="text" id="or_number" name="or_number" class="validate[required] textbox"></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02">
			<li>Date of Receipt</li>
			<li><input type="text" id="date_receipt" name="date_receipt" class="validate[required] textbox"></li>
			<input type="hidden" name="invoice_id" value="<?php echo $invoice['id'] ?>">
		</ul>
		<div class="clear"></div>

		<ul id="form02" style="margin-top: -8px !important;">
			<li>Notes</li>
			<li><textarea name="add_notes" id="add_notes" style="max-width:280px; width:280px; max-height: 87px; height: 87px; border: 1px solid #cccccc !important; margin: 10px 0px !important; border-radius: 0px !important; resize: none !important; padding: 10px !important;"></textarea></li>
		</ul>
		<div class="clear"></div>

		<ul id="form02" class="newform">
			<li>Method of Payment</li>
			<li>
				<input type="radio" class="mode_payment" name="mode_payment" value="cash"><label for="r1"><span></span>Cash</label>
				<input type="radio" class="mode_payment" name="mode_payment" value="cheque"> <label for="r2"><span></span>Cheque</label>
				<input type="radio" class="mode_payment" name="mode_payment" value="creditcard"> <label for="r3"><span></span>Credit Card</label>
				<?php if($patient['credit'] > 0){ ?>
				<input type="radio" class="mode_payment" name="mode_payment" value="credit"> <label for="r3"><span></span>Credit</label>
				<?php } ?>
				<input type="radio" class="mode_payment" name="mode_payment" value="tax_withheld"><label for="r4"><span></span>Tax Withheld</label>
				<!-- <input type="radio" class="mode_payment" name="mode_payment" value="rpc_marketing"><label for="r4"><span></span>RPC Marketing</label> -->
			</li>
		</ul>
		<div class="clear"></div>
		
		<div class="cheque_form">
			<ul id="form02">
				<li></li>
				<li style="margin: 0 40px 0 0; width: 250px !important;">
					<ul class="payment">
						<li>Bank Name</li>
						<li><input type="text" class="form_reset" id="cheque_bank_name"></li>
					</ul>
						
					<ul class="payment">
						<li>Cheque Number</li>
						<li><input type="text" class="form_reset" id="cheque_number"></li>
					</ul>									
				</li>
				
				<li style="width: 250px;">
					<ul class="payment">
						<li>Cheque Date</li>
						<li><input type="text" class="form_reset" id="cheque_date"></li>
					</ul>
						
					<ul class="payment">
						<li>Amount Paid</li>
						<li><input type="text" class="figure" id="cheque_amount_paid"></li>
					</ul>	
				</li>	
			</ul>
			<div class="clear"></div>
		</div>

		<div class="cash_form">
			<ul id="form02">
				<li></li>
				<li style="margin: 0 40px 0 0; width: 250px !important;">
					<ul class="payment">
						<li>Amount Paid</li>
						<li><input type="text" class="figure" id="cash_amount_paid"></li>
					</ul>							
				</li>
			</ul>
			<div class="clear"></div>
		</div>

		<div class="credit_form">
			<ul id="form02">
				<li></li>
				<li style="margin: 0 40px 0 0; width: 250px !important;">
					<ul class="payment">
						<li>Amount Paid</li>
						<li><input type="text" class="figure" id="credit_amount_paid"></li>
					</ul>							
				</li>
			</ul>
			<div class="clear"></div>
		</div>

		<div class="credit_card_form">
			<ul id="form02">
				<li></li>
				<li style="margin: 0 40px 0 0; width: 250px !important;">
					<ul class="payment">
						<li>Bank Name</li>
						<li><input type="text" class="form_reset" id="cc_bank_name"></li>
					</ul>
						
					<ul class="payment">
						<li>Amount Paid</li>
						<li><input type="text" class="figure" id="cc_amount_paid"></li>
					</ul>								
				</li>

				<li style="width: 250px;">
					<ul class="payment">
						<li>Card Type</li>
						<li><input type="text" class="form_reset" id="cc_type"></li>
					</ul>
				</li>
			</ul>
			<div class="clear"></div>
		</div>

		<div class="taxwithheld_form">
			<ul id="form02">
				<li></li>
				<li style="margin: 0 40px 0 0; width: 250px !important;">
					<ul class="payment">
						<li>Amount Paid</li>
						<li><input type="text" class="figure" id="taxwithheld_amount_paid"></li>
					</ul>							
				</li>
			</ul>
			<div class="clear"></div>
		</div>

		<button type="button" class="invoice-modifier-add save_payment_btn" style="float: right; margin-right: 72.5%;">Add Payment</button>		

		<div class="clear"></div>

		<div id="cash_wrapper">
			<h4>Cash</h4>
			<table class="table" id="cash_table_form">
				<th>Amount Paid</th>
				<th></th>
			</table>
		</div>

		<div id="cheque_wrapper">
			<h4>Cheque</h4>
			<table class="table" id="cheque_table_form">
				<th>Check Number</th>
				<th>Bank</th>
				<th>Cheque Date</th>
				<th>Amount Paid</th>
				<th></th>
			</table>
		</div>

		<div id="creditcard_wrapper">
			<h4>Credit Card</h4>
			<table class="table" id="creditcard_table_form">
				<th>Bank</th>
				<th>Card Type</th>
				<th>Amount Paid</th>
				<th></th>
			</table>
		</div>

		<div id="credit_wrapper">
			<h4>Credit</h4>
			<table class="table" id="credit_table_form">
				<th>Amount Paid</th>
				<th></th>
			</table>
		</div>

		<div id="taxwithheld_wrapper">
			<h4>Tax Withheld</h4>
			<table class="table" id="taxwithheld_table_form">
				<th>Amount Paid</th>
				<th></th>
			</table>
		</div>

		<table class="table-total">
			<tr>
				<td>Total Payment</td>
				<td>P <span id="total_payment_span"></span> <input type="hidden" id="total_payment" name="total_payment" value="0.00"></td>
			</tr>
			<tr id="overpayment_wrapper" style="display:none;">
				<td style="border: 1px solid #f4814b;">Over Payment</td>
				<td style="border: 1px solid #f4814b;">P <span id="total_overpayment_span" value="0.00"></span> <input type="hidden" id="total_overpayment" name="total_overpayment" value="0.00"></td>
			</tr>
		</table>
	</form>
<section id="buttons">
	<button type="button" class="form_button" onClick="$('#add_payment_form').submit();">Save Payment</button>
	<button type="button" class="form_button cancel_receipt">Cancel</button>
</section>
</section>

<script>
	computeTotalAmountPaid();
	computeOverpayment();

	$(function(){
		
		
		$(".save_payment_btn").on('click', function(){
			addNewPayment();
		});
	});

	var total_payment 		= 0;
	var paymentCtr 			= 0;
	var paymentCash 		= 0;
	var paymentCheque 		= 0;
	var paymentCreditcard 	= 0;
	var paymentTaxwithheld  = 0;
	var credit_info 		= parseFloat("<?php echo $patient['credit'] ?>");
	function addNewPayment(){
		var payment 			= $(".mode_payment:checked").val();
		var delete_button 		= BASE_IMAGE_PATH +"doc_delete.png";
		var remaining_balance   = parseFloat($("#remaining_balance_val").val().replace(/,/g,'')); 
		var total_amount_due    = parseFloat($("#total_amount_due").val().replace(/,/g,''));
		var total_amount_paid   = parseFloat($("#total_amount_paid").val().replace(/,/g,''));
		
		if(payment == 'cash'){
			var cash = parseFloat($("#cash_amount_paid").val().replace(/,/g,''));
			//GET THE TOTAL AMOUNT PAID 
			var payment_total = parseFloat($("#total_payment").val().replace(/,/g,''));  
			var total_paid = cash + payment_total;
			
			if (total_paid <= remaining_balance) {
				if(hasValue(cash)){
					if(!isNaN(cash)){
						// success we got through all validation!
						paymentCtr++;
						total_payment += cash;
						paymentCash += cash;
						var html_cash = '';
						var cash_input 	= "receipt[cash]["+paymentCtr+"]";
						var cash_id 	= "receipt_cash_"+paymentCtr;

						$('#cash_wrapper').show();
						html_cash += '<tr id="added_row_'+paymentCtr+'" data-type="cash">';
						html_cash += '<td><input type="hidden" name="'+cash_input+'" id="'+cash_id+'" value="'+cash+'">P '+number_format(cash)+'</td>';
						html_cash += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removePayment('+paymentCtr+')" title="Delete"></li></ul></td>';
						html_cash += '</tr>';
						$('#cash_table_form').append(html_cash);

						$("#cash_amount_paid").focus();
						$("#total_payment_span").html(number_format(total_payment,2,'.',','));
						$("#total_payment").val(total_payment);
						
					}else{
						alert("Warning: Cost must be limited to number formatting.");
					}
				}else{
					alert("Warning : Please fill-up the required fields before adding a payment.");
				}

			}else{
				//Oops, Payment will not proceed due to excess amount.
				var confirm = new jBox('Modal', {
				content: '<h3>The amount you entered cannot exceed the remaining balance.</h3>',
				cancelButton: 'close',
				animation: {open: 'tada', close: 'pulse'}
				});
				confirm.open();
			}				
		}else if(payment == 'cheque'){

			var bank_name 	= $("#cheque_bank_name").val();
			var cheque_no 	= $("#cheque_number").val();
			var cheque_date = $("#cheque_date").val();
			var amount_paid = parseFloat($("#cheque_amount_paid").val().replace(/,/g,''));

			var payment_total 	   =  parseFloat((!isNaN($("#total_payment").val().replace(/,/g,''))) ? parseFloat($('#total_payment').val().replace(/,/g,'')) : 0 )
		
			var total_paid 		   = amount_paid + payment_total;		
			var overpayment_amount  = (total_paid - remaining_balance);

			if(hasValue(bank_name) && hasValue(cheque_no) && hasValue(cheque_date) && hasValue(amount_paid)){
				if(!isNaN(amount_paid)){
					paymentCtr++;
					paymentCheque += amount_paid;
					total_payment += amount_paid;

					var html_cheque = '';

					var cash_input 			= "receipt[cheque]["+paymentCtr+"][amount_paid]";
					var bank_name_input 	= "receipt[cheque]["+paymentCtr+"][bank_name]";
					var cheque_no_input 	= "receipt[cheque]["+paymentCtr+"][cheque_no]";
					var cheque_date_input 	= "receipt[cheque]["+paymentCtr+"][cheque_date]";
					//var overpayment_input 	= "receipt[cheque]["+paymentCtr+"][overpayment_amount]";
					
					var cash_id 			= "receipt_cheque_"+paymentCtr;
					
					if (total_paid <= remaining_balance) {
						$('#cheque_wrapper').show();
						html_cheque += '<tr id="added_row_'+paymentCtr+'" data-type="cheque">';
						html_cheque += '<td><input type="hidden" name="'+cheque_no_input+'" value="'+cheque_no+'">'+cheque_no+'</td>';
						html_cheque += '<td><input type="hidden" name="'+bank_name_input+'" value="'+bank_name+'">'+bank_name+'</td>';
						html_cheque += '<td><input type="hidden" name="'+cheque_date_input+'" value="'+cheque_date+'">'+cheque_date+'</td>';
						html_cheque += '<td><input type="hidden" name="'+cash_input+'" id="'+cash_id+'" value="'+amount_paid+'">P '+number_format(amount_paid)+'</td>';
						html_cheque += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removePayment('+paymentCtr+')" title="Delete"></li></ul></td>';
						html_cheque += '</tr>';
						$('#cheque_table_form').append(html_cheque);

						$("#total_payment_span").html(number_format(total_payment,2,'.',','));
						$("#total_payment").val(total_payment);
						
						$("#cheque_bank_name").focus();
					}else{
						var confirm = new jBox('Confirm', {
						content: '<h3>The amount you entered has exceeded the remaining balance, would you like to convert the excess to credits?</h3>',
						confirmButton: 'Yes',
						cancelButton: 'No',
						confirm: function(){
							$('#cheque_wrapper').show();
							html_cheque += '<tr id="added_row_'+paymentCtr+'" data-type="cheque">';
							html_cheque += '<td><input type="hidden" name="'+cheque_no_input+'" value="'+cheque_no+'">'+cheque_no+'</td>';
							html_cheque += '<td><input type="hidden" name="'+bank_name_input+'" value="'+bank_name+'">'+bank_name+'</td>';
							html_cheque += '<td><input type="hidden" name="'+cheque_date_input+'" value="'+cheque_date+'">'+cheque_date+'</td>';
							html_cheque += '<td><input type="hidden" name="'+cash_input+'" id="'+cash_id+'" value="'+amount_paid+'">P '+number_format(amount_paid)+'</td>';
							html_cheque += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removePayment('+paymentCtr+')" title="Delete"></li></ul></td>';
							html_cheque += '</tr>';
							$('#cheque_table_form').append(html_cheque);

							$("#total_payment_span").html(number_format(total_payment,2,'.',','));
							$("#total_payment").val(total_payment);
							$("#overpayment_wrapper").show();
							$("#total_overpayment_span").html(number_format(overpayment_amount,2,'.',','));
							$("#total_overpayment").val(overpayment_amount);

							$("#cheque_bank_name").focus();	
						},
						cancel: function(){
							total_payment -= amount_paid;
							$("#total_payment_span").html(number_format(total_payment,2,'.',','));
							$("#total_payment").val(total_payment);
						},
						animation: {open: 'tada', close: 'pulse'}
						});
						confirm.open();
					}
				}else{
					alert("Warning: Cost must be limited to number formatting.");
				}
			}else{
				alert("Warning : Please fill-up the required fields before adding a payment.");
			}
		} else if(payment == "credit"){
			var credit = parseFloat($("#credit_amount_paid").val().replace(/,/g,''));

			var payment_total = parseFloat($("#total_payment").val().replace(/,/g,''));  
			var total_paid = credit + payment_total;
			
			if (total_paid <= remaining_balance) {
				if(hasValue(credit)){
					if(!isNaN(credit)){
						// success we got through all validation!
						if(credit <= credit_info){
							paymentCtr++;
							total_payment += credit;
							paymentCash += credit;
							credit_info -= credit;
							var html_credit = '';
							var credit_input 	= "receipt[credit]["+paymentCtr+"]";
							var credit_id 	= "receipt_credit_"+paymentCtr;

							$('#credit_wrapper').show();
							html_credit += '<tr id="added_row_'+paymentCtr+'" data-type="credit">';
							html_credit += '<td><input type="hidden" name="'+credit_input+'" id="'+credit_id+'" value="'+credit+'">P '+number_format(credit)+'</td>';
							html_credit += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removePayment('+paymentCtr+')" title="Delete"></li></ul></td>';
							html_credit += '</tr>';
							$('#credit_table_form').append(html_credit);

							$("#credit_amount_paid").focus();
							$("#total_payment_span").html(number_format(total_payment,2,'.',','));
							$("#total_payment").val(total_payment);
						} else {
							alert("Warning: You don't have enough credit.");
						}
						
						
					}else{
						alert("Warning: Cost must be limited to number formatting.");
					}
				}else{
					alert("Warning : Please fill-up the required fields before adding a payment.");
				}
			}else{
				//Oops, Payment will not proceed due to excess amount.
				var confirm = new jBox('Modal', {
				content: '<h3>The amount you entered cannot exceed the remaining balance.</h3>',
				cancelButton: 'close',
				animation: {open: 'tada', close: 'pulse'}
				});
				confirm.open();
			}		
		} else if(payment == "tax_withheld"){
			var taxwithheld = parseFloat($("#taxwithheld_amount_paid").val().replace(/,/g,''));
			//GET THE TOTAL AMOUNT PAID 
			var payment_total = parseFloat($("#total_payment").val().replace(/,/g,''));  
			var total_paid = taxwithheld + payment_total;
			
			if (total_paid <= remaining_balance) {
				if(hasValue(taxwithheld)){
					if(!isNaN(taxwithheld)){
						// success we got through all validation!
						paymentCtr++;
						total_payment += taxwithheld;
						paymentTaxwithheld += taxwithheld;
						var html_taxwithheld = '';
						var cash_input 	= "receipt[taxwithheld]["+paymentCtr+"]";
						var cash_id 	= "receipt_taxwithheld_"+paymentCtr;
					
						$('#taxwithheld_wrapper').show();
						html_taxwithheld += '<tr id="added_row_'+paymentCtr+'" data-type="taxwithheld">';
						html_taxwithheld += '<td><input type="hidden" name="'+cash_input+'" id="'+cash_id+'" value="'+taxwithheld+'">P '+number_format(taxwithheld)+'</td>';
						html_taxwithheld += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removePayment('+paymentCtr+')" title="Delete"></li></ul></td>';
						html_taxwithheld += '</tr>';
						$('#taxwithheld_table_form').append(html_taxwithheld);
						

						$("#taxwithheld_amount_paid").focus();
						$("#total_payment_span").html(number_format(total_payment,2,'.',','));
						$("#total_payment").val(total_payment);
						
					}else{
						alert("Warning: Cost must be limited to number formatting.");
					}
				}else{
					alert("Warning : Please fill-up the required fields before adding a payment.");
				}

			}else{
				//Oops, Payment will not proceed due to excess amount.
				var confirm = new jBox('Modal', {
				content: '<h3>The amount you entered cannot exceed the remaining balance.</h3>',
				cancelButton: 'close',
				animation: {open: 'tada', close: 'pulse'}
				});
				confirm.open();
			}
		} else if(payment == "rpc_marketing"){

		}else{
			var bank_name 	= $("#cc_bank_name").val();
			var cc_type 	= $("#cc_type").val();
			var amount_paid = parseFloat($("#cc_amount_paid").val().replace(/,/g,''));

			var payment_total = parseFloat($("#total_payment").val().replace(/,/g,''));  
			var total_paid = amount_paid + payment_total;

			var total_paid 		   = amount_paid + payment_total;		
			var overpayment_amount  = (total_paid - remaining_balance);
			
			
			if(hasValue(bank_name) && hasValue(amount_paid) && hasValue(cc_type)){
				if(!isNaN(amount_paid)){

					paymentCtr++;
					paymentCreditcard += amount_paid;
					total_payment += amount_paid;

					var html_creditcard = '';

					var cash_input 			= "receipt[creditcard]["+paymentCtr+"][amount_paid]";
					var bank_name_input 	= "receipt[creditcard]["+paymentCtr+"][bank_name]";
					var cc_type_input 		= "receipt[creditcard]["+paymentCtr+"][cc_type]";

					var cash_id 			= "receipt_creditcard_"+paymentCtr;
					if (total_paid <= remaining_balance) {
						$('#creditcard_wrapper').show();
						html_creditcard += '<tr id="added_row_'+paymentCtr+'" data-type="creditcard">';
						html_creditcard += '<td><input type="hidden" name="'+bank_name_input+'" value="'+bank_name+'">'+bank_name+'</td>';
						html_creditcard += '<td><input type="hidden" name="'+cc_type_input+'" value="'+cc_type+'">'+cc_type+'</td>';
						html_creditcard += '<td><input type="hidden" name="'+cash_input+'" id="'+cash_id+'" value="'+amount_paid+'">P '+number_format(amount_paid)+'</td>';
						html_creditcard += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removePayment('+paymentCtr+')" title="Delete"></li></ul></td>';
						html_creditcard += '</tr>';
						$('#creditcard_table_form').append(html_creditcard);

						$("#total_payment_span").html(number_format(total_payment,2,'.',','));
						$("#total_payment").val(total_payment);

						$("#cc_bank_name").focus();
					}else{
						var confirm = new jBox('Confirm', {
						content: '<h3>The amount you entered has exceeded the remaining balance, would you like to convert the excess to credits?</h3>',
						confirmButton: 'Yes',
						cancelButton: 'No',
						confirm: function(){
							$('#creditcard_wrapper').show();
							html_creditcard += '<tr id="added_row_'+paymentCtr+'" data-type="creditcard">';
							html_creditcard += '<td><input type="hidden" name="'+bank_name_input+'" value="'+bank_name+'">'+bank_name+'</td>';
							html_creditcard += '<td><input type="hidden" name="'+cc_type_input+'" value="'+cc_type+'">'+cc_type+'</td>';
							html_creditcard += '<td><input type="hidden" name="'+cash_input+'" id="'+cash_id+'" value="'+amount_paid+'">P '+number_format(amount_paid)+'</td>';
							html_creditcard += '<td style="width: 10%;"><ul class="actions"><li><img src="' + delete_button + '" onclick="javascript:removePayment('+paymentCtr+')" title="Delete"></li></ul></td>';
							html_creditcard += '</tr>';
							$('#creditcard_table_form').append(html_creditcard);

							$("#total_payment_span").html(number_format(total_payment,2,'.',','));
							$("#total_payment").val(total_payment);

							$("#overpayment_wrapper").show();
							$("#total_overpayment_span").html(number_format(overpayment_amount,2,'.',','));
							$("#total_overpayment").val(overpayment_amount);

							$("#cc_bank_name").focus();
						},
						cancel: function(){
							total_payment -= amount_paid;
							$("#total_payment_span").html(number_format(total_payment,2,'.',','));
							$("#total_payment").val(total_payment);
						},
						animation: {open: 'tada', close: 'pulse'}
						});
						confirm.open();
					}
				}else{
					alert("Warning: Cost must be limited to number formatting.");
				}
			}else{
				alert("Warning : Please fill-up the required fields before adding a payment.");
			}
		}
		$('.figure').val("0.00");

		$('.form_reset').val("");
		if(total_payment > 0){
			IS_ADD_RECEIPT_BILLING_CHANGE = true;
		} else {
			IS_ADD_RECEIPT_BILLING_CHANGE = false;
		}
	}

	function removePayment(id){
		var type = $("#added_row_"+id).data("type");

		var cash = parseFloat($("#receipt_"+type+"_"+id).val());
		computeOverpayment(cash);

		if(!isNaN(cash)){
			if(type == 'cash'){
				paymentCash -= cash;

				if(paymentCash > 0){
					$("#cash_wrapper").show();
				} else {
					$("#cash_wrapper").hide();
				}
			}else if(type == 'cheque'){
				paymentCheque -= cash;

				if(paymentCheque > 0){
					$("#cheque_wrapper").show();
				} else {
					$("#cheque_wrapper").hide();
				}
			}else if(type == 'credit'){
				paymentCheque -= cash;
				credit_info += cash;

				if(paymentCheque > 0){
					$("#credit_wrapper").show();
				} else {
					$("#credit_wrapper").hide();
				}
			}else if(type == 'taxwithheld'){
				paymentTaxwithheld -= cash;

				if(paymentTaxwithheld > 0){
					$("#taxwithheld_wrapper").show();
				} else {
					$("#taxwithheld_wrapper").hide();
				}
			}else {
				paymentCreditcard -= cash;
				if(paymentCreditcard > 0){
					$("#creditcard_wrapper").show();
				} else {
					$("#creditcard_wrapper").hide();
				}
			}
		}

		total_payment -= cash;

		$("#total_payment_span").html(number_format(total_payment,2,'.',','));
		$("#total_payment").val(total_payment);

		$("#added_row_"+id).remove();
	}
	function computeOverpayment(cash){
		var payment_total 	    =  parseFloat((!isNaN($("#total_payment").val().replace(/,/g,''))) ? parseFloat($('#total_payment').val().replace(/,/g,'')) : 0 );
		var remaining 	        =  parseFloat((!isNaN($("#remaining_balance_val").val().replace(/,/g,''))) ? parseFloat($('#remaining_balance_val').val().replace(/,/g,'')) : 0 );
		
		var overpayment_amt  = (payment_total - remaining - cash);
		if(overpayment_amt <= 0){
			overpayment_amt = '0.00';
			$("#overpayment_wrapper").hide();
		}
		$("#total_overpayment_span").html(number_format(overpayment_amt,2,'.',','));
		$("#total_overpayment").val(overpayment_amt);

	}

	function computeTotalAmountPaid(){
		var amount_due_us = number_format($("#total_amount_due").val(), 2, '.', '');
		var total_amount_paid = number_format($("#total_amount_paid").val(), 2, '.', '');
		var remaining_balance = amount_due_us - total_amount_paid;
		$("#remaining_balance").html(addCommas(remaining_balance.toFixed(2)));
		$("#remaining_balance_val").val(remaining_balance.toFixed(2));
	}
</script>