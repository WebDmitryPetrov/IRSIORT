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
<h3>Загрузка файлов тестируемых</h3>
<a href="?action=act_table&id=<?= $Act->id ?>" class="btn btn-info">Вернуться в сводную таблицу</a>

<form method="post" enctype="multipart/form-data">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Фамилия Имя</th>
            <th>Фотография</th>
            <!--            <th>Аудирование</th>-->
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

                        <a href="<?= $uploadedPhoto->getDownloadURL() ?>">Скачать</a>   <br>
                    <? endif ?>

                    <input type="file" name="photo[<?= $man->id ?>]">

                    <div style="color:red">Объем файла < 3MB</div>
                </td>
                <!-- <td>
                    <? /* if ($uploadedAud = \SDT\models\PeopleStorage\ManFile::getByUserType($man->id,\SDT\models\PeopleStorage\ManFile::TYPE_AUDITION)): */
                ?>

                        <a href="<? /*= $uploadedAud->getDownloadURL() */
                ?>">Скачать</a>   <br>
                    <? /* endif */
                ?>

                    <input type="file" name="aud[<? /*= $man->id */
                ?>]">

                    <div style="color:red">Объем файла < 1MB</div>
                </td>-->
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
    <input type="submit" class="btn btn-success" value="Загрузить">

    <div style="color:red">Суммарный объем файлов < 100MB</div>
    <div style="color:red">Минимальное разрешение фотографии по сторонам: 1772х2480</div>
    <div style="color:red">При большом количестве файлов в тестовой сессии загрузку осуществлять поэтапно пакетами с
        объемом < 100Мб
    </div>
</form>