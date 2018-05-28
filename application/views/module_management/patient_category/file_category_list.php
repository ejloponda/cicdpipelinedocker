<style>
.correct_alignment
{
	padding-bottom: 35px !important;
}
</style>
<script>
<!--
	$(function() {

		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}

		if($("#alert_confirmation_wrapper")){
			setTimeout(function(){
				$("#alert_confirmation_wrapper").fadeOut();
			}, 3000)
		}
		
		$('#disease_list_dt').dataTable({
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
			"sAjaxSource": base_url + "module_management/getAllFileCategoryList?",
			"fnDrawCallback": function( oSettings ) {
				$('.edit_user').tipsy({gravity: 's'});
				$('.delete_user').tipsy({gravity: 's'});
		    }
		});

		reset_all();
		reset_all_topbars_menu();
		$('.module_management_menu').addClass('hilited');
		$('.medical_history_settings_form').addClass('sub-hilited');
	});
-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1><span id="files_category_list">Files Category</span></h1></li>
			<!-- <li><h1> <span class="not-hilited" id="disease_category">Disease Category</span> <span>:</span> <span class="not-hilited" id="disease_type">Disease Type</span> <span>:</span> <span class="not-hilited" id="doctors_list">Doctors</span> <span>:</span> <span id="files_category_list">Files Category</span></h1></li> -->
		</ul>
		<?php if($mm_fc['can_add'] || $mm_fc['can_update'] || $mm_fc['can_delete']) { ?>
			<button class="button01 get_filecategory_form">+ Add Category</button>
		<?php } ?>
		<div class="clear"></div>

	</hgroup>
	
	
	<table id="disease_list_dt" class="datatable table" style="min-width: 85%;">
	    <thead>
		<tr>
			<th valign="top" width="20%" style="text-align: center;">Category Name</th>
			<th valign="top" width="10%" style="text-align: center;">Status</th>
			<th valign="top" width="10%" style="text-align: center;"></th>
		</tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>
	
<style>
	table.datatable td, th {padding-left:5px; padding: 5px; text-align: center;}
	.table_cb {vertical-align: middle; display: block; margin-left:10px;}
	td {font-size: 12px;}
	.dataTables_paginate{width:100% !important;}
</style>

</section>
<section class="clear"></section>