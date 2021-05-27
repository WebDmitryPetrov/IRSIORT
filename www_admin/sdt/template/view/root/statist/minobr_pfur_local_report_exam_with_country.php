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

        <input type="submit" value="Отфильтровать">
    </form>
<? if (!empty($search)):
//    $yearsReport = date('Y') - 2015 + 1;
    $yearsBlock = '';
    $yearsReport = $toDate - $fromDate + 1;
    $yearRange = range($fromDate, $toDate);
    foreach ($yearRange as $year) {
        $yearsBlock .= '<th>' . $year . '</th>';
    }
    ?>


    <h3>Информация о проведении экзамена по русскому языку как иностранному, истории России и основам законодательства
        Российской Федерации в организация-партёрах за рубежом</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">№ п/п</th>
            <th rowspan="2">Наименование организации партнера</th>
            <th colspan="2">Адреса место нахожденяи организации-партнера</th>
            <th colspan="<?= $yearsReport ?>">Общее количество мигрантов, сдававших экзамен</th>
            <th colspan="<?= $yearsReport ?>">Число мигрантов, успешно сдавших экзамен и получивших сертификат</th>
            <th colspan="<?= $yearsReport ?>">Число мигрантов, не сдавших экзамен</th>
            <th colspan="<?= $yearsReport ?>">Стоимость сдачи экзамена, руб.</th>

        </tr>
        <tr>
            <th>Страна</th>
            <th>Населенный пункт, улица</th>
            <?= $yearsBlock; ?>
            <?= $yearsBlock; ?>
            <?= $yearsBlock; ?>
            <?= $yearsBlock; ?>
        </tr>
        </thead>
        <tbody>

        <?
        $filtered = array_filter($array, function (minobr_pfur_local_report_exam_with_country_dto $dto) {
            return $dto->isHead() === false and $dto->isRussia() === false;
        });
        //die(var_dump($filtered));
        $key = 1;
        foreach ($filtered

        as $dto):

        /** @var minobr_pfur_local_report_exam_with_country_dto $dto */
        ?>
        <tr>

            <td><?= $key++ ?></td>
            <td><?= $dto->getCaption() ?></td>
            <td><?= $dto->getCountry() ?></td>
            <td><?= $dto->getAddress() ?></td>
            <? foreach ($yearRange as $year): ?>
                <td><?= $dto->getCertYear($year) + $dto->getNoteYear($year) ?></td>
            <? endforeach; ?>
            <? foreach ($yearRange as $year): ?>
                <td><?= $dto->getCertYear($year) ?></td>
            <? endforeach; ?>
            <? foreach ($yearRange as $year): ?>
                <td><?= $dto->getNoteYear($year) ?></td>
            <? endforeach; ?>  <? foreach ($yearRange as $year): ?>
                <td>&nbsp;</td>
            <? endforeach; ?>

            <? endforeach ?>
        </tbody>
    </table>

    <hr>


    <h3>Информация о проведении экзамена по русскому языку как иностранному, истории России и основам
        законодательства
        Российской Федерации непосредственно в образовательной организации</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">№ п/п</th>
            <th rowspan="2">Место проведения экзамена</th>
            <th colspan="2">Адреса место нахожденяи организации-партнера</th>
            <th colspan="<?= $yearsReport ?>">Общее количество мигрантов, сдававших экзамен</th>
            <th colspan="<?= $yearsReport ?>">Число мигрантов, успешно сдавших экзамен и получивших сертификат</th>
            <th colspan="<?= $yearsReport ?>">Число мигрантов, не сдавших экзамен</th>
            <th colspan="<?= $yearsReport ?>">Стоимость сдачи экзамена, руб.</th>

        </tr>
        <tr>
            <th>Субъект Российской федерации (страна в случае нахождения филиала или представительства за рубежом)</th>
            <th>Населенный пункт, улица</th>
            <?= $yearsBlock; ?>
            <?= $yearsBlock; ?>
            <?= $yearsBlock; ?>
            <?= $yearsBlock; ?>
        </tr>
        </thead>
        <tbody>

        <?
        $filtered = array_filter($array, function (minobr_pfur_local_report_exam_with_country_dto $dto) {
            return $dto->isHead() === true;
        });
        //die(var_dump($filtered));
        $key = 1;
        foreach ($filtered

        as $dto):

        /** @var minobr_pfur_local_report_exam_with_country_dto $dto */
        ?>
        <tr>

            <td><?= $key++ ?></td>
            <td><?= $dto->getCaption() ?></td>
            <td><?= $dto->isRussia() ? $dto->getRegion() : $dto->getCountry() ?></td>
            <td><?= $dto->getAddress() ?></td>
            <? foreach ($yearRange as $year): ?>
                <td><?= $dto->getCertYear($year) + $dto->getNoteYear($year) ?></td>
            <? endforeach; ?>
            <? foreach ($yearRange as $year): ?>
                <td><?= $dto->getCertYear($year) ?></td>
            <? endforeach; ?>
            <? foreach ($yearRange as $year): ?>
                <td><?= $dto->getNoteYear($year) ?></td>
            <? endforeach; ?>  <? foreach ($yearRange as $year): ?>
                <td>&nbsp;</td>
            <? endforeach; ?>

            <? endforeach ?>
        </tbody>
    </table>

    <hr>


    <h3>Информация о проведении экзамена по русскому языку как иностранному, истории России и основам
        законодательства
        Российской Федерации в организациях партнёрах (в Российской федерации)</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">№ п/п</th>
            <th rowspan="2">Наименованиее организации-партнера</th>
            <th colspan="2">Адреса место нахожденяи организации-партнера</th>
            <th colspan="<?= $yearsReport ?>">Общее количество мигрантов, сдававших экзамен</th>
            <th colspan="<?= $yearsReport ?>">Число мигрантов, успешно сдавших экзамен и получивших сертификат</th>
            <th colspan="<?= $yearsReport ?>">Число мигрантов, не сдавших экзамен</th>
            <th colspan="<?= $yearsReport ?>">Стоимость сдачи экзамена, руб.</th>

        </tr>
        <tr>
            <th>Субъект Российской федерации </th>
            <th>Населенный пункт, улица</th>
            <?= $yearsBlock; ?>
            <?= $yearsBlock; ?>
            <?= $yearsBlock; ?>
            <?= $yearsBlock; ?>
        </tr>
        </thead>
        <tbody>

        <?
        $filtered = array_filter($array, function (minobr_pfur_local_report_exam_with_country_dto $dto) {
            return $dto->isHead() === false && $dto->isRussia() === true;
        });
        //die(var_dump($filtered));
        $key = 1;
        foreach ($filtered

        as $dto):

        /** @var minobr_pfur_local_report_exam_with_country_dto $dto */
        ?>
        <tr>

            <td><?= $key++ ?></td>
            <td><?= $dto->getCaption() ?></td>
            <td><?=  $dto->getRegion()  ?></td>
            <td><?= $dto->getAddress() ?></td>
            <? foreach ($yearRange as $year): ?>
                <td><?= $dto->getCertYear($year) + $dto->getNoteYear($year) ?></td>
            <? endforeach; ?>
            <? foreach ($yearRange as $year): ?>
                <td><?= $dto->getCertYear($year) ?></td>
            <? endforeach; ?>
            <? foreach ($yearRange as $year): ?>
                <td><?= $dto->getNoteYear($year) ?></td>
            <? endforeach; ?>  <? foreach ($yearRange as $year): ?>
                <td>&nbsp;</td>
            <? endforeach; ?>

            <? endforeach ?>
        </tbody>
    </table>

<? endif; ?>