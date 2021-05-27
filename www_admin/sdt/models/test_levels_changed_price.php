<?php

class ChangedPriceTestLevels extends ArrayObject
{


    static public function getAll()
    {
        $list = new TestLevels();
        $sql = 'select * from sdt_test_levels  where deleted=0 order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new TestLevel($row);
        }

        return $list;
    }

    public static function getAvailable4Act($act_id)
    {
        $sqlUsed = 'select COUNT(DISTINCT IF(`level_id`= 1, level_id, NULL)) as migrants,
  COUNT(DISTINCT IF(`level_id` >1, level_id, NULL)) as other
  from sdt_act_test where act_id= ' . intval($act_id);

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
        $sql = 'select * from sdt_test_levels  where deleted=0 ' . $restrict . ' order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new TestLevel($row);
        }

        return $list;
    }
}

class ChangedPriceTestLevel extends Model
{
    protected $_table = 'test_levels_changed_price';

    public $id;
    public $price;
    public $sub_test_price;


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }


        $sql = 'select * from test_levels_changed_price where univer_id=' . $id;
        //die ($sql);
        $result = mysql_query($sql);
        while ($res = mysql_fetch_array($result)) {
            $list[$res['test_level_id']] = $res;
        }

        $test_levels_list = array();
        $sql = 'select id,caption from sdt_test_levels  where deleted=0';
        //die ($sql);
        $result = mysql_query($sql);
        while ($res = mysql_fetch_array($result)) {
            if (!empty($list[$res['id']]['price'])) {
                $price = $list[$res['id']]['price'];
            } else {
                $price = '';
            }
            if (!empty($list[$res['id']]['sub_test_price'])) {
                $sub_test_price = $list[$res['id']]['sub_test_price'];
            } else {
                $sub_test_price = '';
            }
            $test_levels_list[$res['id']] = array(
                'id' => $res['id'],
                'caption' => $res['caption'],
                'price' => $price,
                'sub_test_price' => $sub_test_price
            );
            // $test_levels_list[$res['id']]=$list[$res['id']];
        }


        /*  $sql = 'select * from sdt_test_levels where id=\'' . mysql_real_escape_string($id) . '\'';
          $result = mysql_query($sql);
          if (!mysql_num_rows($result)) {
              return false;
          }
          $univer = new TestLevel(mysql_fetch_assoc($result));*/

//die (var_dump($univer));
        return $test_levels_list;
    }


    /*   public function getFkFields()
       {
           return array('total');
       }*/

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }


    public function save($to_save)
    {
        $sqlDelete = 'delete from test_levels_changed_price where univer_id=' . $to_save[0]['univer_id'];
        mysql_query($sqlDelete);
        foreach ($to_save as $save) {
            if ($save['price'] != 0) {
                $price = floatval(str_replace(',', '.', $save['price']));
            } else {
                $price = 'Null';
            }
            if ($save['sub_test_price'] != 0) {
                $sub_test_price = floatval(
                    str_replace(',', '.', $save['sub_test_price'])
                );
            } else {
                $sub_test_price = 'null';
            }
            $sqlIn = 'insert into test_levels_changed_price(
            univer_id,
            test_level_id,
            price,
            sub_test_price
            ) values (
                ' . intval($save['univer_id']) . ',
                ' . intval($save['test_level_id']) . ',
                ' . $price . ',
                ' . $sub_test_price . '
                )';
            mysql_query($sqlIn);
        }
    }


    public static function getPriceByLevel($act_id, $level_id)
    {

        //$list = new ChangedPriceTestLevels();
        $sql = 'select 
t3.price as changed_price,
t3.sub_test_price as changed_sub_test_price,
t2.is_price_change,
t4.price as normal_price,
t4.sub_test_price as normal_sub_test_price
from sdt_act as t1
left join sdt_university as t2 on t1.university_id=t2.id
left join test_levels_changed_price as t3 on t1.university_id=t3.univer_id and t3.test_level_id=' . $level_id . '
left join sdt_test_levels as t4 on t4.id=' . $level_id . '
where t1.id=' . $act_id;
        //die(var_dump($sql));
        $result = mysql_query($sql) or die(mysql_error());
        $row = mysql_fetch_assoc($result);

        $array = array();
        if ($row['is_price_change'] == 1) {
            if (!empty($row['changed_price']) && $row['changed_price'] != 0) {
                $array['price'] = $row['changed_price'];
            } else {
                $array['price'] = $row['normal_price'];
            }

            if (!empty($row['changed_sub_test_price']) && $row['changed_sub_test_price'] != 0) {
                $array['sub_test_price'] = $row['changed_sub_test_price'];
            } else {
                $array['sub_test_price'] = $row['normal_sub_test_price'];
            }
        }
//        (var_dump($array));

//            $list[] = new ChangedPriceTestLevel($array);
        return new ChangedPriceTestLevel($array);

    }


    public function setFields()
    {
        $this->fields = array(
            'price',
            'sub_test_price',
        );
    }

    public function getEditFields()
    {
      return array(
          'price',
          'sub_test_price',
        );
    }
}