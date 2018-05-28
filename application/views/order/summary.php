<script type="text/javascript">
	$(function(){
		if($('.tipsy-inner')) {
			$('.tipsy-inner').remove();
		}
	});
</script>

<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER?>themes/images/header-order.png"></li>
			<li><h1>Order</h1></li>
		</ul>
		
		<ul id="controls">
			<li><a href="javascript: void(0);" onClick="javascript: loadSummary(<?php echo $order['id'] ?>)"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_refresh.png"></a></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" onClick="javascript: print_summary(<?php echo $order['id'] ?>, <?php echo $version_id ?>);"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_print.png"></a></li>
			<?php #if($rc_reg['can_update']) { ?>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" onClick="javascript: edit_order(<?php echo $order['id'] ?>, 0)"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_edit.png"></a></li>
			<?php #} ?>
			<?php #if($rc_reg['can_delete']) { ?>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER?>themes/images/dot.png" ></li>
			<li><a href="javascript: void(0);" onClick="javascript: delete_order(<?php echo $order['id'] ?>)"><img class="icon" src="<?php echo BASE_FOLDER?>themes/images/icon_trash.png"></a></li>
			<?php #} ?>
		</ul>
		
		<div class="clear"></div>
	</hgroup>
	

	<ul class="regimen-ID" >
		<li><img class="photoID" src="<?php echo ($photo ?  BASE_FOLDER . $photo['base_path'] . $photo['filename'] . '.' . $photo['extension'] : BASE_FOLDER . 'themes/images/photo.png') ?>"></li>
		<li class="patient"><span><?php echo $patient['patient_name'] ?></span><br>
			Patient Code: <b><?php echo $patient['patient_code'] ?></b> 
			Order No: <b><?php echo $order['order_no'] ?></b>
		</li>
	</ul>
	
	<ul id="filter-search" style="padding-right: 36px;">
		<li><b>Date Generated:</b> <?php $date = new Datetime($order['date_created']); $date_generated = date_format($date, "M d, Y"); echo $date_generated; ?></li>
	</ul>
	<div class="clear"></div>
	
	<br>
	
	<div class="meal">Summary</div>
	<table class="table" >
			<th>Medicine Name</th>
			<th>Total Quantity</th>
			
			<?php foreach ($summary_meds as $key => $value) { ?>
				
			<?php } ?>
	</table>
	
	
	<br>
	<section id="buttons" style="padding-right:16px;">
		<button type="button" class="form_button-green" onClick="javascript: print_summary(<?php echo $reg['id'] ?>, <?php echo $version_id ?>);">Print Summary</button>
		<?php if($invoicing['can_add']){ ?>
			<button type="button" class="form_button" onClick="javascript: convertInvoice(<?php echo $reg['id'] ?>, <?php echo $version_id ?>);">Convert to Invoice</button>
		<?php } ?>
		<button type="button" class="form_button back_to_regimen">Back</button>
	</section>						

	<script>
		$(function(){
			var regimen_id = "<?php echo $reg['id'] ?>";
			var version_id = "<?php echo $version_id ?>";
			$(".back_to_regimen").on('click', function(){
				if(version_id == 0){
					view_regimen(regimen_id);
				} else {
					view_version(version_id);
				}
			});

			$(".medicine_availability").on('click', function(){

				var id = $(this).data("id");

				var x = confirm("Is it availble?");
				if(x){
					$.post(base_url+'regimen_management/update_availability',{id:id},function(){
						view_regimen_summary(regimen_id);
					});
				}
			});
		});

		function print_summary(regimen_id, version_id){
			window.open(base_url+'download/generate_summary/'+regimen_id+'/'+version_id,"_blank");
		}
	</script>
</section>