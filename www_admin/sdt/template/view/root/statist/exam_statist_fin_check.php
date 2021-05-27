<form action="" method="get">
    <input type="hidden" name="action" value="exam_statist_fin_check">
    <label>��:

        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="begin"

                readonly="readonly" size="16" type="text"
                value="<?= $begin->format('d.m.Y') ?>">
        </div>
    </label> <label>��:

        <div class="input-prepend date datepicker "
             data-date="">
            <span class="add-on"><i class="icon-th"></i> </span> <input
                class="input-small"
                name="end"

                readonly="readonly" size="16" type="text"
                value="<?= $end->format('d.m.Y') ?>">
        </div>
    </label>
    <input type="submit" value="���������">
</form>


<? if ($array):?>

<h1>��� ��������� ���. ������� ����</h1>
<h2>���������� ������ ���������� ��������� ������� ���� (��������� ��������) �� ��������� ����� �����</h2>
<h2> ��� ���������� ������������ �������� � <?= $begin->format('d.m.Y') ?> �� <?= $end->format('d.m.Y') ?></h2>
<? $current = $array['local']; ?>
<? $dubl = $dubl_array['local']; ?>
<table class="table table-bordered">
   <thead>
    <tr>
        <td rowspan="2">������������ ������</td>
        <td colspan="4">���������� �������� ���������, ������� �������</td>
        <td colspan="4">���������� ���������, ��������� �������</td>
        <td>&nbsp;</td>
        <td colspan="4">���������� ��������� ����������� 2 �����</td>
        <td colspan="4">���������� ��������� ����������� 1 ����</td>
    </tr>
    <tr>
        <td>���������� �� ������ ��� ������ (���)</td>
        <td>���������� �� ��������� ���������� (���)</td>
        <td>��� �� ���������� (���)</td>
        <td>����� ����������</td>
        <td>���������� �� ������ ��� ������ (���)</td>
        <td>���������� �� ��������� ���������� (���)</td>
        <td>��� �� ���������� (���)</td>
        <td>����� ����������</td>
        <td>��������� ���������� ��������� ���������, ����������� � ��������</td>
        <td>���������� �� ������ ��� ������ (���)</td>
        <td>���������� �� ��������� ���������� (���)</td>
        <td>��� �� ���������� (���)</td>
        <td>����� ����������</td>
        <td>���������� �� ������ ��� ������ (���)</td>
        <td>���������� �� ��������� ���������� (���)</td>
        <td>��� �� ���������� (���)</td>
        <td>����� ����������</td>
    </tr>
   </thead>
    <tbody>
    <? $type = $current['simple'] ?>
    <tr>
        <td>����������� �������� ������</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <? $type = $current['simple_simple'] ?>
    <tr>
        <td>����� ����������� ������� �������� ������ � ������� ���������������� �Ի ��� ����������� ��</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['dnr'] ?>
        <td>��� ��� � ���</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['dnr_simple'] ?>
        <td>������� �� ����������� ��������� ��� ��� � ��� (������ "������� ������" � "������ ���������������� ��")</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['ukraine'] ?>
        <td>����������� �� ������� �� ��� ������� �������, ��������� ������� ������</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['invalid'] ?>
        <td>��� ��� � ������������� �������������</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <td>�����. ��������� ����������</td>
        <td><?=$dubl['rnr']['cc']?></td>
        <td><?=$dubl['rvj']['cc']?></td>
        <td><?=$dubl['vnj']['cc']?></td>
        <td><?=$dubl['rnr']['cc'] + $dubl['rvj']['cc'] + $dubl['vnj']['cc']  ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
</table>

<hr>
<p>&nbsp;</p><h1>��� ������� ���. ������� ����</h1>
<h2>���������� ������ ���������� ��������� ������� ���� (��������� ��������) �� ��������� ����� �����</h2>
<h2> ��� ���������� ������������ �������� � <?= $begin->format('d.m.Y') ?> �� <?= $end->format('d.m.Y') ?></h2>
<? $current = $array['external']; ?>
    <? $dubl = $dubl_array['external']; ?>
