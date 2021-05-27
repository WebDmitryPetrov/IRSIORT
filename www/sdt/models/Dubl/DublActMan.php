<?php

class DublActManList extends ArrayObject
{
    static public function getByDublAct($id)
    {
        $list = new self();
        $con = Connection::getInstance();

        $sql = 'select * from ' . DublActMan::TABLE . ' where act_id = ' . intval($id) . '';
        $result = $con->query($sql);
        if ($result) {
            foreach ($result as $row) {
                $list[] = new DublActMan($row);
            }
        }

        return $list;
    }








    static public function Search($surname, $name, $cert, $restrict_id = false)
    {

        //$offset = (int)filter_input(INPUT_GET,'offset',FILTER_VALIDATE_INT);
//die(DublAct::getByID(filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT))->test_level_type_id);
        //if ($offset < 0)
        $offset=0;

        $list = new ActPeople();
        $con = Connection::getInstance();
        $Where = array();
        $Where_dubl = array();



        if (!empty($surname)) {

//            $Where[]= " and";
            $Where_dubl[]= " (cd.surname_rus like('".$con->escape($surname)."%') or cd.surname_lat like('".$con->escape($surname)."%'))";
            $Where[]= " and (sp.surname_rus like('".$con->escape($surname)."%') or sp.surname_lat like('".$con->escape($surname)."%'))";

        }

        if (!empty($name)) {

//            $Where[]= " and";
            $Where_dubl[]= " (cd.name_rus like('".$con->escape($name)."%') or cd.name_lat like('".$con->escape($name)."%'))";
            $Where[]= " and (sp.name_rus like('".$con->escape($name)."%') or sp.name_lat like('".$con->escape($name)."%'))";

        }



        if (!empty($cert)) {
            $Where_dubl[] = " cd.certificate_number = '" . $con->escape($cert) . "'";
            $Where[] = " and (sp.document_nomer = '" . $con->escape($cert) . "'
				or sp.blank_number = '" . $con->escape($cert)."')";
        }

 /*       $sql_dubl='select user_id from certificate_duplicate where '.implode(' and', $Where_dubl);


$result_dubl=(mysql_query($sql_dubl));

        $dubls=array();
while ($res=mysql_fetch_array($result_dubl))
{
    $dubls[]=$res[0];
}
*/
//AND sdt_university.head_id = " . CURRENT_HEAD_CENTER . "
        $sql_options=" FROM sdt_act_people sp
LEFT JOIN sdt_act ON sp.act_id = sdt_act.id
LEFT JOIN sdt_university ON sdt_university.id = sdt_act.university_id
LEFT JOIN sdt_act_test ON sp.test_id = sdt_act_test.id
LEFT JOIN certificate_duplicate cd_count ON cd_count.user_id = sp.id AND cd_count.deleted = 0
WHERE sdt_act.state IN ('wait_payment','archive','received','check','print')


AND sp.blank_number <> '' and  sdt_act.test_level_type_id=" .DublAct::getByID(filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT))->test_level_type_id ."

 AND sp.deleted = 0 AND sdt_act.deleted = 0 and sp.document='certificate' ";



        $sql="
         SELECT SQL_NO_CACHE SQL_CALC_FOUND_ROWS sp.*, COUNT(DISTINCT cd_count.id) AS have_duplicate
    " . $sql_options . "
  " . implode($Where) . "

GROUP BY sp.id

union
SELECT   sp.*, COUNT(DISTINCT cd_count.id) AS have_duplicate
    " . $sql_options . "
  and sp.id IN (select user_id from certificate_duplicate cd
  where ". implode(' and', $Where_dubl). " AND cd.deleted = 0)
  GROUP BY sp.id
  limit 20 offset ".$offset;



      /*  $sql="SELECT SQL_NO_CACHE SQL_CALC_FOUND_ROWS sp.*, COUNT(DISTINCT cd_count.id) AS have_duplicate
        ".$sql_options."
        and sp.id in(33107,53217,169359)
        GROUP by sp.id
        ";*/

//        die ($sql);



