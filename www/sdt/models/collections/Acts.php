<?php

class Acts extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function ReportNotInsertedAct($type)
    {


        $restrict = '';
        $conn = Connection::getInstance();
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
// var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }
        $st = Act::getReceivedStates();
        foreach ($st as $s) {
            $states[] = "'" . $conn->escape($s) . "'";
        }
        $result = array();
        $sql = ' SELECT
sa.id,
sa.date_received,
sdt_university.name

FROM sdt_act sa
LEFT JOIN sdt_act_people sap
ON sap.act_id = sa.id
LEFT JOIN sdt_university
ON sa.university_id = sdt_university.id
WHERE sa.deleted = 0
AND sap.deleted = 0
AND sa.test_level_type_id = ' . intval($type) . '

AND sa.state IN (' . implode(', ', $states) . ')
AND sdt_university.head_id=' . intval(CURRENT_HEAD_CENTER) . '
' . $restrict . '
AND sdt_university.deleted = 0

AND (sap.blank_number = \'\' OR sap.blank_number IS NULL)
GROUP BY sa.id
ORDER BY sa.date_received';
//var_dump($sql);
//die;
        $result = $conn->query($sql);
//        var_dump($result);
//AND sa.test_level_type_id = '2'
        return $result;
    }

    static public function getAll()
    {
        $list = new Acts();
        $sql = 'SELECT sdt_act.*
FROM
sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.deleted = 0
AND
sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
ORDER BY
sdt_act.created DESC,
sdt_university.name
, sdt_university_dogovor.number
, sdt_act.testing_date';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }


    static public function getPaid($univer_id = false)
    {
        $list = new Acts();
        $univer_sql = '';
        if ($univer_id) {
            $univer_sql = ' and sdt_university.id=' . intval($univer_id);
        }
        $sql = 'SELECT sdt_act.*
FROM
sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.paid = 1
AND
sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
AND sdt_act.deleted = 0
' . $univer_sql . '
ORDER BY
sdt_act.created,
sdt_university.name
, sdt_university_dogovor.number
, sdt_act.testing_date';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }

    static public function getMinAddDate()
    {
        $sql = 'SELECT date_format(min(created),\'%Y-%m-%d\') FROM sdt_act WHERE created>\'0000-00-00\'';

        return mysql_result(mysql_query($sql), 0, 0);
    }

    static public function getMaxAddDate()
    {
        $sql = 'SELECT date_format(max(created),\'%Y-%m-%d\') FROM sdt_act WHERE created>\'0000-00-00\'';

        return mysql_result(mysql_query($sql), 0, 0);
    }

    static public function getMinTestDate()
    {
        $sql = 'SELECT date_format(min(testing_date),\'%Y-%m-%d\') FROM sdt_act WHERE testing_date>\'0000-00-00\'';

        return mysql_result(mysql_query($sql), 0, 0);
    }

    static public function getMaxTestDate()
    {
        $sql = 'SELECT date_format(max(testing_date),\'%Y-%m-%d\') FROM sdt_act WHERE testing_date>\'0000-00-00\'';

        return mysql_result(mysql_query($sql), 0, 0);
    }

    public static function Search($params, $restrict_id = false)
    {
//$offset=(int)$params['offset'];
//if ($offset < 0)
        $offset = 0;
        $restrict = "";
//        if ($restrict_id) {
//            $restrict = "
//             and (sdt_university.owner_id is null
//  or sdt_university.owner_id=0
//            or sdt_university.owner_id='" . mysql_real_escape_string($restrict_id) . "') ";
//        }
        $states = Act::getStatesSql(Act::getInnerStates());
        $sql = 'SELECT SQL_CALC_FOUND_ROWS sdt_act.id
FROM
sdt_act
INNER JOIN sdt_act_test
ON sdt_act.id = sdt_act_test.act_id
INNER JOIN sdt_university
ON sdt_university.id = sdt_act.university_id

WHERE sdt_act.deleted = 0 AND

sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
AND
sdt_act.state IN (' . $states . ') AND
';
        $Where = array();
        if (!empty($params['act_id']) && is_numeric($params['act_id'])) {
            $Where[] = "  sdt_act.id = " . $params['act_id'];

        }
        $Where[] = "sdt_act.created>='" . $params['minAddDate'] . " 00:00:00'";
        $Where[] = "sdt_act.created<='" . $params['maxAddDate'] . " 23:59:59'";
        $Where[] = "sdt_act.testing_date>='" . $params['minTestDate'] . " 00:00:00'";
        $Where[] = "sdt_act.testing_date<='" . $params['maxTestDate'] . " 23:59:59'";
        if (isset($params['level']) && is_numeric($params['level']) && $params['level'] > 0) {
            $Where[] = "sdt_act_test.level_id='" . $params['level'] . "'";
        }
        if (isset($params['org_ids'])) {
            $Where[] = "sdt_act.university_id in ('" . $params['org_ids'] . "')";
        } elseif (isset($params['organization']) && is_numeric($params['organization']) && $params['organization'] > 0) {
            $Where[] = "sdt_act.university_id='" . $params['organization'] . "'";
        }
//   $Where[]="sdt_act.state='".$params['state']."'";
        $sql .= implode(' and ', $Where) . $restrict . ' group by sdt_act.id limit 20 offset ' . $offset;
        $result = mysql_query($sql);


        $sql = "Select FOUND_ROWS() as `rows`";
        $count_result = mysql_query($sql);
        $rows_count = mysql_result($count_result, 0);


        $return = array();
        if (!mysql_num_rows($result)) {
            return null;
        }
        while ($row = mysql_fetch_assoc($result)) {
            $return[] = Act::getByID($row['id']);
        }

// return array('result'=>$return,'rows_count'=>$rows_count);
        return $return;
    }

    public static function getByLevels($univer_id, $level)
    {
        $list = new Acts();


        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
// var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }

        $univer_sql = ' and sdt_university.id=' . intval($univer_id);

        $sql = 'SELECT sdt_act.*
FROM
sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.test_level_type_id = \'' . $level . '\'
AND
sdt_act.state IN (\'' . ACT::STATE_RECEIVED . '\',\'' . ACT::STATE_PRINT . '\',\'' . ACT::STATE_WAIT_PAYMENT . '\',\'' . ACT::STATE_CHECK . '\' )
AND
sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
AND sdt_act.deleted = 0
' . $univer_sql . '
' . $restrict . '
ORDER BY
sdt_act.created DESC,
sdt_university.name
, sdt_university_dogovor.number
, sdt_act.testing_date';
        $result = mysql_query($sql) or die(mysql_error() . $sql);

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

//var_dump($list); die();
        return $list;
    }

    public static function getMonthList4Buh()
    {

        $conn = Connection::getInstance();
        $states = array();
        $st = Act::getInnerStates();
        foreach ($st as $s) {
            $states[] = "'" . $conn->escape($s) . "'";
        }

        $sql = 'SELECT
DATE_FORMAT(sdt_act.check_date,\'%m\') AS month,
DATE_FORMAT(sdt_act.check_date,\'%Y\') AS year,
count(*) AS cc
FROM sdt_act

INNER JOIN   sdt_university

ON sdt_university.id = sdt_act.university_id
WHERE

sdt_act.state IN (' . implode(', ', $states) . ')
AND
sdt_act.invoice IS NOT NULL
AND
sdt_act.invoice <> ""

AND   sdt_act.check_date <> "0000-00-00 00:00:00"
AND   sdt_act.check_date IS NOT NULL
AND
sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '

GROUP BY
year,month

ORDER BY
year ASC, month ASC';

        return $conn->query($sql);
    }
    public static function getReestrMonthList4Buh()
    {

        $conn = Connection::getInstance();
        $states = array();
        $st = Act::getInnerStates();
        foreach ($st as $s) {
            $states[] = "'" . $conn->escape($s) . "'";
        }

        $sql = 'SELECT
DATE_FORMAT(sdt_act.check_date,\'%m\') AS month,
DATE_FORMAT(sdt_act.check_date,\'%Y\') AS year,
count(*) AS cc
FROM sdt_act

INNER JOIN   sdt_university

ON sdt_university.id = sdt_act.university_id
WHERE

sdt_act.state IN (' . implode(', ', $states) . ')


AND   sdt_act.check_date <> "0000-00-00 00:00:00"
AND   sdt_act.check_date IS NOT NULL
AND
sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '

GROUP BY
year,month

ORDER BY
year ASC, month ASC';

        return $conn->query($sql);
    }

    public static function get4Buh($month, $year, array $q = array())
    {
        $list = new Acts();
        $univer_sql = '';
        $st = Act::getInnerStates();
        $states = array();
        foreach ($st as $s) {
            $states[] = "'" . Connection::getInstance()->escape($s) . "'";
        }
        $univer_sql = ' and month(sdt_act.check_date) = ' . intval($month)
            . ' and year(sdt_act.check_date) = ' . intval($year);
//and	 sdt_act.deleted = 0

        if (!empty($q['minCheckDate'])) {
            $univer_sql .= sprintf(' and sdt_act.invoice_date >= \'%s 0:0:0\'', $q['minCheckDate']);
        }

        if (!empty($q['maxCheckDate'])) {
            $univer_sql .= sprintf(' and sdt_act.invoice_date <= \'%s 23:59:59\'', $q['maxCheckDate']);
        }

        if (!empty($q['minActDate'])) {
            $univer_sql .= sprintf(' and sdt_act.check_date >= \'%s 0:0:0\'', $q['minActDate']);
        }

        if (!empty($q['maxActDate'])) {
            $univer_sql .= sprintf(' and sdt_act.check_date <= \'%s 23:59:59\'', $q['maxActDate']);
        }

        if (!empty($q['minTestDate'])) {
            $univer_sql .= sprintf(' and sdt_act.testing_date >= \'%s 0:0:0\'', $q['minTestDate']);
        }

        if (!empty($q['maxTestDate'])) {
            $univer_sql .= sprintf(' and sdt_act.testing_date <= \'%s 23:59:59\'', $q['maxTestDate']);
        }

        $sql = 'SELECT sdt_act.*, if(sp.name IS NOT NULL, sp.name, su.name) AS uname
FROM sdt_act
INNER JOIN  sdt_university su ON su.id = sdt_act.university_id
LEFT JOIN sdt_university sp ON sp.id = su.parent_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.state IN (' . implode(', ', $states) . ')
AND  sdt_act.invoice IS NOT NULL
AND
sdt_act.invoice <> ""
AND				   su.head_id = ' . CURRENT_HEAD_CENTER . '

' . $univer_sql . '
ORDER BY
uname ASC,
sdt_act.check_date ASC
';
//        die($sql);

        $list = Connection::getInstance()->query($sql);
        if ($list)
            foreach ($list as &$item) {
                $item = new Act($item);
            }

        return $list;
    }
    public static function getReestr4Buh($month, $year, array $q = array())
    {
        $list = new Acts();
        $univer_sql = '';
        $st = Act::getInnerStates();
        $states = array();
        foreach ($st as $s) {
            $states[] = "'" . Connection::getInstance()->escape($s) . "'";
        }
        $univer_sql = ' and month(sdt_act.check_date) = ' . intval($month)
            . ' and year(sdt_act.check_date) = ' . intval($year);
//and	 sdt_act.deleted = 0

        if (!empty($q['minCheckDate'])) {
            $univer_sql .= sprintf(' and sdt_act.invoice_date >= \'%s 0:0:0\'', $q['minCheckDate']);
        }

        if (!empty($q['maxCheckDate'])) {
            $univer_sql .= sprintf(' and sdt_act.invoice_date <= \'%s 23:59:59\'', $q['maxCheckDate']);
        }

        if (!empty($q['minActDate'])) {
            $univer_sql .= sprintf(' and sdt_act.check_date >= \'%s 0:0:0\'', $q['minActDate']);
        }

        if (!empty($q['maxActDate'])) {
            $univer_sql .= sprintf(' and sdt_act.check_date <= \'%s 23:59:59\'', $q['maxActDate']);
        }

        if (!empty($q['minTestDate'])) {
            $univer_sql .= sprintf(' and sdt_act.testing_date >= \'%s 0:0:0\'', $q['minTestDate']);
        }

        if (!empty($q['maxTestDate'])) {
            $univer_sql .= sprintf(' and sdt_act.testing_date <= \'%s 23:59:59\'', $q['maxTestDate']);
        }

        $sql = 'SELECT sdt_act.*, if(sp.name IS NOT NULL, sp.name, su.name) AS uname
FROM sdt_act
INNER JOIN  sdt_university su ON su.id = sdt_act.university_id
LEFT JOIN sdt_university sp ON sp.id = su.parent_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.state IN (' . implode(', ', $states) . ')

AND				   su.head_id = ' . CURRENT_HEAD_CENTER . '

' . $univer_sql . '
ORDER BY
uname ASC,
sdt_act.check_date ASC
';
//        die($sql);

        $list = Connection::getInstance()->query($sql);
        if ($list)
            foreach ($list as &$item) {
                $item = new Act($item);
            }

        return $list;
    }

    public static function get4BuhDates($month, $year)
    {

        $univer_sql = '';
        $st = Act::getInnerStates();
        $states = array();
        foreach ($st as $s) {
            $states[] = "'" . Connection::getInstance()->escape($s) . "'";
        }
        $univer_sql = ' and month(sdt_act.check_date) = ' . intval($month)
            . ' and year(sdt_act.check_date) = ' . intval($year);
//and	 sdt_act.deleted = 0
        $sql = 'SELECT 
    min(sdt_act.check_date) AS min_created, 
    max(sdt_act.check_date) AS max_created,
 
    min(sdt_act.testing_date) AS min_test, 
    max(sdt_act.testing_date) AS max_test,
   
    min(sdt_act.invoice_date) AS min_invoice, 
    max(sdt_act.invoice_date) AS max_invoice
    
FROM sdt_act
INNER JOIN  sdt_university su ON su.id = sdt_act.university_id
LEFT JOIN sdt_university sp ON sp.id = su.parent_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.state IN (' . implode(', ', $states) . ')
AND  sdt_act.invoice IS NOT NULL
AND
sdt_act.invoice <> ""
AND				   su.head_id = ' . CURRENT_HEAD_CENTER . '

' . $univer_sql ;


        return Connection::getInstance()->queryOne($sql);

    }
    public static function getReestr4BuhDates($month, $year)
    {

        $univer_sql = '';
        $st = Act::getInnerStates();
        $states = array();
        foreach ($st as $s) {
            $states[] = "'" . Connection::getInstance()->escape($s) . "'";
        }
        $univer_sql = ' and month(sdt_act.check_date) = ' . intval($month)
            . ' and year(sdt_act.check_date) = ' . intval($year);
//and	 sdt_act.deleted = 0
        $sql = 'SELECT 
    min(sdt_act.check_date) AS min_created, 
    max(sdt_act.check_date) AS max_created,
 
    min(sdt_act.testing_date) AS min_test, 
    max(sdt_act.testing_date) AS max_test,
   
    min(sdt_act.invoice_date) AS min_invoice, 
    max(sdt_act.invoice_date) AS max_invoice
    
FROM sdt_act
INNER JOIN  sdt_university su ON su.id = sdt_act.university_id
LEFT JOIN sdt_university sp ON sp.id = su.parent_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.state IN (' . implode(', ', $states) . ')

AND				   su.head_id = ' . CURRENT_HEAD_CENTER . '

' . $univer_sql ;


        return Connection::getInstance()->queryOne($sql);

    }

    public static function getListByLevel4Head($level)
    {
        $list = new Acts();


        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
// var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }


        $sql = 'SELECT sdt_act.*
FROM sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.state=\'' . mysql_real_escape_string($level) . '\'
AND sdt_university.deleted=0
AND sdt_act.deleted = 0
' . $restrict . '
ORDER BY
sdt_university.name,
sdt_act.last_state_update,
sdt_act.created DESC

';
        $result = mysql_query($sql) or die(mysql_error() . $sql);

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }

    public static function getListRework()
    {
        $list = new Acts();
        $level = 'init';

        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
// var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }


        $sql = 'SELECT sdt_act.*
FROM sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.state=\'' . mysql_real_escape_string($level) . '\'
AND
is_changed_checker = 1
AND sdt_university.deleted = 0
AND sdt_act.deleted = 0
' . $restrict . '
ORDER BY
sdt_university.name,
sdt_act.last_state_update,
sdt_act.created DESC';
        $result = mysql_query($sql) or die(mysql_error() . $sql);

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

        return $list;
    }


    public static function getOnCheck($uid)
    {
        $univers = self::getByLevel($uid, Act::STATE_SEND);
        foreach ($univers as $un) {

        }

        return $univers;
    }

    /**
     * @param $univer_id
     * @param $level
     * @return Acts|Act[]
     */
    public static function getByLevel($univer_id, $level)
    {
        $list = new Acts();


        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
// var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }

        $univer_sql = ' and sdt_university.id=' . intval($univer_id);

        $sql = 'SELECT sdt_act.*
FROM
sdt_university
INNER JOIN sdt_act
ON sdt_university.id = sdt_act.university_id
INNER JOIN sdt_university_dogovor
ON sdt_university_dogovor.id = sdt_act.university_dogovor_id
WHERE
sdt_act.state=\'' . mysql_real_escape_string($level) . '\'
AND
sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
AND sdt_act.deleted = 0
' . $univer_sql . '
' . $restrict . '
ORDER BY
sdt_act.created DESC,
sdt_university.name
, sdt_university_dogovor.number
, sdt_act.testing_date';
        $result = mysql_query($sql) or die(mysql_error() . $sql);

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new Act($row);
        }

//var_dump($list); die();
        return $list;
    }



}
