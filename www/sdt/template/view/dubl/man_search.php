<a class="btn btn-info btn-mi ni bt n-block"
   href="dubl.php?action=dubl_show&id=<?= $dubl->id ?>">Вернуться</a>

<form class="form-search" method="post"
      action="<?php echo $_SERVER['REQUEST_URI'] ?>"
>
    <input type="hidden" name="action" value="search_pupil">
    <input type="hidden" name="do" value="search">
    <div>
        <input name="surname" type="text" class="input-xxlarge"
               placeholder="Введите фамилию тестируемого"
               value="<?php echo $surname; ?>">

    </div>
    <div>
        <input name="name" type="text" class="input-xxlarge "
               placeholder="Введите имя тестируемого"
               value="<?php echo $name; ?>">

    </div>
    <div>
        <input name="blank" type="text" class="input-xxlarge "
               placeholder="Номер бланка сертификата"
               value="<?php echo $blank; ?>">
    </div>
    <button type="submit" class="btn">Найти</button>
</form>
<?php


if (!empty($result)):?>

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
            <td rowspan="2" class="center"><strong>Действия</strong>
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
        <?php foreach ($result as $Man):
            /** @var ActMan $Man */
            ?>
            <tr
                class="">

                <td><span><?php echo $Man->getSurname_rus(); ?> </span> <br> <span><?php echo $Man->getSurname_lat(); ?>
			</span>
                </td>
                <td><span><?php echo $Man->getName_rus(); ?> </span> <br> <span><?php echo $Man->getName_lat(); ?>
			</span>
                </td>
                <td><?php echo $Man->getCountry()->name; ?></td>
                <td><?php echo $C->date($Man->testing_date) ?>
                </td>
                <td><?php echo $Man->getTest()->getLevel()->caption ?>
                </td>
                <? /*
			<td><span class="percent"><?php echo $Man->reading ?> </span> <span
				class="percent">(<?php echo $Man->reading_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->writing; ?> </span> <span
				class="percent">(<?php echo $Man->writing_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->grammar; ?> </span> <span
				class="percent">(<?php echo $Man->grammar_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->listening; ?> </span> <span
				class="percent">(<?php echo $Man->listening_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->speaking; ?> </span> <span
				class="percent">(<?php echo $Man->speaking_percent; ?>%)
			</span>
			</td>
			<td><span class="percent"><?php echo $Man->total; ?> </span> <span
				class="percent">(<?php echo $Man->total_percent; ?>%)
			</span>
			</td>
 */ ?>
                <td>
                <span class="<?php echo $Man->document; ?> ">
                        <?php echo $Man->isCertificate() ? 'Сертификат' : 'Справка' ?>


                        </span>
                    <? if ($Man->duplicate): ?>
                        - <strong>дубликат</strong>
                    <? endif ?>
                    <br><strong><?php
                        $res = array();
                        if ($Man->document_nomer) {
                            $res[] = $Man->document_nomer;
                        }
                        if ($Man->blank_number) {
                            $res[] = $Man->getBlank_number();
                        }

                        echo implode(' / ', $res); ?></strong>
                    <? if ($Man->blank_date != '0000-00-00' && !is_null($Man->blank_date)): ?>
                        <br>
                        <?= $C->date($Man->blank_date); ?>
                        <br>
                        <?php
                        if(!$Man->duplicate) {
                            echo  HeadCenter::getNameByActID($Man->act_id);
                        }
                        else{
                            echo  HeadCenter::getNameByCertificateID($Man->duplicate->certificate_id);
                        }
//                        echo $Man->getUniversity()->getHeadCenter()->short_name;
                        ?>
                    <? endif ?>
                </td>
                <td>
                    <?
                    $act_state = Act::getActState($Man->act_id);
//                    if ($act_state == Act::STATE_ARCHIVE):?>

                        <a class="btn btn-info btn- mini btn-block"
                           href="dubl.php?action=dubl_man_add&man_id=<?php echo $Man->id; ?>&id=<?= $dubl->id ?>">Выбрать</a>
                    <?php if ($uploadedPhoto = \SDT\models\PeopleStorage\ManFile::getByUserType($Man->id, \SDT\models\PeopleStorage\ManFile::TYPE_PHOTO)): ?>
                        <a href="<?php echo $uploadedPhoto->getDownloadUrl() ?>"
                           target="_blank"
                           class="btn  btn-block btn-primary"> Фотография</a>
                    <?else:?>
                        <button

                           class="btn  btn-block btn-primary disabled"> Нет фотографии</button>
                    <?php endif; ?>
                 <!--   <?/*endif;
                    if ($act_state != Act::STATE_ARCHIVE):*/?>
                        <p class="warning">Оригинал тестовой сессии в головном центре находится в работе (не в архиве). Для оформления заявки на дубликат головной центр должен отправить данную тестовую сессию в архив!</p>
                    --><?/* endif; */?>

                </td>
                <!--               <td>
                    <a class="btn btn-info btn-mini btn-block" target="_blank"
                       href="index.php?action=act_received_view&id=<?php /*echo $Man->getAct()->id; */
                ?>">Карточка акта</a>
                    <? /* if (
                        $Man->isCertificate()
                        && (
                            ($C->userHasRole(Roles::ROLE_SUPERVISOR) && $Man->getAct()->state == Act::STATE_ARCHIVE)
                            || ($C->userHasRole(Roles::ROLE_CENTER_EDITOR) && $Man->getBlank_number())
                        )
                    ): */
                ?>
                        <a class="btn btn-warning btn-mini btn-block" target="_blank"
                           href="index.php?action=man_duplicate&id=<?php /*echo $Man->id; */
                ?>">Дубликат</a>

                    <? /* endif*/
                ?>

                </td>-->

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
endif;
