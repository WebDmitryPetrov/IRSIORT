<?
?>
    <style>
        tr.type-head {
            font-weight: bold;
        }
    </style>
    <h1><?= $caption ?> </h1>
    <form action="" method="POST">

        <label>�� :
            <div class="input-prepend date datepicker "
                 data-date="">
                <span class="add-on"><i class="icon-th"></i> </span> <input
                        class="input-small"
                        name="from"

                        readonly="readonly" size="16" type="text"
                        value="<?= $from ?>">
            </div>
        </label> <label>�� :
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

        <input type="submit" value="�������������">
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


    <h3>���������� � ���������� �������� �� �������� ����� ��� ������������, ������� ������ � ������� ����������������
        ���������� ��������� � �����������-������� �� �������</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">� �/�</th>
            <th rowspan="2">������������ ����������� ��������</th>
            <th colspan="2">������ ����� ���������� �����������-��������</th>
            <th colspan="<?= $yearsReport ?>">����� ���������� ���������, ��������� �������</th>
            <th colspan="<?= $yearsReport ?>">����� ���������, ������� ������� ������� � ���������� ����������</th>
            <th colspan="<?= $yearsReport ?>">����� ���������, �� ������� �������</th>
            <th colspan="<?= $yearsReport ?>">��������� ����� ��������, ���.</th>

        </tr>
        <tr>
            <th>������</th>
            <th>���������� �����, �����</th>
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


    <h3>���������� � ���������� �������� �� �������� ����� ��� ������������, ������� ������ � �������
        ����������������
        ���������� ��������� ��������������� � ��������������� �����������</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">� �/�</th>
            <th rowspan="2">����� ���������� ��������</th>
            <th colspan="2">������ ����� ���������� �����������-��������</th>
            <th colspan="<?= $yearsReport ?>">����� ���������� ���������, ��������� �������</th>
            <th colspan="<?= $yearsReport ?>">����� ���������, ������� ������� ������� � ���������� ����������</th>
            <th colspan="<?= $yearsReport ?>">����� ���������, �� ������� �������</th>
            <th colspan="<?= $yearsReport ?>">��������� ����� ��������, ���.</th>

        </tr>
        <tr>
            <th>������� ���������� ��������� (������ � ������ ���������� ������� ��� ����������������� �� �������)</th>
            <th>���������� �����, �����</th>
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


    <h3>���������� � ���������� �������� �� �������� ����� ��� ������������, ������� ������ � �������
        ����������������
        ���������� ��������� � ������������ �������� (� ���������� ���������)</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">� �/�</th>
            <th rowspan="2">������������� �����������-��������</th>
            <th colspan="2">������ ����� ���������� �����������-��������</th>
            <th colspan="<?= $yearsReport ?>">����� ���������� ���������, ��������� �������</th>
            <th colspan="<?= $yearsReport ?>">����� ���������, ������� ������� ������� � ���������� ����������</th>
            <th colspan="<?= $yearsReport ?>">����� ���������, �� ������� �������</th>
            <th colspan="<?= $yearsReport ?>">��������� ����� ��������, ���.</th>

        </tr>
        <tr>
            <th>������� ���������� ��������� </th>
            <th>���������� �����, �����</th>
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