<?php

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 04.07.2016
 * Time: 11:09
 */
class CotractsByYearAndMonth extends AbstractReport
{
    public function execute($from, $to)
    {
        $connection = Connection::getInstance();


        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }

        $statest = implode(', ', $statest);
        $levels = implode(', ', ExamHelper::getRNRlevels());

        $sql = "

SELECT YEAR(sa.created) AS y, MONTH(sa.created) AS m, MAX(sat.price) AS p, 
  sud.number, sud.date,su.id as lc_id, su.name AS lc_name, sud.id as d_id, shc.id AS h_id, shc.short_name AS h_name, COUNT(DISTINCT sa.id) AS cc
FROM sdt_act_test sat
LEFT JOIN sdt_act sa ON sa.id = sat.act_id
LEFT JOIN sdt_university_dogovor sud ON sud.id = sa.university_dogovor_id
LEFT JOIN sdt_university su ON su.id = sa.university_id
LEFT JOIN sdt_head_center shc ON shc.id= su.head_id
WHERE sat.level_id IN ($levels) AND sa.created BETWEEN '" . $connection->format_date($from) . " 0:0:0' AND  '" . $connection->format_date($to) . " 23:59:59' AND sa.deleted=0 
  and sa.state IN ($statest) 
  AND su.is_head=0
  and sat.deleted=0
  AND shc.horg_id=1 and su.id <> 168
GROUP BY y, m, sud.id, su.id, shc.id
ORDER BY su.name, sud.date, y, m

";
        $horgs = HeadCenters::getStatistHCListArrayPfur([]);

        $hid = [];
        foreach ($horgs as $key => $h) {
            foreach ($h['id'] as $id) $hid[$id] = $key;
        }
//echo $sql;die;
//        $month = range(1, 12);
        $emptyData = array_fill_keys(range(1, 12), 0);
        $emptyData = array_fill_keys(range(date('Y', strtotime($from)), date('Y', strtotime($to))), $emptyData);
//        die(var_dump($emptyData));
//        $years = range(date('Y', strtotime($from)), date('Y', strtotime($to)));
//        $emptyData = [];
//        foreach($years as $y){
//            $emptyData[$y]=[];
//            foreach($month as $m){
//
//            }
//
//            array_fill_keys()
//        }
//
//        die(var_dump($horgs,$month,$years));
        $data = $connection->byRow($sql);;
        foreach ($data as $row) {
//            die(var_dump($row));
            $lc_id = $row['lc_id'];
            $h_id = $row['h_id'];
            if (empty($hid[$h_id]) || empty($horgs[$hid[$h_id]])) die(var_dump("Найден не существующий центр ", $row, $hid));

            if (!array_key_exists($lc_id, $horgs[$hid[$h_id]]['result'])) {
                $horgs[$hid[$h_id]]['result'][$lc_id] = [
                    'caption' => $row['lc_name'],
                    'data' => [],
                ];
            }
//            var_dump($horgs[$hid[$h_id]]);die;
            $d_id = $row['d_id'];

            if (!array_key_exists($d_id, $horgs[$hid[$h_id]]['result'][$lc_id]['data'])) {
                $horgs[$hid[$h_id]]['result'][$lc_id]['data'][$d_id] = [
                    'caption' => $row['number'],
                    'date' => $row['date'],
                    'price' => [],
                    'data' => $emptyData,
                ];
            }
            $horgs[$hid[$h_id]]['result'][$lc_id]['data'][$d_id]['price'][] = $row['p'];
            $horgs[$hid[$h_id]]['result'][$lc_id]['data'][$d_id]['data'][$row['y']][$row['m']] += $row['cc'];
        }

//        die(print_r($horgs));
        return [
            'years' => array_keys($emptyData),
            'data' => $horgs,
            'norm' => $this->denormolize($horgs),
        ];
    }

    private function denormolize($data)
    {
        $result = [];
        foreach ($data as $key => $h) {
            $result[$key] = [
                'caption' => $h['caption'],
                'rows' => [],
            ];

            $rows = &$result[$key]['rows'];

            $s = 0;

            foreach ($h['result'] as $lc) {
                $s++;
                $p = 0;
                foreach ($lc['data'] as $d) {

                    $date = $this->date($d['date']);
                    $price = max($d['price']);
                    $years = array_filter($d['data'], function ($year) {
                        return array_sum($year);
                    });

                    foreach ($years as $yn => $y) {

                        $row = [
                            'n' => $p ? '' : $s,
                            'c' => $lc['caption'],
                            'd' => $d['caption'],
                            'dd' => $date,
                            'p' => $price,
                            'y' => $yn,
                        ];

                        foreach ($y as $m => $v) {
                            $row['m' . $m] = $v;
                        }
                        $rows[] = $row;
                        $p++;
                    }

//                    die(var_dump($rows));
                }
            }
        }

        return $result;
    }
}