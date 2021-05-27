<?
/*
 $alphabet=array(
    1=>'A',    2=>'B',    3=>'C',    4=>'D',    5=>'E',    6=>'F',    7=>'G',    8=>'H',    9=>'I',    10=>'J',    11=>'K',    12=>'L',    13=>'M',    14=>'N',    15=>'O',    16=>'P',    17=>'Q',    18=>'R',    19=>'S',    20=>'T',    21=>'U',    22=>'V',    23=>'W',    24=>'X',    25=>'Y',    26=>'Z',    27=>'AA',    28=>'AB',    29=>'AC',    30=>'AD',    31=>'AE',    32=>'AF',    33=>'AG',    34=>'AH',    35=>'AI',    36=>'AJ',    37=>'AK',    38=>'AL',    39=>'AM',    40=>'AN',    41=>'AO',    42=>'AP',    43=>'AQ',    44=>'AR',    45=>'AS',    46=>'AT',    47=>'AU',    48=>'AV',    49=>'AW',    50=>'AX',    51=>'AY',    52=>'AZ',    53=>'BA',    54=>'BB',);
*/

function get_X($num)
{

    if ($num==0) die('Слишком большое число!');

    $alphabet=array();
    for ($i=1; $i < 27; $i++)
    {
        $alphabet[$i]=chr(64+$i);
    }

    $letters=count($alphabet);
    if ($num <= $letters) $lt = $alphabet[$num];
    else
    {
        $t = (int)($num/$letters);
        $first_letter = $alphabet[$t];
        if (empty($alphabet[$num-($t*$letters)])) die('Слишком большое число -'.$num);
        $second_letter = $alphabet[$num-($t*$letters)];
        $lt = $first_letter.$second_letter;
    }
    return $lt;
}


$was_rows=0;




error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');


//require_once dirname(__FILE__) . '/PHPExcel-1.8.1/Classes/PHPExcel.php';
require_once DC . '/sdt/lib/PHPExcel-1.8.1/Classes/PHPExcel.php';



$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objActiveSheet=$objPHPExcel->getActiveSheet();

$HeadStyle=array(
    'font'=>array(
        'bold' => true
    )
);
/*$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');
			*/
			
$y_letter = 1;

			
if ($was_rows==0 && !empty($table_head))
    {			
		foreach ($table_head as $x => $val) {

		$x_letter = $C->excel_alphabet($x+1);

            if (mb_detect_encoding($val)=='ASCII')
                $text = mb_convert_encoding($val,'UTF-8','cp1251');
            else
                $text=$val;

            $objActiveSheet->setCellValue($x_letter . $y_letter, $text);
            $objActiveSheet->getStyle($x_letter . $y_letter)->applyFromArray($HeadStyle);
            $objActiveSheet->getStyle($x_letter . $y_letter)->getAlignment()->setWrapText(true);

//            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($x_letter)->setAutoSize(true);
		}
        $objActiveSheet->getRowDimension($y_letter)->setRowHeight(-1);

		//$y = $y_letter = 1;
	}			
		


$was_rows++;


		
foreach ($array as $y => $value)
{
	//$y_letter=$y+2;
	$y_letter++;
    
	foreach ($value as $x=>$val)
    {
        $x_letter=$C->excel_alphabet($x+1);
        //$y_letter=$y+1;
		
        //$text=cp1251_utf8($val);
        $text = mb_detect_encoding($val);
        if (mb_detect_encoding($val)=='ASCII')
		    $text = mb_convert_encoding($val,'UTF-8','cp1251');
        else
            $text=$val;
        $objActiveSheet->setCellValue($x_letter.$y_letter, $text);
    }
}



$objActiveSheet->setTitle($caption);






//$objPHPExcel->getActiveSheet()->getStyle('A1:A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
$objActiveSheet->getStyle('A1:'.$x_letter.$y_letter)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);



			
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
//header("Content-Type:application/vnd.ms-excel"); //до 2007 - xls
header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition:attachment;filename=$filename_xlsx");
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');


/*xls
$filename_xls = "report.xls";

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition:attachment;filename=$filename_xls");
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');*/