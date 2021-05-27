<?php
require_once 'AbstrctReport.php';
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 11:18
 * Îò÷åò 6
 */
class ReportMinobrLocalReportAboutExam extends AbstractReport
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

        $sql = "SELECT
  su.name, su.legal_address, su.id, shc.id as head_id,
  sdt_test_levels.caption,
  sdt_test_levels.id as level_id,
  count(distinct sap.id) as sdalo,
  sum(if(sap.blank_number is not null and length(sap.blank_number)>0 , 1,0)) as certs,
  sap.document
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
" . $region_sql . "

  GROUP BY su.id, sat.level_id, sap.document
    order by shc.id, su.name";
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


        /*$gc = array(
            2 => 1,
            1 => 0,
            8 => 3,
            7 => 2,
            3 => 4,
            10 => 5,
            4 => 6,
            9 => 7,
            5 => 8,
            11 => 9,
            12 => 10,
            13 => 11,
        );*/

        $gc = HeadCenters::getStatistHcArrayThroughAll();

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

       /* $result = array(
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
            4 => array(
                'caption' => 'ÌÃÓ',
                'centers' => array(),
            ),
            5 => array(
                'caption' => 'ÒÞÌÃÓ',
                'centers' => array(),
            ),
            6 => array(
                'caption' => 'Ãîñ. ÈÐß èì. À.Ñ. Ïóøêèíà ',
                'centers' => array(),
            ),
            7 => array(
                'caption' => 'ÒÎÃÓ',
                'centers' => array(),
            ),
            8 => array(
                'caption' => 'ÑÏáÃÓ',
                'centers' => array(),
            ),
            9 => array(
                'caption' => 'ÂîëÃÓ',
                'centers' => array(),
            ),
            10 => array(
                'caption' => 'ÊÔÓ',
                'centers' => array(),
            ),
            11 => array(
                'caption' => 'ÐÓÄÍ ÑÎ×È',
                'centers' => array(),
            ),
        );*/

        $result = HeadCenters::getStatistResultArrayThroughAll();


        foreach ($res as $item) {
            if (!array_key_exists($item['head_id'], $gc)) {
                continue;
            }


            $cr = &$result[$gc[$item['head_id']]]['centers'];
            if (!array_key_exists($item['id'], $cr)) {
                $cr[$item['id']] = $template;
                $contractSql = 'select * from sdt_university_dogovor where deleted = 0 and  university_id =  ' . intval(
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
                $cr[$item['id']]['dogovor'] = implode('<br>', $dogovor);
            }
            $lc = &$cr[$item['id']];

            $lc[$item['document']][$levels[$item['level_id']]] += $item['sdalo'];
            $lc['certs'] += $item['certs'];
        }


        /*  $sql = 'select count(*) as cc, head_id from sdt_university where deleted=0 group by head_id';
          $res = $connection->query($sql);

          foreach ($res as $item) {
              if (!array_key_exists($item['head_id'], $gc)) {
                  continue;
              }

              $cr = &$result[$gc[$item['head_id']]]['data'];

              $cr['orgs'] += $item['cc'];
          }*/
//echo '<pre>';
//            die(var_dump($result));
        
        return $result;
    }
}