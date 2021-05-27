<?php

class ActPeople extends ArrayObject
{

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
        $sql = 'SELECT * FROM sdt_act_people  WHERE test_id=' . $id . ' AND deleted=0';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActMan($row);
        }

        return $list;
    }

    static public function Search1($q, $cert, $restrict_id = false)
    {
        $list = new ActPeople();

        $Where = array();
        if (!empty($q)) {
            $Where[] = "and concat(sdt_act_people.surname_rus, ' ', sdt_act_people.name_rus, ' ',
 sdt_act_people.surname_lat, ' ', sdt_act_people.name_lat) LIKE '%" . mysql_real_escape_string(
                    $q
                ) . "%'";
        }

        if (!empty($cert)) {
            $Where[] = "and (document_nomer = '" . mysql_real_escape_string($cert) . "'
				or blank_number = '" . mysql_real_escape_string($cert) . "')";
        }

        $sql = "SELECT sdt_act_people.*
				FROM
				sdt_act_people
				INNER JOIN sdt_act
				ON sdt_act_people.act_id = sdt_act.id
				INNER JOIN sdt_university
				ON sdt_university.id = sdt_act.university_id
				INNER JOIN sdt_act_test
				ON sdt_act_people.test_id = sdt_act_test.id
				WHERE
				sdt_act.state IN ('wait_payment','archive','received','check','print')
				  	" . implode($Where) . "


						AND sdt_act_people.deleted = 0
						AND sdt_act.deleted = 0
						AND sdt_act_test.deleted = 0 LIMIT 20";
        //die(var_dump($sql));
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActMan($row);
        }

        return $list;
    }


    static public function Search($q, $cert, $name, $birthday, $restrict_id = false, $filter = [])
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
        $percentSymbol = '%';
        if (isset($filter['exactSearch']) && $filter['exactSearch']) {
            $percentSymbol = '';
        }

        $typeSearch = '';
        if (isset($filter['docTypeSearch']) && $filter['docTypeSearch']) {
            $typeSearch = " and sp.document  = '" . $con->escape($filter['docTypeSearch']) . "'";
        }
        $testLevelType='';
        if (isset($filter['testTypeSearch']) && $filter['testTypeSearch']) {
            $testLevelType = " and sdt_act.test_level_type_id  = '" . $con->escape($filter['testTypeSearch']) . "'";
        }
//die(var_dump($typeSearch));
        if (!empty($q)) {
            $Where[] = "and
            (
            sp.surname_rus LIKE '" . $con->escape($q) . $percentSymbol . "' or
            sp.surname_lat LIKE '" . $con->escape($q) . $percentSymbol . "'
            ) ";

            $WhereAnnull[] = "and
            (
            cal.man_surname_ru LIKE '" . $con->escape($q) . $percentSymbol . "' or
            cal.man_surname_en LIKE '" . $con->escape($q) . $percentSymbol . "'
            ) ";
            $WhereDuplicate[] = "and
            (
            cd.surname_rus LIKE '" . $con->escape($q) . $percentSymbol . "' or
            cd.surname_lat LIKE '" . $con->escape($q) . $percentSymbol . "'
            ) ";
        }

        if (!empty($name)) {
            $Where[] = "and
            (
            sp.name_rus LIKE '" . $con->escape($name) . $percentSymbol . "' or
            sp.name_lat LIKE '" . $con->escape($name) . $percentSymbol . "'
            ) ";

            $WhereDuplicate[] = "and
            (
            cd.name_rus LIKE '" . $con->escape($name) . $percentSymbol . "' or
            cd.name_lat LIKE '" . $con->escape($name) . $percentSymbol . "'
            ) ";
            $WhereAnnull[] = "and
            (
            cal.man_name_ru LIKE '" . $con->escape($name) . $percentSymbol . "' or
            cal.man_name_en LIKE '" . $con->escape($name) . $percentSymbol . "'
            ) ";
        }

        if (!empty($birthday)) {
            $Where[] = "and
            sp.birth_date = '" . $con->format_date($birthday) . "'";

            $WhereDuplicate[] = "and
            sp.birth_date = '" . $con->format_date($birthday) . "'";

            $WhereAnnull[] = "and
            sp.birth_date = '" . $con->format_date($birthday) . "'";
        }

        $certs_annul = '';

        if (!empty($cert)) {
//            $Where[] = "and (sp.document_nomer = '" . $con->escape($cert) . "'
//				or sp.blank_number = '" . $con->escape($cert) . "'
//    			or cd.certificate_number = '" . $con->escape($cert) . "'
//
//				) ";
            $WhereDuplicate[] = "
   			and cd.certificate_number = '" . $con->escape($cert) . "'
";
            $WhereAnnull[] = "
   			and cal.blank_number = '" . $con->escape($cert) . "'
";

            /* $WhereDuplicate[] ="
   			and cd.certificate_number like '%" . $con->escape($cert) . "'
";*/
            $Where[] = "and (sp.document_nomer = '" . $con->escape($cert) . "'
				or sp.blank_number = '" . $con->escape($cert) . "'
			
    			) ";


            /* $Where[] = "and (sp.document_nomer like '%" . $con->escape($cert) . "'
                 or sp.blank_number like '%" . $con->escape($cert) . "'
                 ) ";*/
