<?php


$cl = function ($input) {

    $word = mb_convert_encoding('УРОВЕНЬ:', 'cp1251', 'UTF-8');
    $input = preg_replace('/^' . $word . '/', '', $input);

    return $input;
};

$C = AbstractController::getInstance();

foreach (TestLevels::getAllGrouped('desc') as $tl) {
    $lvls[] = $cl($tl->print);
}

$table_head = array();
$table_head[] = 'п/п';
$table_head[] = 'id';
$table_head[] = 'Номер сессии';
$table_head[] = 'Дата договора';
$table_head[] = 'Номер договора';
$table_head[] = 'Наименование организации контрагента';
$table_head[] = 'ИНН контрагента';
$table_head[] = 'Дата акта (сводного протокола)';
$table_head[] = 'Номер счета';
$table_head[] = 'Дата счета';
$table_head[] = 'Сумма счета';

foreach ($lvls as $lvl) {
    $lvl = $C->utf_encode($lvl);
    $table_head[] = 'Количество человек по ' . $lvl;
}

foreach ($lvls as $lvl) {
    $lvl = $C->utf_encode($lvl);
    $table_head[] = 'Количество человек с пересдачей по ' . $lvl;
}

foreach ($lvls as $lvl) {
    $lvl = $C->utf_encode($lvl);
    $table_head[] = 'Из них бесплатно по ' . $lvl;
}

//$table_head[]='Из них бесплатно'; //временно убран на время проверки


$table_head[] = 'Дата тестовой сессии';
$table_head[] = 'Оплачено';
$table_head[] = 'Номер платежного поручения';
$table_head[] = 'Дата платежа';
$table_head[] = 'Дата создания тестовой сессии';
$table_head[] = 'Удалено';

//$array = $acts;
//$array=array(0=>array('asd','asdasd'),1=>array('sва','лоывраоырваоылвоар'));
$caption = 'Реестр актов за ' . $m . '.' . $y;

$array = array();
/*--------------------------------*/

if ($list):

    $i = 0;
    foreach ($list as $item):
        /** @var Act $item */
        $levels = ActTests::getCountsGroupedByLevel($item->id);


        ++$i;
        $array[$i][] = $i;
        $array[$i][] = $item->id;
        $array[$i][] = $C->utf_encode($item->number);
        $array[$i][] = $C->date($item->getUniversityDogovor()->date);
        $array[$i][] = $C->utf_encode($item->getUniversityDogovor()->number);
        $array[$i][] = $C->utf_encode($item->getUniversity()->getLegalInfo()['name_parent']);
        $array[$i][] = $C->utf_encode($item->getUniversity()->getLegalInfo()['inn']);
        $array[$i][] = $C->date($item->actDate());
        $array[$i][] = $C->utf_encode($item->invoice_index?$item->invoice_index . '/' . $item->invoice:'');
        $array[$i][] = $C->date($item->invoice_date, true);
        $array[$i][] = $C->utf_encode($item->amount_contributions);

        foreach ($lvls as $tl) {
            $people = '';
            foreach ($levels as $l):
                if ($tl == $cl($l['print']) && !empty($l['pf']))
                    $people = $C->utf_encode($l['pf']);
            endforeach;
            $array[$i][] = $people;
        }

        foreach ($lvls as $tl) {
            $people = '';
            foreach ($levels as $l):
                if ($tl == $cl($l['print']) && !empty($l['pr']))
                    $people = $C->utf_encode($l['pr']);
            endforeach;
            $array[$i][] = $people;
        }


        foreach ($lvls as $tl) {
            $people = '';
            foreach ($levels as $l):
                if ($tl == $cl($l['print']) && !empty($l['fpr']))
                    $people = $C->utf_encode($l['fpr']);
            endforeach;
            $array[$i][] = $people;
        }

        /* foreach ($lvls as $tl) {
             foreach ($levels as $l):
                 if ($item->id==518405) echo 'r=='.$tl.'=='.$cl($l['print']).'<br>';
                 if ($tl == $cl($l['print']) && !empty($l['pr']))
                     $array[$i][]=$C->utf_encode($l['pr']);
             else $array[$i][]='';
             endforeach;
         }

         foreach ($lvls as $tl) {
             foreach ($levels as $l):
                 if ($tl == $cl($l['print']) && !empty($l['fpr']))
                     $array[$i][]=$C->utf_encode($l['fpr']);
             else $array[$i][]='';
             endforeach;
         }
         */
//        if ($item->id==518405)die();
        /* if (Reexam_config::isShowInAct($item->test_level_type_id)): ?>
        Из них бесплатно:
        <?
        $f = true;
        foreach ($levels as $l):
            if ($l['fpr']):
                if (!$f):?>;<?
                else: $f = false; endif; ?>
                <nobr><?= $cl($l['print']) ?>: <?= $l['fpr'] ?></nobr>
                <?
            endif;
        endforeach; ?>
    <? endif; */ //временно убрано на момент проверки

        $array[$i][] = $C->date($item->testing_date);
        $array[$i][] = $item->paid ? 'Да' : 'Нет';
        $array[$i][] = $C->utf_encode($item->platez_number);
        $array[$i][] = $C->date($item->platez_date, true);
        $array[$i][] = $C->date($item->created, true);
        $array[$i][] = $item->deleted ? 'Удален' : '';

    endforeach;
endif;
//die(var_dump($array));
require_once DC . '/sdt/lib/excel.php';