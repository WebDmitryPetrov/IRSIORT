<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 22.07.15
 * Time: 18:01
 * To change this template use File | Settings | File Templates.
 */


/** @var DublAct $dubl */
/** @var DublActMan $man */
$Act = $dubl;
$dogovor = University_dogovor::getByID($Act->center_dogovor_id);
?>
<?if ($dubl->summary_table_id):?>
    <a class="btn btn-danger  btn-color-black" target="_blank"
       href="dubl.php?action=dubl_act_summary_table&id=<?php echo $dubl->id; ?>">�����������/���������� ������� ��������</a>
<?endif?>

<table class="table table-bordered">
    <tr>
        <th>��������� �����</th>
        <td><?php echo $Act->getUniversity()->getLegalInfo()['name_parent'] ?>
        </td>
    </tr>
    <tr>
        <th>������� ���������� ������</th>
        <td><?= $dogovor->number .' '. $C->date($dogovor->date) .' '. $dogovor->caption ?></td>
    </tr>
    <tr>
        <th>����������� ����, ������������ ���</th>
        <td><?php echo $Act->official; ?></td>
    </tr>
    <tr>
        <th>���� ����</th>
        <td><?php echo $C->date($Act->created);?></td>
    </tr>
    <tr>
        <th>���� ���������� ��������</th>
        <td><?php echo $C->dateTime($Act->getCheckDate(),true) ?></td>
    </tr>
    <tr>
        <th>����� �����</th>
        <td><?php echo $Act->invoice_index.'/'.$Act->invoice ?></td>
    </tr>
    <tr>
        <th>���� �����</th>
        <td><?php echo $C->date($Act->invoice_date,true);?></td>
    </tr>
    <tr>
        <th>����� �����</th>
        <td><?php echo $Act->getTotal();?></td>
    </tr>

    <tr>
        <th>�����������</th>
        <td><?= $Act->comment ?></td>
    </tr>
</table>
<? if ($dubl->getFileRequest()): ?>
    <a href="<?= $dubl->getFileRequest()->getDownloadURL() ?>">������� ��������� ������ ������������</a>
<? endif; ?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>���</th>
        <th>��� ����������</th>
        <th>���������</th>
        <th>�������</th>
        <!--        <th></th>-->
    </tr>
    </thead>
    <tbody>
    <? foreach ($people as $man): ?>
        <tr>
            <td><?= $man->getFioRusOld(); ?><br><?= $man->getFioLatOld() ?></td>
            <td><?= (intval($man->is_changed)) ? $man->getFioRusNew() . '<br>' . $man->getFioLatNew() : ''; ?></td>
            <td><?= (intval($man->file_request_id)) ? '<a href="' . $man->getFileRequest()->getDownloadURL() . '">�������</a>' : '���'; ?></td>
            <td><?= (intval($man->file_passport_id)) ? '<a href="' . $man->getFilePassport()->getDownloadURL() . '">�������</a>' : '���'; ?></td>
            <!--            <td>
                <a href="dubl.php?action=dubl_man_edit&man_id=<? /*=$man->id*/ ?>&id=<? /*=$dubl->id*/ ?>" class="btn btn-warning btn-block">������������� ���</a>

                <a href="dubl.php?action=dubl_man_delete&man_id=<? /*=$man->id*/ ?>&id=<? /*=$dubl->id*/ ?>" class="btn btn-danger btn-block">�������</a>
            </td>-->
        </tr>
    <? endforeach; ?>
    </tbody>
</table>




