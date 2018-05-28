<style>
.datepicker{z-index:1151;}
</style>	
<script>
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
					// alert(o.regimen_id + " " + o.version_id);
					if(o.regimen_id){
						getMedicineTable(o.regimen_id, o.version_id);
					} else {
						getMedicineTable();
					}
					unsetMealTypes();
          			// default_success_confirmation({message : o.message, alert_type: "alert-success"});
          		} else {
          			default_success_confirmation({message : o.message, alert_type: "alert-error"});
          		}

				$('#medicine_modal_form').modal('hide');
				

    			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
				
			}, beforeSubmit: function(o) {
				$("#selector_tabs a[href='#date_form']").tab('show');
				var start = $('#start_date').val();
				var end   = $('#expiration_date').val();
				if(start == '' && end == '')
				o.preventDefault();
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
</script>
<style type="text/css">
.modal-body {overflow-y:scroll; max-height: 500px;}
</style>
<div class="modal-dialog" style="width:53%; max-height: 50%; word-wrap:normal;">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <img src="<?php echo BASE_FOLDER; ?>themes/images/header-regimen.png"> <strong><span style="color: black; font-size: 22px;">Add New Regimen</span></strong>
		</div>
		<div class="modal-body">
			<form id="regimen_new_row_form" name="regimen_new_row_form" method="post" action="<?php echo url('regimen_management/addNewRowMedicine'); ?>" style="width:100%:">
		        <?php if(!$_SESSION['regimen_meds']) { ?>
			        <input type="hidden" id="regimen_id" name="regimen_id" value="<?php echo $regimen_id ?>">
			        <input type="hidden" id="version_id" name="version_id" value="<?php echo $version_id ?>">
		        <?php } ?>
		        <ul class="nav nav-tabs" id="selector_tabs" style="width: 627px;">
				  <li class="active"><a href="#date_form" data-toggle="tab">Date</a></li>
				  <li><a href="#breakfast_form" data-toggle="tab" id="breakfast_tab">Breakfast</a></li>
				  <li><a href="#lunch_form" data-toggle="tab" id="lunch_tab">Lunch</a></li>
				  <li><a href="#dinner_form" data-toggle="tab" id="dinner_tab">Dinner</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content" style="width: 627px;">
					<div class="tab-pane active" id="date_form">
						<br/>
						<ul id="form">
							<li>Start Date</li>
							<li><input type="text" id="start_date" name="start_date" class="textbox validate[required,custom[date]]"></li>
						</ul>

						<section class="clear"></section>

						<ul id="form">
							<li>End Date</li>
							<li><input type="text" id="expiration_date" name="expiration_date" class="textbox validate[required,custom[date]]"></li>
						</ul>
					</div>


					<div class="tab-pane" id="breakfast_form">
						<br/>
							<div style="width: 100%;height: 30px;">
								<select id="breakfast_duplicate_select" class="form-control input-sm"  style="width: 155px; display:inline;">
									<option value="to Lunch">Duplicate to Lunch</option>
									<option value="to Dinner">Duplicate to Dinner</option>
								</select>
								<button type="button" onclick="javascript: duplicate('breakfast');" class="btn btn-info btn-sm" ><!-- <i class="glyphicon glyphicon-plus"></i> --> Duplicate</button>	
								<button type="button" onclick="javascript: addBreakfastRow();" class="btn btn-primary btn-sm" style="float:right;"><i class="glyphicon glyphicon-plus"></i> Add More</button>	
							</div><br/>
							<div id="breakfast_duplicate_wrapper"></div>
							<div id="add_breakfast_wrapper"></div>
							<div id="loading_wrapper"></div>
							<div class="line02" style="width:625px;"></div>
							<div class="clear"></div>
							<ul id='form'>
								<li>Special Instructions: </li>
								<li><textarea type='text' name='bf_special_instructions' id='bf_special_instructions' style='max-width: 370px;min-width: 370px; height: 50px;'></textarea></li>
							</ul>
					</div>
					<div class="tab-pane" id="lunch_form">
						<br/>
							<div style="width: 100%;height: 30px;">
								
								<select id="lunch_duplicate_select" class="form-control input-sm"  style="width: 170px; display:inline;">
									<option value="to Breakfast">Duplicate to Breakfast</option>
									<option value="to Dinner">Duplicate to Dinner</option>
								</select>
								<button type="button" onclick="javascript: duplicate('lunch');" class="btn btn-info btn-sm" >Duplicate</button>
								<button type="button" onclick="javascript: addLunchRow();" class="btn btn-primary btn-sm" style="float:right;"><i class="glyphicon glyphicon-plus"></i> Add More</button>	
							</div><br/>
							<div id="lunch_duplicate_wrapper"></div>
							<div id="add_lunch_wrapper"></div>
							<div id="loading_wrapper2"></div>
							<div class="line02" style="width:625px;"></div>
							<div class="clear"></div>
							<ul id='form'>
								<li>Special Instructions: </li>
								<li><textarea type='text' name='l_special_instructions' id='l_special_instructions' style='max-width: 370px;min-width: 370px; height: 50px;'></textarea></li>
							</ul>
					</div>
					<div class="tab-pane" id="dinner_form">
						<br/>
							<div style="width: 100%;height: 30px;">
								<select id="dinner_duplicate_select" class="form-control input-sm"  style="width: 170px; display:inline;">
									<option value="to Breakfast">Duplicate to Breakfast</option>
									<option value="to Lunch">Duplicate to Lunch</option>
								</select>
								<button type="button" onclick="javascript: duplicate('dinner');" class="btn btn-info btn-sm" >Duplicate</button>
								<button type="button" onclick="javascript: addDinnerRow();" class="btn btn-primary btn-sm" style="float:right;"><i class="glyphicon glyphicon-plus"></i> Add More</button>	
							</div><br/>
							<div id="dinner_duplicate_wrapper"></div>
							<div id="add_dinner_wrapper"></div>
							<div id="loading_wrapper3"></div>
							<div class="line02" style="width:625px;"></div>
							<div class="clear"></div>
							<ul id='form'>
								<li>Special Instructions: </li>
								<li><textarea type='text' name='d_special_instructions' id='d_special_instructions' style='max-width: 370px;min-width: 370px; height: 50px;'></textarea></li>
							</ul>
					</div>
				</div>	
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    <button class="btn btn-primary submit_button">Save</button>
		</div>
	</div>
</div>