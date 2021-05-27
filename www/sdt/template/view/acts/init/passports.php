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
$meta=$Act->getMeta();
?>
<h3>Загрузка сканов документов (паспорт и др.)</h3>
<a href="?action=act_table&id=<?= $Act->id ?>" class="btn btn-info">Вернуться в сводную таблицу</a>
<h3 class="text-error">Обязательно вместе с другими документами загрузить скан документа об ограниченных возможностях!</h3>
<form method="post" enctype="multipart/form-data">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Фамилия Имя</th>
            <th>Загрузить</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($people as $man): ?>
            <tr style="
            <? if ($man->getFilePassport()): ?>
                background-color: #98fb98            ;
            <? endif; ?>
                ">
                <td>
                    <?= $man->surname_rus ?> <?= $man->name_rus ?>
                    <? if ($man->getFilePassport()): ?>
                        <br>
                        <a href="<?= $man->getFilePassport()->getDownloadURL() ?>">Скачать</a>
                    <? endif ?>
                </td>
                <td><input type="file" name="file[<?= $man->id ?>]">

                    <div style="color:red">Объем файла < 1MB</div>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
    <input type="submit" class="btn btn-success" value="Загрузить">

    <div style="color:red">Суммарный объем файлов < 20MB</div>
    <div style="color:red">При  большом количестве файлов в тестовой сессии загрузку осуществлять поэтапно пакетами с объемом < 50Мб </div>
</form>