<?php
/** @var ActMan $Man */





$people=array();
$i=0;
$blanks='';




$OUR_FULL_NAME=$OUR_SHORT_NAME=$CERTIFICATE_CITY='';
if (isset($_GET['h_id']) && is_numeric($_GET['h_id']))
{
    $sql = 'SELECT * FROM head_center_text where head_id=' . $_GET['h_id'];

    $res = mysql_query($sql) or die ($sql);
    if (mysql_num_rows($res)) {
        $row = mysql_fetch_assoc($res);
            $CERTIFICATE_CITY=$row['certificate_city'];
        $OUR_FULL_NAME=$row['our_full_name'];
        $OUR_SHORT_NAME=$row['our_short_name'];
    }

}


foreach($persons as $Man)
{

//    $Man = ActMan::getByID($person);



    $cert_type= $Man->getAct()->test_level_type_id;

    $type = 1;
    if (!empty($_GET['type']) &&is_numeric($_GET['type'])

    ) {
        $type = $_GET['type'];
    }




    $i++;




    $document_nomer = $Man->document_nomer; //íîìåð äîêóìåíòà

    $sign=ActSigning::getByID($type);
    if(!$sign) $sign=ActSigning::getByID(1);


    $rukovod_dolzhn_1 =  $sign->position;
    $rukovod_fio = $sign->caption;

    $rukovod_dolzhn_2 = $OUR_SHORT_NAME; //äîëæíîñòü ðóêîâîäèòåëÿ ñòðîêà 2


    $org = $OUR_FULL_NAME; // Îðãàíèçàöèÿ äëèííîå íàçâàíèå
    $org_first_line = ''; // Îðãàíèçàöèÿ äëèííîå íàçâàíèå 1ÿ ñòðîêà
    $org_second_line = ''; // Îðãàíèçàöèÿ äëèííîå íàçâàíèå 2ÿ ñòðîêà







    if (strlen($org) > 30) {
        $org_first_line=$org;
        $org_first_line=substr($org_first_line,0,30);

        $org_first_line=substr($org_first_line,0,strrpos($org_first_line,' '));

        $org_second_line=$org;
        $org_second_line=substr($org_second_line,strlen($org_first_line)+1);

    } else {
        $org_first_line=$org;
    }




    $fio_rus = trim($Man->surname_rus) . ' ' . trim($Man->name_rus); //ÔÈÎ ðóñ
    $fio_lat = trim($Man->surname_lat) . ' ' . trim($Man->name_lat); //ÔÈÎ ëàò
//    $fio_rus = 'Êóçíåöîâ Èâàí ØØØØØØ ØØØØØØ ØØ';
//    $fio_rus = 'ÝÉÑÑÀÂÈ ÌÎÕÀÌÅÄ ÀÁÄÅËÜÌÀÂÃÓÄ ÈÁÐÀÕÈÌ ÈÁÐÀÕÈÌ';
//    $fio_rus = 'ÑÅÉÅÄ  ÐÅÇÀÇÀÄ ÄÀËÀËÈ ÑÅÉÅÄ ÌÎÕÀÌÌÀÄ ÌÓÑÀ ÌÈÐ ÌÎÕÀÌÌÀÄ';
    $fio_rus = 'Àâîëáàåâ Ìîõîìàäòóðñèí Ìîõîìàäàìèíîâè÷';
   $fio_lat = 'Avolbaev Mohomadtursin';

    $font_resizer=1;
    if ($cert_type==1)
    {
        $max_length1=35;
        $max_length2=37;
        $max_length3=40;
    }
    elseif ($cert_type==2)
    {
        $max_length1=30;
        $max_length2=34;
        $max_length3=37;
    }
    else
    {
        $max_length1=150;
        $max_length2=150;
    }

    if (strlen($fio_rus)>$max_length2 || strlen($fio_lat)>$max_length2)
        $font_resizer=0.82;

    if ($font_resizer != 1)
        $max_length1=$max_length3;

    if ((strlen($fio_rus)>$max_length1 || strlen($fio_lat)>$max_length1))
        $fio_align='R';
    else
        $fio_align='C';




    if (strlen($fio_rus) > 323) {
        $fontsize1 = 10;
    } else {
        $fontsize1 = 14;
    }

    $country = $Man->getCountry()->name; //ñòðàíà
    if (strlen($country) > 32) {
        $fontsize2 = 10;
    } else {
        $fontsize2 = 14;
    }

    $uroven = $Man->getTest()->getLevel()->print; // óðîâåíü



    if (strlen($uroven) > 12) {
        $fontsize3 = 8;
    } else {
        $fontsize3 = 14;
    }



    $city=strtoupper($CERTIFICATE_CITY);




    $fio_rus = strtoupper($fio_rus); //ÔÈÎ ðóñ - áîëüøèå
    $fio_lat = strtoupper($fio_lat); //ÔÈÎ ëàò - áîëüøèå
    $uroven=strtoupper($uroven);
    $rukovod_dolzhn_2 = strtoupper($rukovod_dolzhn_2);

    $city=strtoupper($city);


    $print_date=date('d.m.Y',strtotime($Man->blank_date)).' ã.';

    if ($Man->valid_till == null || $Man->valid_till == '0000-00-00') $cert_date = 'ÁÅÑÑÐÎ×ÍÎ';
    else $cert_date=date('d.m.Y',strtotime($Man->valid_till)).' ã.';;


    $rukovod_dolzhn_1 =  strtoupper($rukovod_dolzhn_1);
    $rukovod_fio = strtoupper($rukovod_fio);





    $people[$i]['document_nomer']=$document_nomer;
    $people[$i]['sign']=$sign;
    $people[$i]['fio_rus']=$fio_rus;
    $people[$i]['fio_lat']=$fio_lat;
    $people[$i]['uroven']=$uroven;
    $people[$i]['org']=$org;
    $people[$i]['city']=$city;
    $people[$i]['print_date']=$print_date;
    $people[$i]['cert_date']=$cert_date;
    $people[$i]['rukovod_dolzhn_1']=$rukovod_dolzhn_1;
    $people[$i]['rukovod_dolzhn_2']=$rukovod_dolzhn_2;
    $people[$i]['rukovod_fio']=$rukovod_fio;
    $people[$i]['country']=$country;
    $people[$i]['org_first_line']=$org_first_line;
    $people[$i]['org_second_line']=$org_second_line;
    $people[$i]['font_resizer']=$font_resizer;
    $people[$i]['fio_align']=$fio_align;
    $blanks.='_'.$Man->blank_number;

}













$filename =  dirname(__FILE__).'\certificate_type_' . $cert_type . '.php';
//var_dump($cert_type,$filename,file_exists($filename));
if ($cert_type && file_exists($filename))
{
//    die('t2');
    require_once($filename);

}
else
{
    require_once('certificate_type_1.php');
}

$pdf_name=substr("Ñåðòèôèêàò".$blanks,0,250);

$pdf->Output($pdf_name.".pdf", "D");