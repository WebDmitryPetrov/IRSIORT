<?php

class CountExamFederal extends AbstractReport
{
    /**
     * @var int
     */
    private $horg;

    public function __construct($horg = 1)
    {
        $this->horg = $horg;
    }

    public function execute($from, $to)
    {
        $C = Connection::getInstance();


        $dates = " ";


        $sql = ' SELECT
fd.id AS f_id,
 fd.caption AS f_cap,
 
rs.id AS r_id, 
rs.caption AS r_cap,
su.name AS su_caption,
su.id AS su_id,
 
 SUM(IF(sap.document="certificate", 1,0)) AS certs
FROM
sdt_university su
LEFT JOIN sdt_head_center hc ON hc.id = su.head_id
LEFT JOIN regions rs ON rs.id=su.region_id
LEFT JOIN sdt_act sa ON su.id = sa.university_id
LEFT JOIN sdt_act_people sap ON sa.id=sap.act_id
LEFT JOIN federal_dc_region fdr ON fdr.region_id=rs.id
LEFT JOIN federal_dc fd ON fd.id=fdr.dc_id
WHERE
sap.deleted =0 AND sa.deleted = 0  AND hc.horg_id = %d AND sa.test_level_type_id = 2
AND sa.state IN (%s)
AND su.id NOT IN (168,305,574, 561,224,241)
AND sa.created BETWEEN  \'%s\'  AND   \'%s\'
GROUP BY su.id

ORDER BY f_id, r_id, trim(su_caption)

';

        $states = array_map(function ($item) {
            return "'" . $item . "'";
        }, Act::getInnerStates());

        $from = $C->escape($this->mysql_date($from)) . " 0:0:0";
        $to = $C->escape($this->mysql_date($to)) . " 23:59:59";
        $sql = sprintf($sql, intval($this->horg), implode(', ', $states), $from, $to);
        //  die($sql);
//        and fd.id=9


        $result = $C->query($sql);

        $groups = [
            'none' => [
                'caption' => 'Регион не указан',
                'items' => [],
            ],
        ];
        foreach ($result as $row) {
            $f_id = $row['f_id'];
            $r_id = $row['r_id'];
            if ($f_id) {
                if (!array_key_exists($f_id, $groups)) {
                    $groups[$f_id] = [
                        'caption' => $row['f_cap'],
                        'items' => [],
                    ];
                }

                if (!array_key_exists($r_id, $groups[$f_id]['items'])) {
                    $groups[$f_id]['items'][$r_id] = [
                        'caption' => $row['r_cap'],
                        'items' => [],
                    ];
                }
                $groups[$f_id]['items'][$r_id]['items'][$row['su_id']] = [
                    'caption' => $row['su_caption'],
                    'certs' => $row['certs'],
                ];
            } else {
                $groups['none']['items'][$row['su_id']] = [
                    'caption' => $row['su_caption'],
                    'certs' => $row['certs'],
                ];
            }
        }

        return $groups;
    }


}

