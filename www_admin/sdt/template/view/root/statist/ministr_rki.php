<form action="" method="get">
    <input type="hidden" name="action" value="ministr_statist_rki">
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
<?
//var_dump($data);
if (empty($data)) return; ?>

<p align="right">����������</p>
<p align="center"><strong>�������� � ���������� ������������ �� �������� ����� ��� ������������</strong><u> </u><br/>
    ����������� ����������� �������, ������� ������������, � <strong>����</strong></p>
<p align="right">������� 2.1</p>
<table border="1" cellspacing="0" cellpadding="0" width="999">
    <tr>
        <td width="173" rowspan="2" valign="top"><p align="center">������ ������������</p></td>
        <td width="275" colspan="2" valign="top"><p align="center">����������� ����������� �������, �������
                ������������, � ����, ���.</p></td>
        <td width="156" colspan="2" valign="top"><p align="center">��������� ����� ������������, ���.</p></td>
        <td width="267" colspan="2" valign="top"><p align="center">����������� ����������� �������, �����������
                ������������, � ����, ���.</p></td>
        <td width="129" valign="top"><p align="center">��������� ��������� ������������, ���.</p></td>
    </tr>
    <tr>
        <td width="129" valign="top"><p align="center">����������� �� <?=date('Y')?>�.</p></td>
        <td width="147" valign="top"><p align="center">����������� ��  <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td width="70" valign="top"><p align="center">��������� ����� ������������ ���������� ����, ���.</p></td>
        <td width="86" valign="top"><p align="center">����� ��������� ���� �� ����� ������������ ��������, ���.</p></td>
        <td width="138" valign="top"><p align="center">����������� <?=date('Y')?>�.</p></td>
        <td width="129" valign="top"><p align="center">����������� ��  <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td width="129" valign="top"><p align="center">1 ����������</p></td>
    </tr>
    <tr>
        <td width="173"><p>������������ ���/� 1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][1]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][1]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>������� ��� /� 2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][2]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][2]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>������ ���������������� ������� ����-I/�1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][3]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][3]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>������ ���������������� ������� ����-II/�2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][4]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][4]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>������ ���������������� ������� ����-III/� 1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][5]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][5]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>��������� ���������������� ������� ����-IV/�2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p>

            <p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][6]['pass'] ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][6]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>��� ������ � ����������� ���������� ���������</p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['pfur'][7]['pass'] ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">&nbsp;</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['pfur'][7]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>�����:</p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= (
                    $data['pfur'][1]['pass']
                    + $data['pfur'][2]['pass']
                    + $data['pfur'][3]['pass']
                    + $data['pfur'][4]['pass']
                    + $data['pfur'][5]['pass']
                    + $data['pfur'][6]['pass']
                    + $data['pfur'][7]['pass']
                )
                ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">&nbsp;</p></td>
        <td width="129" valign="top"><p align="center"><?= (
                    $data['pfur'][1]['retry']
                    + $data['pfur'][2]['retry']
                    + $data['pfur'][3]['retry']
                    + $data['pfur'][4]['retry']
                    + $data['pfur'][5]['retry']
                    + $data['pfur'][6]['retry']
                    + $data['pfur'][7]['retry']
                )
                ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
</table>
<p>&nbsp;</p>


<p align="right">����������</p>
<p align="center"><strong>�������� � ���������� ������������ �� �������� ����� ��� ������������</strong><u> </u><br/>
    ����������� ����������� �������, ������� ������������, � <strong>� ��������� ������� ������������</strong></p>
<p align="right">������� 2.1</p>
<table border="1" cellspacing="0" cellpadding="0" width="999">
    <tr>
        <td width="173" rowspan="2" valign="top"><p align="center">������ ������������</p></td>
        <td width="275" colspan="2" valign="top"><p align="center">����������� ����������� �������, ������� ������������, � ������� ������������, ���.</p></td>
        <td width="156" colspan="2" valign="top"><p align="center">��������� ����� ������������, ���.</p></td>
        <td width="267" colspan="2" valign="top"><p align="center">����������� ����������� �������, ����������� ������������, � ������� ������������, ���.</p></td>
        <td width="129" valign="top"><p align="center">��������� ��������� ������������, ���.</p></td>
    </tr>
    <tr>
        <td width="129" valign="top"><p align="center">����������� �� <?=date('Y')?>�.</p></td>
        <td width="147" valign="top"><p align="center">����������� ��  <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td width="70" valign="top"><p align="center">����� ��������� ������� ��������� ����, ���.</p></td>
        <td width="86" valign="top"><p align="center">����� ��������� ���� �� ����� ������������ �������� � ��������� �������, ���.</p></td>
        <td width="138" valign="top"><p align="center">����������� <?=date('Y')?>�.</p></td>
        <td width="129" valign="top"><p align="center">����������� ��  <?=$begin->format('d.m.Y');?> - <?=$end->format('d.m.Y');?></p></td>
        <td width="129" valign="top"><p align="center">1 ����������</p></td>
    </tr>
    <tr>
        <td width="173"><p>������������ ���/� 1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][1]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][1]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>������� ��� /� 2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][2]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][2]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>������ ���������������� ������� ����-I/�1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][3]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][3]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>������ ���������������� ������� ����-II/�2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][4]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][4]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>������ ���������������� ������� ����-III/� 1<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][5]['pass'] ?></p></td>
        <td width="70" valign="top"><p>&nbsp;</p></td>
        <td width="86" valign="top"><p>&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][5]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">-</p></td>
    </tr>
    <tr>
        <td width="173"><p>��������� ���������������� ������� ����-IV/�2<strong></strong></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p>

            <p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][6]['pass'] ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">0</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][6]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>��� ������ � ����������� ���������� ���������</p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= $data['local'][7]['pass'] ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">&nbsp;</p></td>
        <td width="129" valign="top"><p align="center"><?= $data['local'][7]['retry'] ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
    <tr>
        <td width="173"><p>�����:</p></td>
        <td width="129" valign="top"><p align="center">0</p></td>
        <td width="147" valign="top"><p align="center"><?= (
                    $data['local'][1]['pass']
                    + $data['local'][2]['pass']
                    + $data['local'][3]['pass']
                    + $data['local'][4]['pass']
                    + $data['local'][5]['pass']
                    + $data['local'][6]['pass']
                    + $data['local'][7]['pass']
                )
                ?></p></td>
        <td width="70" valign="top"><p align="center">&nbsp;</p></td>
        <td width="86" valign="top"><p align="center">&nbsp;</p></td>
        <td width="138" valign="top"><p align="center">&nbsp;</p></td>
        <td width="129" valign="top"><p align="center"><?= (
                    $data['local'][1]['retry']
                    + $data['local'][2]['retry']
                    + $data['local'][3]['retry']
                    + $data['local'][4]['retry']
                    + $data['local'][5]['retry']
                    + $data['local'][6]['retry']
                    + $data['local'][7]['retry']
                )
                ?></p></td>
        <td width="129" valign="top"><p align="center">&nbsp;</p></td>
    </tr>
</table>
<p>&nbsp;</p>