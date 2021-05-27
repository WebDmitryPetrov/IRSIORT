<? /** @var Act $object */
$show_free = Reexam_config::isShowInAct($object->test_level_type_id);
?>
<h1>�������� ������</h1>
<table class="table table-bordered  table-striped">
    <tr>
        <th>�����</th>
        <td><?php echo $object->number ?>
        </td>
    </tr>

    <tr>
        <th>���</th>
        <td><?php echo $object->getUniversity()->name ?>
        </td>
    </tr>
    <tr>
        <th>������� � �����</th>
        <td><?php echo $object->getUniversityDogovor() ?>
        </td>
    </tr>
    <tr>
        <th>������������ ���</th>
        <td><?php echo $object->official ?>
        </td>
    </tr>
    <tr>
        <th>������������� �� ���������� �����������</th>
        <td><?php echo $object->responsible ?>
        </td>
    </tr>
    <tr>
        <th>���� ������������</th>
        <td><?php echo $C->date($object->testing_date) ?>
        </td>
    </tr>
    <!--<tr>
        <th>����� ���������</th>
        <td><?php echo $object->total_revenue ?>
        </td>
    </tr>-->

    <tr>
        <th>������ �� ��������� ������ <?= TEXT_HEADCENTER_SHORT_IP ?></th>
        <td><?php echo $object->amount_contributions ?>
        </td>
    </tr>
    <tr>
        <th>�����������</th>
        <td class="text-error"><?php echo $object->comment ?>
        </td>
    </tr>
    <tr>
        <th>��������</th>
        <td><?php echo $object->paid ? '��' : '���' ?>
        </td>
    </tr>


    <tr>
        <th>������������</th>
        <td>
            <? if (!$C->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API) && $inWorkVoter->isPeopleEditGranted($object)): ?>
                <a class="btn btn-primary"
                   href="index.php?action=act_test_add&id=<?php echo $object->id; ?>">�������� ������������</a>
            <? endif ?>

            <? /*
            <table class="table-bordered  table-striped">
                <thead>
                <tr>
                    <th rowspan="2">������� ������������</th>
                    <th colspan="2">������������</th>
                    <th colspan=" <?if ($object->test_level_type_id==2 ||$object->test_level_type_id==3  ):?>4<?else:?>3<?endif?>">���������</th>
                    <th rowspan="2">&nbsp;</th>
                </tr>
                <tr>
                    <th>�������</th>
                    <th>��������� ������������</th>
                    <th>�������</th>
                    <th>�� ������ ��������</th>
                    <?if ($object->test_level_type_id==2 ||$object->test_level_type_id==3  ):?>
                        <th>�� ���� ���������</th>
                    <?endif;?>
                    <th>��������� �������� ������������</th>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($object->getTests() as $test):

                    ?>
                    <tr>

                        <td><?php  echo $test->getLevel()?>
                        </td>
                        <td><?php  echo $test->people_first;?>
                        </td>
                        <td><?php  echo $test->getPrice()*$test->people_first; ?>
                        </td>
                        <td><?php  echo $test->people_retry;?>
                        </td>
                        <td><?php  echo $test->people_subtest_retry;?></td>
                        <?if ($object->test_level_type_id==2 ||$object->test_level_type_id==3  ):?>
                            <td><?php  echo $test->people_subtest_2_retry;?></td>
                        <?endif;?>
                        <td><?php  echo
                                $test->getPriceSubTest()*$test->people_subtest_retry + $test->getPriceSubTest2()*$test->people_subtest_2_retry; ?>
                        </td>
                        <td>
                            <? if (!$C->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)): ?>
                                <a class="btn btn-small btn-warning btn-block" onclick="return confirm('�� �������?');"
                                   href="index.php?action=act_test_delete&id=<?php echo $test->id; ?>">�������</a>
                            <? endif?>
                        </td>

                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>*/
            if (!$C->userHasRole(Roles::ROLE_CENTER_EXTERNAL_API)  && $inWorkVoter->isPeopleEditGranted($object)) {
                $template_buttons = 'acts/init/people_remove_buttons';
            } else
                $template_buttons = '';
            echo $this->import('acts/act_table_template', array('object' => $object, 'show_free' => $show_free, 'template_buttons' => $template_buttons));
            ?>






            <?php if ($object->getTests() and count($object->getTests())): ?>
                <a class="btn btn-info btn-small"
                   href="index.php?action=act_table&id=<?php echo $object->id; ?>">��������� ������� �������</a>
            <? endif; ?>
        </td>

    </tr>


</table>
