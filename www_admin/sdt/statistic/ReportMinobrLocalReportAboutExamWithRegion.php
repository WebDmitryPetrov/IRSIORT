<?php
require_once 'AbstrctReport.php';
/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 11:18
 */
class ReportMinobrLocalReportAboutExamWithRegion extends AbstractReport
{
    
    public function execute($from,$to,$region){
        $connection = Connection::getInstance();
        $region_sql = '';
//            var_dump($region);
        if ($region) {
            $region_sql = 'and su.region_id = ' . intval($region);
        }

        $statest = array();
        foreach (Act::getInnerStates() as $st) {
            $statest[] = "'" . $st . "'";
        }
        $statest = implode(', ', $statest);
//SUM(IF(sap.blank_number IS NOT NULL AND LENGTH(sap.blank_number)>0, 1,0)) AS total, 
        $sql = "
		 SELECT trim(su.name) as caption,
 su.id as univer_id, 
sho.id AS head_id, 

min(sa.created) as created , 
min(sa.testing_date) as testing_date,
r.caption as region,

 count(distinct sap.id) AS total, 
  SUM(IF( sap.document='certificate', 1,0)) AS success
 
FROM sdt_act_people sap
LEFT JOIN sdt_act_test sat ON sap.test_id = sat.id
LEFT JOIN sdt_act sa ON sa.id = sat.act_id
LEFT JOIN sdt_university su ON sa.university_id = su.id
left join regions r on r.id = su.region_id
LEFT JOIN sdt_head_center shc ON su.head_id = shc.id
left join sdt_head_org sho on shc.horg_id = sho.id

WHERE sa.created BETWEEN '".$connection->escape($connection->format_date($from))." 0:0:0' AND '".$connection->escape(
                $connection->format_date($to)
            )." 23:59:59' 
AND sa.test_level_type_id = 2
 AND sa.deleted = 0
  AND sap.deleted = 0 
  AND sat.deleted = 0
   AND shc.deleted = 0 
   and su.id not in (168, 305,574)
	AND sa.state IN (" . $statest . ")
	" . $region_sql . "
GROUP BY su.id
ORDER BY sho.id, trim(su.name)
";
            //die($sql);
        $res = $connection->query($sql);
//            AND sap.document = '" . ActMan::DOCUMENT_CERTIFICATE . "'
//            die($sql);
//and shc.id in (1,2,7,8)
//            $levels = array(
//                0 => array(
//                    13,
//                    16
//                ),
//                1 => array(
//                    14,
//                    17
//                ),
//                2 => array(
//                    15,
//                    18
//                ),
//            );

$horgs = HeadCenters::getHeadOrgs();
/*$captions = array(
1=>'Российский университет дружбы народов',
2=>'МГУ имени М.В. ЛОМОНОСОВА',
3=>'Тихоокеанский государственный университет',
4=>'Тюменский государственный университет',
5=>'Государственный институт русского языка им. А.С. Пушкина',
6=>'Санкт-Петербургский государственный университет',
7=>'Волгоградский государственный университет',
8=>'Казанский (Приволжский) Федеральный университет',
);*/
        $captions = HeadCenters::getStatistHONamesALL();

foreach($horgs as &$horg){
$horg['data']=array();
$horg['full']=$captions[$horg['id']];
	foreach($res as $row){
		if($row['head_id']!=$horg['id']) continue;
		$horg['data'][]=$row;
	}
	

}
reset($horgs);
//echo '<pre>';
      //var_dump($horgs);
     //die;


        
        return $horgs;
    }
}