<?php

class ActPeople extends ArrayObject
{
    const SEARCH_LIMIT = 20;
    const SEARCH_PART_LIMIT = 50;

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new ActPeople();
        $sql = 'SELECT * FROM sdt_act_people WHERE deleted=0';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActMan($row);
        }

        return $list;
    }


    /**
     * @param $id
     *
     * @return ActPeople|ActMan[]
     */
    static public function get4Test($id)
    {
        $list = new ActPeople();
        $sql = 'SELECT * FROM sdt_act_people  WHERE test_id=' . $id . ' AND deleted=0 ORDER BY id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActMan($row);
        }

        return $list;
    }

    static public function getNotes4LC($lc_id, $interval = 30)
    {
        $dt = new DateTime('-' . $interval . ' days');
        $list = new ActPeople();
        $sql = "
        select sap.* from
        sdt_act sa
        left join sdt_act_people sap on sap.act_id = sa.id
        left join sdt_act_test sat on sap.test_id = sat.id

        where
        sap.deleted = 0 
        and sap.document = 'note'
         and sap.blank_number <> ''
          and sap.testing_date >= '" . $dt->format('Y-m-d') . "'
        and
        sat.deleted = 0
        and
        sa.deleted = 0 and sa.university_id = " . $lc_id . "

        order by sap.testing_date desc
";
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActMan($row);
        }

        return $list;
    }

    static public function Search($q, $cert, $name, $restrict_id = false)
    {
        $q = trim($q);
        $cert = trim($cert);
        $name = trim($name);
        if (empty($q) && empty($cert) && empty($name)) return array();
        $list = new ActPeople();
        $con = Connection::getInstance();
        $Where = array();
        $WhereDuplicate = array();
        $WhereAnnull = array();
        /*  if (!empty($q)) {
              $Where[] = "and
              (
              concat(sp.surname_rus, ' ', sp.name_rus, ' ',
   sp.surname_lat, ' ', sp.name_lat) LIKE '%" . $con->escape(
                  $q
              ) . "%' or
                 concat(cd.surname_rus, ' ', cd.name_rus, ' ',
   cd.surname_lat, ' ', cd.name_lat) LIKE '%" . $con->escape(
                  $q
              ) . "%'
                  ) ";
          }*/
        $certs_annul = '';
        if (!empty($q)) {
            $Where[] = "and
            (
            sp.surname_rus LIKE '" . $con->escape($q) . "%' or
            sp.surname_lat LIKE '" . $con->escape($q) . "%'

            ) ";
            $WhereAnnull[] = "and
            (
            cal.man_surname_ru LIKE '" . $con->escape($q) . "%' or
            cal.man_surname_en LIKE '" . $con->escape($q) . "%'

            ) ";
            $WhereDuplicate[] = "and
            (

            cd.surname_rus LIKE '" . $con->escape($q) . "%' or
            cd.surname_lat LIKE '" . $con->escape($q) . "%'
            ) ";
        }

        if (!empty($name)) {
            $Where[] = "and
            (
            sp.name_rus LIKE '" . $con->escape($name) . "%' or
            sp.name_lat LIKE '" . $con->escape($name) . "%'
            ) ";

            $WhereDuplicate[] = "and
            (
            cd.name_rus LIKE '" . $con->escape($name) . "%' or
            cd.name_lat LIKE '" . $con->escape($name) . "%'
            ) ";

            $WhereAnnull[] = "and
            (
            cal.man_name_ru LIKE '" . $con->escape($name) . "%' or
            cal.man_name_en LIKE '" . $con->escape($name) . "%'
            ) ";
        }

        if (!empty($cert)) {
//            $Where[] = "and (sp.document_nomer = '" . $con->escape($cert) . "'
//				or sp.blank_number = '" . $con->escape($cert) . "'
//    			or cd.certificate_number = '" . $con->escape($cert) . "'
//
//				) ";
            $WhereDuplicate[] = "
   			and cd.certificate_number = '" . $con->escape($cert) . "'
";
            $Where[] = "and (sp.document_nomer = '" . $con->escape($cert) . "'
				or sp.blank_number = '" . $con->escape($cert) . "'
    			) ";
            $WhereAnnull[] = "
   			and cal.blank_number = '" . $con->escape($cert) . "'";
        }
        if (count($WhereAnnull)) {
            $certs_annul = " UNION
(SELECT
   sp.*,
   COUNT(DISTINCT cd_count.id) AS have_duplicate
 FROM sdt_act_people sp
   LEFT JOIN sdt_act
     ON sp.act_id = sdt_act.id
   LEFT JOIN sdt_university
     ON sdt_university.id = sdt_act.university_id
   LEFT JOIN sdt_act_test
     ON sp.test_id = sdt_act_test.id
   LEFT JOIN certificate_duplicate cd_count
     ON cd_count.user_id = sp.id AND cd_count.deleted = 0
   left join certs_annul cal on cal.man_id = sp.id 
 WHERE
   sdt_act.state IN ('wait_payment', 'archive', 'received', 'check', 'print')
     
  " . implode(' ', $WhereAnnull) . "
 AND sp.deleted = 0
 AND sdt_act.deleted = 0
 

 GROUP BY sp.id limit " . self::SEARCH_PART_LIMIT . ")";
        }

        $sql = "(SELECT
  sp.*,
  COUNT(DISTINCT cd_count.id) AS have_duplicate
FROM sdt_act_people sp
  LEFT JOIN sdt_act
    ON sp.act_id = sdt_act.id
  LEFT JOIN sdt_university
    ON sdt_university.id = sdt_act.university_id
  LEFT JOIN sdt_act_test
    ON sp.test_id = sdt_act_test.id
  LEFT JOIN certificate_duplicate cd_count
    ON cd_count.user_id = sp.id AND cd_count.deleted = 0
