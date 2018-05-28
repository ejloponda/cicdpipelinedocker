<script>
	<!--
	$(function(){
		$('.tipsy-inner').remove();
		$("#edit_filecategory_form").validationEngine({scroll:false});
		$("#edit_filecategory_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_CATEGORY_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
      		window.location.hash = "files_category";
			reload_content("files_category");
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        
       		dataType : "json"
    	});

    	$(".form_new_file_category").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_CATEGORY_FORM_CHANGE = true;
			}
		});

    	reset_all();
		reset_all_topbars_menu();
		$('.module_management_menu').addClass('hilited');
		$('.medical_history_settings_form').addClass('sub-hilited');
		$("#status").val("<?php echo $category['status'] ?>");
	});

		$(".cancel").on('click', function(){
			window.location.hash = "files_category";
			reload_content("files_category");
		});
		
	-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1>Edit File Category</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#edit_filecategory_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" id="files_category_list" class="cancel"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
	
	<section id="left">
			<form action="<?php echo url('module_management/add_file_category') ?>" method="post" id="edit_filecategory_form" style="width:100%;">
				<input type="hidden" id="id" name="id" value="<?php echo $category['id'] ?>"></input>
				<ul id="form02">
					<li>File Category Name <span>*</span></li>
					<li><input type="text" id="category_name" name="category_name" class="textbox validate[required] form_new_file_category" value="<?php echo $category['category_name'] ?>" style="margin: 0px;"></li>
				</ul>

				<section class="clear"></section>

				<ul id="form02">
					<li>Status</li>
					<li>
						<select name="status" id="status" class="select form_new_file_category" style="width: 100px;">
							<option value="Active">Active</option>
							<option value="Inactive">Inactive</option>
						</select>
					</li>
				</ul>
				<section class="clear"></section>
			</form>
	</section>
	<section class="clear"></section>
	<section id="buttons">
		<button class="form_button" onClick="$('#edit_filecategory_form').submit();">Save & Continue</button>
		<button type="button" class="form_button cancel" id="files_category_list">Cancel</button>
	</section>			
</section>
<section class="clear"></section>
</section>