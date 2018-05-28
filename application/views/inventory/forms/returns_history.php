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
			"sAjaxSource": base_url + "inventory_management/getAllReturnsHistoryList?",
			"fnDrawCallback": function( oSettings ) {
				$('.edit_return_item').tipsy({gravity: 's'});
				$('.delete_return_item').tipsy({gravity: 's'});
		    }
		});

		reset_all();
		reset_all_topbars_menu();
		$('.inventory_management_menu').addClass('hilited');
		$('.returns_form').addClass('sub-hilited');
	});
-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-inventory.png"></li>
			<li><h1>Returns History</h1></li>
		</ul>
		<?php #if($mm_dc['can_add']){ ?>
			<button class="button01 new_returns_form">+ Return Item</button>
		<?php #} ?>
		<div class="clear"></div>

	</hgroup>

	
	<?php #if($mm_dc['can_view']){ ?>
	<table id="returns_history_list_dt" class="datatable table" style="min-width: 90%;">
	    <thead>
		<tr>
			<th valign="top" width="8%" style="text-align: center;">Medicine Name</th>
			<th valign="top" width="8%" style="text-align: center;">From Stock</th>
			<th valign="top" width="3%" style="text-align: center;">Quantity</th>
			<th valign="top" width="7%" style="text-align: center;">Cost per Item</th>
			<th valign="top" width="6%" style="text-align: center;">Total Cost</th>
			<th valign="top" width="10%" style="text-align: center;">Quantity Returned</th>
			<th valign="top" width="10%" style="text-align: center;">Returned By</th>
			<?php #if($mm_dc['can_update'] || $mm_dc['can_delete']) { ?>
			<th valign="top" width="5%" style="text-align: center;"> </th>
			<?php #} ?>
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
<section class="clear"></section>