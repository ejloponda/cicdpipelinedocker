<style>
.datepicker{z-index:1151;}
</style>
<script>
	// $("#selector_tabs a[href='#<?php echo $tab_form ?>']").tab('show');
	$(function(){
		CKEDITOR.replace( 'bf_special_instructions',{removePlugins: 'elementspath,save,font,undo,removeformat,format,a11yhelp,button,resize,forms,contextmenu,templates,indentblock,listblock,indent,pastetext,pastefromword,preview,print,tabletools,table,flash,floatingspace,flash,horizontalrule,link,dialogui,dialog,about,bidi,blockquote,clipboard,panelbutton,panel,floatpanel,templates,menu,div,resize,enterkey,filebrowser,entities,fakeobjects,smiley,language,liststyle,list,magicline,maximize,newpage,pagebreak,selectall,specialchar,sourcearea,find,showblocks,iframe,image,specialchar,scayt,wsc,stylescombo',width:['450px'],height:['100px'], resize_enabled:true, removeButtons:'Superscript,Subscript,Strikethrough'});
   		CKEDITOR.replace( 'l_special_instructions',{removePlugins: 'elementspath,save,font,undo,removeformat,format,a11yhelp,button,resize,forms,contextmenu,templates,indentblock,listblock,indent,pastetext,pastefromword,preview,print,tabletools,table,flash,floatingspace,flash,horizontalrule,link,dialogui,dialog,about,bidi,blockquote,clipboard,panelbutton,panel,floatpanel,templates,menu,div,resize,enterkey,filebrowser,entities,fakeobjects,smiley,language,liststyle,list,magicline,maximize,newpage,pagebreak,selectall,specialchar,sourcearea,find,showblocks,iframe,image,specialchar,scayt,wsc,stylescombo',width:['450px'],height:['100px'], resize_enabled:true, removeButtons:'Superscript,Subscript,Strikethrough'});
   		CKEDITOR.replace( 'd_special_instructions',{removePlugins: 'elementspath,save,font,undo,removeformat,format,a11yhelp,button,resize,forms,contextmenu,templates,indentblock,listblock,indent,pastetext,pastefromword,preview,print,tabletools,table,flash,floatingspace,flash,horizontalrule,link,dialogui,dialog,about,bidi,blockquote,clipboard,panelbutton,panel,floatpanel,templates,menu,div,resize,enterkey,filebrowser,entities,fakeobjects,smiley,language,liststyle,list,magicline,maximize,newpage,pagebreak,selectall,specialchar,sourcearea,find,showblocks,iframe,image,specialchar,scayt,wsc,stylescombo',width:['450px'],height:['100px'], resize_enabled:true, removeButtons:'Superscript,Subscript,Strikethrough'});

		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		/*var start_date = $('#start_date').datepicker({
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
		}).data('datepicker');*/

		var start_date = $('#start_date').datepicker({
			format: 'yyyy-mm-dd'
   		 });

		var expiration_date = $('#expiration_date').datepicker({
			format: 'yyyy-mm-dd'
   		 });

		$("#regimen_new_row_form").validationEngine({scroll:false});
		$('#regimen_new_row_form').ajaxForm({
			success:function(o) {
				if(o.is_successful) {
					getMedicineTable(o.regimen_id, o.version_id);
					$('#medicine_modal_form').html('');
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
       		 //alert( CKEDITOR.instances[instance].getData());
   			}
   			$('#regimen_new_row_form').submit();
		});	

	});
</script><style type="text/css">
.modal-body {overflow-y:scroll; max-height: 500px;}
</style>
<div class="modal-dialog" style="width:53%; max-height: 50%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <img src="<?php echo BASE_FOLDER; ?>themes/images/header-regimen.png"> <strong><span style="color: black; font-size: 22px;">Edit Regimen</span></strong>
		</div>
		<div class="modal-body">
			<form id="regimen_new_row_form" name="regimen_new_row_form" method="post" action="<?php echo url('regimen_management/addNewRowMedicine'); ?>" style="width:100%:">
				<input type="hidden" id="row_id" name="row_id" value="<?php echo $record['id']; ?>">
				<input type="hidden" id="regimen_id" name="regimen_id" value="<?php echo $regimen_id; ?>">
				<input type="hidden" id="version_id" name="version_id" value="<?php echo $record['version_id'] ?>">
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
						<input type="hidden" id="temp_id" value="<?php echo $temp_id ?>">
						<ul id="form">
							<li>Start Date</li>
							<li><input type="text" id="start_date" name="start_date" class="textbox validate[required,custom[date]]" value="<?php echo $record['start_date'] ?>"></li>
						</ul>

						<section class="clear"></section>

						<ul id="form">
							<li>End Date</li>
							<li><input type="text" id="expiration_date" name="expiration_date" class="textbox validate[required,custom[date]]" value="<?php echo $record['end_date'] ?>"></li>
						</ul>
					</div>


					<div class="tab-pane" id="breakfast_form">
						<br/>
						<div style="width: 100%;height: 30px;">
							<?php if(!$lunch || !$dinner){ ?>
							<select id="breakfast_duplicate_select" class="form-control input-sm"  style="width: 155px; display:inline;">
								<?php if(!$lunch){ ?><option value="to Lunch">Duplicate to Lunch</option><?php } ?>
								<?php if(!$dinner){ ?><option value="to Dinner">Duplicate to Dinner</option><?php } ?>
							</select>
							<button type="button" onclick="javascript: duplicateDB('breakfast');" class="btn btn-info btn-sm" ><!-- <i class="glyphicon glyphicon-plus"></i> --> Duplicate</button>
							<?php } ?>
							<button type="button" onclick="javascript: addBreakfastRow();" class="btn btn-primary btn-sm" style="float:right;"><i class="glyphicon glyphicon-plus"></i> Add More</button>	
						</div><br/>
						<?php 
								$count = 10;
								$index = 20;
								foreach ($bf as $key => $value) {
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
						// debug_array($value['med_ops']);
							foreach ($value['med_ops'] as $c => $d) {
								$medicine_source 	 = "medicine_source{$index}";
								$medicine_list 	 	 = "eb_medicine_list_{$count}_{$index}";

								$id 			 	 = "breakfast[{$count}][med_ops][{$index}][id]";
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
										    		<option value="<?php echo $b['id']; ?>" <?php echo ($d['medicine_id'] == $b['id'] ? "selected" : "") ?>><?php echo $b['medicine_name']; ?></option>
										  <?php endforeach; ?>
										</select>
										<script>
											$(function(){ getMedicineQuantityType('<?php echo $count ?>','<?php echo $index ?>', 'eb'); });
											$('#<?php echo $medicine_list ?>').on('change', function(){ getMedicineQuantityType('<?php echo $count ?>','<?php echo $index ?>', 'eb'); });
											$('#<?php echo $taken_as ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').val(''); });
											$('#<?php echo $others ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').focus(); });
											$('#<?php echo $quantity_val_count ?>').on('change', function() { $('#<?php echo $others ?>').attr('checked',true); });
										</script>
										<input type="hidden" name="<?php echo $id ?>" value="<?php echo $d['id'] ?>">
										&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-danger' onclick='javascript: deleteElementRow("<?php echo $sub_wrapper ?>", <?php echo $d['id'] ?>);' style='height: 20px;'><i class='glyphicon glyphicon-minus'></i></a>
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
							</div>
							<section class="clear <?php echo $sub_wrapper ?> <?php echo $div_wrapper ?>"></section>
						<!-- <button type="button" class="btn btn-xs btn-danger"><i class='glyphicon glyphicon-minus'></i> Return</button></button>  -->
							<?php $index++; } ?>
							<div id='sub_bf_wrapper_<?php echo $count ?>' class='<?php echo $div_wrapper ?>'></div>
							<div id='sub_bf_wrapper_loader_<?php echo $count ?>' class='<?php echo $div_wrapper ?>'></div>
							<section class="clear <?php echo $div_wrapper ?>"></section>
							<button type="button" class="btn btn-xs btn-danger <?php echo $div_wrapper ?>" onclick="javascript: delSaveMedicine('<?php echo $div_wrapper ?>', <?php echo $key ?>, 'breakfast')"><i class='glyphicon glyphicon-minus'></i> Delete</button></button> 
							<section class="clear <?php echo $div_wrapper ?>"></section>
						<?php $count++; } ?>
						<div id="add_breakfast_wrapper"></div>
						<div id="loading_wrapper"></div>
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
							<?php if(!$bf || !$dinner){ ?>
							<select id="lunch_duplicate_select" class="form-control input-sm"  style="width: 155px; display:inline;">
								<?php if(!$bf){ ?><option value="to Breakfast">Duplicate to Breakfast</option><?php } ?>
								<?php if(!$dinner){ ?><option value="to Dinner">Duplicate to Dinner</option><?php } ?>
							</select>
							<button type="button" onclick="javascript: duplicateDB('lunch');" class="btn btn-info btn-sm" ><!-- <i class="glyphicon glyphicon-plus"></i> --> Duplicate</button>
							<?php } ?>
							<button type="button" onclick="javascript: addLunchRow();" class="btn btn-primary btn-sm" style="float:right;"><i class="glyphicon glyphicon-plus"></i> Add More</button>	
						</div><br/>
						
						<?php 
								$count = 10;
								$index = 20;
								foreach ($lunch as $key => $value) {
									$div_wrapper 		 = "edit_lunch_wrapper_{$count}";
									$activity 	 		 = "lunch[{$count}][activity]";
								
						?>
						<div class='line03 <?php echo $div_wrapper ?>' style='width:625px;'></div>
						<section class='clear <?php echo $div_wrapper ?>' ></section>
							<ul id='form' class='<?php echo $div_wrapper ?>'>
								<li>Activity: </li>
								<li><input type='text' id='<?php echo $activity ?>' name='<?php echo $activity ?>' class='textbox' style='width: 250px;' value="<?php echo $value['activity'] ?>" >&nbsp;&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-success' onclick='javascript: createSubLunchFields(<?php echo $count ?>);' style='height: 20px;'><i class='glyphicon glyphicon-plus'></i></a></li>
							</ul>
						<section class='clear <?php echo $div_wrapper ?>'></section>
						<?php
			
							foreach ($value['med_ops'] as $c => $d) {
								$medicine_source 	 = "medicine_source_{$index}";
								$medicine_list 	 	 = "el_medicine_list_{$count}_{$index}";

								$id 			 	 = "lunch[{$count}][med_ops][{$index}][id]";
								$medicine_name 	 	 = "lunch[{$count}][med_ops][{$index}][medicine_name]";
								$quantity 		 	 = "lunch[{$count}][med_ops][{$index}][quantity]";

								$quantity_type 		 = "lunch[{$count}][med_ops][{$index}][quantity_type]";
								$taken_as 		 	 = "el_taken_as_{$count}_{$index}";
								$dosage_val 	 	 = "el_dosage_val_{$count}_{$index}";

								$others 		 	 = "el_others_{$count}_{$index}";
								$quantity_val_count  = "el_quantity_val_{$count}_{$index}";
								$quantity_val  		 = "lunch[{$count}][med_ops][{$index}][quantity_val]";

								$sub_wrapper 		 = "lunch_sub_wrapper_{$index}";
						?>
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
									    		<option value="<?php echo $b['id']; ?>" <?php echo ($d['medicine_id'] == $b['id'] ? "selected" : "") ?>><?php echo $b['medicine_name']; ?></option>
									  <?php endforeach; ?>
									</select>
									<input type="hidden" name="<?php echo $id ?>" value="<?php echo $d['id'] ?>">
									<script>
										$(function(){ getMedicineQuantityType('<?php echo $count ?>','<?php echo $index ?>', 'el'); });
										$('#<?php echo $medicine_list ?>').on('change', function(){ getMedicineQuantityType('<?php echo $count ?>','<?php echo $index ?>', 'el'); });
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
						<!-- <button type="button" class="btn btn-xs btn-danger"><i class='glyphicon glyphicon-minus'></i> Return</button></button>  -->
							<?php $index++; } ?>
							<div id='sub_l_wrapper_<?php echo $count ?>' class='<?php echo $div_wrapper ?>'></div>
							<div id='sub_l_wrapper_loader_<?php echo $count ?>' class='<?php echo $div_wrapper ?>'></div>
							<section class="clear <?php echo $div_wrapper ?>"></section>
							<button type="button" class="btn btn-xs btn-danger <?php echo $div_wrapper ?>" onclick="javascript: delSaveMedicine('<?php echo $div_wrapper ?>', <?php echo $key ?>, 'lunch')"><i class='glyphicon glyphicon-minus'></i> Delete</button></button> 
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
							<?php if(!$bf || !$lunch){ ?>
							<select id="dinner_duplicate_select" class="form-control input-sm"  style="width: 170px; display:inline;">
								<?php if(!$bf){ ?><option value="to Breakfast">Duplicate to Breakfast</option><?php } ?>
								<?php if(!$lunch){ ?><option value="to Lunch">Duplicate to Lunch</option><?php } ?>
							</select>
							<button type="button" onclick="javascript: duplicateDB('dinner');" class="btn btn-info btn-sm" >Duplicate</button>
							<?php } ?>
							<button type="button" onclick="javascript: addDinnerRow();" class="btn btn-primary btn-sm" style="float:right;"><i class="glyphicon glyphicon-plus"></i> Add More</button>	
						</div><br/>
						
						<?php 
								$count = 10;
								$index = 20;
								foreach ($dinner as $key => $value) {
									$div_wrapper 		 = "edit_dinner_wrapper_{$count}";
									/*$medicine_source 	 = "medicine_source1_{$count}";
									$medicine_list 	 	 = "ed_medicine_list_{$count}";
									$id 			 	 = "edit_dinner[{$count}][id]";
									$medicine_name 	 	 = "edit_dinner[{$count}][medicine_name]";
									$quantity 		 	 = "edit_dinner[{$count}][quantity]";

									$quantity_type 		 = "edit_dinner[{$count}][quantity_type]";
									$taken_as 		 	 = "ed_taken_as_{$count}";
									$dosage_val 	 	 = "ed_dosage_val_{$count}";

									$others 		 	 = "ed_others_{$count}";
									$quantity_val_count  = "ed_quantity_val_{$count}";
									$quantity_val  		 = "edit_dinner[{$count}][quantity_val]";*/

									$activity 	 		 = "dinner[{$count}][activity]";
								
							 ?>
						<div class='line03 <?php echo $div_wrapper ?>' style='width:625px;'></div>
						<section class='clear <?php echo $div_wrapper ?>' ></section>
							<ul id='form' class='<?php echo $div_wrapper ?>'>
								<li>Activity: </li>
								<li><input type='text' id='<?php echo $activity ?>' name='<?php echo $activity ?>' class='textbox' style='width: 250px;' value="<?php echo $value['activity'] ?>" >&nbsp;&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-success' onclick='javascript: createSubDinnerFields(<?php echo $count ?>);' style='height: 20px;'><i class='glyphicon glyphicon-plus'></i></a></li>
							</ul>
						<section class='clear <?php echo $div_wrapper ?>'></section>
						<?php
						// debug_array($value['med_ops']);
							foreach ($value['med_ops'] as $c => $d) {
								$medicine_source 	 = "medicine_source1_{$index}";
								$medicine_list 	 	 = "ed_medicine_list_{$count}_{$index}";

								$id 			 	 = "dinner[{$count}][med_ops][{$index}][id]";
								$medicine_name 	 	 = "dinner[{$count}][med_ops][{$index}][medicine_name]";
								$quantity 		 	 = "dinner[{$count}][med_ops][{$index}][quantity]";

								$quantity_type 		 = "dinner[{$count}][med_ops][{$index}][quantity_type]";
								$taken_as 		 	 = "ed_taken_as_{$count}_{$index}";
								$dosage_val 	 	 = "ed_dosage_val_{$count}_{$index}";

								$others 		 	 = "ed_others_{$count}_{$index}";
								$quantity_val_count  = "ed_quantity_val_{$count}_{$index}";
								$quantity_val  		 = "dinner[{$count}][med_ops][{$index}][quantity_val]";

								$sub_wrapper 		 = "dinner_sub_wrapper_{$index}";
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
										    		<option value="<?php echo $b['id']; ?>" <?php echo ($d['medicine_id'] == $b['id'] ? "selected" : "") ?>><?php echo $b['medicine_name']; ?></option>
										  <?php endforeach; ?>
										</select>
										<script>
											$(function(){ getMedicineQuantityType('<?php echo $count ?>','<?php echo $index ?>', 'ed'); });
											$('#<?php echo $medicine_list ?>').on('change', function(){ getMedicineQuantityType('<?php echo $count ?>','<?php echo $index ?>', 'eb'); });
											$('#<?php echo $taken_as ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').val(''); });
											$('#<?php echo $others ?>').live('click', function() { $('#<?php echo $quantity_val_count ?>').focus(); });
											$('#<?php echo $quantity_val_count ?>').on('change', function() { $('#<?php echo $others ?>').attr('checked',true); });
										</script>
										<input type="hidden" name="<?php echo $id ?>" value="<?php echo $d['id'] ?>">
										&nbsp;<a href='javascript: void(0);' class='btn btn-xs btn-danger' onclick='javascript: deleteElementRow("<?php echo $sub_wrapper ?>", <?php echo $d['id'] ?>);' style='height: 20px;'><i class='glyphicon glyphicon-minus'></i></a>
									</li>				
								</ul>
								<section class="clear <?php echo $div_wrapper ?>"></section>
								<ul id="form" class="<?php echo $div_wrapper ?>">
									<?php #debug_array($d['medicine_id']); ?>
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
							</div>
							<section class="clear <?php echo $sub_wrapper ?> <?php echo $div_wrapper ?>"></section>
						<!-- <button type="button" class="btn btn-xs btn-danger"><i class='glyphicon glyphicon-minus'></i> Return</button></button>  -->
							<?php $index++; } ?>
							<div id='sub_d_wrapper_<?php echo $count ?>' class='<?php echo $div_wrapper ?>'></div>
							<div id='sub_d_wrapper_loader_<?php echo $count ?>' class='<?php echo $div_wrapper ?>'></div>
							<section class="clear <?php echo $div_wrapper ?>"></section>
							<button type="button" class="btn btn-xs btn-danger <?php echo $div_wrapper ?>" onclick="javascript: delSaveMedicine('<?php echo $div_wrapper ?>', <?php echo $key ?>, 'dinner')"><i class='glyphicon glyphicon-minus'></i> Delete</button></button> 
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
		    <button class="btn btn-primary submit_button" >Save</button>
		</div>
	</div>
</div>