<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 04.07.2016
 * Time: 11:18
 * Îò÷åò 5
 */
class minobr_pfur_local_report_about_exam extends AbstractReport
{
    public function execute($from,$to,$region){
        $C = Connection::getInstance();
        $region_sql = '';
//            var_dump($region);
        if ($region) {
            $region_sql = 'and su.region_id = ' . intval($region);
        }
        $states = array();
        foreach (Act::getInnerStates() as $st) {
            $states[] = "'" . $st . "'";
        }
        $states = implode(', ', $states);

        $sql = "SELECT
  su.name, su.id, shc.id AS head_id, su.legal_address,
  sdt_test_levels.caption,
  sdt_test_levels.id AS level_id,
  count(DISTINCT sap.id) AS sdalo,
  sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs,
  sap.document
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
AND sa.state IN (" . $states . ")
" . $region_sql . "

  GROUP BY su.id, sat.level_id, sap.document
    ORDER BY shc.id, su.name";
//            die($sql);
        $res = $C->query($sql);
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


       /* $levels = array(
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
            13 => 4,
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
            4 => array(
                'caption' => 'ÐÓÄÍ ÑÎ×È',
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
                $contractRes = $C->query($contractSql);
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

            $lc[$item['document']][$levels[$item['level_id']]] += $item['sdalo'];
            $lc['certs'] += $item['certs'];
        }
        
        return  $result;
    }
}