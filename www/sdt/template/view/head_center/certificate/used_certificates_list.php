<?php
/**
 * Created by JetBrains PhpStorm.
 * User: LWR
 * Date: 09.09.15
 * Time: 16:55
 * To change this template use File | Settings | File Templates.
 */



$echo='';

if (!empty($search))
{

    if (!empty($array))
    {
        /*$echo.='<h3>������ ������� c '.$from .' �� '.$to.'
        <br>
        '.TestLevelType::getByID($test_level_type)->caption.'
        <br>
        ('.count($array).' ��.):</h3>';*/

        if ($test_level_type == 2) $test_level_type_name = '�������������� �������';
        else $test_level_type_name = '���';

        foreach ($array as $item)
        {
            $echo.= $item['number']."\r\n";
        }

        header('Content-type: text/plain; charset=UTF-8');
        header('Content-Disposition: attachment; filename="c '.$from .' �� '.$to.' ('.count($array).' ��.) - '.$test_level_type_name.'.txt"');
        echo $echo;
        die();

    }
    else $echo.= '�� ��������� ������ ������� �� ����������';

}











echo '<h1>'.$caption.'</h1>';

if (empty($to)) $to = date('d.m.Y');
?>
<form action="" method="post">
<label>�� :
    <div class="input-prepend date datepicker "
         data-date="">
        <span class="add-on"><i class="icon-th"></i> </span> <input
            class="input-small"
            name="from"

            readonly="readonly" size="16" type="text"
            value="<?= $from ?>">
    </div>
</label> <label>�� :
    <div class="input-prepend date datepicker "
         data-date="">
        <span class="add-on"><i class="icon-th"></i> </span> <input
            class="input-small"
            name="to"

            readonly="readonly" size="16" type="text"
            value="<?= $to ?>">
    </div>
</label>
<label>

    ��� ������������ :
    <div>
    <select style="width:100%" name="test_level_type">
       <?
        foreach (TestLevelTypes::getAll() as $level)
            {
                if ($test_level_type == $level->id ) $selected='selected="selected"';
                else $selected='';
                echo '<option value="'.$level->id.'" '.$selected.' >'.$level->caption.'</option>';
            }
        ?>

    </select>
    </div>
</label>

    <input type="submit" value="������������ ������">
</form>
<?

echo $echo;

//die(var_dump($array));
