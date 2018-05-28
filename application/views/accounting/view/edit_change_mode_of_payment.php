<style>
.datepicker{z-index:1151;}
</style>
<div class="modal-dialog"> 
	<!--  style="width:60%; word-wrap:normal;" -->
	<script>
	$(".creditbalance_form").hide();
	
	var patient_credit = parseFloat($("#patient_credit").val().replace(/\-|,/g,''));
	var total_amount_paid = parseFloat($("#total_amount_paid").val().replace(/\-|,/g,''));
	
	HideAllPayment();

	function HideAllPayment(){
		$(".cheque_form").hide();
		$(".cash_form").hide();
		$(".credit_card_form").hide();
		$(".credit_form").hide();
		$(".taxwithheld_form").hide();

		$(".cash_amount").hide();
		$(".cc_amount").hide();
		$(".cheque_amount").hide();
		$(".credit_amount").hide();
		$(".taxwithheld_amount").hide();
	}

	function HideCurrentPayment(){
		$(".cash_amount").hide();
		$(".cc_amount").hide();
		$(".cheque_amount").hide();
		$(".credit_amount").hide();
		$(".taxwithheld_amount").hide();
	}
	var or_id = $("#or_id").val();
		$(function(){
			$("#or_form1").ajaxForm({
		        success: function(o) {
		      		if(o.is_successful) {
		      			$("#change_mode_of_payment").modal("hide");
		      			viewCollection(or_id);
		      			send_notif(o.notif_message,o.notif_title,o.notif_type);
      					default_success_confirmation({message : o.message, alert_type: "alert-success"});
		      		} else {
		      			$("#result_wrapper").html(o.error);
		      			//default_success_confirmation({message : o.message, alert_type: "alert-error"});
		      		}
		      		//$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
		        },
		        beforeSubmit : function(evt){
	         		//$.blockUI();

	         		
	        },
			        
		        dataType : "json"
		    });

		    $(".select_btn").on('click', function(){
				$("#or_form1").submit();
		    });
		});

		function modeofpayment(){
			var mode_payment = $( "#mode" ).val();
			var current_mode_payment =  $( "#current_mode_payment" ).val();
			$(".creditbalance_form").hide();

			ShowHidePayment(mode_payment);
			ShowHideCurrentPayment(current_mode_payment);

			if(mode_payment == 'Credit'){
					$(".creditbalance_form").show();
					$("#result_wrapper").show();
					
				if(total_amount_paid > patient_credit){
         			//alert("Warning: You don't have enough credit.");
         			var confirm = new jBox('Modal', {
						content: '<h3>Warning: You dont have enough credit.</h3>',
						confirmButton: 'OK',
						cancelButton: 'close',
						animation: {open: 'tada', close: 'pulse'}
						});
						confirm.open();
						 $('#mode').prop('selectedIndex','select');
         		}		
			}	else {
				$("#result_wrapper").hide();
			}
		}

		function ShowHidePayment(mode_payment){
			switch(mode_payment){
				case 'Cash':
					HideAllPayment();
					$('.form_reset').val("");
					break;
				case 'Cheque':
					HideAllPayment();
					$(".cheque_form").show();
					$('.form_reset').val("");
					break;
				case 'Credit Card':
					HideAllPayment();
					$(".credit_card_form").show();
					$('.form_reset').val("");
					break;
				case 'Credit':
					HideAllPayment();
					$('.form_reset').val("");
					break;
				case 'Tax Withheld':
					HideAllPayment();
					$('.form_reset').val("");
					break;
				}
		}

		function ShowHideCurrentPayment(current_mode_payment){
			switch(current_mode_payment){
				case 'Cash':
					HideCurrentPayment();
					$(".cash_amount").show();
					break;
				case 'Cheque':
					HideCurrentPayment();
					$(".cheque_amount").show();
					break;
				case 'Credit Card':
					HideCurrentPayment();
					$(".cc_amount").show();
					break;
				case 'Credit':
					HideCurrentPayment();
					$(".credit_amount").show();
					break;
				case 'Tax Withheld':
					HideCurrentPayment();
					$(".taxwithheld_amount").show();
					break;
				}
		}
	
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Edit Collection</h3>
		</div>
		<div class="modal-body" style="">
		<form id="or_form1" action="<?php echo url('account_billing/update_receipt') ?>" method="post" style="width:auto;">
		<input type="hidden" id="or_id" value="<?php echo $or_id?>"></input>
		<input type="hidden" name="invoice_id" id="invoice_id" value="<?php echo $invoice['id']?>"></input>
			<script>
				$(function() {
					var opts=$("#invoice_source").html(), opts2="<option></option>"+opts;
				    $("#invoice_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
				    $("#invoice_list").select2({allowClear: true});
				});

				$('#cheque_date').datepicker({
						format: 'yyyy-mm-dd'
				});
			</script>
			<center><span id="result_wrapper" style="font-weight: bold; font-size: 14px; color: red"></span></center>
			<div class="clear"></div>
			<span>Current Mode of Payment: <input type="hidden" name="current_mode_payment" id="current_mode_payment" value="<?php echo $mode_of_payment?>" style="width: 100px;"> <span style="font-weight: bold; color: black;"> <?php echo $mode_of_payment?> </span> into </span>

			<select id="mode" name="new_mode_payment" onchange="modeofpayment();">
				<option value="select">-Select mode of payment-</option>
				<?php if($mode_of_payment != 'Cash'){?>
				<option value="Cash">Cash</option>
				<?php } ?>
				<?php if($mode_of_payment != 'Cheque'){?>
				<option value="Cheque">Cheque</option>
				<?php } ?>
				<?php if($mode_of_payment != 'Credit Card'){?>
				<option value="Credit Card">Credit Card</option>
				<?php } ?>
				<?php if($mode_of_payment != 'Credit'){?>
				<option value="Credit">Credit</option>
				<?php } ?>
				<?php if($mode_of_payment != 'Tax Withheld'){?>
				<option value="Tax Withheld">Tax Withheld</option>	
				<?php } ?>
			</select>
			<input type="hidden" name="total_amount_paid" value="<?php echo $amount_paid?>" id = "total_amount_paid">
			<input type="hidden" name="patient_credit" id="patient_credit" value="<?php echo $patient['credit']?>">
			<input type="hidden" name="patient_id" value="<?php echo $patient['id']?>">
			<div class="creditbalance_form">
			<p style="font-weight: bold;"><span>Credit Balance: &#8369;<span> <?php echo $patient['credit'] = 0 ? '0.00' :  number_format($patient['credit'] , 2, '.', ',');?> </span></span></p>

			</div>
<!-- FORMS -->
			<div class="cheque_form edit_collection">
					<ul class="pay">
						<li><span>Bank Name</span><br><input type="text" class="form_reset" name="cheque_bank_name" id="cheque_bank_name"></li>
						<li> <span>	Cheque Number</span><br><input type="text" class="form_reset " name="cheque_number" id="cheque_number"></li>
						<li><span>Cheque Date</span><br><input type="text" class="form_reset" name="cheque_date" id="cheque_date"></li>
				</ul>
			
			</div>

		<div class="credit_card_form edit_collection">
			<ul class="pay">
				<li><span>Bank Name</span><br><input type="text" class="form_reset" name="cc_bank_name" id="cc_bank_name"></li>
				<li><span>Card Type</span><br><input type="text" class="form_reset" name="cc_type" id="cc_type"></li>
			</ul>
		</div>

<!-- FOR AMOUNT PAID  -->
			<div class="cash_amount">
				<input type="hidden" name="id_cash" value="<?php echo $cash['id']?>">
				<input type="hidden" name="or_id_cash" value="<?php echo $cash['or_id']?>">
				<div class="form02">
					<div class="payment">
						 <p class="payment-method"><span>Amound Paid: </span><?php echo number_format($cash['price'] , 2, '.', ',')?></p>
						<input type="hidden" class="figure" name="amount_paid_cash" id="amount_paid" value="<?php echo number_format($cash['price'] , 2, '.', ',')?>">
					</div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="cheque_amount">
				<input type="hidden" name="id_cheque" value="<?php echo $cheque['id']?>">
				<input type="hidden" name="or_id_cheque" value="<?php echo $cheque['or_id']?>">
				<div class="form02">
					<div class="payment">
						 <p class="payment-method"><span>Amound Paid: </span><?php echo $cheque['price'];?></p>
						<input type="hidden" class="figure" name="amount_paid_cheque" id="amount_paid" value="<?php echo $cheque['price'];?>">
					</div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="cc_amount">
			<input type="hidden" name="id_cc" value="<?php echo $cc['id']?>">
			<input type="hidden" name="or_id_cc" value="<?php echo $cc['or_id']?>">
				<div class="form02">
					<div class="payment">
						<p class="payment-method"><span>Amound Paid: </span><?php echo $cc['price'];?></p>
						<input type="hidden" class="figure" name="amount_paid_cc" id="amount_paid" value="<?php echo $cc['price'];?>">
					</div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="credit_amount">
			<input type="hidden" name="id_credit" value="<?php echo $credit['id']?>">
		    <input type="hidden" name="or_id_credit" value="<?php echo $credit['or_id']?>">
				<div class="form02">
					<div class="payment">
						<p class="payment-method"><span>Amound Paid: </span><?php echo $credit['price'];?></p>
						<input type="hidden" class="figure" name="amount_paid_credit" id="amount_paid" value="<?php echo $credit['price'];?>">
					</div>

				</div>
				<div class="clear"></div>
			</div>

			<div class="taxwithheld_amount">
			<input type="hidden" name="id_taxwithheld" value="<?php echo $taxwithheld['id']?>">
			<input type="hidden" name="or_id_taxwithheld" value="<?php echo $taxwithheld['or_id']?>">
				<div class="form02">
					<div class="payment">
						<p class="payment-method"><span>Amound Paid: </span><?php echo $taxwithheld['price'];?></p>
						<input type="hidden" class="figure" name="amount_paid_taxwithheld" id="amount_paid" value="<?php echo $taxwithheld['price'];?>">
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary select_btn">Save</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		</div>
	</div>
</div>


