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
<h3>Загрузка файлов сканов, предьявленных сертификатов для "упрощенного" уровня тестирования</h3>
<a href="?action=act_table&id=<?= $Act->id ?>" class="btn btn-info">Вернуться в сводную таблицу</a>
<form method="post" enctype="multipart/form-data">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Фамилия Имя</th>
            <th>Загрузить</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($people as $man): 
		 $man_additional_data=$man->getAdditionalExam();
		
		?>
            <tr style="
            <? if ($man_additional_data->getFileOldBlankScan()): ?>
                background-color: #98fb98            ;
            <? endif; ?>
                ">
                <td>
                    <?= $man->surname_rus ?> <?= $man->name_rus ?>
                    <? if ($man_additional_data->getFileOldBlankScan()): ?>
                        <br>
                        <a href="<?= $man_additional_data->getFileOldBlankScan()->getDownloadURL() ?>">Скачать</a>
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