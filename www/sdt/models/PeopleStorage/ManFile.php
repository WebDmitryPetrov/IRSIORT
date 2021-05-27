<?php


namespace SDT\models\PeopleStorage;


class ManFile extends \File
{
    const TABLE = 'sdt_act_man_files';
    const TYPE_PHOTO = 'photo';
    const TYPE_AUDITION = 'audition';

    protected $prefix = '';
    protected $_table = self::TABLE;
    protected $upload_dir = SDT_ACT_MAN_FILES;
    public $man_id;
    public $type;

    public function __construct(array $input = array())
    {
        parent::__construct($input);
    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $item = new self(mysql_fetch_assoc($result));

        return $item;
    }

    static function getByHash($id)
    {

        $sql = 'SELECT * FROM  ' . self::TABLE . ' WHERE download_id=\'' . mysql_real_escape_string($id) . '\'';

        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $item = new self(mysql_fetch_assoc($result));

        return $item;
    }

    public static function getByUserType($id, $type)
    {
        $c = \Connection::getInstance();

        $sql = 'SELECT * FROM  ' . self::TABLE . ' WHERE man_id=\'%d\' and type=\'%s\'';

        $row = $c->queryOne(vsprintf($sql, [
            intval($id),
            $c->escape($type),
        ]));
        if (!$row) return null;

        return new self($row);
    }

    public static function deleteOther($man_id, $type, array $exclude)
    {
        if (!count($exclude)) return 0;

        $sql = 'select *  FROM  ' . self::TABLE . ' WHERE man_id=\'%d\' and type=\'%s\' and id not in (%s)';
        $c = \Connection::getInstance();
        $res = $c->query(vsprintf($sql, [
                intval($man_id),
                $c->escape($type),
                implode(', ', array_map('intval', $exclude)),
            ]
        ));
        if (!$res) return 0;
        foreach ($res as $row) {
            $r = new self($row);
            $r->delete();

        }


        return count($res);


    }

    public function delete()
    {
        parent::delete();
        if ($this->id) {
            $sql = 'delete from  ' . self::TABLE . ' where id = ' . intval($this->id);
            $c = \Connection::getInstance();
            $c->execute($sql);
        }

    }

    public function getDownloadURL()
    {
        return '/sdt/?action=act_man_file_download&hash=' . $this->download_id;
    }

    public function getShowUrl()
    {
        return '/sdt/?action=act_man_file_show&hash=' . $this->download_id;
    }

    public function show()
    {
        ob_clean();
        flush();
        session_write_close();
        readfile($this->getLocation());
        die();
    }

    protected function getFolder()
    {
        $C = \Connection::getInstance();
        $sql = 'select   sa.id as sa_id, su.id as su_id, su.head_id as head_id,
month(sa.created) as m, year(sa.created) as y
from sdt_act_people sap
left join sdt_act  sa on sa.id = sap.act_id
left join sdt_university su on su.id = sa.university_id
where sap.id = ' . $this->man_id . '
limit 1';
        $q = $C->queryOne($sql);
        return vsprintf('%d/%d/%d/%d/%d/', [
            $q['head_id'],
            $q['su_id'],
            $q['y'],
            $q['m'],
            $q['sa_id'],
        ]);
    }

    public function move($filename, $filepath, &$error = false, $type = '')
    {
        $this->type = $type;
        return parent::move($filename, $filepath, $error);
    }


    public function getFkFields()
    {
        return array_merge(parent::getFkFields(), ['type', 'man_id']);
    }

    public function getMan()
    {
        return \ActMan::getByID($this->man_id);
    }

    protected function customUploadError($_file)
    {
        $errors = [];
        $ext = ['jpg', 'jpeg'];
//        var_dump($_file);
        if (!in_array(strtolower(pathinfo($_file['name'], PATHINFO_EXTENSION)), $ext)) {
            $errors[] = 'неверный формат файла';
        }
        $type = 'image/jpeg';
        $imageSize = getimagesize($_file['tmp_name']);
        if ($_file['type'] !== $type || $imageSize['mime'] !== $type) {
            $errors[] = 'неверный формат файла';
        }

//        if ($_file['size'] > (1024 * 1024 * 2.5)) {
//            $errors[] = 'превышен размер файла';
//        }

        $shortSide = 1772;
        $lingSide = 2480;

        $imageMin = $imageSize[0];
        $imageMax = $imageSize[1];
        if ($imageMin > $imageMax) {
            $imageMin = $imageSize[1];
            $imageMax = $imageSize[0];
        }

        if ($imageMin < $shortSide || $imageMax < $lingSide) {
            $errors[] = vsprintf('минимальное разрешение файла %d x %d', [
                $shortSide, $lingSide
            ]);
        }

        if (count($errors)) {
            $errors = array_unique($errors);
            return implode(', ', $errors);
        }
//        die(var_dump($_file));
        return parent::customUploadError($_file);
    }


}