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
		
		$('#returns_list_dt').dataTable({
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
			"sAjaxSource": base_url + "returns_management/loadReturnsTables?",
			"fnDrawCallback": function( oSettings ) {
		    }
		});

		reset_all();
		reset_all_topbars_menu();
		$('.inventory_management_menu').addClass('hilited');
		$('.returns_form').addClass('sub-hilited');

		$('.add_returns').on('click', function(){
			loadModalChoose();
		});

	});
-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Returns</h1></li>
		</ul>
		<?php if($rets['can_add']){ ?>
			<button class="button01 add_returns">+ Return Invoice</button>
		<?php } ?>
		<div class="clear"></div>

	</hgroup>

	<table id="returns_list_dt" class="datatable table" style="min-width: 90%;">
	    <thead>
		<tr>
			<th valign="top" width="10%" style="text-align: center;">Invoice No.</th>
			<th valign="top" width="10%" style="text-align: center;">Patient Name</th>
			<th valign="top" width="10%" style="text-align: center;">Date of Return</th>
			<th valign="top" width="10%" style="text-align: center;">Status</th>
		</tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>
<style>
	table.datatable td, th {padding-left:5px; padding: 5px; text-align: center;}
	.table_cb {vertical-align: middle; display: block; margin-left:10px;}
	td {font-size: 12px;}
</style>

</section>
<section class="clear"></section>