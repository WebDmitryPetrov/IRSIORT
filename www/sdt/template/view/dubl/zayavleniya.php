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
<h3>Загрузка сканов заявлений</h3>
<a href="?action=dubl_show&id=<?= $Act->id ?>" class="btn btn-info">Вернуться</a>
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
            <? if ($man->getFileRequest()): ?>
                background-color: #98fb98            ;
            <? endif; ?>
                ">
                <td>
                    <?= $man->getSurnameRus() ?> <?= $man->getNameRus() ?>
                    <? if ($man->getFileRequest()): ?>
                        <br>
                        <a href="<?= $man->getFileRequest()->getDownloadURL() ?>">Скачать</a>
                    <? endif ?>
                </td>
                <td><input type="file" name="file[<?= $man->id ?>]">


                    <div style="color:red">Объем файла < 1MB</div>
                </td>
            </tr>

        <? endforeach ?>
        <!--<tr style="
        <?/* if ($Act->file_request_id): */?>
            background-color: #98fb98            ;
        <?/* endif; */?>
            ">
            <td>
                Файл заявления
                <?/* if ($Act->file_request_id): */?>
                    <br>
                    <a href="<?/*=File::getByID($Act->file_request_id)->getDownloadURL() */?>">Скачать</a>
                <?/* endif */?>
            </td>
            <td><input type="file" name="request">
                <div style="color:red">Объем файла < 5MB</div>
            </td>
        </tr>-->
        </tbody>
    </table>
    <input type="submit" class="btn btn-success" value="Загрузить">

    <div style="color:red">Суммарный объем файлов < 20MB</div>
    <div style="color:red">При  большом количестве файлов в тестовой сессии загрузку осуществлять поэтапно пакетами с объемом < 50Мб </div>
</form>