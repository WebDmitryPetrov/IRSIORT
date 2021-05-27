<?php

class TestLevels extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {
        $list = new TestLevels();
        $sql = 'select * from sdt_test_levels  where deleted=0 order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[$row['id']] = new TestLevel($row);
        }

        return $list;
    }

    static public function getAllGrouped($level_type_sort='asc')
    {
        if ($level_type_sort != 'asc' && $level_type_sort != 'desc') $level_type_sort = 'asc';
        $list = new TestLevels();
        $sql = 'select print from sdt_test_levels  where deleted=0 group by print order by type_id '.$level_type_sort.', id asc';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new TestLevel($row);
        }

        return $list;
    }

    public static function getAvailable4Act(Act $act)
    {
        $meta = $act->getMeta();

        $testGroup = 1;

        if ($meta) {
            $testGroup = $meta->test_group;
        }

        $sqlUsed = 'select COUNT(DISTINCT IF(`level_id`= 1, level_id, NULL)) as migrants,
  COUNT(DISTINCT IF(`level_id` >1, level_id, NULL)) as other
  from sdt_act_test where   act_id= ' . intval($act->id);

        //die($sqlUsed);
        $result_used = mysql_query($sqlUsed) or die(mysql_error());
        $row = mysql_fetch_assoc($result_used);

        $restrict = '';
        if ($row['migrants']) {
            $restrict = ' and id=1';
        }
        if ($row['other']) {
            $restrict = ' and id>1';
        }

        $list = new TestLevels();
        $sql = 'select * from sdt_test_levels  where type_id = ' . intval(
                $act->test_level_type_id
            ) . ' and is_publicated = 1 and deleted=0 ' . $restrict . ' and test_group = ' . intval($testGroup) . ' order by id';
        $result = mysql_query($sql) or die(mysql_error());


        while ($row = mysql_fetch_assoc($result)) {
            $testLevel = new TestLevel($row);
            if ($testLevel->id != 16 && $meta && $meta->special_price_group == 2) continue;
            $list[$row['id']] = $testLevel;
        }

        return $list;
    }

    public static function getByType($id)
    {
        $list = new TestLevels();
        $sql = 'select * from sdt_test_levels  where deleted=0 and type_id=' . intval($id) . ' order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[$row['id']] = new TestLevel($row);
        }

        return $list;
    }
}

class TestLevel extends Model
{
    protected $_table = 'sdt_test_levels';

    public $id;
    public $test_1_maxball;
    public $test_1_caption;
    public $test_1_short_caption;
    public $test_1_full_caption;
    public $test_2_maxball;
    public $test_2_caption;
    public $test_2_short_caption;
    public $test_2_full_caption;
    public $test_3_maxball;
    public $test_3_caption;
    public $test_3_short_caption;
    public $test_3_full_caption;
    public $test_4_maxball;
    public $test_4_caption;
    public $test_4_short_caption;
    public $test_4_full_caption;
    public $test_5_maxball;
    public $test_5_caption;
    public $test_5_short_caption;
    public $test_5_full_caption;
    public $test_6_maxball;
    public $test_6_caption;
    public $test_6_short_caption;
    public $test_6_full_caption;
    public $test_7_maxball;
    public $test_7_caption;
    public $test_7_short_caption;
    public $test_7_full_caption;
    public $test_8_maxball;
    public $test_8_caption;
    public $test_8_short_caption;
    public $test_8_full_caption;
    public $test_9_maxball;
    public $test_9_caption;
    public $test_9_short_caption;
    public $test_9_full_caption;
    public $test_10_maxball;
    public $test_10_caption;
    public $test_10_short_caption;
    public $test_10_full_caption;
    public $test_11_maxball;
    public $test_11_caption;
    public $test_11_short_caption;
    public $test_11_full_caption;
    public $test_12_maxball;
    public $test_12_caption;
    public $test_12_short_caption;
    public $test_12_full_caption;
    public $test_13_maxball;
    public $test_13_caption;
    public $test_13_short_caption;
    public $test_13_full_caption;
    public $test_14_maxball;
    public $test_14_caption;
    public $test_14_short_caption;
    public $test_14_full_caption;
    public $test_15_maxball;
    public $test_15_caption;
    public $test_15_short_caption;
    public $test_15_full_caption;
    public $test_1_pass_score;
    public $test_2_pass_score;
    public $test_3_pass_score;
    public $test_4_pass_score;
    public $test_5_pass_score;
    public $test_6_pass_score;
    public $test_7_pass_score;
    public $test_8_pass_score;
    public $test_9_pass_score;
    public $test_10_pass_score;
    public $test_11_pass_score;
    public $test_12_pass_score;
    public $test_13_pass_score;
    public $test_14_pass_score;
    public $test_15_pass_score;
    public $total;
    public $price;
    public $sub_test_price;
    public $sub_test_price_2;
    public $subtest_count;
    public $valid_duration = null;

