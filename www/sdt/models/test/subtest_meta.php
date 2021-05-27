<?php

class SubTestMetas extends ArrayObject
{

    static public function getByGroup($group_id)
    {
        $list = new self();
        $sql = sprintf('select * from  ' . SubTestMeta::TABLE . ' where group_id = %d order by num', $group_id);
        $con = Connection::getInstance();
        $result = $con->query($sql);
        foreach ($result as $row) {
            $list[] = new SubTestMeta($row);
        }

        return $list;
    }
}

class SubTestMeta extends Model
{
    const TABLE = 'subtest_meta';
    private static $_cache = array();

    public $level_id;
    public $num;
    public $group_id;
    public $percent_show;
    public $pass_score;
    public $formula_var;
    public $vedomost_caption;
    protected $_table = self::TABLE;

    /* public function __construct($input = false)
     {
         parent::__construct($input);

     }*/
    /**
     * @param $level_id
     * @param $num
     * @return self|null
     */
    static function getByLevelNum($level_id, $num)
    {

        $cacheKey = $level_id . '_' . $num;
//        if (!is_numeric($id)) {
//            return false;
//        }
        if (!array_key_exists($cacheKey, self::$_cache)) {
            $con = Connection::getInstance();

            $sql = sprintf('select * from ' . self::TABLE . ' where level_id = %d and num = %d', $level_id, $num);
            $result = $con->queryOne($sql);
            if (!$result) return null;

            self::$_cache[$cacheKey] = new self($result);
        }

        return self::$_cache[$cacheKey];
    }

    /**
     * @return bool|SubTestGroup
     */
    public function getGroup()
    {
        return SubTestGroup::getByID($this->group_id);
    }


    public function setFields()
    {
        $this->fields = array(
            'level_id',
            'num',
            'group_id',
            'percent_show',
            'pass_score',
            'formula_var',
            'vedomost_caption',
        );

    }

    public function getEditFields()
    {
        return array(
            'level_id',
            'num',
            'group_id',
            'percent_show',
            'pass_score',
            'formula_var',
            'vedomost_caption',
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();

    }

    public function getFkFields()
    {
        return array();
    }

    public function setTranslate()
    {
        $this->translate = array();
    }

    public function save()
    {
        throw new LogicException('save not implemented');
//        parent::save();
    }


}