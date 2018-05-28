<style>
.correct_alignment
{
	padding-bottom: 35px !important;
}
</style>
<script>
	$(function() {

		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}

		if($("#alert_confirmation_wrapper")){
			setTimeout(function(){
				$("#alert_confirmation_wrapper").fadeOut();
			}, 3000)
		}
		
		$('#user_list_dt').dataTable({
			"bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": true,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false,
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 10,
			"sAjaxSource": base_url + "admin/getAllUserlist?",
			"fnDrawCallback": function( oSettings ) {
				$('.edit_user').tipsy({gravity: 's'});
				$('.delete_user').tipsy({gravity: 's'});
		    }
		});

	});
</script>

<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-users.png"></li>
			<li><h1>List of Users</h1></li>
		</ul>
		<?php if($mu_default['can_add']) { ?>
			<button class="button01 firm_admin_add_user">+ Add New User Account</button>
		<?php } ?>
		<div class="clear"></div>
	</hgroup>

	<table id="user_list_dt" class="datatable table" style="min-width: 85%;">
	    <thead>
		<tr>
			
			<th align="left" valign="top" width="15%" style="text-align: center;">Email Address</th>
			<th align="left" valign="top" width="15%" style="text-align: center;">Last Name</th>
			<th align="left" valign="top" width="15%" style="text-align: center;">First Name</th>
			<th align="left" valign="top" width="15%" style="text-align: center;">Middle</th>
			<th align="left" valign="top" width="13%" style="text-align: center;">Role Type</th>
			<?php if($mu_default['can_update'] || $mu_default['can_delete']) { ?><th align="left" valign="top" width="10%" style="text-align: center;"></th><?php } ?>
			<!-- <th align="left" valign="top" width="13%">Date Created</th> -->
			<!-- <th align="left" valign="top" width="15%">Date Created</th> -->
		</tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>

<style>
	table.datatable td, th {padding-left:5px; padding: 5px; text-align: center;}
	.table_cb {vertical-align: middle; display: block; margin-left:10px;}
</style>

</section>
<!-- <section class="clear"></section> -->
