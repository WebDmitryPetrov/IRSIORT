<a class="btn btn-warning btn-small"
   href="index.php?action=act_numbers&id=<?php echo $object->id; ?>">������
    ����������</a> <a class="btn btn-info btn-small" target="_blank"
                      href="index.php?action=act_vidacha_cert&id=<?php echo $object->id; ?>">���������
    ������ ������������</a>

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
        <th>���� ������������</th>
        <td><?php echo $C->date($object->testing_date) ?>
        </td>
    </tr>
    <tr>
        <th>����� ���������</th>
        <td><?php echo $object->total_revenue ?>
        </td>
    </tr>
    <!--<tr>
        <th>������� ���������� � ����� <?=TEXT_HEADCENTER_SHORT_IP?></th>
        <td><?php echo $object->rate_of_contributions ?>
        </td>
    </tr>-->
    <tr>
        <th>�������</th>
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
        <td><?php  $fileact = $object->getFileAct();
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
        <th>���������� ����������������<br>(������������/�������)</th>
        <td>
            <?php
            $people = $object->getPeople();
            echo count($people);
            $count_cert = 0;
            $count_sprav = 0;
            foreach ($people as $man) {
                if($man->document=='certificate') {
                      $count_cert++;
                }
                else{
                    $count_sprav++;
                }


            }

            echo ' ('.$count_cert.'/'.$count_sprav.')';
            ?>

        </td>
    </tr>
    <tr>
        <th>������������</th>
        <td>
            <table class="table table-bordered  table-striped">
                <tr>
                    <th>������� ������������</th>
                    <th>���������� �����</th>
                    <th>���������</th>
                    <th>&nbsp;</th>
                </tr>
                <?php foreach ($object->getTests() as $test): ?>
                <tr>
                    <td><?php  echo $test->getLevel()?>
                    </td>
                    <td><?php  echo $test->people_count;?>
                    </td>
                    <td><?php  echo $test->money; ?>
                    </td>
                    <td><a class="btn btn-info btn-small"
                           href="index.php?action=act_vedomost&id=<?php echo $test->id; ?>">���������</a>
                    </td>
                    <!--  <td><a class="btn btn-warning btn-small"
						href="index.php?action=act_test_edit&id=<?php echo $test->id; ?>">�������������</a>
					</td>-->
                </tr>
                <?php endforeach;?>

            </table>
        </td>
    </tr>


</table>
