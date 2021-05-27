<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 04.07.2016
 * Time: 11:18
 *
 * SELECT
 * su.name AS lc,
 * su.id AS lc_id,
 * su.deleted,
 * shc.id AS head_id,
 * su.legal_address,
 * c.name AS country,c.id, r.caption AS region,
 * IF(c.id=134 OR c.id=0 OR  c.id IS NULL ,1,0) AS is_russia,
 * -- AVG(sat.price) AS avg_price,
 * su.is_head,
 * YEAR(sa.created) AS y,
 * COUNT(DISTINCT sap.id) AS sdalo,
 * sap.document
 * FROM sdt_act_people sap
 * LEFT JOIN sdt_act_test sat
 * ON sap.test_id = sat.id
 * LEFT JOIN sdt_act sa
 * ON sa.id = sat.act_id
 * LEFT JOIN sdt_university su
 * ON sa.university_id = su.id
 * LEFT JOIN sdt_head_center shc
 * ON su.head_id = shc.id
 * LEFT JOIN sdt_test_levels
 * ON sat.level_id = sdt_test_levels.id
 * LEFT JOIN country c ON c.id = su.country_id
 * LEFT JOIN regions r ON su.region_id = r.id
 *
 * WHERE sa.created BETWEEN '2015-12-04 0:0:0' AND '2019-05-01 23:59:59'
 * AND sa.test_level_type_id = 2
 * AND sa.deleted = 0
 * AND sap.deleted = 0
 * AND sat.deleted = 0
 * AND shc.deleted = 0
 * AND shc.horg_id=1
 * AND sa.state IN ('received', 'print', 'wait_payment', 'check', 'need_confirm', 'archive')
 * GROUP BY su.id,
 * sap.document,
 * y
 *
 * ORDER BY su.name
 */
class minobr_pfur_local_report_exam_with_country extends AbstractReport
{
    public function execute($from, $to)
    {
        $C = Connection::getInstance();
        $region_sql = '';
//            var_dump($region);

        $states = array();
        foreach (Act::getInnerStates() as $st) {
            $states[] = "'" . $st . "'";
        }
        $states = implode(', ', $states);

        $sql = "SELECT
su.name AS lc,
su.id AS lc_id,
su.deleted,
shc.id AS head_id,
su.legal_address,
c.name AS country,c.id as country_id, r.caption AS region,
IF(c.id=134 OR c.id=0 OR  c.id IS NULL ,1,0) AS is_russia,

su.is_head,
YEAR(sa.created) AS y,
COUNT(DISTINCT sap.id) AS sdalo,
sap.document
FROM sdt_act_people sap
LEFT JOIN sdt_act_test sat
ON sap.test_id = sat.id
LEFT JOIN sdt_act sa
ON sa.id = sat.act_id
LEFT JOIN sdt_university su
ON sa.university_id = su.id
LEFT JOIN sdt_head_center shc
ON su.head_id = shc.id
LEFT JOIN sdt_test_levels
ON sat.level_id = sdt_test_levels.id
LEFT JOIN country c ON c.id = su.country_id
LEFT JOIN regions r ON su.region_id = r.id


WHERE sa.created BETWEEN '" . $C->escape($C->format_date($from)) . " 0:0:0' AND '" . $C->escape(
                $C->format_date($to)
            ) . " 23:59:59'
AND sa.test_level_type_id = 2
AND sa.deleted = 0
and su.id <> 168
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.horg_id=1
AND shc.deleted = 0
AND sa.state IN (" . $states . ")


  GROUP BY su.id,
sap.document,
y

ORDER BY su.name";
//die($sql);
        $res = $C->query($sql);
//        die(var_dump($res[0]));
        $lcs = array_unique(array_column($res, 'lc_id'));
//        die(var_dump($lcs));
        $result = [];
        foreach ($lcs as $lc) {
            $result[] = new minobr_pfur_local_report_exam_with_country_dto(array_filter($res, function ($row) use ($lc) {
                return $row['lc_id'] == $lc;
            }));

        }
//        die(var_dump($result));

        return $result;
    }
}

class minobr_pfur_local_report_exam_with_country_dto
{

    private $id;
    private $country;
    private $russia;
    private $head;
    private $region;
    private $address;
    private $caption;
    private $certs = [];
    private $notes = [];


    public function __construct(array $rows)
    {
        $rows = array_values($rows);
//        if (empty($rows[0]))
//            die(var_dump($rows));
        $this->fillFromRow($rows[0]);
        $this->parseDocs($rows);
    }

    private function fillFromRow(array $row)
    {
        $this->id = $row['lc_id'];
        $this->country = $row['country'];
        $this->russia = (bool)$row['is_russia'];
        $this->head = (bool)$row['is_head'];
        $this->region = $row['region'];
        $this->address = $row['legal_address'];
        $this->caption = trim($row['lc']);

    }

    private function parseDocs(array $rows)
    {
        foreach ($rows as $row) {
            if ($row['document'] === 'note') {
                $this->notes[$row['y']] = $row['sdalo'];
            }
            if ($row['document'] === 'certificate') {
                $this->certs[$row['y']] = $row['sdalo'];
            }
        }
    }

    public function getNoteYear($year)
    {
        return !empty($this->notes[$year]) ? $this->notes[$year] : 0;
    }

    public function getCertYear($year)
    {
        return !empty($this->certs[$year]) ? $this->certs[$year] : 0;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function isRussia()
    {
        return $this->russia;
    }

    /**
     * @return mixed
     */
    public function isHead()
    {
        return $this->head;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return array
     */
    public function getCerts()
    {
        return $this->certs;
    }

    /**
     * @return array
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }


}