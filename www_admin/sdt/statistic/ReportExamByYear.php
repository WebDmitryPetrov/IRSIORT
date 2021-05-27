<?php
require_once 'AbstrctReport.php';

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 10:58
 * Отчет 12
 */
class ReportExamByYear extends AbstractReport
{

    const INVALID = 'invalid';
    const PREFIX = 'v';
    CONST GREATER = 'greater';

    public function execute($from, $to, $ageString, $head_org = false)
    {
        $connection = Connection::getInstance();
        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);
        $whereHeadOrg = '';
        if ($head_org) {
            $whereHeadOrg = ' and shc.horg_id = ' . intval($head_org);
        }

        $ageIntervalsDate = $this->createIntervalsFromAgeString($ageString);
        $ageIntervals = $ageIntervalsDate[0];
        $maxAge = $ageIntervalsDate[2];
        $sql = "SELECT
 
         sap.document ,
  sdt_test_levels.caption,
  sdt_test_levels.id as level_id,
  count(distinct sap.id) as sdalo,
  sum(if(sap.blank_number is not null and length(sap.blank_number)>0 , 1,0)) as certs,
TIMESTAMPDIFF(year, sap.birth_date, IF(sap.testing_date IS NOT NULL AND sap.testing_date <>'0000-00-00', sap.testing_date, sa.testing_date )) AS age
FROM sdt_act_people sap
  left JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  left JOIN sdt_act sa
    ON sa.id = sat.act_id
  left JOIN sdt_university su
    ON sa.university_id = su.id
  left JOIN sdt_head_center shc
    ON su.head_id = shc.id
  left JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE sa.created BETWEEN '" . $connection->escape($connection->format_date($from)) . " 0:0:0' and '" . $connection->escape(
                $connection->format_date($to)
            ) . " 23:59:59'
and sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0
AND shc.deleted = 0
" . $whereHeadOrg . "
AND sa.state IN (" . $statest . ")

GROUP BY age,

         sat.level_id,
         sap.document ";

        $res = $connection->byRow($sql);


        $levels = ExamHelper::getLevelGroups();


        $template = array(

            'levels' => array(
                0 => 0,
                1 => 0,
                2 => 0,

            ),

            'note_levels' => array(
                0 => 0,
                1 => 0,
                2 => 0,

            ),


        );
        $result = [
            self::INVALID => $template,
            self::GREATER => $template,
        ];
        foreach ($ageIntervals as $key => $interval) {
            $result[self::PREFIX.$key] = $template;
        }
        $invalidAges = [];
        foreach ($res as $item) {
            $interval =  self::INVALID;
            $age = intval($item['age']);
            foreach ($ageIntervals as $k => $v) {
                if ($age >= $v[0] && $age <= $v[1]) {
                    $interval = self::PREFIX.$k;
                    break;
                }
            }
//            die(var_dump($maxAge));
            if ($age >= $maxAge) {
                $interval = self::GREATER;
            }

            if ($interval ==  self::INVALID) {
                $invalidAges[] = $age;
            }
            $cr = &$result[$interval];
            if ($item['document'] === ActMan::DOCUMENT_CERTIFICATE) {
                $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];

            } else {
                $cr['note_levels'][$levels[$item['level_id']]] += $item['sdalo'];

            }
        }
        $invalidAges = array_unique($invalidAges);

        return [$ageIntervalsDate, $result, $invalidAges];
    }


    private function createIntervalsFromAgeString($string)
    {
        $string = preg_replace('/[^\d,-]+/', '', $string);
        $result = [];
        $strings = explode(',', $string);
        foreach ($strings as $s) {
            $ss = explode('-', $s);
            $ss = array_map('intval', $ss);
            $ss = array_filter($ss);

            if (count($ss) != 2) continue;
            sort($ss);
            $result[] = $ss;
        }
        usort($result, function ($a, $b) {
            if ($a[0] == $b[0]) {
                return 0;
            }
            return ($a[0] < $b[0]) ? -1 : 1;
        });


        $small = array(
            $result[0][0] - 2,
            $result[0][0] - 1,
        );
        array_unshift($result, $small);
        $min = min(array_column($result, 0)) - 1;
        $max = max(array_column($result, 1)) + 1;
        return [$result, $min, $max];
    }
}