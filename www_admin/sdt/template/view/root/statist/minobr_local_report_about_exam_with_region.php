<?
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $caption ?> </h1>
    <form action="" method="POST">

        <label>от :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="from"

                    readonly="readonly" size="16" type="text"
                    value="<?= $from ?>">
            </div>
        </label> <label>До :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                    class="input-small"
                    name="to"

                    readonly="readonly" size="16" type="text"
                    value="<?= $to ?>">
            </div>
        </label>
        <br>
        <label>Регион <select name="region">
                <option>Не указан</option>
                <? foreach ($regions as $r): /** @var Region $r */ ?>
                    <option value="<?= $r->id ?>"
                            <? if ($r->id == $region): ?>selected="selected"<? endif ?>><?= $r->caption ?></option>
                <? endforeach; ?>
            </select></label>
        <input type="submit" value="Отфильтровать">
    </form>
<? if (!empty($search)): ?>
    <h1>
	Перечень организаций-партнеров образовательных организаций высшего 
	образования Российской Федерации, на базе которых по состоянию на
	<?= $to ?> проводится экзамен по русскому языку как иностранному,
	истории России и основам законодательства Российской Федерации
	(далее – комплексный экзамен)
	</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2"><p>№</p></th>
            <th rowspan="2"><p>Наименование 
организаций-партнеров
</p></th>
            <th rowspan="2"><p>Дата заключения соглашения (дата начала работы организации по проведению экзамена)</p></th>
            <th rowspan="2"><p>Субъект РФ</p></th>
            <th ><p>Общее количество иностранных граждан, участвовавших в сдаче комплексного экзамена</p></th>
            <th ><p>Количество иностранных граждан, успешно сдавших комплексный экзамен</p></th>

        </tr>
        <tr>
		<th colspan="2">
Период с <?=$from?> по <?=$to?>
</th>
		</tr>
        </thead>
        <tbody>
        <? foreach ($array as $item): ?>
            <tr>

                <th colspan="6" ><?= $item['full'] ?></th>
             
               


            </tr>
            <? foreach ($item['data'] as $key=>$center): ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><?= $center['caption'] ?></td>
                    <td><?= date('d.m.Y',strtotime($center['created'])) ?></td>
                    <td><?= $center['region'] ?></td>

                    <td><?= $center['total'] ?></td>
                    <td><?= $center['success'] ?></td>
                  

                   

                </tr>
            <? endforeach ?>
        <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>