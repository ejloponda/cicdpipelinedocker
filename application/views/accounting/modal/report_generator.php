<style>
.datepicker{z-index:1151;}
</style>
<div class="modal-dialog"> 
	<div class="modal-content">
		<div class="modal-header">
			<h3>Report Generator</h3>
		</div>
		<div class="modal-body">
			<center>
				<select class="form-control" style="width: 300px;" id="type_of_sale">
					<option value="AllSales">All Sales</option>
					<option value="AllAR">All Accounts Receivables</option>
				</select>
				<br/><br/>
			</center>
			<div role="tabpanel">

			 	 <!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Date Range</a></li>
					<li role="presentation"><a href="#monthly" aria-controls="monthly" role="tab" data-toggle="tab">Monthly</a></li>
					<li role="presentation"><a href="#yearly" aria-controls="yearly" role="tab" data-toggle="tab">Yearly</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="home">
						<br/>
						<center>
							<input type="text" class="textbox datepicker" id="from_date" placeholder="FROM" style="width: 200px;">  - <input type="text" placeholder="TO" class="textbox datepicker" id="to_date"  style="width: 200px;">
							<br/><br/>
							<button type="button" id="generate_date_range" class="btn btn-default btn-sm">Generate Report</button>
						</center>
					</div>
					<div role="tabpanel" class="tab-pane" id="monthly">
						<br/>
						<center>
							<select id="month_date" class="form-control" style="width: 200px;">
								<option value="01">January</option>
								<option value="02">February</option>
								<option value="03">March</option>
								<option value="04">April</option>
								<option value="05">May</option>
								<option value="06">June</option>
								<option value="07">July</option>
								<option value="08">August</option>
								<option value="09">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select>

							<br/><br/>
							<button type="button" id="generate_month" class="btn btn-default btn-sm">Generate Report</button>
						</center>
					</div>
					<div role="tabpanel" class="tab-pane" id="yearly">
						<br/>
						<center>
							<input type="text" class="textbox datepicker" id="year_date" placeholder="Year" style="width: 200px;">
							<br/><br/>
							<button type="button" id="generate_year" class="btn btn-default btn-sm">Generate Report</button>
						</center>
					</div>
				</div>

			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>

<script type="text/javascript">
	
	$(function(){
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		var start_date = $('#from_date').datepicker({
			format: 'yyyy-mm-dd',
		  onRender: function(date) {
		    return date.valueOf() < now.valueOf() ? '' : '';
		  }
		}).on('changeDate', function(ev) {
		  if (ev.date.valueOf() > expiration_date.date.valueOf()) {
		    var newDate = new Date(ev.date)
		    newDate.setDate(newDate.getDate() + 1);
		    expiration_date.setValue(newDate);
		  }
		  start_date.hide();
		  $('#to_date')[0].focus();
		}).data('datepicker');
		var expiration_date = $('#to_date').datepicker({
			format: 'yyyy-mm-dd',
		  onRender: function(date) {
		    return date.valueOf() <= start_date.date.valueOf() ? '' : '';
		  }
		}).on('changeDate', function(ev) {
		  expiration_date.hide();
		}).data('datepicker');

		$("#year_date").datepicker({
			format: " yyyy",
			viewMode: "years", 
    		minViewMode: "years"
		});

		$("#generate_date_range").on('click', function(){
			var type_of_sale = $("#type_of_sale").val();
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();

			if(hasValue(from_date) && hasValue(to_date)){

				$.post(base_url + "account_billing/generateReportDateRange", {from_date: from_date, to_date: to_date, type_of_sale: type_of_sale}, function(o){
					if(o.is_successful){
						window.open(base_url + "download/generate_report_date_range/" + from_date + "/" + to_date + "/" + type_of_sale,'_blank');
						new PNotify({
					      title: "Generate Report",
					      text: "We have successfully generated the report.",
					      type: "info",
					    });
					} else {
						new PNotify({
					      title: "Oops! Error!",
					      text: o.message,
					      type: "error",
					    });
					}
				}, "json");
				
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please enter Dates.",
			      type: "error",
			    });
			}
		});

		$("#generate_month").on('click', function(){
			var type_of_sale = $("#type_of_sale").val();
			var month = $("#month_date").val();

			if(hasValue(month)){

				$.post(base_url + "account_billing/generateReportMonthly", {month:month, type_of_sale: type_of_sale}, function(o){
					if(o.is_successful){
						window.open(base_url + "download/generate_report_month/" + month + "/" + type_of_sale,'_blank');
						new PNotify({
					      title: "Generate Report",
					      text: "We have successfully generated the report.",
					      type: "info",
					    });
					} else {
						new PNotify({
					      title: "Oops! Error!",
					      text: o.message,
					      type: "error",
					    });
					}
				}, "json");
				
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please enter Dates.",
			      type: "error",
			    });
			}
		});

		$("#generate_year").on('click', function(){
			var type_of_sale = $("#type_of_sale").val();
			var year = $.trim($("#year_date").val());

			if(hasValue(year)){

				$.post(base_url + "account_billing/generateReportYearly", {year:year, type_of_sale: type_of_sale}, function(o){
					if(o.is_successful){
						window.open(base_url + "download/generate_report_year/" + year + "/" + type_of_sale,'_blank');
						new PNotify({
					      title: "Generate Report",
					      text: "We have successfully generated the report.",
					      type: "info",
					    });
					} else {
						new PNotify({
					      title: "Oops! Error!",
					      text: o.message,
					      type: "error",
					    });
					}
				}, "json");
				
			} else {
				new PNotify({
			      title: "Oops! Error!",
			      text: "Error generating report, Please enter Dates.",
			      type: "error",
			    });
			}
		});
	});
</script>