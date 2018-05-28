<script>
	$(function() {
		var opts=$("#medicine_source").html(), opts2="<option></option>"+opts;
	    $("#medicine_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
	    $("#medicine_list").select2({allowClear: true});
	});
</script>
				<select id="medicine_list" name="medicine_id" title="Medicine Name" class="populate myTipsy new_order_form select-med" style="width: 300px;"></select>
				<select id="medicine_source" style="display:none" >
					<option value="0">- Select Medicine -</option>
				  		<?php 
				  			foreach($medicines as $a=>$b): 
				  			$dosage 	= Dosage_Type::findById(array("id" => $b['dosage_type']));
				  			$quantity2 	= Quantity_Type::findById(array("id" => $b['quantity_type']));
				  			$quantity   = Inventory_Batch::computeTotalQuantity(array("id" => $b['id']));
				  			$quantity 	= ($quantity ? $quantity : 0);
				  			$qty_per    = empty($b['quantity_per_bottle']) ? '0' : $b['quantity_per_bottle'];
				  		?>
				    			<option class="filter" value="<?php echo $b['id']; ?>" <?php echo ($d['medicine_name'] == $b['id'] ? "selected" : "") ?> data-stock="<?php echo $b['stock'] ?>" ><?php echo $b['medicine_name'] ." (". $qty_per . "/bottle)  - " . $b['dosage'] . " " . $dosage['abbreviation'] . " / " . $quantity . " " . $quantity2['abbreviation']; ?></option>
					  	<?php endforeach; ?>
				</select>
				<br><br>
				<input type="hidden" name="pharma" value="<?php echo $choice ?>" id="pharma">
				<script>
					

					if($("#pharma").val() == 'RPP'){
						$(".othr_chrgs").hide();
					}
					
					$("select[id='medicine_list']").live('change', function(){
						var med_id = $( "#medicine_list option:selected" ).attr("value");
						check_stock(med_id);
					});

					function check_stock(med_id){
						if (med_id > 0) {
							$.post(base_url + "inventory_management/check_stock",{med_id:med_id},function(o){
								if(o.value == 0 || o.value < 0){
									alert(o.message);
									console.log(o.message);
								}
							},"json");
						}
						//$('#medicine_source').val('-Select Medicine-');
						
					}
				</script>
