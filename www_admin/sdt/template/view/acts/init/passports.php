<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m.kulebyakin
 * Date: 15.10.13
 * Time: 14:46
 * To change this template use File | Settings | File Templates.
 */

/** @var Act $Act */
/** @var ActMan[] $people */

?>
<h3>�������� ������ ���������� (������� � ��.)</h3>
<a href="?action=act_table&id=<?=$Act->id?>" class="btn btn-info">��������� � ������� �������</a>
<form method="post" enctype="multipart/form-data">
<table class="table table-bordered">
    <thead>
    <tr>
        <th>������� ���</th>
        <th>���������</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($people as $man): ?>
        <tr style="
        <? if ($man->getFilePassport()): ?>
            background-color: #98fb98            ;
            <?endif;?>
        ">
            <td>
                <?= $man->surname_rus ?> <?= $man->name_rus ?>
                <? if ($man->getFilePassport()): ?>
                    <br>
                    <a href="<?= $man->getFilePassport()->getDownloadURL() ?>">�������</a>
                <? endif ?>
            </td>
            <td><input type="file" name="file[<?=$man->id?>]">
                <div style="color:red">����� ����� < 10MB</div>
            </td>
        </tr>
    <? endforeach ?>
    </tbody>
</table>
    <input type="submit" class="btn btn-success" value="���������">
    <div style="color:red">��������� ����� ������ < 250MB</div>
</form>