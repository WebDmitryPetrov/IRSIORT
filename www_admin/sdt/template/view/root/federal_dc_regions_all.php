<a class="btn btn-info" href="?action=federal_dc">�����</a>

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 25.08.15
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */
echo '<h1>'.$caption.'</h1>';
?>
<table class="table table-bordered  table-striped">
    <tr>

        <th>������</th>
        <th>����������� �����</th>
    </tr>
    <? foreach($result as $item)
{
    if (empty($item['fcap'])) $fcap='<span style="color:green">�� ������ ��</span>';
    else $fcap=$item['fcap'];
echo '<tr><td>'.$item['rcap'].'</td><td>'.$fcap.'</td></tr>';

}
?>
    </table>