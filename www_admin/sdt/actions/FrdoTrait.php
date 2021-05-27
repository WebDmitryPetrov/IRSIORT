<?php

/**
 * Trait ReportsTrait
 * @property Render render
 */
trait FrdoTrait
{
    /**
     *    ФРДО дубликаты выданные в отличном центре от центра сдачи
     * @return false|string
     */
    public function excel_fis_frdo_dubl_not_equal_report_action()
    {
        set_time_limit(3000);


        $search = false;
        $result = array();
        $from = '1.01.' . date('Y');
        $to = date('d.m.Y');
        $limiter = 3000;
        $year = date('Y');
        $month = date('m');

        $dir = FRDO_EXCEL_UPLOAD_DIR;

        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $start = microtime(1);
            set_time_limit(0);
            $search = true;
            $connection = Connection::getInstance();


            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $from = $this->mysql_date($from) . ' 0:0:0';
            $to = $this->mysql_date($to) . ' 23:59:59';

            $limit = filter_input(INPUT_POST, 'limiter', FILTER_VALIDATE_INT);
            if (!empty($limit)) {
                $limiter = $limit;
            }


            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'" . $st . "'";
            }
            $statest = implode(', ', $statest);


            $hc_id = filter_input(INPUT_POST, 'hc');


            $sql = "SELECT
 
  IF(cd.id IS NOT NULL, (SELECT
      cds.surname_rus
    FROM certificate_duplicate cds
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1), sap.surname_rus) AS A,
  IF(cd.id IS NOT NULL, (SELECT
      cds.surname_lat
    FROM certificate_duplicate cds
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1), sap.surname_lat) AS B,
  IF(cd.id IS NOT NULL, (SELECT
      cds.name_rus
    FROM certificate_duplicate cds
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1), sap.name_rus) AS C,
  IF(cd.id IS NOT NULL, (SELECT
      cds.name_lat
    FROM certificate_duplicate cds
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1), sap.name_lat) AS D,
 
  (SELECT
      hct.long_ip
    FROM certificate_duplicate cds
      LEFT JOIN certificate_reserved cr
        ON cr.id = cds.certificate_id
      LEFT JOIN sdt_head_center shc
        ON shc.id = cr.head_center_id
      LEFT JOIN head_center_text hct
        ON hct.head_id = shc.id
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1) AS E,
  stl.caption AS F,

  (SELECT
      hct.certificate_city
    FROM certificate_duplicate cds
      LEFT JOIN certificate_reserved cr
        ON cr.id = cds.certificate_id
      LEFT JOIN sdt_head_center shc
        ON shc.id = cr.head_center_id
      LEFT JOIN head_center_text hct
        ON hct.head_id = shc.id
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1) AS G,
  sap.document_nomer AS H,
  sap.blank_date I,
  sap.valid_till AS J,


    (SELECT
     IF(ss.id IS NOT NULL, ss.`position`,(SELECT
      `position`
    FROM sdt_signing
    WHERE certificate = 1
    AND deleted = 0
    AND head_id = cr1.head_center_id LIMIT 1))
    FROM certificate_duplicate cds
    LEFT JOIN sdt_signing ss ON ss.id = cds.cert_signer
  LEFT JOIN certificate_reserved cr1 ON cr1.id = cds.certificate_id
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1) AS K,
 
  (SELECT
      hct.short_ip
    FROM certificate_duplicate cds
      LEFT JOIN certificate_reserved cr
        ON cr.id = cds.certificate_id
      LEFT JOIN sdt_head_center shc
        ON shc.id = cr.head_center_id
      LEFT JOIN head_center_text hct
        ON hct.head_id = shc.id
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1) AS L,

(SELECT
     IF(ss.id IS NOT NULL, ss.`caption`,(SELECT
      `caption`
    FROM sdt_signing
    WHERE certificate = 1
    AND deleted = 0
    AND head_id = cr1.head_center_id LIMIT 1))
    FROM certificate_duplicate cds
    LEFT JOIN sdt_signing ss ON ss.id = cds.cert_signer
  LEFT JOIN certificate_reserved cr1 ON cr1.id = cds.certificate_id
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1) AS M,

  IF(cd.id IS NOT NULL, (SELECT
      cds.certificate_number
    FROM certificate_duplicate cds
    WHERE cds.user_id = sap.id
    AND cds.deleted = 0
    ORDER BY cds.id DESC LIMIT 1), sap.blank_number) AS N,
  IF(cd.id IS NOT NULL, 1, 0) AS O
FROM sdt_act_people sap
  LEFT JOIN certificate_duplicate cd
    ON cd.user_id = sap.id
    AND cd.deleted = 0
  LEFT JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa
    ON sa.id = sat.act_id
  LEFT JOIN sdt_university su
    ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc
    ON su.head_id = shc.id
  LEFT JOIN head_center_text hct
    ON hct.head_id = shc.id
  LEFT JOIN sdt_test_levels stl
    ON stl.id = sat.level_id
WHERE sa.deleted = 0
AND sap.deleted = 0
AND shc.deleted = 0
AND sat.deleted = 0
AND sap.id IN (SELECT
    cd.user_id
  FROM certificate_duplicate cd
    LEFT JOIN certificate_reserved cr
      ON cd.certificate_id = cr.id 
    LEFT JOIN sdt_head_center shc_r
      ON shc_r.id = cr.head_center_id
    LEFT JOIN sdt_act_people sap_r
      ON sap_r.id = cd.user_id
    LEFT JOIN sdt_act sa_r
      ON sa_r.id = sap_r.act_id
    LEFT JOIN sdt_university su_r
      ON su_r.id = sa_r.university_id
    LEFT JOIN sdt_head_center shc_sap
      ON shc_sap.id = su_r.head_id
  WHERE shc_r.horg_id = " . $hc_id . "
  AND shc_r.horg_id <> shc_sap.horg_id
  AND cd.certificate_issue_date BETWEEN '" . $from . "' AND '" . $to . "'
  AND cd.deleted = 0)
AND sa.state IN (" . $statest . ")
AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'
AND sa.test_level_type_id = 2
AND sap.blank_number <> ''
AND sap.document_nomer <> ''
GROUP BY sap.id
ORDER BY sap.blank_date ASC,  sap.id DESC";


//            die($sql);


