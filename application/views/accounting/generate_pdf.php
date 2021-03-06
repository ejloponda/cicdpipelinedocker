<?php 

ob_start();

$GLOBALS['image_file'] 	= '<img src="' . BASE_FOLDER . 'themes/images/rpc-logo.png">';
$GLOBALS['image_ext']	= substr($GLOBALS['image_file'], -3,3);

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $this->Image($GLOBALS['image_file'], 10, 5, 28, 10, $GLOBALS['image_ext'], '', 'T', false, 300, '', false, false, 0, false, false, false);
    }
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

#$pdf->setPrintHeader(false);
#$pdf->setPrintFooter(false);

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 5, 15);
// $pdf->SetAutoPageBreak(TRUE, 55);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

include('invoice_pdf.php');
$date_today = date("Ymd");
$filename 	= "{$patient_code}-{$date_today}.pdf";

// debug_array($html);
$pdf->AddPage( 'P', 'LETTER');
$pdf->SetFontSize(9);
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->lastPage();

ob_end_clean();

// $pdf->Output($filename, 'I');
$pdf->Output($filename, 'D');
