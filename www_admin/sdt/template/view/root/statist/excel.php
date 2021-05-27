<?
$alphabet=array(
    1=>'A',
    2=>'B',
    3=>'C',
    4=>'D',
    5=>'E',
    6=>'F',
    7=>'G',
    8=>'H',
    9=>'I',
    10=>'J',
    11=>'K',
    12=>'L',
    13=>'M',
    14=>'N',

);




error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');


require_once dirname(__FILE__) . '/PHPExcel-1.8.1/Classes/PHPExcel.php';



$objPHPExcel = new PHPExcel();


/*$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");

*/
foreach ($array as $y => $value)
{

    foreach ($value as $x=>$val)
    {
        $x_letter=$alphabet[$x+1];
        $y_letter=$y+1;
        $text=cp1251_utf8($val);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($x_letter.$y_letter, $text);
    }
}

/*$text=cp1251_utf8($text);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $text)
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');
	*/

$caption=cp1251_utf8($caption);
$objPHPExcel->getActiveSheet()->setTitle($caption);






//$objPHPExcel->getActiveSheet()->getStyle('A1:A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$x_letter.$y_letter)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);



			
			/*$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save(str_replace('.php', '.xls', __FILE__));
			
			
			
			$filename = "report.xlsx";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');*/


/* xlsx*/
$filename_xlsx = "report.xlsx";

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');


header("Content-Type:application/vnd.ms-excel");
header("Content-Disposition:attachment;filename=$filename_xlsx");
 



/*xls
$filename_xls = "report.xls";

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition:attachment;filename=$filename_xls");
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');*/