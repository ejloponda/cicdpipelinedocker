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
		
		$('#user_module_scope_list').dataTable({
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
			"sAjaxSource": base_url + "admin/getAllModulelist?",
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
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-lock.png"></li>
			<li><h1>Access Permissions</h1></li>
		</ul>
		<?php if ($mu_ms['can_add']) { ?>
		<button class="button01 add_module_scope_button">+ Add New Module & Scope</button>
		<?php } ?>
		<div class="clear"></div>
	</hgroup>

	<table id="user_module_scope_list" class="datatable table" style="min-width: 85%;">
	    <thead>
		<tr>
			
			<th align="left" valign="top" width="30%" style="text-align: center;">Scope</th>
			<th align="left" valign="top" width="30%" style="text-align: center;">Module</th>
			<?php if ($mu_ms['can_update'] || $mu_ms['can_delete']) { ?><th align="left" valign="top" width="10%"></th><?php } ?>
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
<section class="clear"></section>