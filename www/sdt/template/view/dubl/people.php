<?
/** @var DublAct $dubl */
/** @var DublActMan[] $people */
?>
<a href="/sdt/dubl.php?action=dubl&type=<?=$dubl->test_level_type_id;?>" class="btn btn-info">����� � ��������</a>
<h1>������ �� ��������� �� <?=$C->dateTime($dubl->created);?></h1>
<a href="dubl.php?action=dubl_man_search&id=<?=$dubl->id?>" class="btn btn-success">��������</a>
<? if (count($people)):?>
<a href="dubl.php?action=dubl_upload&id=<?=$dubl->id?>" class="btn btn-info">��������� ����� ���������</a>
<? endif; ?>
<!--<a href="dubl.php?action=dubl_create" class="btn btn-success">������� ������ �� ��������</a>-->
<table class="table table-bordered">
    <thead>
    <tr>
        <th>���</th>
        <th>���� ���������</th>
        <th>���������</th>
        <th>�������</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach($people as $man):?>
        <tr>
            <td><?=$man->getFioRus(); ?></td>
            <td><?=(intval($man->is_changed))?'��':'���'; ?></td>
            <td><?=(intval($man->file_request_id))?'<a href="'.$man->getFileRequest()->getDownloadURL().'">�������</a>':'���'; ?></td>
            <td><?=(intval($man->file_passport_id))?'<a href="'.$man->getFilePassport()->getDownloadURL().'">�������</a>':'���'; ?></td>
            <td>
                <a href="dubl.php?action=dubl_man_edit&man_id=<?=$man->id?>&id=<?=$dubl->id?>" class="btn btn-warning btn-block">������������� ���</a>
                <?php if ($uploadedPhoto = \SDT\models\PeopleStorage\ManFile::getByUserType($man->old_man_id, \SDT\models\PeopleStorage\ManFile::TYPE_PHOTO)): ?>
                    <a href="<?php echo $uploadedPhoto->getDownloadUrl() ?>"
                       target="_blank"
                       class="btn  btn-block btn-primary"> ����������</a>
                <?else:?>
                    <button

                            class="btn  btn-block btn-primary disabled"> ��� ����������</button>
                <?php endif; ?>
                <a href="dubl.php?action=dubl_man_delete&man_id=<?=$man->id?>&id=<?=$dubl->id?>" class="btn btn-danger btn-block">�������</a>
            </td>
        </tr>
    <? endforeach;?>
    </tbody>
</table>
