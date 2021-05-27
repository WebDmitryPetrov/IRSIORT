<?php
/** @var ActMan $Man */


$people = array();
$i = 0;
$blanks = '';
$encoding='cp1251';
foreach ($persons as $Man) {

    if ($Man->blank_date == '0000-00-00' || is_null($Man->blank_date)) {
        $Man->blank_date = date("Y-m-d");
        $Man->setValidTill();
        $Man->save();
    }
//    $Man = ActMan::getByID($person);
    $Man=CertificateDuplicate::checkForDuplicates($Man); //!!!! îáÿçàòåëüíî ïîñëå ñîõðàíåíèÿ

    $cert_type = Act::getActTestLevelID($Man->act_id);

    $type = 1;
    if (!empty($_GET['type']) && is_numeric($_GET['type'])

    ) {
        $type = $_GET['type'];
    }


    /*if (HeadCenter::getByID(CURRENT_HEAD_CENTER)->pfur_api && $Man->cert_signer)
    {
        $type = $Man->cert_signer;
    }*/

    $i++;


    $document_nomer = $Man->document_nomer; //íîìåð äîêóìåíòà

    $sign = ActSigning::getByID($type);
    if (!$sign) $sign = ActSigning::getByID(1);

    $Man->saveCert_signer($type);


    $rukovod_dolzhn_1 = $sign->position;
    $rukovod_fio = $sign->caption;

    $rukovod_dolzhn_2 = OUR_SHORT_NAME; //äîëæíîñòü ðóêîâîäèòåëÿ ñòðîêà 2


    $org = OUR_FULL_NAME; // Îðãàíèçàöèÿ äëèííîå íàçâàíèå
    $org_first_line = ''; // Îðãàíèçàöèÿ äëèííîå íàçâàíèå 1ÿ ñòðîêà
    $org_second_line = ''; // Îðãàíèçàöèÿ äëèííîå íàçâàíèå 2ÿ ñòðîêà


    if (strlen($org) > 30) {
        $org_first_line = $org;
        $org_first_line = substr($org_first_line, 0, 30);

        $org_first_line = substr($org_first_line, 0, strrpos($org_first_line, ' '));

        $org_second_line = $org;
        $org_second_line = substr($org_second_line, strlen($org_first_line) + 1);

    } else {
        $org_first_line = $org;
    }


    $fio_rus = trim($Man->getSurname_rus()) . ' ' . trim($Man->getName_rus()); //ÔÈÎ ðóñ
    $fio_lat = trim($Man->getSurname_lat()) . ' ' . trim($Man->getName_lat()); //ÔÈÎ ëàò
//    $fio_rus = 'Êóçíåöîâ Èâàí ØØØØØØ ØØØØØØ ØØ';
//    $fio_rus = 'ÝÉÑÑÀÂÈ ÌÎÕÀÌÅÄ ÀÁÄÅËÜÌÀÂÃÓÄ ÈÁÐÀÕÈÌ ÈÁÐÀÕÈÌ';
//    $fio_rus = 'ÑÅÉÅÄ  ÐÅÇÀÇÀÄ ÄÀËÀËÈ ÑÅÉÅÄ ÌÎÕÀÌÌÀÄ ÌÓÑÀ ÌÈÐ ÌÎÕÀÌÌÀÄ';
//    $fio_rus = 'ÑÅÉÅÄ  ÐÅÇÀÇÀÄ ÄÀËÀËÈ ÑÅÉÅÄ     ddd';
//    $fio_lat = 'EISSAVI MOHAMED ABDELMAVGOD IBRAHIM IBRAHIM  â';

    $font_resizer = 1;
    if ($cert_type == 1) {
        $max_length1 = 35;
        $max_length2 = 37;
        $max_length3 = 40;
    } elseif ($cert_type == 2) {
        $max_length1 = 30;
        $max_length2 = 34;
        $max_length3 = 37;
    } else {
        $max_length1 = 150;
        $max_length2 = 150;
    }

    if (strlen($fio_rus) > $max_length2 || strlen($fio_lat) > $max_length2)
        $font_resizer = 0.82;

    if ($font_resizer != 1)
        $max_length1 = $max_length3;

    if ((strlen($fio_rus) > $max_length1 || strlen($fio_lat) > $max_length1))
        $fio_align = 'R';
    else
        $fio_align = 'C';


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


    $city = strtoupper(CERTIFICATE_CITY);
    $city = mb_strtoupper(CERTIFICATE_CITY, $encoding);


    //$fio_rus = strtoupper($fio_rus); //ÔÈÎ ðóñ - áîëüøèå
    //$fio_rus = strtoupper($fio_rus); //ÔÈÎ ðóñ - áîëüøèå
	 $fio_rus =  mb_strtoupper($fio_rus,$encoding);
    $fio_lat = strtoupper($fio_lat); //ÔÈÎ ëàò - áîëüøèå
    $uroven = strtoupper($uroven);
    //$rukovod_dolzhn_2 = strtoupper($rukovod_dolzhn_2);
    $rukovod_dolzhn_2 = mb_strtoupper($rukovod_dolzhn_2,$encoding);

    $city = strtoupper($city);


    $print_date = date('d.m.Y', strtotime($Man->blank_date)) . ' ã.';

    if ($Man->valid_till == null || $Man->valid_till == '0000-00-00') $cert_date = 'ÁÅÑÑÐÎ×ÍÎ';
    else $cert_date = date('d.m.Y', strtotime($Man->valid_till)) . ' ã.';
    ;


    //$rukovod_dolzhn_1 = strtoupper($rukovod_dolzhn_1);
    $rukovod_dolzhn_1 = mb_strtoupper($rukovod_dolzhn_1,$encoding);
    //$rukovod_fio = strtoupper($rukovod_fio);
    $rukovod_fio =  mb_strtoupper($rukovod_fio,$encoding);


    $people[$i]['document_nomer'] = $document_nomer;
    $people[$i]['sign'] = $sign;
    $people[$i]['fio_rus'] = $fio_rus;
    $people[$i]['fio_lat'] = $fio_lat;
    $people[$i]['uroven'] = $uroven;
    $people[$i]['org'] = $org;
    $people[$i]['city'] = $city;
    $people[$i]['print_date'] = $print_date;
    $people[$i]['cert_date'] = $cert_date;
    $people[$i]['rukovod_dolzhn_1'] = $rukovod_dolzhn_1;
    $people[$i]['rukovod_dolzhn_2'] = $rukovod_dolzhn_2;
    $people[$i]['rukovod_fio'] = $rukovod_fio;
    $people[$i]['country'] = $country;
    $people[$i]['org_first_line'] = $org_first_line;
    $people[$i]['org_second_line'] = $org_second_line;
    $people[$i]['font_resizer'] = $font_resizer;
    $people[$i]['fio_align'] = $fio_align;
    $people[$i]['blank_number'] = $Man->getBlank_number();
    $blanks .= '_' . $Man->getBlank_number();

}


$filename = dirname(__FILE__) . '\certificate_type_' . $cert_type . '.php';
//var_dump($cert_type,$filename,file_exists($filename));
if ($cert_type && file_exists($filename)) {
//    die('t2');
    require_once($filename);

} else {
    require_once('certificate_type_1.php');
}


$pdf_name = substr("Ñåðòèôèêàò" . $blanks, 0, 250);

$pdf->Output($pdf_name . ".pdf", "D");