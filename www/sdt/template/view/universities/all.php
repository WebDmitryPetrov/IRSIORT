<h1>?????? ????????? ???????</h1>
<a class="btn btn-primary btn-small" href="index.php?action=university_add">
    ???????? ????????? ????? ????????</a>
<table class="table table-bordered  table-striped">
    <?php

    foreach ($list as $item): ?>
        <tr>
            <td>
                <?php echo $item['id']; ?>
            </td>
            <td>
                <?php echo $item['name']; ?>
                <? if (!empty($children = $item['children'])): ?>
                    <h5>???????????:</h5>
                    <ol >
                        <? foreach ($children as $child): ?>
                            <li style="list-style: none"><?= $child['id'] ?>. <?= $child['name'] ?></li>
                        <? endforeach; ?>
                    </ol>


                <? endif; ?>
            </td>
            <td>
                <a class="btn btn-info btn-small btn-block"
                   href="index.php?action=university_view&id=<?php echo $item['id']; ?>">????????</a>
                <a class="btn btn-info btn-small btn-block"
                   href="index.php?action=university_view_dogovor&id=<?php echo $item['id']; ?>">????????</a>

            </td>
        </tr>

    <?php endforeach; ?>

</table>