    public $is_additional;
    public $available_levels;

    /**
     * @var string
     */
    public $caption;
    public $print;
    public $print_note;
    public $print_act;
    public $print_summary_table;

    public $percent_min;
    public $percent_max;
    public $type_id;
    public $test_group = 1;

    private static $_cache = array();

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        if (!array_key_exists($id, self::$_cache)) {
            $sql = 'select * from sdt_test_levels where id=\'' . mysql_real_escape_string($id) . '\'';
            $result = mysql_query($sql);
            if (!mysql_num_rows($result)) {
                return false;
            }
            self::$_cache[$id] = new TestLevel(mysql_fetch_assoc($result));
        }

        return self::$_cache[$id];
    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'test_1_maxball',
            'test_1_caption',
            'test_1_short_caption',
            'test_1_full_caption',
            'test_2_maxball',
            'test_2_caption',
            'test_2_short_caption',
            'test_2_full_caption',
            'test_3_maxball',
            'test_3_caption',
            'test_3_short_caption',
            'test_3_full_caption',
            'test_4_maxball',
            'test_4_caption',
            'test_4_short_caption',
            'test_4_full_caption',
            'test_5_maxball',
            'test_5_caption',
            'test_5_short_caption',
            'test_5_full_caption',
            'test_6_maxball',
            'test_6_caption',
            'test_6_short_caption',
            'test_6_full_caption',
            'test_7_maxball',
            'test_7_caption',
            'test_7_short_caption',
            'test_7_full_caption',
            'test_8_maxball',
            'test_8_caption',
            'test_8_short_caption',
            'test_8_full_caption',
            'test_9_maxball',
            'test_9_caption',
            'test_9_short_caption',
            'test_9_full_caption',
            'test_10_maxball',
            'test_10_caption',
            'test_10_short_caption',
            'test_10_full_caption',
            'test_11_maxball',
            'test_11_caption',
            'test_11_short_caption',
            'test_11_full_caption',
            'test_12_maxball',
            'test_12_caption',
            'test_12_short_caption',
            'test_12_full_caption',
            'test_13_maxball',
            'test_13_caption',
            'test_13_short_caption',
            'test_13_full_caption',
            'test_14_maxball',
            'test_14_caption',
            'test_14_short_caption',
            'test_14_full_caption',
            'test_15_maxball',
            'test_15_caption',
            'test_15_short_caption',
            'test_15_full_caption',
            'total',
            'print',
            'print_note',
            'print_act',
            'price',
            'caption',
            'sub_test_price',
            'sub_test_price_2',
            'percent_min',
            'percent_max',
            'subtest_count',
            'type_id',
            'test_1_pass_score',
            'test_2_pass_score',
            'test_3_pass_score',
            'test_4_pass_score',
            'test_5_pass_score',
            'test_6_pass_score',
            'test_7_pass_score',
            'test_8_pass_score',
            'test_9_pass_score',
            'test_10_pass_score',
            'test_11_pass_score',
            'test_12_pass_score',
            'test_13_pass_score',
            'test_14_pass_score',
            'test_15_pass_score',
            'valid_duration',
            'is_additional',
            'available_levels',
            'print_summary_table',
            'test_group',

        );
    }

    public function getEditFields()
    {
        $result = array(
            'caption',
            'price',
            'print',
            'print_note',
            'print_act',
            'sub_test_price',
            'sub_test_price_2',
            'percent_min',
            'percent_max',
            'valid_duration',
            'is_additional',
            'available_levels',
            'print_summary_table',
            'test_group',

        );

        for ($i = 1; $i <= $this->subtest_count; $i++) {
            $result[] = 'test_' . $i . '_maxball';
            $result[] = 'test_' . $i . '_caption';
            $result[] = 'test_' . $i . '_short_caption';
            $result[] = 'test_' . $i . '_full_caption';
            $result[] = 'test_' . $i . '_pass_score';
        }

        return $result;
    }

    public function getFkFields()
    {
        return array('total', 'subtest_count', 'type_id');
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => 'Идентификатор',
            'reading' => 'Чтение',
            'writing' => 'Письмо',
            'grammar' => 'Лексика и грамматика',
            'listening' => 'Аудирование',
            'speaking' => 'Устная  речь',
            'total' => 'Общий балл',
            'caption' => 'Название',
            'print' => 'Печатать в сертификатах',
            'print_note' => 'Печатать в справках',
            'print_act' => 'Печатать в акте',
            'print_summary_table' => 'Печатать в ведомостях',
            'price' => 'Стоимость тестирования одного человека',
//            'sub_test_price' => 'Стоимость пересдачи одного субтеста',
            'percent_max' => 'Удовлетворительный результат (%)',
            'percent_min' => 'Можно один субтест, при условии, что остальные сданы удовлетворительно (%)',
            'subtest_count' => 'Количество субтестов',
            'type_id' => 'Категория теста',
            'valid_duration' => 'Срок действия в годах<br> (0 - бессрочно)',
            'sub_test_price' => 'Стоимость пересдачи одного субтеста по русс. яз.',
            'sub_test_price_2' => 'Стоимость пересдачи одного субтеста по истории/закон.',

        );

        for ($i = 1; $i <= 15; $i++) {
            $this->translate['test_' . $i . '_maxball'] = '<strong>Cубтест № ' . $i . '</strong>:<br> максимальный бал';
            $this->translate['test_' . $i . '_caption'] = '<strong>Cубтест № ' . $i . '</strong>: <br>название';
            $this->translate['test_' . $i . '_short_caption'] = '<strong>Cубтест № ' . $i . '</strong>:<br> сокращенное название';
            $this->translate['test_' . $i . '_full_caption'] = '<strong>Cубтест № ' . $i . '</strong>: <br>полное название';
            $this->translate['test_' . $i . '_pass_score'] = '<strong>Cубтест № ' . $i . '</strong>: <br>проходной % (если указано)';

        }
    }

    public function save()
    {
        $this->total = 0;
        for ($i = 1; $i <= $this->subtest_count; $i++) {
            $parameter = 'test_' . $i . '_maxball';
            $this->total += $this->$parameter;
        }
        $this->price = floatval(str_replace(',', '.', $this->price));
        $this->sub_test_price = floatval(str_replace(',', '.', $this->sub_test_price));
        if (!$this->valid_duration) {
            $this->valid_duration = null;
        }

        parent::save();
    }

    public function __toString()
    {
        return $this->caption;

    }

    /**
     * @return SubTests|SubTest[]
     */
    private $subtests = null;

    /**
     * @return SubTest[]|SubTests
     */
    public function getSubTests()
    {
        if (is_null($this->subtests)) {
            $this->subtests = SubTests::getByLevel($this);
        }

        return $this->subtests;
    }

    public function getPrintAct()
    {
        if (empty($this->print_act)) {
            $this->print_act = $this->caption;
        }

        return $this->print_act;
    }

    public function getPrintSummaryTable()
    {
        if (empty($this->print_summary_table)) {
            $this->print_summary_table = $this->caption;
        }

        return $this->print_summary_table;
    }


    public function getAvailableLevels()
    {
        if (!$this->is_additional) {
            return null;
        }

        return (array)json_decode($this->available_levels);
    }
}