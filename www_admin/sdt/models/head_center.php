<?php

class HeadCenters extends ArrayObject
{
    protected $_table;

    public function __construct($array = array())
    {
        parent::__construct($array);

    }

    static public function getAll()
    {

        $list = new HeadCenters();

//        $sql = 'select * from sdt_head_center where deleted=0 and id <> 6 order by horg_id, name';
        $sql = 'SELECT * FROM sdt_head_center WHERE deleted=0 AND id <> 6 ORDER BY horg_id, short_name';

        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = new HeadCenter($row);
        }

        return $list;
    }

    static public function getAllSortedLikeMainPage()
    {

        $list = new HeadCenters();
        $sorted_list = new HeadCenters();

        $sorted_array = array(2, 1, 7, 8, 13, 14, 4, 3, 5, 9, 10, 11, 12, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24); //такой порядок на главной странице

        $sql = 'SELECT * FROM sdt_head_center WHERE deleted=0 AND id <> 6 ORDER BY horg_id';

        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[$row['id']] = new HeadCenter($row);
        }
        foreach ($sorted_array as $key) {
            $sorted_list[] = $list[$key];
            unset($list[$key]);
        }
        if (!empty($list)) {
            foreach ($list as $item) {
                $sorted_list[] = $item;
            }
        }


        return $sorted_list;
    }

    public static function getHeadOrgs()
    {
        $list = array();
        $sql = "SELECT id, captoin AS caption FROM sdt_head_org  WHERE deleted=0";
        $result = mysql_query($sql) or die (mysql_error());
        while ($res = mysql_fetch_assoc($result)) {

            $list[] = $res;
        }
        return $list;
    }





    /* Далее идут функции для построения отчетов - добавление/удаление ГЦ в системе */

    /* Список id всех ГЦ по РУДН */
    public static function getStatistHcArrayPfur()
    {
        return array(
            2 => 1,
            1 => 0,
            8 => 3,
            7 => 2,
            13 => 4,
            14 => 5,
            24 => 6
        );
    }

    /* Список ГЦ по РУДН */
    public static function getStatistResultArrayPfur($template = array())
    {
        if (!empty($template)) {
            $switcher = 'data';
        } else {
            $switcher = 'centers';
        }

        return array(
            0 => array(
                'caption' => 'МЦТ',
                $switcher => $template,
            ),
            1 => array(
                'caption' => 'ГЦТРКИ',
                $switcher => $template,
            ),
            2 => array(
                'caption' => 'ШОПМ',
                $switcher => $template,
            ),
            3 => array(
                'caption' => 'ЦТ РУДН',
                $switcher => $template,
            ),
            4 => array(
//                    'caption' => 'Сочинский институт (филиал) РУДН',
                'caption' => 'ШОПМ2',
                $switcher => $template,
            ),
            5 => array(
                'caption' => 'Горизонт',
                $switcher => $template,
            ),
            6 => array(
                'caption' => 'АМЦ',
                $switcher => $template,
            ),
        );
    }


    /* Список id всех ГЦ с группировкой по Организации */
    public static function getStatistHcArrayAll()
    {
        return array(
            2 => 0,
            3 => 1,
            1 => 0,
            9 => 2,
            10 => 3,
            4 => 4,
            8 => 0,
            5 => 5,
            7 => 0,
            11 => 6,
            12 => 7,
            13 => 0,
            14 => 0,
            15 => 8,
            16 => 9,
            17 => 10,
            18 => 11,
            19 => 12,
            20 => 13,
            21 => 14,
            22 => 15,
            23 => 16,
            24 => 0
        );
    }

    /* Список всех ГЦ с группировкой по Организации  */
    public static function getStatistResultArrayAll($template = '')
    {
        return array(
            0 => array(
                'caption' => 'РУДН',
                'data' => $template,
            ),
            1 => array(
                'caption' => 'МГУ',
                'data' => $template,
            ),
            2 => array(
                'caption' => 'ТОГУ',
                'data' => $template,
            ),
            3 => array(
                'caption' => 'ТюмГУ',
                'data' => $template,
            ),
            4 => array(
                'caption' => 'Гос. ИРЯ им. А.С. Пушкина ',
                'data' => $template,
            ),
            5 => array(
                'caption' => 'СПбГУ ',
                'data' => $template,
            ),
            6 => array(
                'caption' => 'ВолГУ ',
                'data' => $template,
            ),
            7 => array(
                'caption' => 'КФУ ',
                'data' => $template,
            ),
            8 => array(
                'caption' => 'НИУ «БелГУ»',
                'data' => $template,
            ),
            9 => array(
                'caption' => 'ДВФУ',
                'data' => $template,
            ),
            10 => array(
                'caption' => 'СКФУ',
                'data' => $template,
            ),
            11 => array(
                'caption' => 'БашГУ',
                'data' => $template,
            ),
            12 => array(
                'caption' => 'ПСКОВГУ',
                'data' => $template,
            ),
            13 => array(
                'caption' => 'РГПУ им. А.И. Герцена',
                'data' => $template,
            ),
            14 => array(
                'caption' => 'ПГУ',
                'data' => $template,
            ),
            15 => array(
                'caption' => 'ЮЗГУ',
                'data' => $template,
            ),
            16 => array(
                'caption' => 'ВГСПУ',
                'data' => $template,
            ),
        );
    }


    /* Сквозной список id ГЦ */
    public static function getStatistHcArrayThroughAll()
    {
        return array(
            2 => 1,
            1 => 0,
            8 => 3,
            7 => 2,
            3 => 4,
            10 => 5,
            4 => 6,
            9 => 7,
            5 => 8,
            11 => 9,
            12 => 10,
            13 => 11,
            14 => 12,
            15 => 13,
            16 => 14,
            17 => 15,
            18 => 16,
            19 => 17,
            20 => 18,
            21 => 19,
            22 => 20,
            23 => 21,
            24 => 22,
        );
    }

    /* Сквозной список ГЦ */
    public static function getStatistResultArrayThroughAll($template = '')
    {
        return array(
            0 => array(
                'caption' => 'МЦТ',
                'centers' => array(),
            ),
            1 => array(
                'caption' => 'ГЦТРКИ',
                'centers' => array(),
            ),
            2 => array(
                'caption' => 'ШОПМ',
                'centers' => array(),
            ),
            3 => array(
                'caption' => 'ЦТ РУДН',
                'centers' => array(),
            ),
            4 => array(
                'caption' => 'МГУ',
                'centers' => array(),
            ),
            5 => array(
                'caption' => 'ТЮМГУ',
                'centers' => array(),
            ),
            6 => array(
                'caption' => 'Гос. ИРЯ им. А.С. Пушкина ',
                'centers' => array(),
            ),
            7 => array(
                'caption' => 'ТОГУ',
                'centers' => array(),
            ),
            8 => array(
                'caption' => 'СПбГУ',
                'centers' => array(),
            ),
            9 => array(
                'caption' => 'ВолГУ',
                'centers' => array(),
            ),
            10 => array(
                'caption' => 'КФУ',
                'centers' => array(),
            ),
            11 => array(
//                        'caption' => 'Сочинский институт (филиал) РУДН',
                'caption' => 'ШОПМ2',
                'centers' => array(),
            ),
            12 => array(
                'caption' => 'Горизонт',
                'centers' => array(),
            ),
            13 => array(
                'caption' => 'НИУ «БелГУ»',
                'centers' => array(),
            ),
            14 => array(
                'caption' => 'ДВФУ',
                'centers' => array(),
            ),
            15 => array(
                'caption' => 'СКФУ',
                'centers' => array(),
            ),
            16 => array(
                'caption' => 'БашГУ',
                'centers' => array(),
            ),
            17 => array(
                'caption' => 'ПСКОВГУ',
                'centers' => array(),
            ),
            18 => array(
                'caption' => 'РГПУ им. А.И. Герцена',
                'centers' => array(),
            ),
            19 => array(
                'caption' => 'ПГУ',
                'centers' => array(),
            ),
            20 => array(
                'caption' => 'ЮЗГУ',
                'centers' => array(),
            ),
            21 => array(
                'caption' => 'ВГСПУ',
                'centers' => array(),
            ),
            22 => array(
                'caption' => 'АМЦ',
                'centers' => array(),
            ),
        );
    }

    /* ГЦ по РУДН */
    public static function getStatistHCListArrayPfur($result = '')
    {
        return array(
            1 => [
                'id' => [1],
                'caption' => 'МЦТ',
                'result' => $result,
            ],
            2 => [
                'id' => [2],
                'caption' => 'МЦТ ФПКП РКИ',
                'result' => $result,
            ],
            7 => [
                'id' => [7],
                'caption' => 'ШОПМ',
                'result' => $result,
            ],
            8 => [
                'id' => [8],
                'caption' => 'ЦТ РУДН',
                'result' => $result,
            ],
            13 => [
                'id' => [13],
//                'caption' => 'Сочинский институт (филиал) РУДН',
                'caption' => 'ШОПМ2',
                'result' => $result,
            ],
            14 => [
                'id' => [14],
                'caption' => 'Горизонт',
                'result' => $result,
            ],
            24 => [
                'id' => [24],
                'caption' => 'АМЦ',
                'result' => $result,
            ],
        );
    }


    /* Названия организаций */
    public static function getStatistHONamesALL()
    {
        return array(
            1 => 'Российский университет дружбы народов',
            2 => 'МГУ имени М.В. ЛОМОНОСОВА',
            3 => 'Тихоокеанский государственный университет',
            4 => 'Тюменский государственный университет',
            5 => 'Государственный институт русского языка им. А.С. Пушкина',
            6 => 'Санкт-Петербургский государственный университет',
            7 => 'Волгоградский государственный университет',
            8 => 'Казанский (Приволжский) Федеральный университет',
            9 => 'Белгородский государственный национальный исследовательский университет',
            10 => 'Дальневосточный федеральный университет',
            11 => 'Северо-Кавказский федеральный университет',
            12 => 'Башкирский государственный университет',
            13 => 'Псковский государственный университет',
            14 => 'Российский государственный педагогический университет им. А.И. Герцена',
            15 => 'Пензенский государственный университет',
            16 => 'Юго-Западный государственный университет',
            17 => 'Волгоградский государственный социально-педагогический университет',
        );
    }

    /* Список id ГЦ по id Организации */
    public static function getStatistHCByHO($horg = 1)
    {
        //$list = new HeadCenters();
        $list = array();

        $sql = 'SELECT * FROM sdt_head_center WHERE deleted = 0 AND horg_id = ' . $horg;

        $result = mysql_query($sql) or die(mysql_error() . $sql);
        while ($row = mysql_fetch_assoc($result)) {
            $list[] = $row['id'];
        }
//var_dump($list);die;
        // die(var_dump($list));
        return $list;
    }

}

