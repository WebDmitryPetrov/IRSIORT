<?php

gc_enable();


$C = Controller::getInstance();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');


require_once dirname(__FILE__) . '/PHPExcel-1.8.1/Classes/PHPExcel.php';


$filecounter = 1;

//$caption = cp1251_utf8($caption);
$caption = mb_convert_encoding($caption, 'UTF-8', 'cp1251');

$objPHPExcel = new PHPExcel();


$was_rows = 0;
$y = $y_letter = 1;
/**
 * @param $y
 * @param $was_rows
 * @param $value
 * @param $C
 * @param $objPHPExcel
 * @param $limiter
 * @param $caption
 * @param $filecounter
 * @param $table_head
 * @return array
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 */
function fuckingShit(&$y, &$was_rows, $value, $C, $objPHPExcel, $limiter, $caption, &$filecounter, $table_head, $year, $month, $org_name, $report_name, $temp_dir)
{


    if ($was_rows == 0) {
        foreach ($table_head as $x => $val) {

            $x_letter = $x;
            $y_letter = 1;
            $text = mb_convert_encoding($val, 'UTF-8', 'cp1251');

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($x_letter . $y_letter, $text);

        }
        $y = $y_letter = 1;
    }


    $y_letter = ++$y;
    $was_rows++;

    foreach ($value as $x => $val) {
        $x_letter = $x;

        if ($x == 'I') {
            $text = ($val) ? $C->date($val) : '';

        } else if ($x == 'J') {
            if ($val == null || $val == '0000-00-00') $text = 'Бессрочно';
            else $text = $C->date($val);
        } else if ($x == 'P') {
            if ($val == null || $val == '0000-00-00') {
            } else $text = $C->date($val);
        } else if ($x == 'F') {
            $text = trim(str_replace('(н)', '', mb_convert_encoding($val, 'UTF-8', 'cp1251')));
        } else if ($x == 'O') {
            if (empty($val)) $text = 'Оригинал';
            else $text = 'Дубликат';
        } else if ($x == 'A' || $x == 'B' || $x == 'C' || $x == 'D') {
            $text = (!empty($val)) ? mb_convert_encoding($val, 'UTF-8', 'cp1251') : '-';
        } else {
            $text = mb_convert_encoding($val, 'UTF-8', 'cp1251');
        }


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($x_letter . $y_letter, $text);
        unset($text);
    }


//    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $y_letter, (memory_get_usage(1) / 1024 / 1024));


    unset($value);
    unset($val);

    if ($y % $limiter == 0) {
        $objPHPExcel->setActiveSheetIndex(0)->garbageCollect();
        $objPHPExcel->garbageCollect();


//        $caption = cp1251_utf8($caption);
        $objPHPExcel->getActiveSheet()->setTitle($caption);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $x_letter . $y_letter)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $filename_xlsx = $report_name . "_" . $org_name . "_" . $year . "_" . date('m', mktime(0, 0, 0, $month, 1)) . "-" . $filecounter++ . ".xlsx";
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', $temp_dir . '\\' . $filename_xlsx));
        /** @var PHPExcel $objPHPExcel */


//        $objPHPExcel->getActiveSheet()->removeRow(1,$limiter); //очень долго, но не жрет память


        $objPHPExcel->removeSheetByIndex(0);
        $objPHPExcel->createSheet(0);

        unset($objWriter);


        $was_rows = 0;

        gc_collect_cycles();

        $objPHPExcel = new PHPExcel();


    }

}

//while ($value = mysql_fetch_assoc($array)) {
foreach ($frdoGenerator() as $value) {
    fuckingShit($y, $was_rows, $value, $C, $objPHPExcel, $limiter, $caption, $filecounter, $table_head, $year, $month, $org_name, $report_name, $temp_dir);
}


//}


if (!empty($was_rows)) {


    $objPHPExcel->getActiveSheet()->setTitle($caption);
    $objPHPExcel->getActiveSheet()->getStyle('A1:' . 'N' . $y)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $filename_xlsx = $report_name . "_" . $org_name . "_" . $year . "_" . date('m', mktime(0, 0, 0, $month, 1)) . "-" . $filecounter++ . ".xlsx";
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(str_replace('.php', '.xlsx', $temp_dir . '\\' . $filename_xlsx));
}

