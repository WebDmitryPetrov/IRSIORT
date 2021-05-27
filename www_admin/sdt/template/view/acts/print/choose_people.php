<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 11.12.14
 * Time: 12:49
 * To change this template use File | Settings | File Templates.
 */

if ($persons[0]->document=='certificate') $echo_cert=1;else $echo_cert=0;

$legend = '�������� ����������� ��� ������';
echo '<h1>'.$legend.'</h1>';
if ($echo_cert==1) echo '<h2 style="color:red">����������� � ��������� ������� ������ � ��������</h2>';
echo '<form method="post">';
echo '<table class="table table-bordered  table-striped">';
echo '<thead>';
echo '<th>� �/�</th>';
echo '<th>��������</th>';
echo '<th>��� (���/���)</th>';
if ($echo_cert==1) echo '<th>������ ������� ������������</th>';
echo '</thead>';
$i=0;
foreach ($persons as $Man)
{
   echo '<tr>
   <td>'.++$i.'</td>
   <td>
   <input type="checkbox" name="pers[]" value="'.$Man->id.'" checked="checked">
   </td>
   <td>
   '.$Man->surname_rus.' '.$Man->name_rus.' / '.$Man->surname_lat.' '.$Man->name_lat.'
   </td>';
    if ($echo_cert==1) echo '<td>'.$Man->blank_number.'</td>';
    echo '</tr>';
}
$signing=ActSigning::getByID((int)$_GET['type']);
if ($echo_cert==1) $colspan=4; else $colspan=3;
echo '<tr style="font-weight:bold" ><td colspan='.$colspan.'><i>�������������:</i> '.$signing->position.' '.$signing->caption.'</td></tr>';
echo '</table>';
echo '<input type="submit" value="�������">';
echo '</form>';
