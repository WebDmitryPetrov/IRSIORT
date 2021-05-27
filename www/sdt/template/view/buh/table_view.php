<?php
/** @var Act $Act */
$i = 0;
$is_free_text ='<span style="color:red">���������� ���������</span><br>';
?>
<style>
    .duplicate {
        color: red;
        font-weight: normal;
    }

    .duplicate_header {
        font-style: italic;

    }
</style>
<h3>������� �������. ���� ������������: <?php echo $C->date($Act->testing_date); ?></h3>
<h4>��������� �����: <?php echo $Act->getUniversity()->getLegalInfo()['name_parent']; ?></h4>
<h4>�������: <?php echo $Act->getUniversityDogovor(); ?></h4>
<a href="/sdt/index.php?action=buh_view_act&id=<?php echo $Act->id ?>" class="btn btn-info">��������� � ���</a>


<?php if (!empty($Legend)): ?>
    <legend>
        <?php echo $Legend; ?>
    </legend>
<?php endif; ?>
<fieldset>
    <legend>�������</legend>
    <div style="margin-bottom: 2px"><?php echo $Act->tester1 ?></div>
    <div><?php echo $Act->tester2 ?></div>
</fieldset>

<?php foreach ($Act->getLevels() as $ActTest):

    $level = $ActTest->getLevel();
    $sub_tests = $level->getSubTests();;

    $sub_tests_count = count($sub_tests);
    ?>
    <table
        class="table table-bordered  table-condensed summary_table">
        <thead>

        <tr>
            <td colspan="<?= $sub_tests_count + 7 ?>"><h4>������� ������������
                    "<strong><?php echo $level->caption ?></strong>"</h4></td>
        </tr>
        <tr>
            <td rowspan="2"><strong>�
                    <nobr>�/�</nobr>
                </strong></td>
            <td rowspan="2" colspan="4"><strong>�������� � �����������</strong></td>


            <td colspan="<?= $sub_tests_count + 1 ?>" class="center"><strong>����������</strong> (����� /
                %)
            </td>
            <td rowspan="2" class="center"><strong>����</strong>
            </td>
        </tr>
        <tr>
            <? foreach ($sub_tests as $subTest): ?>
                <td class="center"><?= $subTest->short_caption ?></td>
            <? endforeach ?>
            <td class="center">���</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ActTest->getPeople() as $Man):
            /** @var ActMan $Man */
            $Man = CertificateDuplicate::checkForDuplicates($Man);
            ?>
            <tr class=" summary <?php if ($Man->is_retry): ?> man-retry <?php endif ?> <?php if ($Man->id % 2): ?> info <?php endif ?>">
                <td class="npp"><?= ++$i ?></td>
                <td colspan="2">
                    <table>
                        <tr>
                            <td colspan="2">
                                <? if ($Man->isFreeRetry()) echo $is_free_text; ?>
                                <fieldset>
                                    <legend>���</legend>

                                    <span class="value"><?php echo $Man->surname_rus; ?></span><br>

                                    <span class="value"><?php echo $Man->name_rus; ?></span>
                                    <br>

                                    <span class="value"><?php echo $Man->surname_lat; ?></span>

                                    <br>
                                    <span class="value"><?php echo $Man->name_lat; ?></span>

                                    <? if (!empty($Man->duplicate->personal_data_changed)): ?>

                                        <div class="duplicate"><span
                                                class="duplicate_header">����� �������� �� ���:</span><br>
                                            <span class="value"><?php echo $Man->getSurname_rus(); ?></span><br>

                                            <span class="value"><?php echo $Man->getName_rus(); ?></span>
                                            <br>

                                            <span class="value"><?php echo $Man->getSurname_lat(); ?></span>

                                            <br>
                                            <span class="value"><?php echo $Man->getName_lat(); ?></span>
                                        </div>
                                    <? endif; ?>

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

                                    <?php if (!empty($Man->duplicate->personal_data_changed) && $Man->getDuplicateFilePassport()): ?>
                                        <a href="<?php echo $Man->getDuplicateFilePassport()->getDownloadUrl() ?>"
                                           target="_blank"
                                           class="btn  btn-small btn-success"><span
                                                class="custom-icon-download"></span>������� �� ������ ���</a>
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
                                <fieldset>
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
                <? $ManResults = $Man->getResults();
                foreach ($ManResults as $result): ?>
                    <td><span class="value"><?php echo $result->balls; ?></span><br>
                        <span class="percent"><?php echo $result->percent; ?>%</span>
                    </td>


                <? endforeach; ?>


                <td><span class="value"><?php echo $Man->total; ?></span><br>
                    <span class="percent"><?php echo $Man->total_percent; ?>%</span>
                </td>
                <td>  <span class="<?php echo $Man->document; ?> ">
                        <?php //echo $Man->document == "certificate" ? '����������' : '�������'
                        ?>
                        <?php
                        if ($Man->document == "certificate") {

                            echo '����������';
                            if ($Man->duplicate->certificate_number) {
                                echo '<div class="duplicate"><span class="duplicate_header">�����<br>������<br>���������:</span><br>' . $Man->getBlank_number() . '</div>';
                            }


                        } else echo '�������'
                        ?>
                        </span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>


