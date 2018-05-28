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
		
		$('#user_role_list_dt').dataTable({
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
			"sAjaxSource": base_url + "admin/getAllRolelist?",
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
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-role-type.png"></li>
			<li><h1>Role Types</h1></li>
		</ul>
		<?php if($mu_roles['can_add']) { ?>
		<button class="button01 firm_admin_add_role">+ Add New Role Type</button>
		<?php } ?>
		<div class="clear"></div>
	</hgroup>

	<table id="user_role_list_dt" class="datatable table" style="min-width: 85%;">
	    <thead>
		<tr>
			
			<th align="left" valign="top" width="80%" style="text-align: center;">Role Name</th>
			<?php if($mu_roles['can_update'] || $mu_roles['can_delete']) { ?><th align="left" valign="top" width="20%"></th><?php } ?>
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