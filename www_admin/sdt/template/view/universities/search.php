<h1>Поиск локальных центров</h1>
<form class="form-inline" method="get" action="index.php">

        <label class="sr-only" for="un-search">Название
        <input type="search" class="form-control" id="un-search" name="name" placeholder=""
               value="<?= filter_input(INPUT_GET, 'name') ?>">
        </label>

    <label class="sr-only" for="un-search">ИНН
        <input type="search" class="form-control" id="un-search" name="inn" placeholder=""
               value="<?= filter_input(INPUT_GET, 'inn') ?>">
        </label>
    <input type="hidden" name="action" value="university_search">
    <button type="submit" class="btn btn-default">Найти</button>
</form>
<table class="table table-bordered  table-striped">
    <?php

    foreach ($list as $item): ?>
        <tr>
            <td>
                <?php echo $item['id']; ?>
            </td>
            <td>
                <?php echo $item['name'];
                echo sprintf('<br><strong>ГЦ</strong> %s',$item['hc_name']);

                if ($item['api_enabled']) echo '<br><span style="color:red">XML</span>';
                ?>
                <? if (!empty($children = $item['children'])): ?>
                    <h5>Подчинённые:</h5>
                    <ol style="margin-left:40px;">
                        <? foreach ($children as $child): ?>
                            <li style="list-style: none"><?= $child['id'] ?>. <? echo $child['name'];

                                if ($child['api_enabled']) echo '<br><span style="color:red">XML</span>';
                                ?></li>
                        <? endforeach; ?>
                    </ol>


                <? endif; ?>
            </td>
            <td>
                <a class="btn btn-info btn-small btn-block"
                   href="index.php?action=university_view&id=<?php echo $item['id']; ?>">Карточка</a>
            </td>
        </tr>

    <?php endforeach; ?>

</table>