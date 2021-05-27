<?php

namespace SDT\statistic;

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 10:58
 * Отчет 12
 */
class ExamByYear extends \AbstractReport
{
    public function execute($from, $to)
    {
        $connection = \Connection::getInstance();
        $statest = array();
        foreach (\Act::getInnerStates() as $st) {
            $statest[] = "'".$st."'";
        }
        $statest = implode(', ', $statest);
        $sql = "SELECT
   year(sa.created) AS y, 
  MONTH(sa.created) AS m,
  COUNT(DISTINCT sap.id) AS sdalo,
  SUM(IF(sap.blank_number IS NOT NULL AND LENGTH(sap.blank_number) > 0, 1, 0)) AS certs
FROM sdt_act_people sap
  left JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  left JOIN sdt_act sa
    ON sa.id = sat.act_id
  left JOIN sdt_university su
    ON sa.university_id = su.id
    left JOIN sdt_university su_parent
    ON su_parent.id = su.parent_id
  left JOIN sdt_head_center shc
    ON su.head_id = shc.id
  left JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE sa.created BETWEEN '".$from."-01-01 0:0:0' and '".$to."-12-31 23:59:59'
and sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0

AND sa.state IN (".$statest.")
%s
GROUP BY year(sa.created), MONTH(sa.created)";
        $whereRudnHead = '
        and shc.horg_id = 1
        and (su.is_head = 1 or su_parent.is_head = 1)
        ';
        $whereRudn = '
        and shc.horg_id = 1
        
        ';
        $resultRudnHead = $connection->query(sprintf($sql, $whereRudnHead));
        $resRudn = $connection->query(sprintf($sql, $whereRudn));
        $resAll = $connection->query(sprintf($sql, ''));
        $result = [
            'rudn_head' => $this->build($from, $to, $resultRudnHead),
            'rudn' => $this->build($from, $to, $resRudn),
            'all' => $this->build($from, $to, $resAll),
        ];

        return $result;
    }

    private function build($from, $to, $list)
    {
        $result = [];
        foreach (range($from, $to) as $item) {
            $result[$item] = [];
            foreach (range(1, 12) as $m) {
                $result[$item][$m] = 0;
            }
        }
        foreach ($list as $row) {
            $result[$row['y']][$row['m']] = $row['sdalo'];
        }

        return $result;
    }
}