            $array = mysql_query($sql) or die(mysql_error());

            $table_head = array(
                'A' => 'Фамилия русская', //1A
                'B' => 'Фамилия латинская', //2B
                'C' => 'Имя русское', //3C
                'D' => 'Имя латинское', //4D
                'E' => 'Название организации', //5E
                'F' => 'Название уровня тестирования', //6F
                'G' => 'Город', //7G
                'H' => 'Регистрационный номер сертификата', //8H
                'I' => 'Дата выдачи', //9I
                'J' => 'Срок действия', //10J
                'K' => 'Должность подписывающего', //11K
                'L' => 'Сокращенное название организации подписывающего', //12L
                'M' => 'ФИО подписывающего', //13M
                'N' => 'Номер бланка', //14N
                'O' => 'Статус документа', //14N
            );


            $caption = "Отчет";
            $org_name = (HeadCenter::getOrgName($hc_id));
//            die($org_name);
            $report_name = 'ФИС_ФРДО';


            if (mysql_num_rows($array) > 0) {


                $frdoGenerator = function () use ($array) {
                    while ($row = mysql_fetch_assoc($array))
                        yield $row;

                };
                if (!@opendir($dir . '\\' . $hc_id)) {
                    mkdir($dir . '\\' . $hc_id);
                }


                if (!@opendir($dir . '\\' . $hc_id . '\\dubl_not_equal')) {
                    mkdir($dir . '\\' . $hc_id . '\\dubl_not_equal');
                }


                $zip_dir = $dir . '\\' . $hc_id . '\\dubl_not_equal';
                /* if (!@opendir($zip_dir))
                 {
                     mkdir($zip_dir);
                 }*/


                $temp_dir = $dir . '\temp';
                if (!@opendir($temp_dir)) {
                    mkdir($temp_dir);
                }

                $temp_dir = $dir . '\\temp\\' . uniqid($hc_id, true);
                if (!@opendir($temp_dir)) {
                    mkdir($temp_dir);
                }


                require_once(dirname(__FILE__) . '/../template/view/root/statist/excel_fis_frdo.php');


                $filelist = array();
                if ($handle = opendir($temp_dir)) {
                    while ($entry = readdir($handle)) {
                        if (!is_dir($entry)) {
                            $filelist[] = $entry;
//                        echo $entry;
                        }
                    }
                    closedir($handle);
                }

                $zip = new ZipArchive(); // подгружаем библиотеку zip
//            $zip_name = $zip_dir.'\\'.time().".zip"; // имя файла
                $zip_name = $zip_dir . '\\' . $org_name . "_" . date('Y-m-d-H-i') . "_(" . date('Y-m-d', strtotime($from)) . "-" . date('Y-m-d', strtotime($to)) . ").zip"; // имя файла
//                $zip_name = $zip_dir . '\\' . $org_name . "_" . $year . "_" . date('Y-m-d-H-i', mktime(0, 0, 0, $month, 1)) . ".zip"; // имя файла
                if (file_exists($zip_name)) unlink($zip_name);
                if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {

                    die("* Sorry ZIP creation failed at this time");
                }
                foreach ($filelist as $file) {
                    $zip->addFile($temp_dir . '\\' . $file, iconv('cp1251', 'cp866', $file)); // добавляем файлы в zip архив
                }
                $zip->close();

                foreach ($filelist as $file) {
                    unlink($temp_dir . '\\' . $file);
                }
                @rmdir($temp_dir);


                $_SESSION['flash'] = 'Отчеты созданы ' . date('r') . ' (' . sprintf("%0.2f", (microtime(1) - $start)) . 'сек, ' . (memory_get_usage(1) / 1024 / 1024) . 'мб, ' . mysql_num_rows($array) . 'записей)';
            } else $_SESSION['flash'] = 'За выбранный период данных нет';
//
//            $this->redirectByAction('excel_fis_frdo_dubl_report');
//            return;

        }

        $hcs = HeadCenters::getHeadOrgs();
//            $hcs = array_filter($hcs, function ($item) {
//                return $item['id'] == 1;
//            });
//            die(var_dump($hcs));


        $files = [];
        foreach (new DirectoryIterator($dir) as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isFile()) continue;
            $name = intval($fileInfo->getFilename());
            if ($name < 1 || $name > 100) continue;
            $horg = HeadOrg::getByID($name);
            if (!$horg) continue;
