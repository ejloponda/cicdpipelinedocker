<script>
<!--
	$(function() {
		
		$("#filter").on('change', function(){
			var filter = $("#filter").val();
			var filter_to_search = $("#filter_to_search").val();
			loadDatatable(filter, filter_to_search);
		});
		
		$("#filter_to_search").on('change', function(){
			var filter = $("#filter").val();
			var filter_to_search = $("#filter_to_search").val();
			loadDatatable(filter, filter_to_search);
		});
		
		loadDatatable();

		reset_all();
		reset_all_topbars_menu();
		$('.billing_menu').addClass('hilited');
		$('.account_billing_menu').removeClass('hidden');
		$('.sales_report_list').addClass('sub-hilited');
	});

	function loadDatatable(filter, filter_to_search){
		$.post(base_url + "account_billing/getSalesReportFormDatatable",{filter: filter, filter_to_search:filter_to_search},function(o){
			$('#sales_report_list_wrapper').html(o);
		});
	}

	function openReportGenerator(){
		$.post(base_url + "account_billing/loadReportGeneratorModal", {}, function(o){
			$('#report_generator_wrapper').html(o);
			$('#report_generator_wrapper').modal();
		});
	}
-->
</script>
<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-account.png"></li>
			<li><h1>Accounts & Billing - Sales Report</h1></li>
		</ul>

		<ul id="text-search">
			<li><input type="text" name="filter_to_search" id="filter_to_search" placeholder="Input Day" style="width: 200px; height: 25px;padding: 0 0 0 3px; margin: 0 0 0 5px;"></li>
		</ul>
		
		<ul id="filter-search">
			<li>Filter by:</li>
			<li>
				<select name="filter" id="filter" style="width: 70px;" class="select">
					<option value="Day">Day</option>
					<option value="Month">Month</option>
					<option value="Year">Year</option>
				</select>
			</li>
		</ul>
		<div class="clear"></div>

	</hgroup>

	<div id="total_sales_wrapper">
		<div class="left" style="margin-bottom: -3%; font: normal 18px Arial;">
			Total Sales: &#8369; <span id="total_sales_span"></span>
		</div>
	</div>
	<div id="sales_report_list_wrapper"></div>
	<br>
	<button type="button" class="btn btn-info btn-sm" onclick="javascript: openReportGenerator();">Report Generator</button>

</section>
<section class="clear"></section>