WHERE
  sdt_act.state IN ('wait_payment', 'archive', 'received', 'check', 'print')
    
  " . implode(' ', $Where) . "
AND sp.deleted = 0
AND sdt_act.deleted = 0


GROUP BY sp.id limit  " . self::SEARCH_PART_LIMIT . ")

  $certs_annul
  

  UNION
   (SELECT
  sp.*,
 1 AS have_duplicate
FROM sdt_act_people sp
  LEFT JOIN sdt_act
    ON sp.act_id = sdt_act.id
  LEFT JOIN sdt_university
    ON sdt_university.id = sdt_act.university_id
  LEFT JOIN sdt_act_test
    ON sp.test_id = sdt_act_test.id
  inner JOIN certificate_duplicate cd
    ON cd.user_id = sp.id
WHERE
  sdt_act.state IN ('wait_payment', 'archive', 'received', 'check', 'print')
 
 " . implode(' ', $WhereDuplicate) . "
AND sp.deleted = 0
AND sdt_act.deleted = 0
AND cd.deleted = 0

GROUP BY sp.id LIMIT  " . self::SEARCH_PART_LIMIT . ")

    LIMIT  " . (self::SEARCH_PART_LIMIT * 3) . "";
//        die(($sql));
//        echo $sql;
        $result = $con->query($sql);
        if (count($result)) {
            foreach ($result as $row) {
                $actMan = new ActMan($row);

                if ($row['have_duplicate']) {
                    $actMan = CertificateDuplicate::checkForDuplicates($actMan);
                }
//                die(var_dump($row['have_duplicate']));
                if (self::isSearchGranted($actMan))
                    $list[] = $actMan;
            }
        }
//        echo $con->print_log();
//die(var_dump($list));

        return array_slice($list->getArrayCopy(), 0, self::SEARCH_LIMIT);
    }

    static public function isSearchGranted(ActMan $actMan)
    {
//        var_dump($actMan);

        $center = $actMan->getUniversity();
        $cert_head_id = $center->head_id;
        if ($cert_head_id == CURRENT_HEAD_CENTER) return true;
        $dubl_head_id = null;
        if ($actMan->duplicate) {

            /** @var CertificateDuplicate $dupl */
            $dupl = $actMan->duplicate;
            $duplCenter = $dupl->getCenter();
            $dubl_head_id = $duplCenter ? $duplCenter->head_id : null;

            if ($dubl_head_id == CURRENT_HEAD_CENTER) return true;
        }
        $current_horg = HeadCenter::getHorgID(CURRENT_HEAD_CENTER);
        if (HeadCenter::getHorgID($cert_head_id) == $current_horg) return true;
        if (HeadCenter::getHorgID($dubl_head_id) == $current_horg) return true;

        return false;
    }

    static public function SearchOld($q, $cert, $restrict_id = false)
    {

        //$offset = (int)filter_input(INPUT_GET,'offset',FILTER_VALIDATE_INT);

        //if ($offset < 0) 
        $offset = 0;

        $list = new ActPeople();
        $con = Connection::getInstance();
        $Where = array();
        if (!empty($q)) {
            $Where[] = "and
            (
            concat(sp.surname_rus, ' ', sp.name_rus, ' ',
 sp.surname_lat, ' ', sp.name_lat) LIKE '%" . $con->escape(
                    $q
                ) . "%' or
               concat(cd.surname_rus, ' ', cd.name_rus, ' ',
 cd.surname_lat, ' ', cd.name_lat) LIKE '%" . $con->escape(
                    $q
                ) . "%'
                ) ";
        }

        if (!empty($cert)) {
            $Where[] = "and (sp.document_nomer = '" . $con->escape($cert) . "'
				or sp.blank_number = '" . $con->escape($cert) . "'
				or cd.certificate_number = '" . $con->escape($cert) . "'

				) ";
        }


        $sql = "SELECT  SQL_CALC_FOUND_ROWS sp.*, count(DISTINCT cd_count.id) AS have_duplicate

 FROM sdt_act_people sp
LEFT JOIN sdt_act ON sp.act_id = sdt_act.id
LEFT JOIN sdt_university ON sdt_university.id = sdt_act.university_id
LEFT JOIN sdt_act_test ON sp.test_id = sdt_act_test.id
LEFT JOIN certificate_duplicate cd_count ON cd_count.user_id = sp.id AND cd_count.deleted = 0
LEFT JOIN certificate_duplicate cd ON cd.user_id = sp.id AND cd.deleted = 0
WHERE
sdt_act.state IN ('wait_payment','archive','received','check','print')
AND sdt_university.head_id = " . CURRENT_HEAD_CENTER . "
				   	" . implode($Where) . "
						AND sp.deleted = 0
						AND sdt_act.deleted = 0

						GROUP BY sp.id
                LIMIT 20 OFFSET " . $offset;
//        die(var_dump($sql));
        $result = $con->query($sql);

        $sql = "Select FOUND_ROWS() as `rows`";
        $count_result = mysql_query($sql);
        $rows_count = mysql_result($count_result, 0);

        if (count($result)) {
            foreach ($result as $row) {
                $actMan = new ActMan($row);
                if ($row['have_duplicate']) {
                    CertificateDuplicate::checkForDuplicates($actMan);
                }
                $list[] = $actMan;
            }
        }

