<?php

class LcNotWorking extends AbstractReport
{

    public function __construct()
    {

    }

    public function execute($from, $hc)
    {
        $C = Connection::getInstance();

        $sql = 'SELECT su.id as su_id, su.short_name as su_name , su.name as su_fname ,su.legal_address, su.created, shc.id, shc.short_name, shc.horg_id, sall.m
  FROM sdt_university su 
  LEFT join sdt_act sa ON sa.university_id = su.id  AND sa.created >= \'%s 0:0:0\'
LEFT JOIN sdt_head_center shc ON shc.id = su.head_id
  LEFT JOIN (SELECT MAX(sa.created) AS m,
                    sa.university_id AS su_id FROM sdt_act sa GROUP BY su_id) sall
      ON sall.su_id = su.id
  WHERE sa.id IS NULL AND su.deleted=0 and shc.horg_id=%d
  GROUP BY su.id;
';

        $sql = vsprintf($sql, [
             $this->mysql_date($from), intval($hc)
        ]);
//echo $sql;
        return $C->query($sql);

    }


}

