<?php
require_once 'AbstrctReport.php';
/**
 * Created by PhpStorm.
 * User: LWR
 * Date: 11.02.2020
 * Time: 14:03
 * Отчет 71
 */
class ReportMinobrSngReportAboutExamNotes extends AbstractReport
{
    public function execute($from, $to)
    {
        $connection = Connection::getInstance();
        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);



        $countries = array(
            4 => 4, //Азербайджан
            11 => 11, //Республика Армения
            17 => 17, //Республика Беларусь
            49 => 49, //Грузия
            69 => 69, //Казахстан
            76 => 76, //Кыргызская Республика
            90 => 90, //Латвия
            95 => 95, //Литва
            111 => 111, //Республика Молдова
            159 => 159, //Таджикистан
            167 => 167, //Туркменистан
            170 => 170, //Узбекистан
            171 => 171, //Украина
            190 => 190, //Эстония
            1 => 1 //ЛБГ,
        );

        $sql = "SELECT
 
  c.id,
  sap.document ,
  sdt_test_levels.caption,
  sdt_test_levels.id as level_id,
  count(distinct sap.id) as sdalo/*,
  sum(if(sap.blank_number is not null and length(sap.blank_number)>0 , 1,0)) as certs*/
FROM sdt_act_people sap

INNER JOIN country c
				ON c.id = sap.country_id


  left JOIN sdt_act_test sat
    ON sap.test_id = sat.id
  left JOIN sdt_act sa
    ON sa.id = sat.act_id
  left JOIN sdt_university su
    ON sa.university_id = su.id
  left JOIN sdt_test_levels
    ON sat.level_id = sdt_test_levels.id
WHERE 

c.id IN ( ".implode(',',$countries)." )

and 
/*
sa.created BETWEEN '" . $connection->escape($connection->format_date($from)) . " 0:0:0' and '" . $connection->escape(
                $connection->format_date($to)
            ) . " 23:59:59'*/
            
           
            sap.testing_date >= '" . $connection->escape($this->mysql_date($from)) . " 0:0:0'
			AND sap.testing_date <=  '" . $connection->escape(
                $this->mysql_date($to)
            ) . " 23:59:59'
            
and sa.test_level_type_id = 2
AND sa.deleted = 0
AND sap.deleted = 0
AND sat.deleted = 0

AND sa.state IN (" . $statest . ")

GROUP BY c.id,
         sat.level_id,
         sap.document ";
//            die($sql);
        $res = $connection->query($sql);





        $levels = ExamHelper::getLevelGroups();



//        $gc = HeadCenters::getStatistHcArrayAll();
        $gc = $countries;

        $template = array(

            'levels' => array(
                0 => 0,
                1 => 0,
                2 => 0,

            ),
            'certs' => 0,
            'note_levels' => array(
                0 => 0,
                1 => 0,
                2 => 0,

            ),
            'notes' => 0,
            'orgs' => 0,
        );


//        $result = HeadCenters::getStatistResultArrayAll($template);

        $result = array();
        foreach ($countries as $country)
        {
            $result[$country]=array(
              'caption' => Country::getByID($country),
              'data' => $template,
            );
        }


        foreach ($res as $item) {
            if (!array_key_exists($item['id'], $gc)) {
                continue;
            }

            $cr = &$result[$gc[$item['id']]]['data'];
            if ($item['document'] === ActMan::DOCUMENT_CERTIFICATE) {
                $cr['levels'][$levels[$item['level_id']]] += $item['sdalo'];
//                $cr['certs'] += $item['certs'];
            } else {
                $cr['note_levels'][$levels[$item['level_id']]] += $item['sdalo'];
//                $cr['notes'] += $item['certs'];
            }
        }


        return $result;
    }
}