<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 05.07.2016
 * Time: 11:08
 */
class minobr_org_report_about_exam_by_citizenship extends AbstractReport
{
    public function execute($from, $to, $hc, $lc)
    {
        $connection = Connection::getInstance();
        //$centers = array(1, 2, 7, 8);
        $centers = array();
        $all_centers = HeadCenters::getHeadOrgs();
        foreach ($all_centers as $cen) {
            $centers[] = $cen['id'];
        }
        //$centers = HeadCenters::getHeadOrgs();
        $sql_hc = '';
        if ($hc && is_numeric($hc)) {
            
            $lc_list = Univesities::getByHORG($hc);
//var_dump($lc_list); die();
            $centers = array($hc);
            $sql_hc = ' AND sho.id=' . $hc . ' ';

            $chosen_hc_name = ' по "' . HeadCenter::getOrgName($hc) . '"';

            /*   $chosen_hc_name = ' по головному центру "' . HeadCenter::getByID($hc) . '"';
           } elseif ($hc === 'pfur') {
               $chosen_hc_name = 'объеденённый';
               $sql_hc = ' and shc.id in (' . implode(",", $centers) . ') ';*/

        } else {

            $sql_hc = ' and sho.id in (' . implode(",", $centers) . ') ';

        }

        $lc_caption='';

        if ($hc && $lc && is_numeric($lc)) {


            $sql_hc .= ' AND su.id=' . $lc . ' ';

            $lc_caption = ' по "' . University::getByID($lc)->name . '"';

            /*   $chosen_hc_name = ' по головному центру "' . HeadCenter::getByID($hc) . '"';
           } elseif ($hc === 'pfur') {
               $chosen_hc_name = 'объеденённый';
               $sql_hc = ' and shc.id in (' . implode(",", $centers) . ') ';*/

        }
            $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }

        $statest = implode(', ', $statest);


        $sql = "
            SELECT
country.name, country.id, sho.id AS h_id, sap.document, sdt_test_levels.caption, sdt_test_levels.id AS level_id,
count(DISTINCT sap.id) AS sdalo,

sum(if(sap.blank_number IS NOT NULL AND length(sap.blank_number)>0 , 1,0)) AS certs
FROM sdt_act_people sap
LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
LEFT JOIN sdt_act sa ON sa.id = sat.act_id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
LEFT JOIN sdt_test_levels ON sat.level_id = sdt_test_levels.id
LEFT JOIN country ON country.id=sap.country_id
WHERE sa.created BETWEEN '" . $connection->escape($connection->format_date($from)) . " 0:0:0' AND '" . $connection->escape(
                $connection->format_date($to)
            ) . " 23:59:59'
AND sa.test_level_type_id = 2
AND sa.deleted = 0
 AND sap.deleted = 0
 AND sat.deleted = 0
 AND shc.deleted = 0
 AND sho.deleted = 0

" . $sql_hc . "
AND sa.state IN (" . $statest . ")
GROUP BY sho.id, country.id, sat.level_id, sap.document";
//die($sql);
        $res = $connection->query($sql);


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
        );

        $result = array();
        /*            if ($hc === 'pfur') {
                        $result['pfur'] = array();
                    } else {
                        foreach ($centers as $center) {
                            $result[$center] = array();
                        }
                    } 13.10.15*/
        $result[$hc] = array();


        foreach ($result as $h_id => $list) {
            foreach (Countries::getAll() as $reg) {

                $result[$h_id][$reg->id] = array(
                    'caption' => $reg->name,
                    'data' => $template,
                );

            }
            $result[$h_id]['no'] = array(
                'caption' => 'Не указана',
                'data' => $template,
            );
        }

        if ($res) {
            foreach ($res as $item) {


                $region_id = $item['id'];
                if (!$region_id) {
                    $region_id = 'no';
                }

                /*                if ($hc === 'pfur') {
                                    $cr = &$result['pfur'][$region_id]['data'];
                                } else {
                                    $cr = &$result[$item['h_id']][$region_id]['data'];
                                }  13.10.15*/
                $cr = &$result[$hc][$region_id]['data'];

                if ($item['document'] === ActMan::DOCUMENT_CERTIFICATE) {
                    $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['certs'] += $item['certs'];
                } else {
                    $cr['note_levels'][$levels[$item['level_id']]] += $item['sdalo'];
                    $cr['notes'] += $item['certs'];
                }


            }
        }
        return array(
            'result'=>$result,
            'lc_caption'=>$lc_caption,
        );
    }
}