<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m.kulebyakin
 * Date: 15.10.13
 * Time: 14:46
 * To change this template use File | Settings | File Templates.
 */

/** @var DublAct $Act */
/** @var ActMan[] $people */

?>
<h3>�������� ������ ���������</h3>
<a href="?action=dubl_show&id=<?= $Act->id ?>" class="btn btn-info">���������</a>
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
            <? if ($man->getFileRequest()): ?>
                background-color: #98fb98            ;
            <? endif; ?>
                ">
                <td>
                    <?= $man->getSurnameRus() ?> <?= $man->getNameRus() ?>
                    <? if ($man->getFileRequest()): ?>
                        <br>
                        <a href="<?= $man->getFileRequest()->getDownloadURL() ?>">�������</a>
                    <? endif ?>
                </td>
                <td><input type="file" name="file[<?= $man->id ?>]">


                    <div style="color:red">����� ����� < 1MB</div>
                </td>
            </tr>

        <? endforeach ?>
        <!--<tr style="
        <?/* if ($Act->file_request_id): */?>
            background-color: #98fb98            ;
        <?/* endif; */?>
            ">
            <td>
                ���� ���������
                <?/* if ($Act->file_request_id): */?>
                    <br>
                    <a href="<?/*=File::getByID($Act->file_request_id)->getDownloadURL() */?>">�������</a>
                <?/* endif */?>
            </td>
            <td><input type="file" name="request">
                <div style="color:red">����� ����� < 5MB</div>
            </td>
        </tr>-->
        </tbody>
    </table>
    <input type="submit" class="btn btn-success" value="���������">

    <div style="color:red">��������� ����� ������ < 20MB</div>
    <div style="color:red">���  ������� ���������� ������ � �������� ������ �������� ������������ �������� �������� � ������� < 50�� </div>
</form>