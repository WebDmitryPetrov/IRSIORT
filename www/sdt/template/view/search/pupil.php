<form class="form-search" method="post"
      action="<?php
      echo $_SERVER['REQUEST_URI'] ?>">
    <input type="hidden" name="action" value="search_pupil">
    <input type="hidden" name="do" value="search">
    <div class="input-append">
        <input name="query" type="text" class="input-xxlarge search-query"
               placeholder="Введите фамилию тестируемого"
               value="<?php
               echo $query; ?>">
        <button type="submit" class="btn">Найти</button>
    </div>
    <div><input name="name" type="text" class="input-xxlarge search-query"
                placeholder="Имя и отчество"
                value="<?php
                echo $name; ?>">
    </div>
    <div><input name="certificate" type="text" class="input-xxlarge search-query"
                placeholder="Номер бланка сертификата \ номер справки"
                value="<?php
                echo $certificate; ?>">
    </div>

    <!--<div class="input-append">
        <input name="query" type="text" class="input-xxlarge search-query"
               placeholder="Введите фамилию тестируемого"
               value="<?php
    /*echo $query; */ ?>">
        <button type="submit" class="btn">Найти</button>
    </div>
    <div>
        <input name="certificate" type="text" class="input-xxlarge search-query"
                placeholder="Номер бланка сертификата \ номер справки"
                value="<?php
    /*echo $certificate; */ ?>">
    </div>-->

