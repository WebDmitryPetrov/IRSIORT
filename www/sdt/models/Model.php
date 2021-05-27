<?php


abstract class Model
{
    public $id;
    protected $_table;

    protected $fields = array();
    protected $translate = array();
    protected $errors = array();
    protected $fieldTypes = array();

    protected $con;

    protected $values;
    /**
     * @var array
     */
    private $validateErrors = [];

    public function __construct($input = false)
    {
        $this->setFields();
        $this->setDefaultFieldTypes();
        $this->setTranslate();
        $this->parseParameters($input);
        $this->con = Connection::getInstance();

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

    public function setValidateErrors(array $errors = [])
    {
        $this->validateErrors = $errors;
    }

    public function getValidateError($field)
    {
        return isset($this->validateErrors[$field]) ? $this->validateErrors[$field] : null;
    }

    protected function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

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
        if (empty($this->_table)) {
            throw new LogicException('В модели ' . get_class($this) . ' не указана таблица');
        }
        $pk = $this->primary;
        if (!is_null($this->$pk)) {

            return $this->update();
        } else {
            return $this->insert();
        }
    }

    protected function beforeSave()
    {
        return array();
    }


    protected function getFieldValue($field)
    {
        if (array_key_exists($field, $this->beforeSave())) {
            $callbacks = $this->beforeSave();
            $callback = $callbacks[$field];
            if (method_exists($this, $callback)) {
                return $this->$callback();
            }


        }

        return $this->$field;

    }

    protected function insert()
    {

        $sql = 'INSERT INTO ' . $this->_table . ' (' . implode(', ', $this->getSaveFields()) . ') VALUES ';
        $addFilds = array();
        $pk = $this->getPrimaryKey();
        foreach ($this->getSaveFields() as $field) {
            $fieldValue = $this->getFieldValue($field);
            $addFilds[] = "'" . mysql_real_escape_string($fieldValue) . "'";
            /*  if (!is_null($fieldValue)) {


              } else {
                  $addFilds[] = 'null';
              }*/
        }
        $sql .= '(' . implode(', ', $addFilds) . ')';
//        var_dump($sql);
        $res = mysql_query($sql);
        if (!$res) {
            trigger_error('Model::insert(): ' . mysql_error() . ' table: ' . self::getTable(), E_USER_WARNING);
            throw new MysqlException(mysql_error());

        }
        $id = mysql_insert_id();
        $this->$pk = $id;

        return $this->$pk;

    }

    protected function update()
    {
        $sql = 'update  ' . $this->_table . ' set ';
        $addFilds = array();
        //  var_dump($this->getSaveFields()); die();
        foreach ($this->getSaveFields() as $field) {
            if ($field == $this->getPrimaryKey()) {
                continue;
            }
            $addFilds[] = '`' . $field . "` = '" . mysql_real_escape_string($this->getFieldValue($field)) . "'";
//
//            if (!is_null($field)) {
//                $addFilds[] = '`' . $field . "` = '" . mysql_real_escape_string($this->getFieldValue($field)) . "'";
//            } else {
//                $addFilds[] = 'null';
//            }
        }

        $sql .= '' . implode(', ', $addFilds) . '';
        $pk = $this->getPrimaryKey();
        $sql .= ' where ' . $this->getPrimaryKey() . '=' . $this->$pk;


//        die(var_dump($sql));
        $res = mysql_query($sql);

        if (!$res) {
            trigger_error('Model::update(): ' . mysql_error(), E_USER_WARNING);
            throw new MysqlException(mysql_error());

        }

        //  var_dump(mysql_affected_rows());
        return $this->id;

    }

    public function delete()
    {
        $pk = $this->getPrimaryKey();
        $sql = 'update  ' . $this->_table . ' set deleted = 1  where ' . $pk . '=' . $this->$pk;
        mysql_query($sql);

//var_dump($this->_table, static::getTable());die;

        $rows = mysql_affected_rows();


        return $rows;
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

    /**
     * Можно перечислить список название поля - функция.
     * Эти функции будут вызваны при определения пля
     *
     * @return array
     */
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