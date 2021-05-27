<?php

class Files extends ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array);

    }


}

class File extends Model
{
    protected $prefix = '';
    protected $_table = 'sdt_files';
    protected $upload_dir = SDT_UPLOAD_DIR;

    public $id;
    public $deleted;
    public $filename;
    public $caption;
    public $uploaded;
    public $user_id;
    protected $_folder = '';
    public $download_id;

    /**
     * File constructor.
     */
    public function __construct($input = array())
    {
        parent::__construct($input);
        $this->_folder = date('Y/m/');
    }

    protected function getFolder()
    {
        return $this->_folder;
    }

    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT * FROM sdt_files WHERE id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    static function getByHash($id)
    {

        $sql = 'SELECT * FROM sdt_files WHERE download_id=\'' . mysql_real_escape_string($id) . '\'';

        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }


    public function setFields()
    {
        $this->fields = array(
            'id',
            'deleted',
            'filename',
            'caption',
            'uploaded',
            'user_id'
        ,
            'download_id'
        );

    }

    public function getEditFields()
    {
        return array(
            'filename',
            'caption',
        );
    }

    public function setFieldsTypes()
    {
        $this->fieldTypes = array();

    }

    public function getFkFields()
    {
        return array('user_id', 'download_id');
    }

    public function setTranslate()
    {
        $this->translate = array();
    }


    public function save()
    {

        return parent::save();
    }

    protected function customUploadError($_file)
    {
        return false;
    }

    public function upload($_file, &$error = false)
    {
        global $extensions;

        //$error=$_file['error'];
        if ($_file['error'] != UPLOAD_ERR_OK) {
            switch ($_file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $error = 'Превышен размер файла';
                    break;
                default:
                    $error = 'Ошибка загрузки';
            }
            return false;
        } else {
            $error = 'Файл загружен';
        }
        $extension = strtolower(pathinfo($_file['name'], PATHINFO_EXTENSION));
        //die(var_dump($extension));
        if (!in_array($extension, $extensions)) {
            $error = 'Неверный тип файлов';

            return false;
        }
        if ($customCheck = $this->customUploadError($_file)) {
            $error = $customCheck;
            return false;
        }
        $filename = $this->getNewFilename();
        if (!move_uploaded_file($_file['tmp_name'], $filename)) {
            $error = 'Ошибка загрузки';

            return false;
        }
        $this->filename = str_replace($this->getDir(), '', $filename);
        $this->caption = $_file['name'];
        $this->download_id = pathinfo($filename, PATHINFO_BASENAME);
        $this->user_id = $_SESSION['u_id'];

        return $this->save();
        //	die(var_dump($_file));
    }

    protected function getNewFilename()
    {
        $folder = $this->getFolder();

        do {
            $date = date('U');
            $user = $_SERVER['REMOTE_ADDR'];
            $prefix = '';
            if (defined('CURRENT_HEAD_CENTER')) {
                $prefix = CURRENT_HEAD_CENTER . '_';
            }
            $filename = uniqid($prefix, true) . md5($date . $user . mt_rand(1, 100000));
//die($filename);
            if ($this->isHashExist($filename)) continue;
            if (!file_exists($this->getDir() . $folder)) mkdir($this->getDir() . $folder, 0777, true);

            $full_filename = $this->prefix . $this->getDir() . $folder . $filename;
//            die(var_dump($full_filename));
        } while (file_exists($full_filename) && is_file($full_filename));

        return $full_filename;
    }

    protected function isHashExist($hash)
    {
        $C = Connection::getInstance();
        $row = $C->queryOne(sprintf('SELECT COUNT(*) AS cc FROM %s WHERE download_id=\'%s\'', static::getTable(), $C->escape($hash)));
        return !!$row['cc'];
    }

    protected function getDir()
    {
        return $this->upload_dir;
    }

    public function getDownloadURL()
    {
        return '/sdt/?action=download&hash=' . $this->download_id;
    }

    protected function getLocation()
    {
        return $this->getDir() . $this->filename;
    }

    public function download()
    {
        if (!file_exists($this->getLocation()) || !is_file($this->getLocation())) {
            die('Файл не найден');
        }
        header("HTTP/1.1 200 OK");
        header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
        header('Content-Disposition: attachment; filename="' . addslashes($this->caption) . '"');
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($this->getLocation()));
        ob_clean();
        flush();
        session_write_close();
        readfile($this->getLocation());
        die();
    }

    public function delete()
    {
        parent::delete();
        @unlink($this->getLocation());
//        var_dump($this->getLocation());
    }

    public static function duplicateFile($file)
    {
        $old_file = self::getByID($file);
        if (!empty($file)) {

            $old_file_name = $old_file->upload_dir . $old_file->filename;

            $fullname = $new_file_name = $old_file->getNewFilename();


            $new_file_name = str_replace($old_file->getDir(), '', $new_file_name);
            if (@copy($old_file_name, $old_file->getDir() . $new_file_name)) {

                $new_file = new File();
                $new_file->filename = $new_file_name;
                $new_file->download_id = pathinfo($fullname, PATHINFO_BASENAME);

                $new_file->caption = $old_file->caption;
                $new_file->user_id = $old_file->user_id;
                $new_file->save();
                return $new_file->id;
            }
        }

        return null;

    }

    public function move($filename, $filepath, &$error = false)
    {

        $newFilePath = $this->getNewFilename();

        if (!rename($filepath, $newFilePath)) {
            $error = 'Ошибка загрузки';

            return false;
        }
        $this->filename = str_replace($this->getDir(), '', $newFilePath);
        $this->caption = $filename;
        $this->download_id = pathinfo($newFilePath, PATHINFO_BASENAME);
        $this->user_id = Session::getUserID();

        return $this->save();
        //	die(var_dump($_file));
    }
}