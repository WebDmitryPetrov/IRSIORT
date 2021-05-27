<?php

namespace SDT\models\Archive;

class Man
{
    private $fields = [];

    public static function createFromArray(array $row)
    {
        $man = new self();
        foreach ($row as $key => $value) {
            $man->$key = $value;
        }

        return $man;
    }

    public function __get($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->fields[$name] = $value;
    }

    /**
     * @return \Country
     */
    public function getCountry()
    {
        if ($this->country_id) {
            return \Country::getByID($this->country_id);
        }
    }

    /**
     * @return \TestLevel
     */
    public function getTestLevel()
    {
        if ($this->level_id) {
            return \TestLevel::getByID($this->level_id);
        }
    }

    public function isCertificate()
    {
        return $this->document == \ActMan::DOCUMENT_CERTIFICATE;
    }

    /**
     * @return false|PassportFile
     */
    public function getFilePassport()
    {
        return PassportFile::getByID($this->passport_file);
    }

    /**
     * @return false|PassportFile
     */
    public function getDuplicateFilePassport()
    {
        if(!$this->dubl_passport_file_id) return false;
        return PassportFile::getByID($this->dubl_passport_file_id);
    }

    /**
     * @return false|PassportFile
     */
    public function getDuplicateFileRequest()
    {
        if(!$this->dubl_request_file_id) return false;
        return PassportFile::getByID($this->dubl_request_file_id);
    }


    /**
     * @return \SubTestResults|\SubTestResult[]
     */
    public function getResults()
    {
        return \SubTestResults::getByArchiveMan($this);
    }
}