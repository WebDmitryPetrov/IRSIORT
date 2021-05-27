<?php
//require_once 'Model.php';

require_once 'university.php';
require_once 'university_dogovor.php';
require_once 'test_levels.php';
require_once 'act.php';
require_once 'act_test.php';
require_once 'act_people.php';
require_once 'act_signing.php';
require_once 'country.php';
require_once 'otchet.php';
require_once 'otchet/country.php';
require_once 'files.php';
require_once 'head_center.php';
require_once 'head_center_text.php';
require_once 'user.php';
require_once 'groups.php';
require_once 'user_type.php';
require_once 'test_levels_changed_price.php';
require_once 'sub_test.php';
require_once 'sub_test_result.php';
require_once 'test_level_type.php';
require_once 'fms_regions.php';
require_once 'fms_regions_users.php';
require_once 'certificate_duplicate.php';
require_once 'special_prices.php';
require_once 'reports.php';
require_once 'regions.php';
require_once 'federal_dc.php';
require_once 'Dubl\DublAct.php';
require_once 'Dubl\DublActMan.php';
require_once 'annul_certs.php';
require_once 'certificate_invalid.php';

require_once __DIR__ . '/Center/center_signing.php';
require_once 'Session.php';
require_once 'ActSummaryTable.php';
require_once 'html_act_files.php';
require_once 'Certificate/CertificateReserved.php';
require_once 'head_org.php';



abstract class Model
{
    public $id;
    protected $_table;

    protected $fields = array();
    protected $translate = array();
    protected $errors = array();
    protected $fieldTypes = array();


    public function __construct($input=false)
    {
        $this->setFields();
        $this->setDefaultFieldTypes();
        $this->setTranslate();
        $this->parseParameters($input);


    }

    abstract public function setFields();

    public function getFields()
    {
        return $this->fields;
    }

    public function setTranslate()
    {
        $this->translate = array();
    }

    public function parseParameters($parameters = false)
    {

        if (!is_array($parameters)) {
            return false;
        }
        foreach ($parameters as $key => $value) {
            $this->assignParameter($key, $value);
        }
        return true;

    }

    protected function getRequestFields()
    {
        return array();
    }


    protected function assignParameter($name, $value)
    {

        if (in_array($name,
            array_merge($this->fields, $this->getRequestFields())
        )) {

//if($name=='blank_date'){}
//            var_dump(array_key_exists($name, $this->fieldTypes), $this->fieldTypes[$name] == 'date', )
            if (array_key_exists($name, $this->fieldTypes) && $this->fieldTypes[$name] == 'date' && preg_match(
                    '|^\d+\.\d+\.\d+$|',
                    $value
                )
            ) {
                $date = new DateTime($value);
                $value = $date->format('Y-m-d');

            }

            $setters = $this->populateSetters();
            if (array_key_exists($name, $setters)) {
                $value = call_user_func(array($this, $setters[$name]), $value);
            }

        }
        $this->setObjectValue($name, $value);

    }

