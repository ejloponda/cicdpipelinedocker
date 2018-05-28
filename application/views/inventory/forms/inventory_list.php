<script>
<!--var stock_percentage;
	$(function() {
		loadInventoryListTable();
		reset_all();
		reset_all_topbars_menu();
		$('.inventory_management_menu').addClass('hilited');

		$("#stock_percentage").on('blur', function(){
			// alert(stock_percentage);
			var notify_mode = $("#notify_mode").is(':checked');
			// alert(notify_mode);
			if(notify_mode){
				loadInventoryListTable();
			}
		});

		$("#notify_mode").live('click', function(){
			// alert(stock_percentage);
			var notify_mode = $("#notify_mode").is(':checked');
			// alert(notify_mode);
			if(notify_mode){
				$("#stock_percentage").focus();
			} else {
				$("#stock_percentage").val(0);
				loadInventoryListTable();
			}
		});

		if($("#alert_confirmation_wrapper")){
			setTimeout(function(){
				$("#alert_confirmation_wrapper").fadeOut();
			}, 3000)
		}
		
	});
-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-inventory.png"></li>
			<li><h1>Inventory</h1></li>
		</ul>
		<?php #debug_array($invent) ?>
		<?php if($invent['can_add']){ ?>
			<button class="button01 add_new_inventory">+ Add New Inventory</button>
		<?php } ?>
		<div class="clear"></div>

	</hgroup>

	<ul id="checked">
		<li><input type="checkbox" id="notify_mode"></li>
		<li>Notify if stock is </li>
		<li><input type="text" name="stock_percentage" id="stock_percentage"></li>
		<li>%</li>
	</ul>

	<div id="inventory_list_wrapper">
		
	</div>
	

</section>
<!-- <section class="clear"></section> -->