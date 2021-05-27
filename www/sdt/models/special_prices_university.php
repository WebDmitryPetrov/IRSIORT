<?php


class SpecialPriceUniversity extends Model
{
    const TABLE = 'special_prices_university';
    public $_table = self::TABLE;

    static function getById($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return null;
        }

        $con = Connection::getInstance();
        $res = $con->query('select * from ' . self::TABLE . ' where id =  ' . $id, 1);
        if (!$res) {
            return null;
        }

        return new self($res);

    }

    static function getByUniversityId($id, $group_id=1)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return null;
        }

        /*$con = Connection::getInstance();
        $res = $con->query('select * from ' . self::TABLE . ' where university_id =  ' . $id, 1);
        if (!$res) {
            return null;
        }

        return new self($res);*/





        $list=array();
        $sql = 'select * from ' . self::TABLE . ' where university_id=' . $id .' and group_id='.$group_id;
        //die ($sql);
        $result = mysql_query($sql);

        //if (!mysql_num_rows($result)) return null;

        while ($res = mysql_fetch_array($result)) {
            $list[$res['level_id']] = $res;
        }

        $test_levels_list = array();
        $sql = 'select id,caption,type_id,test_group, is_publicated from sdt_test_levels  where deleted=0 and type_id > 1';
        //die ($sql);
        $result = mysql_query($sql);
        while ($res = mysql_fetch_array($result)) {
            if (!empty($list[$res['id']]['price_first_time'])) {
                $price = $list[$res['id']]['price_first_time'];
            } else {
                $price = '';
            }
            if (!empty($list[$res['id']]['price_subtest_1'])) {
                $sub_test_price = $list[$res['id']]['price_subtest_1'];
            } else {
                $sub_test_price = '';
            }
            if (!empty($list[$res['id']]['price_subtest_2'])) {
                $sub_test_price_2 = $list[$res['id']]['price_subtest_2'];
            } else {
                $sub_test_price_2 = '';
            }
            $test_levels_list[$res['id']] = array(
                'id' => $res['id'],
                'caption' => $res['caption'],
                'price' => $price,
                'sub_test_price' => $sub_test_price,
                'sub_test_price_2' => $sub_test_price_2,
                'type_id' => $res['type_id'],
                'test_group' => $res['test_group'],
                'is_publicated' => $res['is_publicated'],
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

    static function getByGroupAndLevel($group_id, $level_id, $university_id)
    {
        $level_id = filter_var($level_id, FILTER_VALIDATE_INT);
        if (!$level_id) {
            return null;
        }

        $group_id = filter_var($group_id, FILTER_VALIDATE_INT);
        if (!$group_id) {
            return null;
        }

        $con = Connection::getInstance();
        $res = $con->query(
            'select * from ' . self::TABLE . '
        where
        level_id =  ' . $level_id . '
        and group_id = ' . $group_id.'
        and university_id = ' .$university_id,
            1
        );
        if (!$res) {
            return null;
        }

        return new self($res);

    }


    const GROUP_DNR_LNR = 1;

    public $id;
    public $university_id;
    public $level_id;
    public $group_id;
    public $price_first_time;
    public $price_subtest_1;
    public $price_subtest_2;

    public function setFields()
    {
        $this->fields = array(
            'id',
            'university_id',
            'level_id',
            'group_id',
            'price_first_time',
            'price_subtest_1',
            'price_subtest_2',


        );
    }

    protected function setFieldsTypes()
    {

    }

    public function getEditFields()
    {
        return array(
//            'id',
            'university_id',
            'level_id',
            'group_id',
            'price_first_time',
            'price_subtest_1',
            'price_subtest_2',


        );
    }


    public function save($to_save, $group_id=1)
    {
        $sqlDelete = 'delete from ' . self::TABLE . ' where university_id=' . $to_save[0]['university_id'] . ' and group_id=' .$group_id;
        mysql_query($sqlDelete);
        foreach ($to_save as $save) {
            if ($save['price_first_time'] != 0) {
                $price = floatval(str_replace(',', '.', $save['price_first_time']));
            } else {
                $price = 'Null';
            }
            if ($save['price_subtest_1'] != 0) {
                $sub_test_price = floatval(
                    str_replace(',', '.', $save['price_subtest_1'])
                );
            } else {
                $sub_test_price = 'null';
            }
            if ($save['price_subtest_2'] != 0) {
                $sub_test_price_2 = floatval(
                    str_replace(',', '.', $save['price_subtest_2'])
                );
            } else {
                $sub_test_price_2 = 'null';
            }
            $sqlIn = 'insert into ' . self::TABLE . '(
            university_id,
            level_id,
            group_id,
            price_first_time,
            price_subtest_1,
            price_subtest_2
            ) values (
                ' . intval($save['university_id']) . ',
                ' . intval($save['level_id']) . ',
                ' . intval($save['group_id']) . ',
                ' . $price . ',
                ' . $sub_test_price . ',
                ' . $sub_test_price_2 . '
                )';
//            die ($sqlIn);
            mysql_query($sqlIn);
        }
    }


}