//            var_dump($fileInfo,$horg);
//            var_dump($fileInfo->getRealPath(),$fileInfo->getPathname(),$fileInfo);
            $dublPath = $fileInfo->getPathname() . DIRECTORY_SEPARATOR . 'dubl_not_equal';
            if (!file_exists($dublPath)) continue;
            $files[$name] = [
                'horg' => $horg,
                'files' => [],
            ];
            foreach (new DirectoryIterator($dublPath) as $file) {
                if ($file->isDot() || $file->isDir()) continue;
//                $data = sscanf($file->getBasename(),'%s_%d-%d-%d-%d-%d_(%d-%d-%d-%d-%d-%d).zip');
                preg_match('|^\S+_(?P<create>\d+-\d+-\d+-\d+-\d+)_\((?P<from>\d+-\d+-\d+)-(?P<to>\d+-\d+-\d+)\)|', $file->getBasename(), $data);
//                    var_dump($data);
                $data['create'] = date_create_from_format('Y-m-d-H-i', $data['create'])->format('d.m.Y H:i');
                $data['from'] = date_create_from_format('Y-m-d', $data['from'])->format('d.m.Y');
                $data['to'] = date_create_from_format('Y-m-d', $data['to'])->format('d.m.Y');
//                var_dump($data['create']);die();
                $files[$name]['files'][] = [
                    'base' => $file->getBasename(),
                    'path' => substr($file->getPathname(), strlen($dir) + 1),
                    'created' => $data['create'],
                    'from' => $data['from'],
                    'to' => $data['to'],
                ];
            }

        }

        return $this->render->view(
            'root/statist/excel_frdo_dubl',
            array(
                'hcs' => $hcs,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'files' => $files,
                'limiter' => $limiter,
                'caption' => 'ФРДО дубликаты выданные в отличном центре от центра сдачи',
            )
        );
    }


    public function excel_fis_frdo_dubl_report_action()
    {
        set_time_limit(3000);


        $search = false;
        $result = array();
        $from = '1.01.' . date('Y');
        $to = date('d.m.Y');
        $limiter = 3000;
        $year = date('Y');
        $month = date('m');

        $dir = FRDO_EXCEL_UPLOAD_DIR;

        if (filter_input(INPUT_POST, 'from') && filter_input(INPUT_POST, 'to')) {
            $start = microtime(1);
            set_time_limit(0);
            $search = true;
            $connection = Connection::getInstance();


            $from = filter_input(INPUT_POST, 'from');
            $to = filter_input(INPUT_POST, 'to');
            $from = $this->mysql_date($from) . ' 0:0:0';
            $to = $this->mysql_date($to) . ' 23:59:59';

            $limit = filter_input(INPUT_POST, 'limiter', FILTER_VALIDATE_INT);
            if (!empty($limit)) {
                $limiter = $limit;
            }


            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'" . $st . "'";
            }
            $statest = implode(', ', $statest);


            $hc_id = filter_input(INPUT_POST, 'hc');


            $sql = "SELECT

if(cd.id IS NOT NULL,
	(SELECT cds.surname_rus FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),sap.surname_rus)
	AS A,

if(cd.id IS NOT NULL,
	(SELECT cds.surname_lat FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),sap.surname_lat)
	AS B,

if(cd.id IS NOT NULL,
	(SELECT cds.name_rus FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),sap.name_rus)
	AS C,

if(cd.id IS NOT NULL,
	(SELECT cds.name_lat FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),sap.name_lat)
	AS D,



	hct.long_ip AS E,
	stl.caption AS F,
	hct.certificate_city AS G,
	sap.document_nomer AS H,
	sap.blank_date I,
	sap.valid_till AS J,

	if (cd.id IS NULL,
		 (if (sap.cert_signer IS NOT NULL AND sap.cert_signer > 0,
		 (SELECT `position` FROM sdt_signing WHERE id=sap.cert_signer LIMIT 1),
		 (SELECT `position` FROM sdt_signing WHERE certificate=1 AND deleted=0 AND head_id=shc.id LIMIT 1))),

		 (if (cd.cert_signer IS NOT NULL AND cd.cert_signer > 0,
		 (SELECT `position` FROM sdt_signing WHERE id=cd.cert_signer LIMIT 1),
		 (SELECT `position` FROM sdt_signing WHERE certificate=1 AND deleted=0 AND head_id=shc.id LIMIT 1))))
	AS K,

	hct.short_ip AS L,

	if (cd.id IS NULL,
		 (if (sap.cert_signer IS NOT NULL AND sap.cert_signer > 0,
		 (SELECT `caption` FROM sdt_signing WHERE id=sap.cert_signer LIMIT 1),
		 (SELECT `caption` FROM sdt_signing WHERE certificate=1 AND deleted=0 AND head_id=shc.id LIMIT 1))),

		 (if (cd.cert_signer IS NOT NULL AND cd.cert_signer > 0,
		 (SELECT `caption` FROM sdt_signing WHERE id=cd.cert_signer LIMIT 1),
		 (SELECT `caption` FROM sdt_signing WHERE certificate=1 AND deleted=0 AND head_id=shc.id LIMIT 1))))
	AS M,

if(cd.id IS NOT NULL,
	(SELECT cds.certificate_number FROM certificate_duplicate cds
	WHERE cds.user_id=sap.id AND cds.deleted = 0
	ORDER BY cds.id DESC LIMIT 1),sap.blank_number)
	AS N,

if(cd.id IS NOT NULL,
	1,0)
	AS O






FROM sdt_act_people sap
LEFT JOIN certificate_duplicate cd ON cd.user_id=sap.id AND cd.deleted = 0
LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
LEFT JOIN sdt_act sa ON sa.id = sat.act_id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN head_center_text hct ON hct.head_id=shc.id
LEFT JOIN sdt_test_levels stl ON stl.id=sat.level_id
    LEFT JOIN certificate_reserved cr
      ON cd.certificate_id = cr.id 
    LEFT JOIN sdt_head_center shc2 ON cr.head_center_id = shc2.id

WHERE  sa.deleted=0
AND sap.deleted=0 AND shc.deleted=0 AND sat.deleted=0

AND shc.horg_id  = " . $hc_id . "
AND shc2.horg_id  = " . $hc_id . "
and sap.id in (select cd.user_id from certificate_duplicate cd 
where 
(YEAR(cd.certificate_issue_date) <> YEAR(cd.certificate_print_date) or MONTH(cd.certificate_issue_date) <>  MONTH(cd.certificate_print_date))
and cd.certificate_issue_date  BETWEEN '" . $from . "' AND '" . $to . "'
and cd.deleted = 0)

AND sa.state IN (" . $statest . ")
AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'



AND sa.test_level_type_id=2 AND sap.blank_number <> ''
AND sap.document_nomer <> ''

GROUP BY sap.id

ORDER BY sap.blank_date ASC, sa.id ASC , sap.id DESC

";


//            die($sql);


            $array = mysql_query($sql) or die(mysql_error());

            $table_head = array(
                'A' => 'Фамилия русская', //1A
                'B' => 'Фамилия латинская', //2B
                'C' => 'Имя русское', //3C
                'D' => 'Имя латинское', //4D
                'E' => 'Название организации', //5E
                'F' => 'Название уровня тестирования', //6F
                'G' => 'Город', //7G
                'H' => 'Регистрационный номер сертификата', //8H
                'I' => 'Дата выдачи', //9I
                'J' => 'Срок действия', //10J
                'K' => 'Должность подписывающего', //11K
                'L' => 'Сокращенное название организации подписывающего', //12L
                'M' => 'ФИО подписывающего', //13M
                'N' => 'Номер бланка', //14N
                'O' => 'Статус документа', //14N
            );


            $caption = "Отчет";
            $org_name = (HeadCenter::getOrgName($hc_id));
//            die($org_name);
            $report_name = 'ФИС_ФРДО';


            if (mysql_num_rows($array) > 0) {
                $frdoGenerator = function () use ($array) {
                    while ($row = mysql_fetch_assoc($array))
                        yield $row;

                };

                if (!@opendir($dir . '\\' . $hc_id)) {
                    mkdir($dir . '\\' . $hc_id);
                }


                if (!@opendir($dir . '\\' . $hc_id . '\\dubl')) {
                    mkdir($dir . '\\' . $hc_id . '\\dubl');
                }


                $zip_dir = $dir . '\\' . $hc_id . '\\dubl';
                /* if (!@opendir($zip_dir))
                 {
                     mkdir($zip_dir);
                 }*/


                $temp_dir = $dir . '\temp';
                if (!@opendir($temp_dir)) {
                    mkdir($temp_dir);
                }

                $temp_dir = $dir . '\\temp\\' . uniqid($hc_id, true);
                if (!@opendir($temp_dir)) {
                    mkdir($temp_dir);
                }


                require_once(dirname(__FILE__) . '/../template/view/root/statist/excel_fis_frdo.php');


                $filelist = array();
                if ($handle = opendir($temp_dir)) {
                    while ($entry = readdir($handle)) {
                        if (!is_dir($entry)) {
                            $filelist[] = $entry;
//                        echo $entry;
                        }
                    }
                    closedir($handle);
                }

                $zip = new ZipArchive(); // подгружаем библиотеку zip
//            $zip_name = $zip_dir.'\\'.time().".zip"; // имя файла
                $zip_name = $zip_dir . '\\' . $org_name . "_" . date('Y-m-d-H-i') . "_(" . date('Y-m-d', strtotime($from)) . "-" . date('Y-m-d', strtotime($to)) . ").zip"; // имя файла
//                $zip_name = $zip_dir . '\\' . $org_name . "_" . $year . "_" . date('Y-m-d-H-i', mktime(0, 0, 0, $month, 1)) . ".zip"; // имя файла
                if (file_exists($zip_name)) unlink($zip_name);
                if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {

                    die("* Sorry ZIP creation failed at this time");
                }
                foreach ($filelist as $file) {
                    $zip->addFile($temp_dir . '\\' . $file, iconv('cp1251', 'cp866', $file)); // добавляем файлы в zip архив
                }
                $zip->close();

                foreach ($filelist as $file) {
                    unlink($temp_dir . '\\' . $file);
                }
                @rmdir($temp_dir);


                $_SESSION['flash'] = 'Отчеты созданы ' . date('r') . ' (' . sprintf("%0.2f", (microtime(1) - $start)) . 'сек, ' . (memory_get_usage(1) / 1024 / 1024) . 'мб, ' . mysql_num_rows($array) . 'записей)';
            } else $_SESSION['flash'] = 'За выбранный период данных нет';
//
//            $this->redirectByAction('excel_fis_frdo_dubl_report');
//            return;

        }

        $hcs = HeadCenters::getHeadOrgs();
