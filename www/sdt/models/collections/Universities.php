<?php

class Univesities extends ArrayObject
{
    protected $_table;

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    public static function getByLevel($level)
    {
        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        if ($restrictions) {
            $restrict = "
             and  su.id in (" . implode(', ', $restrictions) . ') ';
        }
        $list = new Univesities();
        $sql = 'SELECT su.id as id, su.name AS caption
     , count(sdt_act.id) AS cc, su.parent_id as parent_id,
     sum(if(sdt_act.viewed,0,1)) as unread
FROM
  sdt_university  su
  left join sdt_university  sp on sp.id = su.parent_id
INNER JOIN sdt_act
ON su.id = sdt_act.university_id
WHERE
  sdt_act.state = \'' . mysql_real_escape_string($level) . '\'

    and
         su.head_id = ' . CURRENT_HEAD_CENTER . '
  AND sdt_act.deleted = 0
  AND su.deleted = 0

  ' . $restrict . '
GROUP BY
  su.id

having cc>0
ORDER BY
  caption
, cc';

$C = Connection::getInstance();
        $result = $C->query($sql);
//        $list=[];
        if(!$result) return $list;
        foreach($result as $row){


            $new = new stdClass();
            $new->id = $row['id'];
            $new->caption = $row['caption'];
            $new->count = $row['cc'];
            $new->unread = $row['unread'];
            $new->parent_id = $row['parent_id'];
            $list[$row['id']] = $new;

        }

        return $list;
    }

    public static function getBy4Archive()
    {
        $level = Act::STATE_ARCHIVE;
        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        if ($restrictions) {
            $restrict = "
             and  su.id in (" . implode(', ', $restrictions) . ') ';
        }
        $list = new Univesities();
        $sql = 'SELECT su.id as id, su.name AS caption
     , count(sdt_act.id) AS cc, su.deleted as deleted,
     sum(if(sdt_act.paid,0,1)) as unpaid, su.parent_id as parent_id
FROM
  sdt_university su
  left join sdt_university sp on sp.id = su.parent_id
INNER JOIN sdt_act
ON su.id = sdt_act.university_id
WHERE
  sdt_act.state = \'' . $level . '\'

    and
         su.head_id = ' . CURRENT_HEAD_CENTER . '
  AND sdt_act.deleted = 0


  ' . $restrict . '
GROUP BY
  su.id

having cc>0
ORDER BY
  caption
, cc';


        $C = Connection::getInstance();
        $result = $C->query($sql);
//        $list=[];
        foreach($result as $row){
            $new = new stdClass();
            $new->id = $row['id'];
            $new->caption = $row['caption'];
            $new->count = $row['cc'];
            $new->unpaid = $row['unpaid'];
            $new->deleted = $row['deleted'];
            $new->parent_id = $row['parent_id'];
            $list[$row['id']] = $new;

        }

        return $list;
    }

    public static function getByDubl($level, $test_level_type_id = 1)
    {
        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();


        if (is_array($level)) {
            $levels = array();
            foreach ($level as $lev) {
                $levels[] = "'" . mysql_real_escape_string($lev) . "'";
            }
            $level = implode(',', $levels);
        } else $level = mysql_real_escape_string($level);


        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }
        $list = new Univesities();
        /* $sql = 'SELECT sdt_university.id as id, sdt_university.name AS caption
      , count(sdt_act.id) AS cc,
      sum(if(sdt_act.viewed,0,1)) as unread
 FROM
   sdt_university
 INNER JOIN sdt_act
 ON sdt_university.id = sdt_act.university_id
 WHERE
   sdt_act.state = \'' . mysql_real_escape_string($level) . '\'

     and
          sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
   AND sdt_act.deleted = 0
   AND sdt_university.deleted = 0

   ' . $restrict . '
 GROUP BY
   sdt_university.id
 , sdt_university.name
 , sdt_act.university_id
 having cc>0
 ORDER BY
   caption
 , cc';*/

        $sql = '
        select sdt_university.*,count(da.id) AS cc
        from sdt_university sdt_university
        join dubl_act da
        on sdt_university.id=da.center_id
        where da.state in (' . $level . ')
        and sdt_university.deleted=0

        and da.test_level_type_id=' . $test_level_type_id . '

        and da.deleted=0
        ' . $restrict . '
        and sdt_university.head_id=' . CURRENT_HEAD_CENTER . '
        group by sdt_university.id
        order by sdt_university.name
        ';


        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $new = new stdClass();
            $new->id = $row['id'];
            $new->caption = $row['name'];
            $new->count = $row['cc'];
//            $new->unread = $row['unread'];
            $list[] = $new;

        }

        return $list;
    }

