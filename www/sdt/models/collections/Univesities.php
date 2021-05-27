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
        $sql = 'SELECT su.id AS id, su.name AS caption
     , count(sdt_act.id) AS cc, su.parent_id AS parent_id,
     sum(if(sdt_act.viewed,0,1)) AS unread
FROM
  sdt_university  su
  LEFT JOIN sdt_university  sp ON sp.id = su.parent_id
INNER JOIN sdt_act
ON su.id = sdt_act.university_id
WHERE
  sdt_act.state = \'' . mysql_real_escape_string($level) . '\'

    AND
         su.head_id = ' . CURRENT_HEAD_CENTER . '
  AND sdt_act.deleted = 0
  AND su.deleted = 0

  ' . $restrict . '
GROUP BY
  su.id

HAVING cc>0
ORDER BY
  caption
, cc';

        $C = Connection::getInstance();
        $result = $C->query($sql);
//        $list=[];
        if (!$result) return $list;
        foreach ($result as $row) {


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
        $sql = 'SELECT su.id AS id, su.name AS caption
     , count(sdt_act.id) AS cc, su.deleted AS deleted,
     sum(if(sdt_act.paid,0,1)) AS unpaid, su.parent_id AS parent_id
FROM
  sdt_university su
  LEFT JOIN sdt_university sp ON sp.id = su.parent_id
INNER JOIN sdt_act
ON su.id = sdt_act.university_id
WHERE
  sdt_act.state = \'' . $level . '\'

    AND
         su.head_id = ' . CURRENT_HEAD_CENTER . '
  AND sdt_act.deleted = 0


  ' . $restrict . '
GROUP BY
  su.id

HAVING cc>0
ORDER BY
  caption
, cc';


        $C = Connection::getInstance();
        $result = $C->query($sql);
//        $list=[];
        if ($result)
            foreach ($result as $row) {
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

    public static function getByDubl($level, $test_level_type_id = null, $skipDeleted = true)
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

        $skip_deleted_sql = '';
        if ($skipDeleted) {
            $skip_deleted_sql = '  AND sdt_university.deleted=0 ';
        }

        $level_sql = '';
        if (is_numeric($test_level_type_id)) {
            $level_sql = ' and da.test_level_type_id=' . $test_level_type_id . ' ';
        }

        $sql = '
        SELECT sdt_university.*,count(da.id) AS cc
        FROM sdt_university sdt_university
        JOIN dubl_act da
        ON sdt_university.id=da.center_id
        WHERE da.state IN (' . $level . ')
       
        ' . $skip_deleted_sql . '
        
        ' . $level_sql . '

        AND da.deleted=0
        ' . $restrict . '
        AND sdt_university.head_id=' . CURRENT_HEAD_CENTER . '
        GROUP BY sdt_university.id
        ORDER BY sdt_university.name
        ';


        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $new = new stdClass();
            $new->id = $row['id'];
            $new->caption = $row['name'];
            $new->parent_id = $row['parent_id'];
            $new->count = $row['cc'];
            $new->deleted = $row['deleted'];
//            $new->unread = $row['unread'];
            $list[$row['id']] = $new;

        }

        return $list;
    }

    public static function getByLevels($level, $pfur_api = 0)
    {
        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }



        $list = new Univesities();
        $sql = 'SELECT sdt_university.id AS id, sdt_university.name AS caption
     , count(sdt_act.id) AS cc,
     sum(if(sdt_act.viewed,0,1)) AS unread
FROM
  sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