       /* $sql = "SELECT  SQL_CALC_FOUND_ROWS sp.*, count(DISTINCT cd_count.id) as have_duplicate

 FROM sdt_act_people sp
left JOIN sdt_act ON sp.act_id = sdt_act.id
left JOIN sdt_university ON sdt_university.id = sdt_act.university_id
left JOIN sdt_act_test ON sp.test_id = sdt_act_test.id
left join certificate_duplicate cd_count on cd_count.user_id = sp.id
left join certificate_duplicate cd on cd.user_id = sp.id
WHERE
sdt_act.state in ('wait_payment','archive','received','check','print')
and sdt_university.head_id = " . CURRENT_HEAD_CENTER . "
				   	" . implode($Where) . "
						AND sp.deleted = 0
						AND sdt_act.deleted = 0
						group by sp.id
                limit 20 offset ".$offset;*/
//        die(var_dump($sql));
        $result = $con->query($sql);

        $sql = "Select FOUND_ROWS() as `rows`";
        $count_result = mysql_query($sql);
        $rows_count=mysql_result($count_result,0);

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





}

class DublActMan extends Model
{
    const TABLE = 'dubl_act_man';


    protected $_table = self::TABLE;

    public $act_id;
    public $old_man_id;
//    public $state = self::STATE_IN_LC;
    public $file_request_id;
    public $file_passport_id;

    public $surname_rus_old;
    public $surname_lat_old;

    public $surname_rus_new;
    public $surname_lat_new;


    public $name_rus_old;
    public $name_lat_old;

    public $name_rus_new;
    public $name_lat_new;

