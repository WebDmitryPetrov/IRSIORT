<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 04.07.2016
 * Time: 11:28
 * Отчет 11
 */
class report_by_regions extends AbstractReport
{
    public function execute($from, $to, $region, $hc)
    {
        $C = Connection::getInstance();
        if ($region) {
            $sql_region = ' AND su.region_id=' . $region . ' ';

        } else {
            $sql_region = '';

        }

        if (is_numeric($hc) && !empty($hc)) {
            $sql_hc = ' AND shc.id=' . $hc . ' ';
            $sql_hc2 = ' AND head_id=' . $hc . ' ';
            $chosen_hc_name = ' по головному центру "' . HeadCenter::getByID($hc) . '"';
        } elseif ($hc === 'pfur') {
            $sql_hc = ' AND shc.horg_id = 1 ';
            $sql_hc2 = ' AND shc.horg_id = 1 ';
            $chosen_hc_name = ' объединенный РУДН';

        } else {
            $sql_hc = $sql_hc2 = $chosen_hc_name = '';
        }

        $states = array();
        foreach (Act::getInnerStates() as $st) {
            $states[] = "'" . $st . "'";
        }
        $states = implode(', ', $states);

        $sql = "SELECT
 su.region_id,
  sdt_test_levels.caption,
  sdt_test_levels.id AS level_id,
  count(DISTINCT sap.id) AS sdalo,
   sap.document,
  sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs
FROM sdt_act_people sap
  LEFT JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa
    ON sa.id = sat.act_id
  LEFT JOIN sdt_university su
    ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc
    ON su.head_id = shc.id
  LEFT JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE sa.created BETWEEN '" . $C->escape($C->format_date($from)) . " 0:0:0' AND '" . $C->escape(
                $C->format_date($to)
            ) . " 23:59:59'
AND sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0

" . $sql_region . "
" . $sql_hc . "

AND sa.state IN (" . $states . ")

GROUP BY su.region_id,
 sap.document,
         sat.level_id";

        $res = $C->query($sql);


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


        $template = array(
            'levels' => array(
                0 => 0,
                1 => 0,
                2 => 0,

            ),
            'certs' => 0,
            'note_levels' => array(
                0 => 0,
                1 => 0,
                2 => 0,

            ),
            'notes' => 0,
            'orgs' => 0,
            'orgs_distinct' => 0,
        );
        $result = array();
        if ($region) {
            $regs = Region::getByID($region);
            $result[$regs->id] = array(
                'caption' => $regs->caption,
                'data' => $template,
            );

        } else {
            foreach (Regions::getSorted() as $reg) {
                $result[$reg->id] = array(
                    'caption' => $reg,
                    'data' => $template,
                );
            }
            $result['no'] = array(
                'caption' => 'Не указан',
                'data' => $template,
            );
        }


        if ($res) {
            foreach ($res as $item) {

                $region_id = $item['region_id'];
                if (!$region_id) {
                    $region_id = 'no';
                }
                $cr = &$result[$region_id]['data'];

                if ($item['document'] === ActMan::DOCUMENT_CERTIFICATE) {
                    $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['certs'] += $item['certs'];
                } else {
                    $cr['note_levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['notes'] += $item['certs'];
                }

//                $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
//                $cr['certs'] += $item['certs'];
            }
        }

        $sql = 'SELECT count(*) AS cc, su.region_id, su.head_id FROM sdt_university AS su
            LEFT JOIN sdt_head_center shc ON su.head_id=shc.id
            WHERE su.deleted=0 AND shc.deleted=0' . $sql_region . ' ' . $sql_hc2 . ' GROUP BY su.region_id';


        $sql = "SELECT su.region_id,
  count(distinct su.id) as cc
FROM sdt_act_people sap
  LEFT JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  LEFT JOIN sdt_act sa
    ON sa.id = sat.act_id
  LEFT JOIN sdt_university su
    ON sa.university_id = su.id
  LEFT JOIN sdt_head_center shc
    ON su.head_id = shc.id
  LEFT JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE sa.created BETWEEN '" . $C->escape($C->format_date($from)) . " 0:0:0' AND '" . $C->escape(
                $C->format_date($to)
            ) . " 23:59:59'
AND sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0

" . $sql_region . "
" . $sql_hc . "

AND sa.state IN (" . $states . ")

GROUP BY su.region_id
 ";
//die($sql);
        $res = $C->query($sql);

        if ($res) {
            foreach ($res as $item) {


                $region_id = $item['region_id'];
                if (!$region_id) {
                    $region_id = 'no';
                }
                $cr = &$result[$region_id]['data'];

                $cr['orgs'] += $item['cc'];
                $cr['orgs_distinct'] = $item['cc'];
            }
        }
        return $result;
    }
}