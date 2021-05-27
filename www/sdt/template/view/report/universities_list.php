<h1 >Реест актов тестовых сессий</h1>
<h2>Список центров (счета)</h2>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>

        <th valign="top">Месяц</th>
        <th valign="top">Количество</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; foreach ($list as $univer): ?>
        <tr>

            <td>
                  <a target="_blank" href="index.php?action=report_list&m=<?=ltrim($univer['month'],0);?>&y=<?=$univer['year'];?>" >

                      <?=$C->rusMonth($univer['month']);?>,
                      <?=($univer['year']);?>
                  </a>
            </td>
            <td>
                <strong><?=$univer['cc']?></strong>
            </td>
        </tr>


        <?php endforeach; ?>

    </tbody>
</table>