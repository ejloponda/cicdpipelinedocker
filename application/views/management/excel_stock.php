<?PHP
  
  require_once APPPATH."third_party/php-excel.class.php"; 

  $xls = new Excel_XML('UTF-8', false, "Stock History");
  $xls->addArray($stock_array);
  $xls->generateXML($filename);


  /** Error reporting */
// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);
// date_default_timezone_set('Asia/Manila');

// if (PHP_SAPI == 'cli')
// 	die('This example should only be run from a Web Browser');

// /** Include PHPExcel */
// require_once APPPATH. 'third_party/PHPExcel.php';


// // Create new PHPExcel object
// $objPHPExcel = new PHPExcel();

// // Add some data
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A1', 'Hello')
//             ->setCellValue('B2', 'world!')
//             ->setCellValue('C1', 'Hello')
//             ->setCellValue('D2', 'world!');

// // Miscellaneous glyphs, UTF-8
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A4', 'Miscellaneous glyphs')
//             ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// // Rename worksheet
// $objPHPExcel->getActiveSheet()->setTitle('Simple');


// // Set active sheet index to the first sheet, so Excel opens this as the first sheet
// $objPHPExcel->setActiveSheetIndex(0);

// // Redirect output to a client’s web browser (Excel5)
// header('Content-Type: application/vnd.ms-excel');
// header('Content-Disposition: attachment;filename="01simple.xls"');
// header('Cache-Control: max-age=0');
// // If you're serving to IE 9, then the following may be needed
// header('Cache-Control: max-age=1');

// // If you're serving to IE over SSL, then the following may be needed
// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
// header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
// header ('Pragma: public'); // HTTP/1.0

// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
// $objWriter->save('php://output');
// exit;


?>