<?php
/** @var Act $Act */
$i = 0;
?>
<h3>������� �������. ���� ������������: <?php echo $C->date($Act->testing_date); ?></h3>
<h4>�����������: <?php echo $Act->getUniversity(); ?></h4>
<h4>�������: <?php echo $Act->getUniversityDogovor(); ?></h4>
<a href="/sdt/index.php?action=act_third_view&id=<?php echo $Act->id ?>" class="btn btn-info">��������� � ���</a>

<?php if (!empty($Legend)): ?>
    <legend>
        <?php echo $Legend; ?>
    </legend>
<?php endif; ?>
<div class="bg_highlight">
    ��� ���������� ��������� � ������ ������� ������ (����� ��������� ������!) ������� ������� "ENTER" ��� ������
    "���������" ����� �������!
</div>
<fieldset>
    <legend>�������</legend>
    <div style="margin-bottom: 2px"><?php echo $Act->tester1 ?></div>
    <div><?php echo $Act->tester2 ?></div>
</fieldset>
<table
    class="table table-bordered  table-condensed summary_table">
    <?php foreach ($Act->getLevels() as $ActTest):
        $level = $ActTest->getLevel();
        ?>
        <thead>

        <tr>
            <td colspan="12"><h4>������� ������������ "<strong><?php echo $level->caption ?></strong>"</h4></td>
        </tr>
        <tr>
            <td rowspan="2"><strong>�
                    <nobr>�/�</nobr>
                </strong></td>
            <td rowspan="2" colspan="4"><strong>�������� � �����������</strong></td>


            <td colspan="6" class="center"><strong>����������</strong> (����� /
                %)
            </td>
            <td rowspan="2" class="center"><strong>����</strong>
            </td>
        </tr>
        <tr>
            <td class="center">��<span class="percent"><?php echo $level->reading; ?></span></td>
            <td class="center">���<span class="percent"><?php echo $level->writing; ?></span></td>
            <td class="center">����<span class="percent"><?php echo $level->grammar; ?></span></td>
            <td class="center">���<span class="percent"><?php echo $level->listening; ?></span></td>
            <td class="center">���<span class="percent"><?php echo $level->speaking; ?></span></td>
            <td class="center">���<span class="percent"><?php echo $level->total; ?></span></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ActTest->getPeople() as $Man): ?>
            <tr class=" summary <?php if ($Man->id % 2): ?> info <?php endif ?>">
                <td class="npp"><?= ++$i ?></td>
                <td colspan="2">
                    <table>
                        <tr>
                            <td colspan="2">
                                <fieldset>
                                    <legend>���</legend>

                                    <span class="value"><?php echo $Man->surname_rus; ?></span><br>

                                    <span class="value"><?php echo $Man->name_rus; ?></span>
                                    <br>

                                    <span class="value"><?php echo $Man->surname_lat; ?></span>

                                    <br>
                                    <span class="value"><?php echo $Man->name_lat; ?></span>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <fieldset>
                                    <legend>�������� �������������� ��������</legend>

                                    <span class="value"><?php echo $Man->passport_name; ?></span><br>


                                    <span class="value"><?php echo $Man->passport_series; ?></span>

                                    <span class="value"><?php echo $Man->passport; ?></span></br>
                                    <span class="value"><?php echo $C->date($Man->passport_date); ?></span><br>
                                    <span class="value"><?php echo $Man->passport_department; ?></span> <br>
                                    <?php if ($Man->getFilePassport()): ?>
                                        <a href="<?php echo $Man->getFilePassport()->getDownloadUrl() ?>"
                                           target="_blank"
                                           class="btn  btn-small btn-success"><span
                                                class="custom-icon-download"></span>�������</a>
                                    <?php endif; ?>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="border:0">
                                <fieldset >
                                    <legend>������ (�����������)</legend>
                                <span class="value"><?php echo $Man->getCountry(); ?></span>
                                    </fieldset>
                            </td>

                        </tr>
                        <tr>
                            <td style="border:0">
                                <fieldset>
                                    <legend>���� �����</legend>
                                <span class="value"><?php echo $C->date($Man->testing_date); ?></span>
                                    </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:0">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border:0">
                                <fieldset>
                                    <legend>��������</legend>
                                    <span class="value"><?php echo $C->date($Man->birth_date); ?></span>
                                    <span class="value"><?php echo $Man->birth_place; ?></span>

                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:0">
                                <fieldset>
                                    <legend>������������ �����</legend>
                                    <span class="value"><?php echo $Man->migration_card_series; ?></span>
                                    <span class="value"><?php echo $Man->migration_card_number; ?></span>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
                <td><span class="value"><?php echo $Man->reading; ?></span><br>
                    <span class="percent"><?php echo $Man->reading_percent; ?>%</span>
                </td>
                <td><span class="value"><?php echo $Man->writing; ?></span><br>
                    <span class="percent"><?php echo $Man->writing_percent; ?>%</span>
                </td>
                <td><span class="value"><?php echo $Man->grammar; ?></span><br>
                    <span class="percent"><?php echo $Man->grammar_percent; ?>%</span>
                </td>
                <td><span class="value"><?php echo $Man->listening; ?></span><br>
                    <span class="percent"><?php echo $Man->listening_percent; ?>%</span>
                </td>
                <td><span class="value"><?php echo $Man->speaking; ?></span><br>
                    <span class="percent"><?php echo $Man->speaking_percent; ?>%</span>
                </td>
                <td><span class="value"><?php echo $Man->total; ?></span><br>
                    <span class="percent"><?php echo $Man->total_percent; ?>%</span>
                </td>
                <td>  <span class="<?php echo $Man->document; ?> ">
                        <?php echo $Man->document == "certificate" ? '����������' : '�������' ?>
                        </span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    <?php endforeach; ?>
</table>



