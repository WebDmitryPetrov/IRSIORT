<?

$reportName = !empty($caption) ? $caption : 'Без названия';
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $reportName ?></h1>
    <form action="" method="POST">

        <label>от :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                        class="input-small"
                        name="from"

                        readonly="readonly" size="16" type="text"
                        value="<?= $from ?>">
            </div>
        </label> <label>До :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                        class="input-small"
                        name="to"

                        readonly="readonly" size="16" type="text"
                        value="<?= $to ?>">
            </div>
        </label>
        <input type="submit" value="Отфильтровать">
    </form>
<?
if (!empty($search)): ?>
    <h1><?= $reportName ?> с <?= $from ?> по <?= $to ?> </h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><p>Логин</p></th>
            <th><p>Дата и время</p></th>
            <th><p>IP</p></th>
            <th><p>Заблокирован</p></th>
            <th><p>id пользователя</p></th>
            <th><p>Имя пользователя</p></th>
            <th><p>id ЛЦ</p></th>
            <th><p>id ГЦ</p></th>
            <th><p>Название ГЦ</p></th>
        </tr>

        </thead>
        <tbody>
        <?
        foreach ($array as $group): ?>
            <?
            foreach ($group->getItems() as $item):
                $lastStyle = '';
                if ((!empty($item['last']))) {
                    $lastStyle = "border-bottom: 2px solid black;";
                }
                ?>

                <tr style="<?= (!empty($item['blocked'])) ? "background:#ff7979;" : "" ?> <?= (!empty($item['auth'])) ? "background:#83ff83;" : "" ?> ">
                    <td style="<?=$lastStyle?>"><?= $item['login'] ?></td>
                    <td style="<?=$lastStyle?>"><?= date("d.m.Y H:i:s", strtotime($item['created_at'])) ?></td>
                    <td style="<?=$lastStyle?>"><?= $item['ip'] ?></td>
                    <td style="<?=$lastStyle?>"><?= (!empty($item['blocked'])) ? "Да" : "Нет" ?></td>
                    <td style="<?=$lastStyle?>"><?= $item['user_id'] ?></td>
                    <td style="<?=$lastStyle?>"><?= $item['user_name'] ?></td>
                    <td style="<?=$lastStyle?>"><?= $item['lc_id'] ? $item['lc_id'] : '' ?></td>
                    <td style="<?=$lastStyle?>"><?= $item['head_id'] ?></td>
                    <td style="<?=$lastStyle?>"><?= $item['head_name'] ?></td>
                </tr>
            <?
            endforeach ?>
        <?
        endforeach ?>
        </tbody>
    </table>

<?
endif; ?>