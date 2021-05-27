<? $show_free = Reexam_config::isShowInAct($object->test_level_type_id);?>
<? if ($object->isAllPrinted()):?>
<div class="btn-toolbar">

    <?php
    /** @var Act $object */

    if (
        in_array($object->state, Act::getReceivedStates())
        && (!$object->isBlocked() || $object->isCanEdit())
        && (
            $C->userHasRole(Roles::ROLE_CENTER_PRINT)
            || $C->userHasRole(Roles::ROLE_CENTER_FOR_PRINT)
            || $C->userHasRole(Roles::ROLE_CERTIFICATE_MANAGER))
    ): ?>
        <div class="btn-group">
            <a
                    class="btn btn-primary btn-block"
                    href="index.php?action=act_receive_numbers&id=<?php echo $object->id; ?>">Печать сертификатов
                (справок)</a>
        </div>
    <? endif; ?>

    <?php if ( $C->userHasRole(Roles::ROLE_CENTER_PRINT)): ?>

        <? /* <div class="btn-group">
            <a
                class="btn btn-primary  dropdown-toggle  btn-block" data-toggle="dropdown"
                href="#">Печать ведомости выдачи
                сертификатов <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($vidachaSignings as $sign): ?>
                    <li><a target="_blank"
                           href="index.php?action=act_vidacha_cert&id=<?php echo $object->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>*/ ?>

        <?
        $horg = $object->getUniversity()->getHeadCenter()->horg_id;
        if ($horg == 1 && $object->test_level_type_id == 2 && $object->state != $object::STATE_ARCHIVE): ?>
            <div class="btn-group">
                <? if (!empty($object->ved_vid_cert_num) || $object->checkBlanksNums()): ?>
                    <a
                            class="btn btn-primary  dropdown-toggle  btn-block" data-toggle="dropdown"
                            href="#">Печать ведомости выдачи
                        сертификатов <span class="caret"></span></a>
                <? else : ?>
                    <a
                            class="btn btn-primary disabled  dropdown-toggle  btn-block" data-toggle="dropdown"
                    >Печать ведомости выдачи
                        сертификатов <span class="caret"></span></a>
                <? endif; ?>
                <ul class="dropdown-menu">
                    <?php foreach ($vidachaSignings as $sign): ?>
                        <li><a target="_blank"
                               href="index.php?action=act_vidacha_cert_rudn&id=<?php echo $object->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                        </li>
                    <?php endforeach; ?>
                    <ul>
            </div>
            <div></div>
        <? else: ?>
            <div class="btn-group">
                <a
                        class="btn btn-primary  dropdown-toggle  btn-block" data-toggle="dropdown"
                        href="#">Печать ведомости выдачи
                    сертификатов <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php foreach ($vidachaSignings as $sign): ?>
                        <li><a target="_blank"
                               href="index.php?action=act_vidacha_cert&id=<?php echo $object->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                        </li>
                    <?php endforeach; ?>
                    <ul>
            </div>
            <div></div>
        <? endif; ?>

        <div class="btn-group">
            <a class="btn btn-primary
                     dropdown-toggle  btn-block" data-toggle="dropdown"
               href="#">Печать ведомости выдачи
                справок <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($vidachaSignings as $sign): ?>
                    <li><a target="_blank"
                           href="index.php?action=act_vidacha_note&id=<?php echo $object->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>

        <div class="btn-group">
            <a
                    class="btn btn-primary  dropdown-toggle  btn-block" data-toggle="dropdown"
                    href="#">Печать реестра выдачи сертификатов <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <?php foreach ($vidachaSignings as $sign): ?>
                    <li><a target="_blank"
                           href="index.php?action=act_vidacha_reestr&id=<?php echo $object->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                    </li>
                <?php endforeach; ?>
                <ul>
        </div>

    <? endif ?>
</div>
<? endif; ?>
<table class="table table-bordered  table-striped">
    <tr>
        <th>Дата акта</th>
        <td><?php echo $C->date($object->actDate(), true) ?></td>
    </tr>
    <tr>
        <th>Номер</th>
        <td><?php echo $object->number ?>
        </td>
    </tr>

    <tr>
        <th>Локальный центр</th>
        <td><?php echo $object->getUniversity()->name ?>
        </td>
    </tr>
    <? if ($object->getUniversity()->parent_id): ?>
        <tr>
            <th>Партнёр</th>
            <td><?php echo $object->getUniversity()->getParent()->name ?>
            </td>
        </tr>
    <? endif ?>
    <tr>
        <th>Договор</th>
        <td><?php echo $object->getUniversityDogovor() ?>
        </td>
    </tr>
    <tr>
        <th>Утверждающий акт</th>
        <td><?php echo $object->official ?>
        </td>
    </tr>
    <tr>
        <th>Ответственный за проведение тестрования</th>
        <td><?php echo $object->responsible ?>
        </td>
    </tr>
    <tr>
        <th>Дата тестирования</th>
        <td><?php echo $C->date($object->testing_date) ?>
        </td>
    </tr>
    <!--
    <tr>
        <th>Общая стоимость</th>
        <td><?php echo $object->total_revenue ?>
        </td>
    </tr>
    -->


    <tr>
        <th>Оплата за оказанные услуги <?= TEXT_HEADCENTER_SHORT_IP ?></th>
        <td><?php echo $object->amount_contributions ?>
        </td>
    </tr>
    <tr>
        <th>Комментарий</th>
        <td class="text-success"><?php echo $object->comment ?>
        </td>
    </tr>
    <tr>
        <th>Оплачено</th>
        <td><?php echo $object->paid ? 'Да' : 'Нет' ?>
        </td>
    </tr>

    <tr>
        <th>Загруженные файлы</th>
        <td><?php $fileact = $object->getFileAct();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">Отсканированный
                        акт</a>
                </div> <?php endif; ?> <?php $fileact = $object->getFileActTabl();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">Отсканированная
                        сводная таблица</a>
                </div> <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th>Тестирования</th>
        <td>
            <?
            $template_buttons = '';
            echo $this->import('acts/act_table_template', array('object' => $object, 'show_free' => $show_free, 'template_buttons' => $template_buttons));
            ?>

            <a class="btn btn-info btn-small"
               href="index.php?action=act_received_table_view&id=<?php echo $object->id; ?>">Просмотреть сводную
                таблицу</a>

        </td>

    </tr>


</table>


