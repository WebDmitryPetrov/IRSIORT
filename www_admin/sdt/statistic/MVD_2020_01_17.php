<?php

namespace SDT\statistic;
require_once 'AbstrctReport.php';

use League\Flysystem\Adapter\Local;
use League\Flysystem\Config;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 10:58
 * Отчет 12
 */
class MVD_2020_01_17 extends \AbstractReport
{


    public function __construct()
    {
        gc_enable();
        ini_set('memory_limit', '5000M');
        set_time_limit(0);
        ini_set('implicit_flush', 1);
        ob_implicit_flush(1);
    }

    public function execute($from, $to, $head_org, $lc, $test_type, $files = false)
    {

        $this->writeLog('Выборка запущена');
        $folder = $this->createFolder();
        $connection = \Connection::getInstance();
        $cert_sql = $this->getCertSql($from, $to, $head_org, $lc,$test_type);


        $dubl_sql = $this->getDublSql($from, $to, $head_org, $lc,$test_type);
//        echo $cert_sql; die;;

        $result = $connection->byRow($cert_sql);
        $dubl = $connection->byRow($dubl_sql);

        $this->writeLog('Данные получены');
        $time = microtime(1);
        list($origFiles, $dublFiles) = $this->writeExcel($folder, $result, $dubl);
        $this->writeLog('Excel сформирован');
        $this->writeLog('Актов: ' . $origFiles->countAct());
        $this->writeLog('Протестированных: ' . $origFiles->countPeople());
        $this->writeLog('Дубликатов: ' . $dublFiles->countPeople());
        unset($result, $dubl);
        if ($files) {
            $this->writeLog('Обработка файлов оригиналов');
            $resultOrig = $this->writeFiles($folder . '/orig', $origFiles);

            $this->writeLog('Обработка файлов дубликатов');
            $resultDubl = $this->writeFiles($folder . '/dubl', $dublFiles);

            $this->writeLog('Данные сформированы');


            $orig = $this->templateFileNotFound($resultOrig['not_found'], 'Не найдено файлов среди оригиналов');

            if ($orig)
                $this->writeLog($orig);

            $dubl = $this->templateFileNotFound($resultDubl['not_found'], 'Не найдено файлов среди дубликатов');
            if ($dubl)
                $this->writeLog($dubl);
        }
//        var_dump('time: ' . (microtime(1) - $time));
        gc_collect_cycles();

        $this->writeLog('Отчет размещён на сервере в каталоге <strong>' . $folder . '</strong>');
        return [$folder];
    }

    private function templateFileNotFound(array $list, $caption)
    {
        if (!$list) {
            return false;
        }
//        die(var_dump($list));
        $keys = [
            'file_act' => 'Скан акта',
            'file_act_table' => 'Скан сводной таблицы',
            'summary' => 'Сводный протокол',
            'html_act' => 'Акт html',
            'html_act_table' => 'Сводная таблица html',
            'man_passport' => 'Документ удостоверяющий личность',

        ];
        $result = '<strong>' . $caption . '</strong><br>';
        $result .= '<table border="1" cellpadding="2">';
        foreach ($list as $act_id => $data) {
            $result .= '<tr><td colspan="2"><strong>Акт id ' . $act_id . '</strong></td></tr>';

            foreach ($data['act'] as $key => $path) {
                $result .= vsprintf('<tr><td>%s</td><td>%s</td></tr>', [
                    $keys[$key],
                    $path,
                ]);
            }
            foreach ($data['people'] as $key => $path) {
                $result .= vsprintf('<tr><td>%s</td><td>%s</td></tr>', [
                    'Паспорт ' . $key,
                    $path,
                ]);
            }

        }
        $result .= '</table>';
        return $result;
    }

