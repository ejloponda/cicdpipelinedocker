<style>
.datepicker{z-index:1151;}
</style>
<script>
	// $("#selector_tabs a[href='#<?php echo $tab_form ?>']").tab('show');
	CKEDITOR.replace( 'regimen_notes',{removePlugins: 'toolbar,elementspath',width:['810px'],height:['130px'], resize_enabled:false});
	//CKEDITOR.replace( 'version_remarks',{removePlugins: 'toolbar,elementspath',width:['810px'],height:['130px'], resize_enabled:false, autoParagraph:false});
	$(function(){
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		var start_date = $('#start_date').datepicker({
			format: 'yyyy-mm-dd',
		  onRender: function(date) {
		    return date.valueOf() < now.valueOf() ? 'disabled' : '';
		  }
		}).on('changeDate', function(ev) {
		  if (ev.date.valueOf() > expiration_date.date.valueOf()) {
		    var newDate = new Date(ev.date)
		    newDate.setDate(newDate.getDate() + 1);
		    expiration_date.setValue(newDate);
		  }
		  start_date.hide();
		  $('#expiration_date')[0].focus();
		}).data('datepicker');
		var expiration_date = $('#expiration_date').datepicker({
			format: 'yyyy-mm-dd',
		  onRender: function(date) {
		    return date.valueOf() <= start_date.date.valueOf() ? 'disabled' : '';
		  }
		}).on('changeDate', function(ev) {
		  expiration_date.hide();
		}).data('datepicker');


		$("#regimen_new_row_form").validationEngine({scroll:false});
		$('#regimen_new_row_form').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
					getMedicineTable(<?php echo $record['regimen_id']; ?>);
          			// default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}

				$('#medicine_modal_form').modal('hide');

    			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
				
			}, beforeSubmit: function(o) {
				$("#selector_tabs a[href='date_form']").tab('show');
			},
			dataType : "json"

		});

		$(".submit_button").on('click', function(event) {
			for ( instance in CKEDITOR.instances ) {
       		 CKEDITOR.instances[instance].updateElement();
   			}

   			$('#regimen_new_row_form').submit();
		});

	});
