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
		
		$('#disease_type_list_dt').dataTable({
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
			"sAjaxSource": base_url + "module_management/getAllDiseaseTypeList?",
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
			<li><h1><span id="disease_type">Disease Type</span> </h1></li>
			<!-- <li><h1> <span id="disease_category" class="not-hilited">Disease Category</span> <span>:</span> <span id="disease_type">Disease Type</span> <span>:</span> <span class="not-hilited" id="doctors_list">Doctors</span> <span>:</span> <span class="not-hilited" id="files_category_list">Files Category</span></h1></li> -->
		</ul>
		<?php if($mm_dt['can_add']){ ?>
			<button class="button01 add_disease_type">+ Add Disease Type</button>
		<?php } ?>
		<div class="clear"></div>

	</hgroup>

	<?php if($mm_dt['can_view']){ ?>
	<table id="disease_type_list_dt" class="datatable table" style="min-width: 85%;">
	    <thead>
		<tr>
			<th valign="top" width="20%" style="text-align: center;">Disease Type</th>
			<th valign="top" width="20%" style="text-align: center;">Disease Category</th>
			<th valign="top" width="10%" style="text-align: center;">Status</th>
			<?php if($mm_dt['can_update'] || $mm_dt['can_delete']) { ?><th valign="top" width="10%" style="text-align: center;"></th><?php } ?>
		</tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>
	<?php } else { echo "<h1>Ooop! Error viewing this Page. Please contact web administrator!</h1>"; } ?>

<style>
	table.datatable td, th {padding-left:5px; padding: 5px; text-align: center;}
	.table_cb {vertical-align: middle; display: block; margin-left:10px;}
	td {font-size: 12px;}
	.dataTables_paginate{width:100% !important;}
</style>


	<!-- <button class="button02 add_patient_menu">+ Add New Patient</button> -->
</section>
<section class="clear"></section>