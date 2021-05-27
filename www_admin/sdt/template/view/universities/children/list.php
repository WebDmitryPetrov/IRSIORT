<?php
/** @var University $object */
?>
    <h1>������ ���������� ��������� ������� �������:<br> <?= $object->name ?></h1>


    <a href="index.php?action=university_view&id=<?php echo $object->id; ?>">��������� � �������� ���������� ������</a>
    <hr>
    <a target="_blank" class="btn btn-default"
       href="index.php?action=university_child_add&parent_id=<?php echo $object->id; ?>">
        �������� ����������
        ��������� �����</a><br><br>

<? if (!count($children)): ?>
    <h3 class="text-error">���������� ��������� ������ �����������</h3>
<? else: ?>
    <table class="table table-bordered  table-striped">
        <? foreach ($children as $child): ?>


            <tr>
                <th><?= $child->name;

                ?></th>
                <td style="width: 200px">
                    <a class="btn btn-info btn-small btn-block"
                       href="index.php?action=university_child_view&id=<?php echo $child->id; ?>">��������</a>
                    <a class="btn btn-warning btn-small btn-block"
                       href="index.php?action=university_child_edit&id=<?php echo $child->id; ?>">�������������</a>



                    <a href="index.php?action=university_child_delete&id=<?php echo $child->id; ?>"
                       onclick="return confirm('�� �������, ��� ������ ������� �����?\n' +
        '��� �������� ���������� ������ �� ������� �� ����� ������ �� ���� ������� ������� �������, ����� ����, ����� ������� ��� ��������� � ��� �������� ������ �� ����������� ������, ����������� � ������!');" class="btn btn-danger btn-block btn-small">�������</a>
                </td>
            </tr>

        <? endforeach; ?>
    </table>

<? endif ?>