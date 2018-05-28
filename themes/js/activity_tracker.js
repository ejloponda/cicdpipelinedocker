$(function(){
	$('.track_inv_item').live('click',function(){
		id = parseInt($('.track_inv_item').attr('data-id'));
		sessionStorage.setItem('id', id);
		window.location.href="inventory";	
	});

	$('.track_user_permission').live('click',function(){
		id = parseInt($('.track_user_permission').attr('data-id'));
		sessionStorage.setItem('user_type_id', id);
		window.location.href="permissions";	
	});

	$('.track_users').live('click',function(){
		id = parseInt($('.track_users').attr('data-id'));
		sessionStorage.setItem('user_id', id);
		window.location.href="users";	
	});

	$('.track_users_module').live('click',function(){
		window.location.href="users#permissions";	
	});

	$('.track_module_quantity').live('click', function(){
		window.location.href="module#quantity_list";	
	});

	$('.track_module_dosage').live('click', function(){
		window.location.href="module#dosage_list";	
	});

	$('.track_module_dc').live('click', function(){
		window.location.href="module#disease_category";	
	});

	$('.track_module_dt').live('click', function(){
		window.location.href="module#disease_type";	
	});



});