<table class="table table-bordered">
   <thead>
    <tr>
        <td rowspan="2">������������ ������</td>
        <td colspan="4">���������� �������� ���������, ������� �������</td>
        <td colspan="4">���������� ���������, ��������� �������</td>
        <td>&nbsp;</td>
        <td colspan="4">���������� ��������� ����������� 2 �����</td>
        <td colspan="4">���������� ��������� ����������� 1 ����</td>
    </tr>
    <tr>
        <td>���������� �� ������ ��� ������ (���)</td>
        <td>���������� �� ��������� ���������� (���)</td>
        <td>��� �� ���������� (���)</td>
        <td>����� ����������</td>
        <td>���������� �� ������ ��� ������ (���)</td>
        <td>���������� �� ��������� ���������� (���)</td>
        <td>��� �� ���������� (���)</td>
        <td>����� ����������</td>
        <td>��������� ���������� ��������� ���������, ����������� � ��������</td>
        <td>���������� �� ������ ��� ������ (���)</td>
        <td>���������� �� ��������� ���������� (���)</td>
        <td>��� �� ���������� (���)</td>
        <td>����� ����������</td>
        <td>���������� �� ������ ��� ������ (���)</td>
        <td>���������� �� ��������� ���������� (���)</td>
        <td>��� �� ���������� (���)</td>
        <td>����� ����������</td>
    </tr>
   </thead>
    <tbody>
    <? $type = $current['simple'] ?>
    <tr>
        <td>����������� �������� ������</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <? $type = $current['simple_simple'] ?>
    <tr>
        <td>����� ����������� ������� �������� ������ � ������� ���������������� �Ի ��� ����������� ��</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['dnr'] ?>
        <td>��� ��� � ���</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['dnr_simple'] ?>
        <td>������� �� ����������� ��������� ��� ��� � ��� (������ "������� ������" � "������ ���������������� ��")</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['ukraine'] ?>
        <td>����������� �� ������� �� ��� ������� �������, ��������� ������� ������</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <? $type = $current['invalid'] ?>
        <td>��� ��� � ������������� �������������</td>
        <td><?= $type['rnr']['cc']['first'] ?></td>
        <td><?= $type['rvj']['cc']['first'] ?></td>
        <td><?= $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
        <td><?= $type['rnr']['cc']['note'] ?></td>
        <td><?= $type['rvj']['cc']['note'] ?></td>
        <td><?= $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
            + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
        <td><?= $type['rnr']['cc']['two'] ?></td>
        <td><?= $type['rvj']['cc']['two'] ?></td>
        <td><?= $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
        <td><?= $type['rnr']['cc']['one'] ?></td>
        <td><?= $type['rvj']['cc']['one'] ?></td>
        <td><?= $type['vnj']['cc']['one'] ?></td>
        <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
    </tr>
    <tr>
        <td>�����. ��������� ����������</td>
        <td><?=$dubl['rnr']['cc']?></td>
        <td><?=$dubl['rvj']['cc']?></td>
        <td><?=$dubl['vnj']['cc']?></td>
        <td><?=$dubl['rnr']['cc'] + $dubl['rvj']['cc'] + $dubl['vnj']['cc']  ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