    /**
     * @param  $folder
     * @param FileCollection $files
     */
    public function writeFiles($folder, FileCollection $files)
    {
        $fs_dist = new Local($folder);
//        $fsFileUpload = new Local(SDT_UPLOAD_DIR);
        $config = new Config();
        $notFoundFiles = [];
        $addNotFound = function ($act, $type, $key, $value) use (&$notFoundFiles) {
            if (!array_key_exists($act, $notFoundFiles)) $notFoundFiles[$act] = [
                'act' => [],
                'people' => [],
            ];
            $notFoundFiles[$act][$type][$key] = $value;
        };


        $actProcessed = 0;
        foreach ($files->getItems() as $act_id => $data) {
            if ($actProcessed % 500 == 0 && $actProcessed > 0) {
                $this->writeLog('Обработано ' . $actProcessed);
            }
            $actProcessed++;
            $res = $fs_dist->createDir($act_id, $config);


//            var_dump($data);
            $fs_dist->createDir($act_id . DIRECTORY_SEPARATOR . 'people', $config);
            $fs_dist->createDir($act_id . DIRECTORY_SEPARATOR . 'act', $config);

//            foreach ($data['people'] as $man_id => $man) {
//                $fs_dist->createDir($act_id . DIRECTORY_SEPARATOR . 'people' . DIRECTORY_SEPARATOR . $man_id,$config);
//            }

            if ($file_act_id = $data['act']['file_act_id']) {
                $file = \File::getByID($file_act_id);
                try {
                    $stream = $file->getStream();
//                    $stream = $fsFileUpload->readStream($file->filename);
                    $ext = pathinfo($file->caption, PATHINFO_EXTENSION);
                    $caption = 'Скан акта' . '.' . $ext;
                    $fs_dist->writeStream($act_id . DIRECTORY_SEPARATOR . 'act' . DIRECTORY_SEPARATOR . $caption, $stream, $config);
                } catch (\Exception $ex) {
                    if ($ex->getMessage() == 'not_found') {

                        $addNotFound($act_id, 'act', 'file_act', $file->getLocation());
                    }
                }
            }
            if ($file_act_tabl_id = $data['act']['file_act_tabl_id']) {
                $file = \File::getByID($file_act_tabl_id);
                try {
                    $stream = $file->getStream();
//                    $stream = $fsFileUpload->readStream($file->filename);
                    $ext = pathinfo($file->caption, PATHINFO_EXTENSION);
                    $caption = 'Скан сводной таблицы' . '.' . $ext;
                    $fs_dist->writeStream($act_id . DIRECTORY_SEPARATOR . 'act' . DIRECTORY_SEPARATOR . $caption, $stream, $config);
                } catch (\Exception $ex) {
                    if ($ex->getMessage() == 'not_found') {
//                        $notFoundFiles['file_act_table_' . $act_id] = $file->getLocation();
                        $addNotFound($act_id, 'act', 'file_act_table', $file->getLocation());
                    }
                }
            }

            if ($summary_table_id = $data['act']['summary_table_id']) {
                $file = \ActSummaryTable::getByID($summary_table_id);
                try {
                    $stream = $file->getStream();
//                    $stream = $fsFileUpload->readStream($file->filename);
                    $ext = pathinfo($file->caption, PATHINFO_EXTENSION);
                    $caption = 'Сводный протокол' . '.' . $ext;
                    $fs_dist->writeStream($act_id . DIRECTORY_SEPARATOR . 'act' . DIRECTORY_SEPARATOR . $caption, $stream, $config);
                } catch (\Exception $ex) {
                    if ($ex->getMessage() == 'not_found') {
//                        $notFoundFiles['summary_' . $act_id] = $file->getLocation();
                        $addNotFound($act_id, 'act', 'summary', $file->getLocation());
                    }
                }
            }
            if ($html_act = $data['act']['html_act']) {
                $file = \ActSummaryTable::getByID($html_act);
                try {
                    $stream = $file->getStream();
//                    $stream = $fsFileUpload->readStream($file->filename);
                    $ext = pathinfo($file->caption, PATHINFO_EXTENSION);
                    $caption = 'Акт html' . '.' . $ext;
                    $fs_dist->writeStream($act_id . DIRECTORY_SEPARATOR . 'act' . DIRECTORY_SEPARATOR . $caption, $stream, $config);
                } catch (\Exception $ex) {
                    if ($ex->getMessage() == 'not_found') {
//                        $notFoundFiles['html_act_' . $act_id] = $file->getLocation();
                        $addNotFound($act_id, 'act', 'html_act', $file->getLocation());
                    }
                }
            }
            if ($html_act_table = $data['act']['html_act_table']) {
                $file = \ActSummaryTable::getByID($html_act_table);
                try {
                    $stream = $file->getStream();
//                    $stream = $fsFileUpload->readStream($file->filename);
                    $ext = pathinfo($file->caption, PATHINFO_EXTENSION);
                    $caption = 'Сводная таблица html' . '.' . $ext;
                    $fs_dist->writeStream($act_id . DIRECTORY_SEPARATOR . 'act' . DIRECTORY_SEPARATOR . $caption, $stream, $config);
                } catch (\Exception $ex) {
                    if ($ex->getMessage() == 'not_found') {
//                        $notFoundFiles['html_act_table_' . $act_id] = $file->getLocation();
                        $addNotFound($act_id, 'act', 'html_act_table', $file->getLocation());
                    }
                }
            }


//            continue;

            $pasports = array_column($data['people'], 'passport_file');
            $pasports = array_filter($pasports);
//            if(empty($pasports)) die(var_dump($data));
//            continue;
            $files = \Files::getByList($pasports);

            foreach ($files as $file) {
                /**  @var \File $file */


                foreach ($data['people'] as $man_id => $man) {
                    if ($man['passport_file'] != $file->id) continue;
                    try {
                        $stream = $file->getStream();
//                    $stream = $fsFileUpload->readStream($file->filename);
                        $ext = pathinfo($file->caption, PATHINFO_EXTENSION);
                        $caption = $man_id . '_' . $man['caption'] . '.' . $ext;
                        $fs_dist->writeStream($act_id . DIRECTORY_SEPARATOR . 'people' . DIRECTORY_SEPARATOR . $caption, $stream, $config);
                    } catch (\Exception $ex) {
                        if ($ex->getMessage() == 'not_found') {
//                            $notFoundFiles['man_passport_' . $man_id] = $file->getLocation();
                            $addNotFound($act_id, 'people', $man_id . ' ' . $man['caption'], $file->getLocation());
                        }
                    }
                }
            }


        }

        return [
            'not_found' => $notFoundFiles,
        ];
    }

