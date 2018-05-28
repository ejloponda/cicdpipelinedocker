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
			}, 4000)
		}
		
		$('#reasons_list_dt').dataTable({
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
			"sAjaxSource": base_url + "module_management/getAllReasonsList?",
			"fnDrawCallback": function( oSettings ) {
				$('.edit_user').tipsy({gravity: 's'});
				$('.delete_user').tipsy({gravity: 's'});
		    }
		});

		reset_all();
		reset_all_topbars_menu();
		$('.module_management_menu').addClass('hilited');
		$('.inventory_settings_form').addClass('sub-hilited');
	});
-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
			<li><h1><span id="reasons_list_form">Reasons</span></h1></li>
			<!-- <li><h1><span class="not-hilited" id="dosage_list_form">Dosage Type</span> <span>:</span> <span class="not-hilited" id="quantity_list_form">Quantity Type</span> <span>:</span> <span id="reasons_list_form">Reasons</span></h1></li> -->
		</ul>
		<?php if($mm_dc['can_add']){ ?>
			<button class="button01 add_reason">+ Add Stock Adjustment Reason</button>
		<?php } ?>
		<div class="clear"></div>

	</hgroup>
	
	<?php if($mm_dc['can_view']){ ?>
	<table id="reasons_list_dt" class="datatable table" style="min-width: 85%;">
	    <thead>
		<tr>
			<th valign="top" width="20%" style="text-align: center;">Reason</th>
			<th valign="top" width="10%" style="text-align: center;">Status</th>
			<?php if($mm_dc['can_update'] || $mm_dc['can_delete']) { ?><th valign="top" width="5%" style="text-align: center;"></th><?php } ?>
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

</section>
<section class="clear"></section>