//            $certs_annul = sprintf('and  = "%s"', $con->escape($cert));
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

 GROUP BY sp.id limit 50)";
        }

        $sql = "
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

 WHERE
   sdt_act.state IN ('wait_payment', 'archive', 'received', 'check', 'print')
   " . implode(' ', $Where) . "
 AND sp.deleted = 0
 AND sdt_act.deleted = 0
 " . $typeSearch . "
 " . $testLevelType . "
 GROUP BY sp.id limit 50)
  
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
     ON cd.user_id = sp.id AND cd.deleted = 0
     left join certs_annul cal on cal.man_id = sp.id 
 WHERE
   sdt_act.state IN ('wait_payment', 'archive', 'received', 'check', 'print')
  " . implode(' ', $WhereDuplicate) . "
  " . $typeSearch . "
  " . $testLevelType . "
 AND sp.deleted = 0
 AND sdt_act.deleted = 0

 GROUP BY sp.id LIMIT 50)

     LIMIT 50";
//                die(($sql));
        //        echo $sql;
        $result = $con->query($sql);
        if (count($result)) {
            foreach ($result as $row) {
                $actMan = new ActMan($row);

                if ($row['have_duplicate']) {
                    $actMan = CertificateDuplicate::checkForDuplicates($actMan);
                }
                //                die(var_dump($row['have_duplicate']));
                $list[] = $actMan;
            }
        }
        //        echo $con->print_log();
        //die(var_dump($list));
        return $list;
    }


    static public function Search_by_range($cert, $type, $restrict_id = false)
    {
//        $cert = trim($cert);


        if (empty($cert)) return array();
        $list = new ActPeople();
        $con = Connection::getInstance();
        $Where = array();
        $WhereDuplicate = array();
        $WhereAnnull = array();
        $certs = explode(" ", $con->escape($cert));

        foreach ($certs as $key => $val) {
//            print_r($val);
            if (empty($val)) unset($certs[$key]);
            else $certs[$key] = "'" . $val . "'";

        }
        $certs_counter = count($certs);
        $certs = implode($certs, ",");
//        die(var_dump($certs));

        $certs_annul = '';

//        if (!empty($cert)) {
        if (!empty($certs)) {

            $WhereDuplicate[] = "
   			and cd.certificate_number in (" . $certs . ")
";
            $WhereAnnull[] = "
   			and cal.blank_number in (" . $certs . ")
";


            $Where[] = "and (sp.document_nomer in (" . $certs . ")
				or sp.blank_number in (" . $certs . ")

    			) ";

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
   AND sdt_act.test_level_type_id = " . $type . "
  " . implode(' ', $WhereAnnull) . "
 AND sp.deleted = 0
 AND sdt_act.deleted = 0

 GROUP BY sp.id )";
        }

        $sql = "
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

 WHERE
   sdt_act.state IN ('wait_payment', 'archive', 'received', 'check', 'print')
   AND sdt_act.test_level_type_id = " . $type . "
   " . implode(' ', $Where) . "
 AND sp.deleted = 0
 AND sdt_act.deleted = 0

 GROUP BY sp.id )

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
     ON cd.user_id = sp.id AND cd.deleted = 0
     left join certs_annul cal on cal.man_id = sp.id
 WHERE
   sdt_act.state IN ('wait_payment', 'archive', 'received', 'check', 'print')
   AND sdt_act.test_level_type_id = " . $type . "
  " . implode(' ', $WhereDuplicate) . "
 AND sp.deleted = 0
 AND sdt_act.deleted = 0

 GROUP BY sp.id )

     ";