    public static function getByLevels($level)
    {
        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }
        $list = new Univesities();
        $sql = 'SELECT sdt_university.id as id, sdt_university.name AS caption
     , count(sdt_act.id) AS cc,
     sum(if(sdt_act.viewed,0,1)) as unread
FROM
  sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
WHERE
  sdt_act.test_level_type_id = \'' . $level . '\'
	and
  sdt_act.state in (\'' . ACT::STATE_RECEIVED . '\',\'' . ACT::STATE_PRINT . '\',\'' . ACT::STATE_WAIT_PAYMENT . '\',\'' . ACT::STATE_CHECK . '\' )
    and
    sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '

  AND sdt_act.deleted = 0
  AND sdt_university.deleted = 0

  ' . $restrict . '

GROUP BY
  sdt_university.id
, sdt_university.name
, sdt_act.university_id
having cc>0
ORDER BY
  caption
, cc';


        $sql = "SELECT  sdt_university.id AS id, sdt_university.name AS caption,
sdt_university.parent_id as parent_id,
 COUNT(sdt_act.id) AS cc,
 SUM(IF(sdt_act.viewed,0,1)) AS unread,
 SUM( IF(sdt_act.invoice = ''
 or sdt_act.invoice is  null  , 1,0) )  AS no_invoice,
 SUM( IF(sdt_act.invoice <> ''
and sdt_act.invoice is not null and sdt_act.paid = 0  , 1,0) )  AS wait_paid,
sum(ss.o) as no_blanks,
sum(if(
sdt_act.invoice <> ''
 and sdt_act.invoice is not null
  and sdt_act.paid = 1
  and ss.o = 0,1,0)) as to_arch

FROM sdt_university
INNER JOIN sdt_act ON sdt_university.id = sdt_act.university_id
left join (
	select sa.id,
	  IF(
    SUM(IF(sap.blank_number = '' OR sap.blank_number IS NULL, 1, 0))>0,1,0) AS o

	from sdt_act sa
  	left join sdt_act_people sap on sap.act_id = sa.id
  		left join sdt_university  on sa.university_id = sdt_university.id
   where sa.deleted = 0 and sap.deleted = 0
	and sa.state in (" . Act::getStatesSql(Act::getReceivedStates()) . ")
	 " . $restrict . "
	and sa.test_level_type_id = '" . $level . "'
  	group by sa.id) ss on ss.id = sdt_act.id

WHERE sdt_act.test_level_type_id = '" . $level . "'
 AND sdt_act.state IN (" . Act::getStatesSql(Act::getReceivedStates()) . ")
 AND sdt_university.head_id =  " . CURRENT_HEAD_CENTER . "
 AND sdt_act.deleted = 0 AND sdt_university.deleted = 0
  " . $restrict . "
 GROUP BY sdt_university.id, sdt_university.name, sdt_act.university_id

ORDER BY caption";
//die ($sql);

        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $new = new stdClass();
            $new->id = $row['id'];
            $new->caption = $row['caption'];
            $new->count = $row['cc'];
            $new->unread = $row['unread'];
            $new->no_invoice = $row['no_invoice'];
            $new->wait_paid = $row['wait_paid'];
            $new->no_blanks = $row['no_blanks'];
            $new->to_arch = $row['to_arch'];
            $new->parent_id = $row['parent_id'];
            $list[] = $new;

        }

