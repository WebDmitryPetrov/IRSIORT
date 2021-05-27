<h1 xmlns="http://www.w3.org/1999/html">Список документов на проверку</h1>
<h3>Всего <?=count($list)?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top"><nobr>№</nobr></th>
        <th valign="top">Дата создания Акта</th>
        <th valign="top">Организация</th>
        <th valign="top">Дата тестирования</th>
       <!-- <th valign="top">Номер счета</th>
        <th valign="top">Дата счета</th>
        -->
        <th valign="top">Исполнитель</th>

        <th valign="top">Комментарий</th>
        <th valign="top">&nbsp;</th>
    </tr>
    </thead>
    <tbody>


	<?php

foreach($list as $item):
    /** @var Act $item */
    ?>

	<tr>
        <td><?=$item->number?></td>
        <td><?=$C->date($item->created)?></td>

		<td class="wrap"><?php echo $item->getUniversity() ?>
		</td>
        <td><?php echo $C->date($item->testing_date) ?>
        </td>

        <td  class="wrap"><?php echo $item->responsible ?>
        </td>

        <td  class="wrap"><?php echo $item->comment ?>
        </td>
        <td>
            <?php if (!$item->isBlocked() || $item->isCanEdit()): ?>
            <a class="btn btn-info btn-mini btn-width-medium"
			href="index.php?action=act_second_view&id=<?php echo $item->id; ?>">Карточка</a>
            <div class="btn-group btn-width-medium">
                <button class="btn dropdown-toggle btn-mini btn-warning" data-toggle="dropdown">
                    Редактировать
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                   <li> <a class=""
                       href="index.php?action=act_second_edit&id=<?php echo $item->id; ?>">акт</a>
                   </li>
                    <li><a class=""
                           href="index.php?action=act_table_second&id=<?php echo $item->id; ?>"
                           target="_blank">сводную таблицу</a></li>
                </ul>
            </div>

			<a  onclick="return confirm('Вы уверены?');"
                class="btn btn-success btn-mini payd btn-width-medium"
		 href="index.php?action=act_checked&id=<?php echo $item->id; ?>" >Проверено</a>
            <a  onclick="return confirm('Вы уверены?');"
                class="btn btn-danger btn-mini payd btn-width-medium"
                href="index.php?action=act_return_work&id=<?php echo $item->id; ?>" >На доработку</a>
                <? if ($item->isBlocked()):?>
                    <a
                        class="btn btn-danger btn-mini btn-width-medium"
                        href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                        Разблокировать</a>
                <? else: ?>
                    <a
                        class="btn btn-danger btn-mini btn-width-medium"
                        href="index.php?action=act_set_blocked&id=<?php echo $item->id; ?>">
                        Заблокировать</a>
                <? endif; ?>
            <? else: ?>
                <? if ($item->isCanUnBlock()): ?>
                    <a
                        class="btn btn-danger btn-mini btn-width-medium"
                        href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                        Разблокировать</a>
                <? else: ?>
                    <a
                        class="btn disabled btn-mini btn-width-medium">

                        Акт заблокирован</a>
                <?endif; ?>
            <? endif; ?>
		</td>
	</tr>

	<?php  endforeach;?>
</tbody>
</table>
