<h1>Список префиксов всех головных центров</h1>
<table  class="table table-bordered  table-striped">
    <tr>
<!--        <th>№ п/п</th>-->
        <th>Головной центр</th>
        <th>Префикс справок</th>
        <th>Префикс сертификатов</th>
</tr>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 30.12.14
 * Time: 12:29
 * To change this template use File | Settings | File Templates.
 */
//$i=1;
foreach ($list as $item):
    $hctext=HeadCenterText::getByHeadCenterID($item->id);
    ?>
<tr>
<!--    <td>
        <?/*= $i++ */?>
    </td>-->
    <td>
        <?= $item->name ?>
    </td>
    <td>
        <?= $hctext->note_prefix ?>
    </td>
    <td>
        <?= $hctext->cert_reg_num_prefix ?>
    </td>
</tr>
    <? endforeach; ?>
    </table>