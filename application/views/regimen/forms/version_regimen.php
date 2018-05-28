<script>
	$(function(){
		
		//CKEDITOR.replace( 'regimen_notes',{removePlugins: 'toolbar,elementspath',width:['810px'],height:['130px'], resize_enabled:false});
		CKEDITOR.replace( 'regimen_notes',{removePlugins: 'elementspath,save,font,undo,removeformat,format,a11yhelp,resize,forms,contextmenu,templates,indentblock,pastetext,pastefromword,preview,print,tabletools,flash,floatingspace,flash,horizontalrule,link,about,bidi,blockquote,clipboard,templates,div,resize,enterkey,filebrowser,entities,fakeobjects,smiley,language,liststyle,list,magicline,maximize,newpage,pagebreak,selectall,specialchar,sourcearea,find,showblocks,iframe,image,specialchar,scayt,wsc,stylescombo',width:['810px'],height:['130px'], resize_enabled:true, removeButtons:'Superscript,Subscript,Strikethrough'});
		CKEDITOR.replace( 'version_remarks',{removePlugins: 'toolbar,elementspath',width:['810px'],height:['130px'], resize_enabled:false, autoParagraph:false});

		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}

		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		var start_date = $('#from_duration_date').datepicker({
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
		  $('#to_duration_date')[0].focus();
		}).data('datepicker');
		var expiration_date = $('#to_duration_date').datepicker({
			format: 'yyyy-mm-dd',
		  onRender: function(date) {
		    return date.valueOf() <= start_date.date.valueOf() ? 'disabled' : '';
		  }
		}).on('changeDate', function(ev) {
		  expiration_date.hide();
		}).data('datepicker');

		$("#edit_regimen_general_form").validationEngine({scroll:false});
		$("#edit_regimen_general_form").ajaxForm({
	        success: function(o) {
	      		if(o.is_successful) {
	      			IS_EDIT_REGIMEN_GENERAL_FORM_CHANGE = false;
	      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
	      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
	      			$.unblockUI();
	      		} else {
	      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
	      		}
	      		
				if(o.version_id == "0"){
					view_regimen(o.regimen_id);
				} else {
					view_version(o.version_id);
				}
				$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
	        },
	        beforeSubmit : function(evt){
	         	$.blockUI();
	         },
	        
	        dataType : "json"
	    });

		$(".edit_regimen_general_form").live('change', function(e) {
			if($(this).val()) {
				IS_EDIT_REGIMEN_GENERAL_FORM_CHANGE = true;
			}
		});

		$(".backToMain").on('click', function(){
			var regimen_id = $("#regimen_id").val();
			unsetSession();
			view_regimen(regimen_id);	
		});

		$(".submit_button").on('click', function(event) {
			//$.blockUI();
			for ( instance in CKEDITOR.instances ) {
     		 CKEDITOR.instances[instance].updateElement();
     		 //alert( CKEDITOR.instances[instance].getData());
  			}
 			$('#edit_regimen_general_form').submit();		
		});

		var edit_lmp = $("#LMP").val();
		if(edit_lmp != ''){
			$('#checkbox_lmp').prop('checked', true);
			$("#LMP").show();
		}else{
			$("#LMP").hide();
			$("#program").hide();
		}

		var edit_program = $("#program").val();
		if(edit_program != ''){
			$('#checkbox_program').prop('checked', true);
			$("#program").show();
		}else{
			$("#program").hide();
		}

		$("#checkbox_lmp").on('click',function(){
			var check = $("#checkbox_lmp").prop("checked");
			if(check){
				$("#LMP").show();
			}else{
				$("#LMP").hide();
				$("#LMP").val('');
			}		
		});
		
		$("#checkbox_program").on('click',function(){
			var check = $("#checkbox_program").prop("checked");
			if(check){
				$("#program").show();
			}else{
				$("#program").hide();
				$("#program").val('');
			}
		});


	    reset_all();
		reset_all_topbars_menu();
		$('.regimen_menu').addClass('hilited');
		$('.regimen_list_form').addClass('sub-hilited');
		getMedicineTable();
	});
