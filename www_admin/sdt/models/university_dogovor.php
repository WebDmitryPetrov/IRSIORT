<?php

class Univesity_dogovors extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new Univesity_dogovors();
        $sql = 'SELECT * FROM sdt_university_dogovor WHERE deleted=0  ORDER BY university_id,number,caption';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new University_dogovor($row);
        }

        return $list;
    }

    static public function getByUniversity($university_id)
    {
        $university_id = intval($university_id);
        $list = new Univesity_dogovors();
        $sql = 'SELECT * FROM sdt_university_dogovor WHERE deleted=0 AND university_id=\'' .
            $university_id
            . '\'  ORDER BY valid_date DESC ,number,caption';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new University_dogovor($row);
        }

        return $list;
    }

    public static function getByUniversityType($university_id, $test_level_type_id)
    {
        $university_id = intval($university_id);
        $test_level_type_id = intval($test_level_type_id);

        $list = new Univesity_dogovors();
        $sql = 'SELECT * FROM sdt_university_dogovor WHERE
              deleted=0 AND university_id=' . $university_id . '
              AND (type_id IS NULL OR type_id = ' . $test_level_type_id . ')
              AND (valid_date IS NULL OR valid_date = \'0000-00-00\' OR valid_date>=CURDATE())
              ORDER BY valid_date DESC ,number,caption';
//        echo $sql;
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new University_dogovor($row);
        }

        return $list;
    }

}

class University_dogovor extends Model
{
    public $id;
    public $number;
    public $date;
    public $caption;
    public $university_id;
    public $scan_id;

    public $type_id;
    public $valid_date;

    public $print_act;
    public $print_protocol;

    public $document_type;
    protected $_table = 'sdt_university_dogovor';

    const DOGOVOR_PRINT_PROTOCOL = 'protocol';

    const DOGOVOR_PRINT_ACT = 'act';

    public function __construct($input = false)
    {
        parent::__construct($input);


//var_dump($this);die;
        if ($this->type_id == 2) {
            if ($this->print_protocol) {
                $this->document_type = self::DOGOVOR_PRINT_PROTOCOL;
            } elseif ($this->print_act) {
                $this->document_type = self::DOGOVOR_PRINT_ACT;
            }
        } else {
            $this->print_act = 0;
            $this->print_protocol = 0;
        }

    }

    protected function getRequestFields()
    {
        return array(
            'document_type',
        );
    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT * FROM sdt_university_dogovor WHERE id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    public function isPrintAct()
    {
        return $this->type_id == 1
            || ($this->type_id == 2 && $this->print_act)
            || (!$this->print_act && !$this->print_protocol);

    }

    public function isPrintProtocol()
    {
        return $this->type_id == 1
            || ($this->type_id == 2 && $this->print_protocol)
            || (!$this->print_act && !$this->print_protocol);

    }

    public function setDocumentType($value)
    {
//        die(var_dump($value));
        switch ($value) {
            case self::DOGOVOR_PRINT_ACT:
                $this->print_act = 1;
                $this->print_protocol = 0;
                break;
            case self::DOGOVOR_PRINT_PROTOCOL:
                $this->print_protocol = 1;
                $this->print_act = 0;
                break;
        }

    }

    protected function populateSetters()
    {
        return array(
            'document_type' => 'setDocumentType',
        );
    }

    public function setFields()
    {
        $this->fields = array('id', 'number', 'date', 'caption', 'university_id', 'scan_id', 'type_id', 'valid_date', 'print_act', 'print_protocol',);

    }

    public function getEditFields()
    {
        return array('number', 'date', 'caption', 'type_id', 'valid_date');
    }

    public function getFormFields()
    {
        return array('number', 'date', 'caption',
            'type_id',
            'document_type',
            'valid_date', [
                'name' => 'scripts',
                'type' => 'include',
                'source' => 'dogovor.php',
            ]);
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(

            'date' => 'date',
            'valid_date' => 'date',
            'type_id' => 'select',
            'document_type' => 'optionbuttons',
        );

    }

    public function getFkFields()
    {
        return array('university_id', 'scan_id',
            'print_act', 'print_protocol',);
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => 'Идентификатор',
            'number' => 'Номер договора',
            'date' => 'Дата договора',
            'caption' => 'Название договора',
            'type_id' => 'Тип договора',
            'valid_date' => 'Дата окончания действия договора',
            'document_type' => 'Тип утвеждаемого документа',
        );
    }

    public function __toString()
    {
        return $this->number . ' ' . date('d.m.Y', strtotime($this->date));

    }

    /**
     * @return bool|File
     */
    public function getScan()
    {
        if ($this->scan_id) {
            return File::getByID($this->scan_id);
        }

        return false;
    }

    public function getType()
    {
        if (!$this->type_id) return null;

        return TestLevelType::getByID($this->type_id);
    }

    protected function setAvailableValues()
    {
        $types = array(
            1 => 'договор по лингводидактическому тестированию',
            2 => 'договор по комплексному экзамену'
        );


        $this->availableValues = array(
            'type_id' => $types,
            'document_type' => [
                self::DOGOVOR_PRINT_ACT => 'только для Акта',
                self::DOGOVOR_PRINT_PROTOCOL => 'только для Сводного протокола'
            ]
        );

    }

}