<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 04.07.2016
 * Time: 11:34
 * Отчет 15
 */
class people_by_lc extends AbstractReport
{
    public function execute($level_type, $from, $to, $lc, $contract = null)
    {
        $C = Connection::getInstance();

//        $centers = array(1, 2, 7, 8,13);
        $centers = HeadCenters::getStatistHCByHO(1);


        $states = array();
        foreach (Act::getInnerStates() as $st) {
            $states[] = "'" . $st . "'";
        }

        $states = implode(', ', $states);
        $sqlContract = '';
        if ($contract) {
            $sqlContract .= ' and sa.university_dogovor_id=' . intval($contract);
        }

        $sql = "
SELECT 
if(dup.surname_rus IS NULL, sap.surname_rus, dup.surname_rus) AS surname_rus ,
if(dup.name_rus IS NULL, sap.name_rus, dup.name_rus) AS name_rus ,
if(dup.surname_lat IS NULL, sap.surname_lat, dup.surname_lat) AS surname_lat ,
if(dup.name_lat IS NULL, sap.name_lat, dup.name_lat) AS name_lat ,
sap.birth_date,
sap.passport_name,
sap.passport_series,
sap.passport,
sap.passport_department,
sap.passport_date,
if(dup.certificate_number IS NULL, sap.blank_number, dup.certificate_number) AS certificate_number ,
sap.document_nomer,
sap.testing_date,
sap.blank_date,
stl.caption,
sap.document

 FROM sdt_act_people sap
LEFT JOIN (
  SELECT  cd.surname_rus, cd.name_rus, cd.name_lat, cd.surname_lat, cd.certificate_number, cd.user_id FROM certificate_duplicate cd 
LEFT JOIN certificate_duplicate cd2 ON 
cd.user_id = cd2.user_id AND cd.id < cd2.id  
WHERE cd2.id IS NULL AND cd.deleted = 0 AND cd2.deleted = 0
GROUP BY cd.id

ORDER BY  cd.user_id ASC


) dup ON dup.user_id = sap.id
LEFT JOIN sdt_act sa ON sa.id = sap.act_id 
LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
LEFT JOIN sdt_test_levels stl ON sat.level_id = stl.id
WHERE 

 sa.university_id=  " . intval($lc) . "
 " . $sqlContract . "
 AND sa.deleted = 0
 AND sap.deleted = 0
AND sa.test_level_type_id = " . intval($level_type) . "
AND sa.testing_date >= '" . $C->escape($C->format_date($from)) . " 0:0:0' AND sa.testing_date <= '" . $C->escape(
                $C->format_date($to)
            ) . " 23:59:59'
AND sa.state IN (" . $states . ");";


        $res = $C->query($sql);

        return $res;
    }
}