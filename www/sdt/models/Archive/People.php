<?php

namespace SDT\models\Archive;

class People
{
    public function search(array $params = [])
    {
        $C = \Connection::getInstance();
        $where = [];
        if (!empty($params['surname'])) {
            $where[] = vsprintf(
                "(p.surname_rus = '%s' or p.surname_lat = '%s')",
                [
                    $C->escape($params['surname']),
                    $C->escape($params['surname']),
                ]
            );
        }
        if (!empty($params['name'])) {
            $where[] = vsprintf(
                "(p.name_rus = '%s' or p.name_lat = '%s')",
                [
                    $C->escape($params['name']),
                    $C->escape($params['name']),
                ]
            );
        }
        if (!empty($params['cert'])) {
            $where[] = vsprintf(
                "(
                p.blank_number = '%s' or p.original_blank_number = '%s' 
                or p.document_nomer = '%s' or p.annul_blank = '%s'   )",
                [
                    $C->escape($params['cert']),
                    $C->escape($params['cert']),
                    $C->escape($params['cert']),
                    $C->escape($params['cert']),
                ]
            );
        }
        if (empty($where)) {
            return [];
        }
        $sql = 'select * from migrant_archive.people_archive p where %s limit 20';
        $sql = sprintf($sql, implode(' and ', $where));

//die(var_dump($sql));
        return $this->map2Users($C->query($sql));
    }

    private function map2Users($data)
    {
        if (empty($data)) {
            return [];
        }

        return array_map(
            function (array $row) {
                return Man::createFromArray($row);
            },
            $data
        );
    }

    /**
     * @param $id
     * @return Man|null
     */
    public function find($id)
    {
        $C = \Connection::getInstance();
        $sql = 'select * from migrant_archive.people_archive p where p.id = %d limit 1';
        $sql = sprintf($sql, intval($id));
//die(var_dump($sql));
        $res = $C->queryOne($sql);
        if ($res) {
            return  Man::createFromArray($res);
        }

        return null;
    }
}