<?php
/** @var Reports $object */
?>

<script>
    function alls()
    {
        var ch=$('#all').prop("checked");
        if (!(ch))
        {
            $('.vis').removeProp("checked");
        }
        else
        {
            $('.vis').prop("checked","checked");
        }
    }
</script>

<h1>Отчеты</h1>

<a class="btn btn-info" href="./index.php?action=report_add">Добавить</a>

<table class="table table-bordered">





	<tr>
		<th style="width:7%">№ п/п</th>
		<th style="width:10%">Доступен пользователю
        <br><input type="checkbox" onclick="alls()" id="all"><i style="color:grey;font-size:10px">Выделить все</i></th>
		<th style="width:80%">Название</th>
		<th style="width:10%">Действия</th>
        </tr>

            <form method="post">
            <?php
                $i=0;
                foreach ($object as $item):        ?>
                <tr>


                    <td>
                        <?=++$i;?>
                    </td>
                    <td>
                        <input type="checkbox" name="reports[]" value="<?=$item->id?>"
                            <? if ($item->visible):?>
                               checked="checked"
                        <? endif; ?>
                       class="vis" >
                    </td>

                    <td>
                        <strong><a href="?action=<?=$item->action_name;?>" target="_blank"><?=$item->caption?></a></strong>
                    </td>
                    <td>
                        <a class="btn btn-warning" href="./index.php?action=report_edit&id=<?=$item->id;?>">Редактировать</a>
                        <br>
                        <a class="btn btn-danger" href="./index.php?action=report_delete&id=<?=$item->id;?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                    </td>

                </tr>

        <?php endforeach;             ?>

                <tr><td colspan="4">
                <input type="hidden" name="do" value="1">
                <input type="submit" class="btn btn-success" value="Сохранить">
                    </td> </tr>
            </form>


</table>
