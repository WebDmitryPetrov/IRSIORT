<?php

namespace SDT\statistic;

use AbstractReport;

use Connection;

class lc_by_dogovor_date extends AbstractReport
{
    public function execute($date)
    {
        $C = Connection::getInstance();






        $sql = "
SELECT su.name, su.legal_address   FROM sdt_university su
  LEFT JOIN sdt_university_dogovor sud ON sud.university_id = su.id
  LEFT JOIN sdt_head_center shc ON shc.id = su.head_id
  WHERE  sud.date <= \"".$this->mysql_date($date)." 23:59:59\" AND sud.valid_date >= \"".$this->mysql_date($date)." 00:00:00\"
  AND shc.horg_id = 1 and su.id not in (168) and (su.parent_id is null or su.parent_id = 0)
 
  GROUP BY su.id
  order by su.name
  ";
//die($sql);

        $res = $C->query($sql);


//        die(var_dump($result[0]));
        return $res;
    }
}