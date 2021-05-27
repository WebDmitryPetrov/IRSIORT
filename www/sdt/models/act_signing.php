<?php
class ActSignings extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new self();
        $sql = 'select * from sdt_signing  where deleted=0 and head_id=' . CURRENT_HEAD_CENTER;
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActSigning($row);
        }

        return $list;
    }

    static public function get4Invoice()
    {
        $list = new self();
        $sql = 'select * from sdt_signing  where invoice=1 and deleted=0 and head_id=' . CURRENT_HEAD_CENTER;
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActSigning($row);
        }

        return $list;
    }

    static public function get4Certificate()
    {
        $list = new self();
        $sql = 'select * from sdt_signing  where certificate=1 and deleted=0 and head_id=' . CURRENT_HEAD_CENTER;
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActSigning($row);
        }

        return $list;
    }


    public static function get4VidachaCertFirst()
    {
        $list = new self();
        $sql = 'select * from sdt_signing  where aprove_vidacha_cert = 1 and head_id=' . CURRENT_HEAD_CENTER. ' order by deleted asc ';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            return new ActSigning($row);
        }

        return false;
    }


    public static function get4Act()
    {
        $list = new self();
        $sql = 'select * from sdt_signing  where act=1 and deleted=0 and head_id=' . CURRENT_HEAD_CENTER;
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActSigning($row);
        }

        return $list;
    }


    public static function get4VidachaCert()
    {
        $list = new self();
        $sql = 'select * from sdt_signing  where vidacha_cert=1 and deleted=0  and head_id=' . CURRENT_HEAD_CENTER;
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new ActSigning($row);
        }

        return $list;
    }
}

class ActSigning extends Model
{
    protected $_table = 'sdt_signing';

    public $id;
    public $caption;
    public $position;
    public $invoice;
    public $act;
    public $certificate;
    public $vidacha_cert;
    public $aprove_vidacha_cert;

    public $head_id;


    public function setCheckboxes(){
        $C=Controller::getInstance();
        $_POST['invoice']=$C->postNumeric('invoice');
        $_POST['certificate']=$C->postNumeric('certificate');
        $_POST['vidacha_cert']=$C->postNumeric('vidacha_cert');
        $_POST['aprove_vidacha_cert']=$C->postNumeric('aprove_vidacha_cert');
        $_POST['act']=$C->postNumeric('act');
    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from sdt_signing where deleted=0 and id=\'' . mysql_real_escape_string(
                $id
            ) . '\' and head_id=' . CURRENT_HEAD_CENTER;
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return new self();
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'caption',
            'position',
            'invoice',
            'certificate',
            'act',
            'aprove_vidacha_cert',
//            'name_of_center',
//            'short_name',
            'vidacha_cert',
            'head_id'
        );
    }

    public static function getEditForm()
    {
        return array(
            'caption' => 'ФИО',
            'position' => 'Должность',
//            'name_of_center' => 'Название центра',
//            'short_name' => 'Короткое название центра',
            'invoice' => array(
                'type' => 'checkbox',
                'translate' => 'Подписывает счета',
            ),
            'certificate' => array(
                'type' => 'checkbox',
                'translate' => 'Подписывает сертификаты',
            ),
            'vidacha_cert' => array(
                'type' => 'checkbox',
                'translate' => 'Подписывает: Приложение к сертификату, Справка,<br> Ведомости выдачи, Реестр выдачи',
            ), 'act' => array(
                'type' => 'checkbox',
                'translate' => 'Подписывает акты',
            ), 'aprove_vidacha_cert' => array(
                'type' => 'checkbox',
                'translate' => 'Утверждает ведомость выдачи сертификатов и справок',
            ),


        );
    }

    public function getEditFields()
    {
        return array(

            'caption',
            'position',
            'invoice',
            'certificate',
//            'name_of_center',
//            'short_name',
            'vidacha_cert',
            'act',
            'aprove_vidacha_cert',
        );
    }

    public function getFkFields()
    {
        return array('head_id');
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

    public function setTranslate()
    {
        $this->translate = array();
    }


}