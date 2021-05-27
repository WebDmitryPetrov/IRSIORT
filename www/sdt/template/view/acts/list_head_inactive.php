<? if (!empty($legend)):?>
<h1><?=$legend; ?></h1>
<? endif; ?>


<? if (!empty($list)):?>
    <h3>Всего <?=count($list)?></h3>

    <table class="table table-bordered  table-striped">
        <thead>
        <tr>
            <th valign="top">Центр</th>
            <th valign="top"><nobr>№</nobr></th>
            <th valign="top">Дата создания</th>

            <th valign="top">Дата тестирования</th>



            <th valign="top">Дата отправки</th>
            <th valign="top">id</th>

        </tr>
        </thead>
        <tbody>


        <?php

        foreach($list as $item):
            /** @var Act $item */
            if(!$item->getUniversity()) continue;
            ?>
            <tr class="<? if($item->is_changed_checker):?><?endif;?>">
                <td  class="wrap"><?php echo $item->getUniversity()->name ?></td>
                <td><?=$item->number?></td>
                <td><?=$C->date($item->created)?></td>


                <td><?php echo $C->date($item->testing_date); ?>
                </td>

                <td><?=$C->date($item->last_state_update)=='01.01.1970'?'':$C->date($item->last_state_update);?> </td>
                <td><?=$item->id?></td>
            </tr>

        <?php  endforeach;?>
        </tbody>
    </table>
<? else: ?>
    <h3>Тестовые сессии отсутствуют</h3>
<? endif; ?>

