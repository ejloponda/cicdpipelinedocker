function viewMyProfile(){
	$.post(base_url + "admin/viewMyProfile",{},function(o){
		$('#user_profile_form_wrapper').html(o);
		$('#user_profile_form_wrapper').modal();
	});
}

function editMyProfile(){
	$('#user_profile_form_wrapper').modal("hide");
	$.post(base_url + "admin/updateMyProfile",{},function(o){
		$('#update_user_profile_form_wrapper').html(o);
		$('#update_user_profile_form_wrapper').modal();
	});
}