<?php
require_once 'AbstrctReport.php';

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 11:12
 * Отчет 13
 */
class MoneyByDogovors extends AbstractReport
{
    public function execute($from, $to, $level_type, $head_id)
    {
        $connection = Connection::getInstance();
        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);

        $sql = "SELECT a.su_id,a.dog_id, a.name, a.number, a.level_id,
sum((a.people_first * a.price0 + a.people_subtest_retry * a.price1 + a.people_subtest_2_retry * a.price2)) AS money,
 sum(a.people_math) AS people_count
FROM 
(
SELECT 

su.id AS su_id, su.name,su.short_name,
sud.id AS dog_id, sud.number, sud.caption,
 sat.level_id,

 sat.people_first, IF(sat.price > 0, sat.price, stl.price) AS price0,
 sat.people_subtest_retry, IF(sat.price_subtest_retry > 0, sat.price_subtest_retry, COALESCE (stl.sub_test_price,0)) AS price1,
	sat.people_subtest_2_retry, IF(sat.price_subtest_2_retry > 0, sat.price_subtest_2_retry, COALESCE (stl.sub_test_price_2,0)) AS price2,
 
	 sat.people_first * sat.price
 + sat.people_subtest_retry * sat.price_subtest_retry
	+ sat.people_subtest_2_retry * sat.price_subtest_2_retry AS money2, 
	 
 sat.people_first 
 + sat.people_subtest_retry 
	+ sat.people_subtest_2_retry AS people_math, 
	 
	 sat.people_count AS people_count, 
	sa.id AS sa_ids,
	sa.id AS max_sa_id,
	 su.head_id,
	 sat.money AS mc,

	sa.created AS sa_dates
FROM	sdt_university_dogovor sud
LEFT JOIN sdt_university su ON su.id = sud.university_id
LEFT JOIN sdt_act sa ON sa.university_dogovor_id = sud.id
LEFT JOIN sdt_act_test sat ON sat.act_id = sa.id
LEFT JOIN sdt_test_levels stl ON sat.level_id = stl.id
WHERE sa.deleted = 0 
 AND sa.created BETWEEN '" . $connection->escape($connection->format_date($from)) . " 0:0:0' AND '" . $connection->escape(
                $connection->format_date($to)
            ) . " 23:59:59'
           AND sa.state IN (" . $statest . ")  
AND su.head_id =  " . $head_id . "
AND sa.test_level_type_id = " . $level_type . "
-- AND sa.id=398275
HAVING 
 people_math > 0
ORDER BY su.id, sud.id

) a
--  having money = 0 or money is null or money < money2
 GROUP BY a.su_id, a.dog_id, a.level_id
 order by a.name, a.number
 ";
        //AND sap.document = '" . ActMan::DOCUMENT_NOTE . "'
        $res = $connection->query($sql);


        if ($level_type == 2) {
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

        } else {
            $levels = array();
            for ($i = 0; $i < 100; $i++)
                $levels[$i] = 0;
        }
        $result = [];
        if (!$res) $res = [];
        foreach ($res as $row) {
            if (!array_key_exists($row['su_id'], $result)) {
                $result[$row['su_id']] = array(
                    'id' => $row['su_id'],
                    'caption' => $row['name'],
                    'dogovors' => array(),
                );
            }
            if (!array_key_exists($row['dog_id'], $result[$row['su_id']]['dogovors'])) {
                $result[$row['su_id']]['dogovors'][$row['dog_id']] =
                    array(
                        'caption' => $row['number'],
                        'levels' => [],
                        'numbers' =>
                            array(
                                0 => array(
                                    'money' => 0,
                                    'people' => 0,
                                ),
                                1 => array(
                                    'money' => 0,
                                    'people' => 0,
                                ),
                                2 => array(
                                    'money' => 0,
                                    'people' => 0,
                                ),
                            ));
            }
            $result[$row['su_id']]['dogovors'][$row['dog_id']]['levels'][] = $row['level_id'];
            $result[$row['su_id']]['dogovors'][$row['dog_id']]['numbers'][$levels[$row['level_id']]]['money'] += $row['money'];
            $result[$row['su_id']]['dogovors'][$row['dog_id']]['numbers'][$levels[$row['level_id']]]['people'] += $row['people_count'];

        }

        return $result;
    }
}