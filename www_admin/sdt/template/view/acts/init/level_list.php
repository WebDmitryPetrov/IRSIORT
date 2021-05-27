<h1>Список подготовленных тестовых сессий </h1>
<h3>Всего <?=count($list)?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top"><nobr>№</nobr></th>
        <th valign="top">Дата создания</th>

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

foreach($list as $item): ?>
	<tr class="<? if($item->is_changed_checker):?>error<?endif;?>">
        <td><?=$item->number?></td>
        <td><?=$C->date($item->created)?></td>


        <td><?php echo $C->date($item->testing_date) ?>
        </td>

        <td  class="wrap"><?php echo $item->responsible ?>
        </td>

        <td  class="wrap"><?php echo $item->comment ?>
        </td>
		<td><a class="btn btn-info btn-mini btn-width-medium"
			href="index.php?action=act_fs_view&id=<?php echo $item->id; ?>">Карточка</a>
            <div class="btn-group btn-width-medium">
                <button class="btn dropdown-toggle btn-mini btn-warning" data-toggle="dropdown">
                    Редактировать
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                   <li> <a class=""
                       href="index.php?action=act_fs_edit&id=<?php echo $item->id; ?>">акт</a>
                   </li>
                    <li><a class=""
                           href="index.php?action=act_table&id=<?php echo $item->id; ?>"
                           target="_blank">сводную таблицу</a></li>
                </ul>
            </div>    <br>


            <?php if ($item->checkPassport()): ?>
			<a  onclick="return confirm('Отправить документы тестовой сессии на проверку в Головной центр.' +
			 '\nВы уверены?');"
                class="btn btn-success btn-mini payd btn-width-medium"
		 href="index.php?action=act_send&id=<?php echo $item->id; ?>" >Отправить на проверку</a>
            <?php else: ?>
                <a  onclick="alert('Необходимо загрузить все сканы паспортов, заполнить поле &quot;тесторы&quot; и ввести баллы всем протестировавшимся'); return false"
                    class="btn btn-success btn-mini payd btn-width-medium disabled"
                    href="" >Отправить на проверку</a>
        <?php endif;?>
			<a href="index.php?action=act_invalid&id=<?php echo $item->id; ?>" onclick="return confirm('Вы уверены?');" class="btn btn-danger  btn-width-medium btn-mini">Недейств</a>
		</td>
	</tr>

	<?php  endforeach;?>
</tbody>
</table>
