<div class="modal-dialog"> 
	<!--  style="width:60%; word-wrap:normal;" -->
	<script>
		$(function(){
			$("#void_form").ajaxForm({
		        success: function(o) {
		      		if(o.is_successful) {
		      			$("#void_form_wrapper").modal("hide");
		      			loadIndex();
		      			//viewInvoice(o.invoice_id);
		      		}
		        },
		        
		        dataType : "json"
		    });

		    $(".select_btn").on('click', function(){
				$("#void_form").submit();
		    }); 
		   
		    $(".void_update_btn").on('click', function(){
		    	var invoice_id = 	$('#invoice_id').val();
				var remarks = 	$('#remarks').val();
				void2(invoice_id,remarks);
		    }); 
		});

		function void2(invoice_id,remarks){
			$.post(base_url + "returns_management/void_updateInvoice", {invoice_id:invoice_id, remarks:remarks}, function(o){
			});
			$("#void_form_wrapper").modal("hide");
		      loadIndex();
		}
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Void : <?php echo $invoice['invoice_internal'] ?></h3>
		</div>
		<div class="modal-body">
			<form id="void_form" method="post" action="<?php echo url('returns_management/voidInvoice') ?>" style="width: 100%;">
				<p>You are about to void this invoice, all medicines will be returned to inventory. Do you want to proceed?</p>
				<input type="hidden" name="invoice_id" id="invoice_id" value="<?php echo $invoice_id ?>">
				<div class="clear"></div>
				<ul id="form02">
					<li>Remarks</li>
					<li><textarea name="remarks" id= remarks style="max-width:363px; width:363px; max-height: 87px; height: 87px; border: 1px solid #cccccc !important; margin: 10px 0px !important; border-radius: 0px !important; resize: none !important; padding: 10px !important;"></textarea></li>
				</ul>
				<div class="clear"></div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-warning void_update_btn">Void and Update Order</button>
			<button type="button" class="btn btn-danger select_btn">Void</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		</div>
	</div>
</div>