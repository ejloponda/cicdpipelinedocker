
<?php 

$html .= '<style>'.file_get_contents(BASE_FOLDER . 'themes/css/page4style.css').'</style>';
$html .= '<div class="supercontainer">
	<div class="logo">
		<img src="' . BASE_FOLDER . 'themes/images/rpc-logo2.png" width="50px;" height="50px;">
	</div>
	<div class="headers">
		<div class="header1">
			<h1>BILLING INVOICE</h1>
		</div>
		<div class="header2">
			Penthouse 8/F 110 Legazpi St., Legaspi Village, Makati City 1229<br>
			VAT Reg. TIN: 008-612-571-000
		</div>
		<div class="header3">
			No. &nbsp;&nbsp;0012
		</div>
	</div>

	<div class="date">
		<label for="date">DATE:</label>
			<input type="textbox" id="date" value="January 28, 2015" readonly><br>
	</div>

	<div class="info">
		<div class="info-left">
			<label for="soldto" class="soldto">SOLD TO: </label>
				<input class="input-soldto" type="textbox" id="soldto" value="Mary Anne Javate" readonly><br><br>
				
				<label for="tin" class="tin">TIN: </label>
				<input class="input-tin" type="textbox" id="tin" value="000-003-121" readonly><br><br>
				
				<label for="address" class="address">ADDRESS: </label>
				<input class="input-address" type="textbox" id="address" value="Makati City" readonly><br><br>

				<label for="business-style" class="business-style">BUSINESS STYLE: </label>
				<input class="input-business-style" type="textbox" id="business-style" value="January 28, 2015" readonly>
				<label for="service" class="service">SERVICE: </label>
				<input class="input-service" type="textbox" id="service" value="" readonly>
			</div>
			<div class="info-right">
				<label for="terms" class="terms">TERMS: </label>
				<input class="input-terms" type="textbox" id="terms" value="COD" readonly><br><br>
				
				<label for="id-no" class="id-no">OSCA/PWD I.D. NO.: </label>
				<input class="input-id-no" type="textbox" id="id-no" value="005-003-121" readonly><br><br>
				
				<label for="signature" class="signature">SC/PWD SIGNATURE: </label>
				<input class="input-signature" type="textbox" id="signature" value="" readonly><br><br>

				<label for="tin2" class="tin2">SC/PWD TIN: </label>
				<input class="input-tin2" type="textbox" id="tin2" value="" readonly><br><br>
			</div>
	</div>

	<table style="width:100%" class="">
	  <tr class="table-labels">
	    <td class="qty">QTY</td>
	    <td class="unit">UNIT</td>
	    <td class="articles">ARTICLES</td>
	    <td class="unitprice">UNIT PRICE</td>
	    <td class="amount">AMOUNT</td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="1" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="Medication/Treatment" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="1360" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="1360" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="10" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="Consultation" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="2000" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="20,000" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="Less: DISCOUNT: 20% (SC)" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="4272" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="Less: DISCOUNT: 10% (SP)" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="-" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="Supplements can be returned within 30 days" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="from the date of purchase upon approval." readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="TOTAL SALES" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="9,635.00" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="FOR CHECK PAYMENTS:" readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="LESS: Discounts" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="0.00" readonly></td>
	  </tr>

	  <tr>
	    <td class="qty"><input class="table-input" type="textbox" id="qty" value="" readonly></td>
	    <td class="unit"><input class="table-input" type="textbox" id="unit" value="" readonly></td>
	    <td class="articles"><input class="table-input" type="textbox" id="articles" value="ROYAL PREVENTIVE CLINIC, INC." readonly></td>
	    <td class="unitprice"><input class="table-input" type="textbox" id="unitprice" value="LESS: Returns" readonly></td>
	    <td class="amount"><input class="table-input" type="textbox" id="amount" value="0.00" readonly></td>
	  </tr>
	</table>

	<table style="width:100%" class="">
	  <tr class="table-bottom-labels">
	    <td class="preparedby">PREPARED BY</td>
	    <td class="checkedby">CHECKED BY</td>
	    <td class="approvedby">APPROVED BY</td>
	    <td class="totalamtdue"></td>
	    <td class="total"></td>
	  </tr>

	  <tr class="table-bottom-info">
	    <td class="preparedby"><input class="table-input" type="textbox" id="preparedby" value="" readonly></td>
	    <td class="checkedby"><input class="table-input" type="textbox" id="checkedby" value="" readonly></td>
	    <td class="approvedby"><input class="table-input" type="textbox" id="approvedby" value="" readonly></td>
	    <td class="totalamtdue"><label for="total" class="amtdue-label" id="amtdue-label">TOTAL AMOUNT DUE</label></td>
	    <td class="total"><input class="table-input total-value" type="textbox" id="total" value="17,008" readonly></td>
	  </tr>
	</table>

	<div class="invoice-bottom">
		<div class="invoice-bottom-left">
			50 Bklts. (50x4) 0001 - 2500<br>
			BIR Authority to Print No.: OCN9AU0000670662<br>
			Date Issued: 10/25/13 Valid Until: 10/24/18<br>
			ST. GIRARD PRINTERS, INC.<br>
			33 Presidents Ave., BF Homes, Paranaque City<br>
			VAT REG. TIN 200-235-473-000
		</div>

		<div class="invoice-bottom-mid">
			Printer\'s Accrediation No. PROVAN 002201<br>
			Date Assigned: February 14, 2013
		</div>

		<div class="invoice-bottom-right">
			Recieved the above in good order and condition.<br>
			<br>
			<div class="name-sign">(Print name and signature)</div>	
		</div>

		<div class="invoice-footer">
			"THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAXES"<br>
			THIS BILLING INVOICE SHALL BE VALID FOR FIVE (5) YEARS FROM THE DATE OF ATP"
		</div>
	</div>
</div>';