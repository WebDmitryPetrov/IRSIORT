<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 11.12.14
 * Time: 12:49
 * To change this template use File | Settings | File Templates.
 */

if ($persons[0]->document=='certificate') $echo_cert=1;else $echo_cert=0;

$legend = 'Выберите тестируемых для печати '.$header_print['title'];
echo '<h1>'.$legend.'</h1>';
if ($echo_cert==1 && $header_print['show']==1) echo '<h2 style="color:red">Расположите в указанном порядке бланки в принтере</h2>';
echo '<form method="post">';
echo '<table class="table table-bordered  table-striped">';
echo '<thead>';
echo '<th>№ п/п</th>';
echo '<th>Печатать</th>';
echo '<th>ФИО (рус/лат)</th>';
if ($echo_cert==1) echo '<th>Номера бланков сертификатов</th>';
echo '</thead>';
$i=0;
foreach ($persons as $Man)
{
if ($echo_cert==1 && empty($Man->blank_number)) continue;
   echo '<tr>
   <td>'.++$i.'</td>
   <td>
   <input type="checkbox" name="pers[]" value="'.$Man->id.'" checked="checked">
   </td>
   <td>
   '.$Man->surname_rus.' '.$Man->name_rus.' / '.$Man->surname_lat.' '.$Man->name_lat.'
   </td>';

$blank_type=@BlankType::getBlankType($Act->test_level_type_id,$Man->blank_number);
if (empty($blank_type->default) && !empty($blank_type->name) && $header_print['show']==1) $echo_blank_type = '<span style="color:red">'.$blank_type->name.'</span><br>';
else $echo_blank_type = '';


    if ($echo_cert==1) echo '<td>'.$echo_blank_type.$Man->blank_number.'</td>';
    echo '</tr>';
}
$signing=ActSigning::getByID((int)$_GET['type']);
if ($echo_cert==1) $colspan=4; else $colspan=3;
echo '<tr style="font-weight:bold" ><td colspan='.$colspan.'><i>Подписывающий:</i> '.$signing->position.' '.$signing->caption.'</td></tr>';
echo '</table>';
echo '<input type="submit" value="Выбрать">';
echo '</form>';
