<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 04.07.2016
 * Time: 11:09
 */
class StatistFullGcWork extends AbstractReport
{
    public function execute($from, $to)
    {
        $connection = Connection::getInstance();
        $sql = "SELECT
 sdt_head_center.name, COUNT(DISTINCT sdt_act.id) AS acts,
  SUM(sdt_act.amount_contributions) AS contributions,
   SUM(IF(LENGTH(sdt_act.invoice) AND (sdt_act.invoice IS NOT NULL), 1, 0)) AS invoices,
	 SUM(IF(LENGTH(sdt_act.invoice) AND (sdt_act.invoice IS NOT NULL),
	  sdt_act.amount_contributions, 0)) AS invoice_contribution,
	   SUM(IF(LENGTH(sdt_act.invoice) AND (sdt_act.invoice IS NOT NULL), pc, 0)) AS invoice_people,
		SUM(sdt_act.paid) AS paid,
		 SUM(IF(sdt_act.paid, sdt_act.amount_contributions, 0)) AS paid_contributions, SUM(pc) AS people,
		 1 AS is_head,
		 sdt_head_center.id AS head_id
FROM sdt_act
INNER JOIN sdt_university ON sdt_act.university_id = sdt_university.id
INNER JOIN sdt_head_center ON sdt_university.head_id = sdt_head_center.id
LEFT JOIN (
SELECT SUM(sat.people_count) AS pc,
sat.act_id
FROM sdt_act_test sat
WHERE sat.deleted=0
GROUP BY sat.act_id) sat ON sat.act_id = sdt_act.id
WHERE sdt_act.deleted = 0 AND sdt_university.deleted = 0
AND sdt_act.state IN ('received','wait_payment','print','archive')
 AND sdt_act.created >= '" . $connection->format_date($from) . " 0:0:0'
 AND sdt_act.created <= '" . $connection->format_date($to) . " 23:59:59'
GROUP BY sdt_head_center.id
UNION ALL
SELECT
 sdt_university.name, COUNT(DISTINCT sdt_act.id) AS acts,
  SUM(sdt_act.amount_contributions) AS contributions,
   SUM(IF(LENGTH(sdt_act.invoice) AND (sdt_act.invoice IS NOT NULL), 1, 0)) AS invoices,
	 SUM(IF(LENGTH(sdt_act.invoice) AND (sdt_act.invoice IS NOT NULL),
	  sdt_act.amount_contributions, 0)) AS invoice_contribution,
	   SUM(IF(LENGTH(sdt_act.invoice) AND (sdt_act.invoice IS NOT NULL), pc, 0)) AS invoice_people,
		SUM(sdt_act.paid) AS paid,
		 SUM(IF(sdt_act.paid, sdt_act.amount_contributions, 0)) AS paid_contributions, SUM(pc) AS people,
		 0 AS is_head,
		 sdt_head_center.id AS head_id
FROM sdt_act
INNER JOIN sdt_university ON sdt_act.university_id = sdt_university.id
INNER JOIN sdt_head_center ON sdt_university.head_id = sdt_head_center.id
LEFT JOIN (
SELECT SUM(sat.people_count) AS pc,
sat.act_id
FROM sdt_act_test sat
WHERE sat.deleted=0
GROUP BY sat.act_id) sat ON sat.act_id = sdt_act.id
WHERE sdt_act.deleted = 0
 AND sdt_university.is_head=1
 AND sdt_university.deleted = 0
 AND sdt_act.state IN ('received','wait_payment','print','archive')
 AND sdt_act.created >= '" . $connection->format_date($from) . " 0:0:0'
 AND sdt_act.created <= '" . $connection->format_date($to) . " 23:59:59'
GROUP BY sdt_university.id




ORDER BY head_id";
//echo $sql;
        return $connection->query($sql);
    }
}