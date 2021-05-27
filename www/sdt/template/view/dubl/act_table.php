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

<a class="btn btn-info"
   href="/sdt/dubl.php?action=dubl_act_list&uid=<?= $Act->center_id ?>&type=<?= $dubl->test_level_type_id ?>">Назад</a>


<table class="table table-bordered">
    <tr>
        <th>Локальный центр</th>
        <td><?= $Act->getUniversity()->name ?>
        </td>
    </tr>
    <tr>
        <th>Договор</th>
        <td><?= $dogovor->number . ' ' . $C->date($dogovor->date) . ' ' . $dogovor->caption ?></td>
    </tr>

    <tr>
        <th>Должностное лицо, утверждающее акт</th>
        <td><?php echo $Act->official; ?></td>
    </tr>
    <tr>
        <th>Комментарий</th>
        <td><?= $Act->comment ?></td>
    </tr>
</table>
<? if ($dubl->getFileRequest()): ?>
    <a href="<?= $dubl->getFileRequest()->getDownloadURL() ?>">Скачать заявление центра тестирования</a>
<? endif; ?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>ФИО</th>
        <th>ФИО измененное</th>
        <? if ($Act->isShowInArchive()): ?>
            <th>Заявление</th>
            <th>Паспорт</th>
        <? endif ?>
        <!--        <th></th>-->
    </tr>
    </thead>
    <tbody>
    <? foreach ($people as $man): ?>
        <tr>
            <td><?= $man->getFioRusOld(); ?><br><?= $man->getFioLatOld() ?>

                <?php if ($uploadedPhoto = \SDT\models\PeopleStorage\ManFile::getByUserType($man->old_man_id, \SDT\models\PeopleStorage\ManFile::TYPE_PHOTO)): ?>
                    <br><a href="<?php echo $uploadedPhoto->getDownloadUrl() ?>"
                       target="_blank"
                       class=" "> Фотография</a>
                <?php endif; ?>
            </td>
            <td><?= (intval($man->is_changed)) ? $man->getFioRusNew() . '<br>' . $man->getFioLatNew() : ''; ?></td>
            <? if ($Act->isShowInArchive()): ?>
                <td><?= (intval($man->file_request_id)) ? '<a href="' . $man->getFileRequest()->getDownloadURL() . '">Скачать</a>' : 'Нет'; ?></td>
                <td><?= (intval($man->file_passport_id)) ? '<a href="' . $man->getFilePassport()->getDownloadURL() . '">Скачать</a>' : 'Нет'; ?></td>
            <? endif ?>
            <!--            <td>
                <a href="dubl.php?action=dubl_man_edit&man_id=<? /*=$man->id*/ ?>&id=<? /*=$dubl->id*/ ?>" class="btn btn-warning btn-block">Редактировать ФИО</a>

                <a href="dubl.php?action=dubl_man_delete&man_id=<? /*=$man->id*/ ?>&id=<? /*=$dubl->id*/ ?>" class="btn btn-danger btn-block">Удалить</a>
            </td>-->
        </tr>
    <? endforeach; ?>
    </tbody>
</table>




