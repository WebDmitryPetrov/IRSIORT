<?php
/** @var University $object */
?>
<a class="btn btn-danger" href="index.php?action=university_children&id=<?php echo $object->id; ?>">Подчинённые
    локальные центры</a>


<hr>
<a target="_blank" class="btn" href="index.php?action=university_print_simple&id=<?php echo $object->id; ?>">Простая
    форма</a>
<a target="_blank" class="btn" href="index.php?action=university_print_full&id=<?php echo $object->id; ?>">Полная
    форма</a>
<a target="_blank" class="btn" href="index.php?action=university_print_full_dogovor&id=<?php echo $object->id; ?>">Полная
    форма с договорами</a>
<div class="gap"></div>
<br>
<a target="_blank" class="btn"
   href="index.php?action=university_view_dogovor&id=<?php echo $object->id; ?>">Договоры</a>
<a class="btn btn-warning"
   href="index.php?action=university_user_right&id=<?php echo $object->id; ?>">Права</a>

<a class="btn btn-warning"
   href="index.php?action=university_edit&id=<?php echo $object->id; ?>">Редактировать</a>

<? if ($haveChildren): ?>
    <a href="#"
       onclick="alert('Удалите сначала все подчиненные центры');" class="btn btn-danger disabled ">
        Удалить
    </a>

<? else: ?>
    <a href="index.php?action=university_delete&id=<?php echo $object->id; ?>"
       onclick="return confirm('Вы уверены, что хотите удалить центр?\n' +
        'При удалении локального центра из Системы он будет удален из всех списков центров Системы, кроме того, будут удалены все связанные с ним тестовые сессии за исключением сессий, находящихся в архиве!');"
       class="btn btn-danger ">
        Удалить
    </a>
<? endif ?>
<?php if (!empty($_GET['pwd'])): ?>
    <p style="font-weight: bold; color: red; font-size: 18px">Запишите логин и пароль для учетной записи
        данного локального центра</p>


<?php endif; ?>
<br>
<br>

<table class="table table-bordered  table-striped">

    <tr>
        <th>Подчинённые локальные центры</th>
        <td><?= $haveChildren ? 'Имеет' : 'Не имеет' ?></td>
    </tr>
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
    if ($field != 'country_id' && $field != 'region_id' && $field != 'is_old_act' && !($field == 'is_price_change' || $field == 'is_head' || $field == 'print_invoice_quoute')):
        ?>
        <tr>
            <th><?php echo $object->getTranslate($field); ?>
            </th>
            <td><?php echo $object->$field ?>
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
        <?php
        endif;

        if ($field == 'region_id'):
        ?>
    <tr>
        <th><?php echo $object->getTranslate($field); ?>
        </th>
        <td><?= $object->region_id ? $object->getRegion()->caption : '' ?>
        </td>
        <?php
        endif;

        if ($field == 'is_price_change' || $field == 'is_head' || $field == 'print_invoice_quoute' || $field == 'is_old_act'):
        ?>
    <tr>
        <th><?php echo $object->getTranslate($field); ?>
        </th>
        <td><?= $object->$field ? 'Да' : 'Нет' ?>
        </td>
        <?php
        endif;


        endforeach; ?>
    <tr>
        <th>Договоры</th>
        <td>
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                <tr>
                    <th>Номер</th>
                    <th>Дата</th>
                    <th>Название</th>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($object->getDogovors() as $dogovor): ?>
                    <tr>
                        <th><?= $dogovor->number; ?></th>
                        <td><?= $C->date($dogovor->date); ?></td>
                        <td><?= $dogovor->caption; ?></td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </td>

    </tr>

    <tr>
        <th>Утверждающие акт</th>
        <td>
            <? $signs = CenterSignings::getCenterAndType($object->id, CenterSigning::TYPE_APPROVE); ?>
            <a
                    href="index.php?action=center_signing_add&id=<?php echo $object->id; ?>&type=<?= CenterSigning::TYPE_APPROVE ?>">
                Добавить</a>

            <? if ($signs && count($signs)): ?>
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>Должность</th>
                        <th>ФИО</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($signs as $item): ?>
                        <tr>
                            <td><?= $item->position; ?></td>

                            <td><?= $item->caption; ?></td>
                            <td>
                                <a href="index.php?action=center_signing_edit&id=<?php echo $item->id; ?>&type=<?= CenterSigning::TYPE_APPROVE ?>" ><i class="icon-pencil"></i></a>
                                <a href="index.php?action=center_signing_delete&id=<?php echo $item->id; ?>&type=<?= CenterSigning::TYPE_APPROVE ?>" onclick="return confirm('Вы уверены, что хотите удалить?');"><i class="icon-remove"></i></a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <? else: ?>


            <? endif; ?>
        </td>

    </tr>

 <tr>
        <th>Ответственные за тестирование</th>
        <td>
            <? $signs = CenterSignings::getCenterAndType($object->id, CenterSigning::TYPE_RESPONSIVE); ?>
            <a
                    href="index.php?action=center_signing_add&id=<?php echo $object->id; ?>&type=<?= CenterSigning::TYPE_RESPONSIVE ?>">
                Добавить</a>

            <? if ($signs && count($signs)): ?>
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>Должность</th>
                        <th>ФИО</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($signs as $item): ?>
                        <tr>
                            <td><?= $item->position; ?></td>

                            <td><?= $item->caption; ?></td>
                            <td>
                                <a href="index.php?action=center_signing_edit&id=<?php echo $item->id; ?>&type=<?= CenterSigning::TYPE_RESPONSIVE ?>" ><i class="icon-pencil"></i></a>
                                <a href="index.php?action=center_signing_delete&id=<?php echo $item->id; ?>&type=<?= CenterSigning::TYPE_RESPONSIVE ?>" onclick="return confirm('Вы уверены, что хотите удалить?');"><i class="icon-remove"></i></a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <? else: ?>


            <? endif; ?>
        </td>

    </tr>

</table>
