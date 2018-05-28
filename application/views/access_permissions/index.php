
<?php include('themes/templates/includes/user-header.php'); ?>
<?php include('themes/templates/includes/user-sidebar.php'); ?>

<script>
	$(function() {
		reload_content();
		var session = sessionStorage.getItem('user_type_id');
		if(session){
			view_roles(session);
			sessionStorage.removeItem('user_type_id');
		}
	});
</script>

<div id="main_wrapper_management"></div>
<div class="modal fade" id="add_user_access_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="delete_user_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="user_profile_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div class="modal fade" id="update_user_profile_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<?php include('themes/templates/footer/user-footer.php'); ?>
