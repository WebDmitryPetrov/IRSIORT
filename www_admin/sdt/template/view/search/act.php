<?
$signings = ActSignings::get4VidachaCertAll();

//var_dump($signings[1]);die;
?>
<form class="form-inline" method="post"
      action="<?php echo $_SERVER['REQUEST_URI']?>">
    <table class=" ">
        <tbody>
        <tr>
            <td colspan="4">
                <div align="center">���� ��������</div>
            </td>
            <td style="width: 20px;">&nbsp;</td>
            <td colspan="4">
                <div align="center">���� ������������</div>
            </td>
        </tr>
        <tr>
            <td>
                <div align="right"><label for="minAddDate">��:</label></div>
            </td>
            <td>
                <div align="left">
                    <div class="input-prepend date datepicker" id="div_add_from"
                         data-date="<?php echo $C->date($query['minAddDate'])?>"
                        >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="minAddDate" id="minAddDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php  echo $C->date($query['minAddDate'])?>">
                    </div>

                    <!--<input type="text" name="add_from" id="add_from"/>-->
                </div>
            </td>
            <td>
                <div align="right"><label for="maxAddDate">&nbsp;��:</label></div>
            </td>
            <td>
                <div align="left">
                    <div class="input-prepend date datepicker" id="div_add_to"
                         data-date="<?php echo $C->date($query['maxAddDate'])?>"
                        >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="maxAddDate" id="maxAddDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php  echo $C->date($query['maxAddDate'])?>">
                    </div>

                </div>
            </td>
            <td>&nbsp;</td>
            <td>
                <div align="right"><label for="minTestDate">��:</label></div>
            </td>
            <td>
                <div align="left">
                    <div class="input-prepend date datepicker" id="div_test_from"
                         data-date="<?php echo $C->date($query['minTestDate'])?>"
                        >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="minTestDate" id="minTestDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php  echo $C->date($query['minTestDate'])?>">
                    </div>

                </div>
            </td>
            <td>
                <div align="right"><label for="maxTestDate">&nbsp;��:</label></div>
            </td>
            <td>
                <div align="left">
                    <div class="input-prepend date datepicker" id="div_test_to"
                         data-date="<?php echo $C->date($query['maxTestDate'])?>"
                        >
                        <span class="add-on"><i class="icon-th"></i> </span> <input
                            class="input-small" name="maxTestDate" id="maxTestDate"
                            readonly="readonly" size="16" type="text"
                            value="<?php  echo $C->date($query['maxTestDate'])?>">
                    </div>

                </div>
            </td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td >
                <div align="right"><label for="act_id">id ����</label></div>
            </td>
            <td width="90px">
                <input
                    class="input-small" name="act_id" id="act_id"
                    size="16" type="text"
                    value="<?php  echo $query['act_id']?>">
            </td>
            <td >
                <div align="right"><label for="organization">�����������</label></div>
            </td>
            <td colspan="2"><select name="organization" id="organization" class="input-medium">
                    <option>�� �������</option>
                    <?php foreach ($Universities as $univer): ?>
                        <option <?php if ($query['organization'] == $univer->id): ?> selected="selected" <?php endif; ?>
                            value="<?= $univer->id ?>"><?=$univer->short_name?$univer->short_name:$univer->name;?></option>
                    <?php endforeach; ?>
                </select></td>
            <td>&nbsp;</td>
            <td colspan="2">
                <div align="right"><label for="level">������� ������������</label></div>
            </td>
            <td colspan="2"><select name="level" id="level" class="input-medium">
                    <option>�� �������</option>
                    <?php foreach ($Levels as $univer): ?>
                        <option  <?php if ($query['level'] == $univer->id): ?>  selected="selected" <?php endif; ?>
                            value="<?= $univer->id ?>"><?=$univer?></option>
                    <?php endforeach; ?>
                </select></td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr><td colspan="3">
                ������<select name="state">
                    <option value=1 <?php if ($query['state'] == 1): ?>  selected="selected" <?php endif; ?>>� ������</option>
                    <option value=2 <?php if ($query['state'] == 2): ?>  selected="selected" <?php endif; ?>>� ������</option>
                    <option value=3 <?php if ($query['state'] == 3): ?>  selected="selected" <?php endif; ?>>���</option>
                </select>
        </td> <td >
                <div align="right"><label for="act_id">����� ����</label></div>
            </td>
            <td width="90px">
                <input
                        class="input-small" name="act_num" id="act_num"
                        size="16" type="text"
                        value="<?php  echo $query['act_num']?>">
            </td>
           </tr>

        </tbody>
    </table><br>
    <input type="submit" value="������" class="btn btn-primary btn-large pull-right">
</form>
<? if(is_null($Result)):?>
    <h3>������ �� �������</h3>
