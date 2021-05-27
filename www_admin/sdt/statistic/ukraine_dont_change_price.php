<?php

namespace SDT\statistic;

use AbstractReport;
use Act;
use Connection;

class ukraine_dont_change_price extends AbstractReport
{
    public function execute($from, $to, $price)
    {
        $C = Connection::getInstance();


        $states = array();
        foreach (Act::getInnerStates() as $st) {
            $states[] = "'" . $st . "'";
        }

        $states = implode(', ', $states);


        /*        $sql = "
        SELECT
          sa.id,
          sa.number,
          sa.state,
          sa.created,
          shc.id as sch_id,
          su.name AS su_name,
          shc.name AS hc_name,
          SUM(sat.people_first) AS people_count,
          sat.price
        FROM sdt_act sa
          LEFT JOIN sdt_university su
            ON su.id = sa.university_id
          LEFT JOIN sdt_head_center shc
            ON shc.id = su.head_id AND shc.horg_id=1

          INNER JOIN act_metadata am
            ON am.act_id = sa.id
            AND am.special_price_group = 2
          INNER JOIN sdt_act_test sat
            ON sat.act_id = sa.id
            AND sat.level_id = 16
        WHERE sa.state IN (" . $states . ")
        and sa.created BETWEEN '" . $C->escape($C->format_date($from)) . " 0:0:0' AND '" . $C->escape(
                        $C->format_date($to)
                    ) . " 23:59:59'
        and sat.price=" . intval($price) . "
        and sa.deleted = 0
        and sat.deleted = 0
          GROUP BY sa.id
          ORDER BY shc.id, su.name, sa.created
          ";*/

        $sql = "
SELECT
  sa.id AS sa_id,
  sa.number AS number,
  sa.created,
  sa.check_date,
 su.id AS su_id,
  sa.invoice_index,
  sa.invoice,
  sa.invoice_date,
  
  su.id AS su_id,
  su.name AS su_name,
  shc.id as shc_id,
  shc.name AS hc_name,
  SUM(sat.people_first) AS people_count,
  sat.price
FROM sdt_act sa
  LEFT JOIN sdt_university su
    ON su.id = sa.university_id
  LEFT JOIN sdt_head_center shc
    ON shc.id = su.head_id AND shc.horg_id=1
 
  INNER JOIN act_metadata am
    ON am.act_id = sa.id
    AND am.special_price_group = 2
  INNER JOIN sdt_act_test sat
    ON sat.act_id = sa.id
    AND sat.level_id = 16
WHERE sa.state IN (" . $states . ")
and sa.created BETWEEN '" . $C->escape($C->format_date($from)) . " 0:0:0' AND '" . $C->escape(
                $C->format_date($to)
            ) . " 23:59:59'
and sat.price=" . intval($price) . "
and sa.deleted = 0
and sat.deleted = 0
  GROUP BY sa.id
  ORDER BY shc.id, su.name , sa.created
  ";
//die($sql);

        $res = $C->query($sql);

        $hc_list = array_map(function ($id) {
            return \HeadCenter::getByID($id);
        }, \HeadCenters::getStatistHCByHO());


        $result = array_map(function (\HeadCenter $hc) use ($res) {
            $hc_result = [
                'caption' => $hc->name,

            ];

            $hc_result['ts'] = array_reduce($res, function ($carry, $row) use ($hc) {
                if ($row['shc_id'] != $hc->id) return $carry;


                $carry[$row['sa_id']] = [
                    'caption' => $row['su_name'],
                    'created' => $row['created'],
                    'number' => $row['number'],
                    'count' => $row['people_count'],
                    'act_date' => $row['check_date'],
                    'invoice' =>  $row['invoice']?vsprintf('%s/%s от %s',
                        [$row['invoice_index'], $row['invoice'], $this->date($row['invoice_date'])]):'',
                ];

                return $carry;
            }, []);
            return $hc_result;
        }, $hc_list);

//        die(var_dump($result[0]));
        return $result;
    }
}