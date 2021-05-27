<?php

class PrintNumberList extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);
    }

    public static function generate(
        $doc_type,
        $who,
        $prefix='',
        $increment = 1
    )
    {
        $filname = $_SERVER['DOCUMENT_ROOT'] . '/locks/print_number_' . $who.'_'.$doc_type;


        $fp = fopen($filname, "w+");

        if (flock($fp, LOCK_EX)) {
//            $prefix = '';

            $number = self::generateNumber($who,$doc_type,$prefix,$increment);
            if (!$number) {
                flock($fp, LOCK_UN);
                fclose($fp);
                return false;
            }

            $result = new PrintNumber();
            $result->number = $number;
            $result->doc_type = $doc_type;
            $result->prefix = $prefix;
            $result->who = $who;
            $result->user_id = Session::getUserID();
            $result->save();

            flock($fp, LOCK_UN);
            fclose($fp);

            return $result;
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return null;
    }

    private static function generateNumber($who, $doc_type, $prefix, $increment = 1, $format = '%d')
    {

        $C = Connection::getInstance();

        $sql = 'select 
      max(number) as mx from ' . PrintNumber::TABLE . "
         where doc_type ='" . $C->escape($doc_type) . "'
         and who ='" . $C->escape($who) . "'
            and prefix = '" . $C->escape($prefix)."'";


        $result = $C->queryOne($sql);

        if (!$result) return $result;

        $num = $result['mx'];
//        var_dump($num);
        $str = $num + $increment;

//        die($sql.$str);
        $sprintf = sprintf($format, $str);

//        var_dump($sprintf);
        return $sprintf;

    }


}

class PrintNumber extends Model
{


    const TABLE = 'print_numbers';
    public $id;
    public $number;
    public $who;
    public $user_id;
    public $doc_type;
    public $prefix;
    protected $_table = self::TABLE;

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
            'who',
            'doc_type',
            'user_id',
            'prefix',


        );
    }

    public function getEditFields()
    {
        return array(
            'id',
            'number',
            'who',
            'doc_type',
            'user_id',
            'prefix',
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();

    }


    public function getNumber()
    {
        return $this->prefix . $this->number;
    }

}