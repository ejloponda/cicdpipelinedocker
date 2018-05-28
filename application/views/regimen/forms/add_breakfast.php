<?php if($breakfast){ ?>
	<?php 
		$count = 10;
		$index = 20;
		foreach ($breakfast as $key => $value) {
			if(!empty($breakfast[$key])){
			$div_wrapper 		 = "edit_breakfast_wrapper_{$count}";
			$activity 	 		 = "breakfast[{$count}][activity]";
		
	?>
	<div class='line03 <?php echo $div_wrapper ?>' style='width:625px;'></div>
	<section class='clear <?php echo $div_wrapper ?>' ></section>
		<ul id='form' class='<?php echo $div_wrapper ?>'>
			<li>Activity: </li>
			<li><input type='text' id='<?php echo $activity ?>' name='<?php echo $activity ?>' class='textbox' style='width: 250px;' value="<?php echo $value['activity'] ?>" >&nbsp;&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-success' onclick='javascript: createSubBreakfastFields(<?php echo $count ?>);' style='height: 20px;'><i class='glyphicon glyphicon-plus'></i></a></li>
		</ul>
	<section class='clear <?php echo $div_wrapper ?>'></section>
	<?php

		foreach ($value['med_ops'] as $c => $d) {
			if(!empty($value["med_ops"][$c])){
				$medicine_source 	 = "medicine_source_{$index}";
				$medicine_list 	 	 = "eb_medicine_list_{$count}_{$index}";

				$medicine_name 	 	 = "breakfast[{$count}][med_ops][{$index}][medicine_name]";
				$quantity 		 	 = "breakfast[{$count}][med_ops][{$index}][quantity]";

				$quantity_type 		 = "breakfast[{$count}][med_ops][{$index}][quantity_type]";
				$taken_as 		 	 = "eb_taken_as_{$count}_{$index}";
				$dosage_val 	 	 = "eb_dosage_val_{$count}_{$index}";

				$others 		 	 = "eb_others_{$count}_{$index}";
				$quantity_val_count  = "eb_quantity_val_{$count}_{$index}";
				$quantity_val  		 = "breakfast[{$count}][med_ops][{$index}][quantity_val]";

				$sub_wrapper 		 = "breakfast_sub_wrapper_{$index}";
	?>
	<div class="<?php echo $sub_wrapper ?>">
		<ul id="form" class="<?php echo $div_wrapper ?>">
			<li>Medicine Name: <span>*</span></li>
			<li>
				<script>
					$(function() {
						var opts=$("#<?php echo $medicine_source ?>").html(), opts2="<option></option>"+opts;
					    $("#<?php echo $medicine_list ?>").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
					    $("#<?php echo $medicine_list ?>").select2({allowClear: true});
					});
				</script>
				<select id="<?php echo $medicine_list ?>" name="<?php echo $medicine_name ?>" class="populate add_returns_trigger" style="width:250px;"></select>
				<select id="<?php echo $medicine_source ?>" class="validate[required]" style="display:none">
					<option value="0">-Select-</option>
				  <?php 
				  		foreach($medicines as $a=>$b): 
				  			$dosage 	= Dosage_Type::findById(array("id" => $b['dosage_type']));
				  			$quantity2 	= Quantity_Type::findById(array("id" => $b['quantity_type']));
				  ?>
				    		<option value="<?php echo $b['id']; ?>" <?php echo ($d['medicine_name'] == $b['id'] ? "selected" : "") ?>><?php echo $b['medicine_name'] . " " . $b['dosage'] . " " . $dosage['abbreviation'] . " - " . $b['remaining'] . " " . $quantity2['abbreviation']; ?></option>
				  <?php endforeach; ?>
				</select>

				<script>
					$(function(){ getMedicineQuantityType('<?php echo $count ?>', '<?php echo $index ?>', 'eb'); });
					$('#<?php echo $medicine_list ?>').on('change', function(){ getMedicineQuantityType('<?php echo $count ?>','<?php echo $index ?>', 'eb'); });
					$('#<?php echo $taken_as ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').val(''); });
					$('#<?php echo $others ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').focus(); });
					$('#<?php echo $quantity_val_count ?>').on('change', function() { $('#<?php echo $others ?>').attr('checked',true); });
				</script>
				&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-danger' onclick='javascript: deleteElementRow("<?php echo $sub_wrapper ?>", <?php echo $key ?>);' style='height: 20px;'><i class='glyphicon glyphicon-minus'></i></a>
			</li>				
		</ul>
		<section class="clear <?php echo $div_wrapper ?>"></section>
		<ul id="form" class="<?php echo $div_wrapper ?>">
			<li>Quantity: </li>
			<li><input type="text" id="<?php echo $quantity ?>" name="<?php echo $quantity ?>" class="textbox validate[custom[onlyNumberSp]]" style="width:110px;" value="<?php echo $d['quantity'] ?>">&nbsp;&nbsp;<label for='taken' style='padding-right: 14px; padding-left: 10px;'>Taken As: </label><input type='text' id='<?php echo $dosage_val ?>' class='textbox' style='width:50px;' value="<?php echo $quantity2['abbreviation'] ?>" readonly></li>
		</ul>
		<section class="clear <?php echo $div_wrapper ?>"></section>
		<ul id='form' class="<?php echo $div_wrapper ?>">
			<li></li>
			<li>
				<input type='checkbox' id='<?php echo $others ?>' name='<?php echo $quantity_type ?>' value='Others' style='float:left;margin-top:6px;' <?php echo ($d['quantity_type'] == "Others" ? "checked" : "") ?>><label for='others'style='padding-left: 5px; padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='<?php echo $quantity_val_count ?>' name='<?php echo $quantity_val ?>' style='width:80px;' value="<?php echo ($d['quantity_type'] == "Others" && $d['quantity_val'] ? $d['quantity_val'] : '') ?>">
			</li>
		</ul>
		<section class="clear <?php echo $div_wrapper ?>"></section>
	</div><!-- <button type="button" class="btn btn-xs btn-danger"><i class='glyphicon glyphicon-minus'></i> Return</button></button>  -->
		<?php
				$index++; 
			} 
		} 
		?>
			<div id='sub_bf_wrapper_<?php echo $count ?>' class='<?php echo $div_wrapper ?>'></div>
			<div id='sub_bf_wrapper_loader_<?php echo $count ?>' class='<?php echo $div_wrapper ?>'></div>
			<section class="clear <?php echo $div_wrapper ?>"></section>
			<button type="button" class="btn btn-xs btn-danger <?php echo $div_wrapper ?>" onclick="javascript: delSaveMedicine('<?php echo $div_wrapper ?>', <?php echo $key ?>, 'lunch')"><i class='glyphicon glyphicon-minus'></i> Delete</button></button> 
			<section class="clear <?php echo $div_wrapper ?>"></section>
	<?php 
			$count++; 
		} 
	} ?>
<?php } ?>