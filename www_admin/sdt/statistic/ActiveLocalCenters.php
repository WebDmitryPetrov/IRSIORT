<?php
require_once 'AbstrctReport.php';

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 10:58
 */
class ActiveLocalCenters extends AbstractReport
{
    public function execute($from, $to)
    {
        $connection = Connection::getInstance();
        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);

        $sql = "

SELECT  count(DISTINCT su.id) AS center_count, sho.id AS head_id,
sho.captoin, group_concat(DISTINCT su.id) AS ids, su.deleted

   FROM sdt_university su
INNER JOIN sdt_act sa ON sa.university_id = su.id AND sa.test_level_type_id=2 AND sa.created BETWEEN '" . $connection->escape($connection->format_date($from)) . " 0:0:0' AND '" . $connection->escape(
                $connection->format_date($to)
            ) . " 23:59:59'
 JOIN sdt_act_people sap ON sap.act_id = sa.id 

INNER JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
WHERE su.head_id > 0 
AND  (sap.blank_number <> '' AND sap.blank_number IS NOT NULL)
GROUP BY sho.id, su.deleted";
//            die($sql);
        $res = $connection->query($sql);
        $result = array();
        foreach ($res as $row) {
            if (!array_key_exists($row['head_id'], $result)) {
                $result[$row['head_id']] = array(
                    'caption' => $row['captoin'],
                    'active' => 0,
                    'deleted' => 0,
                    'active_ids' => array(),
                    'deleted_ids' => array(),
                );
            }
            if ($row['deleted'] == 0) {
                $result[$row['head_id']]['active'] = $row['center_count'];
                $result[$row['head_id']]['active_ids'] = $row['ids'];
            }
            if ($row['deleted'] == 1) {
                $result[$row['head_id']]['deleted'] = $row['center_count'];
                $result[$row['head_id']]['deleted_ids'] = $row['ids'];
            }
        }


        return $result;
    }
}