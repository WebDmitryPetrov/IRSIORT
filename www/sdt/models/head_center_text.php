<?php

class HeadCentersText extends ArrayObject
{
    protected $_table;

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new Univesities();
        $sql = 'select * from head_center_text where deleted=0 order by name';
        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new University($row);
        }

        return $list;
    }


}

class HeadCenterText extends Model
{

    protected $_table = 'head_center_text';

    public $id;
    public $head_id;
    public $short_ip;
    public $short_vp;
    public $okved;
    public $okpo;
    public $okato;
    public $oktmo;
    public $ogrn;
    public $legal_address_1;
    public $check_receiver;
    public $check_n;
    public $bik;
    public $bank_1;
    public $bank_2;
    public $bank_3;
    public $bank_inn;
    public $bank_kpp;
    public $bank_kbk;
    public $address_1;
    public $long_ip;
    public $middle_ip;
    public $long_tp_print_act;
    public $our_short_name;
    public $our_full_name;
    public $help_caption;
    public $help_phone;
    public $help_email;
    public $rki_rudn_form;
    public $rki_rudn_name;
    public $cert_pril_rudn_form;
    public $cert_pril_rudn_name;
    public $long_tp_print_act_new;
    public $signing_center_name;
    public $signing_short_center_name;
    public $certificate_city;


    public function __construct($input = false)
    {
        parent::__construct($input);


    }

    /**
     * @param $id
     * @return bool|HeadCenterText
     */
    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from head_center_text where deleted=0 and id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    /**
     * @param $id
     * @return bool|HeadCenterText
     */
    static function getByHeadCenterID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from head_center_text where deleted=0 and head_id=\'' . mysql_real_escape_string($id) . '\'';
//        die($sql);
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'head_id',
            'short_ip',
            'short_vp',
            'okved',
            'okpo',
            'okato',
            'oktmo',
            'ogrn',
            'legal_address_1',
            'check_receiver',
            'check_n',
            'bik',
            'bank_1',
            'bank_2',
            'bank_3',
            'bank_inn',
            'bank_kpp',
            'bank_kbk',
            'address_1',
            'long_ip',
            'middle_ip',
            'long_tp_print_act',
            'our_short_name',
            'our_full_name',
            'help_caption',
            'help_phone',
            'help_email',
            'rki_rudn_form',
            'rki_rudn_name',
            'cert_pril_rudn_form',
            'cert_pril_rudn_name',
            'long_tp_print_act_new',
            'signing_center_name',
            'signing_short_center_name',
            'certificate_city',
        );
    }

    public function getEditFields()
    {
        return array(
            'short_ip',
            'short_vp',
            'okved',
            'okpo',
            'okato',
            'oktmo',
            'ogrn',
            'legal_address_1',
            'check_receiver',
            'check_n',
            'bik',
            'bank_1',
            'bank_2',
            'bank_3',
            'bank_inn',
            'bank_kpp',
            'bank_kbk',
            'address_1',
            'long_ip',
            'middle_ip',
            'long_tp_print_act',
            'our_short_name',
            'our_full_name',
            'help_caption',
            'help_phone',
            'help_email',
            'rki_rudn_form',
            'rki_rudn_name',
            'cert_pril_rudn_form',
            'cert_pril_rudn_name',
            'long_tp_print_act_new',
            'signing_center_name',
            'signing_short_center_name',
            'certificate_city',
        );
    }

    public function getFkFields()
    {
        return array();
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => '�������������',
            'short_ip' => '�������� �������� � ������������ �����',
            'short_vp' => '�������� �������� � ����������� �����',
            'okved' => '�����',
            'okpo' => '����',
            'okato' => '�����',
            'oktmo' => '�����',
            'ogrn' => '����',
            'legal_address_1' => '����������� ����� � ���������',
            'check_receiver' => '���������� �����',
            'check_n' => '����� �����',
            'bik' => '���',
            'bank_1' => '�������� ����� � �������',
            'bank_2' => '�������� ����� �������� �������',
            'bank_3' => '����� � ����� �������� �������',
            'bank_inn' => '��� �����',
            'bank_kpp' => '��� �����',
            'bank_kbk' => '��� �����',
            'address_1' => '����� ��� ��������',
            'long_ip' => '������� �������� � ������������ ������',
            'middle_ip' => '������� �������� � ������������ ������',
            'long_tp_print_act' => '������� �������� � ���������� <br> ��� ������ � ���� � ������������ ������',
            'our_short_name' => '�������� �������� ��� ������ ����������� ��� ����������',
            'our_full_name' => '�������� ��� ������ �����������',
            'help_caption' => '��� � ���� ���������� ��� ���������� ��������� ������� �������',
            'help_phone' => '������� � ���� ���������� ��� ���������� ��������� ������� �������',
            'help_email' => '����� � ���� ���������� ��� ���������� ��������� ������� �������',
            'rki_rudn_form' => '������� ��� ����� ������������',
            'rki_rudn_name' => '������� ��� �������� ������������',
            'cert_pril_rudn_form' => '���������� ����������� ����� ������������',
            'cert_pril_rudn_name' => '���������� ����������� �������� ������������',
            'long_tp_print_act_new' => '�������� ���� ��� ������ ����',
            'signing_center_name' => '�������� ������ (������� ���, ���������� � �����������)',
            'signing_short_center_name' => '�������� �������� ������ (������ ������ ������������)',
            'certificate_city' => '�����, ������������ � ������������',
        );
    }


    public function getHeadCenter()
    {
        if ($this->head_id) {
            return HeadCenter::getByID($this->head_id);
        }

        return new stdClass();
    }




}