//                die(($sql));
        //        echo $sql;
        $result = $con->query($sql);
        if (count($result)) {
            foreach ($result as $row) {
                $actMan = new ActMan($row);

                if ($row['have_duplicate']) {
                    $actMan = CertificateDuplicate::checkForDuplicates($actMan);
                }

                $list[] = $actMan;
            }
        }

        return array($list, $certs_counter);
    }


    static public function SearchOld($q, $cert, $restrict_id = false)
    {
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


        $sql = "SELECT sp.*, count(DISTINCT cd_count.id) AS have_duplicate

  FROM sdt_act_people sp
 LEFT JOIN sdt_act ON sp.act_id = sdt_act.id
 LEFT JOIN sdt_university ON sdt_university.id = sdt_act.university_id
 LEFT JOIN sdt_act_test ON sp.test_id = sdt_act_test.id
 LEFT JOIN certificate_duplicate cd_count ON cd_count.user_id = sp.id AND cd_count.deleted = 0
 LEFT JOIN certificate_duplicate cd ON cd.user_id = sp.id AND cd.deleted = 0
 WHERE
 sdt_act.state IN ('wait_payment','archive','received','check','print')

                        " . implode($Where) . "
                         AND sp.deleted = 0
                         AND sdt_act.deleted = 0
                         GROUP BY sp.id
                 LIMIT 20";
        //        die(var_dump($sql));
        $result = $con->query($sql);
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

        return $list;
    }


}

class ActMan extends Model
{
    const DOCUMENT_CERTIFICATE = 'certificate';
    const DOCUMENT_NOTE = 'note';
    protected $_table = 'sdt_act_people';

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
    public $blank_date;
    public $is_retry;
    public $valid_till;
    public $cert_signer;

    /** @var  CertificateDuplicate */
    public $duplicate;

    public $blank_id;

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT * FROM sdt_act_people WHERE id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new ActMan(mysql_fetch_assoc($result));

        return $univer;
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
            'cert_signer',
            'blank_id',

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
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
            'testing_date' => 'date',
            'invoice_date' => 'date',
            'birth_date' => 'date',
            'blank_date' => 'date',
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

            $result->percent = sprintf('%01.1f', $result->balls / $subTest->max_ball * 100);
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

    public function save()
    {
        $this->calculate();

        parent::save();
    }

    protected function documentType(SubTestResults $results, TestLevel $level)
    {
        $minNormativ = $level->percent_min;
        $maxNormativ = $level->percent_max;
        $minNormativCount = 0;
        $missCount = 0;
        $maxNormativCount = 0;

        foreach ($results as $result) {
            /** @var SubTestResult $result */

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
        if ($missCount) {
            return self::DOCUMENT_NOTE;
        }
        if ($minNormativCount > 1) {
            return self::DOCUMENT_NOTE;
        }

        return self::DOCUMENT_CERTIFICATE;
    }

    public function getTest()
    {
        return ActTest::getByID($this->test_id);
    }

    private $_act;

    public function getAct()
    {
        if (!$this->_act) {
            $this->_act = Act::getByID($this->act_id);
        }

        return $this->_act;
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

    public function getSoprovodPassport()
    {
        if ($this->soprovod_file) {
            return File::getByID($this->soprovod_file);
        }

        return false;
    }

    public function delete()
    {
        $sql = 'DELETE FROM sdt_act_people WHERE id = ' . $this->id;
        mysql_query($sql);

        return mysql_affected_rows();
    }

    /**
     * @return SubTestResults|SubTestResult[]
     */
    public function getResults()
    {
        return SubTestResults::getByMan($this);
    }

    public function getLevel()
    {
        return $this->getTest()->getLevel();
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

    public function getBlank_number()
    {
        if (!empty($this->duplicate->certificate_number)) {
            return $this->duplicate->certificate_number;
        } else {
            return $this->blank_number;
        }
    }

    public function getBlankID()
    {
        if (!empty($this->duplicate->certificate_id)) {
            return $this->duplicate->certificate_id;
        } else {
            return $this->blank_id;
        }
    }

    public function isCertificate()
    {
        return $this->document == self::DOCUMENT_CERTIFICATE;
    }

    public function isNote()
    {
        return $this->document == self::DOCUMENT_NOTE;
    }

    public function getCertSigner()
    {
        return ActSigning::getCertSigner($this);
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

    public function emptyBlankFields()
    {
        $this->blank_date = null;
        $this->blank_number = null;
        $this->blank_id = null;
    }

    public function is_anull()
    {
        if (AnnulCert::getByManID($this->id)) return true;
        else return false;

    }

    public function getAnull()
    {
        return AnnulCert::getByManID($this->id);


    }

}