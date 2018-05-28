<div class="modal-dialog"> 
	<!--  style="width:60%; word-wrap:normal;" -->
	<script>
		$(function(){
			$("#void_form").ajaxForm({
		        success: function(o) {
		      		if(o.is_successful) {
		      			$("#void_form_wrapper").modal("hide");
		      			loadAddReturnsForm(o.invoice_id);
		      		}
		        },
		        
		        dataType : "json"
		    });

		    $(".select_btn").on('click', function(){
				$("#void_form").submit();
		    });

		    $("#invoice_list").on('change', function(){
				var invoice_list = $("#invoice_list").val();
				$.post(base_url + 'returns_management/checkReturn',{invoice_list:invoice_list},function(o) {
					$("#result_wrapper").html(o);
					
				});
		    });
		});
	</script>
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>Returns</h3>
		</div>
		<div class="modal-body">
			<form id="void_form" method="post" action="<?php echo url('returns_management/submitModalForm'); ?>" style="width: 100%;">
				<script>
					$(function() {
						// var opts=$("#invoice_source").html(), opts2="<option></option>"+opts;
					    // $("#invoice_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
					    $("#invoice_list").select2({
								placeholder: "Select Invoice",
								ajax: {
									url: base_url + "returns_management/LoadReturnsModalForm",
									type: 'POST',
									dataType: 'json',
									delay: 250,
									data: function(term, page) {
										console.log(arguments, 'hey');
										return {
											q: term,
											return_type: 'json',
											page: page
										}
									},
									results: function(data, page) {
										console.log(page);
										return {
												results: data.invoice,
												more: (page * 10) < data.total
										};
									}
								},
								cache: true
							});
					});
				</script>
				<div class="clear"></div>
				<div id= "result_wrapper" style="color:red;"></div>
				<br/>
				<ul id="form02">
					<li>Invoice No: </li>
					<li>
					<input type="hidden" name="invoice_id" id="invoice_list" class="populate add_regiment_general_form" style="with: 200px;">
					<!-- <select id="invoice_list" name="invoice_id" class="populate add_regimen_general_form" style="width:200px;"></select> -->
					<?php /* ?><select id="invoice_source" class="validate[required]" style="display:none">
                        <option value="0">Select Invoice</option>
                          <?php foreach ($invoice as $key => $value): ?>
                            <option value="<?php echo $value['id']; ?>"><?php echo $value['invoice_num']; ?></option>
                          <?php endforeach; ?>
                    </select>
                    <?php */?>
					</li>
				</ul>
				<div class="clear"></div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger select_btn">Submit</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		</div>
	</div>
</div>