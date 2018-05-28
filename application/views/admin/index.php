<?php include('themes/templates/includes/admin-header.php'); ?>
<script>
	$(function() {
		reload_content();
		var session = sessionStorage.getItem('user_id');
		if(session){
			view_user_profile(session);
			sessionStorage.removeItem('user_id');
		}
	});
</script>

<div id="main-wrapper"></div>
<div class="modal fade" id="delete_user_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="delete_contact_information_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="edit_contact_information_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div class="modal fade" id="user_profile_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div class="modal fade" id="form_module_scope_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>		
<div class="modal fade" id="update_user_profile_form_wrapper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<?php include('themes/templates/footer/user-footer.php'); ?>
