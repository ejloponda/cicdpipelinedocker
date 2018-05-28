<div class="modal-dialog"> 
	<!--  style="width:60%; word-wrap:normal;" -->
	<script>
		$(function(){
			$("#void_form").ajaxForm({
		        success: function(o) {
		      		if(o.is_successful) {
		      			$("#void_form_wrapper").modal("hide");
		      			viewReturns(o.returns_id);
		      			$.unblockUI();
		      		}
		        },
		        beforeSubmit : function(o){
		         	$.blockUI({
					    baseZ: 2000, 
					});
					
		         },
		        
		        dataType : "json"
		    });

		    $(".select_btn").on('click', function(){
				$("#void_form").submit();
		    });
		});
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Accept / Decline Returns</h3>
		</div>
		<div class="modal-body">
			<form id="void_form" method="post" action="<?php echo url('returns_management/saveAcceptDeclineReturns') ?>" style="width: 100%;">
				<input type="hidden" name="returns_id" value="<?php echo $returns_id ?>">
				<div class="clear"></div>
				<ul id="form02">
					<li>Option</li>
					<li>
						<input type="radio" class="type" name="type" value="Accepted" checked> <label for="r1"><span></span> <i class="glyphicon glyphicon-thumbs-up"></i> Accepted</label>
						<input type="radio" class="type" name="type" value="Declined"> <label for="r2"><span></span> <i class="glyphicon glyphicon-thumbs-down"></i> Declined</label>
					</li>
				</ul>
				<div class="clear"></div>
				<ul id="form02">
					<li>Remarks</li>
					<li><textarea name="remarks" style="max-width:363px; width:363px; max-height: 87px; height: 87px;"></textarea></li>
				</ul>
				<div class="clear"></div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger select_btn">Save</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		</div>
	</div>
</div>