<?php

class DocumentNumberList extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);
    }

    public static function generate(
        $doc_type,
        $test_type_id,
        $head_center_id,
        $certificate_prefix = '0000',
        $note_prefix = '',
        $increment = 1
    ) {
        $prefix = '';
        switch ($doc_type) {
            case ActMan::DOCUMENT_CERTIFICATE:
                $prefix = $certificate_prefix;
                $number = self::generateCertificate($test_type_id, $doc_type, $prefix, $increment);
                break;

            case ActMan::DOCUMENT_NOTE:
                $prefix = $note_prefix;
                $number = self::generateNote($prefix, $doc_type, $increment);
                break;

            default:
                $number = null;
        }

        if (!$number) {
            return false;
        }

        $result = new DocumentNumber();
        $result->number = $number;
        $result->doc_type = $doc_type;
        $result->prefix = $prefix;
        $result->head_center_id = $head_center_id;
        $result->test_type_id = $test_type_id;

        return $result;
    }

    private static function generateCertificate($test_type_id, $doc_type, $prefix, $increment)
    {
        $sql = 'select max(number) as mx from ' . DocumentNumber::TABLE . "
         where doc_type ='" . mysql_real_escape_string($doc_type) . "'
            and prefix = '" . mysql_real_escape_string($prefix) . "'
            and  test_type_id=" . intval(
                $test_type_id
            );
//        die($sql);
        $res = mysql_query($sql);

        if (!mysql_num_rows($res)) {
//            die($sql.var_dump(func_get_args()));
            return null;
        }

        $str = intval(mysql_result($res, 0)) + $increment;

//        die($sql.$str);
        return sprintf('%08s',  $str);

    }

    private static function generateNote($prefix, $doc_type, $increment)
    {
        $sql = 'select max(number) as mx
      from ' . DocumentNumber::TABLE . " where doc_type ='" . mysql_real_escape_string($doc_type) . "' and
         prefix like '" . mysql_real_escape_string($prefix) . "'";
        $res = mysql_query($sql);
        if (!mysql_num_rows($res)) {
            return null;
        }

        $str = intval(mysql_result($res, 0)) + $increment;

        return sprintf('%08s', $str);
    }


}

class DocumentNumber extends Model
{


    const TABLE = 'document_numbers_used';
    protected $_table = self::TABLE;
    public $id;
    public $number;
    public $test_type_id;
    public $head_center_id;
    public $doc_type;
    public $prefix;

    static function lock()
    {
        $sql = 'lock tables ' . self::TABLE . ' WRITE ';
        mysql_query($sql);
    }

    static function unlock()
    {
        $sql = 'unlock tables';
        mysql_query($sql);
    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from ' . self::TABLE . ' where id=' . intval($id);
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
            'number',
            'test_type_id',
            'head_center_id',
            'doc_type',
            'prefix',


        );
    }

    public function getEditFields()
    {
        return array(
            'id',
            'number',
            'test_type_id',
            'head_center_id',
            'doc_type',
            'prefix',
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
//            'testing_date' => 'date',
//            'invoice_date' => 'date',
//            'birth_date' => 'date',
//            'blank_date' => 'date',
//            'valid_till' => 'date',
//            'comment' => 'text'

        );

    }


    public function getNumber()
    {
        return $this->prefix . $this->number;
    }

}