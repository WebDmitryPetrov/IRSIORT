<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 04.07.2016
 * Time: 16:12
 */
class minobr_pfur_local_report_about_exam_with_empty extends AbstractReport
{
public function execute($from,$to,$region){
    $connection = Connection::getInstance();
    $region_sql = '';
//            var_dump($region);
    if ($region) {
        $region_sql = 'and su.region_id = ' . intval($region);
    }
    $statest = array();
    foreach (Act::getInnerStates() as $st) {
        $statest[] = "'" . $st . "'";
    }
    $statest = implode(', ', $statest);

/*êîñòûëü íà ËÖ, ó êîòîðûõ null â created*/
   /* $sql = 'SELECT DATE_ADD(MIN(created),INTERVAL -1 DAY) as min FROM sdt_university';
    $res = $connection->query($sql,true);
    if (strtotime($res['min']) > strtotime($from) || strtotime($res['min']) > strtotime($to))
        $null_date_universities=' OR su.created IS NULL';
    else $null_date_universities='';*/



    $sql = "SELECT su.name, su.id, shc.id AS head_id, su.legal_address,
sdt_test_levels.caption, sdt_test_levels.id AS level_id,
 COUNT(DISTINCT sap.id) AS sdalo,
  SUM(IF(sap.blank_number IS NOT NULL AND LENGTH(sap.blank_number)>0, 1,0)) AS certs,
   sap.document
   FROM sdt_university su
   LEFT JOIN sdt_act sa ON sa.university_id = su.id
   LEFT JOIN sdt_act_test sat ON sa.id = sat.act_id
LEFT JOIN  sdt_act_people sap ON sap.test_id = sat.id



LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_test_levels ON sat.level_id = sdt_test_levels.id
WHERE ( sa.created BETWEEN '" . $connection->escape($connection->format_date($from)) . " 0:0:0' AND '" . $connection->escape(
            $connection->format_date($to)
        ) . " 23:59:59' OR sa.created IS NULL)

AND       (su.created <= '" . $connection->escape($connection->format_date($from)) . " 23:59:59' OR su.created <= '" . $connection->escape(
            $connection->format_date($to)
        ) . " 23:59:59' OR su.created IS NULL)


AND (sa.test_level_type_id = 2 OR sa.test_level_type_id IS NULL)
AND (sa.deleted = 0 OR sa.deleted IS NULL)
AND (sap.deleted = 0  OR sap.deleted IS NULL)
AND (sat.deleted = 0   OR sat.deleted IS NULL)
AND shc.deleted = 0
AND (sa.state IN (" . $statest . ") OR sa.state IS NULL)
" . $region_sql . "

  GROUP BY su.id, sat.level_id, sap.document
    ORDER BY shc.id, su.name";
//            die($sql);
    $res = $connection->query($sql);
//            AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'
//            die($sql);
//and shc.id in (1,2,7,8)
//            $levels = array(
//                0 => array(
//                    13,
//                    16
//                ),
//                1 => array(
//                    14,
//                    17
//                ),
//                2 => array(
//                    15,
//                    18
//                ),
//            );


    /*$levels = array(
        13 => 0,
        16 => 0,
        19 => 0,
        14 => 1,
        17 => 1,
        20 => 1,
        15 => 2,
        18 => 2,
        21 => 2,
        22 => 2,
        23 => 2,
        24 => 2,
        25 => 2,
        26 => 2,
        27 => 2,
        28 => 2,
        29 => 2,
    );*/

    $levels = ExamHelper::getLevelGroups();


   /* $gc = array(
        2 => 1,
        1 => 0,
        8 => 3,
        7 => 2,
    );*/

    $gc = HeadCenters::getStatistHcArrayPfur();

    $template = array(
        'caption' => '',
        'certificate' => array(
            0 => 0,
            1 => 0,
            2 => 0,

        ),
        'note' => array(
            0 => 0,
            1 => 0,
            2 => 0,

        ),
        'certs' => 0,
//                'orgs' => 0,
    );
    /*$result = array(
        0 => array(
            'caption' => 'ÌÖÒ',
            'centers' => array(),

        ),
        1 => array(
            'caption' => 'ÃÖÒÐÊÈ',
            'centers' => array(),
        ),
        2 => array(
            'caption' => 'ØÎÏÌ',
            'centers' => array(),
        ),
        3 => array(
            'caption' => 'ÖÒ ÐÓÄÍ',
            'centers' => array(),
        ),
    );*/
    $result = HeadCenters::getStatistResultArrayPfur();


    foreach ($res as $item) {
        if (!array_key_exists($item['head_id'], $gc)) {
            continue;
        }


        $cr = &$result[$gc[$item['head_id']]]['centers'];
        if (!array_key_exists($item['id'], $cr)) {
            $cr[$item['id']] = $template;
            $contractSql = 'SELECT * FROM sdt_university_dogovor WHERE deleted = 0 AND  university_id =  ' . intval(
                    $item['id']
                );
            $contractRes = $connection->query($contractSql);
            $dogovor = array();
            if ($contractRes) {
                foreach ($contractRes as $d) {
                    $dogovor[] = $d['number'] . ' îò ' . $this->date($d['date']);
                }
            }

            $cr[$item['id']]['caption'] = $item['name'];
            $cr[$item['id']]['address'] = $item['legal_address'];
            $cr[$item['id']]['dogovor'] = implode('; ', $dogovor);
        }
        $lc = &$cr[$item['id']];
        if (!empty($item['document']) && !empty($item['level_id']) && !empty($item['sdalo'])) {
            $lc[$item['document']][$levels[$item['level_id']]] += $item['sdalo'];
        }
        $lc['certs'] += $item['certs'];
    }
    return $result;

}
}