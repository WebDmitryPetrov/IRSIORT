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
    <!--
    <tr>
        <th>����� ���������</th>
        <td><?php echo $object->total_revenue ?>
        </td>
    </tr>
    -->


    <tr>
        <th>������ �� ��������� ������ <?=TEXT_HEADCENTER_SHORT_IP?></th>
        <td><?php echo $object->amount_contributions ?>
        </td>
    </tr>
    <tr>
        <th>�����������</th>
        <td><?php echo $object->comment ?>
        </td>
    </tr>
    <tr>
        <th>��������</th>
        <td><?php echo $object->paid ? '��' : '���' ?>
        </td>
    </tr>

    <tr>
        <th>����������� �����</th>
        <td>






            <? if (!empty($object->summary_table_id)):?>
                <a class="btn btn-danger  btn-color-black" target="_blank" id="summary_table_<?=$object->id?>"
                   href="index.php?action=act_summary_table&id=<?php echo $object->id; ?>">�����������/���������� ������� ��������</a>
                <div></div>
            <? endif;?>
            <? if (!empty($object->isActPrinted()) && !$object->file_act_id):?>
                <a class="btn btn-danger  btn-color-black" target="_blank" id="act_print_<?=$object->id?>"
                   href="index.php?action=act_print_view&id=<?php echo $object->id; ?>">�����������/���������� ���</a>
                <div></div>
            <? endif;?>
            <? if (!empty($object->isActTablePrinted()) && !$object->file_act_tabl_id):?>
            <a class="btn btn-danger  btn-color-black" target="_blank" id="act_table_print_<?=$object->id?>"
               href="index.php?action=act_table_print_view&id=<?php echo $object->id; ?>">�����������/���������� ������� �������</a>
            <div></div>
            <? endif;?>









            <?php  $fileact = $object->getFileAct();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">���������������
                        ���</a>
                </div> <?php endif;?> <?php  $fileact = $object->getFileActTabl();
            if ($fileact): ?>
                <div>
                    <a href="<?php echo $fileact->getDownloadURL(); ?>">���������������
                        ������� �������</a>
                </div> <?php endif;?>
        </td>
    </tr>

    <tr>
        <th>������������</th>
        <td>
            <table class="table-bordered  table-striped">
                <thead>
                <tr>
                    <th rowspan="2">������� ������������</th>
                    <th colspan="2">������������</th>
                    <th colspan="3">���������</th>

                </tr>
                <tr>
                    <th>�������</th>
                    <th>��������� ������������</th>
                    <th>�������</th>
                    <th>���������</th>
                    <th>��������� �������� ������������</th>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($object->getTests() as $test):
                    /** @var ActTest $test */
                    //$prices=ChangedPriceTestLevel::checkPrice($object->id);
                    ?>
                <tr>

                    <td><?php  echo $test->getLevel()?>
                    </td>
                    <td><?php  echo $test->people_first;?>
                    </td>
                    <td><?php  /*echo $test->getLevel()->price*$test->people_first;*/echo $test->getPrice()*$test->people_first; ?>
                    </td>
                    <td><?php  echo $test->people_retry;?>
                    </td>
                    <td><?php  echo $test->people_subtest_retry;?>
                    </td>
                    <td><?php  echo  /*$test->getLevel()->sub_test_price*$test->people_subtest_retry;*/$test->getPriceSubTest()*$test->people_subtest_retry; ?>
                    </td>

                </tr>
                    <?php endforeach;?>
                </tbody>
            </table>

            <a class="btn btn-info btn-small"
               href="index.php?action=act_received_table_view&id=<?php echo $object->id; ?>">����������� ������� �������</a></td>
    </tr>


</table>