    /**
     * @param $folder
     * @param \Generator $people
     * @param \Generator $dubl
     * @return array|FileCollection[]
     */
    public function writeExcel($folder, \Generator $people, \Generator $dubl)
    {
        $c = function ($text) {
            return $this->cp2Utf($text);
        };

        $d = function ($date) {
            if ($date == '0000-00-00' || !$date) return '';
            return date('d.m.Y', strtotime($date));
        };

        /*        $filesystemAdapter = new Local(REPORT_DIR . '/cache');
                $filesystem = new Filesystem($filesystemAdapter);

                $pool = new FilesystemCachePool($filesystem);
                $simpleCache = new \Cache\Bridge\SimpleCache\SimpleCacheBridge($pool);

                \PhpOffice\PhpSpreadsheet\Settings::setCache($simpleCache);*/

        $spreadsheet = new Spreadsheet();

        try {
            $sheet = $spreadsheet->getSheet(0);

            $sheet->setTitle($c("Протестированные в ЛЦ"));
            $origFiles = $this->writeOriginals($people, $sheet, $c, $d);

            $sheet = $spreadsheet->createSheet(1);
            $sheet->setTitle($c("Дубликаты в ЛЦ"));
            $dublfiles = $this->writeOriginals($dubl, $sheet, $c, $d);

            $writer = new Xlsx($spreadsheet);
            $writer->save($folder . '\report.xlsx');
//            var_dump(memory_get_peak_usage(true), memory_get_usage(true));


        } catch (Exception $e) {
            var_dump($e);
        }
        return [$origFiles, $dublfiles];
    }