class HeadCenter extends Model
{

    protected $_table = 'sdt_head_center';

    public $id;
    public $name;
    public $short_name;
    public $rector;
    public $form;
    public $legal_address;
    public $contact_phone;
    public $contact_fax;
    public $contact_email;
    public $contact_other;
    public $responsible_person;
    public $responsible_person_phone;
    public $responsible_person_email;

    public $city;
    public $href;
    public $comments;
    public $country_id;
    public $user_password;

    public $horg_id;



    public function __construct($input = false)
    {
        parent::__construct($input);


    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT * FROM sdt_head_center WHERE deleted=0 AND id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new HeadCenter(mysql_fetch_assoc($result));

        return $univer;
    }

    static function getNameByActID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT hc.* FROM sdt_head_center AS hc
                JOIN sdt_university AS u ON hc.id=u.head_id
                JOIN sdt_act AS a ON u.id=a.university_id
                WHERE a.id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new HeadCenter(mysql_fetch_assoc($result));
        if (!empty($univer->short_name)) return $univer->short_name;
        else return $univer->name;

    }

    public static function getNameByCertificateID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT hc.* FROM sdt_head_center AS hc
                JOIN certificate_reserved AS cr ON hc.id=cr.head_center_id

                WHERE cr.id=\'' . intval($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new HeadCenter(mysql_fetch_assoc($result));
        if (!empty($univer->short_name)) return $univer->short_name;
        else return $univer->name;
    }


    static function getByActID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT hc.* FROM sdt_head_center AS hc
                JOIN sdt_university AS u ON hc.id=u.head_id
                JOIN sdt_act AS a ON u.id=a.university_id
                WHERE a.id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new HeadCenter(mysql_fetch_assoc($result));

        return $univer;

    }

    public function setFields()
    {
        $this->fields = array(
            'id',
            'name',
            'short_name',
            'rector',
            'form',
            'legal_address',
            'contact_phone',
            'contact_fax',
            'contact_email',
            'contact_other',
            'responsible_person',
            'responsible_person_phone',
            'responsible_person_email',
            'city',
            'comments',
            'country_id',
            'href',

        );
    }

    public function getEditFields()
    {
        return array(
            'name',
            'short_name',
            'rector',
            'form',
            'legal_address',
            'contact_phone',
            'contact_fax',
            'contact_email',
            'contact_other',
            'responsible_person',
            'responsible_person_phone',
            'responsible_person_email',
            'city',
            'comments',
            'country_id',
            'href',


        );
    }

    public function getFkFields()
    {
        return array(
            'horg_id',
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array(
            'name' => 'text',
            'legal_address' => 'text',
            'contact_other' => 'text',
            'comments' => 'text',
            'country_id' => 'select',
        );
    }

    public function setTranslate()
    {
        $this->translate = array(
            'id' => 'Идентификатор',
            'name' => 'Название',
            'short_name' => 'Сокращенное  название',
            'rector' => 'Ректор',
            'form' => 'Правовая форма',
            'legal_address' => 'Юридический адрес',
            'contact_phone' => 'Телефон',
            'contact_fax' => 'Факс',
            'contact_email' => 'Email',
            'contact_other' => 'Дополнительные контакты',
            'responsible_person' => 'Ответственный по системе',
            'responsible_person_phone' => 'Телефон ответственного по системе',
            'responsible_person_email' => 'Email ответственного по системе',
            'comments' => 'Комментарии',
            'city' => 'Город',
            'country_id' => 'Страна',
            'href' => 'Адрес захода сайта (без https://)',



        );
//        array_merge(...$this->translate);
    }


    public function __toString()
    {
        return (string)$this->name;
    }


    public function getCountry()
    {
        if ($this->country_id) {
            return Country::getByID($this->country_id);
        }

        return new stdClass();
    }

    public function delete()
    {
        return parent::delete();
    }

    protected function setAvailableValues()
    {
        $countries = Countries::getAll4Form();


        $this->availableValues = array(
            'country_id' => $countries,
        );

    }

    public static function getOrgName($head_id)
    {

        $sql = "SELECT sho.captoin FROM sdt_head_center shc
        LEFT JOIN sdt_head_org sho ON sho.id=shc.horg_id
        WHERE shc.horg_id=" . $head_id;

        $result = mysql_query($sql);

        return mysql_result($result, 0, 0);
    }

    public function getFormFields()
    {
        $parent = parent::getFormFields();

        return $parent;

    }

    protected function prepareBsoID($val)
    {
        return trim($val);
    }

    protected function populateSetters()
    {
        return array(

        );
    }

    public function getTitle(){
        $text = HeadCenterText::getByHeadCenterID($this->id);
        return $text->login_page_title;
    }
}