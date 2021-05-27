<?php
/** @var University $object */
?>
<a class="btn btn-danger" href="index.php?action=university_children&id=<?php echo $object->id; ?>">����������
    ��������� ������</a>


<hr>
<a target="_blank" class="btn" href="index.php?action=university_print_simple&id=<?php echo $object->id; ?>">�������
    �����</a>
<a target="_blank" class="btn" href="index.php?action=university_print_full&id=<?php echo $object->id; ?>">������
    �����</a>
<a target="_blank" class="btn" href="index.php?action=university_print_full_dogovor&id=<?php echo $object->id; ?>">������
    ����� � ����������</a>
<div class="gap"></div>
<br>
<a target="_blank" class="btn"
   href="index.php?action=university_view_dogovor&id=<?php echo $object->id; ?>">��������</a>
<a class="btn btn-warning"
   href="index.php?action=university_user_right&id=<?php echo $object->id; ?>">�����</a>

<a class="btn btn-warning"
   href="index.php?action=university_edit&id=<?php echo $object->id; ?>">�������������</a>

<? if ($haveChildren): ?>
    <a href="#"
       onclick="alert('������� ������� ��� ����������� ������');" class="btn btn-danger disabled ">
        �������
    </a>

<? else: ?>
    <a href="index.php?action=university_delete&id=<?php echo $object->id; ?>"
       onclick="return confirm('�� �������, ��� ������ ������� �����?\n' +
        '��� �������� ���������� ������ �� ������� �� ����� ������ �� ���� ������� ������� �������, ����� ����, ����� ������� ��� ��������� � ��� �������� ������ �� ����������� ������, ����������� � ������!');"
       class="btn btn-danger ">
        �������
    </a>
<? endif ?>
<?php if (!empty($_GET['pwd'])): ?>
    <p style="font-weight: bold; color: red; font-size: 18px">�������� ����� � ������ ��� ������� ������
        ������� ���������� ������</p>


<?php endif; ?>
<br>
<br>

<table class="table table-bordered  table-striped">

    <tr>
        <th>���������� ��������� ������</th>
        <td><?= $haveChildren ? '�����' : '�� �����' ?></td>
    </tr>
    <tr>
        <th>�����</th>
        <td><?= $object->user_login; ?></td>
    </tr>
    <?php if (!empty($_GET['pwd'])): ?>
        <tr>
            <th>������</th>
            <td style="font-weight: bold; color: red; font-size: 18px"><?= $_GET['pwd']; ?></td>
        </tr>
    <?php endif; ?>

    <?php foreach ($object->getEditFields() as $field):
    if ($field != 'country_id' && $field != 'region_id' && $field != 'is_old_act' && !($field == 'is_price_change' || $field == 'is_head' || $field == 'print_invoice_quoute')):
        ?>
        <tr>
            <th><?php echo $object->getTranslate($field); ?>
            </th>
            <td><?php echo $object->$field ?>
            </td>
        </tr>
        <?php
    endif;
    if ($field == 'country_id'):
    ?>
    <tr>
        <th><?php echo $object->getTranslate($field); ?>
        </th>
        <td><?= $object->country_id ? $object->getCountry()->name : '' ?>
        </td>
        <?php
        endif;

        if ($field == 'region_id'):
        ?>
    <tr>
        <th><?php echo $object->getTranslate($field); ?>
        </th>
        <td><?= $object->region_id ? $object->getRegion()->caption : '' ?>
        </td>
        <?php
        endif;

        if ($field == 'is_price_change' || $field == 'is_head' || $field == 'print_invoice_quoute' || $field == 'is_old_act'):
        ?>
    <tr>
        <th><?php echo $object->getTranslate($field); ?>
        </th>
        <td><?= $object->$field ? '��' : '���' ?>
        </td>
        <?php
        endif;


        endforeach; ?>
    <tr>
        <th>��������</th>
        <td>
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                <tr>
                    <th>�����</th>
                    <th>����</th>
                    <th>��������</th>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($object->getDogovors() as $dogovor): ?>
                    <tr>
                        <th><?= $dogovor->number; ?></th>
                        <td><?= $C->date($dogovor->date); ?></td>
                        <td><?= $dogovor->caption; ?></td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </td>

    </tr>

    <tr>
        <th>������������ ���</th>
        <td>
            <? $signs = CenterSignings::getCenterAndType($object->id, CenterSigning::TYPE_APPROVE); ?>
            <a
                    href="index.php?action=center_signing_add&id=<?php echo $object->id; ?>&type=<?= CenterSigning::TYPE_APPROVE ?>">
                ��������</a>

            <? if ($signs && count($signs)): ?>
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>���������</th>
                        <th>���</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($signs as $item): ?>
                        <tr>
                            <td><?= $item->position; ?></td>

                            <td><?= $item->caption; ?></td>
                            <td>
                                <a href="index.php?action=center_signing_edit&id=<?php echo $item->id; ?>&type=<?= CenterSigning::TYPE_APPROVE ?>" ><i class="icon-pencil"></i></a>
                                <a href="index.php?action=center_signing_delete&id=<?php echo $item->id; ?>&type=<?= CenterSigning::TYPE_APPROVE ?>" onclick="return confirm('�� �������, ��� ������ �������?');"><i class="icon-remove"></i></a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <? else: ?>


            <? endif; ?>
        </td>

    </tr>

 <tr>
        <th>������������� �� ������������</th>
        <td>
            <? $signs = CenterSignings::getCenterAndType($object->id, CenterSigning::TYPE_RESPONSIVE); ?>
            <a
                    href="index.php?action=center_signing_add&id=<?php echo $object->id; ?>&type=<?= CenterSigning::TYPE_RESPONSIVE ?>">
                ��������</a>

            <? if ($signs && count($signs)): ?>
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>���������</th>
                        <th>���</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($signs as $item): ?>
                        <tr>
                            <td><?= $item->position; ?></td>

                            <td><?= $item->caption; ?></td>
                            <td>
                                <a href="index.php?action=center_signing_edit&id=<?php echo $item->id; ?>&type=<?= CenterSigning::TYPE_RESPONSIVE ?>" ><i class="icon-pencil"></i></a>
                                <a href="index.php?action=center_signing_delete&id=<?php echo $item->id; ?>&type=<?= CenterSigning::TYPE_RESPONSIVE ?>" onclick="return confirm('�� �������, ��� ������ �������?');"><i class="icon-remove"></i></a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <? else: ?>


            <? endif; ?>
        </td>

    </tr>

</table>
