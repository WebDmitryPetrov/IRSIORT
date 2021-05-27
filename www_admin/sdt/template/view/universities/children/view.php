<?php
/** @var University $object */
?>

    <a class=""
       href="index.php?action=university_children&id=<?php echo $object->parent_id; ?>">Вернуться к списку</a>
    <h1>Подчинённый центр: <?= $object->name ?></h1>
    <a class="btn btn-danger"
       href="index.php?action=university_user_right&id=<?php echo $object->id; ?>">Права</a>

    <a class="btn btn-danger"
       href="index.php?action=university_child_edit&id=<?php echo $object->id; ?>">Редактировать</a>

<?php if (!empty($_GET['pwd'])): ?>
    <p style="font-weight: bold; color: red; font-size: 18px">Запишите логин и пароль для учетной записи
        данного локального центра</p>


<?php endif; ?>
    <br>
    <br>

    <table class="table table-bordered  table-striped">

        <tr>
            <th>Логин</th>
            <td><?= $object->user_login; ?></td>
        </tr>
        <?php if (!empty($_GET['pwd'])): ?>
            <tr>
                <th>Пароль</th>
                <td style="font-weight: bold; color: red; font-size: 18px"><?= $_GET['pwd']; ?></td>
            </tr>
        <?php endif; ?>


        <?php foreach ($object->getEditFields() as $field):
            if (in_array($field, ['is_old_act', 'is_price_change', 'is_head', 'print_invoice_quoute']))
                continue;

            if ($field != 'country_id' && $field != 'region_id' && $field != 'is_old_act' && !($field == 'is_price_change' || $field == 'is_head' || $field == 'print_invoice_quoute')):
                ?>
                <tr class="<?= $object->isParentedField($field) ? 'info' : '' ?>">
                    <th>
                        <?
                        if ($field == 'legal_address'):?>
                     Адрес
                        <?
                        else:?>
                            <?php echo $object->getTranslate($field); ?>
                        <?endif ?>
                    </th>
                    <td><?php echo $object->getParentedFieldValue($field) ?>
                    </td>
                </tr>
                <?php
            endif;
            if ($field == 'country_id'):
                ?>
                <tr>
                    <th><?php echo $object->getTranslate($field); ?>
                    </th>
                    <td><?= $object->country_id ? $object->getCountry()->name : '' ?>
                    </td>
                </tr>
                <?php
            endif;

            if ($field == 'region_id'):
                ?>
                <tr>
                    <th><?php echo $object->getTranslate($field); ?>
                    </th>
                    <td><?= $object->region_id ? $object->getRegion()->caption : '' ?>
                    </td>
                </tr>
                <?php
            endif;


        endforeach; ?>
        <tr class="info">
            <th>Договоры</th>
            <td>
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                    <tr>
                        <td>Номер</td>
                        <td>Дата</td>
                        <td>Название</td>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($object->getDogovors() as $dogovor): ?>
                        <tr>
                            <td><?= $dogovor->number; ?></td>
                            <td><?= $C->date($dogovor->date); ?></td>
                            <td><?= $dogovor->caption; ?></td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td>

        </tr>

    </table>


<? /* <tr>
        <th>Название</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Сокращенное название </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Ректор</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Правовая форма </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Юридический адрес </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Телефон</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Факс</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Email</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Дополнительные контакты </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Ответственный за проведение тестирования </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Банк</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Город</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Расчетный счет </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Лицевой счет </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Корреспондентский счет </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>БИК</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>ИНН</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>КПП</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Код по ОКАТО </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Код по ОКПО </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Комментарии</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Страна</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Регион</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Является ли головным </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Разрешить создавать акты с датой тестирования более 15 дней </th>
        <td><?= $object->user_login; ?></td>
    </tr>
*/ ?>