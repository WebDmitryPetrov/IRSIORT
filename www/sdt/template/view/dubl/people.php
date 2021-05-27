<?
/** @var DublAct $dubl */
/** @var DublActMan[] $people */
?>
<a href="/sdt/dubl.php?action=dubl&type=<?=$dubl->test_level_type_id;?>" class="btn btn-info">Назад к запросам</a>
<h1>Запрос на дубликаты от <?=$C->dateTime($dubl->created);?></h1>
<a href="dubl.php?action=dubl_man_search&id=<?=$dubl->id?>" class="btn btn-success">Добавить</a>
<? if (count($people)):?>
<a href="dubl.php?action=dubl_upload&id=<?=$dubl->id?>" class="btn btn-info">Загрузить сканы заявлений</a>
<? endif; ?>
<!--<a href="dubl.php?action=dubl_create" class="btn btn-success">Создать запрос на дубликат</a>-->
<table class="table table-bordered">
    <thead>
    <tr>
        <th>ФИО</th>
        <th>Флаг изменения</th>
        <th>Заявление</th>
        <th>Паспорт</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach($people as $man):?>
        <tr>
            <td><?=$man->getFioRus(); ?></td>
            <td><?=(intval($man->is_changed))?'Да':'Нет'; ?></td>
            <td><?=(intval($man->file_request_id))?'<a href="'.$man->getFileRequest()->getDownloadURL().'">Скачать</a>':'Нет'; ?></td>
            <td><?=(intval($man->file_passport_id))?'<a href="'.$man->getFilePassport()->getDownloadURL().'">Скачать</a>':'Нет'; ?></td>
            <td>
                <a href="dubl.php?action=dubl_man_edit&man_id=<?=$man->id?>&id=<?=$dubl->id?>" class="btn btn-warning btn-block">Редактировать ФИО</a>
                <?php if ($uploadedPhoto = \SDT\models\PeopleStorage\ManFile::getByUserType($man->old_man_id, \SDT\models\PeopleStorage\ManFile::TYPE_PHOTO)): ?>
                    <a href="<?php echo $uploadedPhoto->getDownloadUrl() ?>"
                       target="_blank"
                       class="btn  btn-block btn-primary"> Фотография</a>
                <?else:?>
                    <button

                            class="btn  btn-block btn-primary disabled"> Нет фотографии</button>
                <?php endif; ?>
                <a href="dubl.php?action=dubl_man_delete&man_id=<?=$man->id?>&id=<?=$dubl->id?>" class="btn btn-danger btn-block">Удалить</a>
            </td>
        </tr>
    <? endforeach;?>
    </tbody>
</table>
