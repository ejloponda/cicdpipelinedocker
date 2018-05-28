<?php

  require_once APPPATH."third_party/php-excel.class.php"; 

  $xls = new Excel_XML('UTF-8', false, "Generated Report");

  $xls->addArray($invoice_array);
  $xls->generateXML($filename);