<? endif?>
<?php
if (is_array($Result) && count($Result)) :  ?>
    <table class="table table-bordered  table-striped">
        <thead>
        <tr>
            <th valign="top">
                <nobr>�</nobr>
            </th>
            <th valign="top">���� �������� ����</th>
            <th valign="top">�����������</th>
            <th valign="top">���� ������������</th>
            <th valign="top">C���<br>�����/����</th>
            <th valign="top">��������</th>


            <th valign="top">�����������</th>
            <th valign="top">&nbsp;</th>
        </tr>
        </thead>
        <tbody>


        <?php

        foreach ($Result as $item):
            $head_id=$item->getUniversity()->head_id;
            //var_dump($head_id);die;
            /** @var Act $item */
            ?>
            <tr>
                <td><?=$item->number?></td>
                <td><?=$C->date($item->created)?></td>

                <td class="wrap"><?php echo $item->getUniversity().'<br>('.HeadCenter::getNameByActID($item->id).')'; ?>
                </td>
                <td><?php echo $C->date($item->testing_date) ?>
                </td>
                <td>   <?php if (strlen($item->invoice)):  echo $item->invoice ?><br><?php echo $C->date($item->invoice_date); endif; ?>
                </td>
                <td><?php echo $item->paid ? '��' : '���'; ?>
                </td>

                <td class="wrap"><?php echo $item->comment ?>
                </td>
               <!-- <td>

                    <a class="btn btn-info"
                       href="index.php?action=act_received_view&id=<?php echo $item->id; ?>">��������</a>
                    <br>


                    <a
                        class="btn btn-primary "
                        href="index.php?action=act_archive_numbers&id=<?php echo $item->id; ?>&h_id=<?php echo $head_id;?>">������ ������������
                        (�������)</a> <br>
                    <a
                        class="btn btn-danger "
                        href="index.php?action=act_vidacha_cert&id=<?php echo $item->id; ?>&h_id=<?php echo $head_id;?>">������ ��������� ������
                        ������������</a>
                    <? if (strlen($item->invoice)): ?>
                        <a data-id="<?php echo $item->id; ?>"
                           class="btn invoice btn-warning" target="_blank"
                           href="index.php?action=print_invoice&id=<?php echo $item->id; ?>">������ ����</a>
                    <?php endif; ?>

                    <? if ($item->isBlocked() && $item->isCanUnBlock()): ?>
                        <a
                            class="btn btn-danger"
                            href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                            ��������������</a>
                    <?php endif; ?>



                </td>-->



                <td class="button-width-50">

                    <?php if ($C->userHasRole(Roles::ROLE_ROOT)):?>
                        <a
                            class="btn btn-inverse" onclick="return confirm('�� �������, ��� ������ ������� �������� ����������������?\n(������� ����� ����������)');"
                            href="index.php?action=sess_invalid&id=<?php echo $item->id; ?>">
                            ���������������</a><br>
                    <? endif; ?>

                    <a class="btn btn-info"
                       href="index.php?action=act_received_view&id=<?php echo $item->id; ?>">��������</a>
                    <br>

                    <a
                        class="btn btn-primary "
                        href="index.php?action=act_archive_numbers&id=<?php echo $item->id; ?>&h_id=<?php echo $head_id;?>">������ ������������
                        (�������)</a> <br>

                    <div class="btn-group">
                        <a
                            class="btn btn-primary  dropdown-toggle" data-toggle="dropdown"
                            href="#">������ ��������� ������
                            ������������ <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach ($signings[$head_id] as $sign): ?>
                                <li><a target="_blank"
                                       href="index.php?action=act_vidacha_cert&id=<?php echo $item->id; ?>&h_id=<?php echo $head_id;?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
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
                            <?php foreach ($signings[$head_id] as $sign): ?>
                                <li><a target="_blank"
                                       href="index.php?action=act_vidacha_note&id=<?php echo $item->id; ?>&h_id=<?php echo $head_id;?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
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
                            <?php foreach ($signings[$head_id] as $sign): ?>
                                <li><a target="_blank"
                                       href="index.php?action=act_vidacha_reestr&id=<?php echo $item->id; ?>&h_id=<?php echo $head_id;?>&type=<?= $sign->id ?>"><?= $sign->caption ?></a>
                                </li>
                            <?php endforeach; ?>
                            <ul>
                    </div>


                    <? if (strlen($item->invoice)): ?>
                    <a data-id="<?php echo $item->id; ?>"
                       class="btn invoice btn-warning" target="_blank"
                       href="index.php?action=print_invoice&id=<?php echo $item->id; ?>">������ �����</a>
                    <?php endif; ?>

                    <? if ($item->isBlocked() && $item->isCanUnBlock()): ?>
                        <a
                            class="btn btn-danger"
                            href="index.php?action=act_set_unblocked&id=<?php echo $item->id; ?>">
                            ��������������</a>
                    <?php endif; ?>

                </td>


            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
<?
endif;

?>


