<?php
require_once 'AbstrctReport.php';
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 10:58
 * Îò÷åò 12
 */
class ReportMinobrReportAboutExamNotes extends AbstractReport
{
    public function execute($from, $to)
    {
        $connection = Connection::getInstance();
        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);

        $sql = "SELECT
  shc.short_name,
  shc.id,
         sap.document ,
  sdt_test_levels.caption,
  sdt_test_levels.id as level_id,
  count(distinct sap.id) as sdalo,
  sum(if(sap.blank_number is not null and length(sap.blank_number)>0 , 1,0)) as certs
FROM sdt_act_people sap
  left JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  left JOIN sdt_act sa
    ON sa.id = sat.act_id
  left JOIN sdt_university su
    ON sa.university_id = su.id
  left JOIN sdt_head_center shc
    ON su.head_id = shc.id
  left JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE sa.created BETWEEN '" . $connection->escape($connection->format_date($from)) . " 0:0:0' and '" . $connection->escape(
                $connection->format_date($to)
            ) . " 23:59:59'
and sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0

AND sa.state IN (" . $statest . ")

GROUP BY shc.id,
         sat.level_id,
         sap.document ";
//            die($sql);
        $res = $connection->query($sql);

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


        /*$gc = array(
            2 => 0,
            3 => 1,
            1 => 0,
            9 => 2,
            10 => 3,
            4 => 4,
            8 => 0,
            5 => 5,
            7 => 0,
           11 => 6,
           12 => 7,
           13 => 0,
        );*/

        $gc = HeadCenters::getStatistHcArrayAll();

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
        );
       /* $result = array(
            0 => array(
                'caption' => 'ÐÓÄÍ',
                'data' => $template,
            ),
            1 => array(
                'caption' => 'ÌÃÓ',
                'data' => $template,
            ),
            2 => array(
                'caption' => 'ÒÎÃÓ',
                'data' => $template,
            ),
            3 => array(
                'caption' => 'ÒþìÃÓ',
                'data' => $template,
            ),
            4 => array(
                'caption' => 'Ãîñ. ÈÐß èì. À.Ñ. Ïóøêèíà',
                'data' => $template,
            ),
            5 => array(
                'caption' => 'ÑÏáÃÓ',
                'data' => $template,
            ),
            6 => array(
                'caption' => 'ÂîëÃÓ',
                'data' => $template,
            ),
            7 => array(
                'caption' => 'ÊÔÓ',
                'data' => $template,
            ),

        );*/

        $result = HeadCenters::getStatistResultArrayAll($template);


        foreach ($res as $item) {
            if (!array_key_exists($item['id'], $gc)) {
                continue;
            }

            $cr = &$result[$gc[$item['id']]]['data'];
            if ($item['document'] === ActMan::DOCUMENT_CERTIFICATE) {
                $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                $cr['certs'] += $item['certs'];
            } else {
                $cr['note_levels'][$levels[$item['level_id']]] += $item['sdalo'];
                $cr['notes'] += $item['certs'];
            }
        }


//            echo '<pre>';
//            (var_dump($res));
//            echo '<hr>';

        $sql = 'select  count(distinct su.id) as cc, su.head_id from sdt_university su
where  su.id in (select  sa.university_id  from sdt_act sa where sa.test_level_type_id = 2
and  sa.created BETWEEN \'' . $connection->escape($connection->format_date($from)) . ' 0:0:0\'
and \'' . $connection->escape(
                $connection->format_date($to)
            ) . ' 23:59:59\'
)
group by su.head_id';
//            die($sql);
        $res = $connection->query($sql);

        foreach ($res as $item) {
            if (!array_key_exists($item['head_id'], $gc)) {
                continue;
            }

            $cr = &$result[$gc[$item['head_id']]]['data'];

            $cr['orgs'] += $item['cc'];
        }
        
        return $result;
    }
}