    private function createFolder()
    {
        $folder = rtrim(REPORT_DIR, '\\/') . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . date('d.m.Y_H.i.s');
//        var_dump($folder);
        mkdir($folder, 0777, true);

        return $folder;
    }

    /**
     * @param \Generator $people
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param \Closure $c
     * @param \Closure $d
     * @return FileCollection
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function writeOriginals(\Generator $people, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, \Closure $c, \Closure $d)
    {
        $docTypes=[
            'certificate'=>"Сертификат",
            'note'=>"Справка",
        ];
        $sheet->freezePane('A2');
//        $sheet->setCellValue('A1', 'Hello World !');
        $row = 1;
        $files = new FileCollection();
        $col = 1;
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('id тестируемого'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Фамилия РУС'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Имя РУС'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Фамилия ЛАТ'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Имя ЛАТ'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Гражданство'));

        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Дата рождения'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Место рождения'));


        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Наименование документа удостоверяющего личность'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Серия документа удостоверяющего личность'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Номер документа удостоверяющего личность'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Дата выдачи документа удостоверяющего личность'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Орган выдвший документ удостоверяющий личность'));

        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Серия миграционной карты'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Номер миграционной карты'));


        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Тип выданого документа'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Уровень тестирования'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Дата тестирования'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Регистрационный номер'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Номер бланка'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Дата выдачи бланка'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Срок действия'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Дубликаты'));

        $sheet->setCellValueByColumnAndRow($col++, $row, $c('id акта'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Номер тестовой сессии'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Тестор 1'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Тестор 2'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Ответственный за проведение тестрования'));
        $sheet->setCellValueByColumnAndRow($col++, $row, $c('Должностное лицо, утверждающее акт'));
        $rows = [];
        foreach ($people as $man) {
            $row++;

            $files->addMan($man);
//            continue;
            $col = 1;

            $rows[] = [
                $c($man['man_id']),
                $c($man['surname_rus']),
                $c($man['name_rus']),
                $c($man['surname_lat']),
                $c($man['name_lat']),
                $c($man['country_name']),
////var_dump($man['birth_date'],$d($man['birth_date']), die;
                $c($d($man['birth_date'])),
                $c($man['birth_place']),
//
                $c($man['passport_name']),
                $c($man['passport_series']),
                $c($man['passport']),
                $c($d($man['passport_date'])),
                $c($man['passport_department']),
//
                $c($man['migration_card_number']),
                $c($man['migration_card_series']),
//
//
                $c($docTypes[$man['cert_type']]),
                $c($man['test_level_caption']),
                $c($d($man['testing_date'])),
                $c($man['document_nomer']),
                $c($man['blank_number']),
                $c($d($man['blank_date'])),
                $c($d($man['valid_till'])),
                $c($man['duplicate']),
//
                $c($man['act_id']),
                $c($man['number']),
                $c($man['tester1']),
                $c($man['tester2']),
                $c($man['responsible']),
                $c($man['official']),
            ];
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['man_id']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['surname_rus']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['name_rus']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['surname_lat']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['name_lat']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['country_name']));
////var_dump($man['birth_date'],$d($man['birth_date'])); die;
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($d($man['birth_date'])));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['birth_place']));
//
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['passport_name']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['passport_series']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['passport']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($d($man['passport_date'])));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['passport_department']));
//
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['migration_card_number']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['migration_card_series']));
//
//
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['cert_type']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['test_level_caption']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($d($man['testing_date'])));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['document_nomer']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['blank_number']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($d($man['blank_date'])));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($d($man['valid_till'])));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['duplicate']));
//
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['act_id']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['number']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['tester1']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['tester2']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['responsible']));
//            $sheet->setCellValueByColumnAndRow($col++, $row, $c($man['official']));
            if ($row % 100 == 0) gc_collect_cycles();

        }
        $sheet->fromArray($rows, null, 'A2');
        return $files;
    }

    /**
     * @param $from
     * @param $to
     * @param $head_org
     * @param $lc
     * @param \Connection $connection
     * @return string
     */
    private function getCertSql($from, $to, $head_org, $lc, $test_type)
    {
        $connection = \Connection::getInstance();
        $statest = array();
        foreach (\Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);


        $cert_sql = "SELECT

       sap.id as man_id,
  sap.surname_rus ,
  sap.surname_lat,
  sap.name_rus ,
  sap.name_lat,
       
        sap.passport_name,
  sap.passport_series,
  sap.passport ,
  sap.passport_date ,
  sap.passport_department ,
  sap.passport_file ,
 
  sap.birth_date ,
  sap.birth_place ,
  sap.migration_card_number,
  sap.migration_card_series,

          sap.document as cert_type,
   sap.document_nomer AS document_nomer,
  sap.blank_date,
  sap.blank_number,
  sap.valid_till,
       sap.testing_date,
           
      
       sa.id as act_id,
       sa.number,
       sa.file_act_id,
       sa.file_act_tabl_id,
       sa.tester1,
       sa.tester2,
       sa.responsible,
       sa.official,
       sa.summary_table_id,
        haf.file_act_id as html_act,
        haf.file_act_tabl_id as html_act_table,
       
       c.name as country_name,
       
       
  stl.caption AS test_level_caption,
  stl.type_id AS test_level_type,
 
/*
  IF(ss.id IS NOT NULL, ss.`position`, (SELECT
      `position`
    FROM sdt_signing
    WHERE certificate = 1
    AND deleted = 0
    AND head_id = shc.id LIMIT 1)) AS cert_signer_position,
  IF(ss.id IS NOT NULL, ss.caption, (SELECT
      caption
    FROM sdt_signing
    WHERE certificate = 1
    AND deleted = 0
    AND head_id = shc.id LIMIT 1)) AS cert_signer_caption
 */
  GROUP_CONCAT(DISTINCT cd.certificate_number SEPARATOR \", \") AS duplicate

FROM sdt_act_people sap
    left join country c on c.id=sap.country_id
    
  LEFT JOIN sdt_act_test sat
    ON sat.id = sap.test_id
  LEFT JOIN sdt_test_levels stl
    ON stl.id = sat.level_id
  LEFT JOIN sdt_act sa
    ON sa.id = sap.act_id
  LEFT JOIN sdt_university su
    ON su.id = sa.university_id
  LEFT JOIN sdt_head_center shc
    ON shc.id = su.head_id
  
  LEFT JOIN sdt_signing ss
    ON ss.id = sap.cert_signer

    left join html_act_files haf on haf.act_id = sa.id
    LEFT JOIN certificate_duplicate cd ON cd.user_id = sap.id AND cd.deleted = 0
    
    /*
    
left join act_summary_table html_act  on html_act.id = haf.file_act_id
left join act_summary_table html_act_tabl  on html_act_tabl.id = haf.file_act_tabl_id
*/


WHERE sa.created BETWEEN '" . $connection->format_date($from) . " 0:0:0' AND '" . $connection->format_date($to) . " 23:59:59'
AND sa.test_level_type_id = ".intval($test_type)."
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (" . $statest . ")

AND sap.blank_number <> ''

AND shc.horg_id = " . intval($head_org) . "
AND su.id = " . intval($lc) . "

 GROUP BY sap.id

ORDER BY sa.created ASC, sap.id ASC, cd.id ASC
 /* limit 100 */
";
//        die($cert_sql);
        return $cert_sql;
    }

    /**
     * @param $from
     * @param $to
     * @param $head_org
     * @param $lc
     * @param \Connection $connection
     * @return string
     */
    private function getDublSql($from, $to, $head_org, $lc,$test_type)
    {
        $connection = \Connection::getInstance();
        $statest = array();
        foreach (\Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);
        $whereHeadOrg = '';
        if ($head_org) {
            $whereHeadOrg = ' and shc.horg_id = ' . intval($head_org);
        }


        $cert_sql = "SELECT

       sap.id as man_id,
  sap.surname_rus ,
  sap.surname_lat,
  sap.name_rus ,
  sap.name_lat,
       
        sap.passport_name,
  sap.passport_series,
  sap.passport ,
  sap.passport_date ,
  sap.passport_department ,
  sap.passport_file ,
 
  sap.birth_date ,
  sap.birth_place ,
  sap.migration_card_number,
  sap.migration_card_series,

          sap.document as cert_type,
   sap.document_nomer AS document_nomer,
  sap.blank_date,
  sap.blank_number,
  sap.valid_till,
       sap.testing_date,
           
      
       sa.id as act_id,
       sa.number,
       sa.file_act_id,
       sa.file_act_tabl_id,
       sa.tester1,
       sa.tester2,
       sa.responsible,
       sa.official,
       sa.summary_table_id,
        haf.file_act_id as html_act,
        haf.file_act_tabl_id as html_act_table,
       
       c.name as country_name,
       
       
  stl.caption AS test_level_caption,
  stl.type_id AS test_level_type,
 

  GROUP_CONCAT(DISTINCT cd.certificate_number SEPARATOR \", \") AS duplicate

FROM sdt_act_people sap
    left join country c on c.id=sap.country_id
    
  LEFT JOIN sdt_act_test sat
    ON sat.id = sap.test_id
  LEFT JOIN sdt_test_levels stl
    ON stl.id = sat.level_id
  LEFT JOIN sdt_act sa
    ON sa.id = sap.act_id
  LEFT JOIN sdt_university su
    ON su.id = sa.university_id
  LEFT JOIN sdt_head_center shc
    ON shc.id = su.head_id
  
  LEFT JOIN sdt_signing ss
    ON ss.id = sap.cert_signer

    left join html_act_files haf on haf.act_id = sa.id
    LEFT JOIN certificate_duplicate cd ON cd.user_id = sap.id AND cd.deleted = 0
    
    /*
    
left join act_summary_table html_act  on html_act.id = haf.file_act_id
left join act_summary_table html_act_tabl  on html_act_tabl.id = haf.file_act_tabl_id
*/


WHERE
 sa.test_level_type_id = ".intval($test_type)."
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (" . $statest . ")

AND sap.blank_number <> ''
AND su.id <> " . intval($lc) . "
AND sap.id IN (
SELECT dam.old_man_id FROM dubl_act_man dam  
LEFT JOIN dubl_act da ON da.id = dam.act_id WHERE da.center_id = " . intval($lc) . "
and da.state='processed'
 and da.created BETWEEN '" . $connection->format_date($from) . " 0:0:0' AND '" . $connection->format_date($to) . " 23:59:59'
)


 GROUP BY sap.id

ORDER BY sa.created ASC, sap.id ASC, cd.id ASC
";

        return $cert_sql;
    }

    private function writeLog($message)
    {
        vprintf('%s: %s<br>', [
            date('d.m.Y H:i:s'),
            $message
        ]);
        flush();
    }
}

class FileCollection
{
    private $items = [];

    public function __construct()
    {

    }

    public function addMan(array $row)
    {
        $items = &$this->items;
        if (!array_key_exists($row['act_id'], $items)) {
            $items[$row['act_id']] = [
                'act' => [],
                'people' => [],
            ];
            $items[$row['act_id']]['act'] = [
                'file_act_id' => $row['file_act_id'],
                'file_act_tabl_id' => $row['file_act_tabl_id'],
                'summary_table_id' => $row['summary_table_id'],
                'html_act' => $row['html_act'],
                'html_act_table' => $row['html_act_table'],
            ];
        }
        $items[$row['act_id']]['people'][$row['man_id']] = [
            'passport_file' => $row['passport_file'],
            'caption' => trim($row['surname_rus'] . ' ' . $row['name_rus']),

        ];

    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    public function countAct()
    {
        return count($this->items);
    }

    public function countPeople()
    {
        $c = array_map(function ($data) {
            return count($data['people']);
        }, $this->items);
        return array_sum($c);
    }
}