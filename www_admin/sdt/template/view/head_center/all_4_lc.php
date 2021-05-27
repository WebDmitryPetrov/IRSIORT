<h1>Список головных центров</h1>
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
                <?php echo $item->name . ' (' . $item->href . ')';


                ?>

            </td>
            <td>

                <a class="btn btn-info btn-small" href="index.php?action=universities&h_id=<?php echo $item->id; ?>">Локальные
                    центры</a>
                         </td>
        </tr>

    <?php endforeach; ?>

</table>