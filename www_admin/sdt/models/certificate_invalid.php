<?php

class CertificatesInvalid extends ArrayObject
{
    protected $_table;

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {

//        $list = new HeadCenters();
        $list = [];

//        $sql = 'select * from sdt_head_center where deleted=0 and id <> 6 order by horg_id, name';
        $sql = 'SELECT ci.*,hc.short_name FROM certificate_invalid ci 

      left join certificate_reserved cr on cr.id=ci.certificate_id
      left join sdt_head_center hc on hc.id=cr.head_center_id
      ORDER BY ci.ts DESC';

        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
//            $list[] = new CertificateInvalid($row);
            $list[] = $row;
        }

        return $list;
    }


}




class CertificateInvalid extends Model
{


    protected $_table = 'certificate_invalid';
    public $id;

    public $certificate_id;

    public $user_id;
    public $number;
    public $test_type_id;

    public $reason;
    public $blank_date;

    public $object_type;

    public static function add(ActMan $man, CertificateReserved $certificateReserved = null, $reason = null)
    {
        $o = new self();


        $o->user_id = $man->id;
        if (!empty($certificateReserved)) {
            $o->certificate_id = $certificateReserved->id;
        }
        $o->number = $man->blank_number;
        $o->test_type_id = $man->getAct()->test_level_type_id;
        $o->reason = $reason;
        $o->blank_date = $man->blank_date;
        $o->object_type = 'man';

        return $o->save();


    }

    public static function dubl_add(DublActMan $man, CertificateReserved $certificateReserved = null, $reason = null)
    {
        $o = new self();

        $old_man = ActMan::getByID($man->old_man_id);

        $o->user_id = $man->id;
        if (!empty($certificateReserved)) {
            $o->certificate_id = $certificateReserved->id;
        }
        $o->number = $certificateReserved->number;
        $o->test_type_id = $old_man->getAct()->test_level_type_id;
        $o->reason = $reason;
        $o->blank_date = $old_man->blank_date;
        $o->object_type = 'dubl';

        return $o->save();


    }

    public static function isCertificateInvalid($number, $type)
    {
        $sql = "select * from certificate_invalid WHERE test_type_id = " . intval(
                $type
            ) . " AND number = '" . mysql_real_escape_string($number) . "'";


        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return null;
        }
        return true;
    }

    public static function add_cert_reserved(CertificateReserved $certificateReserved = null, $reason = null)
    {
        $o = new self();

        if (empty($certificateReserved)) return false;


            $o->certificate_id = $certificateReserved->id;
            $o->user_id = $certificateReserved->id;

        $o->number = $certificateReserved->number;
        $o->test_type_id = $certificateReserved->test_type_id;
        $o->reason = $reason;

        $o->object_type = 'cert_reserved';

        return $o->save();


    }


    public function setFields()
    {
        $this->fields = array(
            'id',
            'user_id',
            'certificate_id',
            'number',
            'test_type_id',
            'reason',
            'blank_date',
            'object_type',


        );
    }

    protected function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }


    public function getEditFields()
    {
        return array(

            'user_id',
            'certificate_id',
            'number',
            'test_type_id',
            'reason',
            'blank_date',
            'object_type',

        );
    }




}