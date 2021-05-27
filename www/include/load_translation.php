<?php

$headCenterID = apache_getenv('head_center');

define('CURRENT_HEAD_CENTER', $headCenterID);
define('PFUR_CHECKED_CHANGE_DATE', 2);

$sql = 'SELECT
  short_ip,
  short_vp,
  okved,
  okpo,
  okato,
  oktmo,
  ogrn,
  legal_address_1,
  check_receiver,
  check_n,
  bik,
  bank_1,
  bank_2,
  bank_3,
  bank_inn,
  bank_kpp,


  bank_kbk,
  address_1,
  long_ip,
  middle_ip,
  long_tp_print_act,
  our_short_name,
  our_full_name,
  help_caption,
  help_phone,
  help_email,
  rki_rudn_form,
  rki_rudn_name,
  cert_pril_rudn_form,
  cert_pril_rudn_name,
  long_tp_print_act_new,
  signing_center_name,
  signing_short_center_name,
  certificate_city,
  note_prefix,
  cert_reg_num_prefix,
  hc_prefix,
  hc_prefix_gc

FROM head_center_text where head_id=' . mysql_real_escape_string(CURRENT_HEAD_CENTER);
//die($sql);
$res = mysql_query($sql);
if (!mysql_num_rows($res)) {
    echo '<h1>Необходимо заполнить тестовые данные о головном центре</h1>';
    define('TEXT_HEADCENTER_SHORT_IP', '');
    define('TEXT_HEADCENTER_SHORT_VP', '');
    define('TEXT_HEADCENTER_OKVED', '');
    define('TEXT_HEADCENTER_OKPO', '');
    define('TEXT_HEADCENTER_OKATO', '');
    define('TEXT_HEADCENTER_OKTMO', '');
    define('TEXT_HEADCENTER_OGRN', '');
    define('TEXT_HEADCENTER_LEGAL_ADDRESS_1', '');
    define('TEXT_HEADCENTER_CHECK_RECEIVER', '');
    define('TEXT_HEADCENTER_CHECK_N', '');
    define('TEXT_HEADCENTER_BIK', '');
    define('TEXT_HEADCENTER_BANK_1', '');
    define('TEXT_HEADCENTER_BANK_2', '');
    define('TEXT_HEADCENTER_BANK_3', '');
    define('TEXT_HEADCENTER_BANK_INN', '');
    define('TEXT_HEADCENTER_BANK_KPP', '');
    define('TEXT_HEADCENTER_BANK_KBK', '');
    define('TEXT_HEADCENTER_ADDRESS_1', '');
    define('TEXT_HEADCENTER_LONG_IP', '');
    define('TEXT_HEADCENTER_MIDDLE_IP', '');
    define('TEXT_HEADCENTER_LONG_TP_PRINT_ACT', '');

    define('OUR_SHORT_NAME', '');
    define('OUR_FULL_NAME', '');
    define('SDT_HELP_CAPTION', '');
    define('SDT_HELP_PHONE', '');
    define('SDT_HELP_EMAIL', '');


    define('RKI_RUDN_FORM', '');
    define('RKI_RUDN_NAME', '');
    define('CERT_PRIL_RUDN_FORM', '');
    define('CERT_PRIL_RUDN_NAME', '');
    define('TEXT_HEADCENTER_LONG_TP_PRINT_ACT_NEW', '');
    define('SIGNING_CENTER_NAME', '');
    define('SIGNING_SHORT_CENTER_NAME', '');
    define('CERTIFICATE_CITY', '');
    define('NOTE_PREFIX', '');
    define('CERTIFICATE_REG_NUMBER_PREFIX', '');
    define('HC_PREFIX', '');
    define('HC_PREFIX_GC', '');
} else {
    $row = mysql_fetch_assoc($res);
    define('TEXT_HEADCENTER_SHORT_IP', $row['short_ip']);
    define('TEXT_HEADCENTER_SHORT_VP', $row['short_vp']);
    define('TEXT_HEADCENTER_OKVED', $row['okved']);
    define('TEXT_HEADCENTER_OKPO', $row['okpo']);
    define('TEXT_HEADCENTER_OKATO', $row['okato']);
    define('TEXT_HEADCENTER_OKTMO', $row['oktmo']);
    define('TEXT_HEADCENTER_OGRN', $row['ogrn']);
    define('TEXT_HEADCENTER_LEGAL_ADDRESS_1', $row['legal_address_1']);
    define('TEXT_HEADCENTER_CHECK_RECEIVER', $row['check_receiver']);
    define('TEXT_HEADCENTER_CHECK_N', $row['check_n']);
    define('TEXT_HEADCENTER_BIK', $row['bik']);
    define('TEXT_HEADCENTER_BANK_1', $row['bank_1']);
    define('TEXT_HEADCENTER_BANK_2', $row['bank_2']);
    define('TEXT_HEADCENTER_BANK_3', $row['bank_3']);
    define('TEXT_HEADCENTER_BANK_INN', $row['bank_inn']);
    define('TEXT_HEADCENTER_BANK_KPP', $row['bank_kpp']);
    define('TEXT_HEADCENTER_BANK_KBK', $row['bank_kbk']);
    define('TEXT_HEADCENTER_ADDRESS_1', $row['address_1']);
    define('TEXT_HEADCENTER_LONG_IP', $row['long_ip']);
    define('TEXT_HEADCENTER_MIDDLE_IP', $row['middle_ip']);
    define('TEXT_HEADCENTER_LONG_TP_PRINT_ACT', $row['long_tp_print_act']);

    define('OUR_SHORT_NAME', $row['our_short_name']);
    define('OUR_FULL_NAME', $row['our_full_name']);
    define('SDT_HELP_CAPTION', $row['help_caption']);
    define('SDT_HELP_PHONE', $row['help_phone']);
    define('SDT_HELP_EMAIL', $row['help_email']);


    define('RKI_RUDN_FORM', $row['rki_rudn_form']);
    define('RKI_RUDN_NAME', $row['rki_rudn_name']);
    define('CERT_PRIL_RUDN_FORM', $row['cert_pril_rudn_form']);
    define('CERT_PRIL_RUDN_NAME', $row['cert_pril_rudn_name']);
    define('TEXT_HEADCENTER_LONG_TP_PRINT_ACT_NEW', $row['long_tp_print_act_new']);

    define('SIGNING_CENTER_NAME', $row['signing_center_name']);
    define('SIGNING_SHORT_CENTER_NAME', $row['signing_short_center_name']);
    define('CERTIFICATE_CITY', $row['certificate_city']);
    define('NOTE_PREFIX',  $row['note_prefix']);
    define('CERTIFICATE_REG_NUMBER_PREFIX',  $row['cert_reg_num_prefix']);
    define('HC_PREFIX',  $row['hc_prefix']);
    define('HC_PREFIX_GC',  $row['hc_prefix_gc']);
}