<style>
    .success{color:green}
    .error{color:red}
</style>
<h1>��������� �������� ������� ������������ ����� �� <?=(!empty($pfur))?"����":""?></h1>
<br>
<a href="?action=<?=(!empty($pfur))?'move_certificates_pfur_hc':'move_certificates_all_hc'?>" style="font-size: 18px"><< �����</a>
<br>
<h4>��:
<?=HeadCenter::getByID($from)->short_name?><br>
�:
<?=HeadCenter::getByID($to)->short_name?><br>
��:
<?=TestLevelType::getByID($level_type)->caption?><br>
</h4>

<?php
$num=(!empty($result['success']))?count($result['success']):0;
echo '<h3 class="success">������� ���������� ������ ('. $num .'):</h3>';
if (!empty($result['success']))
{
    echo '<span class="success">'.implode(', ', $result['success']).'</span>';
}

$num=(!empty($result['error']))?count($result['error']):0;
echo '<h3 class="error">�� ���������� ������ ('. $num .'):</h3>';
if (!empty($result['error'])) {
    echo '<span class="error">';
    foreach ($result['error'] as $item) {
        echo $item . ' (' . implode(', ', \SDT\models\Certificate\CertificateReserved::checkCert($from, $level_type, $item)) . ')<br>';
    }
}