</script>
<style type="text/css">
.modal-body {overflow-y:scroll; max-height: 500px;}
</style>
<div class="modal-dialog" style="width:50%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <img src="<?php echo BASE_FOLDER; ?>themes/images/header-regimen.png"> <strong><span style="color: black; font-size: 22px;">Edit Regimen</span></strong>
		</div>
		<div class="modal-body">
			<form id="regimen_new_row_form" name="regimen_new_row_form" method="post" action="<?php echo url('regimen_management/addNewRowMedicine'); ?>" style="width:100%:">
				<input type="hidden" id="row_id" name="row_id" value="<?php echo $record['id']; ?>">
				<input type="hidden" id="regimen_id" name="regimen_id" value="<?php echo $record['regimen_id']; ?>">
		        <ul class="nav nav-tabs" id="selector_tabs" style="width: 627px;">
				  <li class="active"><a href="#date_form" data-toggle="tab">Date</a></li>
				  <li><a href="#breakfast_form" data-toggle="tab">Breakfast</a></li>
				  <li><a href="#lunch_form" data-toggle="tab">Lunch</a></li>
				  <li><a href="#dinner_form" data-toggle="tab">Dinner</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content" style="width: 627px;">
					<div class="tab-pane active" id="date_form">
						<br/>
						<!-- <input type="hidden" name="reg_id" id="reg_id" value="<?php echo $reg_id ?>"> -->
						<ul id="form">
							<li>Start Date</li>
							<li><input type="text" id="start_date" name="start_date" class="textbox validate[required]" value="<?php echo $record['start_date'] ?>"></li>
						</ul>

						<section class="clear"></section>

						<ul id="form">
							<li>End Date</li>
							<li><input type="text" id="expiration_date" name="expiration_date" class="textbox validate[required]" value="<?php echo $record['end_date'] ?>"></li>
						</ul>
					</div>


					<div class="tab-pane" id="breakfast_form">
						<br/>
						<div style="width: 100%;height: 30px;">
							<button type="button" onclick="javascript: addBreakfastRow();" class="btn btn-primary btn-sm" style="float:right;"><i class="glyphicon glyphicon-plus"></i> Add More</button>	
						</div><br/>
						<?php 
								$count = 0;

								foreach ($bf as $key => $value) {
									$div_wrapper 		 = "edit_breakfast_wrapper_{$count}";
									$medicine_source 	 = "medicine_source{$count}";
									$medicine_list 	 	 = "eb_medicine_list_{$count}";
									$id 			 	 = "edit_breakfast[{$count}][id]";
									$medicine_name 	 	 = "edit_breakfast[{$count}][medicine_name]";
									$quantity 		 	 = "edit_breakfast[{$count}][quantity]";

									$quantity_type 		 = "edit_breakfast[{$count}][quantity_type]";
									$taken_as 		 	 = "eb_taken_as_{$count}";
									$dosage_val 	 	 = "eb_dosage_val_{$count}";

									$others 		 	 = "eb_others_{$count}";
									$quantity_val_count  = "eb_quantity_val_{$count}";
									$quantity_val  		 = "edit_breakfast[{$count}][quantity_val]";

									$activity 	 		 = "edit_breakfast[{$count}][activity]";
								
							 ?>
						<div class='line03 <?php echo $div_wrapper ?>' style='width:625px;'></div>
						<section class='clear <?php echo $div_wrapper ?>' ></section>
							<ul id='form' class='<?php echo $div_wrapper ?>'>
								<li>Activity: </li>
								<li><input type='text' id='<?php echo $activity ?>' name='<?php echo $activity ?>' class='textbox' style='width: 250px;' value="<?php echo $value['activity'] ?>" ></li>
							</ul>
						<section class='clear <?php echo $div_wrapper ?>'></section>	 
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
								<script>
									$('#<?php echo $medicine_list ?>').on('change', function(){ getMedicineQuantityType("<?php echo $count ?>", 'eb'); });
									$('#<?php echo $taken_as ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').val(''); });
									$('#<?php echo $others ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').focus(); });
									$('#<?php echo $quantity_val_count ?>').on('change', function() { $('#<?php echo $others ?>').attr('checked',true); });
								</script>
								<select id="<?php echo $medicine_list ?>" name="<?php echo $medicine_name ?>" class="populate add_returns_trigger" style="width:250px;"></select>
								<select id="<?php echo $medicine_source ?>" class="validate[required]" style="display:none">
									<option value="0">-Select-</option>
								  <?php 
								  		foreach($medicines as $a=>$b): 
								  			$dosage 	= Dosage_Type::findById(array("id" => $b['dosage_type']));
								  			$quantity2 	= Quantity_Type::findById(array("id" => $b['quantity_type']));
								  ?>
								    		<option value="<?php echo $b['id']; ?>" <?php echo ($value['medicine_id'] == $b['id'] ? "selected" : "") ?>><?php echo $b['medicine_name'] . " " . $b['dosage'] . " " . $dosage['abbreviation'] . " - " . $b['remaining'] . " " . $quantity2['abbreviation']; ?></option>
								  <?php endforeach; ?>
								</select>
								<input type="hidden" name="<?php echo $id ?>" value="<?php echo $value['id'] ?>">
							</li>				
						</ul>
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<ul id="form" class="<?php echo $div_wrapper ?>">
							<li>Quantity: </li>
							<li><input type="text" id="<?php echo $quantity ?>" name="<?php echo $quantity ?>" class="textbox validate[custom[onlyNumberSp]]" style="width:110px;" value="<?php echo $value['quantity'] ?>"></li>
						</ul>
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<ul id='form' class="<?php echo $div_wrapper ?>">
							<li></li>
							<li>
								<input type='radio' id='<?php echo $taken_as ?>' name='<?php echo $quantity_type ?>' value='Taken As' <?php echo ($value['quantity_type'] == "Taken As" ? "checked" : "") ?>><label for='taken'>Taken As: </label><input type='text' id='<?php echo $dosage_val ?>' class='textbox' style='width:50px;' value="<?php echo $quantity2['abbreviation'] ?>" readonly><br/><br/>
								<input type='radio' id='<?php echo $others ?>' name='<?php echo $quantity_type ?>' value='Others' <?php echo ($value['quantity_type'] == "Others" ? "checked" : "") ?>><label for='others' style='padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='<?php echo $quantity_val_count ?>' name='<?php echo $quantity_val ?>' style='width:80px;' value="<?php echo ($value['quantity_type'] == "Others" && $value['quantity_val'] ? $value['quantity_val'] : '') ?>">
							</li>
						</ul>
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<button type="button" class="btn btn-xs btn-danger <?php echo $div_wrapper ?>" onclick="javascript: delSaveMedicine('<?php echo $div_wrapper ?>', <?php echo  $value['id'] ?>)"><i class='glyphicon glyphicon-minus'></i> Delete</button></button> 
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<!-- <button type="button" class="btn btn-xs btn-danger"><i class='glyphicon glyphicon-minus'></i> Return</button></button>  -->
						<?php $count++; } ?>
						<div id="loading_wrapper"></div>
						<div id="add_breakfast_wrapper"></div>
						<div class="line02" style="width:625px;"></div>
						<div class="clear"></div>
						<ul id='form'>
							<li>Special Instructions: </li>
							<li><textarea type='text' name='bf_special_instructions' id='bf_special_instructions' style='max-width: 370px;min-width: 370px; height: 50px;'><?php echo $record['bf_instructions'] ?></textarea></li>
						</ul>
					</div>


					<div class="tab-pane" id="lunch_form">
						<br/>
						<div style="width: 100%;height: 30px;">
							<button type="button" onclick="javascript: addLunchRow();" class="btn btn-primary btn-sm" style="float:right;"><i class="glyphicon glyphicon-plus"></i> Add More</button>	
						</div><br/>
						
						<?php 
								$count = 0;

								foreach ($lunch as $key => $value) {
									$div_wrapper 		 = "edit_lunch_wrapper_{$count}";
									$medicine_source 	 = "medicine_source_{$count}";
									$medicine_list 	 	 = "el_medicine_list_{$count}";
									$id 			 	 = "edit_lunch[{$count}][id]";
									$medicine_name 	 	 = "edit_lunch[{$count}][medicine_name]";
									$quantity 		 	 = "edit_lunch[{$count}][quantity]";

									$quantity_type 		 = "edit_lunch[{$count}][quantity_type]";
									$taken_as 		 	 = "el_taken_as_{$count}";
									$dosage_val 	 	 = "el_dosage_val_{$count}";

									$others 		 	 = "el_others_{$count}";
									$quantity_val_count  = "el_quantity_val_{$count}";
									$quantity_val  		 = "edit_lunch[{$count}][quantity_val]";

									$activity 	 		 = "edit_lunch[{$count}][activity]";
								
						?>
						<div class='line03 <?php echo $div_wrapper ?>' style='width:625px;'></div>
						<section class='clear <?php echo $div_wrapper ?>' ></section>
							<ul id='form' class='<?php echo $div_wrapper ?>'>
								<li>Activity: </li>
								<li><input type='text' id='<?php echo $activity ?>' name='<?php echo $activity ?>' class='textbox' style='width: 250px;' value="<?php echo $value['activity'] ?>" ></li>
							</ul>
						<section class='clear <?php echo $div_wrapper ?>'></section>
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
								<script>
									$('#<?php echo $medicine_list ?>').on('change', function(){ getMedicineQuantityType("<?php echo $count ?>", 'eb'); });
									$('#<?php echo $taken_as ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').val(''); });
									$('#<?php echo $others ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').focus(); });
									$('#<?php echo $quantity_val_count ?>').on('change', function() { $('#<?php echo $others ?>').attr('checked',true); });
								</script>
								<select id="<?php echo $medicine_list ?>" name="<?php echo $medicine_name ?>" class="populate add_returns_trigger" style="width:250px;"></select>
								<select id="<?php echo $medicine_source ?>" class="validate[required]" style="display:none">
									<option value="0">-Select-</option>
								  <?php 
								  		foreach($medicines as $a=>$b): 
								  			$dosage 	= Dosage_Type::findById(array("id" => $b['dosage_type']));
								  			$quantity2 	= Quantity_Type::findById(array("id" => $b['quantity_type']));
								  ?>
								    		<option value="<?php echo $b['id']; ?>" <?php echo ($value['medicine_id'] == $b['id'] ? "selected" : "") ?>><?php echo $b['medicine_name'] . " " . $b['dosage'] . " " . $dosage['abbreviation'] . " - " . $b['remaining'] . " " . $quantity2['abbreviation']; ?></option>
								  <?php endforeach; ?>
								</select>
								<input type="hidden" name="<?php echo $id ?>" value="<?php echo $value['id'] ?>">
							</li>				
						</ul>
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<ul id="form" class="<?php echo $div_wrapper ?>">
							<li>Quantity: </li>
							<li><input type="text" id="<?php echo $quantity ?>" name="<?php echo $quantity ?>" class="textbox validate[custom[onlyNumberSp]]" style="width:110px;" value="<?php echo $value['quantity'] ?>"></li>
						</ul>
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<ul id='form' class="<?php echo $div_wrapper ?>">
							<li></li>
							<li>
								<input type='radio' id='<?php echo $taken_as ?>' name='<?php echo $quantity_type ?>' value='Taken As' <?php echo ($value['quantity_type'] == "Taken As" ? "checked" : "") ?>><label for='taken'>Taken As: </label><input type='text' id='<?php echo $dosage_val ?>' class='textbox' style='width:50px;' value="<?php echo $quantity2['abbreviation'] ?>" readonly><br/><br/>
								<input type='radio' id='<?php echo $others ?>' name='<?php echo $quantity_type ?>' value='Others' <?php echo ($value['quantity_type'] == "Others" ? "checked" : "") ?>><label for='others' style='padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='<?php echo $quantity_val_count ?>' name='<?php echo $quantity_val ?>' style='width:80px;' value="<?php echo ($value['quantity_type'] == "Others" && $value['quantity_val'] ? $value['quantity_val'] : '') ?>">
							</li>
						</ul>
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<button type="button" class="btn btn-xs btn-danger <?php echo $div_wrapper ?>" onclick="javascript: delSaveMedicine('<?php echo $div_wrapper ?>', <?php echo  $value['id'] ?>)"><i class='glyphicon glyphicon-minus'></i> Delete</button></button> 
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<?php $count++; } ?>
						<div id="loading_wrapper2"></div>
						<div id="add_lunch_wrapper"></div>
						<div class="line02" style="width:625px;"></div>
						<div class="clear"></div>
						<ul id='form'>
							<li>Special Instructions: </li>
							<li><textarea type='text' name='l_special_instructions' id='l_special_instructions' style='max-width: 370px;min-width: 370px; height: 50px;'><?php echo $record['l_instructions'] ?></textarea></li>
						</ul>

					</div>


					<div class="tab-pane" id="dinner_form">
						<br/>
						<div style="width: 100%;height: 30px;">
							<button type="button" onclick="javascript: addDinnerRow();" class="btn btn-primary btn-sm" style="float:right;"><i class="glyphicon glyphicon-plus"></i> Add More</button>	
						</div><br/>
						
						<?php 
								$count = 0;

								foreach ($dinner as $key => $value) {
									$div_wrapper 		 = "edit_dinner_wrapper_{$count}";
									$medicine_source 	 = "medicine_source1_{$count}";
									$medicine_list 	 	 = "ed_medicine_list_{$count}";
									$id 			 	 = "edit_dinner[{$count}][id]";
									$medicine_name 	 	 = "edit_dinner[{$count}][medicine_name]";
									$quantity 		 	 = "edit_dinner[{$count}][quantity]";

									$quantity_type 		 = "edit_dinner[{$count}][quantity_type]";
									$taken_as 		 	 = "ed_taken_as_{$count}";
									$dosage_val 	 	 = "ed_dosage_val_{$count}";

									$others 		 	 = "ed_others_{$count}";
									$quantity_val_count  = "ed_quantity_val_{$count}";
									$quantity_val  		 = "edit_dinner[{$count}][quantity_val]";

									$activity 	 		 = "edit_dinner[{$count}][activity]";
								
							 ?>
						<div class='line03 <?php echo $div_wrapper ?>' style='width:625px;'></div>
						<section class='clear <?php echo $div_wrapper ?>' ></section>
							<ul id='form' class='<?php echo $div_wrapper ?>'>
								<li>Activity: </li>
								<li><input type='text' id='<?php echo $activity ?>' name='<?php echo $activity ?>' class='textbox' style='width: 250px;' value="<?php echo $value['activity'] ?>" ></li>
							</ul>
						<section class='clear <?php echo $div_wrapper ?>'></section>
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
								<script>
									$('#<?php echo $medicine_list ?>').on('change', function(){ getMedicineQuantityType("<?php echo $count ?>", 'eb'); });
									$('#<?php echo $taken_as ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').val(''); });
									$('#<?php echo $others ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').focus(); });
									$('#<?php echo $quantity_val_count ?>').on('change', function() { $('#<?php echo $others ?>').attr('checked',true); });
								</script>
								<select id="<?php echo $medicine_list ?>" name="<?php echo $medicine_name ?>" class="populate add_returns_trigger" style="width:250px;"></select>
								<select id="<?php echo $medicine_source ?>" class="validate[required]" style="display:none">
									<option value="0">-Select-</option>
								  <?php 
								  		foreach($medicines as $a=>$b): 
								  			$dosage 	= Dosage_Type::findById(array("id" => $b['dosage_type']));
								  			$quantity2 	= Quantity_Type::findById(array("id" => $b['quantity_type']));
								  ?>
								    		<option value="<?php echo $b['id']; ?>" <?php echo ($value['medicine_id'] == $b['id'] ? "selected" : "") ?>><?php echo $b['medicine_name'] . " " . $b['dosage'] . " " . $dosage['abbreviation'] . " - " . $b['remaining'] . " " . $quantity2['abbreviation']; ?></option>
								  <?php endforeach; ?>
								</select>
								<input type="hidden" name="<?php echo $id ?>" value="<?php echo $value['id'] ?>">
							</li>				
						</ul>
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<ul id="form" class="<?php echo $div_wrapper ?>">
							<li>Quantity: </li>
							<li><input type="text" id="<?php echo $quantity ?>" name="<?php echo $quantity ?>" class="textbox validate[custom[onlyNumberSp]]" style="width:110px;" value="<?php echo $value['quantity'] ?>"></li>
						</ul>
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<ul id='form' class="<?php echo $div_wrapper ?>">
							<li></li>
							<li>
								<input type='radio' id='<?php echo $taken_as ?>' name='<?php echo $quantity_type ?>' value='Taken As' <?php echo ($value['quantity_type'] == "Taken As" ? "checked" : "") ?>><label for='taken'>Taken As: </label><input type='text' id='<?php echo $dosage_val ?>' class='textbox' style='width:50px;' value="<?php echo $quantity2['abbreviation'] ?>" readonly><br/><br/>
								<input type='radio' id='<?php echo $others ?>' name='<?php echo $quantity_type ?>' value='Others' <?php echo ($value['quantity_type'] == "Others" ? "checked" : "") ?>><label for='others' style='padding-right: 14px;'>Others: </label><input type='text' class='textbox' id='<?php echo $quantity_val_count ?>' name='<?php echo $quantity_val ?>' style='width:80px;' value="<?php echo ($value['quantity_type'] == "Others" && $value['quantity_val'] ? $value['quantity_val'] : '') ?>">
							</li>
						</ul>
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<button type="button" class="btn btn-xs btn-danger <?php echo $div_wrapper ?>" onclick="javascript: delSaveMedicine('<?php echo $div_wrapper ?>', <?php echo  $value['id'] ?>)"><i class='glyphicon glyphicon-minus'></i> Delete</button></button> 
						<section class="clear <?php echo $div_wrapper ?>"></section>
						<?php $count++; } ?>
						<div id="loading_wrapper3"></div>
						<div id="add_dinner_wrapper"></div>
						<div class="line02" style="width:625px;"></div>
						<div class="clear"></div>
						<ul id='form'>
							<li>Special Instructions: </li>
							<li><textarea type='text' name='d_special_instructions' id='d_special_instructions' style='max-width: 370px;min-width: 370px; height: 50px;'><?php echo $record['d_instructions'] ?></textarea></li>
						</ul>
					</div>


				</div>	
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    <button class="btn btn-primary submit_button" onclick="">Save</button>
		</div>
	</div>
</div>