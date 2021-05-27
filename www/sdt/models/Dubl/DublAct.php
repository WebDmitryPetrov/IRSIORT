<?php

class DublActList extends ArrayObject
{
    public static function getMonthList4Buh()
    {

        $conn = Connection::getInstance();
        $states = array();
        $st = [DublAct::STATE_IN_HC, DublAct::STATE_PROCESSED];
        foreach ($st as $s) {
            $states[] = "'" . $conn->escape($s) . "'";
        }

        $sql = 'SELECT
DATE_FORMAT(
if(da.accepted_date IS NOT NULL AND da.accepted_date <> \'0000-00-00 00:00:00\', da.accepted_date, da.created ),\'%m\') AS month,
DATE_FORMAT(if(da.accepted_date IS NOT NULL AND da.accepted_date <> \'0000-00-00 00:00:00\', da.accepted_date, da.created ),\'%Y\') AS year,
count(*) AS cc
FROM  ' . DublAct::TABLE . ' da

INNER JOIN   sdt_university

ON sdt_university.id = da.center_id
WHERE

  da.state  IN (' . implode(',', $states) . ')
        AND da.deleted=0
        AND da.invoice IS NOT NULL
        AND da.invoice <> ""
   
    AND
         sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
  AND sdt_university.deleted = 0
 
GROUP BY
  year,month
HAVING
  year <>  \'0000\'
ORDER BY
year ASC, month ASC';
//die($sql);
        return $conn->query($sql);
    }

    public static function get4Buh($month, $year)
    {
        $list = new self();
        $univer_sql = '';
        $st = [DublAct::STATE_IN_HC, DublAct::STATE_PROCESSED];
        $conn = Connection::getInstance();
        foreach ($st as $s) {
            $states[] = "'" . $conn->escape($s) . "'";
        }
        $univer_sql = ' and month(if(da.accepted_date is not null AND da.accepted_date <> \'0000-00-00 00:00:00\', da.accepted_date, da.created )) = ' . intval($month)
            . ' and year(if(da.accepted_date is not null AND da.accepted_date <> \'0000-00-00 00:00:00\', da.accepted_date, da.created )) = ' . intval($year);
//and	 sdt_act.deleted = 0
        $sql = 'SELECT da.*,  if(sp.name IS NOT NULL, sp.name, su.name) AS uname
				
				FROM  ' . DublAct::TABLE . ' da
			INNER JOIN	sdt_university su				ON su.id = da.center_id
			 LEFT JOIN sdt_university sp ON sp.id = su.parent_id
				INNER JOIN sdt_university_dogovor sud
				ON sud.id = da.center_dogovor_id
				WHERE
				 da.state  IN (' . implode(',', $states) . ')
                  AND da.invoice IS NOT NULL
                  AND                  da.invoice <> ""
                  	AND				   su.head_id = ' . CURRENT_HEAD_CENTER . '

				' . $univer_sql . '
				ORDER BY
				uname ASC,
				 IF(da.accepted_date IS NOT NULL AND da.accepted_date <> \'0000-00-00 00:00:00\', da.accepted_date, da.created ) ASC
				';
//        die($sql);

        $list = Connection::getInstance()->query($sql);
        foreach ($list as &$item) {
            $item = new DublAct($item);
        }

        return $list;
    }

    static public function get4LocalCenter($id, $type)
    {
        $list = new self();
        $con = Connection::getInstance();

        $sql = 'SELECT * FROM ' . DublAct::TABLE . ' WHERE
        state = \'' . $con->escape(DublAct::STATE_IN_LC) . '\'
        AND deleted=0
        AND test_level_type_id=' . $type . '
        AND center_id = ' . intval($id) . '
        ';
//      echo $sql;
        $result = $con->query($sql);
        if ($result) {
            foreach ($result as $row) {
                $list[] = new DublAct($row);
            }
        }

        return $list;
    }

    static public function getByMan($id)
    {
        $list = new self();
        $con = Connection::getInstance();


        $sql = 'SELECT da.* FROM dubl_act_man dam
        LEFT JOIN dubl_act da ON dam.act_id=da.id
        LEFT JOIN sdt_university su ON da.center_id = su.id
        WHERE da.deleted = 0
        AND su.head_id = ' . intval(CURRENT_HEAD_CENTER) . '
        AND da.state="' . DublAct::STATE_PROCESSED . '"
        AND dam.old_man_id =' . intval($id) . '
        ';
//      echo $sql;
        $result = $con->query($sql);
        if ($result) {
            foreach ($result as $row) {
                $list[] = new DublAct($row);
            }
        }

        return $list;
    }

    static public function get4HeadCenter()
    {
        $list = new self();
        $con = Connection::getInstance();

        $sql = 'SELECT da.*  FROM ' . DublAct::TABLE . '  da
        LEFT JOIN sdt_university su
      ON da.university_id = su.id
        WHERE da.state IN (' . $con->escape(DublAct::STATE_IN_HC) . ',' . $con->escape(DublAct::STATE_ON_CHECK) . ')
         AND da.deleted=0
         AND su.deleted=0
         AND su.head_id = ' . CURRENT_HEAD_CENTER . '

         ';
        $result = $con->query($sql);
        foreach ($result as $row) {
            $list[] = new DublAct($row);
        }

        return $list;
    }


    public static function getByLevel($univer_id, $level, $test_level_type_id = null)
    {
        $list = new DublActList();

        if (is_array($level)) {
            $levels = array();
            foreach ($level as $lev) {
                $levels[] = "'" . mysql_real_escape_string($lev) . "'";
            }
            $level = implode(',', $levels);
        } else $level = mysql_real_escape_string($level);

        $restrict = '';
        $C = AbstractController::getInstance();
        $restrictions = $C->getUniversityRestrictionArray();
        // var_dump($restrictions);die();
        if ($restrictions) {
            $restrict = "
             and  sdt_university.id in (" . implode(', ', $restrictions) . ') ';
        }

        $univer_sql = ' and sdt_university.id=' . intval($univer_id);


            $skip_deleted_sql = ' 	AND dubl_act.deleted = 0 ';
        

        $level_sql = '';
        if (is_numeric($test_level_type_id)) {
            $level_sql = ' and dubl_act.test_level_type_id=' . $test_level_type_id . ' ';
        }

        $sql = 'SELECT dubl_act.*
				FROM
				sdt_university
				INNER JOIN dubl_act
				ON sdt_university.id = dubl_act.center_id
				INNER JOIN sdt_university_dogovor
				ON sdt_university_dogovor.id = dubl_act.center_dogovor_id
				WHERE
				  dubl_act.state IN (' . $level . ')
	AND
				   sdt_university.head_id = ' . CURRENT_HEAD_CENTER . '
			' . $skip_deleted_sql . '
        ' . $level_sql . '
				

                ' . $univer_sql . '
                ' . $restrict . '
				ORDER BY
				 dubl_act.created DESC,
				sdt_university.name
				, sdt_university_dogovor.number
				';
//        die($sql);
        $result = mysql_query($sql) or die(mysql_error() . $sql);

        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new DublAct($row);
        }

//var_dump($list); die();
        return $list;
    }

    public static function getLocalArchive($getLocalCenterID)
    {
        $states = DublAct::getHCStates();
        $date = date('Y-m-d', strtotime('-2 month'));
        $Con = Connection::getInstance();
        $states = array_map(function ($item) use ($Con) {
            return "'" . $Con->escape($item) . "'";
        }, $states);
        $sql = 'SELECT da.id, da.state, da.created, da.center_dogovor_id, tlt.caption AS test_type,
 sum(if(dam.dubl_cert_id>0,1,0))  AS cc, count(dam.id) AS cc_total
FROM dubl_act da
LEFT JOIN dubl_act_man dam ON dam.act_id = da.id
LEFT JOIN test_level_type tlt ON tlt.id =  da.test_level_type_id
WHERE
da.state IN (' . implode(', ', $states) . ')
AND da.deleted = 0
AND da.created >= \'' . $date . '\'
AND da.center_id  = ' . $getLocalCenterID . '

GROUP BY da.id
ORDER BY da.id DESC
';
//        die($sql);
        $res = $Con->query($sql);
        return $res;

    }
}

class DublAct extends Model
{
    const TABLE = 'dubl_act';
    const STATE_IN_LC = 'in_localcenter';
    const STATE_ON_CHECK = 'on_check';
    const STATE_IN_HC = 'in_headcenter';
    const STATE_PROCESSED = 'processed';
    public $center_id;
    public $state = self::STATE_IN_LC;
    public $file_request_id;
    public $deleted = 0;
    public $created;
    public $accepted_date;
    public $comment = '';
    public $invoice;
    public $invoice_index;
    public $signing;
    public $center_dogovor_id;
    public $invoice_date;
    public $official;
    public $invoice_price;
    public $test_level_type_id;
    public $summary_table_id;
    protected $_table = self::TABLE;

    static function getHCStates()
    {
        return [
            self::STATE_IN_HC, self::STATE_ON_CHECK, self::STATE_PROCESSED
        ];
    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $con = Connection::getInstance();
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE id=\'' . $con->escape($id) . '\'';
        $result = $con->queryOne($sql);
        if (!$result) {
            return null;
        }

        return new self($result);


    }


    public function setFields()
    {
        $this->fields = array(
            'id',
            'center_id',
            'state',
            'comment',
            'created',
            'invoice',
            'invoice_index',
            'signing',
            'center_dogovor_id',
            'invoice_date',
            'official',
            'file_request_id',
            'invoice_price',
            'test_level_type_id',
            'accepted_date',
            'summary_table_id',

        );
    }

//    public function getFkFields()
//    {
//        return array(
//
//            'deleted'
//        );
//    }
    public function getFkFields()
    {
        return array(

            'summary_table_id'
        );
    }


    public function getEditFields()
    {
        return array(
            'id',
            'center_id',
            'state',
            'comment',
            'center_dogovor_id',
            'official',
            'file_request_id',
            'invoice_price',
            'invoice',
            'invoice_index',
            'invoice_date',
            'signing',
            'test_level_type_id',
            'accepted_date',
        );
    }

    public function getFileRequest()
    {
        if ($this->file_request_id) {
            return File::getByID($this->file_request_id);
        }

        return null;
    }

    public function getLocalCenter()
    {
        return University::getByID($this->center_id);
    }

    public function chechForSend()
    {
        $error = array();
        $people = self::getPeople();
        if (!count($people)) {
            $error[0] = 'добавьте людей в акт';
        } else {
            foreach ($people as $man) {
                if ($man->is_changed && empty($man->file_passport_id)) {
                    $error[0] = 'загрузите сканы паспортов';
                }
                if (empty($man->file_request_id)) {
                    $error[1] = 'загрузите сканы заявлений на дубликаты';
                }
            }
        }

        if (empty($this->center_dogovor_id)) {
            $error[2] = 'укажите договор центра тестирования';
        }
        /* if (empty($this->file_request_id))
         {
             $error[3]='загрузите зявление центра тестирования';
         }*/
        if (empty($this->official)) {
            $error[4] = 'укажите ответственного';
        }

        return $error;

    }

    /**
     * @return array|DublActManList|DublActMan[]
     */
    public function getPeople()
    {
        return DublActManList::getByDublAct($this->id);

    }

    /**
     * @return University
     */
    public function getUniversity()
    {
        return University::getByID($this->center_id);
    }

    /**
     * @return University_dogovor
     */
    public function getUniversityDogovor()
    {
        return University_dogovor::getByID($this->center_dogovor_id);
    }


    public function isSetInvoice()
    {
        if (!$this->invoice || !$this->signing) {
            return false;
        }

        return true;
    }

    public function getOldActs()
    {
        $old_acts = new Acts();
        $people = self::getPeople();

        foreach ($people as $man) {
            $old_acts[] = ActMan::getByID($man->old_man_id)->getAct();
            //var_dump($old_act->state);echo '<br>';
            //$old_acts[]= new Act($old_man);
            //$act=Act::getActState()
            //$old_acts[]=$old_act;
        }
        //die(var_dump($old_acts));
        return $old_acts;
    }

    public function getTotal()
    {
        return $this->invoice_price * count(self::getPeople());
    }

    public function getCheckDate()
    {
        if (!empty($this->accepted_date) && $this->accepted_date != '0000-00-00 00:00:00') {
            return $this->accepted_date;
        }
        return $this->created;
    }

// check_date
    public function getPrintDateAfterCheckDate(DateTime $now = null, $withTime = false)
    {

        if (is_null($now)) {
            $now = new DateTime();
        }
        $time = '';
        if ($withTime) {
            $time = date(' H:i:s');
        }
        if ($this->getUniversity()->getHeadCenter()->horg_id == 1) {

            $checkDate = new DateTime($this->accepted_date);
            if ($now >= $checkDate) {
                return $now->format('Y-m-d') . $time;
            } else {
                return $checkDate->format('Y-m-d') . $time;
            }
        }
        return $now->format('Y-m-d') . $time;
    }

    public function allow_summary_table()
    {
        if ($this->getUniversity()->getHeadCenter()->horg_id == 1 && $this->test_level_type_id == 2) return true;
        else return false;
    }

    public function isShowInArchive()
    {

        return true; //$this->state != self::STATE_PROCESSED;
    }

}