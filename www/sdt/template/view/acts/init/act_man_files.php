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
$meta = $Act->getMeta();
?>
<h3>�������� ������ �����������</h3>
<a href="?action=act_table&id=<?= $Act->id ?>" class="btn btn-info">��������� � ������� �������</a>

<form method="post" enctype="multipart/form-data">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>������� ���</th>
            <th>����������</th>
            <!--            <th>�����������</th>-->
        </tr>
        </thead>
        <tbody>
        <? foreach ($people as $man):
            $uploadedPhoto = \SDT\models\PeopleStorage\ManFile::getByUserType($man->id, \SDT\models\PeopleStorage\ManFile::TYPE_PHOTO)
            ?>
            <tr style="
            <? if ($uploadedPhoto): ?>
                    background-color: #98fb98            ;
            <? endif; ?>
                    ">
                <td>
                    <?= $man->surname_rus ?> <?= $man->name_rus ?>

                </td>
                <td>
                    <? if ($uploadedPhoto): ?>

                        <a href="<?= $uploadedPhoto->getDownloadURL() ?>">�������</a>   <br>
                    <? endif ?>

                    <input type="file" name="photo[<?= $man->id ?>]">

                    <div style="color:red">����� ����� < 3MB</div>
                </td>
                <!-- <td>
                    <? /* if ($uploadedAud = \SDT\models\PeopleStorage\ManFile::getByUserType($man->id,\SDT\models\PeopleStorage\ManFile::TYPE_AUDITION)): */
                ?>

                        <a href="<? /*= $uploadedAud->getDownloadURL() */
                ?>">�������</a>   <br>
                    <? /* endif */
                ?>

                    <input type="file" name="aud[<? /*= $man->id */
                ?>]">

                    <div style="color:red">����� ����� < 1MB</div>
                </td>-->
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
    <input type="submit" class="btn btn-success" value="���������">

    <div style="color:red">��������� ����� ������ < 100MB</div>
    <div style="color:red">����������� ���������� ���������� �� ��������: 1772�2480</div>
    <div style="color:red">��� ������� ���������� ������ � �������� ������ �������� ������������ �������� �������� �
        ������� < 100��
    </div>
</form>