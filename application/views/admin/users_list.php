<script>
	$(function() {

		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}
		
		// $('#current_case_list_dt_filter').hide();
		$('#users_list_id').dataTable({
			"bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": true,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false,
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 50,
			"sAjaxSource": base_url + "admin/getAllUserlist?firm_id=1",
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
		<div class="clear"></div>
	</hgroup>
<button class="button01 firm_admin_add_user">+ Add New User Account</button>
	<table id="users_list_id" class="datatable table" style="min-width: 98%;">
	    <thead>
		<tr>
			<th align="left" valign="top" width="5%"></th>
			<th align="left" valign="top" width="15%">Email Address</th>
			<th align="left" valign="top" width="15%">Last Name</th>
			<th align="left" valign="top" width="15%">First Name</th>
			<th align="left" valign="top" width="15%">Middle</th>
			<th align="left" valign="top" width="15%">Role Type</th>
			<th align="left" valign="top" width="10%">Date Created</th>
			<th align="left" valign="top" width="15%">Last Activity</th>
		</tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>

<style>
	table.datatable td, th {padding-left:5px; padding: 5px;}
	.table_cb {vertical-align: middle; display: block; margin-left:10px;}
</style>


	<button class="button02 firm_admin_add_user">Add New User Account</button>
</section>
<section class="clear"></section>