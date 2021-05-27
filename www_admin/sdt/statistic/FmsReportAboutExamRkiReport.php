<?php
require_once 'AbstrctReport.php';
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 11:26
 * Îò÷åò 8
 */
class FmsReportAboutExamRkiReport extends AbstractReport
{
    public function execute($from,$to){
        $connection = Connection::getInstance();
        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);

        $sql = "SELECT
  shc.short_name,
  shc.id,
  sdt_test_levels.caption,
  sdt_test_levels.id as level_id,
  sap.document,
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
and sa.test_level_type_id = 1
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0

AND sa.state IN (" . $statest . ")

GROUP BY shc.id,
         sat.level_id,sap.document";
        //AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'
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


        $levels = array(
            1 => 0,
            2 => 1,
            3 => 2,
            5 => 3,
            6 => 4,
            7 => 5,
            8 => 6,
            10 => 8,
            11 => 7,
            12 => 8,
        );


       /* $gc = array(
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
            13 => 8,
        );*/

        $gc = HeadCenters::getStatistHcArrayAll();

        $template = array(

            ActMan::DOCUMENT_CERTIFICATE => array(
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,

            ),
            ActMan::DOCUMENT_NOTE => array(
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,

            ),
            'certs' => 0,
            'orgs' => 0,
        );
        /*$result = array(
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
                'caption' => 'ÑÏáÃÓ ',
                'data' => $template,
            ),
            6 => array(
                'caption' => 'ÂîëÃÓ ',
                'data' => $template,
            ),
            7 => array(
                'caption' => 'ÊÔÓ ',
                'data' => $template,
            ),
 8 => array(
                'caption' => 'ÐÓÄÍ ÑÎ×È ',
                'data' => $template,
            ),

        );*/

        $result = HeadCenters::getStatistResultArrayAll($template);


        foreach ($res as $item) {
            if (!array_key_exists($item['id'], $gc)) {
                continue;
            }

            $cr = &$result[$gc[$item['id']]]['data'];
            if (array_key_exists($item['level_id'], $levels)) {
                $cr[$item['document']][$levels[$item['level_id']]] += $item['sdalo'];
                if ($item['document'] == ActMan::DOCUMENT_CERTIFICATE) {
                    $cr['certs'] += $item['certs'];
                }
            } else {
                /*      echo '<pre>';
                      var_dump($item);
                      echo '</pre>';*/
            }
        }


//            echo '<pre>';
//            (var_dump($res));
//            echo '<hr>';

        $sql = 'select count(*) as cc, head_id from sdt_university where deleted=0 group by head_id';
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