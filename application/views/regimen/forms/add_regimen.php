<style>
.datepicker{z-index:1151;}
</style>
<script>
	$(function(){
		$("#LMP").hide();
		$("#program").hide();
		// $('#date_generated').datepicker({
		// 	format: 'yyyy-mm-dd'
	 //    });
		//CKEDITOR.replace( 'regimen_notes',{removePlugins: 'toolbar,elementspath',width:['810px'],height:['130px'], resize_enabled:false});
		CKEDITOR.replace( 'regimen_notes',{removePlugins: 'elementspath,save,font,undo,removeformat,format,a11yhelp,resize,forms,contextmenu,templates,indentblock,pastetext,pastefromword,preview,print,tabletools,flash,floatingspace,flash,horizontalrule,link,about,bidi,blockquote,clipboard,templates,div,resize,enterkey,filebrowser,entities,fakeobjects,smiley,language,liststyle,list,magicline,maximize,newpage,pagebreak,selectall,specialchar,sourcearea,find,showblocks,iframe,image,specialchar,scayt,wsc,stylescombo',width:['810px'],height:['130px'], resize_enabled:true, removeButtons:'Superscript,Subscript,Strikethrough'});
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		/*var start_date = $('#from_duration_date').datepicker({
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
		}).data('datepicker');*/

		var start_date = $('#from_duration_date').datepicker({
			format: 'yyyy-mm-dd'
   		 });

		var expiration_date = $('#to_duration_date').datepicker({
			format: 'yyyy-mm-dd'
   		 });

	    $("#user_list").live('click', function() {
			var patient_id = $("#user_list").val();
			getPatientDetails(patient_id);
		});

		$("#from_duration_date").on('change', function(){
			$('#from_duration_date').validationEngine("hide");
		});

		$("#to_duration_date").on('change', function(){
			$('#to_duration_date').validationEngine("hide");
		});


		function getPatientDetails(patient_id){
			var patient_id = parseInt(patient_id);
			var date_generated = $("#date_generated").val();
			$.post(base_url + "regimen_management/getPatientDetails", {patient_id:patient_id}, function(o){
				$("#patient_id_src").html(o.output['patient_code']);
				$("#patient_id_src2").val(o.output['patient_code']);
				if(o.output['base_path']){
					var src = "<?php echo BASE_FOLDER ?>" + o.output['base_path'] + o.output['filename'] + "." + o.output['extension'];
				} else {
					var src = "<?php echo BASE_FOLDER ?>/themes/images/photo.png";
				}
				
				$(".photoID").attr("src", src);
				getMedicineTable();
			}, "json");	
		}

		getMedicineTable();

		$("#regimen_general_form").validationEngine({scroll:false});
		$("#regimen_general_form").ajaxForm({
	        success: function(o) {
	      		if(o.is_successful) {
	      			IS_ADD_REGIMEN_GENERAL_FORM_CHANGE = false;
	      			// send_notif(o.notif_msg);
	      			//send_notif(o.notif_message,o.notif_title,o.notif_type);
	      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
	      			$.unblockUI();
	      		} else {
	      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
	      		}
	   			// window.location.hash = "lists";
				// reload_content("lists");
				view_regimen(o.regimen_id);
				$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
	        },
	        beforeSubmit : function(evt){
	         	$.blockUI();
	         	var patient_id = $("#user_list").val();
	        	if(patient_id == 0)
	        	{ 
	        		alert("Warning: No Patient Selected.");
	        		$.unblockUI();
	        		return false;
	        	}
	         },
	        
	        dataType : "json"
	    });

		$(".add_regimen_general_form").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_REGIMEN_GENERAL_FORM_CHANGE = true;
			}
		});

		$(".add_submit_button").on('click', function(event) {
			//$.blockUI();
			for ( instance in CKEDITOR.instances ) {
       		 CKEDITOR.instances[instance].updateElement();
   			}

   			$('#regimen_general_form').submit();
		});

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
	})