WHERE
  sdt_act.test_level_type_id = \'' . $level . '\'
	AND
  sdt_act.state IN (\'' . ACT::STATE_RECEIVED . '\',\'' . ACT::STATE_PRINT . '\',\'' . ACT::STATE_WAIT_PAYMENT . '\',\'' . ACT::STATE_CHECK . '\' )
    AND
    sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '

  AND sdt_act.deleted = 0
  AND sdt_university.deleted = 0

  ' . $restrict . '

GROUP BY
  sdt_university.id
, sdt_university.name
, sdt_act.university_id
HAVING cc>0
ORDER BY
  caption
, cc';


        $sql = "SELECT  sdt_university.id AS id, sdt_university.name AS caption,
sdt_university.parent_id AS parent_id,
 COUNT(sdt_act.id) AS cc,
 SUM(IF(sdt_act.viewed,0,1)) AS unread,
 SUM( IF(sdt_act.invoice = ''
 OR sdt_act.invoice IS  NULL  , 1,0) )  AS no_invoice,
 SUM( IF(sdt_act.invoice <> ''
AND sdt_act.invoice IS NOT NULL AND sdt_act.paid = 0  , 1,0) )  AS wait_paid,
sum(ss.o) AS no_blanks,
sum(if(
sdt_act.invoice <> ''
 AND sdt_act.invoice IS NOT NULL
  AND sdt_act.paid = 1
  AND ss.o = 0,1,0)) AS to_arch

FROM sdt_university
INNER JOIN sdt_act ON sdt_university.id = sdt_act.university_id
LEFT JOIN (
	SELECT sa.id,
	  IF(
    SUM(IF(sap.blank_number = '' OR sap.blank_number IS NULL, 1, 0))>0,1,0) AS o

	FROM sdt_act sa
  	LEFT JOIN sdt_act_people sap ON sap.act_id = sa.id
  		LEFT JOIN sdt_university  ON sa.university_id = sdt_university.id
   WHERE sa.deleted = 0 AND sap.deleted = 0
	AND sa.state IN (" . Act::getStatesSql(Act::getReceivedStates()) . ")
	 " . $restrict . "
	AND sa.test_level_type_id = '" . $level . "'
  	GROUP BY sa.id) ss ON ss.id = sdt_act.id

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
SELECT sdt_university.id AS id, sdt_university.name AS caption
     , count(sdt_act.id) AS cc
FROM
  sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
WHERE
     sdt_act.invoice IS NOT NULL
                  AND
                  sdt_act.invoice <> ""
                  AND
   sdt_act.deleted = 0
    AND
         sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
  AND sdt_university.deleted = 0
GROUP BY
  sdt_university.id
, sdt_university.name
, sdt_act.university_id
HAVING cc>0
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
        $sql = 'SELECT * FROM sdt_university
				WHERE deleted=0
				   AND
         head_id = ' . CURRENT_HEAD_CENTER . '
				AND id IN (
				SELECT DISTINCT(university_id)
				FROM sdt_university_dogovor
				WHERE deleted=0)
				ORDER BY name';
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
        $sql = 'SELECT * FROM sdt_university WHERE deleted=0
         AND
         head_id = ' . CURRENT_HEAD_CENTER . '
         ORDER BY name';
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
        $sql = 'SELECT su.id, if(su.short_name, su.short_name,su.name) AS caption, concat_ws(\',\',group_concat(sp.id),su.id) AS ids
      FROM sdt_university su
        LEFT JOIN sdt_university sp ON sp.parent_id = su.id
        WHERE 
         su.head_id = ' . CURRENT_HEAD_CENTER . '
         GROUP BY su.id
         ORDER BY trim(caption)';
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
        $sql = 'SELECT su.id, su.name, count(c.id) AS have_child FROM sdt_university su 

        LEFT JOIN sdt_university c ON c.parent_id = su.id AND c.deleted = 0
     WHERE su.deleted=0
         AND (su.parent_id IS NULL OR su.parent_id = 0)
         AND
         su.head_id = ' . CURRENT_HEAD_CENTER . '
        
         GROUP BY su.id
          ORDER BY su.name ASC
         ';
//        die($sql);
        $C = Connection::getInstance();
        $oldList = $C->query($sql);
        $list = [];
        if (empty($oldList)) return [];
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
            $sql = 'SELECT su.id, su.name, su.parent_id FROM sdt_university su WHERE su.deleted=0
     AND
         su.head_id = ' . CURRENT_HEAD_CENTER . '
         AND su.parent_id IN (' . implode(',', $ids) . ')
         ORDER BY su.name ASC
         
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
SELECT
  u_id,surname,firstname,fathername

  FROM tb_users  tu

  WHERE

 ( tu.univer_id = 0 OR tu.univer_id IS NULL)

    AND
         tu.head_id = ' . CURRENT_HEAD_CENTER . '
  ORDER BY surname,firstname,fathername ';
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
SELECT
 tu.id,name, suu.user_id AS checked

  FROM sdt_university  tu
  LEFT JOIN sdt_univer_user suu
  ON tu.id = suu.univer_id AND suu.user_id=' . $id . '
  WHERE

  tu.deleted = 0

    AND
         tu.head_id = ' . CURRENT_HEAD_CENTER . '
        ORDER BY name;';
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
        $sqlDelete = 'DELETE FROM sdt_univer_user WHERE user_id=' . $user_id;
        mysql_query($sqlDelete);
        foreach ($univers as $univer) {
            $sqlIn = 'INSERT INTO sdt_univer_user(user_id,univer_id) VALUES (' . intval(
                    $user_id
                ) . ',' . $univer . ')';
            mysql_query($sqlIn);
        }
    }

    public static function getChildren($id)
    {


        $list = new Univesities();
        $sql = 'SELECT su.* FROM sdt_university su WHERE su.deleted = 0 AND su.parent_id = ' . intval($id) . ' ORDER BY name ASC';

        $query = Connection::getInstance()->query($sql);
        if ($query)
            foreach ($query as $item) {
                $list->append(new University($item));
            }
        return $list;

    }
}
