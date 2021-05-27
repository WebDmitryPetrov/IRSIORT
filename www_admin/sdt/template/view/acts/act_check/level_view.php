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
                    $prices=ChangedPriceTestLevel::checkPrice($object->id);
                    ?>
                <tr>

                    <td><?php  echo $test->getLevel()?>
                    </td>
                    <td><?php  echo $test->people_first;?>
                    </td>
                    <td><?php  /*echo $test->getLevel()->price*$test->people_first;*/echo $prices->price*$test->people_first; ?>
                    </td>
                    <td><?php  echo $test->people_retry;?>
                    </td>
                    <td><?php  echo $test->people_subtest_retry;?>
                    </td>
                    <td><?php  echo  /*$test->getLevel()->sub_test_price*$test->people_subtest_retry;*/$prices->sub_test_price*$test->people_subtest_retry; ?>
                    </td>

                </tr>
                    <?php endforeach;?>
                </tbody>
            </table>

            <a class="btn btn-info btn-small"
               href="index.php?action=act_table_second&id=<?php echo $object->id; ?>">��������� ������� �������</a>
        </td>
    </tr>


</table>