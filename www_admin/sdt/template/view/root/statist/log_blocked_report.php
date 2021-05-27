<?

$reportName = !empty($caption) ? $caption : 'Без названия';
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $reportName ?></h1>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th><p>IP</p></th>
            <th><p>Дата и время блокировки</p></th>
            <th><p>Срок блокировки</p></th>

            <th><p>Заблокирован</p></th>

            <th><p>id пользователя</p></th>
            <th><p>Login</p></th>
            <th><p>Имя пользователя</p></th>
            <th><p>id ЛЦ</p></th>
            <th><p>id ГЦ</p></th>
            <th><p>Название ГЦ</p></th>

            <th><p>Дата и время разблокировки</p></th>

            <th></th>
        </tr>

        </thead>
        <tbody>
        <?
        foreach ($array as $item): ?>
            <tr style="<?= (!$item['blocked']) ? "background:#00c4005e;;" : "" ?>">
                <td><?= $item['ip'] ?></td>
                <td><?= date("d.m.Y H:i:s", strtotime($item['created_at'])) ?></td>
                <td><?= date("d.m.Y H:i:s", strtotime($item['block_until'])) ?></td>

                <td><?= ($item['blocked']) ? "Да" : "Нет" ?></td>
                <td><?=$item['user_id']?></td>
                <td><?=$item['login']?></td>
                <td><?=$item['user_name']?></td>
                <td><?=$item['lc_id']?></td>
                <td><?=$item['head_id']?></td>
                <td><?=$item['head_name']?></td>


                <td><?= $item['unblocked_at'] ? date("d.m.Y H:i:s", strtotime($item['unblocked_at'])) : '' ?></td>

                <td>
                    <?php
                    if ($item['blocked']): ?>
                        <a class="btn btn-default" href="/sdt/index.php?action=log_blocked_report_unblock&row_id=<?=$item['id']?>" >Разблокировать</a>
                    <?
                    endif ?>
                </td>
            </tr>
        <?
        endforeach ?>
        </tbody>
    </table>

