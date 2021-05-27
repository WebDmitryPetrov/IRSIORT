<ul>
    <li><a href="./index.php?action=report_lc_list&type=1">Локальные центры по всем ГЦ комплексный экзамен</a></li>
    <li><a href="./index.php?action=report_lc_list&type=2">Локальные центры по всем ГЦ РКИ</a></li>
    <li><a href="./index.php?action=report_lc_list&type=3">Локальные центры по РУДН комплексный экзамен</a></li>
    <li><a href="./index.php?action=report_lc_list&type=4">Локальные центры по РУДН РКИ</a></li>
</ul>
<?php

if (empty($result)) return;

?>
<h1><?=$name?></h1>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Локальный центр</th>
        <th>Головной центр</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($result as $r): ?>
        <tr>
            <td><?= $r['name'] ?></td>
            <td><?= $r['short_name'] ?></td>

        </tr>


    <? endforeach; ?>

    </tbody>
</table>