</table>
<p>&nbsp;</p>

    <?foreach ($hc_list as $hc):?>
        <hr>
        <hr>
        <hr>


        <h1>��� ��������� ���. ������� <?=$hc['caption']?></h1>
        <h2>���������� ������ ���������� ��������� ������� ���� (��������� ��������) �� ��������� ����� �����</h2>
        <h2> ��� ���������� ������������ �������� � <?= $begin->format('d.m.Y') ?> �� <?= $end->format('d.m.Y') ?></h2>
        <? $current = $hc['result']['local']; ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <td rowspan="2">������������ ������</td>
                <td colspan="4">���������� �������� ���������, ������� �������</td>
                <td colspan="4">���������� ���������, ��������� �������</td>
                <td>&nbsp;</td>
                <td colspan="4">���������� ��������� ����������� 2 �����</td>
                <td colspan="4">���������� ��������� ����������� 1 ����</td>
            </tr>
            <tr>
                <td>���������� �� ������ ��� ������ (���)</td>
                <td>���������� �� ��������� ���������� (���)</td>
                <td>��� �� ���������� (���)</td>
                <td>����� ����������</td>
                <td>���������� �� ������ ��� ������ (���)</td>
                <td>���������� �� ��������� ���������� (���)</td>
                <td>��� �� ���������� (���)</td>
                <td>����� ����������</td>
                <td>��������� ���������� ��������� ���������, ����������� � ��������</td>
                <td>���������� �� ������ ��� ������ (���)</td>
                <td>���������� �� ��������� ���������� (���)</td>
                <td>��� �� ���������� (���)</td>
                <td>����� ����������</td>
                <td>���������� �� ������ ��� ������ (���)</td>
                <td>���������� �� ��������� ���������� (���)</td>
                <td>��� �� ���������� (���)</td>
                <td>����� ����������</td>
            </tr>
            </thead>
            <tbody>
            <? $type = $current['simple'] ?>
            <tr>
                <td>����������� �������� ������</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <? $type = $current['simple_simple'] ?>
            <tr>
                <td>����� ����������� ������� �������� ������ � ������� ���������������� �Ի ��� ����������� ��</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['dnr'] ?>
                <td>��� ��� � ���</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['dnr_simple'] ?>
                <td>������� �� ����������� ��������� ��� ��� � ��� (������ "������� ������" � "������ ���������������� ��")</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['ukraine'] ?>
                <td>����������� �� ������� �� ��� ������� �������, ��������� ������� ������</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['invalid'] ?>
                <td>��� ��� � ������������� �������������</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>

            </tbody>
        </table>

        <hr>
        <p>&nbsp;</p><h1>��� ������� ���. ������� <?=$hc['caption']?></h1>
        <h2>���������� ������ ���������� ��������� ������� ���� (��������� ��������) �� ��������� ����� �����</h2>
        <h2> ��� ���������� ������������ �������� � <?= $begin->format('d.m.Y') ?> �� <?= $end->format('d.m.Y') ?></h2>
        <? $current = $hc['result']['external']; ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <td rowspan="2">������������ ������</td>
                <td colspan="4">���������� �������� ���������, ������� �������</td>
                <td colspan="4">���������� ���������, ��������� �������</td>
                <td>&nbsp;</td>
                <td colspan="4">���������� ��������� ����������� 2 �����</td>
                <td colspan="4">���������� ��������� ����������� 1 ����</td>
            </tr>
            <tr>
                <td>���������� �� ������ ��� ������ (���)</td>
                <td>���������� �� ��������� ���������� (���)</td>
                <td>��� �� ���������� (���)</td>
                <td>����� ����������</td>
                <td>���������� �� ������ ��� ������ (���)</td>
                <td>���������� �� ��������� ���������� (���)</td>
                <td>��� �� ���������� (���)</td>
                <td>����� ����������</td>
                <td>��������� ���������� ��������� ���������, ����������� � ��������</td>
                <td>���������� �� ������ ��� ������ (���)</td>
                <td>���������� �� ��������� ���������� (���)</td>
                <td>��� �� ���������� (���)</td>
                <td>����� ����������</td>
                <td>���������� �� ������ ��� ������ (���)</td>
                <td>���������� �� ��������� ���������� (���)</td>
                <td>��� �� ���������� (���)</td>
                <td>����� ����������</td>
            </tr>
            </thead>
            <tbody>
            <? $type = $current['simple'] ?>
            <tr>
                <td>����������� �������� ������</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <? $type = $current['simple_simple'] ?>
            <tr>
                <td>����� ����������� ������� �������� ������ � ������� ���������������� �Ի ��� ����������� ��</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['dnr'] ?>
                <td>��� ��� � ���</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['dnr_simple'] ?>
                <td>������� �� ����������� ��������� ��� ��� � ��� (������ "������� ������" � "������ ���������������� ��")</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['ukraine'] ?>
                <td>����������� �� ������� �� ��� ������� �������, ��������� ������� ������</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>
            <tr>
                <? $type = $current['invalid'] ?>
                <td>��� ��� � ������������� �������������</td>
                <td><?= $type['rnr']['cc']['first'] ?></td>
                <td><?= $type['rvj']['cc']['first'] ?></td>
                <td><?= $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first'] ?></td>
                <td><?= $type['rnr']['cc']['note'] ?></td>
                <td><?= $type['rvj']['cc']['note'] ?></td>
                <td><?= $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['first'] + $type['rvj']['cc']['first'] + $type['vnj']['cc']['first']
                    + $type['rnr']['cc']['note'] + $type['rvj']['cc']['note'] + $type['vnj']['cc']['note'] ?></td>
                <td><?= $type['rnr']['cc']['two'] ?></td>
                <td><?= $type['rvj']['cc']['two'] ?></td>
                <td><?= $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['two'] + $type['rvj']['cc']['two'] + $type['vnj']['cc']['two'] ?></td>
                <td><?= $type['rnr']['cc']['one'] ?></td>
                <td><?= $type['rvj']['cc']['one'] ?></td>
                <td><?= $type['vnj']['cc']['one'] ?></td>
                <td><?= $type['rnr']['cc']['one'] + $type['rvj']['cc']['one'] + $type['vnj']['cc']['one'] ?></td>
            </tr>

            </tbody>
        </table>

        <?endforeach?>
<?endif?>