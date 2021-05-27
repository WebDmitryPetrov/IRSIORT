<?php
/** @var Act $item */
$signings = ActSignings::get4VidachaCert();
?>
<tr>
    <td><?= $item->number ?></td>
    <td><?= $C->date($item->created) ?></td>


    <td><?php echo $C->date($item->testing_date) ?>
    </td>
    <td>   <?php if (strlen($item->invoice)):  echo $item->invoice_index . '/' . $item->invoice ?>
            <br><?php echo $C->date($item->invoice_date); endif; ?>
    </td>
    <td><?php echo $item->paid ? '��' : '���'; ?>
    </td>

    <td class="wrap"><?php echo $item->comment ?>
    </td>
    <td class="button-width-50">
        <?php if (!$item->isBlocked() || $item->isCanEdit()): ?>

            <a class="btn btn-info"
               href="index.php?action=act_received_view&id=<?php echo $item->id; ?>">��������</a>
            <div></div>
            <?php if ($C->userHasRole(Roles::ROLE_CENTER_PRINT) || $C->userHasRole(Roles::ROLE_CENTER_FOR_PRINT)): ?>
                <a
                    class="btn btn-primary "
                    href="index.php?action=act_receive_numbers&id=<?php echo $item->id; ?>">������ ������������
                    (�������)</a> <div></div>
            <? endif; ?>
            <?php if ($C->userHasRole(Roles::ROLE_CENTER_PRINT)): ?>
                <div class="btn-group">
                    <a
                        class="btn btn-primary  dropdown-toggle" data-toggle="dropdown"
                        href="#">������ ��������� ������
                        ������������ <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($signings as $sign): ?>
                            <li><a target="_blank"
                                   href="index.php?action=act_vidacha_cert&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                            </li>
                        <?php endforeach; ?>
                        <ul>
                </div>
                <div></div>
                <div class="btn-group">
                    <a
                        class="btn btn-primary  dropdown-toggle" data-toggle="dropdown"
                        href="#">������ ��������� ������
                        ������� <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($signings as $sign): ?>
                            <li><a target="_blank"
                                   href="index.php?action=act_vidacha_note&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                            </li>
                        <?php endforeach; ?>
                        <ul>
                </div>
                <div></div>
                <div class="btn-group">
                    <a
                        class="btn btn-primary  dropdown-toggle" data-toggle="dropdown"
                        href="#">������ ������� ������ ������������ <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($signings as $sign): ?>
                            <li><a target="_blank"
                                   href="index.php?action=act_vidacha_reestr&id=<?php echo $item->id; ?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                            </li>
                        <?php endforeach; ?>
                        <ul>
                </div>

            <? endif ?>

            <?php if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED) && !strlen($item->invoice)): ?>

                <div></div> <a data-id="<?php echo $item->id; ?>"
                   class="btn invoice btn-warning new"
                   href="#">������ ����</a>
            <?php
            endif;
            if (strlen($item->invoice)): ?>
                <div></div>
                <a data-id="<?php echo $item->id; ?>"
                   class="btn invoice btn-warning" target="_blank"
                   href="index.php?action=print_invoice&id=<?php echo $item->id; ?>">������ ����</a>
            <?php endif; ?>

            <?php if ($C->userHasRole(Roles::ROLE_CENTER_WAIT_PAYMENT) && strlen($item->invoice) && !$item->paid): ?>
                <div></div> <a
                    class="btn btn-success  payd"
                    data-id="<?php echo $item->id; ?>"
                    data-money="<?php echo $item->amount_contributions; ?>" href="#">
                    ��������</a>
            <? endif; ?>

            <?php if ($item->paid): ?>
                <div></div> <a
                    class="btn btn-danger" onclick="return confirm('�� �������?');"
                    href="index.php?action=set_archive&id=<?php echo $item->id; ?>">
                    � �����</a>
            <? endif; ?>
            <? if ($item->isBlocked()): ?>
                <div></div>  <a
                    class="btn btn-danger"
                    href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                    ��������������</a>
            <? else: ?>
                <div></div> <a
                    class="btn btn-danger"
                    href="index.php?action=act_set_blocked&id=<?php echo $item->id; ?>">
                    �������������</a>
            <? endif; ?>
        <? else: ?>
            <? if ($item->isCanUnBlock()): ?>
                <div></div> <a
                    class="btn btn-danger"
                    href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                    ��������������</a>
            <? else: ?>
                <div></div><a
                    class="btn disabled">

                    ��� ������������</a>
            <?endif; ?>
        <? endif; ?>
    </td>
</tr>