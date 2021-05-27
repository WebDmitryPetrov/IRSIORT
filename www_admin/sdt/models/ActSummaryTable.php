<?php
require_once __DIR__ . '/files.php';

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 14.02.2017
 * Time: 15:22
 */
class ActSummaryTable extends File
{

    const SUMMARY_TABLE = 'summary';
    const ACT = 'act';
    const ACT_TABLE = 'act_table';

    protected $_table = 'act_summary_table';
    protected $upload_dir = SDT_UPLOAD_SUMMARY_TABLE_DIR;

    public function __construct($input = false)
    {
        parent::__construct($input);
        $this->_folder = date('Y/m/');
    }


    static function getByID($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $sql = 'SELECT * FROM act_summary_table WHERE id=\'' . mysql_real_escape_string($id) . '\'';
        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }

    static function getByHash($id)
    {

        $sql = 'SELECT * FROM act_summary_table WHERE download_id=\'' . mysql_real_escape_string($id) . '\'';

        $result = mysql_query($sql);
        if (!mysql_num_rows($result)) {
            return false;
        }
        $univer = new self(mysql_fetch_assoc($result));

        return $univer;
    }


    public function getDownloadURL()
    {
        return '/sdt/?action=act_summary_table&hash=' . $this->download_id;
    }

    public function show()
    {
        ob_clean();
        flush();
        session_write_close();
        readfile($this->getLocation());
        die();
    }

    public function move($filename, $filepath,  &$error = false,$type = '')
    {
        $this->type=$type;
        return parent::move($filename, $filepath,  $error);
    }


    public function getFkFields()
    {
        return array_merge(parent::getFkFields(),['type']);
    }

}