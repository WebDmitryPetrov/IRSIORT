<?php
require_once 'AbstrctReport.php';

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 11:12
 * Отчет 13
 */
class MoneyByCitizen extends AbstractReport
{
    public function execute($from, $to, $level_type, $horg_id = null, $citizen = null)
    {
        $connection = Connection::getInstance();
        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);
        $citizen_sql = ' ';
        if ($citizen) {
            $citizen_sql = "AND sap.country_id = " . intval($citizen);
        }
        $sql = "
SELECT a.horg_id, a.horg_caption, -- a.level_id,   a.head_id, 
SUM((a.people_first * a.price0 + a.people_subtest_retry * a.price1 + a.people_subtest_2_retry * a.price2)) AS money,
 SUM(a.people_math) AS people_math,
 
  SUM(a.people_count) AS people_count,
   year(sa_dates) AS d_year
FROM 
(
SELECT 

sa.id AS act_id, sat.id AS test_id,sho.id AS horg_id, sho.captoin AS horg_caption, shc.id AS head_id,

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
	 
	 sat.people_count AS people_count_old, 
	 count(distinct sap.id) AS people_count, 
	sa.id AS sa_ids,
	sa.id AS max_sa_id,
	
	 sat.money AS mc,

	sa.created AS sa_dates
FROM sdt_act_test sat

LEFT JOIN	sdt_act sa ON sat.act_id = sa.id
LEFT JOIN sdt_act_people sap ON sap.test_id = sat.id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
LEFT JOIN sdt_test_levels stl ON sat.level_id = stl.id
WHERE sa.deleted = 0  
AND sap.deleted = 0
AND sa.created BETWEEN '" . $connection->escape($connection->format_date($from)) . " 0:0:0' AND '" . $connection->escape(
                $connection->format_date($to)
            ) . " 23:59:59'
           AND sa.state IN (" . $statest . ")  
-- AND su.head_id =  " . $horg_id . "
AND sa.test_level_type_id = " . $level_type . "
" . $citizen_sql . "
GROUP BY sat.id
ORDER BY sa.id, sat.id

) a
--  having money = 0 or money is null or money < money2
GROUP BY d_year, a.horg_id -- , a.level_id
ORDER BY d_year,  a.horg_id -- , a.level_id;";
        $sql = "
SELECT a.horg_id, a.horg_caption, -- a.level_id,   a.head_id, 
SUM((a.money)) AS money,
 SUM(a.people_math) AS people_math,
 
  SUM(a.people_count) AS people_count,
   year(sa_dates) AS d_year
FROM 
(
SELECT 

sa.id AS act_id, sat.id AS test_id,sho.id AS horg_id, sho.captoin AS horg_caption, shc.id AS head_id,

 sat.level_id,

-- sat.people_first,
 sum(if(sap.is_retry=0,1,0))  as people_first_c,
  IF(sat.price > 0, sat.price, stl.price) AS price0,
  sum(if(sap.is_retry=1,1,0))  as people_retry_c,
 
  IF(sat.price_subtest_retry > 0, sat.price_subtest_retry, COALESCE (stl.sub_test_price,0)) AS price1,
	sum(if(sap.is_retry=1,2,0))  as people_retry2_c,
	
	IF(sat.price_subtest_2_retry > 0, sat.price_subtest_2_retry, COALESCE (stl.sub_test_price_2,0)) AS price2,
 
	 sat.people_first * sat.price
 + sat.people_subtest_retry * sat.price_subtest_retry
	+ sat.people_subtest_2_retry * sat.price_subtest_2_retry AS money2, 
	
	  sum(if(sap.is_retry=0,1,0)) * IF(sat.price > 0, sat.price, stl.price)
 + sum(if(sap.is_retry=1,1,0)) *  IF(sat.price_subtest_retry > 0, sat.price_subtest_retry, COALESCE (stl.sub_test_price,0))
	+	sum(if(sap.is_retry=1,2,0)) * IF(sat.price_subtest_2_retry > 0, sat.price_subtest_2_retry, COALESCE (stl.sub_test_price_2,0)) AS money, 
	
 sat.people_first 
 + sat.people_subtest_retry 
	+ sat.people_subtest_2_retry AS people_math, 
	 
	 sat.people_count AS people_count_old, 
	 count(distinct sap.id) AS people_count, 
	sa.id AS sa_ids,
	sa.id AS max_sa_id,
	
	 sat.money AS mc,

	sa.created AS sa_dates
FROM sdt_act_test sat

LEFT JOIN	sdt_act sa ON sat.act_id = sa.id
LEFT JOIN sdt_act_people sap ON sap.test_id = sat.id
LEFT JOIN sdt_university su ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc ON shc.id = su.head_id
LEFT JOIN sdt_head_org sho ON sho.id = shc.horg_id
LEFT JOIN sdt_test_levels stl ON sat.level_id = stl.id
WHERE sa.deleted = 0  
AND sap.deleted = 0
AND sa.created BETWEEN '" . $connection->escape($connection->format_date($from)) . " 0:0:0' AND '" . $connection->escape(
                $connection->format_date($to)
            ) . " 23:59:59'
           AND sa.state IN (" . $statest . ")  

AND sa.test_level_type_id = " . $level_type . "
" . $citizen_sql . "
GROUP BY sat.id
ORDER BY sa.id, sat.id



) a
--  having money = 0 or money is null or money < money2
GROUP BY d_year, a.horg_id -- , a.level_id
ORDER BY d_year,  a.horg_id -- , a.level_id;";

        //AND sap.document = '" . ActMan::DOCUMENT_NOTE . "'
//        die($sql);
        $res = $connection->query($sql);

//die(var_dump($res));
//
        $result = [];
        foreach ($res as $row) {
            if (empty($result[$row['horg_id']])) $result[$row['horg_id']] = [
                'id'=>$row['horg_id'],
                'caption'=>$row['horg_caption'],
                'years'=>[],
            ];
//            if (empty($result[$row['d_year']])) $result[$row['d_year']] = [];
//            if($result[$row['d_year']][$row['horg_id']])
            $result[$row['horg_id']]['years'][$row['d_year']] = [
                'money' => $row['money'],
                'people' => $row['people_count'],
            ];

        }

//        die(var_dump($result));

        return $result;
    }
}