</script>
<section class="area">
			<hgroup id="area-header">
				<ul class="page-title">
					<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-regimen.png"></li>
					<li><h1>Regimen Versioning Creator</h1></li>
				</ul>
				
				<ul id="controls">
					<li><a href="javascript: void(0);" onClick="$('#edit_regimen_general_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
					<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
					<li><a href="javascript: void(0);" class="backToMain"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
				</ul>
				
				<div class="clear"></div>
			</hgroup>
			
			<form action="<?php echo url("regimen_management/addRegimen") ?>" method="post" id="edit_regimen_general_form" style="width: 100%;">
				<ul class="regimen-ID" >
					<li><img class="photoID" src="<?php echo ( $photo ? BASE_FOLDER . $photo['base_path'] . $photo['filename'] . "." . $photo['extension'] : BASE_FOLDER . "themes/images/photo.png") ?>"></li>
					<li class="patient">
						<b style="font-size: 20px;"><span><?php echo $reg['patient_name'] ?></span></b><input type="hidden" value="<?php echo $reg['id'] ?>" name="regimen_id" id="regimen_id"><input type="hidden" value="<?php echo $reg['patient_id'] ?>" id="user_list" name="patient_id">
					<br>Patient ID: <b id="patient_id_src"><?php echo $patient['patient_code'] ?></b></li>
				</ul>
				
				<ul id="form" style="margin: 10px 0px 0px 15px; padding: 0; height:25px; float:right;">
					<li style="width:110px;">Date Generated</li>
					<li style="padding-right: 35px;">
						<input type="text" id="date_generated" name="date_generated" class="textbox edit_regimen_general_form validate[required]" style="width: 170px;" value="<?php echo $reg['date_generated'] ?>" readonly>
					</li>
				</ul>
				<div class="clear"></div>

				<h5><b>Regimen Duration:</b></h5>
				<ul id="notes">
					<li style="width: 40px; padding: 4px 0 0 0;">From: </li>
					<li><input type="text" id="from_duration_date" name="from_duration_date" class="textbox add_regimen_general_form validate[required]" style="width: 170px;" value="<?php echo (empty($version['start_date']) ? $reg['start_date'] : $version['start_date'] )?>"></li>
					<li style="width:35px; padding: 4px 5px 0 10px;">To: </li>
					<li style=""><input type="text" id="to_duration_date" name="to_duration_date" class="textbox add_regimen_general_form validate[required]" style="width: 170px;" value="<?php echo (empty($version['end_date']) ? $reg['end_date'] : $version['end_date'] )?>"></li>
				</ul>
				<br>
				<ul id="notes" style="padding-bottom: 4px">
					<li style="width: 40px; padding: 4px 0 0 0;">LMP </li>
					<li><input type="checkbox" name="checkbox_lmp" id="checkbox_lmp" style="margin-left: 24px;"></li>
					<li style="padding: 4px 0 0 0;"><input type="text" id="LMP" name="LMP" class="textbox add_regimen_general_form" style="width: 200px; margin-left: 25px; position:absolute; display:block;" value="<?php echo (empty($version['lmp']) ? $reg['lmp'] : $version['lmp'] )?>"></li>	
				</ul>
				<ul id="notes" style="padding-bottom: 4px; margin-top:30px;"> 
					<li style="width: 40px; padding: 4px 0px 5px 0;"> Program </li>
					<li><input type="checkbox" name="checkbox_program" id="checkbox_program" style="margin-left:25px;"></li>
					<li><input type="text" id="program" name="program" class="textbox add_regimen_general_form" style="width: 200px; margin-left:24px; padding-bottom: 5px; position:absolute; display:block;" value="<?php echo (empty($version['program']) ? $reg['program'] : $version['program'] )?>"></li>
				</ul>
				<div class="clear"></div>

				<div id="medicine_wrapper_table"></div>
				
				<div class="line02"></div>
									
				<ul id="notes">
					<li>Regimen Notes</li>
					<li><textarea class="edit_regimen_general_form" name="regimen_notes"><?php echo (empty($version['regimen_notes']) ? $reg['regimen_notes'] : $version['regimen_notes'] )  ?></textarea></li>
				</ul>
				<section class="clear"></section>
				<div class="clear"></div>
				<ul id="notes" style="margin: -14px 0 20px 0;">
					<li>Preferences</li>
					<li><input type="text" class="edit_regimen_general_form" name="preference" value="<?php echo (empty($version['preferences']) ? $reg['preferences'] : $version['preferences'] )?>"></li>
				</ul>
				<div class="clear"></div>
				<br/>
				<div class="line02"></div>
				<h4><b>Version Information:</b></h4>
				<br/>
				<ul id="notes" style="margin:0px 0 20px 0;">
					<li>Version Name <span class="red" style="float:none;">*</span></li>
					<li><input type="text" class="edit_regimen_general_form validate[required]" name="version_name"></li>
				</ul>
				<div class="clear"></div>
				<ul id="notes" style="margin: -14px 0 20px 0;">
					<li>Version Remarks</li>
					<li><textarea class="edit_regimen_general_form" name="version_remarks"></textarea></li>
				</ul>
				<section class="clear"></section>
				<div class="clear"></div>
				<ul id="notes" style="margin: -30px 0 20px 0;">
					<li style="padding-top: 10px;">Status</li>
					<li>
						<select name="status" class="edit_regimen_general_form select02" id="status" >
							<option value="Active" <?php echo ($reg['status'] == "Active" ? "selected" : "") ?>>Active</option>
							<option value="Inactive" <?php echo ($reg['status'] == "Inactive" ? "selected" : "") ?>>Inactive</option>
						</select>
					</li>
				</ul>
				<div class="clear"></div>
			</form>
			<section id="buttons">
				<button class="form_button submit_button" onClick="">Save & Continue</button>
				<button class="form_button backToMain">Cancel</button>
			</section>						
		</section>
	<section class="clear"></section>
</section>