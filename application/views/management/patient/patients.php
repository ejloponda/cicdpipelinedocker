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
			// "iDisplayLength": 10,
			"sAjaxSource": base_url + "patient_management/getAllPatientsList?",
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
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1>Patients List</h1></li>
		</ul>
		<?php if($pm_pi['can_add']) { ?>
		<button class="button01 add_patient_menu">+ Add New Patient</button>
		<?php } ?>
		<div class="clear"></div>
	</hgroup>

	<table id="user_role_list_dt" class="datatable table" style="min-width: 98%;">
	    <thead>
		<tr>
			<th valign="top" width="10%" style="text-align: center;">Patient ID</th>
			<th valign="top" width="20%" style="text-align: center;">Patient Name</th>
			<th valign="top" width="10%" style="text-align: center;">Appointment Date</th>
			<th valign="top" width="10%" style="text-align: center;">Gender</th>
			<th valign="top" width="12%" style="text-align: center;">Date of Birth</th>
			<th valign="top" width="20%" style="text-align: center;">Place of Birth</th>
			<?php if($pm_pi['can_update'] || $pm_pi['can_delete']) { ?><th valign="top" width="10%" style="text-align: center;"></th><?php } ?>
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


	<!-- <button class="button02 add_patient_menu">+ Add New Patient</button> -->
</section>
<!-- <section class="clear"></section> -->