//            $hcs = array_filter($hcs, function ($item) {
//                return $item['id'] == 1;
//            });
//            die(var_dump($hcs));


        $files = [];
        foreach (new DirectoryIterator($dir) as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isFile()) continue;
            $name = intval($fileInfo->getFilename());
            if ($name < 1 || $name > 100) continue;
            $horg = HeadOrg::getByID($name);
            if (!$horg) continue;
//            var_dump($fileInfo,$horg);
//            var_dump($fileInfo->getRealPath(),$fileInfo->getPathname(),$fileInfo);
            $dublPath = $fileInfo->getPathname() . DIRECTORY_SEPARATOR . 'dubl';
            if (!file_exists($dublPath)) continue;
            $files[$name] = [
                'horg' => $horg,
                'files' => [],
            ];
            foreach (new DirectoryIterator($dublPath) as $file) {
                if ($file->isDot() || $file->isDir()) continue;
//                $data = sscanf($file->getBasename(),'%s_%d-%d-%d-%d-%d_(%d-%d-%d-%d-%d-%d).zip');
                preg_match('|^[\S\s]+_(?P<create>\d+-\d+-\d+-\d+-\d+)_\((?P<from>\d+-\d+-\d+)-(?P<to>\d+-\d+-\d+)\)|', $file->getBasename(), $data);
//                    var_dump($data);
                $data['create'] = date_create_from_format('Y-m-d-H-i', $data['create'])->format('d.m.Y H:i');
                $data['from'] = date_create_from_format('Y-m-d', $data['from'])->format('d.m.Y');
                $data['to'] = date_create_from_format('Y-m-d', $data['to'])->format('d.m.Y');
//                var_dump($data['create']);die();
                $files[$name]['files'][] = [
                    'base' => $file->getBasename(),
                    'path' => substr($file->getPathname(), strlen($dir) + 1),
                    'created' => $data['create'],
                    'from' => $data['from'],
                    'to' => $data['to'],
                ];
            }

        }

        return $this->render->view(
            'root/statist/excel_frdo_dubl',
            array(
                'hcs' => $hcs,
                'search' => $search,
                'from' => $from,
                'to' => $to,
                'files' => $files,
                'limiter' => $limiter,
                'caption' => 'ФРДО дубликаты, выданные в месяц отличный от основного сертификата',
            )
        );
    }

    public function excel_fis_frdo_report_action()
    {
//die('sdf');
        ini_set('memory_limit', '2G');
        set_time_limit(3000);
        $search = false;
        $result = array();
        $from = '1.01.2015';
        $to = date('d.m.Y');


//        $month=date("n");
        $month = date("n", mktime(0, 0, 0, date("n"), 0));
//        $year=date('Y');
        $year = date('Y', mktime(0, 0, 0, date("n"), 0, date('Y')));

        $limiter = 3000;

        if (filter_input(INPUT_POST, 'month') && filter_input(INPUT_POST, 'year')) {
            $start = microtime(1);
            set_time_limit(0);
            $search = true;
            $connection = Connection::getInstance();


            $month = filter_input(INPUT_POST, 'month');
            $year = filter_input(INPUT_POST, 'year');

//            $from = date("Y-m-d H:i:s", mktime(0, 0, 0, $month, 1, $year)); //ч м с мес день год
            $from = date("Y-m-d 0:0:0", mktime(0, 0, 0, $month, 1, $year)); //ч м с мес день год
//            $to = date("Y-m-d H:i:s", mktime(23, 59, 59, $month+1, 0, $year)); //ч м с мес день год
            $to = date("Y-m-d 23:59:59", mktime(23, 59, 59, $month + 1, 0, $year)); //ч м с мес день год
//            $to = date("Y-m-d", mktime(23, 59, 59, $month, 2, $year)); //ч м с мес день год
//var_dump($from,$to);die;
            $limit = filter_input(INPUT_POST, 'limiter', FILTER_VALIDATE_INT);
            if (!empty($limit)) {
                $limiter = $limit;
            }


            $statest = array();
            foreach (Act::getInnerStates() as $st) {
                $statest[] = "'" . $st . "'";
            }
            $statest = implode(', ', $statest);


            $hc_id = filter_input(INPUT_POST, 'hc');


            /*  $sql = "SELECT

  if(cd.id IS NOT NULL,
      (SELECT cds.surname_rus FROM certificate_duplicate cds
      WHERE cds.user_id=sap.id AND cds.deleted = 0
      ORDER BY cds.id DESC LIMIT 1),sap.surname_rus)
      AS A,

  if(cd.id IS NOT NULL,
      (SELECT cds.surname_lat FROM certificate_duplicate cds
      WHERE cds.user_id=sap.id AND cds.deleted = 0
      ORDER BY cds.id DESC LIMIT 1),sap.surname_lat)
      AS B,

  if(cd.id IS NOT NULL,
      (SELECT cds.name_rus FROM certificate_duplicate cds
      WHERE cds.user_id=sap.id AND cds.deleted = 0
      ORDER BY cds.id DESC LIMIT 1),sap.name_rus)
      AS C,

  if(cd.id IS NOT NULL,
      (SELECT cds.name_lat FROM certificate_duplicate cds
      WHERE cds.user_id=sap.id AND cds.deleted = 0
      ORDER BY cds.id DESC LIMIT 1),sap.name_lat)
      AS D,



      hct.long_ip AS E,
      stl.caption AS F,
      hct.certificate_city AS G,
      sap.document_nomer AS H,
      sap.blank_date I,
      sap.valid_till AS J,

      if (cd.id IS NULL,
           (if (sap.cert_signer IS NOT NULL AND sap.cert_signer > 0,
           (SELECT `position` FROM sdt_signing WHERE id=sap.cert_signer LIMIT 1),
           (SELECT `position` FROM sdt_signing WHERE certificate=1 AND deleted=0 AND head_id=shc.id LIMIT 1))),

           (if (cd.cert_signer IS NOT NULL AND cd.cert_signer > 0,
           (SELECT `position` FROM sdt_signing WHERE id=cd.cert_signer LIMIT 1),
           (SELECT `position` FROM sdt_signing WHERE certificate=1 AND deleted=0 AND head_id=shc.id LIMIT 1))))
      AS K,

      hct.short_ip AS L,

      if (cd.id IS NULL,
           (if (sap.cert_signer IS NOT NULL AND sap.cert_signer > 0,
           (SELECT `caption` FROM sdt_signing WHERE id=sap.cert_signer LIMIT 1),
           (SELECT `caption` FROM sdt_signing WHERE certificate=1 AND deleted=0 AND head_id=shc.id LIMIT 1))),

           (if (cd.cert_signer IS NOT NULL AND cd.cert_signer > 0,
           (SELECT `caption` FROM sdt_signing WHERE id=cd.cert_signer LIMIT 1),
           (SELECT `caption` FROM sdt_signing WHERE certificate=1 AND deleted=0 AND head_id=shc.id LIMIT 1))))
      AS M,

  if(cd.id IS NOT NULL,
      (SELECT cds.certificate_number FROM certificate_duplicate cds
      WHERE cds.user_id=sap.id AND cds.deleted = 0
      ORDER BY cds.id DESC LIMIT 1),sap.blank_number)
      AS N,

  if(cd.id IS NOT NULL,
      1,0)
      AS O






  FROM sdt_act_people sap
  LEFT JOIN certificate_duplicate cd ON cd.user_id=sap.id AND cd.deleted = 0
  LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa ON sa.id = sat.act_id
  LEFT JOIN sdt_university su ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
  LEFT JOIN head_center_text hct ON hct.head_id=shc.id
  LEFT JOIN sdt_test_levels stl ON stl.id=sat.level_id
  WHERE
  sap.id in (select cu.user_id from certificate_used cu where cu.timest  BETWEEN '" . $from . "' AND '" . $to . "'
  union
  select cd.user_id from certificate_duplicate cd where cd.certificate_issue_date BETWEEN '" . $from . "' AND '" . $to . "')

  AND su.deleted=0 AND sa.deleted=0
  AND sap.deleted=0 AND shc.deleted=0 AND sat.deleted=0
  AND shc.horg_id  = " . $hc_id . "

  AND sa.state IN (" . $statest . ")
  AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'



  AND sa.test_level_type_id=2 AND sap.blank_number <> ''
  AND sap.document_nomer <> ''

  GROUP BY sap.id

  ORDER BY sap.blank_date ASC, sa.id ASC , sap.id DESC

  ";*/
            $cert_sql = "SELECT

  sap.surname_rus AS A,
  sap.surname_lat AS B,
  sap.name_rus AS C,
  sap.name_lat AS D,
  hct.long_ip AS E,
  hct.short_ip AS L,
  stl.caption AS F,
  hct.certificate_city AS G,
  sap.document_nomer AS H,
  sap.blank_date AS I,
  sap.blank_number AS N,
  sap.valid_till AS J,
  IF(ss.id IS NOT NULL, ss.`position`, (SELECT
      `position`
    FROM sdt_signing
    WHERE certificate = 1
    AND deleted = 0
    AND head_id = shc.id LIMIT 1)) AS K,
  IF(ss.id IS NOT NULL, ss.caption, (SELECT
      caption
    FROM sdt_signing
    WHERE certificate = 1
    AND deleted = 0
    AND head_id = shc.id LIMIT 1)) AS M,

  0 AS O

FROM sdt_act_people sap
    
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
  LEFT JOIN head_center_text hct
    ON hct.head_id = shc.id
  LEFT JOIN sdt_signing ss
    ON ss.id = sap.cert_signer
WHERE sap.blank_date BETWEEN '" . $from . "' AND '" . $to . "'
AND sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (" . $statest . ")
AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'
AND sa.test_level_type_id = 2
AND sap.blank_number <> ''
AND sap.document_nomer <> ''
AND shc.horg_id = " . intval($hc_id) . "
ORDER BY sap.blank_date ASC, sap.id asc";

//            die($sql);


            $cert_result = mysql_query($cert_sql) or die(mysql_error());

            $sql_dub = "SELECT
  cd.surname_rus AS A,
  cd.surname_lat AS B,
  cd.name_rus AS C,
  cd.name_lat AS D,
  hct.long_ip AS E,
  hct.short_ip AS L,
  stl.caption AS F,
  hct.certificate_city AS G,
  sap.document_nomer AS H,
  sap.blank_date AS I,
  cd.certificate_number AS N,
  cd.certificate_issue_date AS P,
  sap.valid_till AS J,
  IF(ss.id IS NOT NULL, ss.`position`, (SELECT
      `position`
    FROM sdt_signing
    WHERE certificate = 1
    AND deleted = 0
    AND head_id = shc.id LIMIT 1)) AS K,
  IF(ss.id IS NOT NULL, ss.caption, (SELECT
      caption
    FROM sdt_signing
    WHERE certificate = 1
    AND deleted = 0
    AND head_id = shc.id LIMIT 1)) AS M,

  1 AS O

FROM certificate_duplicate cd

  LEFT JOIN sdt_act_people sap
    ON cd.user_id = sap.id
  LEFT JOIN sdt_act_test sat
    ON sat.id = sap.test_id
  LEFT JOIN sdt_test_levels stl
    ON stl.id = sat.level_id
  LEFT JOIN sdt_act sa
    ON sa.id = sap.act_id

 
 left JOIN certificate_reserved cr  ON cr.id = cd.certificate_id   
 
 LEFT JOIN sdt_head_center shc  ON cr.head_center_id  = shc.id
 
 LEFT JOIN head_center_text hct
    ON hct.head_id = shc.id

  LEFT JOIN sdt_signing ss
    ON ss.id = cd.cert_signer
WHERE cd.certificate_issue_date BETWEEN  '" . $from . "' AND '" . $to . "'
  AND cd.deleted = 0
AND sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sa.state IN (" . $statest . ")
AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'
AND sa.test_level_type_id = 2
AND sap.blank_number <> ''
AND sap.document_nomer <> ''
AND shc.horg_id = " . intval($hc_id) . "
ORDER BY cd.certificate_issue_date ASC";

            $dub_result = mysql_query($sql_dub) or die(mysql_error());

            $table_head = array(
                'A' => 'Фамилия русская', //1A
                'B' => 'Фамилия латинская', //2B
                'C' => 'Имя русское', //3C
                'D' => 'Имя латинское', //4D
                'E' => 'Название организации', //5E
                'F' => 'Название уровня тестирования', //6F
                'G' => 'Город', //7G
                'H' => 'Регистрационный номер сертификата', //8H
                'I' => 'Дата выдачи', //9I
                'J' => 'Срок действия', //10J
                'K' => 'Должность подписывающего', //11K
                'L' => 'Сокращенное название организации подписывающего', //12L
                'M' => 'ФИО подписывающего', //13M
                'N' => 'Номер бланка', //14N
                'O' => 'Статус документа', //14N
                'P' => 'Дата выдачи дубликата', //14N
            );


            $caption = "Отчет";
            $org_name = (HeadCenter::getOrgName($hc_id));
//            die($org_name);
            $report_name = 'ФИС_ФРДО';


            if (mysql_num_rows($cert_result) > 0 || mysql_num_rows($dub_result) > 0) {

                $frdoGenerator = function () use ($cert_result, $dub_result) {
                    while ($row = mysql_fetch_assoc($cert_result))
                        yield $row;
                    while ($row = mysql_fetch_assoc($dub_result))
                        yield $row;
                };
                $dir = FRDO_EXCEL_UPLOAD_DIR;

                if (!@opendir($dir . '\\' . $hc_id)) {
                    mkdir($dir . '\\' . $hc_id);
                }


                if (!@opendir($dir . '\\' . $hc_id . '\show')) {
                    mkdir($dir . '\\' . $hc_id . '\show');
                }
                if (!@opendir($dir . '\\' . $hc_id . '\hide')) {
                    mkdir($dir . '\\' . $hc_id . '\hide');
                }
                if (!@opendir($dir . '\\' . $hc_id . '\show\\' . $year)) {
                    mkdir($dir . '\\' . $hc_id . '\show\\' . $year);
                }
                if (!@opendir($dir . '\\' . $hc_id . '\hide\\' . $year)) {
                    mkdir($dir . '\\' . $hc_id . '\hide\\' . $year);
                }

                $zip_dir = $dir . '\\' . $hc_id . '\hide\\' . $year;
                /* if (!@opendir($zip_dir))
                 {
                     mkdir($zip_dir);
                 }*/


                $temp_dir = $dir . '\temp';
                if (!@opendir($temp_dir)) {
                    mkdir($temp_dir);
                }

                $temp_dir = $dir . '\temp\\' . uniqid($hc_id, true);
                if (!@opendir($temp_dir)) {
                    mkdir($temp_dir);
                }


                require_once(dirname(__FILE__) . '/../template/view/root/statist/excel_fis_frdo.php');


                $filelist = array();
                if ($handle = opendir($temp_dir)) {
                    while ($entry = readdir($handle)) {
                        if (!is_dir($entry)) {
                            $filelist[] = $entry;
//                        echo $entry;
                        }
                    }
                    closedir($handle);
                }

                $zip = new ZipArchive(); // подгружаем библиотеку zip
//            $zip_name = $zip_dir.'\\'.time().".zip"; // имя файла
                $zip_name = $zip_dir . '\\' . $org_name . "_" . $year . "_" . date('m', mktime(0, 0, 0, $month, 1)) . ".zip"; // имя файла
                if (file_exists($zip_name)) unlink($zip_name);
                if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {

                    die("* Sorry ZIP creation failed at this time");
                }
                foreach ($filelist as $file) {
                    $zip->addFile($temp_dir . '\\' . $file, iconv('cp1251', 'cp866', $file)); // добавляем файлы в zip архив
                }
                $zip->close();

                foreach ($filelist as $file) {
                    unlink($temp_dir . '\\' . $file);
                }
                @rmdir($temp_dir);


                $_SESSION['flash'] = 'Отчеты созданы ' . date('r') . ' (' . sprintf("%0.2f", (microtime(1) - $start)) . 'сек, ' . (memory_get_usage(1) / 1024 / 1024) . 'мб, ' . (mysql_num_rows($cert_result) + mysql_num_rows($dub_result)) . 'записей)';
            } else $_SESSION['flash'] = 'За выбранный период данных нет';

            $this->redirectByAction('excel_fis_frdo_report', ['month' => $month, 'year' => $year, 'hc' => $hc_id, 'limit' => $limit]);


        } else {

            $hcs = HeadCenters::getHeadOrgs();
//            $hcs = array_filter($hcs, function ($item) {
//                return $item['id'] == 1;
//            });
//            die(var_dump($hcs));

            return $this->render->view(
                'root/statist/excel_frdo',
                array(
                    'hcs' => $hcs,
                    'search' => $search,
                    'from' => $from,
                    'to' => $to,
                    'year' => filter_input(INPUT_GET, 'year') ?: $year,
                    'month' => filter_input(INPUT_GET, 'month') ?: $month,
                    'current_hc' => filter_input(INPUT_GET, 'hc'),
                    'limiter' => filter_input(INPUT_GET, 'limit') ?: $limiter,
                    'caption' => 'ПЕРЕДАЧА ДАННЫХ В ФИС ФРДО ОБРАЗОВАТЕЛЬНЫМИ ОРГАНИЗАЦИЯМИ, ПРОВОДИВШИМИ ЭКЗАМЕН, О СЕРТИФИКАТАХ О ВЛАДЕНИИ РУССКИМ ЯЗЫКОМ, ЗНАНИИ ИСТОРИИ РОССИИ И ОСНОВ ЗАКОНОДАТЕЛЬСТВА РОССИЙСКОЙ ФЕДЕРАЦИИ',
                )
            );
        }


    }

    public function frdo_excel_reports_list_action()
    {
        echo '<h1>Список отчетов для ФРДО</h1>';

        $hc = filter_input(INPUT_GET, 'hc', FILTER_VALIDATE_INT);
        $visible = filter_input(INPUT_GET, 'visible', FILTER_VALIDATE_INT);

        if (count($_POST)) {
            $hc = filter_input(INPUT_POST, 'hc', FILTER_VALIDATE_INT);
            $visible = filter_input(INPUT_POST, 'visible', FILTER_VALIDATE_INT);
        }

        $hcs = HeadCenters::getHeadOrgs();
//        $hcs = array_filter($hcs, function ($item) {
//            return $item['id'] == 1;
//        });

        ?>
        <form action="?action=frdo_excel_reports_list" method="post">
            <label>Организация :
                <div><select name="hc">
                        <? foreach ($hcs as $horg) {
                            if ($hc == $horg['id']) $selected = 'selected="selected"';
                            else $selected = '';
                            echo '<option value="' . $horg['id'] . '" ' . $selected . '>' . $horg['caption'] . '</option>';
                        }
                        ?>
                    </select></div>

            </label>

            <label>Статус :
                <div><select name="visible">
                        <?

                        if (!empty($visible)) $selected = 'selected="selected"';
                        else $selected = '';

                        echo '<option value="0">Скрытые</option>';
                        echo '<option value="1" ' . $selected . '>Доступные к скачиванию</option>';

                        ?>
                    </select></div>

            </label>
            <input type="submit" value="Вывести список">
        </form>

        <?

        if (!empty($hc)) {

            /*show_hide*/
            $file_name = filter_input(INPUT_POST, 'file_name');

            if (!empty($file_name)) {
                if (!empty($visible)) {

                    /*
                                $file=file_get_contents(FRDO_EXCEL_UPLOAD_DIR.'\\'.$hc.'\show\\'.$file_name);

                                file_put_contents(FRDO_EXCEL_UPLOAD_DIR.'\\'.$hc.'\hide\\'.$file_name,$file);
                                unlink(FRDO_EXCEL_UPLOAD_DIR.'\\'.$hc.'\show\\'.$file_name);
                    */
                    rename(FRDO_EXCEL_UPLOAD_DIR . '\\' . $hc . '\show\\' . $file_name, FRDO_EXCEL_UPLOAD_DIR . '\\' . $hc . '\hide\\' . $file_name);
                } else {
                    /*
                    $file=file_get_contents(FRDO_EXCEL_UPLOAD_DIR.'\\'.$hc.'\hide\\'.$file_name);

                    file_put_contents(FRDO_EXCEL_UPLOAD_DIR.'\\'.$hc.'\show\\'.$file_name,$file);
                    unlink(FRDO_EXCEL_UPLOAD_DIR.'\\'.$hc.'\hide\\'.$file_name);
                    */
                    rename(FRDO_EXCEL_UPLOAD_DIR . '\\' . $hc . '\hide\\' . $file_name, FRDO_EXCEL_UPLOAD_DIR . '\\' . $hc . '\show\\' . $file_name);

                }

            }
            /*/show_hide*/

            echo '<table class="table table-bordered  table-striped">';

            if (!empty($visible)) $show = 'show';
            else $show = 'hide';
            $dir = FRDO_EXCEL_UPLOAD_DIR . '\\' . $hc . '\\' . $show;
//        $filelist = array();
            if ($handle = @opendir($dir)) {

                while ($entry = readdir($handle)) {
                    if (!is_dir($entry)) {
                        $filelist = array();

                        $filelist[] = '<tr><td colspan="2"><h3>' . $entry . '</h3></td></tr>';

                        if ($handle2 = @opendir($dir . '\\' . $entry)) {
                            while ($entry2 = readdir($handle2)) {
                                if (!is_dir($entry2)) {


                                    $form = '<td style="width:30%"><form action="" method="post" style="margin:0">
                                    <input type="hidden" name="hc" value="' . $hc . '">
                                    <input type="hidden" name="visible" value="' . $visible . '">
                                    <input type="hidden" name="file_name" value="' . $entry . '\\' . $entry2 . '">';

                                    if (!empty($visible)) {
                                        $form .= '<input type="submit" value="скрыть для скачивания" class="btn btn-warning">';
                                    } else {
                                        $form .= '<input type="submit" value="сделать доступным для скачивания" class="btn btn-warning">';
                                    }
                                    $form .= '</form>
                                <a class="btn btn-danger"
                                    href="?action=excel_report_delete&file=' . $hc . '\\' . $show . '\\' . $entry . '\\' . $entry2 . '&hc=' . $hc . '&visible=' . $visible . '"
                                    onclick="return confirm(\'Вы уверены?\')">
                                    Удалить файл отчета</a>
                                </td>';


//                                $filelist[] = $entry;
                                    $filelist[] = '<tr><td><a href="?action=excel_report_download&file=' . $hc . '\\' . $show . '\\' . $entry . '\\' . $entry2 . '">' . $entry2 . '</a>' . $form . '</tr>';
                                }
                            }
                            closedir($handle2);
                        }


                    }

                    if (!empty($filelist) && count($filelist) > 1)
                        foreach ($filelist as $item) {
                            echo $item;
                        }
                    unset($filelist);

                }
                closedir($handle);
            }

            echo '</table>';

        }


    }

    public function excel_report_download_action()
    {

        $dir = FRDO_EXCEL_UPLOAD_DIR;
        $file = filter_input(INPUT_GET, 'file', FILTER_SANITIZE_MAGIC_QUOTES);


        $file_name = substr($file, strrpos($file, '\\') + 1, (strlen($file) - (strrpos($file, '\\') + 1)));

        if (file_exists($dir . '\\' . $file)) {
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            readfile($dir . '\\' . $file);
        } else {
            $this->redirectReturn();
        }


    }

    public function excel_report_delete_action()
    {

        $dir = FRDO_EXCEL_UPLOAD_DIR;
        $file = filter_input(INPUT_GET, 'file', FILTER_SANITIZE_MAGIC_QUOTES);

        $hc = filter_input(INPUT_GET, 'hc', FILTER_VALIDATE_INT);
        $visible = filter_input(INPUT_GET, 'visible', FILTER_VALIDATE_INT);

        $file_name = substr($file, strrpos($file, '\\') + 1, (strlen($file) - (strrpos($file, '\\') + 1)));

        if (file_exists($dir . '\\' . $file)) {
            unlink($dir . '\\' . $file);
            $this->redirectByAction('frdo_excel_reports_list', array('hc' => $hc, 'visible' => $visible));

        } else {
            $this->redirectReturn();
        }


    }

}