    private function setObjectValue($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            $this->values[$name] = $value;
        }
    }

    public function getTranslate($name)
    {
        if (array_key_exists($name, $this->translate)) {
            return $this->translate[$name];
        }

        return $name;
    }

    public function getError($name)
    {
        if (array_key_exists($name, $this->errors)) {
            return implode(', ', $this->errors[$name]);
        }

        return '';
    }

    public function setError($name, $error)
    {
        if (!array_key_exists($name, $this->errors)) {
            $this->errors[$name] = array();
        }
        array_push($this->errors[$name], $error);
    }

    protected function setDefaultFieldTypes()
    {
        $this->setFieldsTypes();
        $fields = $this->getFields();
        foreach ($fields as $field) {
            if (!array_key_exists($field, $this->fieldTypes)) {
                $this->fieldTypes[$field] = 'string';
            }
        }
    }

    abstract protected function setFieldsTypes();

    abstract public function getEditFields();

    public function getFormFields()
    {
        return $this->getEditFields();
    }

    public function getFkFields()
    {
        return array();
    }

    public function getSaveFields()
    {
        return array_unique(array_merge($this->getEditFields(), $this->getFkFields()));
    }

    public function getFieldTypes()
    {

        return $this->fieldTypes;
    }

    public function getFieldType($field)
    {
        $this->setDefaultFieldTypes();

        return $this->fieldTypes[$field];
    }

    public function save()
    {
        $pk = $this->primary;
        if (!is_null($this->$pk)) {

            return $this->update();
        } else {
            return $this->insert();
        }
    }

    protected function insert()
    {

        $sql = 'insert into ' . $this->_table . ' (' . implode(', ', $this->getSaveFields()) . ') values ';
        $addFilds = array();
        $pk=$this->getPrimaryKey();
        //var_dump($this);
        foreach ($this->getSaveFields() as $field) {

            //  $addFilds[] = "'" . mysql_real_escape_string($this->$field) . "'";
            //  var_dump($field.':',$this->$field);
            $addFilds[] = "'" . mysql_real_escape_string($this->$field) . "'";
//            if (!is_null($this->$field)) {
//                $addFilds[] = "'" . mysql_real_escape_string($this->$field) . "'";
//            } else {
//                //var_dump($field.':',$this->$field);
//                $addFilds[] = 'null';
//            }
        }
        //var_dump($addFilds);
        $sql .= '(' . implode(', ', $addFilds) . ')';
        //var_dump($sql);
        mysql_query($sql) or die(mysql_error());
        $id = mysql_insert_id();
        //die($this->_table);
        if ($this->_table=='sdt_head_center')
        {
            $sql='INSERT INTO `head_center_text` (`head_id`) VALUES ('.$id.');';
            mysql_query($sql) or die(mysql_error());
        }
        // echo $id;die();
        $this->$pk = $id;

        return $this->$pk;

    }

    protected function update()
    {
        $sql = 'update  ' . $this->_table . ' set ';
        $addFilds = array();
        //  var_dump($this->getSaveFields()); die();
        foreach ($this->getSaveFields() as $field) {
            if($field==$this->getPrimaryKey()) continue;

            $addFilds[] = '`' . $field . "` = '" . mysql_real_escape_string($this->$field) . "'";

          /*  if (!is_null($this->$field)) {
                $addFilds[] = '`' . $field . "` = '" . mysql_real_escape_string($this->$field) . "'";
            } else {
                //var_dump($field.':',$this->$field);
                $addFilds[] = '`' . $field . "` = null ";
            }*/

        }

        $sql .= '' . implode(', ', $addFilds) . '';
        $pk=$this->getPrimaryKey() ;
        $sql .= ' where ' . $this->getPrimaryKey() . '=' . $this->$pk;


//        die(var_dump($sql));
//        var_dump($sql);
        mysql_query($sql);

        //  var_dump(mysql_affected_rows());
        return $this->$pk;

    }

    public function delete()
    {
        $pk=$this->getPrimaryKey() ;
        $sql = 'update  ' . $this->_table . ' set deleted = 1  where '.$pk.'=' . $this->$pk;
        mysql_query($sql);

        return mysql_affected_rows();
    }

    public function getTable()
    {
        return $this->_table;
    }

    protected $availableValues = null;

    protected function setAvailableValues()
    {
        $this->availableValues = array();
    }

    public function getAvailableValues($field)
    {
        if (is_null($this->availableValues)) {
            $this->setAvailableValues();
        }

        if (array_key_exists($field, $this->availableValues)) {
            return $this->availableValues[$field];
        }

        return null;
    }

    protected $primary = 'id';

    protected function getPrimaryKey()
    {
        return $this->primary;
    }

    protected function populateSetters()
    {
        return array();
    }

    public function isDeleted()
    {

        if (property_exists($this, 'deleted') && $this->deleted) {
            return true;
        }

        return false;
    }

    public function getValue($name)
    {
        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        return null;
    }
}