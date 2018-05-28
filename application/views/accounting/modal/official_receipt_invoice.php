<div class="modal-dialog"> 
	<!--  style="width:60%; word-wrap:normal;" -->
	<script>
		$(function(){
			$("#or_form").ajaxForm({
		        success: function(o) {
		      		if(o.is_successful) {
		      			$("#official_receipt_wrapper").modal("hide");
		      			loadInvoiceORForm(o.invoice_id);
		      		} else {
		      			alert("Invoice ID is Invalid!");
		      		}
		        },
		        
		        dataType : "json"
		    });

		    $(".select_btn").on('click', function(){
				$("#or_form").submit();
		    });
		});
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Select Invoice</h3>
		</div>
		<div class="modal-body">
		<form id="or_form" method="post" action="<?php echo url('account_billing/loadORAddForm') ?>">
			<script>
				$(function() {
					var opts=$("#invoice_source").html(), opts2="<option></option>"+opts;
				    $("#invoice_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
				    $("#invoice_list").select2({allowClear: true});
				});
			</script>
			Invoice No: 
			<select id="invoice_list" name="invoice_id" class="populate add_regimen_general_form" style="width:200px;"></select>
			<select id="invoice_source" class="validate[required]" style="display:none">
				<option value="0">Select Invoice</option>
			  <?php foreach($invoice as $key=>$value): ?>
			    <option value="<?php echo $value['id']; ?>"><?php echo $value['invoice_num'] ?></option>
			  <?php endforeach; ?>
			</select>
		</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary select_btn">Select</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		</div>
	</div>
</div>