//        echo $con->print_log();

        //return array('list'=>$list, 'rows_count'=>$rows_count);
        return $list;
    }


    static public function SearchSeparate($surname, $name, $blank, array $lc_id = array(), array $skip = array())
    {

        //$offset = (int)filter_input(INPUT_GET,'offset',FILTER_VALIDATE_INT);

        //if ($offset < 0)
        $offset = 0;

        $list = new ActPeople();
        $con = Connection::getInstance();
        $Where = array();
        if (!empty($q)) {
            $Where[] = "and
            (
            concat(sp.surname_rus, ' ', sp.name_rus, ' ',
 sp.surname_lat, ' ', sp.name_lat) LIKE '%" . $con->escape(
                    $q
                ) . "%' or
               concat(cd.surname_rus, ' ', cd.name_rus, ' ',
 cd.surname_lat, ' ', cd.name_lat) LIKE '%" . $con->escape(
                    $q
                ) . "%'
                ) ";
        }

        if (!empty($cert)) {
            $Where[] = "and (sp.document_nomer = '" . $con->escape($cert) . "'
				or sp.blank_number = '" . $con->escape($cert) . "'
				or cd.certificate_number = '" . $con->escape($cert) . "'

				) ";
        }


        $sql = "SELECT  SQL_CALC_FOUND_ROWS sp.*, count(DISTINCT cd_count.id) AS have_duplicate

 FROM sdt_act_people sp
LEFT JOIN sdt_act ON sp.act_id = sdt_act.id
LEFT JOIN sdt_university ON sdt_university.id = sdt_act.university_id
LEFT JOIN sdt_act_test ON sp.test_id = sdt_act_test.id
LEFT JOIN certificate_duplicate cd_count ON cd_count.user_id = sp.id AND cd_count.deleted = 0
LEFT JOIN certificate_duplicate cd ON cd.user_id = sp.id AND cd.deleted = 0
WHERE
sdt_act.state IN ('wait_payment','archive','received','check','print')
AND sdt_university.head_id = " . CURRENT_HEAD_CENTER . "
				   	" . implode($Where) . "
						AND sp.deleted = 0
						AND sdt_act.deleted = 0
						GROUP BY sp.id
                LIMIT 20 OFFSET " . $offset;
//        die(var_dump($sql));
        $result = $con->query($sql);

        $sql = "Select FOUND_ROWS() as `rows`";
        $count_result = mysql_query($sql);
        $rows_count = mysql_result($count_result, 0);

        if (count($result)) {
            foreach ($result as $row) {
                $actMan = new ActMan($row);
                if ($row['have_duplicate']) {
                    CertificateDuplicate::checkForDuplicates($actMan);
                }
                $list[] = $actMan;
            }
        }

//        echo $con->print_log();

        //return array('list'=>$list, 'rows_count'=>$rows_count);
        return $list;
    }

    public static function getAdditionalExam($act_id)
    {
        $sql = 'SELECT sap.*  FROM sdt_act_people sap
  INNER JOIN people_additional_exam pam ON pam.man_id = sap.id

  WHERE sap.act_id = ' . $act_id . '
  AND pam.cert_exists = 0';
//        die($sql);
        $res = Connection::getInstance()->query($sql);
        $list = new self();
        if ($res) {
            foreach ($res as $row) {
                $actMan = new ActMan($row);

                $list[] = $actMan;
            }
        }

        return $list;
    }


    public static function getAdditionalExamForUpload($act_id)
    {
        $sql = 'SELECT sap.* AS need_to_approve FROM sdt_act_people sap
  INNER JOIN people_additional_exam pam ON pam.man_id = sap.id

  WHERE sap.act_id = ' . $act_id;
//        die($sql);
        $res = Connection::getInstance()->query($sql);
        $list = new self();
        if ($res) {
            foreach ($res as $row) {
                $actMan = new ActMan($row);

                $list[] = $actMan;
            }
        }

        return $list;
    }
}

class ActMan extends Model
{
    CONST RETRY_ALL = '9939';
    const DOCUMENT_CERTIFICATE = 'certificate';
    const DOCUMENT_NOTE = 'note';
    const CERT_CANT_LOCK = 'cant_lock';
    const CERT_NOT_AVAILABLE = 'not_available';
    public $id;
    public $act_id;
    public $test_id;
    public $surname_rus;
    public $surname_lat;
    public $name_rus;
    public $name_lat;
    public $country_id;
    public $testing_date;
    public $test_1_ball;
    public $test_1_percent;
    public $test_2_ball;
    public $test_2_percent;
    public $test_3_ball;
    public $test_3_percent;
    public $test_4_ball;
    public $test_4_percent;
    public $test_5_ball;
    public $test_5_percent;
    public $test_6_ball;
    public $test_6_percent;
    public $test_7_ball;
    public $test_7_percent;
    public $test_8_ball;
    public $test_8_percent;
    public $test_9_ball;
    public $test_9_percent;
    public $test_10_ball;
    public $test_10_percent;
    public $test_11_ball;
    public $test_11_percent;
    public $test_12_ball;
    public $test_12_percent;
    public $test_13_ball;
    public $test_13_percent;
    public $test_14_ball;
    public $test_14_percent;
    public $test_15_ball;
    public $test_15_percent;
    public $total;
    public $total_percent;
    public $document;
    public $document_nomer;
    public $passport;
    public $passport_file;
    public $passport_name;
    public $passport_series;
    public $passport_date;
    public $passport_department;
    public $birth_date;
    public $birth_place;
    public $migration_card_number;
    public $migration_card_series;
    public $soprovod_file;
    public $blank_number;
    public $blank_id;
    public $blank_date;
    public $is_retry;
    public $valid_till;
    public $cert_signer;
    public $duplicate;
    protected $_table = 'sdt_act_people';
    private $_act;
    private $additionalExam = null;
    private $ReExam = null;

    public function parseParameters($parameters = false)
    {
        if ($parameters) {
            foreach (['surname_rus', 'name_rus', 'surname_lat', 'name_lat'] as $f) {
                if (array_key_exists($f, $parameters)) {
                    $parameters[$f] = trim($parameters[$f]);
                }
            }

        }
        return parent::parseParameters($parameters);
    }


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $con = Connection::getInstance();
        $sql = 'SELECT * FROM sdt_act_people WHERE id=\'' . $con->escape($id) . '\'';
        $result = $con->query($sql, true);
        if (!$result) {
            return null;
        }
        $univer = new ActMan($result);

