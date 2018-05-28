<style>
.correct_alignment
{
	padding-bottom: 35px !important;
}
</style>
<script>
<!--
	$(function() {
		generateSummary();
		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}
		
		$('#returns_history_list_dt').dataTable({
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
			"sAjaxSource": base_url + "regimen_management/getAllRegimenRecordList?",
			"fnDrawCallback": function( oSettings ) {
				$('.edit_regimen').tipsy({gravity: 's'});
				$('.delete_regimen').tipsy({gravity: 's'});
				$('.regimen_status').tipsy({gravity: 's'});
		    }
		});

		reset_all();
		reset_all_topbars_menu();
		$('.regimen_menu').addClass('hilited');
		$('.regimen_list_form').addClass('sub-hilited');
	});
-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-regimen.png"></li>
			<li><h1>Regimen</h1></li>
		</ul>
		<?php if($rc_reg['can_add']){ ?>
			<button class="button01 new_regimen_form">+ Add New Regimen</button>
		<?php } ?>
		<div class="clear"></div>

	</hgroup>

	
	<?php #if($mm_dc['can_view']){ ?>
	<table id="returns_history_list_dt" class="datatable table" style="min-width: 90%;">
	    <thead>
		<tr>
			<th valign="top" width="10%" style="text-align: center;">Regimen Number</th>
			<th valign="top" width="10%" style="text-align: center;">Patient Name</th>
			<th valign="top" width="10%" style="text-align: center;">Date Generated</th>
			<th valign="top" width="7%" style="text-align: center;">Regimen Duration</th>
			<!-- <th valign="top" width="6%" style="text-align: center;">Year</th> -->
			<th valign="top" width="7%" style="text-align: center;">Status</th>
			<?php if($rc_reg['can_update'] || $rc_reg['can_delete']) { ?>
			<th valign="top" width="5%" style="text-align: center;"> </th>
			<?php } ?>
		</tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>
	<?php #} else { echo "<h1>Ooop! Error viewing this Page. Please contact web administrator!</h1>"; } ?>
<style>
	table.datatable td, th {padding-left:5px; padding: 5px; text-align: center;}
	.table_cb {vertical-align: middle; display: block; margin-left:10px;}
	td {font-size: 12px;}
</style>

</section>
<!-- <section class="clear"></section> -->