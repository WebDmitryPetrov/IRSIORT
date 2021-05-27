<?php

class LoginReport extends AbstractReport
{

    public function __construct()
    {

    }

    public function execute($from, $to, $hc)
    {
        $C = Connection::getInstance();

        $head_center_condition = '';
        if (is_numeric($hc)) {
            $head_center_condition = ' and l.head_id = ' . intval($hc);
        }
        elseif($hc=='pfur'){
            $ids = HeadCenters::getStatistHCByHO(1);
            $head_center_condition = ' and l.head_id in ( ' . implode(', ',$ids).')';
        }

        $sql = 'select l.*, 
       if(su.id is not null, u.surname , concat(u.surname, \' \', u.firstname,\' \',u.fathername))
       as name, 
       if(su.id is not null, "local_center" , ut.caption) as utype, su.id as univer_id, su.name as univer,
       shc.short_name


from log_login l
left join tb_users u on u.u_id = l.user_id
left join user_type ut on ut.id = u.user_type_id
left join sdt_university su on u.univer_id = su.id
left join sdt_head_center shc on shc.id = l.head_id
where l.created_at >= \'%s 0:0:0\' and l.created_at <=\'%s 23:59:59\'  %s
order by l.created_at;
';

        $sql = vsprintf($sql, [
            $this->mysql_date($from), $this->mysql_date($to), $head_center_condition
        ]);
//echo $sql;
        return $C->query($sql);

    }


}

