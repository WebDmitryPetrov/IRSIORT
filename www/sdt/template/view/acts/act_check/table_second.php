<?php
/** @var Act $Act */
$i = 0;
$is_free_text = '<span style="color:red">���������� ���������</span><br>';
?>

<h3>������� �������. ���� ������������: <?php echo $C->date($Act->testing_date); ?></h3>
<h4>��������� �����: <?php echo $Act->getUniversity(); ?></h4>
<h4>�������: <?php echo $Act->getUniversityDogovor(); ?></h4>
<a href="/sdt/index.php?action=act_second_view&id=<?php echo $Act->id ?>" class="btn btn-info">��������� � ���</a>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>"
      class="form-horizontal marks">
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
        <div style="margin-bottom: 2px">
            <input disabled="disabled" value="<?php echo $Act->tester1 ?>" style="width:400px"
                   placeholder="��� �������"
            ></div>
        <div><input disabled="disabled" value="<?php echo $Act->tester2 ?>" placeholder="��� �������"
                    style="width:400px"></div>
    </fieldset>

    <?php foreach ($Act->getLevels() as $ActTest):
        $level = $ActTest->getLevel();
        $sub_tests = $level->getSubTests();;

        $sub_tests_count = count($sub_tests);
        ?>
        <table class="table table-bordered  table-condensed summary_table">
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
                    <td class="center"><?= $subTest->short_caption ?><span
                                class="percent"><?= $subTest->max_ball ?></span></td>
                <? endforeach ?>
                <td class="center">���<span class="percent"><?php echo $level->total; ?></span></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($ActTest->getPeople() as $Man):
                $disabled = ' ';
                $retry_disabled = ' ';
                if ($Man->is_retry) {
                    $disabled = ' ';
                    $retry_disabled = 'disabled="disabled"';
                }

                /** @var ActMan $Man */
                ?>
                <tr class=" summary <?php if ($Man->is_retry): ?> man-retry <?php endif ?>  <?php if ($Man->id % 2): ?> info <?php endif ?>">
                    <td class="npp"><?= ++$i ?></td>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td colspan="2">

                                    <? if (!empty($Man->is_retry)): ?>

                                        <? if ($Man->is_retry != $Man::RETRY_ALL): ?>
                                            <b>��������� ���������: <?= $Man->is_retry; ?></b>
                                        <? endif ?>
                                        <? if ($Man->is_retry == $Man::RETRY_ALL): ?>
                                            <b style="color:red">���������� ���������</b>
                                        <? endif ?>
                                        <? if ($Man->getReExam()): ?>
                                            <br>����� ������������� �������:<br>


                                            <?= $Man->getReExam()->document_number; ?>
                                            <br><br>
                                        <? endif ?>
                                    <? endif ?>
                                    <fieldset>
                                        <legend>���</legend>

                                        <input type="text"
                                               name="user[<?php echo $Man->id; ?>][surname_rus]"
                                               class="input-large only-rus" placeholder="������� ��-������"
                                            <?php echo $disabled;

                                            echo $retry_disabled;
                                            ?>
                                               value="<?php echo $Man->surname_rus; ?>"><br>

                                        <input type="text"
                                               name="user[<?php echo $Man->id; ?>][name_rus]" class="input-large  only-rus"
                                            <?php echo $disabled;

                                            echo $retry_disabled;
                                            ?>
                                               placeholder="��� ��-������" value="<?php echo $Man->name_rus; ?>">
                                        <br>

                                        <input type="text" name="user[<?php echo $Man->id; ?>][surname_lat]"
                                               class="input-large  only-lat" placeholder="������� ���������"
                                            <?php echo $disabled;

                                            echo $retry_disabled;
                                            ?>
                                               value="<?php echo $Man->surname_lat; ?>">

                                        <br>
                                        <input type="text" name="user[<?php echo $Man->id; ?>][name_lat]"
                                               class="input-large  only-lat" placeholder="��� ���������"
                                            <?php echo $disabled;

                                            echo $retry_disabled;
                                            ?>
                                               value="<?php echo $Man->name_lat; ?>">
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <fieldset>
                                        <legend>�������� �������������� ��������</legend>

                                        <input placeholder="������������" type="text"
                                               name="user[<?php echo $Man->id; ?>][passport_name]"
                                            <?php echo $disabled;
                                            echo $retry_disabled;
                                            ?>
                                               class="input-large" value="<?php echo $Man->passport_name; ?>">

                                        <div>
                                            <input placeholder="����� " type="text"
                                                   name="user[<?php echo $Man->id; ?>][passport_series]"
                                                <?php echo $disabled;
                                                echo $retry_disabled;
                                                ?>

                                                   class="input-mini" value="<?php echo $Man->passport_series; ?>">

                                            <div class="input-append">
                                                <input type="text" name="user[<?php echo $Man->id; ?>][passport]"
                                                    <?php echo $disabled;
                                                    echo $retry_disabled;
                                                    ?>

                                                       class="input-small" value="<?php echo $Man->passport; ?>"
                                                       placeholder="�����"><!-- <span class="add-on">
                                                   <a href="#"
                                                                                                   class="passport_upload custom-icon-upload"
                                                                                                   data-id="<?php /*echo $Man->id; */
                                                ?>"></a>
                                                </span>-->

                                            </div>

                                        </div>
                                        <div class="input-prepend date datepicker "
                                            <?php echo $disabled;
                                            echo $retry_disabled;
                                            ?>

                                             data-date="<?php if ($Man->passport_date != '0000-00-00') {
                                                 echo $C->date(
                                                     $Man->passport_date
                                                 );
                                             } ?>"
                                        >
                                            <span class="add-on"><i class="icon-th"></i> </span> <input
                                                    placeholder="���� ������"
                                                    class="input-small"
                                                    name="user[<?php echo $Man->id; ?>][passport_date]"
                                                <?php echo $disabled;
                                                echo $retry_disabled;
                                                ?>

                                                    id="passport_date" readonly="readonly" size="16" type="text"
                                                    value="<?php if ($Man->passport_date != '0000-00-00') {
                                                        echo $C->date(
                                                            $Man->passport_date
                                                        );
                                                    } ?>">
                                        </div>
                                        <input placeholder="����� �������� " type="text"
                                               name="user[<?php echo $Man->id; ?>][passport_department]"
                                            <?php echo $disabled;
                                            echo $retry_disabled;
                                            ?>

                                               class="input-large" value="<?php echo $Man->passport_department; ?>">
                                        <?php if ($Man->getFilePassport()): ?>
                                            <a href="<?php echo $Man->getFilePassport()->getDownloadUrl() ?>"
                                               target="_blank"
                                               class="btn  btn-small btn-success"><span
                                                        class="custom-icon-download"></span>�������</a>
                                        <?php endif; ?>


                                        <?php if ($uploadedPhoto = \SDT\models\PeopleStorage\ManFile::getByUserType($Man->id, \SDT\models\PeopleStorage\ManFile::TYPE_PHOTO)): ?>
                                            <a href="<?php echo $uploadedPhoto->getDownloadUrl() ?>"
                                               target="_blank"
                                               class="btn  btn-small btn-primary"> ����������</a>
                                        <?php endif; ?>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2">
                        <table border="0" style="border:0">
                            <tr>
                                <td style="border:0">
                                    <fieldset>
                                        <legend>������ (�����������)</legend>
                                        <select name="user[<?php echo $Man->id; ?>][country_id]"
                                            <?php echo $disabled;
                                            echo $retry_disabled;
                                            ?>

                                                class="input-large" style="width: 100%">
                                            <?php foreach ($Countries as $country): ?>
                                                <option value="<?php echo $country->id; ?>"
                                                    <?php if ($country->id == $Man->country_id) {
                                                        echo 'selected=selected';
                                                    } ?>>
                                                    <?php echo $country->name; ?>
                                                </option>

                                            <?php endforeach; ?>
                                        </select>
                                    </fieldset>
                                </td>

                            </tr>

                            <tr>
                                <td style="border:0">
                                    <fieldset>
                                        <legend>���� �����</legend>
                                        <div class="input-prepend date datepicker "
                                             data-date="<?php if ($Man->testing_date != '0000-00-00') {
                                                 echo $C->date($Man->testing_date);
                                             } else {
                                                 echo $C->date($Act->testing_date);
                                             } ?>"
                                        >
                                            <span class="add-on"><i class="icon-th"></i> </span> <input
                                                    class="input-small"
                                                    name="user[<?php echo $Man->id; ?>][testing_date]"
                                                    id="testing_date" readonly="readonly" size="16" type="text"
                                                    value="<?php if ($Man->testing_date != '0000-00-00') {
                                                        echo $C->date($Man->testing_date);
                                                    } else {
                                                        echo $C->date($Act->testing_date);
                                                    } ?>">
                                        </div>

                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td style="border:0">
                                    <fieldset>
                                        <legend>���� ��������</legend>
                                        <div class="input-prepend date datepicker "
                                            <?php echo $disabled;
                                            echo $retry_disabled;
                                            ?>

                                             data-date="<?php if ($Man->birth_date != '0000-00-00') {
                                                 echo $C->date($Man->birth_date);
                                             } ?>"
                                        >
                                            <span class="add-on"><i class="icon-th"></i> </span> <input
                                                    class="input-small"
                                                <?php echo $disabled;
                                                echo $retry_disabled;
                                                ?>

                                                    name="user[<?php echo $Man->id; ?>][birth_date]"
                                                    placeholder="����"
                                                    id="birth_date" readonly="readonly" size="16" type="text"
                                                    value="<?php if ($Man->birth_date != '0000-00-00') {
                                                        echo $C->date($Man->birth_date);
                                                    } ?>">
                                        </div>
                                        <input placeholder="����� ��������" type="text"
                                            <?php echo $disabled;
                                            echo $retry_disabled;
                                            ?>

                                               name="user[<?php echo $Man->id; ?>][birth_place]"
                                               class="input-large" value="<?php echo $Man->birth_place; ?>">

                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td style="border:0">
                                    <fieldset>
                                        <legend>������������ �����</legend>
                                        <input placeholder="�����" type="text"
                                            <?php echo $disabled;
                                            echo $retry_disabled;
                                            ?>

                                               name="user[<?php echo $Man->id; ?>][migration_card_series]"
                                               class="input-mini" value="<?php echo $Man->migration_card_series; ?>">
                                        <input placeholder="�����" type="text"
                                            <?php echo $disabled;
                                            echo $retry_disabled;
                                            ?>

                                               name="user[<?php echo $Man->id; ?>][migration_card_number]"
                                               class="input-small" value="<?php echo $Man->migration_card_number; ?>">
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </td>


                    <? $ManResults = $Man->getResults();
                    foreach ($ManResults as $result): ?>
                        <td>
                            <input type="text" maxlength="5"
                                   name="user[<?php echo $Man->id; ?>][test_<?= $result->order ?>_ball]"
                                   class="scores"
                                   value="<?php echo $result->balls; ?>">
                            <span class="percent"><?php echo $result->percent; ?>%</span>
                        </td>

                    <? endforeach; ?>

                    <td><input type="text" maxlength="5"
                               name="user[<?php echo $Man->id; ?>][total]" class="scores"
                               value="<?php echo $Man->total; ?>" readonly="readonly">
                        <span class="percent"><?php echo $Man->total_percent; ?>%</span>
                    </td>

                    <td>  <span class="<?php echo $Man->document; ?> ">
                        <?php echo $Man->document == "certificate" ? '����������' : '�������' ?>
                        </span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>


    <div class="form-actions">
        <button class="btn btn-success" type="submit">���������</button>

    </div>
</form>


<div class="modal hide fade" id="passport_upload" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" enctype="multipart/form-data"
          action="index.php?action=man_passport_upload" class="form-inline">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;
            </button>
            <h3 id="myModalLabel">��������� ��������������� ������� � ������������ ����� � ����� �����</h3>
        </div>
        <div class="modal-body">

              <span class="help-block">����� ��������� ����� ��������� ��������� ������!  <br>
(��� ����� �������� "�������" � ����� ����� "���������")
</span>
            <legend>�������� ����</legend>

            <input type="hidden" value="" name="man_id" class="man_id"> <input
                    type="file" name="file" class="input-xlarge">

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">�������</button>
            <button class="btn btn-primary save" type="submit">���������</button>
        </div>
    </form>
</div>
<script>
    $(function () {

        $('.passport_upload').on('click', function (e) {
            e.preventDefault();
            //alert($(this).data('id'));
            $('#passport_upload').find('.man_id').val($(this).data('id'));
            $('#passport_upload').modal();
        });
    });

</script>