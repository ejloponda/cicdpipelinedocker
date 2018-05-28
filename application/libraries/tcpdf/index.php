<?php

$drno = "12341";
$dataprepared = "SEP 21, 2013";

$driver1 = "Driver 1";
$driver2 = "Driver 2";
$plateno = "QWE-123";
$dateofdelivery = "SEP 21, 2013";

$clientname = "TOYOTA - MANILA BAY";
$deliveryaddress = "South Woods Nissan";
$pickupaddress = "Nissan Warehouse";

$pickup_point_arrive = "";
$pickup_point_depart = "";
$deliver_point_arrive = "";
$deliver_point_depart = "";

// Include the main TCPDF library (search for installation path).
require_once('tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RVL Movers');
$pdf->SetTitle('TCPDF Example 006');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 5, 10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

// add a page
$pdf->AddPage( 'P', 'LETTER');


$params = TCPDF_STATIC::serializeTCPDFtagParameters(array($drno, 'C39', '', '', 70, 14, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>1, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));


$html = '
<style>
.medium{
	font-size: 10pt;
}
.small{
	font-size: 9pt;
	font-weight: bold;
}
.title{
	font-size: 14pt;
	font-weight: bold;
}
.bcr{
	border: 1px solid #000;
}

.bdrbtm{
	border-bottom: 1px solid #000;
	border-top: none;
	border-right: none;
	border-left: none;
}
</style>
<div style="width:100%; border: 2px solid #000; ">
	
	<table border="0">
	<tr>
		<td colspan="3" align="center"><h3>Delivery Receipt</h3></td>
	</tr>
	<tr>
		<td rowspan="3" width="110px"><img src="rvllogoonly.png" /></td>
		<td width="288px"><p style="font-size: 8pt;"> </p></td>
		<td rowspan="3" width="297px">
			<table border="0" width="497px">
				<tr>
				<td width="36px"></td>
				<td width="50%" >
				<table border ="0">
				<tr><td width="20%"><p style="font-size: 9pt; font-weight:bold;">DR No.</p></td><td width="80%"><p style="font-size: 7.5pt; text-align:right;">Data Prepared: '.$dataprepared.'</p></td></tr>
				</table>
				<tcpdf method="write1DBarcode" params="'.$params.'" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="288px"><p class="title">RVL MOVERS CORPORATION</p></td>
	</tr>
	<tr>
		<td><p class="medium"> Tel. 809-4124 / 842-4747 | Fax 850-3066</p></td>
	</tr>
	</table>
	

<table border="0">
	<tr>
		<td width="2%"></td>
		<td width="96%">'.MakeItemTable().'</td>
		<td width="2%"></td>
	</tr>
</table>

<table border="0">
	<tr>
		<td width="2%"><p style="font-size: 1pt; "> </p></td>
		<td width="40%" ></td>
		<td width="15%"></td>
		<td width="40%"></td>
		<td width="2%"></td>
	</tr>
	<tr>
		<td><p style="font-size: 5pt; "> </p></td>
		<td class="bdrbtm"><p style="font-size: 5pt; "> </p></td>
		<td><p style="font-size: 5pt; "> </p></td>
		<td class="bdrbtm"><p style="font-size: 5pt; "> </p></td>
		<td><p style="font-size: 5pt; "> </p></td>
	</tr>
	<tr align="center">
		<td></td>
		<td ><p class="small">Received in good order and condition <br /><small>(Signature over Printed name)</small></p></td>
		<td></td>
		<td><p class="small">Approved By</p></td>
		<td></td>
	</tr>
</table>
<b align="center"><small style="font-size:9px;">Original - (O) RVL Copy • Yellow - (A) Accounting Copy • Pink - (C ) Customer Copy • Blue - (D) Driver Copy • Green - (G) Gate Pass</small></b>
</div>
';



// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
// text on center
$pdf->SetY(32);
$pdf->SetX(15);$pdf->SetFont('helvetica', 'R', 10);		$pdf->Cell(100, 0, $clientname, 'B', 1, 'L', 0, '', 0, false, 'T', 'C');
$pdf->SetX(15);$pdf->SetFont('helvetica', 'R', 7);		$pdf->Cell(100, 0, 'CLIENT NAME', '', 1, 'L', 0, '', 0, false, 'T', 'C'); $pdf->Ln();$pdf->Ln(5);
$pdf->SetX(15);$pdf->SetFont('helvetica', 'R', 10);		$pdf->Cell(100, 0, $deliveryaddress, 'B', 1, 'L', 0, '', 0, false, 'T', 'C');
$pdf->SetX(15);$pdf->SetFont('helvetica', 'R', 7); 		$pdf->Cell(100, 0, 'DELIVERY ADDRESS', '', 1, 'L', 0, '', 0, false, 'T', 'C');$pdf->Ln(4);
$pdf->SetX(15);$pdf->SetFont('helvetica', 'R', 10); 	$pdf->Cell(100, 0, $pickupaddress, 'B', 1, 'L', 0, '', 0, false, 'T', 'C');
$pdf->SetX(15);$pdf->SetFont('helvetica', 'R', 7); 		$pdf->Cell(100, 0, 'PICK-UP ADDRESS', '', 1, 'L', 0, '', 0, false, 'T', 'C');


