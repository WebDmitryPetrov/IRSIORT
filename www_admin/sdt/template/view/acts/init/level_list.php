<h1>������ �������������� �������� ������ </h1>
<h3>����� <?=count($list)?></h3>

<table class="table table-bordered  table-striped">
    <thead>
    <tr>
        <th valign="top"><nobr>�</nobr></th>
        <th valign="top">���� ��������</th>

        <th valign="top">���� ������������</th>
       <!-- <th valign="top">����� �����</th>
        <th valign="top">���� �����</th>
        -->
        <th valign="top">�����������</th>

        <th valign="top">�����������</th>
        <th valign="top">&nbsp;</th>
    </tr>
    </thead>
    <tbody>


	<?php

foreach($list as $item): ?>
	<tr class="<? if($item->is_changed_checker):?>error<?endif;?>">
        <td><?=$item->number?></td>
        <td><?=$C->date($item->created)?></td>


        <td><?php echo $C->date($item->testing_date) ?>
        </td>

        <td  class="wrap"><?php echo $item->responsible ?>
        </td>

        <td  class="wrap"><?php echo $item->comment ?>
        </td>
		<td><a class="btn btn-info btn-mini btn-width-medium"
			href="index.php?action=act_fs_view&id=<?php echo $item->id; ?>">��������</a>
            <div class="btn-group btn-width-medium">
                <button class="btn dropdown-toggle btn-mini btn-warning" data-toggle="dropdown">
                    �������������
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                   <li> <a class=""
                       href="index.php?action=act_fs_edit&id=<?php echo $item->id; ?>">���</a>
                   </li>
                    <li><a class=""
                           href="index.php?action=act_table&id=<?php echo $item->id; ?>"
                           target="_blank">������� �������</a></li>
                </ul>
            </div>    <br>


            <?php if ($item->checkPassport()): ?>
			<a  onclick="return confirm('��������� ��������� �������� ������ �� �������� � �������� �����.' +
			 '\n�� �������?');"
                class="btn btn-success btn-mini payd btn-width-medium"
		 href="index.php?action=act_send&id=<?php echo $item->id; ?>" >��������� �� ��������</a>
            <?php else: ?>
                <a  onclick="alert('���������� ��������� ��� ����� ���������, ��������� ���� &quot;�������&quot; � ������ ����� ���� ������������������'); return false"
                    class="btn btn-success btn-mini payd btn-width-medium disabled"
                    href="" >��������� �� ��������</a>
        <?php endif;?>
			<a href="index.php?action=act_invalid&id=<?php echo $item->id; ?>" onclick="return confirm('�� �������?');" class="btn btn-danger  btn-width-medium btn-mini">��������</a>
		</td>
	</tr>

	<?php  endforeach;?>
</tbody>
</table>