</form>
<?php
if ($Result || $archive):?>

    <?
    //require_once('paginator_tab.php');
    // echo $paginator;
    ?>

    <table
            class="table table-bordered  table-striped table-condensed">
        <thead>
        <tr>
            <td rowspan="2"><strong>Фамилия</strong><br/> русскими <br/>
                латинскими
            </td>
            <td rowspan="2"><strong>Имя</strong><br/> русскими<br/> латинскими</td>
            <td rowspan="2"><strong>Страна</strong></td>

            <td rowspan="2"><strong>Дата тестирования</strong></td>
            <td rowspan="2"><strong>Уровень тестирования</strong></td>
            <!--			<td colspan="6" class="center"><strong>Результаты</strong> (баллы /-->
            <!--				%)</td>-->
            <td rowspan="2" class="center"><strong>Итог</strong>
            </td>
            <td rowspan="2" class="center">
            </td>
        </tr>
        <!--		<tr>-->
        <!--			<td class="center">Чт</td>-->
        <!--			<td class="center">Пис</td>-->
        <!--			<td class="center">Лекс</td>-->
        <!--			<td class="center">Ауд</td>-->
        <!--			<td class="center">Уст</td>-->
        <!--			<td class="center">Общ</td>-->
        <!--		</tr>-->

        </thead>
        <tbody>
        <?php
        foreach ($Result as $Man):
            /** @var ActMan $Man */
            ?>
            <tr
                    class="">

                <td><span><?php
                        echo $Man->getSurname_rus(); ?> </span> <br> <span><?php
                        echo $Man->getSurname_lat(); ?>
			</span>
                </td>
                <td><span><?php
                        echo $Man->getName_rus(); ?> </span> <br> <span><?php
                        echo $Man->getName_lat(); ?>
			</span>
                </td>
                <td><?php
                    echo $Man->getCountry()->name; ?></td>
                <td><?php
                    echo $C->date($Man->testing_date) ?>
                </td>
                <td><?php
                    echo $Man->getTest()->getLevel()->caption ?>
                </td>

                <td>
                <span class="<?php
                echo $Man->document; ?> ">
                        <?php
                        echo $Man->isCertificate() ? 'Сертификат' : 'Справка' ?>


                        </span>
                    <?
                    if ($Man->duplicate): ?>
                        - <strong>дубликат</strong>
                    <?
                    endif ?>
                    <br><strong><?php
                        $res = array();
                        if ($Man->document_nomer) {
                            $res[] = $Man->document_nomer;
                        }
                        if ($Man->blank_number) {
                            $res[] = $Man->getBlank_number();
                        } elseif ($Man->is_anull() && $Man->getAnull()->blank_number) {
                            $res[] = $Man->getAnull()->blank_number;
                        }
                        echo implode(' / ', $res); ?></strong>
                    <br>
                    <?php
                    if (!$Man->duplicate) {
                        echo HeadCenter::getNameByActID($Man->act_id);
                    } else {
                        echo HeadCenter::getNameByCertificateID($Man->duplicate->certificate_id);
                    }
                    if ($Man->blank_date != '0000-00-00' && !is_null($Man->blank_date)): ?>
                        <br>
                        <?= $C->date($Man->blank_date); ?>
                    <?
                    endif ?>

                    <?
                    if ($Man->is_anull()): ?>
                        <p class="text-error"><strong>Аннулирован</strong><br><?= $C->date(
                                $Man->getAnull()->date_annul
                            ) ?><br><strong>Причина:</strong><br><?= $Man->getAnull()->reason ?></p>
                    <?
                    endif ?>
                </td>
                <td>
                    <?
                    if (Act::isHeadCenter($Man->act_id, CURRENT_HEAD_CENTER) && !$Man->is_anull()): ?>
                        <a class="btn btn-info btn-mini btn-block" target="_blank"
                           href="index.php?action=act_received_view&id=<?php
                           echo $Man->act_id; ?>">Карточка акта</a>
                        <?php
                        if ($uploadedPhoto = \SDT\models\PeopleStorage\ManFile::getByUserType(
                            $Man->id,
                            \SDT\models\PeopleStorage\ManFile::TYPE_PHOTO
                        )): ?>
                            <a href="<?php
                            echo $uploadedPhoto->getDownloadUrl() ?>"
                               target="_blank"
                               class="btn  btn-mini btn-block btn-primary"> Фотография</a>
                        <?php
                        endif; ?>
                    <?
                    endif ?>

                    <?
                    $dublList = DublActList::getByMan($Man->id);
                    if ($Man->isCertificate() && $dublList && count($dublList) && (($C->userHasRole(
                                Roles::ROLE_SUPERVISOR
                            ) && Act::getActState($Man->act_id) == Act::STATE_ARCHIVE))): ?>
                        <a class="btn btn-warning btn-mini btn-block" target="_blank"
                           href="dubl.php?action=dubl_man_acts&id=<?php
                           echo $Man->id; ?>">Список запросов на
                            дубликаты</a>

                    <?
                    endif ?>

                </td>

            </tr>
        <?php
        endforeach; ?>

        <?php
        foreach ($archive as $am):
            /** @var \SDT\models\Archive\Man $am */
            ?>
            <tr
                    class="">

                <td><span><?php
                        echo $am->surname_rus ?> </span> <br> <span><?php
                        echo $am->surname_lat ?>
			</span>
                </td>
                <td><span><?php
                        echo $am->name_rus; ?> </span> <br> <span><?php
                        echo $am->name_lat; ?>
			</span>
                </td>
                <td><?php
                    echo $am->getCountry()->name; ?></td>
                <td><?php
                    echo $C->date($am->testing_date) ?>
                </td>
                <td><?php
                    echo $am->getTestLevel()->caption ?>
                </td>

                <td>
                <span class="<?php
                echo $am->document; ?> ">
                        <?php
                        echo $am->isCertificate() ? 'Сертификат' : 'Справка' ?>


                        </span>
                    <?
                    if ($am->original_blank_number): ?>
                        - <strong>дубликат</strong>
                    <?
                    endif ?>
                    <br><strong><?php
                        $res = array();
                        if ($am->document_nomer) {
                            $res[] = $am->document_nomer;
                        }
                        if ($am->blank_number) {
                            $res[] = $am->blank_number;
                        } elseif ($am->annul_blank) {
                            $res[] = $am->annul_blank;
                        }
                        echo implode(' / ', $res); ?></strong>
                    <br>

                    <?= $am->head_center ?>
                    <?php
                    if ($am->blank_date != '0000-00-00' && !is_null($am->blank_date)): ?>
                        <br>
                        <?= $C->date($am->blank_date); ?>
                    <?
                    endif ?>

                    <?
                    if ($am->annul): ?>
                        <p class="text-error"><strong>Аннулирован</strong>
                            <br>
                            <?= $C->date($am->annul_date) ?><br>
                            <strong>Причина:</strong><br><?= $am->annul_reason ?></p>
                    <?
                    endif ?>
                </td>
                <td>
                    <?
                    if (false == $am->annul): ?>
                        <a class="btn btn-info btn-mini btn-block" target="_blank"
                           href="index.php?action=archive_man&id=<?php
                           echo $am->id; ?>">Карточка тестируемого</a>

                        <?php
                        if ($uploadedPhoto = \SDT\models\Archive\PhotoFile::getByUserType(
                            $am->id,
                            \SDT\models\Archive\PhotoFile::TYPE_PHOTO
                        )): ?>
                            <a href="<?php
                            echo $uploadedPhoto->getDownloadUrl() ?>"
                               target="_blank"
                               class="btn  btn-mini btn-block btn-primary"> Фотография</a>
                        <?php
                        endif; ?>
                    <?php
                    endif; ?>


                </td>

            </tr>
        <?php
        endforeach; ?>
        </tbody>
    </table>
<?php
endif;
