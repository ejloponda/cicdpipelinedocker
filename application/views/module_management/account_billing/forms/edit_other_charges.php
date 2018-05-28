<script>
	<!--
	$(function(){
		$('.tipsy-inner').remove();
		$("#add_other_charges_form").validationEngine({scroll:false});
		$("#add_other_charges_form").ajaxForm({
        success: function(o) {
      		if(o.is_successful) {
      			IS_ADD_OTHER_CHARGE_FORM_CHANGE = false;
      			default_success_confirmation({message : o.message, alert_type: "alert-success"});
      		} else {
      			default_success_confirmation({message : o.message, alert_type: "alert-error"});
      		}
      		window.location.hash = "charges_list";
			reload_content("charges_list");
			$('html,body').animate({scrollTop: $("#alert_confirmation_wrapper").offset().top},10);
        },
        
       		dataType : "json"
    	});

    	$(".form_charges").live('change', function(e) {
			if($(this).val()) {
				IS_ADD_OTHER_CHARGE_FORM_CHANGE = true;
			}
		});

    	reset_all();
		reset_all_topbars_menu();
		$('.module_management_menu').addClass('hilited');
		$('.account_billing_settings_form').addClass('sub-hilited');
	});
		
	-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1>Other Charges</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onclick="$('#add_other_charges_form').submit();"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" class="other_charges"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
		</ul>
		<div class="clear"></div>
	</hgroup>
	
	<section id="left">
			<form action="<?php echo url('module_management/save_other_charges') ?>" method="post" id="add_other_charges_form" style="width:100%;">
					<input type="hidden" value="<?php echo $oc['id'] ?>" name="oc_id">
					<ul id="form">
						<li>Revenue Centers</li>
						<li>
							<input type="input" name="r_centers" id="r_centers" class="textbox form_charges validate[required]" value="<?php echo $oc['r_centers'] ?>" style="width: 200px;">
						</li>
					</ul>
					
				<section class="clear"></section>
					<ul id="form02">
						<li>Status</li>
						<li>
							<select name="status" id="status" class="select form_charges" style="width: 100px;">
								<option value="Active" <?php echo ($oc['status'] == "Active" ? "selected" : "") ?>>Active</option>
								<option value="Inactive" <?php echo ($oc['status'] == "Inactive" ? "selected" : "") ?>>Inactive</option>
							</select>
						</li>
					</ul>
				
				<section class="clear"></section>
				<ul id="form02">
					<li>Category</li>
					<li>
						<select name="category" id="category" class="select form_charges validate[required]" style="width: 130px;">
							<option value="">-Select-</option>
							<option value="Consultation" <?php echo ($oc['category']  == "Consultation" ? "selected" : "") ?>>Consultation</option>
							<option value="Genetic Testing" <?php echo ($oc['category']  == "Genetic Testing" ? "selected" : "") ?>>Genetic Testing</option>
							<option value="Other Test" <?php echo ($oc['category']  == "Other Test" ? "selected" : "") ?>>Other Test</option>
							<option value="Others" <?php echo ($oc['category']  == "Others" ? "selected" : "") ?>>Others</option>
							<option value="yp"  <?php echo ($oc['category']  == "yp" ? "selected" : "") ?> >Your Prevention Test</option>
							<option value="armin"  <?php echo ($oc['category']  == "armin" ? "selected" : "") ?> >Armin Test</option>
						</select>
					</li>
				</ul>

				<section class="clear"></section>
				<ul id="form02">
					<li>Price</li>
					<li>
						<input type="input" name="price" class="textbox form_charges" style="width: 100px;" placeholder='0.00' value='<?php echo $oc['price']; ?>'>
					</li>
				</ul>

				<!-- <section class="clear"></section>
				<ul id="form02">
					<li>Doctor</li>
					<li>
						<script>
							var opts=$('#attending_doctor_source').html(), opts2='<option></option>'+opts;
						    $('#doctor').each(function() { var e=$(this); e.html(e.hasClass('placeholder')?opts2:opts); });
						    $('#doctor').select2({allowClear: true});
						</script>
						<select id='doctor' name='doctor' class='populate add_returns_trigger' style='width:280px;'></select>
						<select id='attending_doctor_source' style='display:none'><option value='0'>-Select-</option>";
							<?php foreach($doctors as $key=>$value): ?>
								<option value="<?php echo $value['id'] ?>" <?php echo ($oc['doctor_id'] == $value['id'] ? "selected" : "") ?>><?php echo $value['full_name'] ?></option>
							<?php endforeach; ?>
						</select>
					</li>
				</ul> -->
			</form>
	</section>
	<section class="clear"></section>
	<section id="buttons">
		<button class="form_button" onClick="$('#add_other_charges_form').submit();">Save & Continue</button>
		<button type="button" class="form_button other_charges">Cancel</button>
	</section>			
</section>
<!-- <section class="clear"></section> -->
</section>