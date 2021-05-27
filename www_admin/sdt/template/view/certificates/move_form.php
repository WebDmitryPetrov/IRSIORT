<style>
    .success{color:green}
    .error{color:red}
</style>

<h1>������� ������� ������������ ����� �� <?=(!empty($pfur))?"����":""?></h1>
<br>
<form action="" method="post">



    <label>�������� ����� �� �������� �������:
        <div>

            <select name="from" id="hc-list" style="width:400px">

                <?
                foreach (HeadCenters::getAll() as $item) {
                    /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                          continue;
                      }*/

                    if (!empty($pfur) && $item->horg_id != 1) {
                        continue;
                    }
                    if ($item->id == $from) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }

                    $name = $item->name;
                    $short_name = $item->short_name;

                    echo '<option value=' . $item->id . ' ' . $selected . '>' . $short_name.' | '.$name . '</option>';
                }
                ?>
            </select>
        </div>
    </label>

    <label>�������� ����� � ������� �������:
        <div>

            <select name="to" id="hc-list" style="width:400px">

                <?
                foreach (HeadCenters::getAll() as $item) {
                    /*  if ($item->id != 1 && $item->id != 2 && $item->id != 7 && $item->id != 8) {
                          continue;
                      }*/
                    if (!empty($pfur) && $item->horg_id != 1) {
                        continue;
                    }
                    if ($item->id == $to) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }

                    $name = $item->name;
                    $short_name = $item->short_name;

                    echo '<option value=' . $item->id . ' ' . $selected . '>' . $short_name.' | '.$name . '</option>';
                }
                ?>
            </select>
        </div>
    </label>
    <label>��� ������������:
        <div>
        <select name="level_type" style="width: 400px">
            <?
            foreach (TestLevelTypes::getAll() as $item) {

                if (empty($level_type) && $item->id == 2){
                    $selected = 'selected="selected"';
                }
                elseif ($item->id == $level_type) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }


                echo '<option value=' . $item->id . ' ' . $selected . '>' . $item->caption . '</option>';
            }
            ?>
        </select>
        </div>
    </label>

    <label>������ ��������� (������ ����� ���������) �������:
        <div>
            <input type="text" name="start" value="<?=$start;?>">
        </div>
    </label>

    <label>����� ��������� (��������� ����� ���������) �������:
        <div>
            <input type="text" name="end" value="<?=$end;?>">
        </div>
    </label>


    <input type="submit" value="���������">
</form>

<?php
if ($from == $to && !empty($from)) echo '<h3 class="error">������ ������� ���� � ��� �� �� ��� ��������!</h3>';
elseif ((empty($start) || empty($end)) && !empty($from)) echo '<h3 class="error">������ ��������� ����������!</h3>';
elseif (!empty($result)){

    $num=(!empty($result['success']))?count($result['success']):0;
    echo '<h3 class="success">������, ������� ����� ���� ���������� ('. $num .'):</h3>';
    if (!empty($result['success']))
    {
        echo '<span class="success">'.implode(', ', $result['success']).'</span>';
        echo '<form action="?action=save_moved_certificates" method="post">
                <input type="hidden" name="from" value="'.$from.'">
                <input type="hidden" name="to" value="'.$to.'">
                <input type="hidden" name="level_type" value="'.$level_type.'">
                <input type="hidden" name="to_send" value="'.implode(',',$result['success']).'">
                <input type="hidden" name="pfur" value="'.$pfur.'">
                <input type="submit" value="��������� ��������� ������" onclick="return(confirm(\'�� �������?\'))">';
    }

    $num=(!empty($result['error']))?count($result['error']):0;
    echo '<h3 class="error">������, ������� �� ����� ���� ���������� ('. $num .'):</h3>';
    if (!empty($result['error'])) {
        echo '<span class="error">';
        foreach ($result['error'] as $item) {
            echo $item . ' (' . implode(', ', \SDT\models\Certificate\CertificateReserved::checkCert($from, $level_type, $item)) . ')<br>';
        }
    }
}