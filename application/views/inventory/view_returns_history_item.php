<script>
	$(function(){
		$('.tipsy-inner').remove();
		$('#add_button_quantity').tipsy({gravity: 's'});
		$('#minus_button_quantity').tipsy({gravity: 's'});
		$('#add_quantity').tipsy({gravity: 's'});
		reset_all();
		reset_all_topbars_menu();
		$('.inventory_management_menu').addClass('hilited');
		$('.returns_form').addClass('sub-hilited');
		// $('.inventory_list_form').addClass('sub-hilited');

       $('#regimen_history_list_dt').dataTable( {
	        "bJQueryUI": true,
	        "sPaginationType": "full_numbers",
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,     
	        "bScrollCollapse": false
       });

       $('#regimen_history_list_dt_length').hide();
       $('#regimen_history_list_dt_filter').hide();

	});
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-inventory.png"></li>
			<li><h1>Edit Inventory</h1></li>
		</ul>
		
		<div class="id-number">ID Number: <input type="text" name="item_id_view" id="item_id_view" value="<?php echo $inv['id'] ?>" readonly></div>
		<div class="clear"></div>
	</hgroup>	
	<input type="hidden" name="item_id" id="item_id" value="<?php echo $inv['id'] ?>">
	<h1>Return History</h1> 

	<table id="regimen_history_list_dt" class="datatable table">
		<thead>
			<tr>
				<th style="width: 10%; text-align: center;">Invoice Number</th>
				<th style="width: 20%; text-align: center;">Reason of Return</th>
				<th style="width: 5%; text-align: center;">Quantity</th>
				<th style="width: 5%; text-align: center;">Date of Return</th>
			</tr>
		</thead>
		<tbody>	
			<?php foreach ($returns as $key => $value) { ?>
				<tr>
					<td class="first"><?php echo $value['invoice_number'] ?></td>
					<td><?php echo $value['reason'] ?></td>
					<td><?php echo $value['quantity'] ?></td>
					<td><?php echo $value['date_return'] ?></td>
				</tr>
			<?php } ?>
			
		</tbody>
		
	</table>
	<?php #debug_array($returns) ?>

		
</section>
<!-- <section class="clear"></section> -->
<!-- </section> -->