$pdf->SetY(32);

$pdf->SetX(120);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(40, 0, $driver1, 'B', 0,'L', 0, '', 1, false);
$pdf->SetX(162);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(40, 0, $driver2, 'B', 1,'L', 0, '', 1, false);
$pdf->SetX(120);$pdf->SetFont('helvetica', 'R', 8);		$pdf->Cell(80, 0, 'Driver 1', '', 0, 'L', 0, '', 0, false, 'T', 'C');
$pdf->SetX(162);$pdf->SetFont('helvetica', 'R', 8);		$pdf->Cell(80, 0, 'Driver 2', '', 0, 'L', 0, '', 0, false, 'T', 'C');
$pdf->Ln(5);

$pdf->SetX(156);$pdf->SetFont('helvetica', 'R', 10);	$pdf->Cell(30, 5, 'Plate No. : ', '', 0,'L', 0, '', 1, false);
$pdf->SetX(176);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(25, 5, $plateno, 'B', 1,'C', 0, '', 1, false);
$pdf->SetX(145);$pdf->SetFont('helvetica', 'R', 10);	$pdf->Cell(30, 5, 'Date of Delivery : ', '', 0,'L', 0, '', 1, false);
$pdf->SetX(176);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(25, 5, $dateofdelivery, 'B', 1,'C', 0, '', 1, false);
$pdf->Ln(4);
$pdf->SetX(151);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(25, 5, 'Arrival', 'TBLR', 0,'C', 0, '', 1, false);
$pdf->SetX(176);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(25, 5, 'Departure', 'TBLR', 1,'C', 0, '', 1, false);
$pdf->SetX(126);$pdf->SetFont('helvetica', 'R', 10);	$pdf->Cell(25, 5, 'Pick-up Point', 'TBLR', 0,'L', 0, '', 1, false);
$pdf->SetX(151);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(25, 5, $pickup_point_arrive, 'TBLR', 0,'C', 0, '', 1, false);
$pdf->SetX(176);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(25, 5, $pickup_point_depart, 'TBLR', 1,'C', 0, '', 1, false);
$pdf->SetX(126);$pdf->SetFont('helvetica', 'R', 10);	$pdf->Cell(25, 5, 'Delivery Point', 'TBLR', 0,'L', 0, '', 1, false);
$pdf->SetX(151);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(25, 5, $deliver_point_arrive, 'TBLR', 0,'C', 0, '', 1, false);
$pdf->SetX(176);$pdf->SetFont('helvetica', 'B', 10);	$pdf->Cell(25, 5, $deliver_point_depart, 'TBLR', 1,'C', 0, '', 1, false);





// // Print some HTML Cells

// reset pointer to the last page
$pdf->lastPage();

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');
?>
<?php

function MakeItemTable(){

	$itemTable = "<br /><br /><br /><br /><br /><br /><br /><br /><br />";
	
	$itemTable .= '<table border="1">';
	$itemTable .= '	<tr bgcolor="#353535" color="#fff" align="center" class="small medium">
						<td>VIN No.</td>
						<td>Conduction Sticker no.</td>
						<td width="16%">Model</td>
						<td width="18%">Color</td>
						<td width="4%">Qty.</td>
						<td width="6%">Setting</td>
						<td width="27%">Remarks</td>
					</tr>';
	for($i=0; $i<8; $i++)
	{
		$itemTable .= '	<tr align="center" class="medium">
						<td>1234567890</td>
						<td>1234567890</td>
						<td></td>
						<td>Sparkling Brown</td>
						<td>00</td>
						<td></td>
						<td>Remarks</td>
					</tr>';
	}
	$itemTable .= '</table>';
	$itemTable .= '<table border="1"><tr class="medium">
						<td width="62.55%"> &nbsp;<span class="small medium">TOTAL UNIT </span></td>
						<td width="4%" align="center"><b>1</b></td>
					</tr></table>';
	return $itemTable;
}

?>