    public $is_changed = 0;
    public $dubl_cert_id;

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $con = Connection::getInstance();
        $sql = 'select * from ' . self::TABLE . ' where id=\'' . $con->escape($id) . '\'';
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
            'act_id',
            'old_man_id',
            'file_request_id',
            'file_passport_id',
            'surname_rus_old',
            'surname_lat_old',
            'surname_rus_new',
            'surname_lat_new',
            'name_rus_old',
            'name_lat_old',
            'name_rus_new',
            'name_lat_new',
            'is_changed',
            'dubl_cert_id',


        );
    }

    public function getEditFields()
    {
        return array(
            'id',
            'act_id',
            'old_man_id',
            'file_request_id',
            'file_passport_id',
            'surname_rus_old',
            'surname_lat_old',
            'surname_rus_new',
            'surname_lat_new',
            'name_rus_old',
            'name_lat_old',
            'name_rus_new',
            'name_lat_new',
            'is_changed',
            'dubl_cert_id',
        );
    }

    public function getFileRequest()
    {
        if ($this->file_request_id) {
            return File::getByID($this->file_request_id);
        }

        return null;
    }

    public function getFilePassport()
    {
        if ($this->file_passport_id) {
            return File::getByID($this->file_passport_id);
        }

        return null;
    }

    public function getAct()
    {

        return DublAct::getByID($this->act_id);
    }

    public function delete()
    {
        $con = Connection::getInstance();
        $sql = 'delete from ' . self::TABLE . ' where id  = ' . intval($this->id);
        return $con->execute($sql);
//            return true;

    }

    public function getFioRus()
    {
        return $this->getSurnameRus() . ' ' . $this->getNameRus();
    }

    public function getFioRusOld()
    {
        return $this->surname_rus_old . ' ' .$this->name_rus_old;
    }
    public function getFioLatOld()
    {
        return $this->surname_lat_old . ' ' .$this->name_lat_old;
    }

    public function getFioRusNew()
    {
        return trim($this->surname_rus_new . ' ' .$this->name_rus_new);
    }
    public function getFioLatNew()
    {
        return trim($this->surname_lat_new . ' ' .$this->name_lat_new);
    }

    public function getSurnameRus()
    {
        if ($this->surname_rus_new) return $this->surname_rus_new;
        else return $this->surname_rus_old;
    }

    public function getSurnameLat()
    {
        if ($this->surname_lat_new) return $this->surname_lat_new;
        else return $this->surname_lat_old;
    }

    public function getNameLat()
    {
        if ($this->name_lat_new) return $this->name_lat_new;
        else return $this->name_lat_old;
    }

    public function getNameRus()
    {
        if ($this->name_rus_new) return $this->name_rus_new;
        else return $this->name_rus_old;
    }

    public function getCertNum()
    {
        if ($this->dubl_cert_id) return CertificateReserved::getByID($this->dubl_cert_id)->number;
        else return '';
    }



    public function assignBlank()
    {

        $old_man=ActMan::getByID($this->old_man_id);

//        $act = $old_man->getAct();

        //$filname = $_SERVER['DOCUMENT_ROOT'] . "/locks/certificate_blank_" . CURRENT_HEAD_CENTER . '_' . $this->getAct()->test_level_type_id;
        $filname = $this->getLockPath($this->getAct());


        $fp = fopen($filname, "w+");

        if (flock($fp, LOCK_EX)) {

            $cert = CertificateReserved::getActiveByType($this->getAct()->test_level_type_id);
            if (!$cert) {
                flock($fp, LOCK_UN);
                fclose($fp);

                return false;
            }

            $this->setBlank($cert);


        } else {
            flock($fp, LOCK_UN);
            fclose($fp);

            return false;
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }



    public function tryAssignBlank($certNumber, &$error=null)
    {


        $act = $this->getAct();
        $filname = $this->getLockPath($act);
        $fp = fopen($filname, "w+");
        if (flock($fp, LOCK_EX)) {
            $cert = CertificateReserved::getActiveByTypeAndNum($act->test_level_type_id,$certNumber);
            if (!$cert) {
                $error= ActMan::CERT_NOT_AVAILABLE;
                flock($fp, LOCK_UN);
                fclose($fp);
                return false;
            }
            $this->setBlank($cert);

        } else {
            $error= ActMan::CERT_CANT_LOCK;
            flock($fp, LOCK_UN);
            fclose($fp);

            return false;
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }


    protected function setBlank(CertificateReserved $cert)
    {
//        $this->blank_number = $cert->number;
        $old_man=ActMan::getByID($this->old_man_id);

        if (empty($old_man->blank_date) || $old_man->blank_date == '0000-00-00') {
            $old_man->blank_date = $old_man->getAct()->getPrintDateAfterCheckDate();
            /*$now = new DateTime();
            $checkDate = new DateTime($old_man->getAct()->check_date);
            if($now>=$checkDate)
            {
                $old_man->blank_date = date('Y-m-d');
            }
            else{
                $old_man->blank_date = $checkDate->format('Y-m-d');
            }*/
//            $old_man->blank_date = date('Y-m-d');
            $old_man->setValidTill();
            $old_man->save();
        }
        $this->dubl_cert_id = $cert->id;
        $cert->used = 1;

        $duplicate_man = new CertificateDuplicate();

        $duplicate_man->surname_rus=$this->getSurnameRus();
        $duplicate_man->surname_lat=$this->getSurnameLat();
        $duplicate_man->name_rus=$this->getNameRus();
        $duplicate_man->name_lat=$this->getNameLat();
        $duplicate_man->user_id=$this->old_man_id;
        $duplicate_man->certificate_id=$this->dubl_cert_id;
        $duplicate_man->certificate_number=$cert->number;
        $duplicate_man->request_file_id=$this->file_request_id;
        $duplicate_man->passport_file_id=$this->file_passport_id;
        $duplicate_man->personal_data_changed=$this->is_changed;

       /*$now = new DateTime();
        $checkDate = new DateTime($old_man->getAct()->check_date);
        if ($now >= $checkDate) {
            $certificate_issue_date = date('Y-m-d H:i:s');
        } else {
            $certificate_issue_date = $checkDate->format('Y-m-d') . ' ' . date('H:i:s');
        }
*/
        $certificate_issue_date = $old_man->getAct()->getPrintDateAfterCheckDate(null,true);

        $duplicate_man->certificate_issue_date=$certificate_issue_date;
        $duplicate_man->certificate_print_date=$old_man->blank_date;
        $duplicate_man->save();
        $this->save();
        $cert->save();
//        CertificateUsed::add($this);







        return true;
    }



    private function getLockPath($act)
    {
        return $_SERVER['DOCUMENT_ROOT'] . "/locks/certificate_blank_" . CURRENT_HEAD_CENTER . '_' . $act->test_level_type_id;
    }





    public function getCertBlank()
    {
//        if ($this->document == self::DOCUMENT_CERTIFICATE && !empty($this->blank_id)) {
            if (!empty($this->dubl_cert_id)) {
//            return CertificateReserved::getByID($this->blank_id);
            return CertificateReserved::getByID($this->dubl_cert_id);
        }

        return null;
    }


    protected function emptyBlankFields()
    {
        $this->dubl_cert_id = null;
        CertificateDuplicate::getByUserID($this->id);
        /* и остальное дописать */
    }


    public function invalidateBlank($reason)
    {

//        $act = $this->getAct();


        $oldCert = $this->getCertBlank();
        if ($oldCert) {
            $oldCert->invalid = 1;
            $oldCert->save();
        }
        CertificateInvalid::dubl_add($this, $oldCert, $reason);

        $this->emptyBlankFields();

        $this->save();

        return true;
    }





}