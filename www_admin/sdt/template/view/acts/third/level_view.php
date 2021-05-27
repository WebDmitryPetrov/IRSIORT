
<?php
/** @var Act $object */
if ($object->is_changed_checker):?>
<div class="row-fluid">
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    Документ изменен проверяющей стороной
    </div>
</div>
<?php endif; ?>
<div class="btn-group">
    <button class="btn dropdown-toggle btn-info " data-toggle="dropdown">
        Печать
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <!--<?php if($object->isMigrantSession()):?>
        <li><a class=""   target="_blank"
               href="index.php?action=act_print_migrant&id=<?php echo $object->id; ?>">Акт</a>
        </li>
        <?php else: ?>
            <li><a class=""   target="_blank"
                   href="index.php?action=act_print&id=<?php echo $object->id; ?>">Акт</a>
            </li>
        <?php endif; ?>
		
		-->
		
		
		<li class="dropdown-submenu">
		 <a tabindex="-1" >Акт</a>
<ul class="dropdown-menu">
<? $signers=ActSignings::get4Act();
if($object->isMigrantSession()) $href='index.php?action=act_print_migrant&id=';
else $href='index.php?action=act_print&id=';
foreach ($signers as $signer)
{
echo '<li><a class=""   target="_blank"
               href="'.$href.$object->id.'&s_id='.$signer->id.'">'.$signer->position.' '.$signer->caption.'</a>
        </li>';
}

?>
</ul>
	</li>	
		
		
		
		
		
        <li><a class=""   target="_blank"
               href="index.php?action=act_table_print&id=<?php echo $object->id; ?>"
               target="_blank">Сводная таблица</a></li>
    </ul>
</div>
<div class="btn-group">
    <button class="btn dropdown-toggle btn-warning " data-toggle="dropdown">
        Загрузить  скан
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a class="act_upload">Акт</a>
        </li>
        <li><a class="act_tabl_upload">Сводная таблица</a>
    </ul>
</div>



<table class="table table-bordered  table-striped">
    <tr>
        <th>Номер</th>
        <td><?php echo $object->number ?>
        </td>
    </tr>

    <tr>
        <th>ВУЗ</th>
        <td><?php echo $object->getUniversity()->name ?>
        </td>
    </tr>
    <tr>
        <th>Договор с ВУЗом</th>
        <td><?php echo $object->getUniversityDogovor() ?>
        </td>
    </tr>
    <tr>
        <th>Утверждающий акт</th>
        <td><?php echo $object->official ?>
        </td>
    </tr>
    <tr>
        <th>Ответственный за проведение тестрования</th>
        <td><?php echo $object->responsible ?>
        </td>
    </tr>
    <tr>
        <th>Дата тестирования</th>
        <td><?php echo $C->date($object->testing_date) ?>
        </td>
    </tr>

    <tr>
        <th>Оплата за оказанные услуги <?=TEXT_HEADCENTER_SHORT_IP?></th>
        <td><?php echo $object->amount_contributions ?>
        </td>
    </tr>
    <tr>
        <th>Комментарий</th>
        <td><?php echo $object->comment ?>
        </td>
    </tr>
    <tr>
        <th>Оплачено</th>
        <td><?php echo $object->paid ? 'Да' : 'Нет' ?>
        </td>
    </tr>

    <tr>
        <th>Загруженные файлы</th>
        <td><?php  $fileact = $object->getFileAct();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">Отсканированный
                        акт</a>
                </div> <?php endif;?> <?php  $fileact = $object->getFileActTabl();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">Отсканированная
                        сводная таблица</a>
                </div> <?php endif;?>
        </td>
    </tr>

    <tr>
        <th>Тестирования</th>
        <td>
            <table class="table-bordered  table-striped">
                <thead>
                <tr>
                    <th rowspan="2">Уровень тестирования</th>
                    <th colspan="2">Тестирование</th>
                    <th colspan="3">Пересдача</th>

                </tr>
                <tr>
                    <th>Человек</th>
                    <th>Стоимость тестирования</th>
                    <th>Человек</th>
                    <th>Субтестов</th>
                    <th>Стоимость пересдач тестирования</th>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($object->getTests() as $test):
                    /** @var ActTest $test */
                    ?>
                <tr>

                    <td><?php  echo $test->getLevel()?>
                    </td>
                    <td><?php  echo $test->people_first;?>
                    </td>
                    <td><?php  echo $test->getLevel()->price*$test->people_first; ?>
                    </td>
                    <td><?php  echo $test->people_retry;?>
                    </td>
                    <td><?php  echo $test->people_subtest_retry;?>
                    </td>
                    <td><?php  echo  $test->getLevel()->sub_test_price*$test->people_subtest_retry; ?>
                    </td>

                </tr>
                    <?php endforeach;?>
                </tbody>
            </table>

            <a class="btn btn-info btn-small"
               href="index.php?action=act_table_view&id=<?php echo $object->id; ?>">Просмотреть сводную таблицу</a></td>
    </tr>


</table>


<div class="modal hide fade" id="act" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" enctype="multipart/form-data"
          action="index.php?action=act_upload_scan" class="form-inline">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">Загрузить отсканированный акт</h3>
        </div>
        <div class="modal-body">


            <legend>Выберите файл</legend>

            <input type="hidden" value="<?php echo $object->id; ?>" name="id"> <input
                type="file" name="file" class="input-xlarge">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            <button class="btn btn-primary save" type="submit">Загрузить</button>
        </div>
    </form>
</div>
<div class="modal hide fade" id="act_tabl" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" enctype="multipart/form-data"
          action="index.php?action=act_upload_tabl_scan" class="form-inline">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
            <h3 id="myModalLabel">Загрузить отсканированную сводную таблицу</h3>
        </div>
        <div class="modal-body">


            <legend>Выберите файл</legend>

            <input type="hidden" value="<?php echo $object->id; ?>" name="id"> <input
                type="file" name="file" class="input-xlarge">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            <button class="btn btn-primary save" type="submit">Загрузить</button>
        </div>
    </form>
</div>
<script>
    $(function () {

        $('.act_upload').on('click', function (e) {
            e.preventDefault();
            $('#act').modal();
        });
        $('.act_tabl_upload').on('click', function (e) {
            e.preventDefault();
            $('#act_tabl').modal();
        });

    });
</script>