        return $list;
    }

    public static function get4Buh()
    {
        $list = new Univesities();
        $sql = '
SELECT sdt_university.id as id, sdt_university.name AS caption
     , count(sdt_act.id) AS cc
FROM
  sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
WHERE
     sdt_act.invoice is not NULL
                  and
                  sdt_act.invoice <> ""
                  and
   sdt_act.deleted = 0
    and
         sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
  AND sdt_university.deleted = 0
GROUP BY
  sdt_university.id
, sdt_university.name
, sdt_act.university_id
having cc>0
ORDER BY
  caption
, cc';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $new = new stdClass();
            $new->id = $row['id'];
            $new->caption = $row['caption'];
            $new->count = $row['cc'];
            $list[] = $new;

        }

        return $list;
    }

    static public function get4Act()
    {
        $list = new Univesities();
        $sql = 'select * from sdt_university
				where deleted=0
				   and
         head_id = ' . CURRENT_HEAD_CENTER . '
				and id in (
				select distinct(university_id)
				from sdt_university_dogovor
				where deleted=0)
				order by name';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new University($row);
        }

        return $list;
    }

    public static function get4Admin()
    {
        return self::getAll();
    }

    static public function getAll($skipRights = false)
    {
        $list = new Univesities();
        $sql = 'select * from sdt_university where deleted=0
         and
         head_id = ' . CURRENT_HEAD_CENTER . '
         order by name';
//        die($sql);
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new University($row, $skipRights);
        }

        return $list;
    }

    static public function getPartnersList()
    {
        $list = new Univesities();
        $sql = 'select su.id, if(su.short_name, su.short_name,su.name) as caption, concat_ws(\',\',group_concat(sp.id),su.id) as ids
      from sdt_university su
        left join sdt_university sp on sp.parent_id = su.id
        where 
         su.head_id = ' . CURRENT_HEAD_CENTER . '
         group by su.id
         order by trim(caption)';
//        die($sql);
        $C = Connection::getInstance();
        $result = $C->query($sql);
        $list = [];
        foreach ($result as $item)
            $list[$item['id']] = $item;

        return $list;
    }


    static public function getDictList()
    {
//        $list = new Univesities();
        $sql = 'select su.id, su.name, count(c.id) as have_child from sdt_university su 

        left join sdt_university c on c.parent_id = su.id and c.deleted = 0
     where su.deleted=0
         and (su.parent_id is null or su.parent_id = 0)
         and
         su.head_id = ' . CURRENT_HEAD_CENTER . '
        
         group by su.id
          order by su.name asc
         ';
//        die($sql);
        $C = Connection::getInstance();
        $oldList = $C->query($sql);
        $list = [];
        foreach ($oldList as $item) {
            $list[$item['id']] = $item;
            $list[$item['id']]['children'] = [];
        }
        unset($oldList);
        $haveChildren = array_filter($list, function ($item) {
            return $item['have_child'];
        });

        if (count($haveChildren)) {
            $ids = array_column($haveChildren, 'id');
            $sql = 'select su.id, su.name, su.parent_id from sdt_university su where su.deleted=0
     and
         su.head_id = ' . CURRENT_HEAD_CENTER . '
         and su.parent_id in (' . implode(',', $ids) . ')
         order by su.name asc
         
         ';
//            die($sql);
            $childList = $C->query($sql);
//            var_dump($childList);die;
            if ($childList)
                foreach ($childList as $c) {
                    $list[$c['parent_id']]['children'][] = $c;
                }
        }

        return $list;
    }

    public static function getUsers()
    {
        $sql = '
select
  u_id,surname,firstname,fathername

  from tb_users  tu

  WHERE

 ( tu.univer_id = 0 or tu.univer_id is NULL)

    and
         tu.head_id = ' . CURRENT_HEAD_CENTER . '
  order by surname,firstname,fathername ';
//        die($sql);
        $res = mysql_query($sql);
        $result = array();
        while ($row = mysql_fetch_assoc($res)) {
            $result[] = array(
                'id' => $row['u_id'],
                'caption' => $row['surname'] . ' ' . $row['firstname'] . ' ' . $row['fathername'],

            );
        }

        return $result;
    }

    public static function getByUser($id)
    {
        $sql = '
select
 tu.id,name, suu.user_id as checked

  from sdt_university  tu
  LEFT JOIN sdt_univer_user suu
  ON tu.id = suu.univer_id AND suu.user_id=' . $id . '
  WHERE

  tu.deleted = 0

    and
         tu.head_id = ' . CURRENT_HEAD_CENTER . '
        order by name;';
        $res = mysql_query($sql) or die(mysql_error());
        $result = array();
        while ($row = mysql_fetch_assoc($res)) {
            $result[] = array(
                'id' => $row['id'],
                'caption' => $row['name'],
                'checked' => intval($row['checked']),

            );
        }

        return $result;
    }

    public static function saveUserRight($user_id, $univers)
    {
        $sqlDelete = 'delete from sdt_univer_user where user_id=' . $user_id;
        mysql_query($sqlDelete);
        foreach ($univers as $univer) {
            $sqlIn = 'insert into sdt_univer_user(user_id,univer_id) values (' . intval(
                    $user_id
                ) . ',' . $univer . ')';
            mysql_query($sqlIn);
        }
    }

    public static function getChildren($id)
    {


        $list = new Univesities();
        $sql = 'select su.* from sdt_university su where su.deleted = 0 and su.parent_id = ' . intval($id) . ' order by name asc';

        $query = Connection::getInstance()->query($sql);
        if ($query)
            foreach ($query as $item) {
                $list->append(new University($item));
            }
        return $list;

    }
}