</script>
<section class="area">
				<hgroup id="area-header">
					<ul class="page-title">
						<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-regimen.png"></li>
						<li><h1>Regimen Creator</h1></li>
					</ul>
					
					<ul id="controls">
						<li><a href="javascript: void(0);" onclick="$('#regimen_general_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
						<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
						<li><a href="javascript: void(0);" class="regimen_general_cancel_button"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
					</ul>
					
					<div class="clear"></div>
				</hgroup>
				
				<form action="<?php echo url("regimen_management/addRegimen") ?>" method="post" id="regimen_general_form" style="width: 100%;">
					<ul class="regimen-ID" >
						<li><img class="photoID" src="<?php echo BASE_FOLDER; ?>themes/images/photo.png"></li>
						<li class="patient">
							<script>
								$(function() {
									var opts=$("#user_source").html(), opts2="<option></option>"+opts;
								    $("#user_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
								    $("#user_list").select2({allowClear: true});
								});
							</script>
						<select id="user_list" name="patient_id" class="populate add_regimen_general_form" style="width:250px;"></select>
						<select id="user_source" style="display:none">
							<option value="0">Select Patient</option>
						  <?php foreach($patients as $key=>$value): ?>
						    <option value="<?php echo $value['id']; ?>"><?php echo $value['firstname'] . " " . $value['lastname']; ?></option>
						  <?php endforeach; ?>
						</select>
						<br>Patient ID: <b id="patient_id_src"></b></li>
					</ul>
					
					<ul id="form" style="margin: 10px 0px 0px 15px; padding: 0; height:25px; float:right;">
						<li style="width:110px;">Date Generated</li>
						<li style="padding-right: 35px;">
							<input type="text" id="date_generated" name="date_generated" class="textbox add_regimen_general_form validate[required]" style="width: 170px;" value="<?php date_default_timezone_set('Asia/Manila');  echo $today = date('Y-m-d'); ?>" readonly>
						</li>
					</ul>
					<div class="clear"></div>

					<h5><b>Regimen Duration:</b></h3>
					<ul id="notes">
						<li style="width: 40px; padding: 4px 0 0 0;">From: </li>
						<li><input type="text" id="from_duration_date" name="from_duration_date" class="textbox add_regimen_general_form validate[required]" style="width: 170px;"></li>
						<li style="width:35px; padding: 4px 5px 0 10px;">To: </li>
						<li style=""><input type="text" id="to_duration_date" name="to_duration_date" class="textbox add_regimen_general_form validate[required]" style="width: 170px;"></li>
					</ul>
					<br>
					<ul id="notes" style="padding-bottom: 4px">
						<li style="width: 40px; padding: 4px 0 0 0;">LMP </li>
						<li><input type="checkbox" name="checkbox_lmp" id="checkbox_lmp" style="margin-left: 24px;"></li>
						<li style="padding: 4px 0 0 0;"><input type="text" id="LMP" name="LMP" class="textbox add_regimen_general_form" style="width: 200px; margin-left: 25px; position:absolute; display:block;"></li>	
					</ul>
					<ul id="notes" style="padding-bottom: 4px; margin-top:30px;"> 
						<li style="width: 40px; padding: 4px 0px 5px 0;"> Program </li>
						<li><input type="checkbox" name="checkbox_program" id="checkbox_program" style="margin-left:25px;"></li>
						<li><input type="text" id="program" name="program" class="textbox add_regimen_general_form" style="width: 200px; margin-left:24px; padding-bottom: 5px; position:absolute; display:block;"></li>
					</ul>
					<div class="clear"></div>

					<div id="medicine_wrapper_table"></div>
					
					<div class="line02"></div>
					
					<div class="clear"></div>
										
					<ul id="notes">
						<li>Regimen Notes</li>
						<li><textarea class="add_regimen_general_form" name="regimen_notes"></textarea></li>
					</ul>
					<section class="clear"></section>
					<div class="clear"></div>
					<ul id="notes" style="margin: -14px 0 20px 0;">
						<li>Preferences</li>
						<li><input type="text" class="add_regimen_general_form" name="preference"></li>
					</ul>
					<div class="clear"></div>
					<ul id="notes" style="margin: -14px 0 20px 0;">
						<li style="padding-top: 10px;">Status</li>
						<li>
							<select name="status" class="add_regimen_general_form select02" id="status" >
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</li>
					</ul>
					<div class="clear"></div>
				</form>
				<section id="buttons">
					<button class="form_button add_submit_button">Save & Continue</button>
					<button class="form_button regimen_general_cancel_button">Cancel</button>
				</section>						
			</section>
		<!-- <section class="clear"></section> -->
		</section>