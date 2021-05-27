<?php
/** @var University $univer */
$university = $univer;
$api_enabled = $university->api_enabled;
?>
<h1>������ ���������� �� �������� ���������� ������: <?= $univer->name ?></h1>
<? if ($univer->parent_id): ?>
    <h2>������: <?= $univer->getParent()->name ?></h2>
<? endif ?>
<h3>����� <?= count($list) ?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top">
            <nobr>�</nobr>
        </th>
        <th valign="top">���� �������� ����</th>
        <th valign="top">�����������</th>
        <th valign="top">���� ������������</th>
        <!-- <th valign="top">����� �����</th>
         <th valign="top">���� �����</th>
         -->
        <th valign="top">C���<br>�����/����</th>
        <th valign="top">�����������</th>

        <th valign="top">�����������</th>
        <th valign="top">&nbsp;</th>
    </tr>
    </thead>
    <tbody>


    <?php

    foreach ($list as $item):
        /** @var Act $item */
        $meta = $item->getMeta();
        ?>

        <tr class="
         <? if ($meta->test_group == 2) {
            echo 'warning';
        } ?>
            <? if (!$item->viewed): ?>info<? endif; ?>

">
            <td><?= $item->number ?></td>
            <td><?= $C->date($item->created) ?></td>

            <td class="wrap"><?php echo $item->getUniversity() ?>
                <? if ($meta->test_group == 2):?><p class="text-error">���� � ������������� �������������</p><? endif ?>
            </td>
            <td><?php echo $C->date($item->testing_date) ?>
            </td>
            <td>
                <? if ($item->invoicePrinted()):
                    echo $item->invoice_index . '/' . $item->invoice ?>
                    <br><?= $C->date($item->invoice_date) ?>  <br>
                    <strong> <?= $item->amount_contributions ?> ���.</strong>
                <? endif ?>
            </td>
            <td class="wrap"><?php echo $item->responsible ?>
            </td>

            <td class="wrap text-error"><?php echo $item->comment ?>
            </td>
            <td>
                <?php if (!$item->isBlocked() || $item->isCanEdit()): ?>
                    <a class="btn btn-info btn-mini   btn-block"
                       href="index.php?action=act_second_view&id=<?php echo $item->id; ?>">��������</a>
                    <div class="btn-group btn-block">
                        <button class="btn dropdown-toggle btn-mini btn-warning  btn-block" data-toggle="dropdown">
                            <nobr>�������������
                                <span class="caret"></span></nobr>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class=""
                                   href="index.php?action=act_second_edit&id=<?php echo $item->id; ?>">���</a>
                            </li>
                            <li><a class=""
                                   href="index.php?action=act_table_second&id=<?php echo $item->id; ?>"
                                   target="_blank">������� �������</a></li>
                        </ul>
                    </div>


                    <?php
                    /*
                    if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED) && !strlen(
                            $item->invoice
                        ) && empty($api_enabled)
                    ):
                        $checkDate = null;
                        if ($item->getUniversity()->getHeadCenter()->horg_id == 1) {
                            $checkDate = $item->check_date;
                        }
//                $checkDate = null;
                        ?>

                        <div></div> <a data-id="<?php echo $item->id; ?>"
                                       data-date="<?= $item->getPrintDateAfterCheckDate() ?>"
                                       data-level_id="<?= $item->test_level_type_id ?>"
                                       data-check_date="<?= $checkDate ?>"
                                       class="btn invoice btn-warning new btn-mini  btn-block print_docs_<?= $item->id; ?>"

                                       href="#">������ �����</a>
                    <?php
                    endif;
                    if ($C->userHasRole(Roles::ROLE_CENTER_RECEIVED) && strlen($item->invoice) && empty($api_enabled)): ?>
                        <div></div>
                        <a data-id="<?php echo $item->id; ?>"
                           class="btn invoice btn-warning btn-mini btn-block print_docs_<?= $item->id; ?>"
                             target="_blank"
                           href="index.php?action=print_invoice&id=<?php echo $item->id; ?>">������ �����</a>
                    <?php endif;
                    */
                    ?>


                    <? if ($item->canBeChecked()): ?>
                        <a onclick="return confirm('�� �������?');"
                           class="btn btn-success btn-mini btn-block"
                           href="index.php?action=act_checked&id=<?php echo $item->id; ?>">���������</a>
                    <? else: ?>
                        <button onclick="alert('���������� ��������� ����������� ������������� ������������ ���� ������� �� ����������� ������')"
                                class="btn btn-success btn-mini   btn-block "
                        >���������
                        </button>
                    <? endif ?>
                    <? if ($item->haveAdditionalExam()): ?>
                        <a
                                class="btn btn-primary btn-mini   btn-block"
                                href="index.php?action=oncheck_additional_exam&act_id=<?php echo $item->id; ?>">���
                            �����������</a>
                    <? endif ?>
                    <a onclick="return confirm('�� �������?');"
                       class="btn btn-danger btn-mini btn-block"
                       href="index.php?action=act_return_work&id=<?php echo $item->id; ?>">�� ���������</a>
                    <? if ($item->isBlocked()): ?>
                        <a
                                class="btn btn-danger btn-mini   btn-block"
                                href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                            ��������������</a>
                    <? else: ?>
                        <a
                                class="btn btn-danger btn-mini   btn-block"
                                href="index.php?action=act_set_blocked&id=<?php echo $item->id; ?>">
                            �������������</a>
                    <? endif; ?>
                <? else: ?>
                    <? if ($item->isCanUnBlock()): ?>
                        <a
                                class="btn btn-danger btn-mini   btn-block"
                                href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                            ��������������</a>
                    <? else: ?>
                        <a
                                class="btn disabled btn-mini   btn-block">

                            ��� ������������</a>
                    <? endif; ?>
                <? endif; ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>
<?php

echo $this->import('acts/act_table_popups',array('signings'=>$signings,'university'=>$university));