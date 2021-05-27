<h1>Список локальных центров</h1>
<a class="btn btn-primary btn-small" href="index.php?action=university_add&h_id=<? echo filter_input(INPUT_GET, 'h_id', FILTER_VALIDATE_INT);?>">
    Добавить локальный центр партнёра</a>
<table class="table table-bordered  table-striped">
    <?php

    foreach ($list as $item): ?>
        <tr>
            <td>
                <?php echo $item['id']; ?>
            </td>
            <td>
                <?php echo $item['name'];

                if ($item['api_enabled']) echo '<br><span style="color:red">XML</span>';
                ?>
                <? if (!empty($children = $item['children'])): ?>
                    <h5>Подчинённые:</h5>
                    <ol style="margin-left:40px;">
                        <? foreach ($children as $child): ?>
                            <li  style="list-style: none"><?= $child['id'] ?>. <? echo $child['name'];

                                if ($child['api_enabled']) echo '<br><span style="color:red">XML</span>';
                                ?></li>
                        <? endforeach; ?>
                    </ol>


                <? endif; ?>
            </td>
            <td>
                <a class="btn btn-info btn-small btn-block"
                   href="index.php?action=university_view&id=<?php echo $item['id']; ?>">Карточка</a>
                <a class="btn btn-info btn-small btn-block"
                   href="index.php?action=university_view_dogovor&id=<?php echo $item['id']; ?>">Договоры</a>



            </td>
        </tr>

    <?php endforeach; ?>

</table>