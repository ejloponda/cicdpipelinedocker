<?php include('themes/templates/includes/user-header.php'); ?>
<?php include('themes/templates/includes/user-sidebar.php'); ?>

<script>
	$(function() {
		reload_content();
	});
</script>

<div id="main_wrapper_management"></div>
<div class="modal fade" id="user_profile_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div class="modal fade" id="update_user_profile_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="official_receipt_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="void_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div class="modal fade" id="report_generator_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div class="modal fade" id="change_mode_of_payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<?php include('themes/templates/footer/user-footer.php'); ?>
