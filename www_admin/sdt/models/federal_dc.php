<?php

/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 03.09.14
 * Time: 10:39
 */
class FederalDCs extends ArrayObject
{


    static public function getAll()
    {
        $list = new FederalDCs();
        $sql = 'select * from federal_dc order by id';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new FederalDC($row);
        }

        return $list;
    }


    public static function getAll4Form()
    {
        $list = self::getAll();
        $result = array(
            0 => 'Íå óêàçàíî'
        );
        foreach ($list as $item) {
            $result[$item->id] = $item->caption;
        }

        return $result;
    }

        public static function getRegionsByAllDC()
        {
//        $list = new FederalDCs();
        $list = array();
        $sql = 'select rs.caption rcap,fd.caption fcap from regions rs
        left join federal_dc_region fdr  on rs.id=fdr.region_id
        left join federal_dc fd on fd.id=fdr.dc_id order by rs.caption asc';
        $result = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
//            $list[] = new FederalDC($row);
            $list[] = $row;
        }

        return $list;
        }




}

class FederalDC extends Model
{

    protected $_table = 'federal_dc';

    public $id;
    public $caption;
    public $full_caption;
//    public $r_num;
//    public $deleted;


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'select * from federal_dc where id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new FederalDC(mysql_fetch_assoc($result));

        return $univer;
    }




    public function setFields()
    {
        $this->fields = array(
            'id',
            'caption',
            'full_caption',
//            'r_num',
//            'deleted',


        );
    }

    public function getEditFields()
    {
        $result = array(
            'caption',
            'full_caption',
//            'r_num',


        );

        return $result;
    }

    public function getFkFields()
    {
        return array();
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => 'Èäåíòèôèêàòîğ',
            'caption' => 'Íàèìåíîâàíèå îêğóãà',
            'full_caption' => 'Ïîëíîå íàçâàíèå îêğóãà',
//            'r_num' => 'Êîä ÎÊÒÌÎ',

        );


    }


    public function __toString()
    {
        return $this->caption;

    }


    public static function getRegionsByID($id)
    {

        if (!is_numeric($id)) {
            return false;
        }

      //  $regions=Regions::getAll();

        /*$sql='select * from federal_dc_region fdr
        left join regions rs on rs.id=fdr.region_id
        where fdr.dc_id='.$id;*/

        $sql='select * from regions rs
        left join federal_dc_region fdr  on rs.id=fdr.region_id
        where fdr.dc_id is null or fdr.dc_id='.$id.' order by rs.caption asc';

        $result=mysql_query($sql) or die(mysql_error());


        return $result;


    }

    public static function saveRegions($post)
    {
//        $available = array_keys(self::getAvailableRoles());
//        $roles_id = array_intersect($roles_id, $available);
        $id=$post['DC'];
        if (!is_numeric($id)) {
            return false;
        }/**/
//die(var_dump($post['regions']));
        $sqlDelete = 'delete from federal_dc_region where dc_id=' . $id;
        mysql_query($sqlDelete);
        foreach ($post['regions'] as $key=>$item) {
            $sqlIn = 'insert into federal_dc_region(region_id,dc_id) values (' . $item . ',' .$id. ')';
          // die($sqlIn);
            mysql_query($sqlIn);
        }
        return true;
    }

    /**
     * @return TestLevel[]
     */
 /*   public function getTestLevels()
    {
        return TestLevels::getByType($this->id);
    }*/

}