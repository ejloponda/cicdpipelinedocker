<div class="modal-dialog"> 
	<!--  style="width:60%; word-wrap:normal;" -->
	<script>
		$(function(){
			$('.figure').on('change',function() {
				var value = $(this).val();
				var formatted = number_format(value,2,'.',',');
				$(this).val(formatted);
			});

			$(".btn-close-modal").on('click', function(){
				view_patient("<?php echo $patient_id ?>");
			})
			var patient_id = $("#patient_id").val();

			loadCreditsHistory(patient_id);
			loadCredits(patient_id);
		});


		function manageCredit(type){
			var credit 	= parseFloat($("#credit").val().replace(/,/g,''));
			var remarks = $("#remarks").val();
			var patient_id = $("#patient_id").val();

			if(hasValue(credit) && hasValue(remarks)){
				$.post(base_url + "patient_management/manageCredit", {patient_id:patient_id, credit:credit, remarks:remarks, type:type}, function(o){
					loadCreditsHistory(patient_id);
					loadCredits(patient_id);
					$("#credit").val("0.00");
					$("#remarks").val("");
					$("#result_wrapper").html(o);
				});
			} else {
				if(!hasValue(credit) && !hasValue(remarks)){
					alert("Enter how much credit and your remark.");
				}else if(hasValue(credit) && !hasValue(remarks)){
					alert("Enter your remark.");
				}else if(!hasValue(credit) && hasValue(remarks)){
					alert("Enter how much credit");
				}
			}
		}

		function loadCreditsHistory(patient_id){
			$.post(base_url + "patient_management/loadCreditsHistory", {patient_id:patient_id}, function(o){
					$("#credit_history_wrapper").html(o);
				});
		}

		function loadCredits(patient_id){
			$.post(base_url + "patient_management/loadCredits", {patient_id:patient_id}, function(o){
					$("#my_credit").html(o);
				});
		}
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<h3>Credit System</h3>
		</div>
		<div class="modal-body">
			<div role="tabpanel">

			 	 <!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Credits</a></li>
					<?php if($cr['can_add'] || $cr['can_update']){ ?>
					<li role="presentation"><a href="#manage_credit" aria-controls="manage_credit" role="tab" data-toggle="tab">Manage Credits</a></li>
					<?php } ?>
					<li role="presentation"><a href="#manage_credit_history" aria-controls="manage_credit_history" role="tab" data-toggle="tab">Credits History</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="home">
						<br/>
						<center><h1>Credits: <span id="my_credit"></span></h1></center>
					</div>
					<div role="tabpanel" class="tab-pane" id="manage_credit">
						<br/>
						<input type="hidden" id="patient_id" value="<?php echo $patient_id ?>">
						<center>
							<div id="result_wrapper"></div>
							<input type="text" id="credit" placeholder="Credit" class="textbox figure" value="0.00" style="width: 200px;">
							<div class="clear"></div>
							<textarea placeholder="Remarks" id="remarks" class="form-control" style="min-width: 200px;max-width: 200px; height:70px;"></textarea>
							<div class="clear"></div>
							<button type="button" onclick="manageCredit('add')" class="btn btn-success btn-xs">ADD</button>	
							<button type="button" onclick="manageCredit('less')" class="btn btn-danger btn-xs">LESS</button>	
						</center>
					</div>
					<div role="tabpanel" class="tab-pane" id="manage_credit_history">
						<br/>
						<div id="credit_history_wrapper"></div>
					</div>
				</div>

			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>