        return $univer;
    }

    static public function searchByCertNum($cert)
    {
        $cert = trim($cert);
        if (empty($cert)) {
            return null;
        }
        $list = new ActPeople();
        $con = Connection::getInstance();
        $Where = array();


        /* $Where[] = "and (sp.document_nomer = '" . $con->escape($cert) . "'
                 or sp.blank_number = '" . $con->escape($cert) . "'
                 or cd.certificate_number = '" . $con->escape($cert) . "'

                 ) ";*/


        //$Where[] = "and sp.blank_number = '" . $con->escape($cert) . "'";

        /* $Where[] = "and (sp.blank_number = '" . $con->escape($cert) . "'
         or cd.certificate_number = '" . $con->escape($cert) . "'
         ) ";


         $sql = "SELECT sp.*, count(DISTINCT cd_count.id) as have_duplicate

  FROM sdt_act_people sp
 left JOIN sdt_act ON sp.act_id = sdt_act.id

 left join certificate_duplicate cd_count on cd_count.user_id = sp.id
 left join certificate_duplicate cd on cd.user_id = sp.id
 WHERE
 sdt_act.test_level_type_id = 1
  and
 sdt_act.state in ('wait_payment','archive','received','check','print')

                        " . implode($Where) . "
                        and sp.document='certificate'
                         AND sp.deleted = 0
                         AND sdt_act.deleted = 0
                         group by sp.id
                 limit 1";*/


        $sql = "SELECT user_id FROM certificate_duplicate WHERE
        id=(
        SELECT id FROM certificate_duplicate WHERE
            user_id=(
              SELECT user_id AS asd FROM certificate_duplicate
                JOIN sdt_act_people sap ON sap.id = certificate_duplicate.user_id
              JOIN sdt_act sa ON sa.id = sap.act_id
              WHERE certificate_number = '" . $con->escape($cert) . "'
               AND sa.test_level_type_id=1
               
               AND sap.deleted = 0
               AND sa.deleted = 0
               AND certificate_duplicate.deleted = 0

              )
ORDER BY id DESC LIMIT 1)
 AND certificate_number = '" . $con->escape($cert) . "'";
//        die($sql);
        $r = mysql_query($sql);
        if (!mysql_num_rows($r)) {

            $Where[] = " t1.blank_number = '" . $con->escape($cert) . "' ";

        } else {

            $Where[] = " t1.id='" . mysql_result($r, 0) . "' ";

        }

        $sql = "SELECT t1.* FROM sdt_act_people AS t1
LEFT JOIN sdt_act AS t2 ON t1.act_id=t2.id

WHERE
test_level_type_id = 1
AND
t2.state IN ('wait_payment','archive','received','check','print')
	AND t1.document='certificate'
	AND " . implode($Where) . "

						AND t1.deleted = 0
						AND t2.deleted = 0
						GROUP BY t1.id
                LIMIT 1";

//die($sql);
        $r = mysql_query($sql);
        if (!mysql_num_rows($r)) {
            return null;
        }
        $row = mysql_fetch_assoc($r);


        $actMan = new ActMan($row);

        /* if ($row['have_duplicate']) {
             CertificateDuplicate::checkForDuplicates($actMan);

         }*/

//var_dump($actMan);
        return $actMan;
    }

    static public function searchByNoteNum($note)
    {
        $note = trim($note);
        if (empty($note)) {
            return null;
        }
        $list = new ActPeople();
        $con = Connection::getInstance();
        $Where = array();


        /*
                $sql = "SELECT user_id FROM certificate_duplicate WHERE
                id=(
                SELECT id FROM certificate_duplicate WHERE
                    user_id=(
                      SELECT user_id AS asd FROM certificate_duplicate
                        JOIN sdt_act_people sap ON sap.id = certificate_duplicate.user_id
                      JOIN sdt_act sa ON sa.id = sap.act_id
                      WHERE certificate_number = '" . $con->escape($note) . "'
                       AND sa.test_level_type_id=1

                      )
        ORDER BY id DESC LIMIT 1)
         AND certificate_number = '" . $con->escape($note) . "'";

                $r = mysql_query($sql);
                if (!mysql_num_rows($r)) {

                    $Where[] = " t1.blank_number = '" . $con->escape($note) . "' ";

                } else {

                    $Where[] = " t1.id='" . mysql_result($r, 0) . "' ";

                }*/


//        $Where[] = " t1.document_nomer = '" . $con->escape($note) . "' ";
        $Where[] = " t1.blank_number = '" . $con->escape($note) . "' ";

        $sql = "SELECT t1.* FROM sdt_act_people AS t1
LEFT JOIN sdt_act AS t2 ON t1.act_id=t2.id
LEFT JOIN sdt_act_test AS t3 ON t1.test_id = t3.id

WHERE
/*t3.level_id in (" . implode(',', Reexam_config::getTestLevels()) . ")
AND*/
t2.state IN ('wait_payment','archive','received','check','print')
	AND t1.document='note'
	AND " . implode($Where) . "

						AND t1.deleted = 0
						AND t2.deleted = 0
						AND t3.deleted = 0
						GROUP BY t1.id
                LIMIT 1";

//die($sql);
        $r = mysql_query($sql);
        if (!mysql_num_rows($r)) {
            return null;
        }
        $row = mysql_fetch_assoc($r);


        $actMan = new ActMan($row);


        return $actMan;
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'act_id',
            'test_id',
            'surname_rus',
            'surname_lat',
            'name_rus',
            'name_lat',
            'country_id',
            'testing_date',
            'test_1_ball',
            'test_1_percent',
            'test_2_ball',
            'test_2_percent',
            'test_3_ball',
            'test_3_percent',
            'test_4_ball',
            'test_4_percent',
            'test_5_ball',
            'test_5_percent',
            'test_6_ball',
            'test_6_percent',
            'test_7_ball',
            'test_7_percent',
            'test_8_ball',
            'test_8_percent',
            'test_9_ball',
            'test_9_percent',
            'test_10_ball',
            'test_10_percent',
            'test_11_ball',
            'test_11_percent',
            'test_12_ball',
            'test_12_percent',
            'test_13_ball',
            'test_13_percent',
            'test_14_ball',
            'test_14_percent',
            'test_15_ball',
            'test_15_percent',
            'total',
            'total_percent',
            'document',
            'document_nomer',
            'passport',
            'passport_file',
            'soprovod_file',
            'passport_name',
            'passport_series',
            'passport_date',
            'passport_department',
            'birth_date',
            'birth_place',
            'migration_card_number',
            'migration_card_series',
            'blank_number',
            'blank_date',
            'is_retry',
            'valid_till',
            'blank_id',
            'cert_signer',

        );
    }

    public function getEditFields()
    {
        return array(
            'id',
            'surname_rus',
            'surname_lat',
            'name_rus',
            'name_lat',
            'country_id',
            'testing_date',
            'test_1_ball',
            'test_1_percent',
            'test_2_ball',
            'test_2_percent',
            'test_3_ball',
            'test_3_percent',
            'test_4_ball',
            'test_4_percent',
            'test_5_ball',
            'test_5_percent',
            'test_6_ball',
            'test_6_percent',
            'test_7_ball',
            'test_7_percent',
            'test_8_ball',
            'test_8_percent',
            'test_9_ball',
            'test_9_percent',
            'test_10_ball',
            'test_10_percent',
            'test_11_ball',
            'test_11_percent',
            'test_12_ball',
            'test_12_percent',
            'test_13_ball',
            'test_13_percent',
            'test_14_ball',
            'test_14_percent',
            'test_15_ball',
            'test_15_percent',
            'total',
            'total_percent',
            'document',
            'document_nomer',
            'passport',
            'passport_name',
            'passport_series',
            'passport_date',
            'passport_department',
            'birth_date',
            'birth_place',
            'migration_card_number',
            'migration_card_series',
            'blank_number',
            'blank_date',
            'is_retry',
            'valid_till',
            'cert_signer',

        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
            'testing_date' => 'date',
            'invoice_date' => 'date',
            'birth_date' => 'date',
            'blank_date' => 'date',
            'valid_till' => 'date',
            'comment' => 'text'

        );

    }

    public function getFkFields()
    {
        return array(
            'act_id',
            'test_id',
            'passport_file',
            'soprovod_file',
            'blank_id',
        );
    }

    public function setTranslate()
    {
        $this->translate = array();
    }

    public function getCountry()
    {
        $country = Country::getByID($this->country_id);
        //var_dump($country);
        if (!$country) {
            return new Country();
        }

        return $country;
    }

    public function getFilePassport()
    {
        if ($this->passport_file) {
            return File::getByID($this->passport_file);
        }

        return false;
    }

    public function getDuplicateFilePassport()
    {
        if ($this->duplicate->passport_file_id) {
            return File::getByID($this->duplicate->passport_file_id);
        }

        return false;
    }

    public function getIfDuplicatedFilePassport()
    {

        if (!empty($this->duplicate->passport_file_id)) {
            return $this->duplicate->passport_file_id;
        } else {
            return $this->passport_file;
        }
    }

    public function getSoprovodPassport()
    {
        if ($this->soprovod_file) {
            return File::getByID($this->soprovod_file);
        }

        return false;
    }

    public function delete()
    {
        if ($this->isRetry() && $retr = $this->getRetryInfo()) {
            $retr->delete();
        }

        $sql = 'DELETE FROM sdt_act_people WHERE id = ' . $this->id;
        mysql_query($sql);

        $affected = mysql_affected_rows();


        return $affected;
    }

    public function isRetry()
    {
        return !!$this->is_retry;
    }

    public function getRetryInfo()
    {
        return Re_exam::getByManID($this->id);
    }

    /**
     *
     * Присвоение регистрационного номера
     * @return bool
     */
    public function assignNumbers()
    {
        if (!$this->needNumber()) {
            return false;
        }


        $filname = $_SERVER['DOCUMENT_ROOT'] . "/locks/certificate_doc_num_" . CURRENT_HEAD_CENTER;


        $fp = fopen($filname, "w+");

        if (flock($fp, LOCK_EX)) {

            $increment = 0;
            if ($this->getAct()->test_level_type_id == 2 && $this->document == self::DOCUMENT_CERTIFICATE) {

                do {
                    $increment++;
                    $number = $this->generateNumber($increment);
                    $this->document_nomer = $number->getNumber();

                    $rows = self::checkUniqCertificate($this, $this->getAct());
                    $uniq = $rows['number'];
                    if ($uniq && $number) {
                        try {
                            $number->save();
                        } catch (MysqlException $ex) {
                            $number = null;
                        }
                    }

                } while (!$uniq || !$number);

//            $number->save();
                $this->save();

//                return true;
            }

            if ($this->document == self::DOCUMENT_NOTE) {

                do {
                    $increment++;

                    $number = $this->generateNumber($increment);
                    $this->blank_number = $number->getNumber();
                    $uniq = self::checkUniqNote($this, $this->getAct());
                    if ($uniq && $number) {
                        try {
                            $number->save();
                        } catch (MysqlException $ex) {
                            $number = null;
                        }
                    }
                } while (!$uniq || !$number);
                $this->save();
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        return false;

    }

    public function needNumber()
    {
        if ($this->document == self::DOCUMENT_NOTE && empty($this->blank_number)) {
            return true;
        }
        if (
            $this->document == self::DOCUMENT_CERTIFICATE
            && empty($this->document_nomer)
            && $this->getAct()->test_level_type_id == 2
        ) {
            return true;
        }

        return false;
    }

    public function getAct()
    {
        if (!$this->_act) {
            $this->_act = Act::getByID($this->act_id, false);
        }

        return $this->_act;
    }

    /**
     * @return bool|DocumentNumber
     */
    protected function generateNumber($increment = 1)
    {
        $number = DocumentNumberList::generate(
            $this->document,
            $this->getAct()->test_level_type_id,
            CURRENT_HEAD_CENTER,
            CERTIFICATE_REG_NUMBER_PREFIX,
            NOTE_PREFIX,
            $increment
        );

//        var_dump($number); die;
        return $number;
    }

    /**
     * @param ActMan $item
     * @param Act $act
     */
    protected static function checkUniqCertificate(ActMan $item, Act $act)
    {
        $conditions = array();
        $select = array();
//        if (!empty($item->blank_number)) {
//            $conditions[] = "ap.blank_number = '" . mysql_real_escape_string($item->blank_number) . "'";
//            $select[] = ' count(ap.blank_number) as blank ';
//        }

        if ($act->test_level_type_id == 2
            && !empty($item->document_nomer)
        ) {
            $conditions[] = "ap.document_nomer = '" . mysql_real_escape_string($item->document_nomer) . "'";
            $select[] = ' count(ap.document_nomer) as document';
        } else {
            return array(
                'number' => 0,
            );
        }

        $sql = "select " . implode(', ', $select) . " from sdt_act_people ap
        inner join sdt_act  sa on sa.id = ap.act_id and sa.deleted = 0
        where
        sa.test_level_type_id = " . intval($act->test_level_type_id) . "
        and ap.id <> " . intval($item->id) . "
        and ap.document='" . mysql_real_escape_string($item->document) . "'
        and (" . implode(' or ', $conditions) . ') ';
//                echo $sql;
//        die;
        $r = mysql_query($sql);

        $row = mysql_fetch_assoc($r);

        return array(
//            'blank' => empty($row['blank']),
            'number' => empty($row['document']),
        );

        /* if (!empty($row['blank'])) {
             Errors::add(
                 Errors::CERTIFICATE_BLANK_NUMBER,
                 $item->id,
                 'Номер бланка <strong>"' . $item->blank_number . '"</strong> уже использован'
             );
             $item->blank_number = '';
         }

         if (!empty($row['document'])) {
             Errors::add(
                 Errors::CERTIFICATE_DOCUMENT_NUMBER,
                 $item->id,
                 'Номер документа <strong>"' . $item->document_nomer . '"</strong> уже использован'
             );
             $item->document_nomer = '';
         }*/
    }

    public function save()
    {
        $this->calculate();
        if ($this->id) {
            $uniq = self::checkUnique($this);
//            die("test");
//           if(!$uniq){
//               $this->blank_number=null;
//               $this->document_nomer=null;
//           }
        }
        parent::save();
    }

    public function calculate()
    {
        if ($this->getAct()->state != ACT::STATE_INIT && $this->getAct()->state != ACT::STATE_SEND) {
            return false;
        }

        $Level = $this->getTest()->getLevel();

        $results = $this->getResults();
//die(var_dump($results));
        foreach ($results as $result) {
            $result->balls = sprintf('%01.1f', floatval(str_replace(',', '.', $result->balls)));
        }
        $subtests = $Level->getSubTests();

        $marks = array();
        foreach ($results as $result) {
            $subTest = $subtests->getByOrder($result->order);
            if (is_null($subTest)) {
                $result->balls = 0;
                continue;
            }

            if ($result->balls > $subTest->max_ball) {
                $result->balls = $subTest->max_ball;
            }
            $calcPercent = $result->balls / $subTest->max_ball * 100;
            if ($subTest->level_id == 5) {
                if ($result->order == 1 && round($result->balls) == 92) {
                    $calcPercent = 66.0;
                }
                if ($result->order == 4 && round($result->balls) == 79) {
                    $calcPercent = 66.0;
                }
                if ($result->order == 5 && round($result->balls) == 112) {
                    $calcPercent = 66.0;
                }
            }
//var_dump($calcPercent,$subTest,$result);die;
            $result->percent = sprintf('%01.1f', $calcPercent);
            $marks[] = $result->percent;
        }


        $this->total = 0;
        foreach ($results as $result) {
            $this->total += $result->balls;
        }


        $this->total_percent = sprintf('%01.1f', $this->total / $Level->total * 100);

        $results->expose($this);
        $this->document = $this->documentType($results, $Level);

        return true;
    }

    public function getTest()
    {
        return ActTest::getByID($this->test_id);
    }

    /**
     * @return SubTestResults|SubTestResult[]
     */
    public function getResults()
    {
        return SubTestResults::getByMan($this);
    }

    protected function documentType(SubTestResults $results, TestLevel $level)
    {
        $minNormativ = $level->percent_min;
        $maxNormativ = $level->percent_max;
        $minNormativCount = 0;
        $missCount = 0;
        $maxNormativCount = 0;

        $grouped = [];

        foreach ($results as $result) {
            /** @var SubTestResult $result */
            if ($result->subtest->meta && $result->subtest->meta->group_id) {
                if (!array_key_exists($result->subtest->meta->group_id, $grouped)) {
                    $grouped[$result->subtest->meta->group_id] = [
                        'g' => $result->subtest->meta->getGroup(),
                        't' => [],
                    ];
                }
                $grouped[$result->subtest->meta->group_id]['t'][$result->subtest->meta->formula_var] = $result;
                continue;
            }

            if (!$result->subtest->pass_score) {
                if ($result->percent >= $maxNormativ) {
                    $maxNormativCount++;
                    continue;
                }
                if ($result->percent >= $minNormativ) {
                    $minNormativCount++;
                    continue;
                }
            } else {
                if ($result->percent >= $result->subtest->pass_score) {
//                    die(var_dump($result));
                    $maxNormativCount++;
                    continue;
                }
            }
            $missCount++;
        }

        if (!empty($grouped)) {
            $calculation = new TestCalculation();

            foreach ($grouped as $group) {
                /** @var SubTestGroup $g */
                $g = $group['g'];

                $res = $calculation->calc($g, $group['t']);
                if (!$res) $missCount++;
            }

        }

        if ($missCount) {
            return self::DOCUMENT_NOTE;
        }
        if ($minNormativCount > 1) {
            return self::DOCUMENT_NOTE;
        }

        return self::DOCUMENT_CERTIFICATE;
    }

    /**
     * @param ActMan $item
     * @return bool
     */
    static function checkUnique(ActMan $item)
    {
        $act = $item->getAct();

        if (
            (
                (
                    $act->test_level_type_id == 1
                    ||
                    (
                        $act->test_level_type_id == 2
                        &&
                        $item->document == self::DOCUMENT_NOTE
                    )
                )
                &&
                empty($item->blank_number)
            )
            ||
            (
                $act->test_level_type_id == 2
                &&
                $item->document == self::DOCUMENT_CERTIFICATE
                &&
                empty($item->blank_number)
                &&
                empty($item->document_nomer)
            )
        ) {
            return true;
        }
//        echo '<pre>';
//        var_dump($item);die;
//        var_dump($item);

        switch ($item->document) {
            case self::DOCUMENT_CERTIFICATE:
//                self::checkUniqCertificate($item, $act);


                break;
            case self::DOCUMENT_NOTE:
//                self::checkUniqNote($item, $act);
                break;
            default:
//                die('jopa');

        }

        return true;

    }

    /**
     * @param ActMan $item
     * @param Act $act
     */
    protected static function checkUniqNote(ActMan $item, Act $act)
    {
        $conditions = array();
        $select = array();

        $conditions[] = "ap.blank_number = '" . mysql_real_escape_string($item->blank_number) . "'";
        $select[] = ' count(ap.blank_number) as blank ';


        $sql = "select " . implode(', ', $select) . " from sdt_act_people ap
        inner join sdt_act  sa on sa.id = ap.act_id and sa.deleted = 0
        Inner join sdt_university su on sa.university_id = su.id and su.deleted = 0
        where
        su.head_id=" . intval(CURRENT_HEAD_CENTER) . "
        and ap.id <> " . intval($item->id) . "
        and ap.document='" . mysql_real_escape_string($item->document) . "'
        and (" . implode(' or ', $conditions) . ') ';
//        echo $sql;
//        die($sql);
        $r = mysql_query($sql);
        $row = mysql_fetch_assoc($r);

        return empty($row['blank']);

        /*  if (!empty($row['blank'])) {
              Errors::add(
                  Errors::NOTE_BLANK_NUMBER,
                  $item->id,
                  'Номер справки <strong>"' . $item->blank_number . '"</strong> уже использован'
              );
              $item->blank_number = '';
          }*/


    }

    public function needBlank()
    {


        if (
            $this->document == self::DOCUMENT_CERTIFICATE
            && empty($this->blank_number)

        ) {
            return true;
        }

        return false;
    }

    /* public function issueDuplicate()
     {
         $act = $this->getAct();
         $newCert = CertificateReserved::getActiveByType($act->test_level_type_id);
         if (!$newCert) {
             return -1;
         }

         $oldCert = $this->getCertBlank();
         if ($oldCert) {
             $oldCert->invalid = 1;
             $oldCert->save();
         }
         CertificateInvalid::add($this, $oldCert);


         return $this->setBlank($newCert);


     }*/

    public function is_anull()
    {
        if (AnnulCert::getByManID($this->id)) return true;
        else return false;

    }

    public function assignBlank()
    {


        $act = $this->getAct();

        $filname = $this->getLockPath($act);


        $fp = fopen($filname, "w+");

        if (flock($fp, LOCK_EX)) {

            $cert = CertificateReserved::getActiveByType($act->test_level_type_id);
            if (!$cert) {
                flock($fp, LOCK_UN);
                fclose($fp);

                return false;
            }
            //sleep(2);
            $this->setBlank($cert);

//            sleep(5);
//            die(var_dump($cert));
        } else {
            flock($fp, LOCK_UN);
            fclose($fp);

            return false;
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }

    /**
     * @param $act
     * @return string
     */
    private function getLockPath($act)
    {
        return $_SERVER['DOCUMENT_ROOT'] . "/locks/certificate_blank_" . CURRENT_HEAD_CENTER . '_' . $act->test_level_type_id;
    }

    /**
     * @param CertificateReserved $cert
     * @return bool
     */
    protected function setBlank(CertificateReserved $cert)
    {
        $this->blank_number = $cert->number;
//        die(var_dump($this->blank_date));
        if (empty($this->blank_date) || $this->blank_date == '0000-00-00') {
            $this->blank_date = $this->getAct()->getPrintDateAfterCheckDate();
            /*$now = new DateTime();
            $checkDate = new DateTime($this->getAct()->check_date);
            if($now>=$checkDate)
            {
                $this->blank_date = date('Y-m-d');
            }
            else{
                $this->blank_date = $checkDate->format('Y-m-d');
            }*/
            $this->setValidTill();
        }
        $this->blank_id = $cert->id;
        $cert->used = 1;
        $this->save();
        $cert->save();
        CertificateUsed::add($this);

        return true;
    }

    public function setValidTill()
    {
        if ($this->blank_date && $this->blank_date != '0000-00-00' && $this->getLevel()->valid_duration) {
            $date = new DateTime($this->blank_date);
//            die(var_dump($date));
            $date->modify('+' . $this->getLevel()->valid_duration . ' year');
            $this->valid_till = date_format($date, 'Y-m-d');
        } else {
//            die(($this->blank_date));
            $this->valid_till = null;
        }


    }

    public function getLevel()
    {
        return $this->getTest()->getLevel();
    }

    public function tryAssignBlank($certNumber, &$error = null)
    {


        $act = $this->getAct();
        $filname = $this->getLockPath($act);
        $fp = fopen($filname, "w+");
        if (flock($fp, LOCK_EX)) {
            $cert = CertificateReserved::getActiveByTypeAndNum($act->test_level_type_id, $certNumber);
            if (!$cert) {
                $error = self::CERT_NOT_AVAILABLE;
                flock($fp, LOCK_UN);
                fclose($fp);
                return false;
            }
            $this->setBlank($cert);

        } else {
            $error = self::CERT_CANT_LOCK;
            flock($fp, LOCK_UN);
            fclose($fp);

            return false;
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }

    public function isCertificate()
    {
        return $this->document == self::DOCUMENT_CERTIFICATE;
    }

    public function isNote()
    {
        return $this->document == self::DOCUMENT_NOTE;
    }

    public function invalidateBlank($reason)
    {

//        $act = $this->getAct();


        $oldCert = $this->getCertBlank();
        if ($oldCert) {
            $oldCert->invalid = 1;
            $oldCert->save();
        }
        CertificateInvalid::add($this, $oldCert, $reason);

        $this->emptyBlankFields();

        $this->save();

        return true;
    }

    public function getCertBlank()
    {
        if ($this->document == self::DOCUMENT_CERTIFICATE && !empty($this->blank_id)) {
            return CertificateReserved::getByID($this->blank_id);
        }

        return null;
    }

    protected function emptyBlankFields()
    {
        $this->blank_date = null;
        $this->blank_number = null;
        $this->blank_id = null;
    }

    public function getBlank_number()
    {
        if (!empty($this->duplicate->certificate_number)) {
            return $this->duplicate->certificate_number;
        } else {
            return $this->blank_number;
        }
    }

    public function saveOldCertMan()
    {
//        $this->calculate();
        /* if ($this->id) {
             $uniq = self::checkUnique($this);*/
//            die("test");
//           if(!$uniq){
//               $this->blank_number=null;
//               $this->document_nomer=null;
//           }
//        }
//        return $this->id;
        parent::save();
    }

    public function getAdditionalExam()
    {

        if (is_null($this->additionalExam)) {
            $this->additionalExam = ManAdditionalExam::getByManID($this->id);
        }

        return $this->additionalExam;
    }

    public function getReExam()
    {

        if (is_null($this->ReExam)) {
            $this->ReExam = Re_exam::getByManID($this->id);
        }

        return $this->ReExam;
    }


    public function getApiUserData()
    {
        return ApiUserData::getByManID($this->id);
    }

    public function saveCert_signer($cert_signer)
    {
        if (!empty($this->duplicate->id)) {
            if (empty($this->duplicate->cert_signer)) {


                $cert_duplicate = CertificateDuplicate::getByUserID($this->duplicate->user_id);
                $cert_duplicate->cert_signer = $cert_signer;
                $cert_duplicate->save();
            }

        } else {
            if (empty($this->cert_signer)) {
                $this->cert_signer = $cert_signer;
                $this->save();

            }

        }
        return true;
    }

    public function getUniversity()
    {

        return University::getByActID($this->act_id);
    }

    public function getAnull()
    {
        return AnnulCert::getByManID($this->id);


    }

    public function isFreeRetry()
    {
        if (!$this->isRetry()) return false;
        $q = 'SELECT rei.is_free FROM re_exam_info rei
    WHERE rei.man_id =  %d';
        $c = Connection::getInstance();

        if ($res = $c->queryOne(sprintf($q, $this->id))) {
            return !!$res['is_free'];
        }
        return false;
    }

    public function retryNumber()
    {
        if (!$this->isRetry()) return false;
        $q = 'SELECT rei.number FROM re_exam_info rei
    WHERE rei.man_id =  %d';
        $c = Connection::getInstance();

        if ($res = $c->queryOne(sprintf($q, $this->id))) {
            return !!$res['number'];
        }
        return false;
    }

    /**
     *
     * @return array
     */
    public function getRexamData()
    {
        $return = array();
        $return['surname_rus'] = $this->getSurname_rus();
        $return['surname_rus'] = $this->getSurname_rus();
        $return['surname_lat'] = $this->getSurname_lat();
        $return['name_rus'] = $this->getName_rus();
        $return['name_lat'] = $this->getName_lat();

        $return['country_id'] = $this->country_id;


        $return['passport_name'] = $this->passport_name;
        $return['passport_series'] = $this->passport_series;
        $return['passport'] = $this->passport;
        $return['passport_date'] = $this->passport_date;
        $return['passport_department'] = $this->passport_department;

        $return['birth_place'] = $this->birth_place;
        $return['birth_date'] = $this->birth_date;

        $return['migration_card_number'] = $this->migration_card_number;
        $return['migration_card_series'] = $this->migration_card_series;
        return $return;
    }

    public function getSurname_rus()
    {
        if (!empty($this->duplicate->surname_rus)) {
            return $this->duplicate->surname_rus;
        } else {
            return $this->surname_rus;
        }
    }

    public function getSurname_lat()
    {
        if (!empty($this->duplicate->surname_lat)) {
            return $this->duplicate->surname_lat;
        } else {
            return $this->surname_lat;
        }
    }

    public function getName_rus()
    {
        if (!empty($this->duplicate->name_rus)) {
            return $this->duplicate->name_rus;
        } else {
            return $this->name_rus;
        }
    }

    public function getName_lat()
    {
        if (!empty($this->duplicate->name_lat)) {
            return $this->duplicate->name_lat;
        } else {
            return $this->name_lat;
        }
    }

}