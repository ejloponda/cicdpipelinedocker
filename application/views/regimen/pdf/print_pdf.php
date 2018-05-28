<?php 

ob_start();

$GLOBALS['image_file'] 	= '<img src="' . BASE_FOLDER . 'themes/images/rpc-logo.png">';
$GLOBALS['image_ext']	= substr($GLOBALS['image_file'], -3,3);
$GLOBALS['patient_name'] = $patient['patient_name'];

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $this->Image($GLOBALS['image_file'], 10, 5, 28, 10, $GLOBALS['image_ext'], '', 'T', false, 300, '', false, false, 0, false, false, false);
    }
    //Page Footer
    public function Footer() {
	    $this->SetY(-10);
	    $this->SetFont('helvetica', 'I', 8);
	    // Page number
	    $this->writeHTMLCell(0, 10, '', '','<div>PATIENT NAME: <b>'.$GLOBALS['patient_name'].'</b></div>', 0, 0, 0, true ,'R' );
	     
	}
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 15);
// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_TOP);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
include('regimen_pdf.php');

$pdf->AddPage( 'P', 'LETTER');
$pdf->SetFontSize(9);
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->lastPage();

ob_end_clean();

// $pdf->Output($filename, 'I');
$pdf->Output($filename, 'D');
