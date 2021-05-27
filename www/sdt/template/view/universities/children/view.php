<?php
/** @var University $object */
?>

    <a class=""
       href="index.php?action=university_children&id=<?php echo $object->parent_id; ?>">��������� � ������</a>
    <h1>���������� �����: <?= $object->name ?></h1>
    <a class="btn btn-danger"
       href="index.php?action=university_user_right&id=<?php echo $object->id; ?>">�����</a>

    <a class="btn btn-danger"
       href="index.php?action=university_child_edit&id=<?php echo $object->id; ?>">�������������</a>

<?php if (!empty($_GET['pwd'])): ?>
    <p style="font-weight: bold; color: red; font-size: 18px">�������� ����� � ������ ��� ������� ������
        ������� ���������� ������</p>


<?php endif; ?>
    <br>
    <br>

    <table class="table table-bordered  table-striped">

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
            if (in_array($field, ['is_old_act', 'is_price_change', 'is_head', 'print_invoice_quoute']))
                continue;

            if ($field != 'country_id' && $field != 'region_id' && $field != 'is_old_act' && !($field == 'is_price_change' || $field == 'is_head' || $field == 'print_invoice_quoute')):
                ?>
                <tr class="<?= $object->isParentedField($field) ? 'info' : '' ?>">
                    <th>
                        <?
                        if ($field == 'legal_address'):?>
                     �����
                        <?
                        else:?>
                            <?php echo $object->getTranslate($field); ?>
                        <?endif ?>
                    </th>
                    <td><?php echo $object->getParentedFieldValue($field) ?>
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
                </tr>
                <?php
            endif;

            if ($field == 'region_id'):
                ?>
                <tr>
                    <th><?php echo $object->getTranslate($field); ?>
                    </th>
                    <td><?= $object->region_id ? $object->getRegion()->caption : '' ?>
                    </td>
                </tr>
                <?php
            endif;


        endforeach; ?>
        <tr class="info">
            <th>��������</th>
            <td>
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                    <tr>
                        <td>�����</td>
                        <td>����</td>
                        <td>��������</td>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($object->getDogovors() as $dogovor): ?>
                        <tr>
                            <td><?= $dogovor->number; ?></td>
                            <td><?= $C->date($dogovor->date); ?></td>
                            <td><?= $dogovor->caption; ?></td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td>

        </tr>

    </table>


<? /* <tr>
        <th>��������</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>����������� �������� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>������</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>�������� ����� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>����������� ����� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>�������</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>����</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>Email</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>�������������� �������� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>������������� �� ���������� ������������ </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>����</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>�����</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>��������� ���� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>������� ���� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>����������������� ���� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>���</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>���</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>���</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>��� �� ����� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>��� �� ���� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>�����������</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>������</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>������</th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>�������� �� �������� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
     <tr>
        <th>��������� ��������� ���� � ����� ������������ ����� 15 ���� </th>
        <td><?= $object->user_login; ?></td>
    </tr>
*/ ?>